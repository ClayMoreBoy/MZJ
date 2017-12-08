<?php
namespace App\Http\Controllers\Merchant;

use App\Models\UMerchantGame;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class GameController extends Controller
{

    public function __construct()
    {
//        $this->middleware('merchant_auth_api')->only(['update']);
    }

    public function setting(Request $request)
    {
        $login = session('_login_merchant');
        $merchant_games = $login->account->games()->get();
        $result['games'] = $merchant_games;
        return view('merchant.games', $result);
    }

    public function update(Request $request)
    {
        $login = session('_login_merchant');
        $key = $request->key;
        $value = $request->value;
        $game = $login->account->games()->where('game_id', $request->id)->first();
        if (isset($game) && isset($key) && isset($value)) {
            if ($key == 'on_off') {
                $game->on_off = $value;
            } elseif ($key == 'odd') {
                $game->odd = $value;
            } elseif ($key == 'odd1') {
                $game->odd1 = $value;
            } else {
                return response()->json(['code' => 401, 'message' => '参数错误']);
            }
            if ($game->save()) {
                return response()->json(['code' => 0, 'message' => 'OK']);
            } else {
                return response()->json(['code' => 500, 'message' => '服务器异常']);
            }
        } else {
            return response()->json(['code' => 401, 'message' => '参数错误']);
        }
    }
}