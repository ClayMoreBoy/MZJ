<?php
namespace App\Http\Controllers\Inner;

use App\Models\LModel;
use App\Models\UGame;
use App\Models\UMerchantAccount;
use App\Models\UMerchantGame;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function createMerchantAccount(Request $request)
    {
        if ($request->isMethod('post')) {
            $phone = $request->input("phone");
            $password = $request->input("password");
            $name = $request->input("name");
            $domain = $request->input("prefix_domain");
            $code = $request->input("code", 0);
            if ($code != 'xxooxx') {
                return response()->json(["code" => 401, "msg" => "验证码错误"]);
            }
            if (empty($phone) || empty($password) || empty($name) || empty($domain)) {
                return response()->json(["code" => 401, "msg" => "参数错误"]);
            }
            $uma = UMerchantAccount::query()->where('phone', $phone)->first();
            if (isset($uma)) {
                return response()->json(["code" => 401, "msg" => "手机号已被注册！"]);
            }
            DB::beginTransaction();
            $uma = new UMerchantAccount();
            $uma->phone = $phone;
            $uma->name = $name;
            $uma->nickname = $name;
            $uma->domain = $domain;
            $salt = uniqid();
            $uma->salt = $salt;
            $uma->password = sha1(sha1($password) . $salt);
            $uma->status = LModel::k_status_valid;
            if ($uma->save()) {
                $games = UGame::query()->get();
                foreach ($games as $game) {
                    $umg = new UMerchantGame();
                    $umg->merchant_id = $uma->id;
                    $umg->game_id = $game->id;
                    $umg->name = $game->name;
                    $umg->on_off = $game->on_off;
                    $umg->odd = $game->odd;
                    $umg->odd1 = $game->odd1;
                    $umg->path = $game->path;
                    $umg->items_min = $game->items_min;
                    $umg->items_max = $game->items_max;
                    $umg->sort = $game->sort;
                    if (!$umg->save()) {
                        DB::rollBack();
                        return response()->json(["code" => 401, "msg" => "服务器异常！"]);
                    }
                }
            } else {
                DB::rollBack();
                return response()->json(["code" => 401, "msg" => "服务器异常！"]);
            }
            DB::commit();
            return response()->json(["code" => 0, "msg" => "OK"]);
        }
        return view('inner.created_merchant');
    }

    public function syncGames(Request $request)
    {
        if ($request->has('id')) {
            $umas = UMerchantAccount::query()->where('id', $request->input('id'))->get();
        } else {
            $umas = UMerchantAccount::query()->get();
        }
        if (isset($umas)) {
            $games = UGame::query()->get();
            foreach ($umas as $uma) {
                foreach ($games as $game) {
                    $umg = $uma->games()->where('game_id', $game->id)->first();
                    if (isset($umg)) {
                        $umg->name = $game->name;
                        $umg->path = $game->path;
                        $umg->items_min = $game->items_min;
                        $umg->items_max = $game->items_max;
                        $umg->sort = $game->sort;
                        $umg->save();
                    } else {
                        $umg = new UMerchantGame();
                        $umg->merchant_id = $uma->id;
                        $umg->game_id = $game->id;
                        $umg->name = $game->name;
                        $umg->on_off = $game->on_off;
                        $umg->odd = $game->odd;
                        $umg->odd1 = $game->odd1;
                        $umg->path = $game->path;
                        $umg->items_min = $game->items_min;
                        $umg->items_max = $game->items_max;
                        $umg->sort = $game->sort;
                        $umg->save();
                    }
                }
            }
        }
    }
}