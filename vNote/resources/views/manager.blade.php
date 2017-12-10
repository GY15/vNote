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
    <link href="{{ URL::asset('/') }}css/manager.css" rel="stylesheet">
    <link href="{{ URL::asset('/') }}css/font-awesome.min.css" rel="stylesheet">
    <link href="{{ URL::asset('/') }}css/button.css" rel="stylesheet">
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

        <div class="jumbotron banner-desc col-md-6 col-md-offset-5">
            <div class="container text-center">
                <div class="userBlock">
                </div>
            </div>
        </div>
    </div>
</header>

<div class="modal fade" id="userModify" tabindex="-1" role="dialog" aria-labelledby="loginLabel" aria-hidden="true">
    <div class="modal-dialog" style="margin-top: 100px;width: 27%">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" style="margin-top: 20px">修改用户信息</h4>
            </div>
            <div class="modal-body">
                <div class="form-group row" style="margin-top:20px">
                    <label class=" col-md-offset-1 col-md-2" style="margin-top: 5px;font-size: 15px;color:slategrey">账号</label>
                    <div class="titleNote col-md-8">
                        <input type="text"  id="emailModify" class="form-control"
                               style="color:gray;font-size:13px;border-color:rgba(200,200,200,0.2);background:rgba(200,200,200, 0.2);" disabled>
                    </div>
                </div>
                <div class="form-group row" style="margin-top:20px">
                    <label class=" col-md-offset-1 col-md-2" style="margin-top: 5px;font-size: 15px;color:slategrey">昵称</label>
                    <div class="titleNote col-md-8">
                        <input type="text"  id="nicknameModify" class="form-control"
                               style="color:gray;font-size:13px;border-color:rgba(200,200,200,0.2);background:rgba(200,200,200, 0.2);" >
                    </div>
                </div>
                <div class="form-group row" style="margin-top:20px">
                    <label class=" col-md-offset-1 col-md-2" style="margin-top: 5px;font-size: 15px;color:slategrey">密码</label>
                    <div class="titleNote col-md-8">
                        <input type="password"  id="passwordModify" class="form-control"
                               style="color:gray;font-size:13px;border-color:rgba(200,200,200,0.2);background:rgba(200,200,200, 0.2);">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="login-btn-group">
                    <button type="button" class="btn btn-primary" onclick="modifyUser()">修改</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="{{ URL::asset('/') }}js/jquery-3.2.1.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="{{ URL::asset('/') }}js/bootstrap.js"></script>
<!--<script src="../js/global.js"></script>-->
<script src="{{ URL::asset('/') }}js/icheck.js"></script>

<script type="text/javascript">
    function getAllUser() {
        $.ajax({
            type: 'POST',
            url: '/Ajax/getAllUser',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function(data){
                $(".userBlock").empty();
                $(".userBlock").append(data.users);
                initUser();
            },
            error: function(xhr, type){
                alert("发生了未知的错误");
            }

        });
    }
    $(document).ready(function(){
        getAllUser();
    });
    function initUser() {
        $(".delete").on('click',function () {
            var email= $(this).parent().parent().find(".email").html();
            $.ajax({
                type: 'POST',
                url: '/Ajax/deleteUser',
                data:{email:email},
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                success: function(data){
                    getAllUser();
                },
                error: function(xhr, type){
                    alert("发生了未知的错误");
                }
            });
        })
        $(".edit").on('click',function () {
            $('#userModify').modal();
            $('#emailModify').val($(this).parent().parent().find(".email").html());
            $('#nicknameModify').val($(this).parent().parent().find(".nickname").html());
            $('#passwordModify').val($(this).parent().parent().find(".password").html());
        })
    }
    function modifyUser() {
        var email = $('#emailModify').val();
        var nickname = $('#nicknameModify').val();
        var password = $('#passwordModify').val();
        $.ajax({
            type: 'POST',
            url: '/Ajax/modifyUser',
            data:{email:email,nickname:nickname,password:password},
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function(data){
                getAllUser();
                $("#userModify").modal("hide");
            },
            error: function(xhr, type){
                alert("发生了未知的错误");
            }
        });
    }

</script>

</body>
</html>