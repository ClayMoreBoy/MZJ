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
            if (isset($rs['merchant']) && isset($rs['agent'])) {
                if ($login->account->merchant->id == $rs['merchant']->id && $login->account->agent->id == $rs['agent']->id) {
                    return $next($request);
                }
            }
            $domain_old = parse_url($request->fullUrl(), PHP_URL_HOST);
            $scheme = parse_url($request->fullUrl(), PHP_URL_SCHEME);
            $path = parse_url($request->fullUrl(), PHP_URL_PATH);
            $domain = $login->account->agent->domain . '.' . $login->account->merchant->domain . '.' . $rs['domain'];
            if ($domain_old == $domain) {
                return $next($request);
            } else {
                return redirect($scheme . '://' . $login->account->agent->domain . '.' . $login->account->merchant->domain . '.' . $rs['domain'] . $path);
            }
        } else {
            if (isset($rs['merchant']) && isset($rs['agent'])) {
                View::share('merchant', $rs['merchant']);
                View::share('agent', $rs['agent']);
                return $next($request);
            } elseif (isset($rs['merchant'])) {
                View::share('merchant', $rs['merchant']);
                $domain_old = parse_url($request->fullUrl(), PHP_URL_HOST);
                $scheme = parse_url($request->fullUrl(), PHP_URL_SCHEME);
                $path = parse_url($request->fullUrl(), PHP_URL_PATH);
                $domain = $login->account->merchant->domain . '.' . $rs['domain'];
                if ($domain_old == $domain) {
                    return $next($request);
                } else {
                    return redirect($scheme . '://' . $domain . $path);
                }
            } else {
                $domain_old = parse_url($request->fullUrl(), PHP_URL_HOST);
                $scheme = parse_url($request->fullUrl(), PHP_URL_SCHEME);
                if ($domain_old == $rs['domain']) {
                    return $next($request);
                } else {
                    return redirect($scheme . '://' . $rs['domain'] . '/mobiles/');
                }
            }
        }
    }

}