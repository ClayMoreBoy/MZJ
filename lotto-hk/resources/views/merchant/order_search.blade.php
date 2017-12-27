<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="//cdn.bootcss.com/jquery-mobile/1.4.5/jquery.mobile.css" rel="stylesheet">
    <script src="//cdn.bootcss.com/jquery/1.10.2/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/jquery-mobile/1.4.5/jquery.mobile.js"></script>
    <style>
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

        .fee {
            width: 35%;
            font-size: .8em;
            position: relative;
            display: inline-block;
        }

        .odd {
            width: 30%;
            font-size: .8em;
            line-height: 1.6em;
            height: 1.6em;
            text-align: center;
            display: inline-block;
        }

        .bonus {
            width: 35%;
            font-size: .8em;
            line-height: 1.6em;
            height: 1.6em;
            text-align: right;
            display: inline-block;
        }

        .date {
            font-size: .8em;
            position: relative;
            line-height: 1.6em;
            height: 1.6em;
            width: 50%;
            text-align: right;
            display: inline-block;
        }

        .status {
            font-size: .8em;
            position: relative;
            width: 50%;
            line-height: 1.6em;
            height: 1.6em;
            display: inline-block;
        }

        .money {
            color: #FB223D;
        }

        .money.clean, .money.un-hit {
            color: #9BA2AB;
            text-decoration: line-through;
        }

        .unknown {
            color: #9BA2AB;
        }

        .hit {
            color: #FB223D;
        }

        .un-hit {
            color: #1fc26b;
        }

        .clean {
            color: #9BA2AB;
        }

        .conditions > p {
            margin: .5em 0;
        }

        .conditions > p > .label {
            display: inline-block;
            text-align: right;
            width: 30%;
        }

        .conditions > p > .condition {
            display: inline-block;
            text-align: left;
            width: 70%;
            color: #FB223D;
        }
    </style>
</head>
<body>
<div data-role="page" id="main">
    <div data-role="header">
        <h1 class="title">订单列表</h1>
        <a href="/merchant/" data-ajax="false"
           class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-back ui-nodisc-icon ui-alt-icon ui-btn-left"></a>
        <a href="#conditions_panel"
           class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-bullets ui-nodisc-icon ui-alt-icon ui-btn-right"></a>
    </div>

    {{--右侧栏--}}
    <div id="conditions_panel" data-role="panel" data-position-fixed="true" data-position="right" data-display="push">
        <form>
            <label for="agent_id" class="select">代理人:</label>
            <select name="agent_id" id="agent_id">
                <option value="0">请选择</option>
                @foreach($agents as $items)
                    <option value="{{ $items->id }}"
                            @if(isset($c_agent) && $items->id == $c_agent->id) selected="selected" @endif>{{ $items->name }}</option>
                @endforeach
            </select>

            <label for="game_id" class="select">玩法:</label>
            <select name="game_id" id="game_id">
                <option value="0">请选择</option>
                @foreach($games as $items)
                    <option value="{{ $items->id }}"
                            @if(isset($c_game) && $items->id == $c_game->id) selected="selected" @endif>{{ $items->name }}</option>
                @endforeach
            </select>

            <label for="issue" class="select">期数:</label>
            <select name="issue" id="issue">
                <option value="0">请选择</option>
                @foreach($issues as $items)
                    <option value="{{ $items->id }}"
                            @if(isset($c_issue) && $items->id == $c_issue->id) selected="selected" @endif>
                        {{ $items->id }}
                    </option>
                @endforeach
            </select>

            <label for="nickname">用户昵称:</label>
            <input type="text" data-clear-btn="true" name="nickname" id="nickname" value="{{ $nickname or '' }}">

            <button type="submit" data-theme="b">搜索</button>
        </form>
    </div>

    <div role="main" class="ui-content">
        <div style="margin:-1em -.5em;">
            <div class="conditions">
                @if(isset($c_issue))
                    <p><span class="label">期数：</span><span class="condition">第{{ $c_issue->id }}期</span></p>
                @endif
                @if(isset($nickname))
                    <p><span class="label">用户：</span><span class="condition">{{ $nickname }}</span></p>
                @endif
                @if(isset($c_agent))
                    <p><span class="label">代理人：</span><span class="condition">{{ $c_agent->name }}</span></p>
                @endif
                @if(isset($c_game))
                    <p><span class="label">玩法：</span><span class="condition">{{ $c_game->name }}</span></p>
                @endif
            </div>
            <ul data-role="listview" data-inset="true">
                @foreach($orders as $order)
                    <li>
                        <h3>{{ '第'.$order->issue.'期-'.$order->game->name }}</h3>
                        <div class="items">
                            @foreach(explode('|',$order->items) as $item)
                                <span class="ball {{ str_contains($order->hit_item,$item)?'hit':'' }}">{{ $item }}</span>
                            @endforeach
                        </div>
                        <div class="items" style="clear: left">
                            <span class="fee">
                                本金:<strong class="money">{{ '￥'.number_format($order->total_fee,2,'.','') }}</strong>
                            </span>
                            <span class="odd">
                                {{ $order->oddName() }}:<strong class="money">{{ '@'.number_format($order->odd,2,'.','') }}</strong>
                            </span>
                            <span class="bonus">
                                {{ $order->bonusName() }}:<strong
                                        class="money {{ $order->statusCSS() }}">{{ '￥'.number_format($order->bonus,2,'.','') }}</strong>
                            </span>
                        </div>
                        <div class="items">
                            <span class="status">
                                状态:<strong class="{{ $order->statusCSS() }}">{{ $order->statusCN() }}</strong>
                            </span>
                            <span class="date">
                                时间:<strong class="unknown">{{ substr($order->created_at,0,16) }}</strong>
                            </span>
                        </div>
                        <div class="items">
                            <span class="status">
                                代理人:<strong class="unknown">{{ $order->agent->name }}</strong>
                            </span>
                            <span class="date">
                                用户:<strong class="unknown">{{ $order->account->nickname }}</strong>
                            </span>
                        </div>
                    </li>
                @endforeach
            </ul>
            <div data-role="controlgroup" data-type="horizontal" data-mini="true">
                <a href="{{ $orders->url(1) }}"
                   class="ui-btn ui-corner-all {{ $orders->currentPage()==1?'ui-state-disabled':'' }}"> 首页 </a>
                <a href="{{ $orders->previousPageUrl() }}"
                   class="ui-btn ui-corner-all {{ $orders->currentPage()==1?'ui-state-disabled':'' }}"> 上一页 </a>
                <a href="#" class="ui-btn ui-corner-all">{{ $orders->currentPage() }}</a>
                <a href="{{ $orders->nextPageUrl() }}"
                   class="ui-btn ui-corner-all {{ $orders->currentPage()==$orders->lastPage()?'ui-state-disabled':'' }}">
                    下一页 </a>
                <a href="{{ $orders->url($orders->lastPage()) }}"
                   class="ui-btn ui-corner-all {{ $orders->currentPage()==$orders->lastPage()?'ui-state-disabled':'' }}">
                    尾页 </a>
            </div>
        </div>
    </div>
</div>
</body>
</html>