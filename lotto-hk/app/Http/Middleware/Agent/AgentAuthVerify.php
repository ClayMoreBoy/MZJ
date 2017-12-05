<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/3
 * Time: 17:16
 */
namespace App\Http\Middleware\Agent;

use App\Http\Controllers\Agent\AuthController;
use App\Models\UAgentAccountLogin;
use Closure;
use Illuminate\Http\Request;

class AgentAuthVerify
{

    public function handle(Request $request, Closure $next)
    {
        if ($this->hasAuth($request)) {
            $login = session('_login_agent');
            if ($login->account->change_password == 0 && !strstr(request()->path(), 'agent/change-password')) {
                return redirect('/agent/change-password/?target=/agent/auth/');
            }
            return $next($request);
        } else {
            return redirect('/agent/auth/?target=' . urlencode(request()->fullUrl()));
        }
    }

    /**
     * 判断是登录
     * @param Request $request
     * @return bool
     */
    protected function hasAuth(Request $request)
    {
        $login = session('_login_agent');
        if (isset($login)) {
            return true;
        }
        if ($request->has(AuthController::LOTTO_AUTH_TOKEN)) {
            $token = $request->input(AuthController::LOTTO_AUTH_TOKEN);
        } else {
            $token = $request->cookie(AuthController::LOTTO_AUTH_TOKEN);
        }
        if (isset($token)) {
            $login = UAgentAccountLogin::query()->find($token);
            if (isset($login) && $login->status == 1) {
                if (strtotime($login->expired_at) > strtotime('now')) {
                    session(['_login_agent' => $login]);
                    $login->account->last_access_at = date_create();
                    $login->account->save();
                    return true;
                } else {
                    $login->status = 0;
                    $login->save();
                }
            }
        }
        return false;
    }

}