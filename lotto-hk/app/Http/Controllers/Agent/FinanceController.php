<?php
namespace App\Http\Controllers\Agent;

use App\Models\UAccountBill;
use App\Models\UAccountDeposit;
use App\Models\UAccountWithdraw;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FinanceController extends Controller
{

    public function __construct()
    {
//        $this->middleware('agent_auth_api')->only(['update']);
    }

    public function deposit(Request $request, $id)
    {
        $login = session('_login_agent');
        $user = $login->account->accounts()->where('id', $id)->first();
        $result = ['user' => $user];
        if ($request->isMethod('post')) {
            if (!isset($user)) {
                $request['error'] = '无效的用户';
            } elseif (!$request->has('fee') || $request->fee < 10 || $request->fee > 99999) {
                $request['error'] = '充值金额必须在10-99999之间';
            }

            if (!isset($request['error'])) {
                $user->balance += $request->fee;
                DB::beginTransaction();
                if ($user->save()) {
                    $deposit = new UAccountDeposit();
                    $deposit->account_id = $user->id;
                    $deposit->merchant_id = $user->merchant_id;
                    $deposit->agent_id = $user->agent_id;
                    $deposit->fee = $request->fee;
                    $deposit->status = UAccountDeposit::k_status_succeed;
                    if ($deposit->save()) {
                        $bill = new UAccountBill();
                        $bill->account_id = $user->id;
                        $bill->merchant_id = $user->merchant_id;
                        $bill->agent_id = $user->agent_id;
                        $bill->fee = $deposit->fee;
                        $bill->type = UAccountBill::k_type_deposit;
                        $bill->tid = $deposit->id;
                        $bill->describe = '充值';
                        if ($bill->save()) {
                            DB::commit();
                        } else {
                            DB::rollBack();
                            $request['error'] = '服务器异常';
                        }
                    } else {
                        DB::rollBack();
                        $request['error'] = '服务器异常';
                    }
                } else {
                    DB::rollBack();
                    $request['error'] = '服务器异常';
                }
            }
        }
        return view('agent.deposit', $result);
    }

    public function accounts(Request $request)
    {
        return view('agent.deposit_accounts');
    }

    public function deposits(Request $request)
    {
        $login = session('_login_agent');
        $query = $login->account->deposits();
        if ($request->has('account_id')) {
            $user = $login->account->accounts()->where('id', $request->account_id)->first();
            if (isset($user)) {
                $result['filter_user'] = $user;
                $query->where('account_id', $request->account_id);
            }
        }
        $query->orderBy('created_at', 'desc');
        $deposits = $query->paginate(20);
        if (isset($result['filter_user'])) {
            $deposits->appends(['account_id' => $request->account_id]);
        }
        $result['deposits'] = $deposits;
        return view('agent.bill_deposits', $result);
    }

    public function withdraws(Request $request)
    {
        $login = session('_login_agent');
        $query = $login->account->withdraws();
        if ($request->has('account_id')) {
            $user = $login->account->accounts()->where('id', $request->account_id)->first();
            if (isset($user)) {
                $result['filter_user'] = $user;
                $query->where('account_id', $request->account_id);
            }
        }
        $query->orderBy('created_at', 'desc');
        $withdraws = $query->paginate(20);
        if (isset($result['filter_user'])) {
            $withdraws->appends(['account_id' => $request->account_id]);
        }
        $result['withdraws'] = $withdraws;
        return view('agent.bill_withdraws', $result);
    }

    public function processWithdraw(Request $request)
    {
        if (!$request->has('id')) {
            return response()->json(['code' => 401, 'message' => '参数错误']);
        }
        if (!$request->has('action')) {
            return response()->json(['code' => 401, 'message' => '参数错误']);
        }
        $login = session('_login_agent');
        DB::beginTransaction();
        $withdraw = $login->account->withdraws()->where('id', $request->id)->first();
        if (!isset($withdraw)) {
            DB::rollBack();
            return response()->json(['code' => 401, 'message' => '参数错误']);
        }
        if ($withdraw->status != UAccountWithdraw::k_status_waiting) {
            DB::rollBack();
            return response()->json(['code' => 304, 'message' => '请求已经被处理']);
        }
        if ($request->action == 'done') {
            $withdraw->status = UAccountWithdraw::k_status_succeed;
            if ($withdraw->save()) {
                $bill = new UAccountBill();
                $bill->account_id = $withdraw->account_id;
                $bill->merchant_id = $withdraw->merchant_id;
                $bill->agent_id = $withdraw->agent_id;
                $bill->fee = $withdraw->fee;
                $bill->type = UAccountBill::k_type_withdraw;
                $bill->tid = $withdraw->id;
                $bill->describe = '提款';
                if ($bill->save()) {
                    DB::commit();
                    return response()->json(['code' => 0, 'message' => 'OK']);
                } else {
                    DB::rollBack();
                    return response()->json(['code' => 500, 'message' => '服务器异常']);
                }
            } else {
                DB::rollBack();
                return response()->json(['code' => 500, 'message' => '服务器异常']);
            }
        } elseif ($request->action == 'reject') {
            $withdraw->status = UAccountWithdraw::k_status_rejected;
            if ($withdraw->save()) {
                $withdraw->account->balance += $withdraw->fee;
                if ($withdraw->account->save()) {
                    DB::commit();
                    return response()->json(['code' => 0, 'message' => 'OK']);
                } else {
                    DB::rollBack();
                    return response()->json(['code' => 500, 'message' => '服务器异常']);
                }
            } else {
                DB::rollBack();
                return response()->json(['code' => 500, 'message' => '服务器异常']);
            }
        } else {
            DB::rollBack();
            return response()->json(['code' => 304, 'message' => '无效的请求']);
        }
    }
}