<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="//cdn.bootcss.com/jquery-mobile/1.4.5/jquery.mobile.css" rel="stylesheet">
    <script src="//cdn.bootcss.com/jquery/1.10.2/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/jquery-mobile/1.4.5/jquery.mobile.js"></script>
    <style>
        .ui-bar {
            padding: .4em .4em;
        }

        .ui-btn-inline {
            padding: .3em;
            margin: 0;
            float: right;
        }
    </style>
</head>
<body>
<div data-role="page" id="main">
    <div data-role="header">
        <h1 class="title">提现列表</h1>
        <a href="/agent/" data-ajax="false"
           class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-back ui-nodisc-icon ui-alt-icon ui-btn-left"></a>
        <a href="#conditions_panel"
           class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-bullets ui-nodisc-icon ui-alt-icon ui-btn-right"></a>
    </div>

    {{--右侧栏--}}
    <div id="conditions_panel" data-role="panel" data-position-fixed="true" data-position="right" data-display="push">
        <ul data-role="listview" data-filter="true" data-filter-reveal="true" data-filter-placeholder="用户昵称"
            data-inset="true">
            @foreach($account->accounts as $user)
                <li><a href="?account_id={{ $user->id }}">{{ $user->nickname }}</a></li>
            @endforeach
        </ul>
    </div>

    <div role="main" class="ui-content ui-mini">
        <div style="margin:-1.5em -1em;">
            <ul data-role="listview" data-inset="true">
                @if(isset($filter_user))
                    <div class="ui-grid-solo">
                        <div class="ui-block-a">
                            <div class="ui-bar ui-bar-b">
                                <h3 style="line-height: 2.4em;height: 2.4em;">用户：{{ $filter_user->nickname }}</h3>
                                <a href="/agent/bill/withdraws/" class="ui-btn ui-icon-delete ui-btn-icon-notext ui-corner-all ui-btn-inline"></a>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="ui-grid-b">
                    <div class="ui-block-a">
                        <div class="ui-bar ui-bar-a">用户</div>
                    </div>
                    <div class="ui-block-c">
                        <div class="ui-bar ui-bar-a">金额</div>
                    </div>
                    <div class="ui-block-c">
                        <div class="ui-bar ui-bar-a">时间</div>
                    </div>
                </div>
                @foreach($withdraws as $withdraw)
                    <div class="ui-grid-b">
                        <div class="ui-block-a">
                            <div class="ui-bar ui-bar-a">{{ $withdraw->account->nickname }}</div>
                        </div>
                        <div class="ui-block-c">
                            <div class="ui-bar ui-bar-a">{{ number_format($withdraw->fee,2) }}</div>
                        </div>
                        <div class="ui-block-c">
                            <div class="ui-bar ui-bar-a">{{ substr($withdraw->updated_at,5,11) }}</div>
                        </div>
                    </div>
                @endforeach
            </ul>
            <div data-role="controlgroup" data-type="horizontal" data-mini="true">
                <a href="{{ $withdraws->url(1) }}"
                   class="ui-btn ui-corner-all {{ $withdraws->currentPage()==1?'ui-state-disabled':'' }}"> 首页 </a>
                <a href="{{ $withdraws->previousPageUrl() }}"
                   class="ui-btn ui-corner-all {{ $withdraws->currentPage()==1?'ui-state-disabled':'' }}"> 上一页 </a>
                <a href="#" class="ui-btn ui-corner-all">{{ $withdraws->currentPage() }}</a>
                <a href="{{ $withdraws->nextPageUrl() }}"
                   class="ui-btn ui-corner-all {{ $withdraws->currentPage()==$withdraws->lastPage()?'ui-state-disabled':'' }}">
                    下一页 </a>
                <a href="{{ $withdraws->url($withdraws->lastPage()) }}"
                   class="ui-btn ui-corner-all {{ $withdraws->currentPage()==$withdraws->lastPage()?'ui-state-disabled':'' }}">
                    尾页 </a>
            </div>
        </div>
    </div>
</div>
</body>
</html>