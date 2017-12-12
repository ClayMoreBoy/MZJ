<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="//cdn.bootcss.com/jquery-mobile/1.4.5/jquery.mobile.css" rel="stylesheet">
    <script src="//cdn.bootcss.com/jquery/1.10.2/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/jquery-mobile/1.4.5/jquery.mobile.js"></script>
    <script src="{{ url('/js/alert.js') }}"></script>
    <style>
        .ui-bar {
            padding: .4em .4em;
        }

        .ui-btn-inline {
            padding: .3em;
            margin: 0;
            float: right;
        }

        .line {
            height: 2.4em;
            line-height: 2.4em;
        }

        .waiting {
            color: #9BA2AB;
        }

        .rejected {
            color: #FB223D;
        }

        .succeed {
            color: #1fc26b;
        }

        .canceled {
            color: #9BA2AB;
        }

        .ui-btn-process, .ui-btn-process:hover, .ui-btn-process:active {
            background: none;
            border: 0;
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
                                <a href="/agent/bill/withdraws/"
                                   class="ui-btn ui-icon-delete ui-btn-icon-notext ui-corner-all ui-btn-inline"></a>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="ui-grid-c">
                    <div class="ui-block-a">
                        <div class="ui-bar ui-bar-a">用户</div>
                    </div>
                    <div class="ui-block-b">
                        <div class="ui-bar ui-bar-a">金额</div>
                    </div>
                    <div class="ui-block-c">
                        <div class="ui-bar ui-bar-a">时间</div>
                    </div>
                    <div class="ui-block-d">
                        <div class="ui-bar ui-bar-a">状态</div>
                    </div>
                </div>
                @foreach($withdraws as $withdraw)
                    <div class="ui-grid-c">
                        <div class="ui-block-a">
                            <div class="ui-bar ui-bar-a"><span class="line">{{ $withdraw->account->nickname }}</span>
                            </div>
                        </div>
                        <div class="ui-block-b">
                            <div class="ui-bar ui-bar-a"><span class="line">{{ number_format($withdraw->fee,2) }}</span>
                            </div>
                        </div>
                        <div class="ui-block-c">
                            <div class="ui-bar ui-bar-a"><span
                                        class="line">{{ substr($withdraw->updated_at,5,11) }}</span></div>
                        </div>
                        <div class="ui-block-d">
                            <div class="ui-bar ui-bar-a">
                                <span class="line {{ $withdraw->statusCSS() }}">{{ $withdraw->statusCN() }}</span>
                                @if($withdraw->status == \App\Models\UAccountWithdraw::k_status_waiting)
                                    <a href="#popup_process_{{ $withdraw->id }}" data-transition="pop" data-rel="popup"
                                       class="ui-btn ui-alt-icon ui-nodisc-icon ui-btn-inline ui-icon-info ui-btn-icon-notext ui-btn-process"></a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div data-role="popup" id="popup_process_{{ $withdraw->id }}" data-theme="none">
                        <ul data-role="listview">
                            <li data-icon="check">
                                <a data-rel="back" onclick="processWithdraw('{{ $withdraw->id }}','done')">转账完成</a>
                            </li>
                            <li data-icon="delete">
                                <a data-rel="back" onclick="processWithdraw('{{ $withdraw->id }}','reject')">拒绝提现</a>
                            </li>
                        </ul>
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
<script>
    function processWithdraw(id, action) {
        $.mobile.loading("show");
        $.post('/agent/process/withdraw/', {
            _token: '{{ csrf_token() }}',
            id: id,
            action: action
        }, function (data) {
            $.mobile.loading("hide");
            if (data.code == 0) {
                LAlert('操作成功', 'a');
                location.reload();
            } else if (data.code == 403) {
                location.reload();
            } else {
                LAlert(data.message, 'b');
            }
        });
    }
</script>
</html>