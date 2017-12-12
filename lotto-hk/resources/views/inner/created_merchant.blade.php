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
        <h1 class="title">新建店主</h1>
        {{--<a href="/merchant/" data-ajax="false"--}}
           {{--class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-back ui-nodisc-icon ui-alt-icon ui-btn-left"></a>--}}
    </div>
    <div role="main" class="ui-content">
        <div style="margin:-1em -.5em;">
            <form method="post" onsubmit="return verifyForm(this);" data-ajax="false">
                {{ csrf_field() }}
                {{--<label for="phone">手机号码</label>--}}
                <div data-role="controlgroup" data-type="horizontal" class="ui-mini">
                    <button disabled="disabled">手机</button>
                    <input type="text" name="phone" size="20" placeholder="" value="{{ request('phone','') }}"
                           data-wrapper-class="controlgroup-textinput ui-btn">
                </div>
                {{--<label for="password">密码:</label>--}}
                <div data-role="controlgroup" data-type="horizontal" class="ui-mini">
                    <button disabled="disabled">密码</button>
                    <input type="text" name="password" size="20" placeholder="" value="{{ request('password','') }}"
                           data-wrapper-class="controlgroup-textinput ui-btn">
                </div>
                {{--<label for="name">代理名称:</label>--}}
                <div data-role="controlgroup" data-type="horizontal" class="ui-mini">
                    <button disabled="disabled">名称</button>
                    <input type="text" name="name" size="20" placeholder="" value="{{ request('name','') }}"
                           data-wrapper-class="controlgroup-textinput ui-btn">
                </div>
                {{--<label for="currency-controlgroup">Value</label>--}}
                <div data-role="controlgroup" data-type="horizontal" class="ui-mini">
                    <button disabled="disabled">域名</button>
                    <input name="prefix_domain" type="text" size="10" placeholder=""
                           value="{{ request('prefix_domain','') }}"
                           data-wrapper-class="controlgroup-textinput ui-btn">
                </div>
                <div data-role="controlgroup" data-type="horizontal" class="ui-mini">
                    <button disabled="disabled">验证码</button>
                    <input name="code" type="text" size="10"
                           data-wrapper-class="controlgroup-textinput ui-btn">
                </div>
                <input type="submit" value="新建" data-theme="b">
            </form>
        </div>
    </div>
</div>
</body>
<script>
    function verifyForm(form) {
        if (!(/^1[34578]\d{9}$/.test($.trim(form.phone.value)))) {
            LAlert('无效的手机号码', 'b');
            return false;
        }
        if ($.trim(form.password.value).length < 6) {
            LAlert('密码不得少于6位', 'b');
            return false;
        }
        if ($.trim(form.name.value).length < 2) {
            LAlert('名称不得少于2', 'b');
            return false;
        }
        if (!/[A-Za-z0-9]+/.test($.trim(form.prefix_domain.value))) {
            LAlert('域名只能用字母或者数字', 'b');
            return false;
        }
        return true;
    }
</script>
</html>