<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="//cdn.bootcss.com/jquery-mobile/1.4.5/jquery.mobile.css" rel="stylesheet">
    <script src="//cdn.bootcss.com/jquery/1.10.2/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/jquery-mobile/1.4.5/jquery.mobile.js"></script>
</head>
<body>
<div data-role="page" id="main">
    <div data-role="header">
        <h1 class="title">工具图表</h1>
        <a href="#" data-rel="back"
           class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-back ui-nodisc-icon ui-alt-icon ui-btn-left"></a>
        <a href="/mobiles/home/" data-ajax="false"
           class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-home ui-nodisc-icon ui-alt-icon ui-btn-right"></a>
        <div class="ui-bar">
            <select id="select-zodiacs" data-native-menu="false" onchange="changeIssueCount(this)">
                <option>选择期数</option>
                <option value="50" {{ $issue_count==50?'selected':'' }}>近50期</option>
                <option value="100" {{ $issue_count==100?'selected':'' }}>近100期</option>
                <option value="200" {{ $issue_count==200?'selected':'' }}>近200期</option>
                <option value="500" {{ $issue_count==500?'selected':'' }}>近500期</option>
            </select>
        </div>
    </div>
    <div role="main" class="ui-content">
        <canvas id="canvas" height="1024"></canvas>
    </div>
</div>
</body>
<script src="//cdn.bootcss.com/Chart.js/2.7.1/Chart.bundle.js"></script>
<script>
    var labels = [];
    var datas = [];
    var title = '';
    var height = 1200;
    @if($action == 'te-number')
        title = '特码走势';
        @foreach($te_numbers as $number=>$count)
            labels.push('{{ $number }}');
            datas.push({{ $count }});
        @endforeach
    @elseif($action == 'te-zodiac')
        title = '特码生肖';height = 350;
        @foreach($te_zodiacs as $number=>$count)
            labels.push('{{ $number }}');
            datas.push({{ $count }});
        @endforeach
    @elseif($action == 'te-weishu')
        title = '特码尾数';height = 300;
        @foreach($te_weishus as $number=>$count)
            labels.push('{{ $number }}尾');
            datas.push({{ $count }});
        @endforeach
    @elseif($action == 'te-color')
        title = '特码波色';height = 150;
        @foreach($te_colors as $number=>$count)
            labels.push('{{ $number }}');
            datas.push({{ $count }});
        @endforeach
    @elseif($action == 'ping-number')
        title = '平特号码';
        @foreach($ping_numbers as $number=>$count)
            labels.push('{{ $number }}');
            datas.push({{ $count }});
        @endforeach
    @elseif($action == 'ping-zodiac')
        title = '平特生肖';height = 350;
        @foreach($ping_zodiacs as $number=>$count)
            labels.push('{{ $number }}');
            datas.push({{ $count }});
        @endforeach
    @elseif($action == 'ping-weishu')
        title = '平特尾数';height = 300;
        @foreach($ping_weishus as $number=>$count)
            labels.push('{{ $number }}尾');
            datas.push({{ $count }});
        @endforeach
    @elseif($action == 'ping-color')
        title = '平特波色';height = 150;
        @foreach($ping_colors as $number=>$count)
            labels.push('{{ $number }}');
            datas.push({{ $count }});
        @endforeach
    @endif

    $('.title').html(title);
    $('#canvas').attr('height',height);

    var barData = {
                labels: labels,
                datasets: [{
                    label: title,
                    backgroundColor: 'rgb(255, 99, 132)',
                    borderColor: 'rgb(255, 99, 132)',
                    borderWidth: 1,
                    data: datas
                }]
            };

    var options = {
        maintainAspectRatio: false,
        scales: {
            yAxes: [{
                stacked: true,
                gridLines: {
                    display: true,
                    color: "rgba(255,99,132,0.2)"
                }
            }],
            xAxes: [{
                gridLines: {
                    display: false
                }
            }],
            responsive: false,
            legend: {
                display: false
            },
            title: {
                display: false
            }
        }
    };

    window.onload = function () {
        var ctx = document.getElementById("canvas").getContext("2d");
        window.myHorizontalBar = new Chart(ctx, {
            type: 'horizontalBar',
            data: barData,
            options: options
        });
    };

    function changeIssueCount(select) {
        location.href = '?issue_count=' + select.value;
    }
</script>
</html>