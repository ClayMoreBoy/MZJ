@extends('mobiles.layout.main')

@section('css')
    <link href="{{ asset('/css/mobiles/index.css') }}" rel="stylesheet">
    <style>
    </style>
@endsection

@section('title')
    开奖查询
@endsection

@section('nav')
    {{--<div data-role="navbar" data-inset="false">--}}
    {{--<ul>--}}
    {{--<li><a href="#" class="ui-btn-active">首页</a></li>--}}
    {{--<li><a href="#">页面二</a></li>--}}
    {{--<li><a href="#">页面二</a></li>--}}
    {{--<li><a href="#">页面二</a></li>--}}
    {{--</ul>--}}
    {{--</div>--}}
@endsection

@section('content')
    <div class="num-list">
        <ul class="head">
            <li class="first">期数</li>
            <li class="second">平码</li>
            <li class="third">特码</li>
        </ul>
        @foreach($issues as $issue)
            @if ($loop->first)
                <ul class="{{ ($issues->currentPage()==1&&$loop->first)?"first":"" }}">
                    <li class="issue">{{ $issue->id }}</li>
                    <li>
                        @if(is_numeric($issue->num1))
                            <span class="ball {{ $num_attr[$issue->num1*1]['color'] }}">{{ $issue->num1 }}</span>
                            <span class="zodiacs">{{ $num_attr[$issue->num1*1]['zodiacs'] }}</span>
                        @else
                            <span class="ball gray">?</span>
                            <span class="zodiacs">?</span>
                        @endif
                    </li>
                    <li>
                        @if(is_numeric($issue->num2))
                            <span class="ball {{ $num_attr[$issue->num2*1]['color'] }}">{{ $issue->num2 }}</span>
                            <span class="zodiacs">{{ $num_attr[$issue->num2*1]['zodiacs'] }}</span>
                        @else
                            <span class="ball gray">?</span>
                            <span class="zodiacs">?</span>
                        @endif
                    </li>
                    <li>
                        @if(is_numeric($issue->num3))
                            <span class="ball {{ $num_attr[$issue->num3*1]['color'] }}">{{ $issue->num3 }}</span>
                            <span class="zodiacs">{{ $num_attr[$issue->num3*1]['zodiacs'] }}</span>
                        @else
                            <span class="ball gray">?</span>
                            <span class="zodiacs">?</span>
                        @endif
                    </li>
                    <li>
                        @if(is_numeric($issue->num4))
                            <span class="ball {{ $num_attr[$issue->num4*1]['color'] }}">{{ $issue->num4 }}</span>
                            <span class="zodiacs">{{ $num_attr[$issue->num4*1]['zodiacs'] }}</span>
                        @else
                            <span class="ball gray">?</span>
                            <span class="zodiacs">?</span>
                        @endif
                    </li>
                    <li>
                        @if(is_numeric($issue->num5))
                            <span class="ball {{ $num_attr[$issue->num5*1]['color'] }}">{{ $issue->num5 }}</span>
                            <span class="zodiacs">{{ $num_attr[$issue->num5*1]['zodiacs'] }}</span>
                        @else
                            <span class="ball gray">?</span>
                            <span class="zodiacs">?</span>
                        @endif
                    </li>
                    <li>
                        @if(is_numeric($issue->num6))
                            <span class="ball {{ $num_attr[$issue->num6*1]['color'] }}">{{ $issue->num6 }}</span>
                            <span class="zodiacs">{{ $num_attr[$issue->num6*1]['zodiacs'] }}</span>
                        @else
                            <span class="ball gray">?</span>
                            <span class="zodiacs">?</span>
                        @endif
                    </li>
                    <li class="te">
                        @if(is_numeric($issue->num7))
                            <span class="ball {{ $num_attr[$issue->num7*1]['color'] }}">{{ $issue->num7 }}</span>
                            <span class="zodiacs">{{ $num_attr[$issue->num7*1]['zodiacs'] }}</span>
                        @else
                            <span class="ball gray">?</span>
                            <span class="zodiacs">?</span>
                        @endif
                    </li>
                </ul>
            @else
                <ul>
                    <li class="issue">{{ $issue->id }}</li>
                    <li>
                        <span class="ball {{ $num_attr[$issue->num1*1]['color'] }}">{{ $issue->num1 }}</span>
                        <span class="zodiacs">{{ $num_attr[$issue->num1*1]['zodiacs'] }}</span>
                    </li>
                    <li>
                        <span class="ball {{ $num_attr[$issue->num2*1]['color'] }}">{{ $issue->num2 }}</span>
                        <span class="zodiacs">{{ $num_attr[$issue->num2*1]['zodiacs'] }}</span>
                    </li>
                    <li>
                        <span class="ball {{ $num_attr[$issue->num3*1]['color'] }}">{{ $issue->num3 }}</span>
                        <span class="zodiacs">{{ $num_attr[$issue->num3*1]['zodiacs'] }}</span>
                    </li>
                    <li>
                        <span class="ball {{ $num_attr[$issue->num4*1]['color'] }}">{{ $issue->num4 }}</span>
                        <span class="zodiacs">{{ $num_attr[$issue->num4*1]['zodiacs'] }}</span>
                    </li>
                    <li>
                        <span class="ball {{ $num_attr[$issue->num5*1]['color'] }}">{{ $issue->num5 }}</span>
                        <span class="zodiacs">{{ $num_attr[$issue->num5*1]['zodiacs'] }}</span>
                    </li>
                    <li>
                        <span class="ball {{ $num_attr[$issue->num6*1]['color'] }}">{{ $issue->num6 }}</span>
                        <span class="zodiacs">{{ $num_attr[$issue->num6*1]['zodiacs'] }}</span>
                    </li>
                    <li class="te">
                        <span class="ball {{ $num_attr[$issue->num7*1]['color'] }}">{{ $issue->num7 }}</span>
                        <span class="zodiacs">{{ $num_attr[$issue->num7*1]['zodiacs'] }}</span>
                    </li>
                </ul>
            @endif
        @endforeach
        <div data-role="controlgroup" data-type="horizontal" data-mini="true">
            <a href="{{ $issues->url(1) }}"
               class="ui-btn ui-corner-all {{ $issues->currentPage()==1?'ui-state-disabled':'' }}"> 首页 </a>
            <a href="{{ $issues->previousPageUrl() }}"
               class="ui-btn ui-corner-all {{ $issues->currentPage()==1?'ui-state-disabled':'' }}"> 上一页 </a>
            <a href="#" class="ui-btn ui-corner-all">{{ $issues->currentPage() }}</a>
            <a href="{{ $issues->nextPageUrl() }}"
               class="ui-btn ui-corner-all {{ $issues->currentPage()==$issues->lastPage()?'ui-state-disabled':'' }}">
                下一页 </a>
            <a href="{{ $issues->url($issues->lastPage()) }}"
               class="ui-btn ui-corner-all {{ $issues->currentPage()==$issues->lastPage()?'ui-state-disabled':'' }}">
                尾页 </a>
        </div>
    </div>
@endsection


