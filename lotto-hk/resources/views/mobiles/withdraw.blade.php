<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="//cdn.bootcss.com/jquery-mobile/1.4.5/jquery.mobile.css" rel="stylesheet">
    <script src="//cdn.bootcss.com/jquery/1.10.2/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/jquery-mobile/1.4.5/jquery.mobile.js"></script>
    <script src="{{ url('/js/alert.js') }}"></script>
    <style>
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

        .money {
            color: #FB223D;
        }

        .money.canceled, .money.rejected {
            color: #9BA2AB;
            text-decoration: line-through;
        }
    </style>
</head>
<body>
<div data-role="page" id="main">
    <div data-role="header">
        <h1 class="title">提现</h1>
        <a href="#" data-rel="back"
           class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-back ui-nodisc-icon ui-alt-icon ui-btn-left"></a>
        <a href="/mobiles/home/" data-ajax="false"
           class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-home ui-nodisc-icon ui-alt-icon ui-btn-right"></a>
    </div>
    <div role="main" class="ui-content">
        <div style="margin:-1em -.5em;">
            <form method="post" onsubmit="return verifyForm(this);" data-ajax='false'>
                {{ csrf_field() }}
                <div class="ui-field-contain">
                    <label for="fee" class="ui-hidden-accessible">提现金额:</label>
                    <input type="number" name="fee" id="fee" value="{{ $fee or '' }}" data-clear-btn="true"
                           placeholder="输入提现金额">
                </div>
                <p>账户余额:<span class="total money"
                              data-value="{{ number_format($account->balance, 2, '.', '') }}">￥{{ number_format($account->balance, 2, '.', '') }}</span>，<a
                            onclick="allWithdraw('{{ number_format($account->balance, 2, '.', '') }}');">全部提出</a></p>
                <button type="submit" data-theme="b">提现</button>
            </form>

            <ul data-role="listview" data-inset="true">
                <li data-role="list-divider">
                    最近提现记录 <span class="ui-li-count">{{ $withdraws->count() }}</span>
                </li>
                @foreach($withdraws as $withdraw)
                    <li>
                        <h3>
                            提现<span class="money {{ $withdraw->statusCSS() }}">￥{{ number_format($withdraw->fee, 2, '.', '') }}</span>
                        </h3>
                        <div class="items">
                            <span class="status">
                                状态：<strong class="{{ $withdraw->statusCSS() }}">{{ $withdraw->statusCN() }}</strong>
                            </span>
                            <span class="date">
                                时间：<strong class="canceled">{{ substr($withdraw->created_at,0,16) }}</strong>
                            </span>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
</body>
<script>
    function allWithdraw(balance) {
        $('#fee').val(balance);
    }

    function verifyForm(form) {
        if ($.trim(form.fee.value) === '' || parseFloat($.trim(form.fee.value)) <= 0 || parseFloat($.trim(form.fee.value)) > parseFloat($('.total').attr('data-value'))) {
            LAlert('无效的提款金额', 'b');
            return false;
        }
        return true;
    }
</script>
@if(isset($error))
    <script>
        $(function () {
            LAlert('{{ $error }}', 'b');
        });
    </script>
@endif
</html>