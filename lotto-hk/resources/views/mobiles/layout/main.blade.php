<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="//cdn.bootcss.com/jquery-mobile/1.4.5/jquery.mobile.css" rel="stylesheet">
    <style>
        .ui-panel-inner {
            background: #f9f9f9;
        }

        .nav-logo {
            margin: -1em;
            text-align: center;
            height: 5em;
        }

        .nav-user {
            margin: -1em;
            text-align: center;
            background: #f9f9f9;
            padding-top: 1em;
            padding-bottom: 1em;
        }

        .nav-agent {
            margin: -1em;
            text-align: center;
            background: #f9f9f9;
            padding-bottom: 1em;
            padding-top: 1em;
        }

        .login {
            padding: 10px 20px;
            text-align: center;
        }

        .balance {
            color: crimson;
        }
    </style>
    @yield('css')
    <script src="//cdn.bootcss.com/jquery/1.10.2/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/jquery-mobile/1.4.5/jquery.mobile.js"></script>
    <script src="{{ url('/js/sha1.js') }}"></script>
    <script src="{{ url('/js/alert.js') }}"></script>
</head>
<body>
<div data-role="page" id="main">
    {{--左侧栏--}}
    <div id="nav_panel" data-role="panel" data-position-fixed="true" data-position="left" data-display="push">
        <div class="nav-logo">
            <h1>{{ $merchant->name or '六和娱乐' }}</h1>
        </div>
        <ul data-role="listview">
            <li data-role="list-divider"><h3>网站、导航</h3></li>
            @if(starts_with(request()->path(),'mobiles/home') || ends_with(str_finish(request()->path(),'/'),'mobiles/'))
                <li data-icon="false" data-theme="b">开奖查询</li>
            @else
                <li><a href="/mobiles/home/" data-ajax="false">开奖查询</a></li>
            @endif
            @if(starts_with(request()->path(),'mobiles/tools'))
                <li data-icon="false" data-theme="b">工具图表</li>
            @else
                <li><a href="/mobiles/tools/" data-ajax="false">工具图表</a></li>
            @endif

            @if(starts_with(request()->path(),'mobiles/columns'))
                <li data-icon="false" data-theme="b">大神预测</li>
            @else
                <li><a href="/mobiles/columns/" data-ajax="false">大神预测</a></li>
            @endif
            @if(starts_with(request()->path(),'mobiles/newspapers'))
                <li data-icon="false" data-theme="b">天机图</li>
            @else
                <li><a href="/mobiles/newspapers/" data-ajax="false">天机图</a></li>
            @endif

            <li data-role="list-divider"><h3>投注、玩法</h3></li>
            @if (isset($merchant) && $merchant->status == 1)
                @foreach($merchant->games as $item)
                    @if(starts_with(request()->path(),'mobiles/games/'.$item->path))
                        <li data-icon="false" data-theme="b">{{ $item->name }}</li>
                    @else
                        <li><a href="{{ '/mobiles/games/'.$item->path.'/' }}" data-ajax="false">{{ $item->name }}</a>
                        </li>
                    @endif
                @endforeach
            @elseif(isset($merchant))
                <li>该店暂无开通任何玩法</li>
            @else
                <li>无店铺信息</li>
            @endif
        </ul>
    </div>
    {{--右侧栏--}}
    <div id="user_panel" data-role="panel" data-position-fixed="true" data-position="right" data-display="push">
        <div class="nav-user">
            @if(isset($account))
                <img src="{{ $account->icon }}" width="30%">
                <p><strong>{{ $account->nickname }}</strong></p>
                <p>余额：<strong
                            class="balance" id="balance">￥{{ number_format($account->balance, 2, '.', '') }}</strong>&nbsp;&nbsp;
                    <a href="javascript:refreshBalance()"
                       class="ui-btn ui-btn-inline ui-icon-refresh ui-btn-icon-notext ui-corner-all"></a>
                </p>
            @else
                <p>未登录
                    <a href="#popupLogin" data-rel="popup" data-position-to="window" data-transition="pop"
                       class="ui-btn ui-btn-inline ui-corner-all ui-mini">点击登录</a>
                </p>
            @endif
        </div>
        <ul data-role="listview" data-count-theme="b">
            @if(isset($account))
                <li data-role="list-divider"><h3>账户</h3></li>
                <li>
                    <a href="#popupAgentInfo" data-rel="popup" data-position-to="window" data-transition="pop">充值</a>
                </li>
                <li><a href="#">提现</a></li>
                <li><a href="#">账单流水</a></li>
                <li data-role="list-divider"><h3>订单</h3></li>
                <li>
                    <a href="/mobiles/order/order_curr/" data-ajax="false">
                        <span class="ui-li-count new">{{ $account->orders()->where('status',\App\Models\UOrder::k_status_unknown)->count() }}</span>未结算订单
                    </a>
                </li>
                <li>
                    <a href="/mobiles/order/order_history/" data-ajax="false">已结算订单</a>
                </li>
                <li data-role="list-divider"><h3>操作</h3></li>
                <li>
                    <a href="/mobiles/change-password/?target={{ urlencode(url()->current()) }}" data-ajax="false">修改密码</a>
                </li>
                <li>
                    <a href="#popupLogout" data-rel="popup" data-position-to="window" data-transition="pop">退出登录</a>
                </li>
            @endif
        </ul>
        @if(isset($agent))
            <div class="nav-agent">
                <p>注册、充值请联系代理人</p>
                <p>代理人微信号：<strong class="balance">{{ $agent->wx_account }}</strong></p>
                <img src="{{ $agent->wx_qr }}" width="100%">
            </div>
        @endif
    </div>

    {{--登陆--}}
    <div data-role="popup" id="popupLogin" data-overlay-theme="b" data-theme="a" class="ui-corner-all">
        <a href="#" data-rel="back"
           class="ui-btn ui-btn-b ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>
        <form action="/mobiles/auth/" method="post" data-ajax="false" onsubmit="return verifyForm(this);">
            <div class="login">
                <h3>登陆</h3>
                {{ csrf_field() }}
                <input type="hidden" name="target" value="{{ url()->current() }}">
                <label for="phone" class="ui-hidden-accessible">手机:</label>
                <input type="number" name="phone" id="phone" value="" placeholder="手机" data-theme="a">
                <label for="password" class="ui-hidden-accessible">密码:</label>
                <input type="password" name="password" id="password" value="" placeholder="密码" data-theme="a">
                <button type="submit" class="ui-btn ui-corner-all ui-shadow ui-btn-b ui-btn-icon-left ui-icon-check">
                    登陆
                </button>
            </div>
        </form>
    </div>

    {{--退出登陆--}}
    <div data-role="popup" id="popupLogout" data-overlay-theme="b" data-theme="b" data-dismissible="false"
         style="max-width:400px;">
        <div data-role="header" data-theme="a"><h1>提示</h1></div>
        <div role="main" class="ui-content">
            <p>您确定要退出登录吗？</p>
            <a href="/mobiles/logout/" data-ajax="false"
               class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b ui-icon-check ui-btn-icon-left">确定</a>
            <a href="#" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b ui-icon-delete ui-btn-icon-left"
               data-rel="back" data-transition="flow">取消</a>
        </div>
    </div>

    {{--代理人微信--}}
    <div data-role="popup" id="popupAgentInfo" class="photopopup" data-overlay-theme="a" data-corners="false"
         data-tolerance="30,15">
        <a href="#" data-rel="back"
           class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>
        <h3>注册、充值请联系代理人</h3>
        @if(isset($agent))
            <p>代理人微信号：<strong class="balance">{{ $agent->wx_account }}</strong></p>
            <img src="{{ $agent->wx_qr }}" width="100%">
        @endif
    </div>

    {{--导航--}}
    <div data-role="header" data-position="fixed">
        <a href="#nav_panel"
           class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-bars ui-nodisc-icon ui-alt-icon ui-btn-left">Grid</a>
        <a href="#user_panel"
           class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-user ui-nodisc-icon ui-alt-icon ui-btn-right">User</a>
        <h1>@yield('title')</h1>
        @yield('nav')
    </div>

    <div role="main" class="ui-content">
        @yield('content')
    </div>

    @yield('footer')
</div>

@yield('other_page')

</body>
@yield('js')
<script>
    function refreshBalance(hideLoading) {
        if (!hideLoading)$.mobile.loading("show");
        $.getJSON('/mobiles/account/balance.json', function (data) {
            if (!hideLoading)$.mobile.loading("hide");
            if (data.code == 0) {
                $('#balance').html('￥' + data.balance);
                $('.new').html(data.new_order)
            } else {
                LAlert(data.message, 'b');
            }
        });
    }

    function verifyForm(form) {
        if ($.trim(form.phone.value) === '' || $.trim(form.password.value) === '') {
            LAlert('手机号码或者密码不能为空', 'b');
            return false;
        }
        form.password.value = hex_sha1($.trim(form.password.value));
        return true;
    }
</script>
@if(isset($error))
    <script>
        $(function () {
            LAlert('{{ $error }}', 'b');
        });
    </script>
@endif
</html>

