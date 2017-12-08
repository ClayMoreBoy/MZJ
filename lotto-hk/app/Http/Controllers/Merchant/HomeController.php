<?php
namespace App\Http\Controllers\Merchant;

use App\Models\Issue;
use App\Models\UOrder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{

    public function __construct()
    {
//        $this->middleware('auth_api')->only(['balance']);
    }

    public function index(Request $request)
    {
        $login = session('_login_merchant');
        $issues = Issue::query()->where('date', '<', date_create('36 hour'))->orderBy('id', 'desc')->take(2)->get();
        $report = [];
        foreach ($issues as $issue) {
            $orders = $login->account->orders()->where('status', '>=', UOrder::k_status_unknown)->where('issue', $issue->id)->get();
            if ($issue->status == Issue::k_status_done) {//已开奖
                $total_sell = $orders->sum('total_fee');//总销售
                $total_bonus = $orders->where('hit', UOrder::k_hit_win)->sum('bonus');//总返奖
                $commission = $total_sell * 0.05;
                $profit = $total_sell - $total_bonus - $commission;
                $report[$issue->id . ''] = ['status' => 'done', 'total_sell' => $total_sell, 'total_bonus' => $total_bonus, 'commission' => $commission, 'profit' => $profit];
            } else {//未开奖
                $total_sell = $orders->sum('total_fee');//总销售
                $report[$issue->id . ''] = ['status' => 'unknown', 'total_sell' => $total_sell, 'total_bonus' => 0, 'commission' => 0, 'profit' => 0];
            }
        }
        $result['report'] = $report;
        $result['new_account'] = $login->account->accounts()->where('created_at', '>', date_create('today'))->count();
        return view('merchant.home', $result);
    }

}