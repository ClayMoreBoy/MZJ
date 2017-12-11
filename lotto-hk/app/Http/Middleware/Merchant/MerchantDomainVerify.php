<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/3
 * Time: 17:16
 */
namespace App\Http\Middleware\Merchant;

use App\Http\Controllers\Mobiles\LDomainParse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class MerchantDomainVerify
{
    use LDomainParse;

    public function handle(Request $request, Closure $next)
    {
        $login = session('_login_merchant');
        $rs = $this->parse($request);
//        $scheme = parse_url($request->fullUrl(), PHP_URL_SCHEME);
//        $path = parse_url($request->fullUrl(), PHP_URL_PATH);
        if (isset($login) && isset($login->account)) {
            View::share('account', $login->account);
            $domain = $login->account->domain . '.' . $rs['domain'];
            View::share('domain', $domain);
            if (isset($rs['merchant'])) {
                if ($login->account->id == $rs['merchant']->id) {
                    return $next($request);
                }
            }
            return $next($request);
//            return redirect($scheme . '://' . $domain . $path);
        } else {
            return $next($request);
        }
    }

}