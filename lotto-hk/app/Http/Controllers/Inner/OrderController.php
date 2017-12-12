<?php
namespace App\Http\Controllers\Inner;

use App\Models\Issue;
use App\Models\UAccountBill;
use App\Models\UAccountStatistic;
use App\Models\UAgentStatistic;
use App\Models\UGame;
use App\Models\UMerchantStatistic;
use App\Models\UOrder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function hit(Request $request)
    {
        $orders = UOrder::query()->where('status', UOrder::k_status_unknown)->take(100)->get();
        foreach ($orders as $order) {
            if ($order->issueO->status == Issue::k_status_done) {
                //特码
                if ($order->game_id == UGame::k_type_te_solo) {
                    DB::beginTransaction();
                    $num = $order->issueO->num7;
                    //命中
                    if (in_array($num, explode('|', $order->items))) {
                        $order->status = UOrder::k_status_done;
                        $order->hit = UOrder::k_hit_win;
                        $order->hit_item = $num;
                        if ($order->save()) {
                            $order->account->balance += $order->bonus;
                            if ($order->account->save()) {
                                $sell = new UAccountBill();
                                $sell->account_id = $order->account_id;
                                $sell->merchant_id = $order->merchant_id;
                                $sell->agent_id = $order->agent_id;
                                $sell->fee = $order->bonus;
                                $sell->type = UAccountBill::k_type_bonus;
                                $sell->tid = $order->id;
                                $sell->describe = '返奖';
                                if ($sell->save()) {
                                    DB::commit();
                                } else {
                                    DB::rollBack();
                                }
                            } else {
                                DB::rollBack();
                            }
                        } else {
                            DB::rollBack();
                        }
                    } else {//未命中
                        $order->status = UOrder::k_status_done;
                        $order->hit = UOrder::k_hit_lose;
                        if ($order->save()) {
                            DB::commit();
                        } else {
                            DB::rollBack();
                        }
                    }
                }
                //平特
                if ($order->game_id == UGame::k_type_all_solo) {

                }
            }
        }
        return response()->json(["code" => 0]);
    }

    public function report(Request $request)
    {
        $issue = $request->input('issue', 0);
        if ($issue == 0) {
            $issues = Issue::query()
                ->where('status', '<', Issue::k_status_done)
//                ->where('date', '<', date_create('36 hour'))
                ->orderBy('date', 'desc')
                ->take(1)->get();
        } elseif ($issue == 1) {
            $issues = Issue::query()
                ->where('status', Issue::k_status_done)
                ->orderBy('date', 'desc')
                ->take(1)->get();
        } elseif ($issue == -1) {
            $issues = Issue::query()
                ->where('status', Issue::k_status_done)
                ->orderBy('date', 'desc')
                ->take(10)->get();
        } else {
            return 400;
        }
        foreach ($issues as $issue) {
            $orders = UOrder::query()
                ->where('issue', $issue->id)
                ->where('status', '>=', UOrder::k_status_unknown)
                ->get();
            $merchant_s = [];
            $agent_s = [];
            $account_s = [];
            foreach ($orders as $order) {
                if (isset($merchant_s[$order->merchant_id]['total_fee'])) {
                    $merchant_s[$order->merchant_id]['total_fee'] += $order->total_fee;
                    $merchant_s[$order->merchant_id]['commission'] += $order->total_fee * $order->agent->commission / 100;
                } else {
                    $merchant_s[$order->merchant_id]['total_fee'] = $order->total_fee;
                    $merchant_s[$order->merchant_id]['commission'] = $order->total_fee * $order->agent->commission / 100;
                }
                if (isset($agent_s[$order->agent_id]['total_fee'])) {
                    $agent_s[$order->agent_id]['total_fee'] += $order->total_fee;
                    $agent_s[$order->agent_id]['commission'] += $order->total_fee * $order->agent->commission / 100;
                } else {
                    $agent_s[$order->agent_id]['total_fee'] = $order->total_fee;
                    $agent_s[$order->agent_id]['commission'] = $order->total_fee * $order->agent->commission / 100;
                }
                if (isset($account_s[$order->account_id]['total_fee'])) {
                    $account_s[$order->account_id]['total_fee'] += $order->total_fee;
                } else {
                    $account_s[$order->account_id]['total_fee'] = $order->total_fee;
                }
                if ($order->hit == UOrder::k_hit_win) {
                    if (isset($merchant_s[$order->merchant_id]['bonus_total'])) {
                        $merchant_s[$order->merchant_id]['bonus_total'] += $order->bonus;
                    } else {
                        $merchant_s[$order->merchant_id]['bonus_total'] = $order->bonus;
                    }
                    if (isset($agent_s[$order->agent_id]['bonus_total'])) {
                        $agent_s[$order->agent_id]['bonus_total'] += $order->bonus;
                    } else {
                        $agent_s[$order->agent_id]['bonus_total'] = $order->bonus;
                    }
                    if (isset($account_s[$order->account_id]['bonus_total'])) {
                        $account_s[$order->account_id]['bonus_total'] += $order->bonus;
                    } else {
                        $account_s[$order->account_id]['bonus_total'] = $order->bonus;
                    }
                }
            }
            foreach ($merchant_s as $key => $merchant_) {
                $ums = UMerchantStatistic::query()->where(['merchant_id' => $key, 'issue_id' => $issue->id])->first();
                if (!isset($ums)) {
                    $ums = new UMerchantStatistic();
                    $ums->merchant_id = $key;
                    $ums->issue_id = $issue->id;
                }
                $ums->sell_total = isset($merchant_['total_fee']) ? $merchant_['total_fee'] : 0;
                $ums->bonus_total = isset($merchant_['bonus_total']) ? $merchant_['bonus_total'] : 0;
                $ums->commission = isset($merchant_['commission']) ? $merchant_['commission'] : 0;
                $ums->save();
            }
            foreach ($agent_s as $key => $agent_) {
                $uas = UAgentStatistic::query()->where(['agent_id' => $key, 'issue_id' => $issue->id])->first();
                if (!isset($uas)) {
                    $uas = new UAgentStatistic();
                    $uas->agent_id = $key;
                    $uas->issue_id = $issue->id;
                }
                $uas->sell_total = isset($agent_['total_fee']) ? $agent_['total_fee'] : 0;
                $uas->bonus_total = isset($agent_['bonus_total']) ? $agent_['bonus_total'] : 0;
                $uas->commission = isset($agent_['commission']) ? $agent_['commission'] : 0;
                $uas->save();
            }
            foreach ($account_s as $key => $account_) {
                $uas = UAccountStatistic::query()->where(['account_id' => $key, 'issue_id' => $issue->id])->first();
                if (!isset($uas)) {
                    $uas = new UAccountStatistic();
                    $uas->account_id = $key;
                    $uas->issue_id = $issue->id;
                }
                $uas->sell_total = isset($agent_['total_fee']) ? $agent_['total_fee'] : 0;
                $uas->bonus_total = isset($agent_['bonus_total']) ? $agent_['bonus_total'] : 0;
                $uas->save();
            }
        }
    }
}