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
        <h1 class="title">新建代理人</h1>
        <a href="/merchant/" data-ajax="false"
           class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-back ui-nodisc-icon ui-alt-icon ui-btn-left"></a>
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
                {{--<label for="commission">佣金:</label>--}}
                <div data-role="controlgroup" data-type="horizontal" class="ui-mini">
                    <button disabled="disabled">佣金</button>
                    <input type="text" name="commission" size="20" placeholder=""
                           value="{{ request('commission','') }}" data-wrapper-class="controlgroup-textinput ui-btn">
                </div>
                {{--<label for="currency-controlgroup">Value</label>--}}
                <div data-role="controlgroup" data-type="horizontal" class="ui-mini">
                    <button disabled="disabled">域名</button>
                    <input name="prefix_domain" type="text" size="10" placeholder=""
                           value="{{ request('prefix_domain','') }}"
                           data-wrapper-class="controlgroup-textinput ui-btn">
                    <button disabled="disabled">{{ $domain or '' }}</button>
                </div>
                {{--<label for="wx_account">微信号:</label>--}}
                <div data-role="controlgroup" data-type="horizontal" class="ui-mini">
                    <button disabled="disabled">微信号</button>
                    <input type="text" name="wx_account" size="20" placeholder="" value="{{ request('wx_account','') }}"
                           data-wrapper-class="controlgroup-textinput ui-btn">
                </div>
                <input type="hidden" name="wx_qr" id="wx_qr" value="{{ request('wx_qr','') }}">
                <div class="ui-grid-a">
                    <div class="ui-block-a">
                        <img id="img_container" src="{{ request('wx_qr','') }}" style="width: 80%">
                    </div>
                    <div class="ui-block-b" style="text-align: center;">
                        <p style="text-align: center;">
                            <a class="ui-btn ui-mini ui-corner-all" onclick="$('#wx_qr_file').click()">上传微信二维码</a>
                        </p>
                    </div>
                </div>
                <input type="submit" value="新建" data-theme="b">
            </form>
            <div style="width: 0;height: 0;">
                <input type="file" id="wx_qr_file">
            </div>
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
        if (!(parseInt($.trim(form.commission.value)) > 0 && parseInt($.trim(form.commission.value)) <= 10)) {
            LAlert('佣金只能在1-10之间', 'b');
            return false;
        }
        if ($.trim(form.wx_account.value) === '') {
            LAlert('微信号不能为空', 'b');
            return false;
        }
        if (!/[A-Za-z0-9]+/.test($.trim(form.prefix_domain.value))) {
            LAlert('域名只能用字母或者数字', 'b');
            return false;
        }
        return true;
    }

    $('#wx_qr_file').bind('change', function (e) {
        var file = e.target.files[0];
        if (file && /^image\//i.test(file.type)) {
            var reader = new FileReader();
            reader.onloadend = function () {
                // 图片的 base64 格式, 可以直接当成 img 的 src 属性值
                var img = new Image();
                img.onload = function () {
                    // 当图片宽度超过 400px 时, 就压缩成 400px, 高度按比例计算
                    // 压缩质量可以根据实际情况调整
                    var w = Math.min(400, img.width);
                    var h = img.height * (w / img.width);
                    var canvas = document.createElement('canvas');
                    var ctx = canvas.getContext('2d');

                    // 设置 canvas 的宽度和高度
                    canvas.width = w;
                    canvas.height = h;

                    // 把图片绘制到 canvas 中
                    ctx.drawImage(img, 0, 0, w, h);

                    // 取出 base64 格式数据
                    var dataURL = canvas.toDataURL('image/jpeg');
                    $('#img_container')[0].src = dataURL;
                    $('#wx_qr').val(dataURL);
                    console.info(dataURL.length);
                };
                img.src = reader.result;
                // 插入到 DOM 中预览
//                $('#img_container')[0].src =reader.result;
            };

            // 读出base64格式
            reader.readAsDataURL(file);
        }
    });
</script>
</html>