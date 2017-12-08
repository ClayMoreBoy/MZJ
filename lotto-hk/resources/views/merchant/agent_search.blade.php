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
        <h1 class="title">代理人列表</h1>
        <a href="/merchant/" data-ajax="false"
           class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-back ui-nodisc-icon ui-alt-icon ui-btn-left"></a>
    </div>
    <div role="main" class="ui-content">
        <div style="margin:-1em -.5em;">
            <ul data-role="listview" data-inset="true" data-split-icon="edit">
                @foreach($agents as $agent)
                    <li>
                        <a href="/merchant/agent/view/{{ $agent->id }}/" data-ajax="false"><h3>{{ $agent->name }}</h3></a>
                        <a href="/merchant/agent/edit/{{ $agent->id }}/" data-ajax="false"></a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
</body>
</html>