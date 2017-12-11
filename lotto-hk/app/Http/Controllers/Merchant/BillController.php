<?php
namespace App\Http\Controllers\Merchant;

use App\Models\Issue;
use App\Models\UAccount;
use App\Models\UAccountDeposit;
use App\Models\UAccountStatistic;
use App\Models\UAccountWithdraw;
use App\Models\UAgentAccount;
use App\Models\UAgentStatistic;
use App\Models\UMerchantGame;
use App\Models\UMerchantStatistic;
use App\Models\UOrder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class BillController extends Controller
{

    public function __construct()
    {
//        $this->middleware('auth_api')->only(['balance']);
    }

    public function deposits(Request $request)
    {
        $login = session('_login_merchant');
        $query = $login->account->deposits()
            ->where('status', UAccountDeposit::k_status_succeed);
        $result['agents'] = $login->account->agents;
        if ($request->has('agent_id')) {
            $agent = $login->account->agents->where('id', $request->agent_id)->first();
            if (isset($agent)) {
                $result['agent'] = $agent;
                $query->where('agent_id', $request->agent_id);
            }
        }
        $query->orderBy('created_at', 'desc');
        $result['deposits'] = $query->paginate(20);
        if (isset($result['agent'])) {
            $result['deposits']->appends(['agent_id' => $result['agent']->id]);
        }
        return view('merchant.bill_deposits', $result);
    }

    public function withdraws(Request $request)
    {
        $login = session('_login_merchant');
        $query = $login->account->withdraws()
            ->where('status', UAccountWithdraw::k_status_succeed);
        $result['agents'] = $login->account->agents;
        if ($request->has('agent_id')) {
            $agent = $login->account->agents->where('id', $request->agent_id)->first();
            if (isset($agent)) {
                $result['agent'] = $agent;
                $query->where('agent_id', $request->agent_id);
            }
        }
        $query->orderBy('created_at', 'desc');
        $result['withdraws'] = $query->paginate(20);
        if (isset($result['agent'])) {
            $result['withdraws']->appends(['agent_id' => $result['agent']->id]);
        }
        return view('merchant.bill_withdraws', $result);
    }

}