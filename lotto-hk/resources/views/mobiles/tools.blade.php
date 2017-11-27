@extends('mobiles.layout.main')

@section('css')
    <link href="{{ asset('/css/mobiles/index.css') }}" rel="stylesheet">
    <style>
        .tool-num-list {
            margin: 0;
        }

        .num-list li {
            width: 12%;
        }

        .num-list ul {
            border-bottom: 0;
        }
    </style>
@endsection

@section('title')
    工具图表
@endsection

@section('content')
    <div style="margin:-1em -.5em;">
        <ul data-role="listview" data-inset="true" data-divider-theme="a">
            <li data-role="list-divider">特码走势</li>
            <li><a href="/mobiles/tools/te/number/">号码</a></li>
            <li><a href="/mobiles/tools/te/zodiac/">生肖</a></li>
            <li><a href="/mobiles/tools/te/weishu/">尾数</a></li>
            <li><a href="/mobiles/tools/te/color/">波色</a></li>
            <li data-role="list-divider">平特走势</li>
            <li><a href="/mobiles/tools/te/number/">号码</a></li>
            <li><a href="/mobiles/tools/te/zodiac/">生肖</a></li>
            <li><a href="/mobiles/tools/te/weishu/">尾数</a></li>
            <li><a href="/mobiles/tools/te/color/">波色</a></li>
            <li data-role="list-divider">属性参照表</li>
            <li><a href="#page_colors">波色</a></li>
            <li><a href="#page_zodiacs">生肖</a></li>
            <li><a href="#page_wuxing">五行</a></li>
            <li><a href="#page_zodiac_jiaye">家禽野兽</a></li>
            <li><a href="#page_zodiac_nannv">男女生肖</a></li>
            <li><a href="#page_zodiac_tiandi">天地生肖</a></li>
            <li><a href="#page_zodiac_siji">四季生肖</a></li>
            <li><a href="#page_zodiac_qqsh">琴棋书画</a></li>
            <li><a href="#page_zodiac_sanse">三色生肖</a></li>
        </ul>
    </div>
@endsection

@section('other_page')
    <div data-role="page" id="page_colors">
        <div data-role="header">
            <h1>波色参照表</h1>
            <a href="#" data-rel="back"
               class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-back ui-nodisc-icon ui-alt-icon ui-btn-left"></a>
        </div>
        <div role="main" class="ui-content">
            <div style="margin:-.5em -.5em;">
                <div data-role="tabs" id="tabs">
                    <div data-role="navbar">
                        <ul>
                            <li><a href="#red_color" class="ui-btn-active">红波</a></li>
                            <li><a href="#green_color">绿波</a></li>
                            <li><a href="#blue_color">蓝波</a></li>
                        </ul>
                    </div>
                    <div id="red_color">
                        <div class="num-list tool-num-list">
                            <ul>
                                @foreach($red_ball as $item)
                                    <li>
                                        <span class="ball red selective">{{ $num_attr[$item]['num'] }}</span>
                                        <span class="zodiacs">{{ $num_attr[$item]['zodiacs'] }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div id="green_color">
                        <div class="num-list tool-num-list">
                            <ul>
                                @foreach($green_ball as $item)
                                    <li>
                                        <span class="ball green selective">{{ $num_attr[$item]['num'] }}</span>
                                        <span class="zodiacs">{{ $num_attr[$item]['zodiacs'] }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div id="blue_color">
                        <div class="num-list tool-num-list">
                            <ul>
                                @foreach($blue_ball as $item)
                                    <li>
                                        <span class="ball blue selective">{{ $num_attr[$item]['num'] }}</span>
                                        <span class="zodiacs">{{ $num_attr[$item]['zodiacs'] }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div data-role="page" id="page_zodiacs">
        <div data-role="header">
            <h1>生肖参照表</h1>
            <a href="#" data-rel="back"
               class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-back ui-nodisc-icon ui-alt-icon ui-btn-left"></a>
        </div>
        <div role="main" class="ui-content">
            <div style="margin:-.5em -.5em;">
                <div data-role="tabs" id="tabs">
                    <div data-role="navbar">
                        <ul>
                            <li><a href="#zodiac_shu" class="ui-btn-active">鼠</a></li>
                            <li><a href="#zodiac_niu">牛</a></li>
                            <li><a href="#zodiac_hu">虎</a></li>
                            <li><a href="#zodiac_tu">兔</a></li>
                            <li><a href="#zodiac_long">龙</a></li>
                            <li><a href="#zodiac_she">蛇</a></li>
                            <li><a href="#zodiac_ma">马</a></li>
                            <li><a href="#zodiac_yang">羊</a></li>
                            <li><a href="#zodiac_hou">猴</a></li>
                            <li><a href="#zodiac_ji">鸡</a></li>
                            <li><a href="#zodiac_gou">狗</a></li>
                            <li><a href="#zodiac_zhu">猪</a></li>
                        </ul>
                    </div>
                    <div id="zodiac_shu">
                        <div class="num-list tool-num-list">
                            <ul>
                                @foreach($zodiacs_ball['鼠'] as $item)
                                    <li>
                                        <span class="ball {{ $num_attr[$item]['color'] }} selective">{{ $num_attr[$item]['num'] }}</span>
                                        <span class="zodiacs">{{ $num_attr[$item]['zodiacs'] }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div id="zodiac_niu">
                        <div class="num-list tool-num-list">
                            <ul>
                                @foreach($zodiacs_ball['牛'] as $item)
                                    <li>
                                        <span class="ball {{ $num_attr[$item]['color'] }} selective">{{ $num_attr[$item]['num'] }}</span>
                                        <span class="zodiacs">{{ $num_attr[$item]['zodiacs'] }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div id="zodiac_hu">
                        <div class="num-list tool-num-list">
                            <ul>
                                @foreach($zodiacs_ball['虎'] as $item)
                                    <li>
                                        <span class="ball {{ $num_attr[$item]['color'] }} selective">{{ $num_attr[$item]['num'] }}</span>
                                        <span class="zodiacs">{{ $num_attr[$item]['zodiacs'] }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div id="zodiac_tu">
                        <div class="num-list tool-num-list">
                            <ul>
                                @foreach($zodiacs_ball['兔'] as $item)
                                    <li>
                                        <span class="ball {{ $num_attr[$item]['color'] }} selective">{{ $num_attr[$item]['num'] }}</span>
                                        <span class="zodiacs">{{ $num_attr[$item]['zodiacs'] }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div id="zodiac_long">
                        <div class="num-list tool-num-list">
                            <ul>
                                @foreach($zodiacs_ball['龙'] as $item)
                                    <li>
                                        <span class="ball {{ $num_attr[$item]['color'] }} selective">{{ $num_attr[$item]['num'] }}</span>
                                        <span class="zodiacs">{{ $num_attr[$item]['zodiacs'] }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div id="zodiac_she">
                        <div class="num-list tool-num-list">
                            <ul>
                                @foreach($zodiacs_ball['蛇'] as $item)
                                    <li>
                                        <span class="ball {{ $num_attr[$item]['color'] }} selective">{{ $num_attr[$item]['num'] }}</span>
                                        <span class="zodiacs">{{ $num_attr[$item]['zodiacs'] }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div id="zodiac_ma">
                        <div class="num-list tool-num-list">
                            <ul>
                                @foreach($zodiacs_ball['马'] as $item)
                                    <li>
                                        <span class="ball {{ $num_attr[$item]['color'] }} selective">{{ $num_attr[$item]['num'] }}</span>
                                        <span class="zodiacs">{{ $num_attr[$item]['zodiacs'] }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div id="zodiac_yang">
                        <div class="num-list tool-num-list">
                            <ul>
                                @foreach($zodiacs_ball['羊'] as $item)
                                    <li>
                                        <span class="ball {{ $num_attr[$item]['color'] }} selective">{{ $num_attr[$item]['num'] }}</span>
                                        <span class="zodiacs">{{ $num_attr[$item]['zodiacs'] }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div id="zodiac_hou">
                        <div class="num-list tool-num-list">
                            <ul>
                                @foreach($zodiacs_ball['猴'] as $item)
                                    <li>
                                        <span class="ball {{ $num_attr[$item]['color'] }} selective">{{ $num_attr[$item]['num'] }}</span>
                                        <span class="zodiacs">{{ $num_attr[$item]['zodiacs'] }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div id="zodiac_ji">
                        <div class="num-list tool-num-list">
                            <ul>
                                @foreach($zodiacs_ball['鸡'] as $item)
                                    <li>
                                        <span class="ball {{ $num_attr[$item]['color'] }} selective">{{ $num_attr[$item]['num'] }}</span>
                                        <span class="zodiacs">{{ $num_attr[$item]['zodiacs'] }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div id="zodiac_gou">
                        <div class="num-list tool-num-list">
                            <ul>
                                @foreach($zodiacs_ball['狗'] as $item)
                                    <li>
                                        <span class="ball {{ $num_attr[$item]['color'] }} selective">{{ $num_attr[$item]['num'] }}</span>
                                        <span class="zodiacs">{{ $num_attr[$item]['zodiacs'] }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div id="zodiac_zhu">
                        <div class="num-list tool-num-list">
                            <ul>
                                @foreach($zodiacs_ball['猪'] as $item)
                                    <li>
                                        <span class="ball {{ $num_attr[$item]['color'] }} selective">{{ $num_attr[$item]['num'] }}</span>
                                        <span class="zodiacs">{{ $num_attr[$item]['zodiacs'] }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div data-role="page" id="page_wuxing">
        <div data-role="header">
            <h1>五行参照表</h1>
            <a href="#" data-rel="back"
               class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-back ui-nodisc-icon ui-alt-icon ui-btn-left"></a>
        </div>
        <div role="main" class="ui-content">
            <div style="margin:-.5em -.5em;">
                <div data-role="tabs" id="tabs">
                    <div data-role="navbar">
                        <ul>
                            <li><a href="#wuxing_jin" class="ui-btn-active">金</a></li>
                            <li><a href="#wuxing_mu">木</a></li>
                            <li><a href="#wuxing_shui">水</a></li>
                            <li><a href="#wuxing_huo">火</a></li>
                            <li><a href="#wuxing_tu">土</a></li>
                        </ul>
                    </div>
                    <div id="wuxing_jin">
                        <div class="num-list tool-num-list">
                            <ul>
                                @foreach($jin_ball as $item)
                                    <li>
                                        <span class="ball {{ $num_attr[$item]['color'] }} selective">{{ $num_attr[$item]['num'] }}</span>
                                        <span class="zodiacs">{{ $num_attr[$item]['zodiacs'] }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div id="wuxing_mu">
                        <div class="num-list tool-num-list">
                            <ul>
                                @foreach($mu_ball as $item)
                                    <li>
                                        <span class="ball {{ $num_attr[$item]['color'] }} selective">{{ $num_attr[$item]['num'] }}</span>
                                        <span class="zodiacs">{{ $num_attr[$item]['zodiacs'] }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div id="wuxing_shui">
                        <div class="num-list tool-num-list">
                            <ul>
                                @foreach($shui_ball as $item)
                                    <li>
                                        <span class="ball {{ $num_attr[$item]['color'] }} selective">{{ $num_attr[$item]['num'] }}</span>
                                        <span class="zodiacs">{{ $num_attr[$item]['zodiacs'] }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div id="wuxing_huo">
                        <div class="num-list tool-num-list">
                            <ul>
                                @foreach($huo_ball as $item)
                                    <li>
                                        <span class="ball {{ $num_attr[$item]['color'] }} selective">{{ $num_attr[$item]['num'] }}</span>
                                        <span class="zodiacs">{{ $num_attr[$item]['zodiacs'] }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div id="wuxing_tu">
                        <div class="num-list tool-num-list">
                            <ul>
                                @foreach($tu_ball as $item)
                                    <li>
                                        <span class="ball {{ $num_attr[$item]['color'] }} selective">{{ $num_attr[$item]['num'] }}</span>
                                        <span class="zodiacs">{{ $num_attr[$item]['zodiacs'] }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div data-role="page" id="page_zodiac_jiaye">
        <div data-role="header">
            <h1>家禽野兽参照</h1>
            <a href="#" data-rel="back"
               class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-back ui-nodisc-icon ui-alt-icon ui-btn-left"></a>
        </div>
        <div role="main" class="ui-content">
            <div style="margin:-.5em -.5em;">
                <div data-role="tabs" id="tabs">
                    <div data-role="navbar">
                        <ul>
                            <li><a href="#page_zodiac_jiaye_jia" class="ui-btn-active">家禽</a></li>
                            <li><a href="#page_zodiac_jiaye_ye">野兽</a></li>
                        </ul>
                    </div>
                    <div id="page_zodiac_jiaye_jia">
                        <div class="ui-grid-b" style="margin-top: 1em;">
                            <div class="ui-block-a">
                                <div class="ui-bar ui-bar-a">牛</div>
                            </div>
                            <div class="ui-block-b">
                                <div class="ui-bar ui-bar-a">马</div>
                            </div>
                            <div class="ui-block-c">
                                <div class="ui-bar ui-bar-a">羊</div>
                            </div>
                            <div class="ui-block-a">
                                <div class="ui-bar ui-bar-a">鸡</div>
                            </div>
                            <div class="ui-block-b">
                                <div class="ui-bar ui-bar-a">狗</div>
                            </div>
                            <div class="ui-block-c">
                                <div class="ui-bar ui-bar-a">猪</div>
                            </div>
                        </div>
                    </div>
                    <div id="page_zodiac_jiaye_ye">
                        <div class="ui-grid-b" style="margin-top: 1em;">
                            <div class="ui-block-a">
                                <div class="ui-bar ui-bar-a">鼠</div>
                            </div>
                            <div class="ui-block-b">
                                <div class="ui-bar ui-bar-a">虎</div>
                            </div>
                            <div class="ui-block-c">
                                <div class="ui-bar ui-bar-a">兔</div>
                            </div>
                            <div class="ui-block-a">
                                <div class="ui-bar ui-bar-a">龙</div>
                            </div>
                            <div class="ui-block-b">
                                <div class="ui-bar ui-bar-a">蛇</div>
                            </div>
                            <div class="ui-block-c">
                                <div class="ui-bar ui-bar-a">猴</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div data-role="page" id="page_zodiac_nannv">
        <div data-role="header">
            <h1>男女生肖参照</h1>
            <a href="#" data-rel="back"
               class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-back ui-nodisc-icon ui-alt-icon ui-btn-left"></a>
        </div>
        <div role="main" class="ui-content">
            <div style="margin:-.5em -.5em;">
                <div data-role="tabs" id="tabs">
                    <div data-role="navbar">
                        <ul>
                            <li><a href="#page_zodiac_nannv_nan" class="ui-btn-active">男肖</a></li>
                            <li><a href="#page_zodiac_nannv_nv">女肖</a></li>
                        </ul>
                    </div>
                    <div id="page_zodiac_nannv_nan">
                        <div class="ui-grid-b" style="margin-top: 1em;">
                            <div class="ui-block-a">
                                <div class="ui-bar ui-bar-a">蛇</div>
                            </div>
                            <div class="ui-block-b">
                                <div class="ui-bar ui-bar-a">牛</div>
                            </div>
                            <div class="ui-block-c">
                                <div class="ui-bar ui-bar-a">虎</div>
                            </div>
                            <div class="ui-block-a">
                                <div class="ui-bar ui-bar-a">龙</div>
                            </div>
                            <div class="ui-block-b">
                                <div class="ui-bar ui-bar-a">马</div>
                            </div>
                            <div class="ui-block-c">
                                <div class="ui-bar ui-bar-a">猴</div>
                            </div>
                            <div class="ui-block-a">
                                <div class="ui-bar ui-bar-a">狗</div>
                            </div>
                        </div>
                    </div>
                    <div id="page_zodiac_nannv_nv">
                        <div class="ui-grid-b" style="margin-top: 1em;">
                            <div class="ui-block-a">
                                <div class="ui-bar ui-bar-a">兔</div>
                            </div>
                            <div class="ui-block-b">
                                <div class="ui-bar ui-bar-a">蛇</div>
                            </div>
                            <div class="ui-block-c">
                                <div class="ui-bar ui-bar-a">羊</div>
                            </div>
                            <div class="ui-block-a">
                                <div class="ui-bar ui-bar-a">鸡</div>
                            </div>
                            <div class="ui-block-b">
                                <div class="ui-bar ui-bar-a">猪</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div data-role="page" id="page_zodiac_tiandi">
        <div data-role="header">
            <h1>天地生肖参照</h1>
            <a href="#" data-rel="back"
               class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-back ui-nodisc-icon ui-alt-icon ui-btn-left"></a>
        </div>
        <div role="main" class="ui-content">
            <div style="margin:-.5em -.5em;">
                <div data-role="tabs" id="tabs">
                    <div data-role="navbar">
                        <ul>
                            <li><a href="#page_zodiac_tiandi_tian" class="ui-btn-active">天肖</a></li>
                            <li><a href="#page_zodiac_tiandi_di">地肖</a></li>
                        </ul>
                    </div>
                    <div id="page_zodiac_tiandi_tian">
                        <div class="ui-grid-b" style="margin-top: 1em;">
                            <div class="ui-block-a">
                                <div class="ui-bar ui-bar-a">牛</div>
                            </div>
                            <div class="ui-block-b">
                                <div class="ui-bar ui-bar-a">兔</div>
                            </div>
                            <div class="ui-block-c">
                                <div class="ui-bar ui-bar-a">龙</div>
                            </div>
                            <div class="ui-block-a">
                                <div class="ui-bar ui-bar-a">马</div>
                            </div>
                            <div class="ui-block-b">
                                <div class="ui-bar ui-bar-a">猴</div>
                            </div>
                            <div class="ui-block-c">
                                <div class="ui-bar ui-bar-a">猪</div>
                            </div>
                        </div>
                    </div>
                    <div id="page_zodiac_tiandi_di">
                        <div class="ui-grid-b" style="margin-top: 1em;">
                            <div class="ui-block-a">
                                <div class="ui-bar ui-bar-a">鼠</div>
                            </div>
                            <div class="ui-block-b">
                                <div class="ui-bar ui-bar-a">虎</div>
                            </div>
                            <div class="ui-block-c">
                                <div class="ui-bar ui-bar-a">羊</div>
                            </div>
                            <div class="ui-block-a">
                                <div class="ui-bar ui-bar-a">蛇</div>
                            </div>
                            <div class="ui-block-b">
                                <div class="ui-bar ui-bar-a">鸡</div>
                            </div>
                            <div class="ui-block-c">
                                <div class="ui-bar ui-bar-a">狗</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div data-role="page" id="page_zodiac_siji">
        <div data-role="header">
            <h1>天地生肖参照</h1>
            <a href="#" data-rel="back"
               class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-back ui-nodisc-icon ui-alt-icon ui-btn-left"></a>
        </div>
        <div role="main" class="ui-content">
            <div style="margin:-.5em -.5em;">
                <div data-role="tabs" id="tabs">
                    <div data-role="navbar">
                        <ul>
                            <li><a href="#page_zodiac_siji_chun" class="ui-btn-active">春肖</a></li>
                            <li><a href="#page_zodiac_siji_xia">夏肖</a></li>
                            <li><a href="#page_zodiac_siji_qiu">秋肖</a></li>
                            <li><a href="#page_zodiac_siji_dong">冬肖</a></li>
                        </ul>
                    </div>
                    <div id="page_zodiac_siji_chun">
                        <div class="ui-grid-b" style="margin-top: 1em;">
                            <div class="ui-block-a">
                                <div class="ui-bar ui-bar-a">虎</div>
                            </div>
                            <div class="ui-block-b">
                                <div class="ui-bar ui-bar-a">兔</div>
                            </div>
                            <div class="ui-block-c">
                                <div class="ui-bar ui-bar-a">龙</div>
                            </div>
                        </div>
                    </div>
                    <div id="page_zodiac_siji_xia">
                        <div class="ui-grid-b" style="margin-top: 1em;">
                            <div class="ui-block-a">
                                <div class="ui-bar ui-bar-a">蛇</div>
                            </div>
                            <div class="ui-block-b">
                                <div class="ui-bar ui-bar-a">马</div>
                            </div>
                            <div class="ui-block-c">
                                <div class="ui-bar ui-bar-a">羊</div>
                            </div>
                        </div>
                    </div>
                    <div id="page_zodiac_siji_qiu">
                        <div class="ui-grid-b" style="margin-top: 1em;">
                            <div class="ui-block-a">
                                <div class="ui-bar ui-bar-a">猴</div>
                            </div>
                            <div class="ui-block-b">
                                <div class="ui-bar ui-bar-a">鸡</div>
                            </div>
                            <div class="ui-block-c">
                                <div class="ui-bar ui-bar-a">狗</div>
                            </div>
                        </div>
                    </div>
                    <div id="page_zodiac_siji_dong">
                        <div class="ui-grid-b" style="margin-top: 1em;">
                            <div class="ui-block-a">
                                <div class="ui-bar ui-bar-a">鼠</div>
                            </div>
                            <div class="ui-block-b">
                                <div class="ui-bar ui-bar-a">牛</div>
                            </div>
                            <div class="ui-block-c">
                                <div class="ui-bar ui-bar-a">猪</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div data-role="page" id="page_zodiac_qqsh">
        <div data-role="header">
            <h1>天地生肖参照</h1>
            <a href="#" data-rel="back"
               class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-back ui-nodisc-icon ui-alt-icon ui-btn-left"></a>
        </div>
        <div role="main" class="ui-content">
            <div style="margin:-.5em -.5em;">
                <div data-role="tabs" id="tabs">
                    <div data-role="navbar">
                        <ul>
                            <li><a href="#page_zodiac_qqsh_qin" class="ui-btn-active">琴肖</a></li>
                            <li><a href="#page_zodiac_qqsh_qi">棋肖</a></li>
                            <li><a href="#page_zodiac_qqsh_shu">书肖</a></li>
                            <li><a href="#page_zodiac_qqsh_hua">画肖</a></li>
                        </ul>
                    </div>
                    <div id="page_zodiac_qqsh_qin">
                        <div class="ui-grid-b" style="margin-top: 1em;">
                            <div class="ui-block-a">
                                <div class="ui-bar ui-bar-a">兔</div>
                            </div>
                            <div class="ui-block-b">
                                <div class="ui-bar ui-bar-a">蛇</div>
                            </div>
                            <div class="ui-block-c">
                                <div class="ui-bar ui-bar-a">鸡</div>
                            </div>
                        </div>
                    </div>
                    <div id="page_zodiac_qqsh_qi">
                        <div class="ui-grid-b" style="margin-top: 1em;">
                            <div class="ui-block-a">
                                <div class="ui-bar ui-bar-a">鼠</div>
                            </div>
                            <div class="ui-block-b">
                                <div class="ui-bar ui-bar-a">牛</div>
                            </div>
                            <div class="ui-block-c">
                                <div class="ui-bar ui-bar-a">狗</div>
                            </div>
                        </div>
                    </div>
                    <div id="page_zodiac_qqsh_shu">
                        <div class="ui-grid-b" style="margin-top: 1em;">
                            <div class="ui-block-a">
                                <div class="ui-bar ui-bar-a">虎</div>
                            </div>
                            <div class="ui-block-b">
                                <div class="ui-bar ui-bar-a">龙</div>
                            </div>
                            <div class="ui-block-c">
                                <div class="ui-bar ui-bar-a">马</div>
                            </div>
                        </div>
                    </div>
                    <div id="page_zodiac_qqsh_hua">
                        <div class="ui-grid-b" style="margin-top: 1em;">
                            <div class="ui-block-a">
                                <div class="ui-bar ui-bar-a">羊</div>
                            </div>
                            <div class="ui-block-b">
                                <div class="ui-bar ui-bar-a">猴</div>
                            </div>
                            <div class="ui-block-c">
                                <div class="ui-bar ui-bar-a">猪</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div data-role="page" id="page_zodiac_sanse">
        <div data-role="header">
            <h1>三色生肖参照</h1>
            <a href="#" data-rel="back"
               class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-back ui-nodisc-icon ui-alt-icon ui-btn-left"></a>
        </div>
        <div role="main" class="ui-content">
            <div style="margin:-.5em -.5em;">
                <div data-role="tabs" id="tabs">
                    <div data-role="navbar">
                        <ul>
                            <li><a href="#page_zodiac_sanse_red" class="ui-btn-active">红肖</a></li>
                            <li><a href="#page_zodiac_sanse_blue">蓝肖</a></li>
                            <li><a href="#page_zodiac_sanse_green">绿肖</a></li>
                        </ul>
                    </div>
                    <div id="page_zodiac_sanse_red">
                        <div class="ui-grid-c" style="margin-top: 1em;">
                            <div class="ui-block-a">
                                <div class="ui-bar ui-bar-a">鼠</div>
                            </div>
                            <div class="ui-block-b">
                                <div class="ui-bar ui-bar-a">兔</div>
                            </div>
                            <div class="ui-block-c">
                                <div class="ui-bar ui-bar-a">马</div>
                            </div>
                            <div class="ui-block-d">
                                <div class="ui-bar ui-bar-a">鸡</div>
                            </div>
                        </div>
                    </div>
                    <div id="page_zodiac_sanse_blue">
                        <div class="ui-grid-c" style="margin-top: 1em;">
                            <div class="ui-block-a">
                                <div class="ui-bar ui-bar-a">虎</div>
                            </div>
                            <div class="ui-block-b">
                                <div class="ui-bar ui-bar-a">蛇</div>
                            </div>
                            <div class="ui-block-c">
                                <div class="ui-bar ui-bar-a">猴</div>
                            </div>
                            <div class="ui-block-d">
                                <div class="ui-bar ui-bar-a">鸡</div>
                            </div>
                        </div>
                    </div>
                    <div id="page_zodiac_sanse_green">
                        <div class="ui-grid-c" style="margin-top: 1em;">
                            <div class="ui-block-a">
                                <div class="ui-bar ui-bar-a">牛</div>
                            </div>
                            <div class="ui-block-b">
                                <div class="ui-bar ui-bar-a">龙</div>
                            </div>
                            <div class="ui-block-c">
                                <div class="ui-bar ui-bar-a">羊</div>
                            </div>
                            <div class="ui-block-d">
                                <div class="ui-bar ui-bar-a">狗</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection