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
        <h1 class="title">今日新增用户</h1>
        <a href="/merchant/" data-ajax="false"
           class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-back ui-nodisc-icon ui-alt-icon ui-btn-left"></a>
    </div>

    <div role="main" class="ui-content">
        <div style="margin:-1em -.5em;">
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
        </div>
    </div>
</div>
</body>
</html>