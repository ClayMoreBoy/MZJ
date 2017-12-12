<?php
namespace App\Http\Controllers\Agent;

use App\Models\Issue;
use App\Models\UAccountStatistic;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{

    public function __construct()
    {
//        $this->middleware('auth_api')->only(['balance']);
    }

    public function issue(Request $request)
    {
        $login = session('_login_agent');
        $result['issues'] = $login->account->statistics()->orderBy('issue_id', 'desc')->paginate(20);
        return view('agent.report_issue', $result);
    }

    public function account(Request $request)
    {
        $result['issues'] = Issue::query()->where('date', '<', date_create('12 hour'))->orderBy('id', 'desc')->take(20)->get();
        $issue_id = $request->input('issue');
        if ($request->has('issue')) {
            $issue = Issue::query()->find($issue_id);
        }
        if (!isset($issue)) {
            $issue = $result['issues']->first();
        }
        $result['issue_id'] = $issue->id;

        $login = session('_login_agent');
        $accounts = $login->account->accounts;
        $result['accounts'] = $accounts;

        $account_ids = [];
        foreach ($accounts as $account) {
            $account_ids[] = $account->id;
        }
        $result['uass'] = UAccountStatistic::query()
            ->whereIn('account_id', $account_ids)
            ->where(['issue_id' => $issue->id])
            ->orderBy('issue_id', 'desc')
            ->paginate(20);
        $result['uass']->appends(['issue' => $issue->id]);
        return view('agent.report_account', $result);
    }

}