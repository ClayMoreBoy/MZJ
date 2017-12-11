<?php
namespace App\Http\Controllers\Agent;

use App\Models\LModel;
use App\Models\UAccount;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    public function __construct()
    {
//        $this->middleware('agent_auth_api')->only(['update']);
    }

    public function search(Request $request)
    {
        $login = session('_login_agent');
        $result = [];
        $query = $login->account->accounts();
        if ($request->has('phone')) {
            $result['phone'] = $request->phone;
            $query->where('phone', 'like', '%' . $request->phone . '%');
        }
        if ($request->has('nickname')) {
            $result['nickname'] = $request->nickname;
            $query->where('nickname', 'like', '%' . $request->nickname . '%');
        }
        if ($request->has('sort_at') && $request->has('sort_rule')) {
            $result['sort_at'] = $request->sort_at;
            $result['sort_rule'] = $request->sort_rule;
            $query->orderBy($request->sort_at, $request->sort_rule);
        }
        $accounts = $query->paginate(20);
        if (isset($result['phone'])) {
            $accounts->appends(['phone' => $result['phone']]);
        }
        if (isset($result['nickname'])) {
            $accounts->appends(['nickname' => $result['nickname']]);
        }
        if ($request->has('sort_at') && $request->has('sort_rule')) {
            $accounts->appends(['sort_at' => $request->sort_at, 'sort_rule' => $request->sort_rule]);
        }
        $result['accounts'] = $accounts;
        return view('agent.user_search', $result);
    }

    public function view(Request $request, $id)
    {
        $login = session('_login_agent');
        $result = [];
        $query = $login->account->accounts()->where('id', $id);
        $user = $query->first();
        if (isset($user)) {
            $result['user'] = $user;
        } else {
            $result['error'] = '无效的代理人';
        }
        return view('agent.user_view', $result);
    }

    public function create(Request $request)
    {
        $result = [];
        if ($request->isMethod('post')) {
            if (!$request->has('phone') || !preg_match('/^1[34578]\d{9}$/', $request->phone)) {
                $request['error'] = '无效的手机号码';
            } else {
                $agent = UAccount::query()->where("phone", $request->phone)->first();
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
                if (!$request->has('wx_account')) {
                    $request['error'] = '微信号不能为空';
                }
            }
            if (!isset($request['error'])) {
                $login = session('_login_agent');
                $account = new UAccount();
                $account->agent_id = $login->account->id;
                $account->merchant_id = $login->account->merchant_id;
                $account->phone = $request->phone;
                $account->wx_account = $request->wx_account;
                $account->nickname = $request->name;
                $password = sha1($request->password);
                $salt = uniqid();
                $account->salt = $salt;
                $account->password = sha1($password . $salt);
                $account->status = LModel::k_status_valid;
                $account->change_password = 0;
                $account->balance = 0;
                $account->gender = 0;
                if ($account->save()) {
                    return redirect('/agent/user/view/' . $account->id);
                } else {
                    $request['error'] = '服务器异常';
                }
            }
        }

        return view('agent.user_create', $result);
    }

    public function edit(Request $request, $id)
    {
        $login = session('_login_agent');
        $result = [];
        $query = $login->account->accounts()->where('id', $id);
        $user = $query->first();
        if (isset($user)) {
            $result['user'] = $user;
        } else {
            $result['error'] = '无效的代理人';
        }
        return view('agent.user_edit', $result);
    }

    public function update(Request $request)
    {
        $login = session('_login_agent');
        $query = $login->account->accounts()->where('id', $request->input('id', 0));
        $user = $query->first();
        if (!isset($user)) {
            return response()->json(['code' => 401, 'message' => '无效的代理人']);
        }
        if ($request->has('key') && $request->has('value')) {
            switch ($request->input('key', '')) {
                case 'phone': {
                    if (!preg_match('/^1[34578]\d{9}$/', $request->value)) {
                        return json_encode(['code' => 401, 'message' => '无效的手机号码']);
                    }
                    $user->phone = $request->value;
                    if ($user->save()) {
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
                    $user->password = sha1($password . $user->salt);
                    if ($user->save()) {
                        return response()->json(['code' => 0, 'message' => 'OK']);
                    } else {
                        return response()->json(['code' => 500, 'message' => '服务器异常']);
                    }
                }
                case 'nickname': {
                    if (strlen($request->value) < 2) {
                        return response()->json(['code' => 401, 'message' => '名称不得少于2位']);
                    }
                    $user->nickname = $request->value;
                    if ($user->save()) {
                        return response()->json(['code' => 0, 'message' => 'OK']);
                    } else {
                        return response()->json(['code' => 500, 'message' => '服务器异常']);
                    }
                }
                case 'wx_account': {
                    $user->wx_account = $request->value;
                    if ($user->save()) {
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