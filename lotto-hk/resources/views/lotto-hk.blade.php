<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport"
          content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <!--[if lt IE 9]>
    <script type="text/javascript" src="lib/html5shiv.js"></script>
    <script type="text/javascript" src="lib/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="static/h-ui/css/H-ui.min.css"/>
    <link rel="stylesheet" type="text/css" href="lib/Hui-iconfont/1.0.8/iconfont.min.css"/>
    <!--[if lt IE 9]>
    <link href="static/h-ui/css/H-ui.ie.css" rel="stylesheet" type="text/css"/>
    <![endif]-->
    <!--[if IE 6]>
    <script type="text/javascript" src="lib/DD_belatedPNG_0.0.8a-min.js"></script>
    <script>DD_belatedPNG.fix('*');</script>
    <![endif]-->
    <style type="text/css">
        .ui-sortable .panel-header {
            cursor: move
        }
    </style>
    <title>Hi，H-ui v3.1</title>
    <meta name="keywords" content="关键词,5个左右,单个8汉字以内">
    <meta name="description" content="网站描述，字数尽量空制在80个汉字，160个字符以内！">
</head>
<body ontouchstart>
<div class="containBox">
    <header class="navbar-wrapper">
        <div class="navbar navbar-black navbar-fixed-top">
            <div class="container cl">
                <a class="logo navbar-logo hidden-xs" href="/">六合娱乐</a>
                <a class="logo navbar-logo-m visible-xs" href="/">六合娱乐</a>
                <span class="logo navbar-slogan hidden-xs">简单 &middot; 方便</span>
                <nav class="nav navbar-nav nav-collapse" role="navigation" id="Hui-navbar">
                    <ul class="cl">
                        <li class="current">
                            <a href="#" target="_blank">首页</a>
                        </li>
                        <li>
                            <a href="#" target="_blank">联系我们</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    <div class="wap-container">
        <nav class="breadcrumb">
            <div class="container">
                <i class="Hui-iconfont">&#xe67f;</i>
                <a href="/" class="c-primary">首页</a>
                {{--<span class="c-gray en">&gt;</span>--}}
                {{--<a href="#">组件</a>--}}
                {{--<span class="c-gray en">&gt;</span>--}}
                {{--<span class="c-gray">当前页面</span>--}}
            </div>
        </nav>
        <div class="container">
            <div class="panel panel-default mt-20">
                <div class="panel-header">即时开奖</div>
                <div class="panel-body">
                    <div style="text-align: center;">
                        <span class="btn btn-default size-XL disabled">{{ substr($curr_issue->date,0,10) }}</span>
                        <span class="btn btn-default size-XL disabled">{{ $curr_issue->id }}期</span>
                        <span class="btn {{ is_numeric($curr_issue->num1)?$num_attr[$curr_issue->num1*1]['color']:'disabled' }} size-XL">{{ ($curr_issue->num1).'('.(is_numeric($curr_issue->num1)?$num_attr[$curr_issue->num1*1]['zodiacs']:'即').')' }}</span>
                        <span class="btn {{ is_numeric($curr_issue->num2)?$num_attr[$curr_issue->num2*1]['color']:'disabled' }} size-XL">{{ ($curr_issue->num2).'('.(is_numeric($curr_issue->num2)?$num_attr[$curr_issue->num2*1]['zodiacs']:'时').')' }}</span>
                        <span class="btn {{ is_numeric($curr_issue->num3)?$num_attr[$curr_issue->num3*1]['color']:'disabled' }} size-XL">{{ ($curr_issue->num3).'('.(is_numeric($curr_issue->num3)?$num_attr[$curr_issue->num3*1]['zodiacs']:'开').')' }}</span>
                        <span class="btn {{ is_numeric($curr_issue->num4)?$num_attr[$curr_issue->num4*1]['color']:'disabled' }} size-XL">{{ ($curr_issue->num4).'('.(is_numeric($curr_issue->num4)?$num_attr[$curr_issue->num4*1]['zodiacs']:'奖').')' }}</span>
                        <span class="btn {{ is_numeric($curr_issue->num5)?$num_attr[$curr_issue->num5*1]['color']:'disabled' }} size-XL">{{ ($curr_issue->num5).'('.(is_numeric($curr_issue->num5)?$num_attr[$curr_issue->num5*1]['zodiacs']:'看').')' }}</span>
                        <span class="btn {{ is_numeric($curr_issue->num6)?$num_attr[$curr_issue->num6*1]['color']:'disabled' }} size-XL">{{ ($curr_issue->num6).'('.(is_numeric($curr_issue->num6)?$num_attr[$curr_issue->num6*1]['zodiacs']:'本').')' }}</span>
                        <span class="btn btn-default size-XL disabled">
                            <i class="Hui-iconfont Hui-iconfont-add"></i>
                        </span>
                        <span class="btn {{ is_numeric($curr_issue->num7)?$num_attr[$curr_issue->num7*1]['color']:'disabled' }} size-XL">{{ ($curr_issue->num7).'('.(is_numeric($curr_issue->num7)?$num_attr[$curr_issue->num7*1]['zodiacs']:'站').')' }}</span>
                    </div>
                </div>
            </div>
            <div class="panel panel-default mt-20">
                <div class="panel-header">往期开奖查询</div>
                <div class="panel-body">
                    <table class="table table-border table-bordered table-striped table-responsive mt-20">
                        <thead>
                        <tr>
                            <th class="col1">日期</th>
                            <th class="col1">期数</th>
                            <th class="col2">平码</th>
                            <th class="col3">特码</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($issues as $issue)
                            <tr>
                                <td>
                                    <span class="btn btn-default size-XL disabled">{{ substr($issue->date,0,10) }}</span>
                                </td>
                                <td>
                                    <span class="btn btn-default size-XL disabled">{{ $issue->id }}期</span>
                                </td>
                                <td>
                                    <span class="btn {{ $num_attr[$issue->num1*1]['color'] }} size-XL">{{ ($issue->num1).'('.$num_attr[$issue->num1*1]['zodiacs'].')' }}</span>
                                    <span class="btn {{ $num_attr[$issue->num2*1]['color'] }} size-XL">{{ ($issue->num2).'('.$num_attr[$issue->num2*1]['zodiacs'].')' }}</span>
                                    <span class="btn {{ $num_attr[$issue->num3*1]['color'] }} size-XL">{{ ($issue->num3).'('.$num_attr[$issue->num3*1]['zodiacs'].')' }}</span>
                                    <span class="btn {{ $num_attr[$issue->num4*1]['color'] }} size-XL">{{ ($issue->num4).'('.$num_attr[$issue->num4*1]['zodiacs'].')' }}</span>
                                    <span class="btn {{ $num_attr[$issue->num5*1]['color'] }} size-XL">{{ ($issue->num5).'('.$num_attr[$issue->num5*1]['zodiacs'].')' }}</span>
                                    <span class="btn {{ $num_attr[$issue->num6*1]['color'] }} size-XL">{{ ($issue->num6).'('.$num_attr[$issue->num6*1]['zodiacs'].')' }}</span>
                                </td>
                                <td>
                                    <span class="btn {{ $num_attr[$issue->num7*1]['color'] }} size-XL">{{ ($issue->num7).'('.$num_attr[$issue->num7*1]['zodiacs'].')' }}</span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <footer class="footer mt-20">
            <div class="container">
                <nav class="footer-nav">
                    <a target="_blank" href="#">关于H-ui</a>
                    <span class="pipe">|</span>
                    <a target="_blank" href="#">软件著作权</a>
                    <span class="pipe">|</span>
                    <a target="_blank" href="#">感谢捐赠</a>
                </nav>
                <p>Copyright &copy;2013-2017 H-ui.net All Rights Reserved. <br>
                    {{--<a rel="nofollow" target="_blank" href="http://www.miitbeian.gov.cn/">京ICP备15015336号-1</a>--}}
                    {{--<br>--}}
                    未经允许，禁止转载、抄袭、镜像<br>
                    用心做站，做不一样的站</p>
            </div>
        </footer>
    </div>
</div>
<script type="text/javascript" src="lib/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="lib/jquery-ui/1.9.1/jquery-ui.min.js"></script>
<script type="text/javascript" src="static/h-ui/js/H-ui.js"></script>
<script type="text/javascript" src="lib/jquery.SuperSlide/2.1.1/jquery.SuperSlide.min.js"></script>
<script type="text/javascript" src="lib/jquery.validation/1.14.0/jquery.validate.min.js"></script>
<script type="text/javascript" src="lib/jquery.validation/1.14.0/validate-methods.js"></script>
<script type="text/javascript" src="lib/jquery.validation/1.14.0/messages_zh.min.js"></script>
<script>
</script>
</body>
</html>
<!--H-ui前端框架提供前端技术支持 h-ui.net @2017-01-01 -->