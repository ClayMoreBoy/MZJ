@extends('mobiles.layout.main')

@section('css')
    <link href="{{ asset('/css/mobiles/index.css') }}" rel="stylesheet">
    <style>

    </style>
@endsection

@section('title')
    第{{ $issue->id }}期
@endsection

@section('nav')
@endsection

@section('content')
    <div class="tz-num-list zodiac">
        <div class="select">
            <fieldset data-role="controlgroup">
                <select name="select-zodiacs" id="select-zodiacs" data-native-menu="false" multiple="multiple">
                    <option>选择生肖</option>
                    <optgroup label="家禽">
                        <option value="牛">牛</option>
                        <option value="马">马</option>
                        <option value="羊">羊</option>
                        <option value="鸡">鸡</option>
                        <option value="狗">狗</option>
                        <option value="猪">猪</option>
                    </optgroup>
                    <optgroup label="野兽">
                        <option value="鼠">鼠</option>
                        <option value="虎">虎</option>
                        <option value="兔">兔</option>
                        <option value="龙">龙</option>
                        <option value="蛇">蛇</option>
                        <option value="猴">猴</option>
                    </optgroup>
                </select>
                <select name="select-colors" id="select-colors" data-native-menu="false" multiple="multiple">
                    <option>选择波色</option>
                    <option value="red">红</option>
                    <option value="blue">蓝</option>
                    <option value="green">绿</option>
                </select>
                <select name="select-uo" id="select-uo" data-native-menu="false" multiple="multiple">
                    <option>选择大小</option>
                    <option value="u">大</option>
                    <option value="o">小</option>
                </select>
                <select name="select-ds" id="select-ds" data-native-menu="false" multiple="multiple">
                    <option>选择单双</option>
                    <option value="d">单</option>
                    <option value="s">双</option>
                </select>
                <select name="select-ws" id="select-ws" data-native-menu="false" multiple="multiple">
                    <option>选择尾数</option>
                    <option value="0">0尾</option>
                    <option value="1">1尾</option>
                    <option value="2">2尾</option>
                    <option value="3">3尾</option>
                    <option value="4">4尾</option>
                    <option value="5">5尾</option>
                    <option value="6">6尾</option>
                    <option value="7">7尾</option>
                    <option value="8">8尾</option>
                    <option value="9">9尾</option>
                </select>
            </fieldset>
        </div>
        @foreach($zodiacs_ball as $zodiac=>$nums)
            <ul class="{{ $loop->first?'first':'' }}">
                <li class="issue">{{ $zodiac }}</li>
                @foreach($nums as $num)
                    <li class="tz">
                        <span data-value="{{ $num }}"
                              data-zodiac="{{ $zodiac }}"
                              data-uo="{{ $num>24?'u':'o' }}"
                              data-ds="{{ $num%2==0?'s':'d' }}"
                              data-ws="{{ $num%10 }}"
                              data-color="{{ $num_attr[$num*1]['color'] }}"
                              class="ball {{ $num_attr[$num*1]['color'] }}">
                            {{ $num<10?('0'.$num):$num }}
                        </span>
                        <span class="odd">@45.00</span>
                    </li>
                @endforeach
            </ul>
        @endforeach
    </div>

    <div data-role="popup" id="purchase" data-overlay-theme="b" data-theme="b" data-dismissible="false" style="max-width:400px;">
        <div data-role="header" data-theme="a"><h1>确认投注</h1></div>
        <div role="main" class="ui-content">
            <p>您确定要投注<i class="odd purchase">￥0.00 </i>吗？</p>
            <a href="#" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b ui-icon-check ui-btn-icon-left" data-rel="back">确定</a>
            <a href="#" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b ui-icon-delete ui-btn-icon-left" data-rel="back" data-transition="flow">取消</a>
        </div>
    </div>
@endsection

@section('footer')
    <div data-role="footer" data-position="fixed">
        <h1>开奖时间：{{ substr($issue->date,5,11) }}</h1>
        <div class="tz-num-list cart" style="display: none;">
            <input type="number" id="capital" placeholder="本金" data-clear-btn="true">
            <p style="margin-top: .5em;margin-bottom: 0.5em">
                综合赔率<i class="odd">@0.00</i>；返还<i class="odd price">￥0.00</i>。
            </p>
            <a href="#purchase" data-rel="popup" data-position-to="window" data-transition="pop"
               class="ui-btn ui-corner-all ui-btn-b ui-state-disabled" style="display: block;">￥100 投注</a>
            <div class="basket">
                <ul>
                    {{--<li>--}}
                    {{--<span class="ball green selective">02</span>--}}
                    {{--</li>--}}
                </ul>
            </div>
            <p style="text-align: center;color: #bd362f;margin-top: 0.5em;margin-bottom: 0.5em;">请在开奖前10分钟投注</p>
        </div>
    </div>
@endsection

@section('js')
    <script>
        var zodiacs = [];
        $('#select-zodiacs').bind('change', function (e) {
            zodiacs.splice(0, zodiacs.length);
            $(this).find('option:selected').each(function (index, o) {
                zodiacs.push(o.value);
            });
            console.info('zodiacs:' + zodiacs);
            review();
        });

        var colors = [];
        $('#select-colors').bind('change', function (e) {
            colors.splice(0, colors.length);
            $(this).find('option:selected').each(function (index, o) {
                colors.push(o.value);
            });
            console.info('colors:' + colors);
            review();
        });

        var dss = [];
        $('#select-ds').bind('change', function (e) {
            dss.splice(0, dss.length);
            $(this).find('option:selected').each(function (index, o) {
                dss.push(o.value);
            });
            console.info('dss:' + dss);
            review();
        });

        var uos = [];
        $('#select-uo').bind('change', function (e) {
            uos.splice(0, uos.length);
            $(this).find('option:selected').each(function (index, o) {
                uos.push(o.value);
            });
            console.info('uos:' + uos);
            review();
        });

        var wss = [];
        $('#select-ws').bind('change', function (e) {
            wss.splice(0, wss.length);
            $(this).find('option:selected').each(function (index, o) {
                wss.push(o.value);
            });
            console.info('wss:' + wss);
            review();
        });

        $('.zodiac .ball').bind('tap', function (e) {
            if ($(this).hasClass('selective')) {
                $(this).removeClass('selective');
                selected_ball.splice(selected_ball.indexOf(this), 1);
            } else {
                $(this).addClass('selective');
                selected_ball.push(this);
            }
            reload();
//            e.preventDefault();
//            e.stopPropagation();
            return false;
        });

        $('.cart').delegate('.ball', 'tap', function (e) {
            var n1 = $(this).attr('data-value');
            for (var i = 0; selected_ball.length; i++) {
                var n2 = $(selected_ball[i]).attr('data-value');
                if (n1 == n2) {
                    selected_ball.splice(i, 1);
                    break;
                }
            }
            reload();
//            e.preventDefault();
//            e.stopPropagation();
            return false;
        });

        $('#capital').bind('input propertychange', function () {
            var capital = parseFloat($(this).val());
            if (isNaN(capital)) {
                $('.cart a').addClass("ui-state-disabled");
                $('.cart a').html('￥0.00 投注');
                $('.purchase').html('￥0.00 ');
            } else {
                $('.cart a').removeClass("ui-state-disabled");
                $('.cart a').html('￥' + capital.toFixed(2) + ' 投注');
                $('.purchase').html('￥' + capital.toFixed(2) + ' ');
            }
            reload();
        });

        var selected_ball = [];
        function review() {
            selected_ball.splice(0, selected_ball.length);
            var zodiacs_ball = [];
            var uos_ball = [];
            var dss_ball = [];
            var wss_ball = [];
            var colors_ball = [];
            $('li.tz .ball').each(function (i, data) {
                var n = $(data).attr('data-value');
                var z = $(data).attr('data-zodiac');
                var u = $(data).attr('data-uo');
                var d = $(data).attr('data-ds');
                var c = $(data).attr('data-color');
                var w = $(data).attr('data-ws');
                if (zodiacs.length > 0 && zodiacs.indexOf(z) > -1) {
                    zodiacs_ball.push(data);
                }
                if (uos.length > 0 && uos.indexOf(u) > -1) {
                    uos_ball.push(data);
                }
                if (dss.length > 0 && dss.indexOf(d) > -1) {
                    dss_ball.push(data);
                }
                if (wss.length > 0 && wss.indexOf(w) > -1) {
                    wss_ball.push(data);
                }
                if (colors.length > 0 && colors.indexOf(c) > -1) {
                    colors_ball.push(data);
                }
            });
            var intersection = [];
            var flag = false;
            if (zodiacs_ball.length > 0) {
                if (intersection.length > 0) {
                    intersection = intersection.filter(function (v) {
                        return zodiacs_ball.indexOf(v) > -1;
                    });
                } else if (!flag) {
                    intersection = zodiacs_ball;
                    flag = true;
                }
            }
            if (uos_ball.length > 0) {
                if (intersection.length > 0) {
                    intersection = intersection.filter(function (v) {
                        return uos_ball.indexOf(v) > -1;
                    });
                } else if (!flag) {
                    intersection = uos_ball;
                    flag = true;
                }
            }
            if (dss_ball.length > 0) {
                if (intersection.length > 0) {
                    intersection = intersection.filter(function (v) {
                        return dss_ball.indexOf(v) > -1;
                    });
                } else if (!flag) {
                    intersection = dss_ball;
                    flag = true;
                }
            }
            if (wss_ball.length > 0) {
                if (intersection.length > 0) {
                    intersection = intersection.filter(function (v) {
                        return wss_ball.indexOf(v) > -1;
                    });
                } else if (!flag) {
                    intersection = wss_ball;
                    flag = true;
                }
            }
            if (colors_ball.length > 0) {
                if (intersection.length > 0) {
                    intersection = intersection.filter(function (v) {
                        return colors_ball.indexOf(v) > -1;
                    });
                } else if (!flag) {
                    intersection = colors_ball;
                }
            }
//            console.info('intersection:' + intersection);
            if (intersection.length > -1) {
                $(intersection).each(function (i, d) {
                    selected_ball.push(d);
                });
            }
            reload();
        }

        function reload() {
            $('li.tz .ball').each(function (i, data) {
                $(data).removeClass('selective');
            });
            var html = '';
            $(selected_ball).each(function (i, data) {
                $(data).addClass('selective');
                html += '<li>' + data.outerHTML + '</li>';
            });
            $('.cart ul').html(html);

            if (selected_ball.length > 0) {
                $('.cart').show();
            } else {
                $('.cart').hide();
            }

            var odd = (45 / selected_ball.length).toFixed(2);
            $('.cart .odd').html('@' + odd);
            var capital = parseFloat($('#capital').val());
            if (isNaN(capital)) {
                $('.price').html('￥0.00 ');
            } else {
                $('.price').html('￥' + (capital * odd).toFixed(2) + ' ');
            }
        }
    </script>
@endsection