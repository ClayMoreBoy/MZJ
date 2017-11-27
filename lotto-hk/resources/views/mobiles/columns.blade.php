@extends('mobiles.layout.main')

@section('css')
    <link href="{{ asset('/css/mobiles/index.css') }}" rel="stylesheet">
@endsection

@section('title')
    大神预测
@endsection

@section('content')
    <div style="margin:-1em -.5em;">
        @foreach($columns as $column)
            <div data-role="collapsible" data-collapsed="false">
                <h2>{{ $column->name or '' }}</h2>
                <ul data-role="listview" data-divider-theme="a">
                    @foreach($column->articles()->orderBy('released_at','desc')->take(5)->get() as $article)
                        <li><a href="#page_{{ $article->id }}">{{ $article->title }}</a></li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>
@endsection

@section('other_page')
    @foreach($columns as $column)
        @foreach($column->articles()->orderBy('released_at','desc')->take(5)->get() as $article)
            <div data-role="page" id="page_{{ $article->id }}">
                <div data-role="header">
                    <h1>{{ $article->shortTitle or '' }}</h1>
                    <a href="#" data-rel="back"
                       class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-back ui-nodisc-icon ui-alt-icon ui-btn-left"></a>
                </div>
                <div role="main" class="ui-content">
                    <div style="margin:-.5em -.5em;">
                        {!! $article->content or '' !!}
                    </div>
                </div>
            </div>
        @endforeach
    @endforeach
@endsection