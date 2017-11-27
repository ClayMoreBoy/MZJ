@extends('mobiles.layout.main')

@section('css')
    <link href="{{ asset('/css/mobiles/index.css') }}" rel="stylesheet">
    <style>
    </style>
@endsection

@section('title')
    工具图表
@endsection

@section('nav')
    <div class="ui-bar">
        <select id="select-zodiacs" data-native-menu="false" onchange="changeIssueCount(this)">
            <option>选择期数</option>
            <option value="50" {{ $issue_count==50?'selected':'' }}>近50期</option>
            <option value="100" {{ $issue_count==100?'selected':'' }}>近100期</option>
            <option value="200" {{ $issue_count==200?'selected':'' }}>近200期</option>
            <option value="500" {{ $issue_count==500?'selected':'' }}>近500期</option>
        </select>
    </div>
@endsection

@section('content')
    <canvas id="canvas"></canvas>
@endsection

@section('js')
    <script src="//cdn.bootcss.com/Chart.js/2.7.1/Chart.bundle.js"></script>
    <script>
        var horizontalBarChartData = {
            labels: ["1", "2", "3", "4", "5", "6", "7"],
            datasets: [{
                label: '特码',
                backgroundColor: 'rgb(255, 99, 132)',
                borderColor: 'rgb(255, 99, 132)',
                borderWidth: 1,
                data: [
                    34,
                    33,
                    32,
                    31,
                    30,
                    29,
                    28
                ]
            }]
        };

        window.onload = function () {
            var ctx = document.getElementById("canvas").getContext("2d");
            window.myHorizontalBar = new Chart(ctx, {
                type: 'horizontalBar',
                data: horizontalBarChartData,
                options: {
                    elements: {
                        rectangle: {
                            borderWidth: 3
                        }
                    },
                    responsive: false,
                    legend: {
                        display: false
                    },
                    title: {
                        display: false
                    }
                }
            });

        };

        function changeIssueCount(select) {
            location.href = '';
//            location.href = '?issue=' + select.value;
        }
    </script>
@endsection