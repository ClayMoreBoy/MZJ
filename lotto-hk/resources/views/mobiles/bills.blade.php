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
            color: #1fc26b;
        }

        .ball {
            float: left;
            margin: .2em;
            line-height: 1.6em;
            height: 1.6em;
            width: 1.6em;
            display: inline-block;
            text-align: center;
            border-radius: 50%;
            background: #c0c0c0;
            color: #fff;
            text-shadow: none;
        }

        .ball.hit {
            background: #FB223D;
            color: #fff;
        }

    </style>
</head>
<body>
<div data-role="page" id="main">
    <div data-role="header">
        <h1 class="title">账单流水</h1>
        <a href="#" data-rel="back"
           class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-back ui-nodisc-icon ui-alt-icon ui-btn-left"></a>
        <a href="/mobiles/home/" data-ajax="false"
           class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-home ui-nodisc-icon ui-alt-icon ui-btn-right"></a>
    </div>
    <div role="main" class="ui-content">
        <div style="margin:-1em -.5em;">
            <ul data-role="listview" data-inset="true">
                @foreach($bills as $bill)
                    <li>
                        <h3>
                            {{ $bill->describe }}
                            <span class="{{ $bill->fee>0?'win':'lose' }}">￥{{ number_format($bill->fee, 2, '.', '') }}</span>
                        </h3>
                        <p class="ui-li-aside">{{ substr($bill->created_at,0,16) }}</p>
                        @if($bill->type == \App\Models\UAccountBill::k_type_bonus)
                            <div class="items">
                                @foreach(explode('|',$bill->order->items) as $item)
                                    <span class="ball {{ str_contains($bill->order->hit_item, $item)?'hit':'' }}">{{ $item }}</span>
                                @endforeach
                            </div>
                        @elseif($bill->type == \App\Models\UAccountBill::k_type_buy)
                            <div class="items">
                                @foreach(explode('|',$bill->order->items) as $item)
                                    <span class="ball">{{ $item }}</span>
                                @endforeach
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
            <div data-role="controlgroup" data-type="horizontal" data-mini="true">
                <a href="{{ $bills->url(1) }}"
                   class="ui-btn ui-corner-all {{ $bills->currentPage()==1?'ui-state-disabled':'' }}"> 首页 </a>
                <a href="{{ $bills->previousPageUrl() }}"
                   class="ui-btn ui-corner-all {{ $bills->currentPage()==1?'ui-state-disabled':'' }}"> 上一页 </a>
                <a href="#" class="ui-btn ui-corner-all">{{ $bills->currentPage() }}</a>
                <a href="{{ $bills->nextPageUrl() }}"
                   class="ui-btn ui-corner-all {{ $bills->currentPage()==$bills->lastPage()?'ui-state-disabled':'' }}">
                    下一页 </a>
                <a href="{{ $bills->url($bills->lastPage()) }}"
                   class="ui-btn ui-corner-all {{ $bills->currentPage()==$bills->lastPage()?'ui-state-disabled':'' }}">
                    尾页 </a>
            </div>
        </div>
    </div>
</div>
</body>
</html>