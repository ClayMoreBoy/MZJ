<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="//cdn.bootcss.com/jquery-mobile/1.4.5/jquery.mobile.css" rel="stylesheet">
    <script src="//cdn.bootcss.com/jquery/1.10.2/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/jquery-mobile/1.4.5/jquery.mobile.js"></script>
</head>
<body>
<div data-role="page" id="main">
    <div data-role="header">
        <h1 class="title">修改密码</h1>
        <a href="#" data-rel="back"
           class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-back ui-nodisc-icon ui-alt-icon ui-btn-left"></a>
    </div>
    <div role="main" class="ui-content">
        <form method="post" action="/mobiles/auth/">
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
</html>