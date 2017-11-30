<?php
namespace App\Http\Controllers\Inner;

use App\Models\LModel;
use App\Models\UAccount;
use App\Models\UAgentAccount;
use App\Models\UMerchantAccount;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function createMerchantAccount(Request $request)
    {
        $phone = $request->input("phone");
        $password = $request->input("password");
        $name = $request->input("name");
        if (empty($phone) || empty($password) || empty($name)) {
            return response()->json(["code" => 401, "msg" => "参数错误"]);
        }
        $uma = UMerchantAccount::query()->where('phone', $phone)->first();
        if (isset($uma)) {
            return response()->json(["code" => 401, "msg" => "手机号已被注册！"]);
        }
        $uma = new UMerchantAccount();
        $uma->phone = $phone;
        $uma->name = $name;
        $uma->nickname = $phone;
        $salt = uniqid();
        $uma->salt = $salt;
        $uma->password = sha1($password . $salt);
        $uma->status = LModel::k_status_valid;
        $uma->save();
        return response()->json(["code" => 0]);
    }

    public function createAgentAccount(Request $request)
    {
        $phone = $request->input("phone");
        $password = $request->input("password");
        $name = $request->input("name");
        $merchant = $request->input("merchant");
        if (empty($phone) || empty($password) || empty($name)) {
            return response()->json(["code" => 401, "msg" => "参数错误"]);
        }
        $uma = UMerchantAccount::query()->find($merchant);
        if (empty($uma)) {
            return response()->json(["code" => 401, "msg" => "店主不存在！"]);
        }
        $uaa = UAgentAccount::query()->where('phone', $phone)->first();
        if (isset($uaa)) {
            return response()->json(["code" => 401, "msg" => "手机号已被注册！"]);
        }
        $uaa = new UAgentAccount();
        $uaa->phone = $phone;
        $uaa->merchant_id = $merchant;
        $uaa->name = $name;
        $uaa->nickname = $phone;
        $salt = uniqid();
        $uaa->salt = $salt;
        $uaa->password = sha1($password . $salt);
        $uaa->status = LModel::k_status_valid;
        $uaa->save();
        return response()->json(["code" => 0]);
    }

    public function createAccount(Request $request)
    {
        $phone = $request->input("phone");
        $password = $request->input("password");
        $name = $request->input("name");
        if (empty($phone) || empty($password)) {
            return response()->json(["code" => 401, "msg" => "参数错误"]);
        }
        $agent = $request->input("agent");
        $uaa = UAgentAccount::query()->find($agent);
        if (empty($uaa)) {
            return response()->json(["code" => 401, "msg" => "代理不存在！"]);
        }
        $ua = UAccount::query()->where('phone', $phone)->first();
        if (!isset($ua)) {
            $ua = new UAccount();
        }
        $ua->phone = $phone;
        $ua->agent_id = $agent;
        $ua->merchant_id = $uaa->merchant_id;
        $ua->nickname = isset($name) ? $name : $phone;
        $salt = uniqid();
        $ua->salt = $salt;
        $ua->password = sha1($password . $salt);
        $ua->balance = 0;
        $ua->change_password = 0;
        $ua->gender = 0;
        $ua->status = LModel::k_status_valid;
        $ua->save();
        return response()->json(["code" => 0]);
    }
}