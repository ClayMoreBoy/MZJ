<?php
namespace App\Http\Controllers\Merchant;

use App\Models\LModel;
use App\Models\UMerchantAccount;
use App\Models\UMerchantAccountLogin;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{

    const LOTTO_AUTH_TOKEN = "LOTTO_MERCHANT_AUTH_TOKEN";

    const LOTTO_AUTH_TOKEN_EXPIRED = 7;

    public function __construct()
    {
        $this->middleware('auth_api')->only(['balance']);
    }

    public function login(Request $request)
    {
        $phone = $request->input("phone");
        $target = $request->input('target', '/merchant/');
        $result = ['target' => $target, 'phone' => $phone];
        if ($request->isMethod('post')) {
            $password = $request->input('password');
            if (empty($phone) || empty($password)) {//数据验证
                $result['error'] = '账号或者密码不能为空!';
            } else {
                $account = UMerchantAccount::query()->where("phone", $phone)->first();
                if (empty($account) || $account->password != sha1($account->salt . $password)) {
                    $result['error'] = '账号或者密码错误!';
                } else {
                    $token = UMerchantAccountLogin::generateToken();
                    $al = new UMerchantAccountLogin();
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
                        session(['_login_merchant' => $al]);
                        if ($account->change_password == 0) {
                            return redirect('/merchant/change-password/?target=/merchant/auth/');
                        }
                        $logins = UMerchantAccountLogin::query()
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
        return view('merchant.login', $result);
    }

    public function changePassword(Request $request)
    {
        $login = session('_login_merchant');
        $target = $request->input('target', '/merchant/');
        $result = ['account' => $login->account, 'target' => $target];
        if ($request->isMethod('post')) {
            $password = $request->input('password');
            if (empty($password)) {
                $result['error'] = '密码不能为空！';
            } else {
                if ($login->account->change_password == 0) {
                    $login->account->password = sha1($login->account->salt . $password);
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
                    } elseif ($login->account->password != sha1($login->account->salt . $password_old)) {
                        $result['error'] = '密码错误';
                    } else {
                        $login->account->password = sha1($login->account->salt . $password);
                        if ($login->account->save()) {
                            return redirect($target);
                        } else {
                            $result['error'] = '服务器异常，请稍后重试！';
                        }
                    }
                }
            }
        }
        return view('merchant.change_password', $result);
    }

    public function logout(Request $request)
    {
        $c = cookie(self::LOTTO_AUTH_TOKEN, null);
        $request->session()->remove('_login_merchant');
        return back()->withCookies([$c]);
    }

    public function bills(Request $request)
    {
        $login = session('_login');
        $bills = $login->account->bills()->orderBy('created_at', 'desc')->paginate(20);
        return view('mobiles.bills', ['bills' => $bills]);
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
            return UMerchantAccountLogin::K_PLATFORM_WECHAT;
        }
        $keywords = ['iPhone', 'iPod', 'iPad', 'Android', 'IEMobile'];
        if (preg_match("/(" . implode('|', $keywords) . ")/i", $userAgent)) {
            return UMerchantAccountLogin::K_PLATFORM_WAP;
        }
        return UMerchantAccountLogin::K_PLATFORM_PC;
    }

}