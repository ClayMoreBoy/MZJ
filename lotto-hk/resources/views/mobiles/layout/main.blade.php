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
    </style>
    @yield('css')
</head>
<body>
<div data-role="page" id="main">
    {{--左侧栏--}}
    <div id="nav_panel" data-role="panel" data-position-fixed="true" data-position="left" data-display="push">
        <div class="nav-logo">
            <h1>六合娱乐</h1>
        </div>
        <ul data-role="listview">
            <li data-role="list-divider"><h3>网站、导航</h3></li>
            <li data-icon="false" data-theme="b"><a href="/mobiles/" data-ajax="false">开奖查询</a></li>
            <li><a href="/mobiles/tools/" data-ajax="false">工具图表</a></li>
            <li><a href="/mobiles/columns/" data-ajax="false">大神预测</a></li>
            <li><a href="/mobiles/newspapers/" data-ajax="false">天机图</a></li>
            <li data-role="list-divider"><h3>投注、玩法</h3></li>
            <li><a href="/mobiles/tz_te/" data-ajax="false">特码</a></li>
            <li><a href="/mobiles/tz_ping/" data-ajax="false">平特</a></li>
        </ul>
    </div>
    {{--右侧栏--}}
    <div id="user_panel" data-role="panel" data-position-fixed="true" data-position="right" data-display="push">
        <div class="nav-user">
            <img src="/img/mobiles/user.png" width="30%">
            <p>用户昵称</p>
            <p>余额：￥123.12&ensp;
                <a href="#" class="ui-btn ui-btn-inline ui-icon-refresh ui-btn-icon-notext ui-corner-all"></a>
            </p>
            {{--<p>--}}
            {{--<a href="#" class="ui-btn ui-corner-all ui-btn-inline ui-icon-delete ui-btn-icon-left ui-btn-b">退出</a>--}}
            {{--<a href="#popupLogin" data-rel="popup" data-position-to="window"--}}
            {{--class="ui-btn ui-corner-all ui-btn-inline ui-icon-check ui-btn-icon-left"--}}
            {{--data-transition="pop">登陆</a>--}}
            {{--</p>--}}
        </div>
        <ul data-role="listview">
            <li data-role="list-divider"><h3>账户</h3></li>
            <li><a href="#">提现</a></li>
            <li data-role="list-divider"><h3>订单</h3></li>
            <li><a href="#"><span class="ui-li-count">12</span>未结算订单</a></li>
            <li><a href="#">已结算订单</a></li>
        </ul>
        <div class="nav-agent">
            <p>代理人微信</p>
            <img src="/img/mobiles/qr.png" width="100%">
        </div>
    </div>

    {{--登陆--}}
    <div data-role="popup" id="popupLogin" data-overlay-theme="b" data-theme="a" class="ui-corner-all">
        <a href="#" data-rel="back"
           class="ui-btn ui-btn-b ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>
        <form action="/auth/login" method="post">
            <div class="login">
                <h3>登陆</h3>
                <label for="un" class="ui-hidden-accessible">用户名:</label>
                <input type="text" name="user" id="un" value="" placeholder="用户名" data-theme="a">
                <label for="pw" class="ui-hidden-accessible">密码:</label>
                <input type="password" name="pass" id="pw" value="" placeholder="密码" data-theme="a">
                <button type="submit" class="ui-btn ui-corner-all ui-shadow ui-btn-b ui-btn-icon-left ui-icon-check">
                    登陆
                </button>
            </div>
        </form>
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
<script src="//cdn.bootcss.com/jquery/1.10.2/jquery.min.js"></script>
<script src="//cdn.bootcss.com/jquery-mobile/1.4.5/jquery.mobile.js"></script>
<script>
</script>
@yield('js')
</html>

