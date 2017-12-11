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
        <h1 class="title">用户列表</h1>
        <a href="/agent/" data-ajax="false"
           class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-back ui-nodisc-icon ui-alt-icon ui-btn-left"></a>
        <a href="#conditions_panel"
           class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-bullets ui-nodisc-icon ui-alt-icon ui-btn-right"></a>
    </div>

    {{--右侧栏--}}
    <div id="conditions_panel" data-role="panel" data-position-fixed="true" data-position="right" data-display="push">
        <form>
            <label for="phone">手机号码:</label>
            <input type="text" data-clear-btn="true" name="phone" id="phone" value="{{ $phone or '' }}">
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
                @if(isset($phone))
                    <p><span class="label">手机号码：</span><span class="condition">{{ $phone }}</span></p>
                @endif
                @if(isset($nickname))
                    <p><span class="label">用户昵称：</span><span class="condition">{{ $nickname }}</span></p>
                @endif
                @if(isset($sort_at) && isset($sort_rule))
                    <p>
                        <span class="label">排序：</span><span class="condition">按 {{ $sort_at=='created_at'?'注册时间':'余额' }} {{ $sort_rule=='desc'?'倒序':'正序' }}</span>
                    </p>
                @endif
            </div>
            <ul data-role="listview" data-inset="true" data-split-icon="edit">
                @foreach($accounts as $account)
                    <li>
                        <a href="/agent/user/view/{{ $account->id }}/">
                            <h3>{{ $account->nickname }}，微信号({{ $account->wx_account }})</h3>
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
                                余额：<strong class="unknown">￥{{ number_format($account->balance,2) }}</strong>
                            </span>
                                <span class="date">
                                注册时间：<strong class="unknown">{{ substr($account->created_at,2,8) }}</strong>
                            </span>
                            </div>
                        </a>
                        <a href="/agent/user/edit/{{ $account->id }}/" data-ajax="false"></a>
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