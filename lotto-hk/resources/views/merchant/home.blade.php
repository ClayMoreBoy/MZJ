<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="//cdn.bootcss.com/jquery-mobile/1.4.5/jquery.mobile.css" rel="stylesheet">
    <script src="//cdn.bootcss.com/jquery/1.10.2/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/jquery-mobile/1.4.5/jquery.mobile.js"></script>
    <script src="{{ url('/js/sha1.js') }}"></script>
    <script src="{{ url('/js/alert.js') }}"></script>
    <style>
        .report {
            margin: -1em -1em 1.5em -1em;
        }

        .report > .issue {
            display: inline-block;
            width: 49%;
        }

        .report > .issue > p {
            margin: .5em 0;
            /*font-size: .8em;*/
        }

        .report > .issue > p:first-child {
            text-align: center;
            /*font-size: 1em;*/
        }

        .report > .issue > p > .label {
            display:inline-block;
            text-align: right;
            width: 30%;
        }

        .report > .issue > p > .money {
            display:inline-block;
            text-align: left;
            width: 70%;
            color: #FB223D;
        }

        .footer {
            padding-top: 1em;
            text-align: center;
        }

        .done {
            color: #2ab27b;
        }

        .unknown {
            color: #9BA2AB;
        }
    </style>
</head>
<body>
<div data-role="page" id="main">
    <div data-role="header">
        <h1 class="title">庄家后台首页</h1>
    </div>
    <div role="main" class="ui-content">
        <div class="report">
            @foreach($statistics as $statistic)
                <div class="issue">
                    <p>{{ $statistic->issue_id or '' }}期(<span class="{{ $statistic->issue->status == \App\Models\Issue::k_status_done?'done':'unknown' }}">{{ $statistic->issue->status==\App\Models\Issue::k_status_done?'已结束':'未结算' }}</span>)</p>
                    <p><span class="label">销售:</span><span class="money">￥{{ number_format($statistic->sell_total,2) }}</span></p>
                    <p><span class="label">返奖:</span><span class="money">￥{{ number_format($statistic->bonus_total,2) }}</span></p>
                    <p><span class="label">佣金:</span><span class="money">￥{{ number_format($statistic->commission,2) }}</span></p>
                    <p><span class="label">利润:</span><span class="money">￥{{ number_format($statistic->sell_total-$statistic->bonus_total-$statistic->commission,2) }}</span></p>
                </div>
            @endforeach
        </div>
        <ul data-role="listview">
            <li data-role="list-divider">销售</li>
            <li><a href="/merchant/order/issue/" data-ajax="false">销售详情</a></li>
            <li><a href="/merchant/order/search/" data-ajax="false">订单列表</a></li>
            <li data-role="list-divider">报表</li>
            <li><a href="/merchant/report/issue/" data-ajax="false">总销售报表</a></li>
            <li><a href="/merchant/report/agent/" data-ajax="false">代理人报表</a></li>
            <li><a href="/merchant/report/account/" data-ajax="false">用户报表</a></li>
            <li data-role="list-divider">财务</li>
            <li><a href="/merchant/bill/deposits/" data-ajax="false">充值记录</a></li>
            <li><a href="/merchant/bill/withdraws/" data-ajax="false">提现记录</a></li>
            <li data-role="list-divider">用户</li>
            <li><a href="/merchant/user/search/" data-ajax="false">用户列表</a></li>
            <li><a href="/merchant/user/today/" data-ajax="false">新用户<span class="ui-li-count">{{ $new_account }}</span></a></li>
            <li data-role="list-divider">代理人</li>
            <li><a href="/merchant/agent/search/" data-ajax="false">代理人列表</a></li>
            <li><a href="/merchant/agent/create/" data-ajax="false">新增代理人</a></li>
            <li data-role="list-divider">设置</li>
            <li><a href="/merchant/game/setting/" data-ajax="false">玩法配置</a></li>
            <li><a href="/merchant/change-password/" data-ajax="false">修改密码</a></li>
            <li><a href="/merchant/logout/" data-ajax="false">退出登录</a></li>
        </ul>
        <div class="footer">
            <p>
                我的域名:<a href="{{ $domain }}" data-ajax="false" target="_blank">{{ $domain }}</a>
            </p>
        </div>
    </div>
</div>
</body>
<script>
</script>
@if(isset($error))
    <script>
        $(function () {
            LAlert('{{ $error }}', 'b');
        });
    </script>
@endif
</html>