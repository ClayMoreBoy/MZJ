@extends('mobiles.layout.main')

@section('css')
    <link href="{{ asset('/css/mobiles/index.css') }}" rel="stylesheet">
    <style>
        .ui-header .ui-title {
            margin: 0 25%;
        }

        .tz-num-list li {
            width: 16.66%;
        }

        .ball.red {
            background: #2F3133;
        }

        .zodiac ul {
            height: 4.5em;
        }

        .tz-num-list li .ball {
            line-height: 2em;
            height: 2em;
            width: 2em;
        }

        .tz-num-list li .odd {
            line-height: 2em;
            height: 2em;
            font-size: 0.6em;
        }
    </style>
@endsection

@section('title')
    {{ $game->name }}-{{ $issue->id }}期
@endsection

@section('content')
    <div class="tz-num-list zodiac">
        <ul>
            @for($i=0; $i<6;$i++)
                <li class="tz">
                    <span class="ball red" data-value="{{ $zodiacs[$i] }}"
                          data-odd="{{ $zodiacs[$i] == $first_zodiac?$game->odd1:$game->odd }}">
                        {{ $zodiacs[$i] }}
                    </span>
                    <span class="odd">
                        {{ '@'.number_format($zodiacs[$i] == $first_zodiac?$game->odd1:$game->odd, 2) }}
                    </span>
                </li>
            @endfor
        </ul>
        <ul>
            @for($i=6; $i<12;$i++)
                <li class="tz">
                    <span class="ball red" data-value="{{ $zodiacs[$i] }}"
                          data-odd="{{ $zodiacs[$i] == $first_zodiac?$game->odd1:$game->odd }}">
                        {{ $zodiacs[$i] }}
                    </span>
                    <span class="odd">
                        {{ '@'.number_format($zodiacs[$i] == $first_zodiac?$game->odd1:$game->odd, 2) }}
                    </span>
                </li>
            @endfor
        </ul>
    </div>

    <div data-role="popup" id="purchase" data-overlay-theme="b" data-theme="b" data-dismissible="false"
         style="max-width:400px;">
        <div data-role="header" data-theme="a"><h1>确认投注</h1></div>
        <div role="main" class="ui-content">
            <p>您确定要投注<i class="odd purchase">￥0.00 </i>吗？</p>
            <a href="#" onclick="purchasePost();"
               class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b ui-icon-check ui-btn-icon-left"
               data-rel="back">确定</a>
            <a href="#" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b ui-icon-delete ui-btn-icon-left"
               data-rel="back" data-transition="flow">取消</a>
        </div>
    </div>
@endsection

@section('footer')
    <div data-role="footer" data-position="fixed">
        <h1>开奖时间：{{ substr($issue->date,5,11) }}</h1>
        <div class="tz-num-list cart" style="display: block;">
            <input type="number" id="capital" placeholder="本金" data-clear-btn="true">
            <p style="margin-top: .5em;margin-bottom: 0.5em">
                综合赔率<i class="odd">@0.00</i>；返还<i class="odd price">￥0.00</i>。
            </p>
            <div class="basket">
                <ul>
                    <li style="width: 100%"><span class="ball red">?</span></li>
                </ul>
            </div>
            <a href="#purchase" data-rel="popup" data-position-to="window" data-transition="pop"
               class="ui-btn ui-corner-all ui-btn-b ui-state-disabled" style="display: block;">￥0.00 投注</a>
            <p style="text-align: center;color: #bd362f;margin-top: 0.5em;margin-bottom: 0.5em;">请在开奖前10分钟投注</p>
        </div>
    </div>
@endsection

@section('js')
    <script>
        var selected_ball = '';
        var selected_odd = 0;
        $('.zodiac .ball').bind('tap', function (e) {
            if ($(this).hasClass('selective')) {
                selected_ball = '';
                selected_odd = 0;
            } else {
                selected_ball = $(this).attr('data-value');
                selected_odd = $(this).attr('data-odd');
            }
            reload();
            return false;
        });

        $('.cart').delegate('.ball', 'tap', function (e) {
            selected_ball = '';
            selected_odd = 0;
            reload();
            return false;
        });

        var capital = 0;
        $('#capital').bind('input propertychange', function () {
            reload();
        });

        function reload() {
            $('li.tz .ball').each(function (i, data) {
                if (selected_ball == $(data).attr('data-value')) {
                    if (!$(data).hasClass('selective')) {
                        $(data).addClass('selective');
                    }
                } else {
                    $(data).removeClass('selective');
                }
            });
            $('.cart .ball').html(selected_ball == '' ? '?' : selected_ball);
            if (parseFloat(selected_odd) > 0) {
                $('.cart .ball').addClass('selective');
                $('.cart .odd').html('@' + parseFloat(selected_odd).toFixed(2));
            } else {
                $('.cart .ball').removeClass('selective');
                $('.cart .odd').html('@' + parseFloat(selected_odd).toFixed(2));
            }

            capital = parseFloat($('#capital').val());
            if (capital == 0 || isNaN(capital) || selected_ball == '' || selected_odd == 0) {
                $('.cart a').addClass("ui-state-disabled");
                $('.cart a').html('￥0.00 投注');
                $('.purchase').html('￥0.00 ');
                $('.price').html('￥0.00 ');
            } else {
                $('.cart a').removeClass("ui-state-disabled");
                $('.cart a').html('￥' + capital.toFixed(2) + ' 投注');
                $('.purchase').html('￥' + capital.toFixed(2) + ' ');
                $('.price').html('￥' + (capital * parseFloat(selected_odd)).toFixed(2) + ' ');
            }
        }

        function clean() {
            selected_ball = '';
            selected_odd = 0;
            $('#capital').val('');
            reload();
        }

        function purchasePost() {
            if (selected_ball != '') {
                LAlert('参数错误', 'b');
                return;
            }
            if (capital < 2) {
                LAlert('本金必须在2以上', 'b');
                return;
            }
            var balls_str = selected_ball;
            var token = '{{ csrf_token() }}';
            var issue = '{{ $issue->id }}';
            $.mobile.loading("show");
            $.post('/mobiles/games/all-zodiac/post/', {
                'total_fee': capital.toFixed(2),
                'balls': balls_str,
                'issue': issue,
                '_token': token
            }, function (data) {
                $.mobile.loading("hide");
                if (data.code == 0) {
                    LAlert('下单成功', 'a');
                    clean();
                    refreshBalance(true);
                } else {
                    LAlert(data.message, 'b');
                }
            });
            console.info(balls_str);
        }
    </script>
@endsection