<?php
namespace App\Http\Controllers\Mobiles;

use App\Models\UAccount;
use App\Models\UAccountLogin;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    const LOTTO_AUTH_TOKEN = "LOTTO_AUTH_TOKEN";

    const LOTTO_AUTH_TOKEN_EXPIRED = 3;

    public function __construct()
    {
//        $this->middleware('auth')->only('changePassword');
    }

    /**
     * 登录页面
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $target = $request->input('target', url()->previous());
        if (empty($target)) {
            $target = '/';
        }
        if (isset($request->_login)) {
            return redirect($target);
        }
        $domain = parse_url($request->fullUrl(), PHP_URL_HOST);
        $platform = $this->loginPlatform($request);
        $result = [];
        $result["target"] = $target;
        if ($request->isMethod('post')) {
            $phone = $request->input("phone");
            $password = $request->input('password');
            if (empty($phone) || empty($password)) {//数据验证
                $result['error'] = '账号或者密码不能为空!';
            } else {
                $account = UAccount::query()->where("phone", $phone)->first();
                if (empty($account) || $account->password != sha1($account->salt . $password)) {
                    $result['error'] = '账号或者密码错误!';
                } else {
                    $token = UAccountLogin::generateToken();
                    $al = new UAccountLogin();
                    $al->token = $token;
                    $al->account_id = $account->id;
                    $al->platform = $platform;
                    $al->domain = $domain;
                    $al->expired_at = date_create(self::LOTTO_AUTH_TOKEN_EXPIRED . ' days');
                    $al->status = 1;
                    if ($al->save()) {
                        $logins = UAccountLogin::query()
                            ->where(['account_id' => $account->id, 'status' => 1, 'platform' => $platform])
                            ->where('token', '<>', $token)
                            ->get();
                        if (!empty($logins)) {
                            foreach ($logins as $login) {
                                $login->status = 0;
                                $login->save();
                            }
                        }
                        $c = cookie(self::LOTTO_AUTH_TOKEN, $token, 60 * 24 * self::LOTTO_AUTH_TOKEN_EXPIRED);
                        return response()->redirectTo($target)->withCookies([$c]);
                    } else {
                        $result['error'] = '服务器异常，请稍后重试！';
                    }
                }
            }
            $result["phone"] = $phone;
        }
        return view('mobiles.login', $result);
    }

    public function changePassword(Request $request)
    {
        $login = session('_login');
        $result = [];
        if ($request->isMethod('post')) {
            $password = $request->input('password');
            if (empty($password)) {
                $result['error'] = '密码不能为空！';
            }
            if ($login->account->change_password == 0) {
                $login->account->password = sha1($login->account->salt . $password);
                $login->account->change_password = 1;
            } else {
                $password_old = $request->input('password_old');
                if (empty($password_old)) {
                    $result['error'] = '旧密码不能为空！';
                } elseif ($login->account->password != sha1($login->account->salt . $password_old)) {
                    $result['error'] = '密码错误';
                } else {
                    $login->account->password = sha1($login->account->salt . $password);
                }
            }
            $login->account->save();
        }
        return view('mobiles.change_password', $result);
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