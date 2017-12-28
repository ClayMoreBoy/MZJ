<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/3
 * Time: 17:16
 */
namespace App\Http\Middleware;

use App\Http\Controllers\Mobiles\LDomainParse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class UDomainVerify
{
    use LDomainParse;

    public function handle(Request $request, Closure $next)
    {
        $login = session('_login');
        $rs = $this->parse($request);
        if (isset($login) && isset($login->account)) {
            $accountDB = $login->account()->first();
            if ($accountDB->balance != $login->account->balance) {
                $login->account = $accountDB;
                session(['_login' => $login]);
            }
            View::share('account', $accountDB);
            View::share('merchant', $login->account->merchant()->first());
            View::share('agent', $login->account->agent()->first());
            return $next($request);
        } else {
            if (isset($rs['merchant']) && isset($rs['agent'])) {
                View::share('merchant', $rs['merchant']);
                View::share('agent', $rs['agent']);
                return $next($request);
            } elseif (isset($rs['merchant'])) {
                View::share('merchant', $rs['merchant']);
                return $next($request);
            } else {
//                $domain_old = parse_url($request->fullUrl(), PHP_URL_HOST);
//                $scheme = parse_url($request->fullUrl(), PHP_URL_SCHEME);
//                if ($domain_old == $rs['domain']) {
//                    return $next($request);
//                } else {
//                    $domain = env('APP_DOMAIN', $rs['domain']);
//                    return redirect($scheme . '://' . $domain . '/mobiles/');
//                }
            }
            return $next($request);
        }
    }

}