@extends('mobiles.layout.main')
@section('title')
开奖查询
@endsection

@section('nav')
    <div data-role="navbar" data-inset="false">
        <ul>
            <li><a href="#" class="ui-btn-active">首页</a></li>
            <li><a href="#">页面二</a></li>
            <li><a href="#">页面二</a></li>
            <li><a href="#">页面二</a></li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="ui-field-contain">
        {{--<label for="select-zodiacs">选择生肖:</label>--}}
        <select name="select-zodiacs" id="select-zodiacs" data-native-menu="false" multiple="multiple"
                data-iconpos="left">
            <option>选择生肖</option>
            <optgroup label="家禽">
                <option value="2">牛</option>
                <option value="7">马</option>
                <option value="8">羊</option>
                <option value="10">鸡</option>
                <option value="11">狗</option>
                <option value="12">猪</option>
            </optgroup>
            <optgroup label="野兽">
                <option value="1">鼠</option>
                <option value="3">虎</option>
                <option value="4">兔</option>
                <option value="5">龙</option>
                <option value="6">蛇</option>
                <option value="9">猴</option>
            </optgroup>
        </select>
        <select name="select-colors" id="select-colors" data-native-menu="false" multiple="multiple"
                data-iconpos="left">
            <option>选择波色</option>
            <option value="1">红</option>
            <option value="2">蓝</option>
            <option value="3">绿</option>
        </select>
        <select name="select-uo" id="select-uo" data-native-menu="false" multiple="multiple"
                data-iconpos="left">
            <option>选择大小</option>
            <option value="1">大</option>
            <option value="2">小</option>
        </select>
        <select name="select-ds" id="select-ds" data-native-menu="false" multiple="multiple"
                data-iconpos="left">
            <option>选择单双</option>
            <option value="1">单</option>
            <option value="2">双</option>
        </select>
    </div>
@endsection


