<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/3
 * Time: 17:16
 */
namespace App\Http\Middleware\Agent;

use App\Http\Controllers\Mobiles\LDomainParse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class AgentDomainVerify
{
    use LDomainParse;

    public function handle(Request $request, Closure $next)
    {
        $login = session('_login_agent');
        $rs = $this->parse($request);
//        $scheme = parse_url($request->fullUrl(), PHP_URL_SCHEME);
//        $path = parse_url($request->fullUrl(), PHP_URL_PATH);
        if (isset($login) && isset($login->account)) {
            View::share('account', $login->account);
            View::share('merchant', $login->account->merchant);
            $domain = $login->account->domain . '.' . $login->account->merchant->domain . '.' . $rs['domain'];
            View::share('domain', $domain);
            if (isset($rs['merchant']) && isset($rs['agent'])) {
                if ($login->account->merchant->id == $rs['merchant']->id && $login->account->id == $rs['agent']->id) {
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