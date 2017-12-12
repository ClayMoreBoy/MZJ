<?php

namespace App\Http\Controllers\Mobiles;

use App\Models\UOrder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class OrdersController extends BaseController
{
    use LBallParse;

    public function __construct()
    {
        $this->initBalls();
    }

    public function order_curr(Request $request)
    {
        $login = session('_login');
        $orders = $login->account->orders()->where('status', UOrder::k_status_unknown)->orderBy('created_at','desc')->get();
        $result = [];
        foreach ($orders as $order) {
            $result[$order->issue][] = $order;
        }
        return view('mobiles.order_curr', ['issues' => $result]);
    }

    public function order_history(Request $request)
    {
        $login = session('_login');
        $orders = $login->account->orders()
//        $orders = UOrder::query()
//            ->where('account_id',$login->account_id)
            ->where('status', UOrder::k_status_done)
            ->orderBy('issue','desc')
            ->orderBy('created_at','desc')
            ->paginate(20);
        $result = [];
        foreach ($orders as $order) {
            $result[$order->issue][] = $order;
        }
        return view('mobiles.order_history', ['issues' => $result, 'page_orders' => $orders]);
    }

}
