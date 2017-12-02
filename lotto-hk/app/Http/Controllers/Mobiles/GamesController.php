<?php

namespace App\Http\Controllers\Mobiles;

use App\Models\Issue;
use App\Models\LModel;
use App\Models\UAccount;
use App\Models\UAccountBill;
use App\Models\UOrder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class GamesController extends BaseController
{
    use LBallParse;

    public function __construct()
    {
        $this->initBalls();
    }

    public function te(Request $request)
    {
        $login = session('_login');
        $game = $login->account->merchant->games()->where('id', 100)->first();
        $issue = Issue::query()->where('status', '0')->orderBy('date', 'desc')->first();
        return view('mobiles.tz_te', ['issue' => $issue, 'game' => $game, 'num_attr' => $this->num_attr, 'zodiacs_ball' => $this->zodiacs_ball]);
    }

    public function tePost(Request $request)
    {
        if ($request->isMethod('post')) {

            $login = session('_login');
            $account = $login->account;
            $total_fee = $request->input('total_fee', 0);
            if (!is_numeric($total_fee) || $total_fee < 2) {
                return response()->json(['code' => 401, 'message' => '本金至少大于2！']);
            }
            $issue = $request->input('issue', 0);
            $issue = Issue::query()->find($issue);
            if (!isset($issue) || $issue->status == 2) {
                return response()->json(['code' => 401, 'message' => '无效的期数！']);
            }
            if (strtotime($issue->date) < date_create('+10 min')->getTimestamp()) {
                return response()->json(['code' => 401, 'message' => '请在开奖前10分钟投注！']);
            }
            $gameId = $request->input('gameId', 0);
            $game = $account->merchant->games()->where('id', $gameId)->first();
            if (!isset($game)) {
                return response()->json(['code' => 401, 'message' => '无效的玩法！']);
            }

            $ballstr = $request->input('balls', '');
            $balls = explode('|', $ballstr);
            if (empty($balls) || count($balls) == 0) {
                return response()->json(['code' => 401, 'message' => '参数错误！']);
            }
            foreach ($balls as $item) {
                if ($item < 1 || $item > 49) {
                    return response()->json(['code' => 401, 'message' => '参数错误！']);
                }
            }
            $order = new UOrder();
            $order->issue = $issue->id;
            $order->game_id = $gameId;
            $order->merchant_id = $account->merchant->id;
            $order->agent_id = $account->agent->id;
            $order->account_id = $account->id;
            $order->total_fee = $total_fee;
            $order->items = $ballstr;
            $order->odd = round($game->odd / count($balls), 2);
            $order->bonus = $order->odd * $total_fee;
            $order->status = UOrder::k_status_unknown;
            $order->hit = UOrder::k_hit_unknown;
            $order->hit_item = '';
            //开始事务
            DB::beginTransaction();
            $account = UAccount::query()->sharedLock()->find($account->id);//避免脏读
            if ($total_fee > $account->balance) {
                DB::rollBack();
                return response()->json(['code' => 406, 'message' => '余额不足！']);
            }
            if ($order->save()) {
                $account->balance = $account->balance - $total_fee;//扣除账户余额
                if ($account->save()) {
                    $sell = new UAccountBill();
                    $sell->account_id = $order->account_id;
                    $sell->merchant_id = $order->merchant_id;
                    $sell->agent_id = $order->agent_id;
                    $sell->fee = $order->total_fee * -1;
                    $sell->type = UAccountBill::k_type_buy;
                    $sell->tid = $order->id;
                    $sell->describe = '消费';
                    if ($sell->save()) {
                        DB::commit();
                        return response()->json(['code' => 0, 'message' => 'OK']);
                    } else {
                        DB::rollBack();
                        return response()->json(['code' => 400, 'message' => '服务器异常！']);
                    }
                } else {
                    DB::rollBack();
                    return response()->json(['code' => 400, 'message' => '服务器异常！']);
                }
            } else {
                DB::rollBack();
                return response()->json(['code' => 400, 'message' => '服务器异常！']);
            }
        } else {
            return response()->json(['code' => 405, 'message' => '无效的请求！']);
        }
    }

    public function ping()
    {
        return view('mobiles.tz_ping');
    }
}
