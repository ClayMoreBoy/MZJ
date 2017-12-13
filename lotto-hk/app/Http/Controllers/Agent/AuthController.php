<?php
namespace App\Http\Controllers\Agent;

use App\Models\LModel;
use App\Models\UAccountWithdraw;
use App\Models\UAgentAccount;
use App\Models\UAgentAccountLogin;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{

    const LOTTO_AUTH_TOKEN = "LOTTO_AGENT_AUTH_TOKEN";

    const LOTTO_AUTH_TOKEN_EXPIRED = 7;

    public function __construct()
    {
//        $this->middleware('auth_api')->only(['balance']);
    }

    public function login(Request $request)
    {
        $phone = $request->input("phone");
        $target = $request->input('target', '/agent/');
        $result = ['target' => $target, 'phone' => $phone];
        if ($request->isMethod('post')) {
            $password = $request->input('password');
            if (empty($phone) || empty($password)) {//数据验证
                $result['error'] = '账号或者密码不能为空!';
            } else {
                $account = UAgentAccount::query()->where("phone", $phone)->first();
                if (empty($account) || $account->password != sha1($password . $account->salt)) {
                    $result['error'] = '账号或者密码错误!';
                } else {
                    $token = UAgentAccountLogin::generateToken();
                    $al = new UAgentAccountLogin();
                    $al->token = $token;
                    $al->account_id = $account->id;
                    $domain = parse_url($request->fullUrl(), PHP_URL_HOST);
                    $platform = $this->loginPlatform($request);
                    $al->platform = $platform;
                    $al->domain = $domain;
                    $al->expired_at = date_create(self::LOTTO_AUTH_TOKEN_EXPIRED . ' days');
                    $al->status = 1;
                    if ($al->save()) {
                        $account->last_access_at = date_create();
                        $account->save();
                        session(['_login_agent' => $al]);
                        if ($account->change_password == 0) {
                            return redirect('/agent/change-password/?target=/agent/auth/');
                        }
                        $logins = UAgentAccountLogin::query()
                            ->where(['account_id' => $account->id, 'status' => 1, 'platform' => $platform])
                            ->where('token', '<>', $token)
                            ->get();
                        foreach ($logins as $login) {
                            $login->status = LModel::k_status_invalid;
                            $login->save();
                        }
                        if ($request->input('keep_online', 'off') == 'on') {
                            $c = cookie(self::LOTTO_AUTH_TOKEN, $token, 60 * 24 * self::LOTTO_AUTH_TOKEN_EXPIRED);
                            return response()->redirectTo($target)->withCookies([$c]);
                        } else {
                            return response()->redirectTo($target);
                        }
                    } else {
                        $result['error'] = '服务器异常，请稍后重试！';
                    }
                }
            }
        }
        return view('agent.login', $result);
    }

    public function changePassword(Request $request)
    {
        $login = session('_login_agent');
        $target = $request->input('target', '/agent/');
        $result = ['account' => $login->account, 'target' => $target];
        if ($request->isMethod('post')) {
            $password = $request->input('password');
            if (empty($password)) {
                $result['error'] = '密码不能为空！';
            } else {
                if ($login->account->change_password == 0) {
                    $login->account->password = sha1($password . $login->account->salt);
                    $login->account->change_password = 1;
                    if ($login->account->save()) {
                        return redirect($target);
                    } else {
                        $result['error'] = '服务器异常，请稍后重试！';
                    }
                } else {
                    $password_old = $request->input('password_old');
                    if (empty($password_old)) {
                        $result['error'] = '旧密码不能为空！';
                    } elseif ($login->account->password != sha1($password_old . $login->account->salt)) {
                        $result['error'] = '密码错误';
                    } else {
                        $login->account->password = sha1($password . $login->account->salt);
                        if ($login->account->save()) {
                            return redirect($target);
                        } else {
                            $result['error'] = '服务器异常，请稍后重试！';
                        }
                    }
                }
            }
        }
        return view('agent.change_password', $result);
    }

    public function logout(Request $request)
    {
        $c = cookie(self::LOTTO_AUTH_TOKEN, null);
        $request->session()->remove('_login_agent');
        return back()->withCookies([$c]);
    }

    public function edit(Request $request)
    {
        return view('agent.agent_edit');
    }

    public function update(Request $request)
    {
        $login = session('_login_agent');
        $agent = $login->account;
        if ($request->has('key') && $request->has('value')) {
            switch ($request->input('key', '')) {
                case 'phone': {
                    if (!preg_match('/^1[34578]\d{9}$/', $request->value)) {
                        return json_encode(['code' => 401, 'message' => '无效的手机号码']);
                    }
                    $agent->phone = $request->value;
                    if ($agent->save()) {
                        return response()->json(['code' => 0, 'message' => 'OK']);
                    } else {
                        return json_encode(['code' => 500, 'message' => '服务器异常']);
                    }
                }
                case 'name': {
                    if (strlen($request->value) < 2) {
                        return response()->json(['code' => 401, 'message' => '名称不得少于2位']);
                    }
                    $agent->name = $request->value;
                    if ($agent->save()) {
                        return response()->json(['code' => 0, 'message' => 'OK']);
                    } else {
                        return response()->json(['code' => 500, 'message' => '服务器异常']);
                    }
                }
                case 'wx_account': {
                    $agent->wx_account = $request->value;
                    if ($agent->save()) {
                        return response()->json(['code' => 0, 'message' => 'OK']);
                    } else {
                        return response()->json(['code' => 500, 'message' => '服务器异常']);
                    }
                }
                case 'wx_qr': {
                    $agent->wx_qr = $request->value;
                    if ($agent->save()) {
                        return response()->json(['code' => 0, 'message' => 'OK']);
                    } else {
                        return response()->json(['code' => 500, 'message' => '服务器异常']);
                    }
                }
            }
        } else {
            return response()->json(['code' => 401, 'message' => '参数错误']);
        }
    }

    /**
     * 获取登录来源
     * @param Request $request
     * @return string
     */
    protected function loginPlatform(Request $request)
    {
        $userAgent = $request->header('user_agent', '');
        if (strpos($userAgent, 'MicroMessenger') !== false) {
            return UAgentAccountLogin::K_PLATFORM_WECHAT;
        }
        $keywords = ['iPhone', 'iPod', 'iPad', 'Android', 'IEMobile'];
        if (preg_match("/(" . implode('|', $keywords) . ")/i", $userAgent)) {
            return UAgentAccountLogin::K_PLATFORM_WAP;
        }
        return UAgentAccountLogin::K_PLATFORM_PC;
    }

}