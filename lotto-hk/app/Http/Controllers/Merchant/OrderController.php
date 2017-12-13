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
                $query->where('u_orders.issue', $request->issue);
            }
        }
        $query->leftJoin('u_accounts', 'u_accounts.id', '=', 'u_orders.account_id');
        if ($request->has('nickname')) {
            $result['nickname'] = $request->nickname;
            $query->where('u_accounts.nickname', 'like', '%' . $request->nickname . '%');
            $query->select('u_orders.*');
        }
        if ($request->has('agent_id')) {
            $agent = UAgentAccount::query()->find($request->agent_id);
            if (isset($agent)) {
                $result['c_agent'] = $agent;
                $query->where('u_orders.agent_id', $request->agent_id);
            }
        }
        if ($request->has('game_id')) {
            $game = UMerchantGame::query()->find($request->game_id);
            if (isset($game)) {
                $result['c_game'] = $game;
                $query->where('u_orders.game_id', $request->game_id);
            }
        }
        $query->orderBy('u_orders.created_at', 'desc');
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
        $result['orders'] = $orders;
        return view('merchant.order_search', $result);
    }

    public function issue(Request $request)
    {
        $issue_id = $request->input('issue');
        if ($request->has('issue')) {
            $issue = Issue::query()->find($issue_id);
        }
        if (!isset($issue)) {
            $issue = Issue::query()
//                ->where('status', '<', Issue::k_status_done)
                ->where('date', '<', date_create('36 hour'))
                ->orderBy('date', 'desc')
                ->first();
        }
        $result['issue_id'] = $issue->id;
        $login = session('_login_merchant');
        $orders = $login->account->orders()->where('issue', $issue->id)->where('status', '>=', UOrder::k_hit_unknown)->get();
        $games = [];
        foreach ($orders as $order) {
            if (!isset($games[$order->game_id])) {
                if (isset($order->game)) {
                    $games[$order->game_id]['game'] = $order->game;
                    $games[$order->game_id]['total'] = 0;
                    foreach ($order->game->listItems() as $item) {
                        $games[$order->game_id]['items'][$item]['item'] = $item;
                        $games[$order->game_id]['items'][$item]['count'] = 0;
                        $games[$order->game_id]['items'][$item]['fee'] = 0;
                        $games[$order->game_id]['items'][$item]['bonus'] = 0;
                    }
                } else {
                    continue;
                }
            }
            $games[$order->game_id]['total'] += $order->total_fee;
            $items = explode('|', $order->items);
            $fee = $order->total_fee / count($items);
            $bonus = $order->bonus;
            foreach ($items as $item) {
                if (isset($games[$order->game_id]['items'][$item])) {
                    $games[$order->game_id]['items'][$item]['count'] += 1;
                    $games[$order->game_id]['items'][$item]['fee'] += $fee;
                    $games[$order->game_id]['items'][$item]['bonus'] += $bonus;
                }
            }
        }
        $result['games'] = $games;
        $result['issues'] = Issue::query()->where('date', '<', date_create('36 hour'))->orderBy('id', 'desc')->take(20)->get();
        return view('merchant.order_game', $result);
    }

}