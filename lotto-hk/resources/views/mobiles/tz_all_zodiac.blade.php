@extends('mobiles.layout.main')

@section('css')
    <link href="{{ asset('/css/mobiles/index.css?_t='.time()) }}" rel="stylesheet">
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
            @for($i=6; $i<12; $i++)
                <li class="tz">
                    <span class="ball red"
                          data-value="{{ $zodiacs[$i] }}"
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
        <div class="cart" style="display: block;">
            <input type="number" id="capital" placeholder="本金" data-clear-btn="true">
            <p style="margin-top: .5em;margin-bottom: 0.5em">
                {{ $game->oddName() }}:<i class="odd">@0.00</i>；{{ $game->bonusName() }}:<i class="odd price">￥0.00</i>。
            </p>
            <div class="basket">
                @for($i = 0 ; $i < $game->items_min ; $i++)
                    <span class="list"><span class="ball red">?</span></span>
                @endfor
            </div>
            <a href="#purchase"
               data-rel="popup"
               data-position-to="window"
               data-transition="pop"
               class="ui-btn ui-corner-all ui-btn-b ui-state-disabled"
               style="display: block;">￥0.00 投注</a>
            <p style="text-align: center;color: #bd362f;margin-top: 0.5em;margin-bottom: 0.5em;">请在开奖前10分钟投注</p>
        </div>
    </div>
@endsection

@section('js')
    <script>
        var itemsMax = parseFloat('{{ $game->items_max }}');
        var itemsMin = parseFloat('{{ $game->items_min }}');
        var game_id = '{{ $game->game_id }}';
                @if($game->game_id == \App\Models\UGame::k_type_all_solo)
        var is_solo = true;
                @else
        var is_solo = false;
                @endif
        var selected_ball = [];
        $('.zodiac .ball').bind('tap', function (e) {
            if ($(this).hasClass('selective')) {
                $(this).removeClass('selective');
                selected_ball.splice(selected_ball.indexOf(this), 1);
                reload();
            } else if (selected_ball.length < itemsMax) {
                $(this).addClass('selective');
                selected_ball.push(this);
                reload();
            } else {
                LAlert('不能超过' + itemsMax + '个选项', 'b');
            }
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
            return false;
        });

        var capital = 0;
        $('#capital').bind('input propertychange', function () {
            reload();
        });

        function reload() {
            $('li.tz .ball').each(function (i, data) {
                $(data).removeClass('selective');
            });
            var maxOdd = 0;
            capital = parseFloat($('#capital').val());
            var html = '';
            var gameOdd = 9999999;
            var selectCount = 0;
            if (selected_ball.length > 0) {
                var prePrice = capital / selected_ball.length;
                $(selected_ball).each(function (i, data) {
                    var odd = parseFloat($(data).attr('data-odd'));
                    gameOdd = Math.min(gameOdd, odd);
                    var value = $(data).attr('data-value');
                    maxOdd += prePrice * odd;
                    $(data).addClass('selective');
                    html += '<span class="list"><span class="ball red selective" data-value="' + value + '">' + data.innerText + '</span></span>';
                    selectCount++;
                });
            }
            for (; selectCount < itemsMin; selectCount++) {
                html += '<span class="list"><span class="ball red">?</span></span>';
            }
            $('.cart .basket').html(html);

            if (capital == 0 || isNaN(capital) || selected_ball.length == 0) {
                $('.cart a').addClass("ui-state-disabled");
                $('.cart a').html('￥0.00 投注');
                $('.purchase').html('￥0.00 ');
                $('.cart .odd').html('@0.00');
                $('.cart .price').html('￥0.00 ');
            } else {
                if (selected_ball.length >= itemsMin && selected_ball.length <= itemsMax) {
                    $('.cart a').removeClass("ui-state-disabled");
                } else {
                    $('.cart a').addClass("ui-state-disabled");
                }
                $('.cart a').html('￥' + capital.toFixed(2) + ' 投注');
                $('.purchase').html('￥' + capital.toFixed(2) + ' ');
                if (is_solo) {
                    $('.cart .odd').html('@' + (maxOdd / capital).toFixed(2));
                    $('.cart .price').html('￥' + (maxOdd).toFixed(2) + ' ');
                } else {
                    $('.cart .odd').html('@' + gameOdd.toFixed(2));
                    $('.cart .price').html('￥' + (gameOdd * capital).toFixed(2) + ' ');
                }
            }
        }

        function clean() {
            selected_ball = [];
            $('#capital').val('');
            reload();
        }

        function purchasePost() {
            var balls = [];
            for (var i = 0; i < selected_ball.length; i++) {
                balls.push(selected_ball[i].innerText);
            }
            if (balls.length < itemsMin || balls.length > itemsMax) {
                LAlert('选项数量错误', 'b');
                return;
            }
            if (capital < 2) {
                LAlert('本金必须在2以上', 'b');
                return;
            }
            var balls_str = balls.join('|');
            var token = '{{ csrf_token() }}';
            var issue = '{{ $issue->id }}';
            $.mobile.loading("show");
            $.post('/mobiles/games/all-zodiac/post/', {
                'game_id': '{{ $game->game_id }}',
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