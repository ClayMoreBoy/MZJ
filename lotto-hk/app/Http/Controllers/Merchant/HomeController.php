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
        $result['statistics'] = $login->account->statistics()->orderBy('issue_id', 'desc')->take(2)->get();
        $result['new_account'] = $login->account->accounts()->where('created_at', '>', date_create('today'))->count();
        return view('merchant.home', $result);
    }

}