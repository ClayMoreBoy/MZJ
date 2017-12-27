<?php

namespace App\Http\Controllers\Mobiles;

use App\Models\Issue;
use App\Models\UAccount;
use App\Models\UAccountBill;
use App\Models\UGame;
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
        $game = $login->account->merchant->games->where('game_id', UGame::k_type_te_solo)->first();
        $issue = Issue::currentIssue();
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
            if (!isset($issue) || $issue->status > 0) {
                return response()->json(['code' => 401, 'message' => '无效的期数！']);
            }
            if (strtotime($issue->date) < date_create('+10 min')->getTimestamp()) {
                return response()->json(['code' => 401, 'message' => '请在开奖前10分钟投注！']);
            }
            $game = $account->merchant->games()->where('game_id', UGame::k_type_te_solo)->first();
            if (!isset($game)) {
                return response()->json(['code' => 401, 'message' => '该玩法已经关闭！']);
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
            $order->game_id = UGame::k_type_te_solo;
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

    public function all(Request $request)
    {
        $login = session('_login');
        if ($request->route()->named('all-two')) {
            $game = $login->account->merchant->games->where('game_id', UGame::k_type_all_two)->first();
        } elseif ($request->route()->named('all-three')) {
            $game = $login->account->merchant->games->where('game_id', UGame::k_type_all_three)->first();
        } elseif ($request->route()->named('all-four')) {
            $game = $login->account->merchant->games->where('game_id', UGame::k_type_all_four)->first();
        } elseif ($request->route()->named('all-five')) {
            $game = $login->account->merchant->games->where('game_id', UGame::k_type_all_five)->first();
        } elseif ($request->route()->named('all-six')) {
            $game = $login->account->merchant->games->where('game_id', UGame::k_type_all_six)->first();
        } else {
            $game = $login->account->merchant->games->where('game_id', UGame::k_type_all_solo)->first();
        }
        $issue = Issue::currentIssue();
        return view('mobiles.tz_all', ['issue' => $issue, 'game' => $game, 'num_attr' => $this->num_attr, 'zodiacs_ball' => $this->zodiacs_ball]);
    }

    public function allPost(Request $request)
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
            if (!isset($issue) || $issue->status > 0) {
                return response()->json(['code' => 401, 'message' => '无效的期数！']);
            }
            if (strtotime($issue->date) < date_create('+10 min')->getTimestamp()) {
                return response()->json(['code' => 401, 'message' => '请在开奖前10分钟投注！']);
            }
            $game_id = $request->input('game_id', 0);
            if (!in_array($game_id, [UGame::k_type_all_solo, UGame::k_type_all_six, UGame::k_type_all_two, UGame::k_type_all_three, UGame::k_type_all_four, UGame::k_type_all_five])) {
                return response()->json(['code' => 401, 'message' => '无效的玩法！']);
            }
            $game = $account->merchant->games()->where('game_id', $game_id)->first();
            if (!isset($game) || $game->on_off == 0) {
                return response()->json(['code' => 401, 'message' => '该玩法已经关闭！']);
            }

            $ballstr = $request->input('balls', '');
            $balls = explode('|', $ballstr);
            if (count($balls) > $game->items_max || count($balls) < $game->items_min) {
                return response()->json(['code' => 401, 'message' => '选项数量错误！']);
            }
            foreach ($balls as $item) {
                if ($item < 1 || $item > 49) {
                    return response()->json(['code' => 401, 'message' => '选项内容错误！']);
                }
            }
            $order = new UOrder();
            $order->issue = $issue->id;
            $order->game_id = $game_id;
            $order->merchant_id = $account->merchant->id;
            $order->agent_id = $account->agent->id;
            $order->account_id = $account->id;
            $order->total_fee = $total_fee;
            $order->items = $ballstr;
            if ($game_id == UGame::k_type_all_solo) {//单
                $order->bonus = round((($order->odd * $total_fee) / count($balls)) * min(count($balls), 7), 2);
                $order->odd = round($order->bonus / $order->total_fee, 2);
            } else {
                $order->odd = $game->odd;
                $order->bonus = round($order->odd * $total_fee, 2);
            }
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

    public function allZodiac(Request $request)
    {
        $login = session('_login');
        if ($request->route()->named('zodiac-two')) {
            $game = $login->account->merchant->games->where('game_id', UGame::k_type_all_zodiac_two)->first();
        } elseif ($request->route()->named('zodiac-three')) {
            $game = $login->account->merchant->games->where('game_id', UGame::k_type_all_zodiac_three)->first();
        } elseif ($request->route()->named('zodiac-four')) {
            $game = $login->account->merchant->games->where('game_id', UGame::k_type_all_zodiac_four)->first();
        } elseif ($request->route()->named('zodiac-five')) {
            $game = $login->account->merchant->games->where('game_id', UGame::k_type_all_zodiac_five)->first();
        } elseif ($request->route()->named('zodiac-six')) {
            $game = $login->account->merchant->games->where('game_id', UGame::k_type_all_zodiac_six)->first();
        } else {
            $game = $login->account->merchant->games->where('game_id', UGame::k_type_all_zodiac)->first();
        }
        $issue = Issue::currentIssue();
        return view('mobiles.tz_all_zodiac', ['issue' => $issue, 'game' => $game, 'zodiacs' => $this->zodiacs, 'first_zodiac' => $this->first_zodiac]);
    }

    public function allZodiacPost(Request $request)
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
            if (!isset($issue) || $issue->status > 0) {
                return response()->json(['code' => 401, 'message' => '无效的期数！']);
            }
            if (strtotime($issue->date) < date_create('+10 min')->getTimestamp()) {
                return response()->json(['code' => 401, 'message' => '请在开奖前10分钟投注！']);
            }
            $game_id = $request->input('game_id', 0);
            if (!in_array($game_id, [UGame::k_type_all_zodiac, UGame::k_type_all_zodiac_six, UGame::k_type_all_zodiac_two, UGame::k_type_all_zodiac_three, UGame::k_type_all_zodiac_four, UGame::k_type_all_zodiac_five])) {
                return response()->json(['code' => 401, 'message' => '无效的玩法！']);
            }
            $game = $account->merchant->games()->where('game_id', $game_id)->first();
            if (!isset($game) || $game->on_off == 0) {
                return response()->json(['code' => 401, 'message' => '该玩法已经关闭！']);
            }

            $zodiacsStr = $request->input('balls', '');
            $zodiacs = explode('|', $zodiacsStr);
            if (count($zodiacs) > $game->items_max && count($zodiacs) < $game->items_min) {
                return response()->json(['code' => 401, 'message' => '参数错误！']);
            }
            $oddMax = 0;
            $preFee = $total_fee / count($zodiacs);
            $has_first_zodiac = false;
            foreach ($zodiacs as $zodiac) {
                if (!in_array($zodiac, $this->zodiacs)) {
                    return response()->json(['code' => 401, 'message' => '参数错误！']);
                }
                if ($zodiac == $this->first_zodiac) {
                    $oddMax += $game->odd1 * $preFee;
                    $has_first_zodiac = true;
                } else {
                    $oddMax += $game->odd * $preFee;
                }
            }
            $order = new UOrder();
            $order->issue = $issue->id;
            $order->game_id = $game_id;
            $order->merchant_id = $account->merchant->id;
            $order->agent_id = $account->agent->id;
            $order->account_id = $account->id;
            $order->total_fee = $total_fee;
            $order->items = $zodiacsStr;
            if ($game_id == UGame::k_type_all_zodiac) {
                $order->odd = round($oddMax / $total_fee, 2);
                $order->bonus = round($oddMax, 2);
            } else if ($has_first_zodiac) {
                $order->odd = $game->odd1;
                $order->bonus = $order->total_fee * $order->odd;
            } else {
                $order->odd = $game->odd;
                $order->bonus = $order->total_fee * $order->odd;
            }
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
}
