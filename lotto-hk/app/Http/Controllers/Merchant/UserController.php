<?php
namespace App\Http\Controllers\Merchant;

use App\Models\UAgentAccount;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    public function __construct()
    {
//        $this->middleware('auth_api')->only(['balance']);
    }

    public function search(Request $request)
    {
        $agents = UAgentAccount::query()->get();
        $result = [];
        $result['agents'] = $agents;
        $login = session('_login_merchant');
        $query = $login->account->accounts();
        if ($request->has('nickname')) {
            $result['nickname'] = $request->nickname;
            $query->where('nickname', 'like', '%' . $request->nickname . '%');
        }
        if ($request->has('agent_id')) {
            $agent = UAgentAccount::query()->find($request->agent_id);
            if (isset($agent)) {
                $result['c_agent'] = $agent;
                $query->where('agent_id', $request->agent_id);
            }
        }
        if ($request->has('sort_at') && $request->has('sort_rule')) {
            $result['sort_at'] = $request->sort_at;
            $result['sort_rule'] = $request->sort_rule;
            $query->orderBy($request->sort_at, $request->sort_rule);
        }
        $accounts = $query->paginate(20);
        if (isset($account)) {
            $accounts->appends(['account_id' => $account->id]);
        }
        if (isset($agent)) {
            $accounts->appends(['agent_id' => $agent->id]);
        }
        if ($request->has('sort_at') && $request->has('sort_rule')) {
            $accounts->appends(['sort_at' => $request->sort_at, 'sort_rule' => $request->sort_rule]);
        }
        $result['accounts'] = $accounts;
        return view('merchant.user_search', $result);
    }

}