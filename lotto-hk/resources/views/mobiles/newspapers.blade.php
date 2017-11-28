@extends('mobiles.layout.main')

@section('css')
    <link href="{{ asset('/css/mobiles/index.css') }}" rel="stylesheet">
@endsection

@section('title')
    天机图
@endsection

@section('content')
    <div style="margin:-1em -.5em;">
        <fieldset data-role="controlgroup">
            <select name="select-zodiacs" id="select-zodiacs" data-native-menu="false"
                    onchange="changeIssue(this)">
                <option>选择期数</option>
                @foreach($issues as $issue)
                    <option value="{{ $issue->id }}" {{ $curr_issue==$issue->id?'selected':'' }}>第{{ $issue->id }}期
                    </option>
                @endforeach
            </select>
        </fieldset>

        <div data-role="collapsibleset" data-theme="a" data-content-theme="a">
            @foreach($nis as $ni)
                <div data-role="collapsible">
                    <h2>{{ isset($ni->newspaperO->name)?$ni->newspaperO->name:'' }}</h2>
                    <ul data-role="listview" data-divider-theme="a">
                        <li>
                            <a href="#page_{{ $ni->id }}_a" data-ajax="false">
                                {{--<img src="{{ 'http://images.6hch.com/'.$ni->image_a }}" style="width: 45%;">--}}
                                A面
                            </a>
                        </li>
                        <li>
                            <a href="#page_{{ $ni->id }}_b" data-ajax="false">
                                {{--<img src="{{ 'http://images.6hch.com/'.$ni->image_b }}" style="width: 45%;">--}}
                                B面
                            </a>
                        </li>
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@section('other_page')
    @foreach($nis as $ni)
        <div data-role="page" id="page_{{ $ni->id }}_a">
            <div data-role="header">
                <h1>{{ isset($ni->newspaperO->name)?$ni->newspaperO->name:'' }}-A面</h1>
                <a href="#" data-rel="back"
                   class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-back ui-nodisc-icon ui-alt-icon ui-btn-left"></a>
            </div>
            <div role="main" class="ui-content">
                <div style="margin:-.5em -.5em;">
                    <img src="{{ 'http://images.6hch.com/'.$ni->image_a }}" style="width: 100%;">
                </div>
            </div>
        </div>
        <div data-role="page" id="page_{{ $ni->id }}_b">
            <div data-role="header">
                <h1>{{ isset($ni->newspaperO->name)?$ni->newspaperO->name:'' }}-B面</h1>
                <a href="#" data-rel="back"
                   class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-back ui-nodisc-icon ui-alt-icon ui-btn-left"></a>
            </div>
            <div role="main" class="ui-content">
                <div style="margin:-.5em -.5em;">
                    <img src="{{ 'http://images.6hch.com/'.$ni->image_b }}" style="width: 100%;">
                </div>
            </div>
        </div>
    @endforeach
@endsection

@section('js')
    <script>
        function changeIssue(select) {
            location.href = '/mobiles/newspapers/?issue=' + select.value;
        }
    </script>
@endsection
