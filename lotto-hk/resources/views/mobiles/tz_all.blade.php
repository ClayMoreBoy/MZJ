@extends('mobiles.layout.main')

@section('css')
    <link href="{{ asset('/css/mobiles/index.css') }}" rel="stylesheet">
    <style>
        .ui-header .ui-title {
            margin: 0 25%;
        }
    </style>
@endsection

@section('title')
    @if(isset($game))
        {{ $game->name }}-{{ $issue->id }}期
    @else
        该玩法暂未开通
    @endif
@endsection

@section('content')
    @if(isset($game))
        <div class="tz-num-list zodiac">
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
                            <span class="odd">{{ '@'.number_format($game->odd, 2) }}</span>
                        </li>
                    @endforeach
                </ul>
            @endforeach
        </div>
        <div data-role="popup" id="purchase" data-overlay-theme="b" data-theme="b" data-dismissible="false"
             style="max-width:400px;">
            <div data-role="header" data-theme="a"><h1>确认投注</h1></div>
            <div role="main" class="ui-content">
                <p>您确定要投注<i class="odd purchase">￥0.00 </i>吗？</p>
                <a href="#" onclick="purchasePost();"
                   class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b ui-icon-check ui-btn-icon-left"
                   data-rel="back">确定</a>
                <a href="#"
                   class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b ui-icon-delete ui-btn-icon-left"
                   data-rel="back" data-transition="flow">取消</a>
            </div>
        </div>
    @endif
@endsection

@section('footer')
    @if(isset($game))
        <div data-role="footer" data-position="fixed">
            <h1>开奖时间：{{ substr($issue->date,5,11) }}</h1>
            <div class="tz-num-list cart" style="display: none;">
                <input type="number" id="capital" placeholder="本金" data-clear-btn="true">
                <p style="margin-top: .5em;margin-bottom: 0.5em">
                    综合赔率<i class="odd">@0.00</i>；返还<i class="odd price">￥0.00</i>。
                </p>
                <div class="basket">
                    <ul>
                        {{--<li>--}}
                        {{--<span class="ball green selective">02</span>--}}
                        {{--</li>--}}
                    </ul>
                </div>
                <a href="#purchase" data-rel="popup" data-position-to="window" data-transition="pop"
                   class="ui-btn ui-corner-all ui-btn-b ui-state-disabled" style="display: block;">￥0.00 投注</a>
                <p style="text-align: center;color: #bd362f;margin-top: 0.5em;margin-bottom: 0.5em;">请在开奖前10分钟投注</p>
            </div>
        </div>
    @endif
@endsection

@section('js')
    <script>
        var gameOdd = parseFloat('{{ $game->odd }}');
        $('.zodiac .ball').bind('tap', function (e) {
            if ($(this).hasClass('selective')) {
                $(this).removeClass('selective');
                selected_ball.splice(selected_ball.indexOf(this), 1);
            } else {
                $(this).addClass('selective');
                selected_ball.push(this);
            }
            reload();
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
            capital = parseFloat($(this).val());
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
            var odd = (gameOdd / selected_ball.length).toFixed(2);
            $('.cart .odd').html('@' + odd);
//            var capital = parseFloat($('#capital').val());
            if (isNaN(capital)) {
                $('.price').html('￥0.00 ');
            } else {
                $('.price').html('￥' + (capital * odd).toFixed(2) + ' ');
            }
        }

        function clean() {
            selected_ball.splice(0, selected_ball.length);
            $('#capital').val('');
            reload();
        }

        function purchasePost() {
            var balls = [];
            for (var i = 0; i < selected_ball.length; i++) {
                balls.push(selected_ball[i].innerText);
            }
            if (balls.length < 1 || balls.length > 49) {
                LAlert('参数错误', 'b');
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
            $.post('/mobiles/games/all/post/', {
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