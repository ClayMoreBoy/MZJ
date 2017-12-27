<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="//cdn.bootcss.com/jquery-mobile/1.4.5/jquery.mobile.css" rel="stylesheet">
    <script src="//cdn.bootcss.com/jquery/1.10.2/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/jquery-mobile/1.4.5/jquery.mobile.js"></script>
    <style>
        .win {
            color: #FB223D;
        }

        .lose {
            color: #2ab27b;
        }

        .ui-bar {
            padding: .4em .4em;
        }
    </style>
</head>
<body>
<div data-role="page" id="main">
    <div data-role="header">
        <h1 class="title">提现列表</h1>
        <a href="/merchant/" data-ajax="false"
           class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-back ui-nodisc-icon ui-alt-icon ui-btn-left"></a>
        <a href="#conditions_panel"
           class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-bullets ui-nodisc-icon ui-alt-icon ui-btn-right"></a>
    </div>

    {{--右侧栏--}}
    <div id="conditions_panel" data-role="panel" data-position-fixed="true" data-position="right" data-display="push">
        <ul data-role="listview" data-inset="false">
            <li data-role="list-divider">代理人列表</li>
            @foreach($agents as $a)
                @if(isset($agent) && $a->id == $agent->id)
                    <li data-theme="b"><a href="?agent_id="><h3>{{ $a->name }}</h3></a></li>
                @else
                    <li><a href="?agent_id={{ $a->id }}"><h3>{{ $a->name }}</h3></a></li>
                @endif
            @endforeach
        </ul>
    </div>

    <div role="main" class="ui-content ui-mini">
        <div style="margin:-1.5em -1em;">
            <ul data-role="listview" data-inset="true">
                @if(isset($agent))
                    <div class="ui-grid-solo">
                        <div class="ui-block-a">
                            <div class="ui-bar ui-bar-b">代理人:{{ $agent->name }}</div>
                        </div>
                    </div>
                @endif
                <div class="ui-grid-c">
                    <div class="ui-block-a">
                        <div class="ui-bar ui-bar-a">用户</div>
                    </div>
                    <div class="ui-block-b">
                        <div class="ui-bar ui-bar-a">代理人</div>
                    </div>
                    <div class="ui-block-c">
                        <div class="ui-bar ui-bar-a">金额</div>
                    </div>
                    <div class="ui-block-c">
                        <div class="ui-bar ui-bar-a">时间</div>
                    </div>
                </div>
                @foreach($withdraws as $withdraw)
                    <div class="ui-grid-c">
                        <div class="ui-block-a">
                            <div class="ui-bar ui-bar-a">{{ $withdraw->account->nickname }}</div>
                        </div>
                        <div class="ui-block-b">
                            <div class="ui-bar ui-bar-a">{{ $withdraw->agent->name }}</div>
                        </div>
                        <div class="ui-block-c">
                            <div class="ui-bar ui-bar-a">{{ number_format($withdraw->fee,2,'.','') }}</div>
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