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
        <h1 class="title">代理人报表</h1>
        <a href="/merchant/" data-ajax="false"
           class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-back ui-nodisc-icon ui-alt-icon ui-btn-left"></a>
        <a href="#conditions_panel"
           class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-bullets ui-nodisc-icon ui-alt-icon ui-btn-right"></a>
    </div>

    {{--右侧栏--}}
    <div id="conditions_panel" data-role="panel" data-position-fixed="true" data-position="right" data-display="push">
        <ul data-role="listview" data-inset="false">
            <li data-role="list-divider">最近20期</li>
            @foreach($issues as $issue)
                @if($issue->id == $issue_id)
                    <li data-icon="false" data-theme="b"><h3>{{ $issue->id }}</h3></li>
                @else
                    <li><a href="?issue={{ $issue->id }}" data-ajax="false"><h3>{{ $issue->id }}</h3></a></li>
                @endif
            @endforeach
        </ul>
    </div>

    <div role="main" class="ui-content ui-mini">
        <div style="margin:-1.5em -1em;">
            <ul data-role="listview" data-inset="true">
                <div class="ui-grid-solo">
                    <div class="ui-block-a">
                        <div class="ui-bar ui-bar-b">第{{ $issue_id }}期,总销售:￥{{ number_format($uass->sum('sell_total'),2,'.','') }}</div>
                    </div>
                </div>
                <div class="ui-grid-c">
                    <div class="ui-block-a">
                        <div class="ui-bar ui-bar-a">代理人</div>
                    </div>
                    <div class="ui-block-b">
                        <div class="ui-bar ui-bar-a">销售</div>
                    </div>
                    <div class="ui-block-c">
                        <div class="ui-bar ui-bar-a">返奖</div>
                    </div>
                    <div class="ui-block-c">
                        <div class="ui-bar ui-bar-a">佣金</div>
                    </div>
                </div>
                @foreach($uass as $uas)
                    <div class="ui-grid-c">
                        <div class="ui-block-a">
                            <div class="ui-bar ui-bar-a">{{ $uas->agent->name }}</div>
                        </div>
                        <div class="ui-block-b">
                            <div class="ui-bar ui-bar-a">{{ number_format($uas->sell_total,2,'.','') }}</div>
                        </div>
                        <div class="ui-block-c">
                            <div class="ui-bar ui-bar-a">{{ number_format($uas->bonus_total,2,'.','') }}</div>
                        </div>
                        <div class="ui-block-c">
                            <div class="ui-bar ui-bar-a">{{ number_format($uas->commission,2,'.','') }}</div>
                        </div>
                    </div>
                @endforeach
            </ul>
        </div>
    </div>
</div>
</body>
</html>