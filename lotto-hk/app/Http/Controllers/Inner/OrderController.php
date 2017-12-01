<?php
namespace App\Http\Controllers\Inner;

use App\Models\Issue;
use App\Models\UAccountSell;
use App\Models\UGame;
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
                if ($order->game_id == UGame::k_type_te) {
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
                                $sell = new UAccountSell();
                                $sell->account_id = $order->account_id;
                                $sell->merchant_id = $order->merchant_id;
                                $sell->agent_id = $order->agent_id;
                                $sell->fee = $order->bonus;
                                $sell->type = UAccountSell::k_type_bonus;
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
                if ($order->game_id == UGame::k_type_ping) {

                }
            }
        }
        return response()->json(["code" => 0]);
    }
}