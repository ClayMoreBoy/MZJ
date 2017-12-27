<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="//cdn.bootcss.com/jquery-mobile/1.4.5/jquery.mobile.css" rel="stylesheet">
    <script src="//cdn.bootcss.com/jquery/1.10.2/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/jquery-mobile/1.4.5/jquery.mobile.js"></script>
</head>
<body>
<div data-role="page" id="main">
    <div data-role="header">
        <h1 class="title">{{ isset($agent)?'代理人详情':'无效的代理人' }}</h1>
        <a href="/merchant/agent/search/" data-ajax="false"
           class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-back ui-nodisc-icon ui-alt-icon ui-btn-left"></a>
        @if(isset($agent))
            <a href="/merchant/agent/edit/{{ $agent->id }}/" data-ajax="false"
               class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-edit ui-nodisc-icon ui-alt-icon ui-btn-right"></a>
        @endif
    </div>
    <div role="main" class="ui-content">
        <div style="margin:-1em -.5em;">
            <ul data-role="listview" data-inset="false" class="ui-mini">
                @if(isset($agent))
                    <li data-role="list-divider">基础信息</li>
                    <li>
                        <h3>名称：<strong>{{ $agent->name }}</strong></h3>
                    </li>
                    <li>
                        <h3>手机号：<a href="tel:{{ $agent->phone }}">{{ $agent->phone }}</a></h3>
                    </li>
                    <li>
                        <h3>域名：<a href="http://{{ $agent->domain.'.'.$domain.'/mobiles/' }}"
                                  data-ajax="false">{{ $agent->domain.'.'.$domain }}</a></h3>
                    </li>
                    <li>
                        <h3>佣金：<strong>{{ $agent->commission }}</strong></h3>
                    </li>
                    <li>
                        <h3>微信号：{{ $agent->wx_account }}</h3>
                    </li>
                    <li>
                        <h3>微信二维码</h3>
                        <p><img src="{{ $agent->wx_qr }}" width="90%"></p>
                    </li>
                    <li data-role="list-divider">用户信息</li>
                    <li>
                        <h3>用户数：<strong>{{ $agent->accounts()->count() }}</strong></h3>
                    </li>
                    <li>
                        <h3>总销售：<strong>{{ number_format($agent->statistics()->sum('sell_total'),2,'.','') }}</strong></h3>
                    </li>
                    <li>
                        <h3>总返奖：<strong>{{ number_format($agent->statistics()->sum('bonus_total'),2,'.','') }}</strong></h3>
                    </li>
                    <li data-role="list-divider">近5期销售</li>
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
                    @foreach($agent->statistics()->take(5)->get() as $item)
                        <div class="ui-grid-b">
                            <div class="ui-block-a">
                                <div class="ui-bar ui-bar-a">{{ $item->issue_id }}期</div>
                            </div>
                            <div class="ui-block-b">
                                <div class="ui-bar ui-bar-a">{{ number_format($item->sell_total,2,'.','') }}</div>
                            </div>
                            <div class="ui-block-c">
                                <div class="ui-bar ui-bar-a">{{ number_format($item->bonus_total,2,'.','') }}</div>
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