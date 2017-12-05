<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="//cdn.bootcss.com/jquery-mobile/1.4.5/jquery.mobile.css" rel="stylesheet">
    <script src="//cdn.bootcss.com/jquery/1.10.2/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/jquery-mobile/1.4.5/jquery.mobile.js"></script>
    <script src="{{ url('/js/sha1.js') }}"></script>
    <script src="{{ url('/js/alert.js') }}"></script>
</head>
<body>
<div data-role="page" id="main">
    <div data-role="header">
        <h1 class="title">{{ $account->change_password == 0?'修改初始密码':'修改密码' }}</h1>
        <a href="#" data-rel="back"
           class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-back ui-nodisc-icon ui-alt-icon ui-btn-left"></a>
        <a href="/merchant/" data-ajax="false"
           class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-home ui-nodisc-icon ui-alt-icon ui-btn-right"></a>
    </div>
    <div role="main" class="ui-content">
        <form method="post" action="/merchant/change-password/" onsubmit="return verifyForm(this);" data-ajax='false'>
            <input type="hidden" name="target" value="{{ $target }}">
            {{ csrf_field() }}
            @if($account->change_password == 1)
                <label for="password_old">旧密码:</label>
                <input type="password" name="password_old" id="password_old" value="">
            @endif
            <label for="password">新密码:</label>
            <input type="password" data-clear-btn="true" name="password" id="password" value="">
            <label for="confirm_password">重复密码:</label>
            <input type="password" data-clear-btn="true" name="confirm_password" id="confirm_password" value="">
            <input type="submit" data-theme="b" value="登录">
        </form>
    </div>
</div>
</body>
<script>
    function verifyForm(form) {
        if ($.trim(form.password.value) != $.trim(form.confirm_password.value)) {
            LAlert('两次密码不匹配', 'b');
            return false;
        }
        if (form.password_old) {
            if ($.trim(form.password_old.value) === '') {
                LAlert('旧密码不能为空', 'b');
                return false;
            }
            form.password_old.value = hex_sha1($.trim(form.password_old.value));
        }
        form.password.value = hex_sha1($.trim(form.password.value));
        form.confirm_password.value = hex_sha1($.trim(form.confirm_password.value));
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