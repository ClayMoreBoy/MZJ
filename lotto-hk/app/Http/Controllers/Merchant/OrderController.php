<?php
namespace App\Http\Controllers\Merchant;

use App\Models\Issue;
use App\Models\UAccount;
use App\Models\UAgentAccount;
use App\Models\UMerchantGame;
use App\Models\UOrder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{

    public function __construct()
    {
//        $this->middleware('auth_api')->only(['balance']);
    }

    public function search(Request $request)
    {
        $issues = Issue::query()->where('date', '<', date_create('36 hour'))->orderBy('id', 'desc')->take(100)->get();
        $games = UMerchantGame::query()->get();
        $accounts = UAccount::query()->get();
        $agents = UAgentAccount::query()->get();
        $result = [];
        $result['issues'] = $issues;
        $result['games'] = $games;
        $result['accounts'] = $accounts;
        $result['agents'] = $agents;
        $login = session('_login_merchant');
        $query = $login->account->orders();
        if ($request->has('issue')) {
            $issue = Issue::query()->find($request->issue);
            if (isset($issue)) {
                $result['c_issue'] = $issue;
                $query->where('issue', $request->issue);
            }
        }
        if ($request->has('account_id')) {
            $account = UAccount::query()->find($request->account_id);
            if (isset($account)) {
                $result['c_account'] = $account;
                $query->where('account_id', $request->account_id);
            }
        }
        if ($request->has('agent_id')) {
            $agent = UAgentAccount::query()->find($request->agent_id);
            if (isset($agent)) {
                $result['c_agent'] = $agent;
                $query->where('agent_id', $request->agent_id);
            }
        }
        if ($request->has('game_id')) {
            $game = UMerchantGame::query()->find($request->game_id);
            if (isset($game)) {
                $result['c_game'] = $game;
                $query->where('game_id', $request->game_id);
            }
        }
        $orders = $query->paginate(20);
        if (isset($issue)) {
            $orders->appends(['issue' => $issue->id]);
        }
        if (isset($account)) {
            $orders->appends(['account_id' => $account->id]);
        }
        if (isset($agent)) {
            $orders->appends(['agent_id' => $agent->id]);
        }
        if (isset($game)) {
            $orders->appends(['game_id' => $game->id]);
        }
        $result['orders'] = $query->paginate(20);
        return view('merchant.order_search', $result);
    }

}