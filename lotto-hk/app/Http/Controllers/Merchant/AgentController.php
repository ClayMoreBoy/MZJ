<?php
namespace App\Http\Controllers\Merchant;

use App\Models\LModel;
use App\Models\UAgentAccount;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class AgentController extends Controller
{

    public function __construct()
    {
//        $this->middleware('merchant_auth_api')->only(['update']);
    }

    public function search(Request $request)
    {
        $login = session('_login_merchant');
        $result = [];
        $query = $login->account->agents();
        $agents = $query->get();
        $result['agents'] = $agents;
        return view('merchant.agent_search', $result);
    }

    public function view(Request $request, $id)
    {
        $login = session('_login_merchant');
        $result = [];
        $query = $login->account->agents()->where('id', $id);
        $agent = $query->first();
        if (isset($agent)) {
            $result['agent'] = $agent;
        } else {
            $result['error'] = '无效的代理人';
        }
        return view('merchant.agent_view', $result);
    }

    public function create(Request $request)
    {
        $result = [];
        if ($request->isMethod('post')) {
            if (!$request->has('phone') || !preg_match('/^1[34578]\d{9}$/', $request->phone)) {
                $request['error'] = '无效的手机号码';
            } else {
                $agent = UAgentAccount::query()->where("phone", $request->phone)->first();
                if (isset($agent)) {
                    $request['error'] = '手机号码已经存在';
                }
            }
            if (!isset($request['error'])) {
                if (!$request->has('password') || strlen($request->password) < 6) {
                    $request['error'] = '密码不得少于6位';
                }
            }
            if (!isset($request['error'])) {
                if (!$request->has('name') || strlen($request->name) < 2) {
                    $request['error'] = '名称不得少于2';
                }
            }
            if (!isset($request['error'])) {
                if (!$request->has('commission') || $request->commission < 1 || $request->commission > 10) {
                    $request['error'] = '佣金只能在1-10之间';
                }
            }
            if (!isset($request['error'])) {
                if (!$request->has('wx_account')) {
                    $request['error'] = '微信号不能为空';
                }
            }
            if (!isset($request['error'])) {
                if (!$request->has('prefix_domain') && preg_match('/^[A-Za-z0-9]+$/', $request->prefix_domain)) {
                    $request['error'] = '域名只能用字母或者数字';
                }
            }
            if (!isset($request['error'])) {
                $login = session('_login_merchant');
                $agent = new UAgentAccount();
                $agent->merchant_id = $login->account->id;
                $agent->phone = $request->phone;
                $agent->domain = $request->prefix_domain;
                $agent->name = $request->name;
                $agent->nickname = $request->name;
                $agent->commission = $request->commission;
                $password = sha1($request->password);
                $salt = uniqid();
                $agent->salt = $salt;
                $agent->password = sha1($password . $salt);
                $agent->status = LModel::k_status_valid;
                $agent->change_password = 0;
                $agent->wx_account = $request->wx_account;
                if ($request->has('wx_qr')) {
                    $agent->wx_qr = $request->wx_qr;
                }
                if ($agent->save()) {
                    return redirect('/merchant/agent/view/' . $agent->id);
                } else {
                    $request['error'] = '服务器异常';
                }
            }
        }

        return view('merchant.agent_create', $result);
    }

    public function edit(Request $request, $id)
    {
        $login = session('_login_merchant');
        $result = [];
        $query = $login->account->agents()->where('id', $id);
        $agent = $query->first();
        if (isset($agent)) {
            $result['agent'] = $agent;
        } else {
            $result['error'] = '无效的代理人';
        }
        return view('merchant.agent_edit', $result);
    }

    public function update(Request $request)
    {
        $login = session('_login_merchant');
        $query = $login->account->agents()->where('id', $request->input('id', 0));
        $agent = $query->first();
        if (!isset($agent)) {
            return response()->json(['code' => 401, 'message' => '无效的代理人']);
        }
        if ($request->has('key') && $request->has('value')) {
            switch ($request->input('key', '')) {
                case 'phone': {
                    if (!preg_match('/^1[34578]\d{9}$/', $request->value)) {
                        return json_encode(['code' => 401, 'message' => '无效的手机号码']);
                    }
                    $agent->phone = $request->value;
                    if ($agent->save()) {
                        return response()->json(['code' => 0, 'message' => 'OK']);
                    } else {
                        return json_encode(['code' => 500, 'message' => '服务器异常']);
                    }
                }
                case 'password': {
                    if (strlen($request->value) < 6) {
                        return response()->json(['code' => 401, 'message' => '密码不得少于6位']);
                    }
                    $password = sha1($request->value);
                    $agent->password = sha1($password . $agent->salt);
                    if ($agent->save()) {
                        return response()->json(['code' => 0, 'message' => 'OK']);
                    } else {
                        return response()->json(['code' => 500, 'message' => '服务器异常']);
                    }
                }
                case 'name': {
                    if (strlen($request->value) < 2) {
                        return response()->json(['code' => 401, 'message' => '名称不得少于2位']);
                    }
                    $agent->name = $request->value;
                    if ($agent->save()) {
                        return response()->json(['code' => 0, 'message' => 'OK']);
                    } else {
                        return response()->json(['code' => 500, 'message' => '服务器异常']);
                    }
                }
                case 'commission': {
                    if ($request->value < 1 || $request->value > 10) {
                        return response()->json(['code' => 401, 'message' => '佣金只能在1-10之间']);
                    }
                    $agent->commission = $request->value;
                    if ($agent->save()) {
                        return response()->json(['code' => 0, 'message' => 'OK']);
                    } else {
                        return response()->json(['code' => 500, 'message' => '服务器异常']);
                    }
                }
                case 'prefix_domain': {
                    if (!preg_match('/^[a-zA-Z0-9]+$/', $request->value)) {
                        return response()->json(['code' => 401, 'message' => '无效的域名']);
                    }
                    $agent->domain = $request->value;
                    if ($agent->save()) {
                        return response()->json(['code' => 0, 'message' => 'OK']);
                    } else {
                        return response()->json(['code' => 500, 'message' => '服务器异常']);
                    }
                }
                case 'wx_account': {
                    $agent->wx_account = $request->value;
                    if ($agent->save()) {
                        return response()->json(['code' => 0, 'message' => 'OK']);
                    } else {
                        return response()->json(['code' => 500, 'message' => '服务器异常']);
                    }
                }
                case 'wx_qr': {
                    $agent->wx_qr = $request->value;
                    if ($agent->save()) {
                        return response()->json(['code' => 0, 'message' => 'OK']);
                    } else {
                        return response()->json(['code' => 500, 'message' => '服务器异常']);
                    }
                }
            }
        } else {
            return response()->json(['code' => 401, 'message' => '参数错误']);
        }
    }
}