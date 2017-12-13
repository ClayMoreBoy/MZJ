<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="//cdn.bootcss.com/jquery-mobile/1.4.5/jquery.mobile.css" rel="stylesheet">
    <script src="//cdn.bootcss.com/jquery/1.10.2/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/jquery-mobile/1.4.5/jquery.mobile.js"></script>
    <script src="{{ url('/js/alert.js') }}"></script>
    <style>
        .ui-flipswitch {
            float: right;
            border-style: none;
            margin: 0;
        }

        .controlgroup-textinput {
            padding: .11em 0;
        }
    </style>
</head>
<body>
<div data-role="page" id="main">
    <div data-role="header">
        <h1 class="title">玩法设置</h1>
        <a href="/merchant/" data-ajax="false"
           class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-back ui-nodisc-icon ui-alt-icon ui-btn-left"></a>
    </div>
    <div role="main" class="ui-content">
        <div style="margin:-1em -.5em;">
            <ul data-role="listview" data-inset="true">
                @foreach($games as $game)
                    <li>
                        <div style="margin: .446em;">
                            <div style="display: inline-block">{{ $game->name }}</div>
                            <input type="checkbox"
                                   data-id="{{ $game->game_id }}"
                                   data-role="flipswitch"
                                   data-mini="true"
                                   data-on-text="开"
                                   data-off-text="关"
                                   {{ $game->on_off==1?'checked':'' }}
                                   onchange="gameOnOff(this);">
                        </div>
                        @if($game->game_id == \App\Models\UGame::k_type_all_zodiac)
                            <div class="odd_{{ $game->game_id }}">
                                <div data-role="controlgroup" data-type="horizontal" class="ui-mini">
                                    <button disabled="disabled">普宵</button>
                                    <input type="text"
                                           id="odd_{{ $game->game_id }}"
                                           size="14"
                                           value="{{ number_format($game->odd, 2) }}"
                                           data-wrapper-class="controlgroup-textinput ui-btn">
                                    <button onclick="updateOdd({{ $game->game_id }})">确定</button>
                                </div>
                                <div data-role="controlgroup" data-type="horizontal" class="ui-mini">
                                    <button disabled="disabled">特宵</button>
                                    <input type="text"
                                           id="odd1_{{ $game->game_id }}"
                                           size="14"
                                           value="{{ number_format($game->odd1, 2) }}"
                                           data-wrapper-class="controlgroup-textinput ui-btn">
                                    <button onclick="updateOdd1({{ $game->game_id }})">确定</button>
                                </div>
                            </div>
                        @else
                            <div class="odd_{{ $game->game_id }}">
                                <div data-role="controlgroup" data-type="horizontal" class="ui-mini">
                                    <button disabled="disabled">赔率</button>
                                    <input type="text"
                                           id="odd_{{ $game->game_id }}"
                                           size="14"
                                           value="{{ number_format($game->odd, 2) }}"
                                           data-wrapper-class="controlgroup-textinput ui-btn">
                                    <button onclick="updateOdd({{ $game->game_id }})">确定</button>
                                </div>
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
</body>
<script>
    $('input[type=checkbox]').each(function (i, d) {
        if ($(d).attr('checked')) {
            $('.odd_' + $(d).attr('data-id')).show();
        } else {
            $('.odd_' + $(d).attr('data-id')).hide();
        }
    });
    function gameOnOff(flip) {
        id = $(flip).attr('data-id');
        if (flip.checked) {
            $('.odd_' + id).show();
        } else {
            $('.odd_' + id).hide();
        }
        $.mobile.loading("show");
        $.post('/merchant/game/update/', {
            '_token': '{{ csrf_token() }}',
            id: id,
            key: 'on_off',
            value: flip.checked ? 1 : 0
        }, function (data) {
            $.mobile.loading("hide");
            if (data.code == 0) {
                if (flip.checked) {
                    $('.odd_' + id).show();
                } else {
                    $('.odd_' + id).hide();
                }
            } else if (data.code == 403) {
                location.reload();
            } else {
                LAlert(data.message, 'b');
            }
        });
    }
    function updateOdd(id) {
        var value = $('#odd_' + id).val();
        $.mobile.loading("show");
        $.post('/merchant/game/update/', {
            '_token': '{{ csrf_token() }}',
            id: id,
            key: 'odd',
            value: value
        }, function (data) {
            $.mobile.loading("hide");
            if (data.code == 0) {
                LAlert('更新成功', 'a');
            } else if (data.code == 403) {
                location.reload();
            } else {
                LAlert(data.message, 'b');
            }
        });
    }
    function updateOdd1(id) {
        var value = $('#odd1_' + id).val();
        $.mobile.loading("show");
        $.post('/merchant/game/update/', {
            '_token': '{{ csrf_token() }}',
            id: id,
            key: 'odd1',
            value: value
        }, function (data) {
            $.mobile.loading("hide");
            if (data.code == 0) {
                LAlert('更新成功', 'a');
            } else if (data.code == 403) {
                location.reload();
            } else {
                LAlert(data.message, 'b');
            }
        });
    }
</script>
</html>