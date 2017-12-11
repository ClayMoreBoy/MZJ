<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="//cdn.bootcss.com/jquery-mobile/1.4.5/jquery.mobile.css" rel="stylesheet">
    <script src="//cdn.bootcss.com/jquery/1.10.2/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/jquery-mobile/1.4.5/jquery.mobile.js"></script>
    <script src="{{ url('/js/alert.js') }}"></script>
    <style>
        .controlgroup-textinput {
            padding: .22em 0;
        }

        .ui-controlgroup {
            text-align: center;
        }

        .info {
            text-align: center;
        }

        .balance {
            color: #3eb249;
        }

        .nickname {
            color: #9BA2AB;
        }

        .date {
            color: #9BA2AB;
            float: right;
        }
    </style>
</head>
<body>
<div data-role="page" id="main">
    <div data-role="header">
        <h1 class="title">{{ isset($user)?'充值':'无效的用户' }}</h1>
        @if(isset($user))
            <a href="/agent/user/view/{{ $user->id }}/" data-ajax="false"
               class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-back ui-nodisc-icon ui-alt-icon ui-btn-left"></a>
        @else
            <a href="/agent/user/search/" data-ajax="false"
               class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-back ui-nodisc-icon ui-alt-icon ui-btn-left"></a>
        @endif
    </div>
    <div role="main" class="ui-content">
        <div style="margin:-1em -.5em;">
            @if(isset($user))
                <form method="post" onsubmit="return verifyForm(this);" data-ajax="false">
                    {{ csrf_field() }}
                    <h3 class="info">
                        <span class="nickname">{{ $user->nickname }}</span>，当前余额：
                        <span class="balance">￥{{ number_format($user->balance,2) }}</span>
                    </h3>
                    <div data-role="controlgroup" data-type="horizontal">
                        <button disabled="disabled">充值金额￥</button>
                        <input type="number" name="fee" id="fee" data-balance="{{ $user->balance }}"
                               value="{{ request('fee','') }}"
                               data-wrapper-class="controlgroup-textinput ui-btn">
                    </div>
                    <h5 class="info">
                        充值后余额：
                        <span class="balance deposit">￥{{ number_format($user->balance,2) }}</span>
                    </h5>
                    <input type="submit" value="充值" data-theme="b">
                </form>
                <ul data-role="listview" data-inset="false" style="margin-top: 1em;">
                    <li data-role="list-divider">充值记录</li>
                    @foreach($user->deposits()->orderBy('created_at','desc')->take(20)->get() as $deposit)
                        <li>
                            <h3>
                                <span class="balance">￥{{ number_format($deposit->fee,2) }}</span>
                                <span class="date">{{ substr($deposit->created_at,0,16) }}</span>
                            </h3>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
</body>
<script>
    $(function () {
        $('#fee').bind('input propertychange', function () {
            var balance = parseFloat($(this).attr('data-balance')) + parseFloat($(this).val());
            $('.deposit').html('￥' + balance.toFixed(2) + ' ');
        });
    });
    function verifyForm(form) {
        var fee = parseFloat($.trim(form.fee.value));
        if (fee < 10 || fee > 99999) {
            LAlert('充值金额必须在10-99999之间', 'b');
            return false;
        }
        if (confirm('确定充值：' + fee.toFixed(2) + '元')) {
            return true;
        } else {
            return false;
        }
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