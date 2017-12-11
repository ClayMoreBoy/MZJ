<?php
namespace App\Http\Controllers\Agent;

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
        $login = session('_login_agent');
        $result['statistics'] = $login->account->statistics()->take(2)->get();
        return view('agent.home', $result);
    }

}