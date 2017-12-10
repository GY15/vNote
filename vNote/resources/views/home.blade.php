<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>vNote</title>

    <!-- Bootstrap -->
    <link href="{{ URL::asset('/') }}css/bootstrap.css" rel="stylesheet">
    <link href="{{ URL::asset('/') }}css/home.css" rel="stylesheet">
    <link href="{{ URL::asset('/') }}css/startLoader.css" rel="stylesheet">
    <link href="{{ URL::asset('/') }}css/flat/green.css" rel="stylesheet">

</head>
<body>
<header>

    <div class="banner-holder">
        <div class="banner-image-holder" style="z-index: 1">
            <div role="banner" id="fh5co-header" style=" background-position: 0px -170.2px;"
                 data-stellar-background-ratio="0.5">
                <img alt="Background" src="{{ URL::asset('/') }}img/home.jpg" >
                <div class="fh5co-overlay"></div>
            </div>
        </div>
        <!--<div class="jumbotron banner-desc col-md-4 col-md-offset-1" style="z-index: 10;background: transparent;margin-top: 100px">-->
        <!--<div class="container text-center">-->
        <!--<h1 style="color: lightgreen;font-size: 40px">开启您的私人笔记</h1>-->
        <!--</div>-->
        <!--</div>-->
        <div class="jumbotron banner-desc col-md-4 col-md-offset-7" style="z-index: 10">
            <div class="container text-center">
                <h1>vNote</h1>
                <div class="loginPanel" style="display: none">
                    <form class="form-horizontal" action="/login" method="POST" role="form">
                        <div class="form-group" style="margin-top: 50px">
                            <label class="col-md-3 col-md-offset-1 control-label" for="login_username"
                                   style="font-size: 18px;color:whitesmoke">邮&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;箱</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name = 'login_email' id="login_email" value="{{ old('login_email') }}" placeholder="请输入用户名">
                            </div>
                        </div>

                        <div class="form-group" style="margin-top: 40px">
                            <label class="col-md-3 col-md-offset-1 control-label" for="login_password"
                                   style="font-size: 18px;color:whitesmoke">密&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;码</label>
                            <div class="col-md-7">
                                <input type="password" class="form-control" name="login_password"  id="login_password" placeholder="请输入密码">
                            </div>
                        </div>
                        <div class="col-md-4 col-md-offset-8" style="color: whitesmoke">
                            <label id="rem-password">
                                <input type="checkbox" class="remember">记住密码
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary col-md-6 col-md-offset-3" style="margin-top: 30px"
                            onclick="login()">登&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;录
                        </button>
                    </form>
                    <a class="regBt col-md-2">注 册</a>
                </div>

                <div class="registerPanel" style="display: none">
                    <form class="form-horizontal" action="/register" method="post" role="form">
                        <div class="form-group" style="margin-top: 30px">
                            <label class="col-md-3 col-md-offset-1 control-label" for="reg_username"
                                   style="font-size: 18px;color:whitesmoke">邮&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;箱</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control"  name="reg_email" id="reg_email" placeholder="请输入邮箱">
                            </div>
                        </div>

                        <div class="form-group" style="margin-top: 20px">
                            <label class="col-md-3 col-md-offset-1 control-label" for="login_password"
                                   style="font-size: 18px;color:whitesmoke">昵&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;称</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="reg_nickname" id="reg_nickname" placeholder="请输入昵称">
                            </div>
                        </div>
                        <div class="form-group" style="margin-top: 20px">
                            <label class="col-md-3 col-md-offset-1 control-label" for="reg_password"
                                   style="font-size: 18px;color:whitesmoke">密&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;码</label>
                            <div class="col-md-7">
                                <input type="password" class="form-control" name="reg_password" placeholder="请输入密码">
                            </div>
                        </div>
                        <div class="form-group" style="margin-top: 20px">
                            <label class="col-md-3 col-md-offset-1 control-label" for="reg_password2"
                                   style="font-size: 18px;color:whitesmoke">确认密码</label>
                            <div class="col-md-7">
                                <input type="password" class="form-control" name="reg_password2" placeholder="请再次输入密码">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary col-md-6 col-md-offset-3" style="margin-top: 30px"
                                onclick="register()">注&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;册
                        </button>
                    </form>

                    <a class="loginBt col-md-3">返回登录</a>
                </div>
                <div class="col-md-12 errorMessage" style="margin-top: 20px;color: red;font-weight: 500" >
                    {{isset($errorMessage) ? $errorMessage : '' }}
                </div>
            </div>
        </div>
    </div>
</header>


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="{{ URL::asset('/') }}js/jquery-3.2.1.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="{{ URL::asset('/') }}js/bootstrap.js"></script>
<!--<script src="../js/global.js"></script>-->
<script src="{{ URL::asset('/') }}js/icheck.js"></script>

<script type="text/javascript">
    $('.loginBt').click(function () {
        $('.loginPanel').slideDown();
        $('.registerPanel').slideUp("fast");
    });
    $('.regBt').click(function () {
        $('.loginPanel').slideUp();
        $('.registerPanel').slideDown("fast");
    });
    $('.remember').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass:  'iradio_flat-green'
    });
    $(document).ready(function(){
        var a ={{isset($type) ? $type : "false"}} ;
        if (!a) {
            $(".loginPanel").show();
        }else{
            if (a == 1) {
                $(".registerPanel").show();
            } else {
                $(".loginPanel").show();
            }
        }
        setTimeout(function () {$('.errorMessage').hide()}, 2000);
    });
</script>

</body>
</html>