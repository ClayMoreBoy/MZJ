<?php
namespace App\Http\Controllers\Mobiles;

use App\Models\LModel;
use App\Models\UAccount;
use App\Models\UAccountLogin;
use App\Models\UAccountWithdraw;
use App\Models\UOrder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{

    const LOTTO_AUTH_TOKEN = "LOTTO_AUTH_TOKEN";

    const LOTTO_AUTH_TOKEN_EXPIRED = 7;

    public function __construct()
    {
        $this->middleware('auth')->only(['changePassword', 'bills']);
        $this->middleware('auth_api')->only(['balance']);
    }

    public function login(Request $request)
    {
        $phone = $request->input("phone");
        $target = $request->input('target', '/mobiles/');
        $result = ['target' => $target, 'phone' => $phone];
        if ($request->isMethod('post')) {
            $password = $request->input('password');
            if (empty($phone) || empty($password)) {//数据验证
                $result['error'] = '账号或者密码不能为空!';
            } else {
                $account = UAccount::query()->where("phone", $phone)->first();
                if (empty($account) || $account->password != sha1($password . $account->salt)) {
                    $result['error'] = '账号或者密码错误!';
                } else {
                    $token = UAccountLogin::generateToken();
                    $al = new UAccountLogin();
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
                        session(['_login' => $al]);
                        if ($account->change_password == 0) {
                            return redirect('/mobiles/change-password/?target=/mobiles/auth/');
                        }
                        $logins = UAccountLogin::query()
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
        return view('mobiles.login', $result);
    }

    public function changePassword(Request $request)
    {
        $login = session('_login');
        $target = $request->input('target', '/mobiles/');
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
        return view('mobiles.change_password', $result);
    }

    public function logout(Request $request)
    {
        $c = cookie(self::LOTTO_AUTH_TOKEN, null);
        $request->session()->remove('_login');
        return back()->withCookies([$c]);
    }

    public function withdraw(Request $request)
    {
        $login = session('_login');
        $result = [];
        if ($request->isMethod('post')) {
            if (!$request->has('fee')) {
                $result['error'] = '提现金额不能为空';
            } elseif ($request->fee <= 0 || $request->fee > $login->account->balance) {
                $result['error'] = '无效的提现金额';
            } else {
                $withdraw = new UAccountWithdraw();
                $withdraw->account_id = $login->account->id;
                $withdraw->merchant_id = $login->account->merchant_id;
                $withdraw->agent_id = $login->account->agent_id;
                $withdraw->fee = $request->fee;
                $withdraw->status = UAccountWithdraw::k_status_waiting;
                DB::beginTransaction();
                if ($withdraw->save()) {
                    $login->account->balance = $login->account->balance - $request->fee;
                    if ($login->account->save()) {
                        View::share('account', $login->account);
                        DB::commit();
                    } else {
                        $result['error'] = '服务器异常，请稍后重试';
                        DB::rollBack();
                    }
                } else {
                    $result['error'] = '服务器异常，请稍后重试';
                    DB::rollBack();
                }
            }
        }
        $withdraws = $login->account->withdraws()->orderBy('created_at', 'desc')->paginate(20);
        $result['withdraws'] = $withdraws;
        return view('mobiles.withdraw', $result);
    }

    public function bills(Request $request)
    {
        $login = session('_login');
        $bills = $login->account->bills()->orderBy('created_at', 'desc')->paginate(20);
        return view('mobiles.bills', ['bills' => $bills]);
    }

    public function balance(Request $request)
    {
        $login = session('_login');
        $account = UAccount::query()->find($login->account->id);
        $login->account = $account;
        session(['_login' => $login]);
        return response()->json([
            'code' => 0,
            'balance' => number_format($account->balance, 2, '.', ''),
            'new_order' => $account->orders()->where('status', UOrder::k_status_unknown)->count(),
        ]);
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
            return UAccountLogin::K_PLATFORM_WECHAT;
        }
        $keywords = ['iPhone', 'iPod', 'iPad', 'Android', 'IEMobile'];
        if (preg_match("/(" . implode('|', $keywords) . ")/i", $userAgent)) {
            return UAccountLogin::K_PLATFORM_WAP;
        }
        return UAccountLogin::K_PLATFORM_PC;
    }

}