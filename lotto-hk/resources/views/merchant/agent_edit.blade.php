<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="//cdn.bootcss.com/jquery-mobile/1.4.5/jquery.mobile.css" rel="stylesheet">
    <script src="//cdn.bootcss.com/jquery/1.10.2/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/jquery-mobile/1.4.5/jquery.mobile.js"></script>
    <script src="{{ url('/js/alert.js') }}"></script>
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
        <h1 class="title">{{ isset($agent)?'编辑代理人':'无效的代理人' }}</h1>
        <a href="/merchant/agent/search/" data-ajax="false"
           class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-back ui-nodisc-icon ui-alt-icon ui-btn-left"></a>
    </div>
    <div role="main" class="ui-content">
        <div style="margin:-1em -.5em;">
            @if(isset($agent))
                <div data-role="controlgroup" data-type="horizontal" class="ui-mini">
                    <button disabled="disabled">手机</button>
                    <input type="text" id="phone" size="16" data-btn="phone_btn"
                           value="{{ request('phone',$agent->phone) }}"
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
                    <button disabled="disabled">名称</button>
                    <input type="text" id="name" size="16" data-btn="name_btn"
                           value="{{ request('name',$agent->name) }}"
                           data-wrapper-class="controlgroup-textinput ui-btn">
                    <button id="name_btn" onclick="updateData('name')" disabled="disabled">确定</button>
                </div>
                <div data-role="controlgroup" data-type="horizontal" class="ui-mini">
                    <button disabled="disabled">佣金</button>
                    <input type="text" id="commission" size="16" data-btn="commission_btn"
                           value="{{ request('commission',$agent->commission) }}"
                           data-wrapper-class="controlgroup-textinput ui-btn">
                    <button id="commission_btn" onclick="updateData('commission')" disabled="disabled">确定</button>
                </div>
                <div data-role="controlgroup" data-type="horizontal" class="ui-mini">
                    <button disabled="disabled">域名</button>
                    <input id="prefix_domain" type="text" size="6" data-btn="prefix_domain_btn"
                           value="{{ request('prefix_domain',$agent->domain) }}"
                           data-wrapper-class="controlgroup-textinput ui-btn">
                    <button disabled="disabled">{{ $domain or '' }}</button>
                    <button id="prefix_domain_btn" onclick="updateData('prefix_domain')" disabled="disabled">确定</button>
                </div>
                <div data-role="controlgroup" data-type="horizontal" class="ui-mini">
                    <button disabled="disabled">微信号</button>
                    <input type="text" id="wx_account" size="16" data-btn="phone_btn"
                           value="{{ request('wx_account',$agent->wx_account) }}"
                           data-wrapper-class="controlgroup-textinput ui-btn">
                    <button id="wx_account_btn" onclick="updateData('wx_account')" disabled="disabled">确定</button>
                </div>
                <input type="hidden" id="wx_qr"
                       value="{{ request('wx_qr',$agent->wx_qr) }}">
                <div class="ui-grid-a">
                    <div class="ui-block-a">
                        <img id="img_container" src="{{ request('wx_qr',$agent->wx_qr) }}" style="width: 80%">
                    </div>
                    <div class="ui-block-b" style="text-align: center;">
                        <p style="text-align: center;">
                            <a class="ui-btn ui-mini ui-corner-all" onclick="$('#wx_qr_file').click()">上传微信二维码</a>
                        </p>
                        <p style="text-align: center;">
                            <a id="wx_qr_btn" class="ui-btn ui-mini ui-corner-all ui-state-disabled"
                               onclick="updateData('wx_qr')">确定</a>
                        </p>
                    </div>
                </div>
                <div style="width: 0;height: 0;">
                    <input type="file" id="wx_qr_file">
                </div>
            @endif
        </div>
    </div>
</div>
</body>
<script>
    function updateData(id) {
        $.mobile.loading("show");
        $.post('/merchant/agent/edit/update/', {
            '_token': '{{ csrf_token() }}',
            id: '{{ $agent->id }}',
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
                    $('#wx_qr_btn').removeClass('ui-state-disabled');
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