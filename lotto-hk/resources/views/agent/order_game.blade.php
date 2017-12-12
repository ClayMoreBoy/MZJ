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
        <h1 class="title">销售统计</h1>
        <a href="/agent/" data-ajax="false"
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

    <div role="main" class="ui-content">
        <div style="margin:-1em -.5em;">
            <ul data-role="listview" data-inset="true">
                <li data-role="list-divider">
                    <span class="condition">第{{ $issue_id }}期</span>
                </li>
                @foreach($games as $key => $game)
                    <li>
                        <a href="#page_game_{{ $key }}">
                            <h3>{{ $game['game']->name }},总销售:￥{{ number_format($game['total'],2) }}</h3>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@foreach($games as $key => $game)
    <div data-role="page" id="page_game_{{ $key }}">
        <div data-role="header">
            <h1>{{ $game['game']->name }}</h1>
            <a href="#" data-rel="back"
               class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-back ui-nodisc-icon ui-alt-icon ui-btn-left"></a>
        </div>
        <div role="main" class="ui-content ui-mini">
            <div style="margin:-1em -1em;">
                <div class="ui-grid-solo">
                    <div class="ui-block-a">
                        <div class="ui-bar ui-bar-b">第{{ $issue_id }}期,总销售:￥{{ number_format($game['total'],2) }}</div>
                    </div>
                </div>
                <div class="ui-grid-c">
                    <div class="ui-block-a">
                        <div class="ui-bar ui-bar-a">选项</div>
                    </div>
                    <div class="ui-block-b">
                        <div class="ui-bar ui-bar-a">销售</div>
                    </div>
                    <div class="ui-block-c">
                        <div class="ui-bar ui-bar-a">返奖</div>
                    </div>
                    <div class="ui-block-c">
                        <div class="ui-bar ui-bar-a">盈亏</div>
                    </div>
                </div>
                @foreach(collect($game['items'])->sortByDesc('fee') as $values)
                    <div class="ui-grid-c">
                        <div class="ui-block-a">
                            <div class="ui-bar ui-bar-a">{{ $values['item'] }}</div>
                        </div>
                        <div class="ui-block-b">
                            <div class="ui-bar ui-bar-a">{{ number_format($values['fee'],2) }}</div>
                        </div>
                        <div class="ui-block-c">
                            <div class="ui-bar ui-bar-a">{{ number_format($values['bonus'],2) }}</div>
                        </div>
                        <div class="ui-block-c">
                            <div class="ui-bar ui-bar-a {{ ($game['total']-$values['bonus'])>0?'win':'lose' }}">{{ number_format($game['total']-$values['bonus'],2) }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endforeach
</body>
</html>