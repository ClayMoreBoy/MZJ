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
            padding: .11em 0;
        }
    </style>
</head>
<body>
<div data-role="page" id="main">
    <div data-role="header">
        <h1 class="title">{{ isset($user)?'编辑用户':'无效的用户' }}</h1>
        <a href="/agent/user/search/" data-ajax="false"
           class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-back ui-nodisc-icon ui-alt-icon ui-btn-left"></a>
    </div>
    <div role="main" class="ui-content">
        <div style="margin:-1em -.5em;">
            @if(isset($user))
                <div data-role="controlgroup" data-type="horizontal" class="ui-mini">
                    <button disabled="disabled">手机</button>
                    <input type="text" id="phone" size="16" data-btn="phone_btn"
                           value="{{ request('phone',$user->phone) }}"
                           data-wrapper-class="controlgroup-textinput ui-btn">
                    <button id="phone_btn" onclick="updateData('phone')" disabled="disabled">确定</button>
                </div>
                <div data-role="controlgroup" data-type="horizontal" class="ui-mini">
                    <button disabled="disabled">密码</button>
                    <input type="text" id="password" size="16" data-btn="password_btn"
                           value="{{ request('password','') }}"
                           data-wrapper-class="controlgroup-textinput ui-btn">
                    <button id="password_btn" onclick="updateData('password')" disabled="disabled">重置</button>
                </div>
                <div data-role="controlgroup" data-type="horizontal" class="ui-mini">
                    <button disabled="disabled">昵称</button>
                    <input type="text" id="name" size="16" data-btn="name_btn"
                           value="{{ request('name',$user->nickname) }}"
                           data-wrapper-class="controlgroup-textinput ui-btn">
                    <button id="name_btn" onclick="updateData('name')" disabled="disabled">确定</button>
                </div>
                <div data-role="controlgroup" data-type="horizontal" class="ui-mini">
                    <button disabled="disabled">微信号</button>
                    <input type="text" id="wx_account" size="16" data-btn="wx_account_btn"
                           value="{{ request('wx_account',$user->wx_account) }}"
                           data-wrapper-class="controlgroup-textinput ui-btn">
                    <button id="wx_account_btn" onclick="updateData('wx_account')" disabled="disabled">确定</button>
                </div>
            @endif
        </div>
    </div>
</div>
</body>
<script>
    function updateData(id) {
        $.mobile.loading("show");
        $.post('/agent/user/update/', {
            '_token': '{{ csrf_token() }}',
            id: '{{ $user->id }}',
            key: id,
            value: $('#' + id).val()
        }, function (data) {
            $.mobile.loading("hide");
            if (data.code == 0) {
                LAlert('更新成功', 'a');
                if (id == 'wx_qr_btn') {
                    $('#wx_qr_btn').addClass('ui-state-disabled');
                } else {
                    $('#' + $('#' + id).attr('data-btn')).attr('disabled', 'disabled');
                }
            } else if (data.code == 403) {
                location.reload();
            } else {
                LAlert(data.message, 'b');
            }
        });
    }
    $('input').each(function (i, input) {
        $(input).bind('change', function (e) {
            if ($(e.target).attr('data-btn')) {
                $('#' + $(e.target).attr('data-btn')).removeAttr('disabled');
            }
        })
    });
</script>
</html>