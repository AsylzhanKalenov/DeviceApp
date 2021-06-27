<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>@yield('pageTitle')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Каленов Асылжан">
    <!-- Le styles -->
    <link href="{{asset('/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('/bootstrap/css/bootstrap-responsive.min.css')}}" rel="stylesheet">


    <style type="text/css">
        body {
            padding-top: 60px;
            padding-bottom: 40px;
        }
        .sidebar-nav {
            padding: 9px 0;
        }
        .table-striped input, .table-striped textarea, .table-striped select{ width:90%}

        .table-striped select{ width:93%}

        .table-striped input[type=button],.table-striped input[type=checkbox],.table-striped input[type=radio],.table-striped input[type=submit]{ width:auto}

        input[type=checkbox]{ margin:5px;}

        td{ padding:5px;}

        @charset "utf-8";
        /* CSS Document */

        * {
            margin:0px;
            padding:0px;
            font-family:Arial, Helvetica, sans-serif;
            font-size:12px;
        }
        body {
            margin:0px;
            padding:0px;
        }
        #main {
            background:url(/mc/images/admin_bg.jpg) repeat-x left top;
            min-width:1155px;
        }
        #width {
            margin:0 auto;
            width:100%;
            min-width:1155px;
        }
        img {
            border:0
        }
        #header {
            height:108px;
        }
        #top_menu a {
            margin-left:20px;
        }
        a {
            outline:none;
            color:#0095dd;
            text-decoration:none
        }
        #footer {
            height:20px;
            background:#cce9fb
        }
        #user_form {
            margin: 0;
            border: 0px solid #C0E3FA;
            -webkit-border-radius: 8px;
            -moz-border-radius: 8px;
            border-radius: 8px;
            -webkit-box-shadow: #666 0px 2px 3px;
            -moz-box-shadow: #666 0px 2px 3px;
            box-shadow: #666 0px 2px 3px;
            background: #DCEEFF;
            background: -webkit-gradient(linear, 0 0, 0 bottom, from(#F3F9FD), to(#CBE7FE));
            background: -moz-linear-gradient(#F3F9FD, #CBE7FE);
            background: linear-gradient(#F3F9FD, #CBE7FE);
            -pie-background: linear-gradient(#F3F9FD, #CBE7FE);
            behavior: url(/PIE.php);
            z-index:9;
        }
        #user_form td {
            padding:0px 10px 0px 10px;
            color:#138dce
        }
        #menu_link {
            border: 0px solid #C0E3FA;
            -webkit-border-radius: 8px;
            -moz-border-radius: 8px;
            border-radius: 8px;
            background: #DCEEFF;
            background: -webkit-gradient(linear, 0 0, 0 bottom, from(#CBE7FE), to(#fff));
            background: -moz-linear-gradient(#CBE7FE, #ffffff);
            background: linear-gradient(#CBE7FE, #ffffff);
            -pie-background: linear-gradient(#CBE7FE, #ffffff);
            behavior: url(/PIE.php);
            margin-top:10px;
            width:570px;
        }
        #menu_link ul {
            list-style:none;
            width:auto
        }
        #menu_link ul li {
            float:left;
            margin-right:6px;
        }
        #menu_link ul li a {
            text-transform:uppercase;
            font-size:14px;
            display:block;
            padding:3px 17px 3px 17px
        }
        #menu_link ul li a:hover, #menu_link ul li a.active {
            color:#fff;
            background:#fe080b;
            border: 0px solid #C0E3FA;
            -webkit-border-radius: 8px;
            -moz-border-radius: 8px;
            border-radius: 8px;
            background: -webkit-gradient(linear, 0 0, 0 bottom, from(#ff0603), to(#ff0603));
            background: -moz-linear-gradient(#fd6d6d, #ff0603);
            background: linear-gradient(#fd6d6d, #ff0603);
            -pie-background: linear-gradient(#fd6d6d, #ff0603);
            behavior: url(/PIE.php);
        }
        #block {
            background:#edf8fe;
            border: 0px solid #C0E3FA;
            -webkit-border-radius: 8px;
            -moz-border-radius: 8px;
            border-radius: 8px;
            overflow: hidden;
            width: 100%;
        }
        #block_menu {
            background:url(/mc/images/block_menu.jpg) repeat left top;
        }
        #block_menu ul {
            list-style:none;
        }
        #block_menu ul li {
            float:left;
            margin-right:10px;
        }
        #block_menu ul li a {
            padding:7px 10px 7px 10px;
            display:block
        }
        #block_menu ul li a:hover, #block_menu ul li a.active {
            color:#fff;
            background:#99d2f0
        }
        .s_t {
            width:100%;
            border-collapse:collapse
        }
        .s_t th {
            padding:7px;
            font-weight:normal;
            color:#0095dd
        }
        .s_t tr.row1 {
            background:#fff;
        }
        .s_t tr.row1:hover, .s_t tr.row1.active {
            background:#FFC000;
            cursor:pointer
        }
        .s_t tr.row2:hover, .s_t tr.row2.active {
            background:#FFC000;
            cursor:pointer
        }
        .s_t tr.row2 {
            background:#f1f8ff;
        }
        .s_t td {
            padding:5px;
        }
        .info_block {
            margin-left:10px;
            padding:5px;
            border:2px solid #def2fd;
            color:#0095dd
        }
        .info_block p {
            color:#222
        }
        .inp_d {
            display:inline-block;
            background:url(/mc/images/in_d.jpg) no-repeat left top;
            width:82px;
            height:20px;
        }
        .inp_d input {
            margin-left:3px;
            width:50px;
            margin-top:3px;
            border:none;
            background:none;
        }
        .select {
            width:250px;
            background:url(/mc/images/select.jpg) no-repeat left top;
            height:21px;
        }
        hr {
            border:none;
            border-bottom:1px dashed #b7e5f4;
        }
        input[type=submit] {
            -moz-border-bottom-colors: none;
            -moz-border-left-colors: none;
            -moz-border-right-colors: none;
            -moz-border-top-colors: none;
            background-color: #F5F5F5;
            background-image: linear-gradient(to bottom, #FFFFFF, #E6E6E6);
            background-repeat: repeat-x;
            border-color: #CCCCCC #CCCCCC #B3B3B3;
            border-image: none;
            border-radius: 4px 4px 4px 4px;
            border-style: solid;
            border-width: 1px;
            box-shadow: 0 1px 0 rgba(255, 255, 255, 0.2) inset, 0 1px 2px rgba(0, 0, 0, 0.05);
            color: #333333;
            cursor: pointer;
            display: inline-block;
            font-size: 14px;
            line-height: 20px;
            margin-bottom: 0;
            padding: 4px 12px;
            text-align: center;
            text-shadow: 0 1px 1px rgba(255, 255, 255, 0.75);
            vertical-align: middle;
            background-color: #3DAAE9;
            background-image: linear-gradient(to bottom, #46AEEA, #2FA4E7);
            background-repeat: repeat-x;
            border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
            color: #FFFFFF;
            text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
            box-shadow: 0 1px 0 rgba(255, 255, 255, 0.2) inset, 0 1px 2px rgba(0, 0, 0, 0.05);
        }
        input[type=submit]:hover {

        }

        .error {
            color:red;
            display:none
        }

    </style>

{{--    <link type="text/css" href="/css/smoothness/jquery-ui-1.8.24.custom.css" rel="stylesheet" />--}}
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>--}}
    <script src="{{asset('js/test.js')}}"></script>
</head>

<body>
<div class="navbar">
    <div class="navbar-inner">
        <div class="container-fluid">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <a class="brand" href="/service" style="color:#1D3C41">Каталог оборудования</a>
            <div class="nav-collapse collapse">

                <p class="navbar-text pull-right" style="color:#fff;">
                    Здравствуйте, {{\App\User::get_user(Auth::user()->id)->fio}} | <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();" style="color:#fff;">Выйти</a>
                </p>
                <ul class="nav" style="margin-left:83px;">
                    <li ><a href="/">Главная</a></li>
                    <li ><a href="/help">Помощь</a></li>

                </ul>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row-fluid">
        <div class="span2">
            <div class="well sidebar-nav">
                <ul class="nav nav-list">
{{--                    <li class="nav-header">Доступные модули</li>--}}
{{--                    <li style="margin-bottom:10px;"><a href="/service/isp" style="line-height:12px;">Исполнитель</a></li>--}}
{{--                    <li style="margin-bottom:10px;"><a href="/service/type" style="line-height:12px;">Вид работ</a></li>--}}
{{--                    <li style="margin-bottom:10px;"><a href="{{route('razdel.index')}}" style="line-height:12px;">Раздел</a></li>--}}

                    @if(Auth::user()->id_group == 1)
                    <div>
                    <li class="nav-header">Администратор</li>

                @foreach(get_array() as $r)
                        @if($r->id_group == 1)
                        <li style="margin-bottom:10px;"><a href="{{$r->link}}" style="line-height:12px;">{{$r->name}}</a></li>
                        @endif
                @endforeach
                    </div>
                    <div>
                        <li class="nav-header">Медсестра</li>

                        @foreach(get_array() as $r)
                            @if($r->id_group == 2)
                                <li style="margin-bottom:10px;"><a href="{{$r->link}}" style="line-height:12px;">{{$r->name}}</a></li>
                            @endif
                        @endforeach
                    </div>
                    <div>
                        <li class="nav-header">Диспетчер</li>

                        @foreach(get_array() as $r)
                            @if($r->id_group == 8)
                                <li style="margin-bottom:10px;"><a href="{{$r->link}}" style="line-height:12px;">{{$r->name}}</a></li>
                            @endif
                        @endforeach

                    </div>

                    @elseif(Auth::user()->id_group == 8)
                        <div>
                            <li class="nav-header">Диспетчер</li>

                            @foreach(get_array() as $r)
                                @if($r->id_group == 8)
                                    <li style="margin-bottom:10px;"><a href="{{$r->link}}" style="line-height:12px;">{{$r->name}}</a></li>
                                @endif
                            @endforeach

                        </div>
                    @elseif(Auth::user()->id_group == 2)
                        <div>
                            <li class="nav-header">Медсестра</li>

                            @foreach(get_array() as $r)
                                @if($r->id_group == 2)
                                    <li style="margin-bottom:10px;"><a href="{{$r->link}}" style="line-height:12px;">{{$r->name}}</a></li>
                                @endif
                            @endforeach
                        </div>
                    @elseif(Auth::user()->id_group == 7)
                        <div>
                            <li class="nav-header">Бухгалтер</li>

                            @foreach(get_array() as $r)
                                @if($r->id_group == 7)
                                    <li style="margin-bottom:10px;"><a href="{{$r->link}}" style="line-height:12px;">{{$r->name}}</a></li>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </ul>
            </div><!--/.well -->
        </div><!--/span-->
        <div class="span10">
            <div class="row-fluid">
                <h2 class="page-header" style="border-bottom: 1px solid #EEEEEE;
    margin: 10px 0 30px;
    padding-bottom: 9px; font-size:22px; font-weight:normal; line-height:18px;"></h2>