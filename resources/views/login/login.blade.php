<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Вход в систему</title>
    <script src="/js/jquery-1.4.2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}" />

</head>

<body>
<div id="login_msg" style="display:none">
</div>

<div id="login" style="height:auto;">
    <form method="post" action="{{route('auth.login1')}}" autocomplete="on" class="ajax_form">
        @csrf
        <h1>Вход в систему</h1>
        <p>
            <label for="name" class="name" data-icon="u" > Логин: </label>
            <input id="name" name="name" onkeydown="func_login(event)" style="box-sizing: border-box;" required type="text" />
        </p>
        <p>
            <label for="password" class="youpasswd" data-icon="p"> Пароль </label>
            <input id="password" name="password" onkeydown="func_login(event)"  style="box-sizing: border-box;" required type="password"  />
        </p>

        <p class="login button">
            <input type="submit" class="btn" id="login_btn" value="Войти" />
        </p>

    </form>
    <div id="info_login" style="color:red; padding:0px; text-align:center; margin:0 auto"></div>
</div>

<script>
    function func_login(event){
        if(event.keyCode==13){
            $('#login_btn').click();
        }
    }
    $(function(){

        $('#login_btn').click(function(){

            $.ajax(
                {
                    url: "/enter/check",
                    data: "do=login&log="+$("#username").val()+"&pass="+$("#password").val()+"&g-recaptcha-response="+$("#g-recaptcha-response").val()+"&x=secure",
                    type: "POST",
                    dataType : "html",
                    cache: false,
                    success:  function(data)  { $("#login_msg").html(data); },
                    error: function()         { $("#info_login").html("Невозможно связаться с сервером!"); }
                });

        })

    })
</script>


</body>
</html>
