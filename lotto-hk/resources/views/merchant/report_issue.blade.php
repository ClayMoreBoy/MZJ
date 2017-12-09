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
        <h1 class="title">总销售报表</h1>
        <a href="/merchant/" data-ajax="false"
           class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-back ui-nodisc-icon ui-alt-icon ui-btn-left"></a>
    </div>

    <div role="main" class="ui-content ui-mini">
        <div style="margin:-1.5em -1em;">
            <ul data-role="listview" data-inset="true">
                <div class="ui-grid-c">
                    <div class="ui-block-a">
                        <div class="ui-bar ui-bar-a">期数</div>
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
                @foreach($issues as $issue)
                    <div class="ui-grid-c">
                        <div class="ui-block-a">
                            <div class="ui-bar ui-bar-a">第{{ $issue->issue_id }}期</div>
                        </div>
                        <div class="ui-block-b">
                            <div class="ui-bar ui-bar-a">{{ number_format($issue->sell_total,2) }}</div>
                        </div>
                        <div class="ui-block-c">
                            <div class="ui-bar ui-bar-a">{{ number_format($issue->bonus_total,2) }}</div>
                        </div>
                        <div class="ui-block-c">
                            <div class="ui-bar ui-bar-a">{{ number_format($issue->commission,2) }}</div>
                        </div>
                    </div>
                @endforeach
            </ul>
            <div data-role="controlgroup" data-type="horizontal" data-mini="true">
                <a href="{{ $issues->url(1) }}"
                   class="ui-btn ui-corner-all {{ $issues->currentPage()==1?'ui-state-disabled':'' }}"> 首页 </a>
                <a href="{{ $issues->previousPageUrl() }}"
                   class="ui-btn ui-corner-all {{ $issues->currentPage()==1?'ui-state-disabled':'' }}"> 上一页 </a>
                <a href="#" class="ui-btn ui-corner-all">{{ $issues->currentPage() }}</a>
                <a href="{{ $issues->nextPageUrl() }}"
                   class="ui-btn ui-corner-all {{ $issues->currentPage()==$issues->lastPage()?'ui-state-disabled':'' }}">
                    下一页 </a>
                <a href="{{ $issues->url($issues->lastPage()) }}"
                   class="ui-btn ui-corner-all {{ $issues->currentPage()==$issues->lastPage()?'ui-state-disabled':'' }}">
                    尾页 </a>
            </div>
        </div>
    </div>
</div>
</body>
</html>