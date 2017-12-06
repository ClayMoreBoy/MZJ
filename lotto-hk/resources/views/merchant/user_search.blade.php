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
            width: 33.33%;
            font-size: .8em;
            position: relative;
            display: inline-block;
        }

        .odd {
            width: 33.33%;
            font-size: .8em;
            line-height: 1.6em;
            height: 1.6em;
            text-align: center;
            display: inline-block;
        }

        .bonus {
            width: 33.33%;
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

            <label for="nickname">用户昵称:</label>
            <input type="text" data-clear-btn="true" name="nickname" id="nickname" value="{{ $nickname or '' }}">

            <label for="sort_at" class="select">排序项:</label>
            <select name="sort_at" id="sort_at">
                <option value="created_at" {{ isset($sort_at)&&$sort_at=='created_at'?'selected':'' }}>注册时间</option>
                <option value="balance" {{ isset($sort_at)&&$sort_at=='balance'?'selected':'' }}>余额</option>
            </select>

            <label for="sort_rule" class="select">排序规则:</label>
            <select name="sort_rule" id="sort_rule">
                <option value="desc" {{ isset($sort_rule)&&$sort_rule=='desc'?'selected':'' }}>倒序</option>
                <option value="asc" {{ isset($sort_rule)&&$sort_rule=='asc'?'selected':'' }}>正序</option>
            </select>

            <button type="submit" data-theme="b">搜索</button>
        </form>
    </div>

    <div role="main" class="ui-content">
        <div style="margin:-1em -.5em;">
            <div class="conditions">
                @if(isset($nickname))
                    <p><span class="label">用户昵称：</span><span class="condition">{{ $nickname }}</span></p>
                @endif
                @if(isset($c_agent))
                    <p><span class="label">代理人：</span><span class="condition">{{ $c_agent->name }}</span></p>
                @endif
            </div>
            <ul data-role="listview" data-inset="true">
                @foreach($accounts as $account)
                    <li>
                        <h3>{{ $account->nickname }}</h3>
                        <div class="items">
                            <span class="status">
                                手机号码：<strong class="unknown">{{ $account->phone }}</strong>
                            </span>
                            <span class="date">
                                代理人：<strong class="unknown">{{ $account->agent->name }}</strong>
                            </span>
                        </div>
                        <div class="items">
                            <span class="status">
                                余额：<strong class="unknown">{{ $account->balance }}</strong>
                            </span>
                            <span class="date">
                                总消费：<strong class="unknown">{{ $account->orders()->whereIn('status',[0,1])->sum('total_fee') }}</strong>
                            </span>
                        </div>
                        <div class="items">
                            <span class="status">
                                注册时间：<strong class="unknown">{{ substr($account->created_at,2,11) }}</strong>
                            </span>
                            <span class="date">
                                最后访问：<strong class="unknown">{{ substr($account->updated_at,2,11) }}</strong>
                            </span>
                        </div>
                    </li>
                @endforeach
            </ul>
            <div data-role="controlgroup" data-type="horizontal" data-mini="true">
                <a href="{{ $accounts->url(1) }}"
                   class="ui-btn ui-corner-all {{ $accounts->currentPage()==1?'ui-state-disabled':'' }}"> 首页 </a>
                <a href="{{ $accounts->previousPageUrl() }}"
                   class="ui-btn ui-corner-all {{ $accounts->currentPage()==1?'ui-state-disabled':'' }}"> 上一页 </a>
                <a href="#" class="ui-btn ui-corner-all">{{ $accounts->currentPage() }}</a>
                <a href="{{ $accounts->nextPageUrl() }}"
                   class="ui-btn ui-corner-all {{ $accounts->currentPage()==$accounts->lastPage()?'ui-state-disabled':'' }}">
                    下一页 </a>
                <a href="{{ $accounts->url($accounts->lastPage()) }}"
                   class="ui-btn ui-corner-all {{ $accounts->currentPage()==$accounts->lastPage()?'ui-state-disabled':'' }}">
                    尾页 </a>
            </div>
        </div>
    </div>
</div>
</body>
</html>