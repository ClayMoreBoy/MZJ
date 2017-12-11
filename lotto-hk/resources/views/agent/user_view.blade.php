<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="//cdn.bootcss.com/jquery-mobile/1.4.5/jquery.mobile.css" rel="stylesheet">
    <script src="//cdn.bootcss.com/jquery/1.10.2/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/jquery-mobile/1.4.5/jquery.mobile.js"></script>
</head>
<style>
    .ui-btn {
        padding: .3em;
        margin: 0;
    }
</style>
<body>
<div data-role="page" id="main">
    <div data-role="header">
        <h1 class="title">{{ isset($user)?'用户详情':'无效的用户' }}</h1>
        <a href="/agent/user/search/" data-ajax="false"
           class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-back ui-nodisc-icon ui-alt-icon ui-btn-left"></a>
        @if(isset($user))
            <a href="/agent/user/edit/{{ $user->id }}/" data-ajax="false"
               class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-edit ui-nodisc-icon ui-alt-icon ui-btn-right"></a>
        @endif
    </div>
    <div role="main" class="ui-content">
        <div style="margin:-1em -.5em;">
            <ul data-role="listview" data-inset="false" class="ui-mini">
                @if(isset($user))
                    <li data-role="list-divider">基础信息</li>
                    <li>
                        <h3>昵称：{{ $user->nickname }}</h3>
                    </li>
                    <li>
                        <h3>手机号：<a href="tel:{{ $user->phone }}">{{ $user->phone }}</a></h3>
                    </li>
                    <li>
                        <h3>微信号：{{ $user->wx_account }}</h3>
                    </li>
                    <li>
                        <h3>入驻时间：{{ substr($user->created_at,0,16) }}</h3>
                    </li>
                    <li>
                        <h3>最后访问：{{ substr($user->last_access_at,0,16) }}</h3>
                    </li>
                    <li data-role="list-divider">消费信息</li>
                    <li>
                        <h3>账户余额：<strong>￥{{ number_format($user->balance,2) }}</strong>&nbsp;&nbsp;&nbsp;&nbsp;<a
                                    href="/agent/deposit/{{ $user->id }}/" data-ajax="false"
                                    class="ui-btn ui-corner-all ui-btn-inline ui-btn-b">充值</a></h3>
                    </li>
                    <li>
                        <h3>总消费：<strong>￥{{ number_format($user->statistics()->sum('sell_total'),2) }}</strong></h3>
                    </li>
                    <li>
                        <h3>总返奖：<strong>￥{{ number_format($user->statistics()->sum('bonus_total'),2) }}</strong></h3>
                    </li>
                    <li>
                        <h3>
                            最后一次消费：<strong>{{ $user->lastExpenseTime() }}</strong>
                        </h3>
                    </li>
                    <li data-role="list-divider">近5期消费</li>
                    <div class="ui-grid-b">
                        <div class="ui-block-a">
                            <div class="ui-bar ui-bar-a">期数</div>
                        </div>
                        <div class="ui-block-b">
                            <div class="ui-bar ui-bar-a">销售</div>
                        </div>
                        <div class="ui-block-c">
                            <div class="ui-bar ui-bar-a">返奖</div>
                        </div>
                    </div>
                    @foreach($user->statistics()->take(5)->get() as $item)
                        <div class="ui-grid-b">
                            <div class="ui-block-a">
                                <div class="ui-bar ui-bar-a">{{ $item->issue_id }}期</div>
                            </div>
                            <div class="ui-block-b">
                                <div class="ui-bar ui-bar-a">{{ number_format($item->sell_total,2) }}</div>
                            </div>
                            <div class="ui-block-c">
                                <div class="ui-bar ui-bar-a">{{ number_format($item->bonus_total,2) }}</div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </ul>
        </div>
    </div>
</div>
</body>
</html>