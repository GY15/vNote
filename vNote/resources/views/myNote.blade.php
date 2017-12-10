<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="_token" content="{{ csrf_token() }}"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>vNote</title>

    <!-- Bootstrap -->
    <link href="{{ URL::asset('/') }}css/bootstrap.css" rel="stylesheet">
    <link href="{{ URL::asset('/') }}css/font-awesome.min.css" rel="stylesheet">
    <link href="{{ URL::asset('/') }}css/myNote.css" rel="stylesheet">
    <link href="{{ URL::asset('/') }}css/button.css" rel="stylesheet">
<!--<link href="{{ URL::asset('/') }}css/summernote.css" rel="stylesheet">-->
    <link href="{{ URL::asset('/') }}css/startLoader.css" rel="stylesheet">
    <link href="{{ URL::asset('/') }}css/flat/green.css" rel="stylesheet">
    <link href="{{ URL::asset('/') }}css/bootstrap-switch.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::asset('/') }}css/bootstrap-select.css">


</head>
<body>
<header>
    <div class="banner-holder">
        <div class="banner-image-holder" style="z-index: 1">
            <div role="banner" id="fh5co-header" style=" background-position: 0px -170.2px;"
                 data-stellar-background-ratio="0.5">
                <img alt="Background" src="{{ URL::asset('/') }}img/home.jpg" draggable="true ">
                <div class="fh5co-overlay"></div>
            </div>
        </div>

        <div class="down"></div>
        <div class="logo"><img alt="Background" src="{{ URL::asset('/') }}img/logo.png" style="max-width: 100% "></div>

        <div class="row">
            <div class="part1 text-center" style="z-index: 100">
                <button class="button button-primary button-circle button-large addNote" style="margin-top: 30%"><i
                            class="icon-plus"></i></button>
                <button class="button button-primary button-circle button-large allNote"><i class="icon-pencil"></i>
                </button>
                <button class="button button-primary button-box button-large allBook"><i class="icon-book "></i>
                </button>
                <button class="button button-action button-box button-large share" style="margin-top: 280%;"><i
                            class="icon-group"></i></button>
                <button class="button button-royal button-square button-large community" style="margin-top: 20%;"><i
                            class="icon-comments"></i></button>
            </div>

            <div class="part2">
                <div class="text-center" style="color: cornflowerblue"><h3 class="curbookname">全部笔记</h3><h3 class="curbookid" style="display: none">0</h3></div>
                <div class = "nowBook" style="display: none"></div>
                <div>
                    <input type="text" class="form-control searchNote" placeholder="输入笔记名称或者标签"
                           style="color:gray;font-size:13px;border-color:rgba(1,1,1,0);background:rgba(255,255, 255, 0.4);">
                </div>
                <div class="noteList">

                </div>
            </div>

            <div class="part3 " style="width: 0;display: none">
                <div class="row" style="padding-top: 15px">
                    <p id="thisNoteId" style="display:none"></p>
                    <div class="col-md-4 row newNote" style="display: none">
                        <div class="form-group row col-md-4 col-md-offset-1">
                            <label class="col-md-4" style="margin-top: 4px;font-size: 20px;color:slategrey"><i
                                        class="icon-book"></i></label>
                            <div class=' col-md-5' id="selectBlock">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 row oldNote" style="margin-bottom: 13px;">
                        <label class="col-md-1 col-md-offset-2" style="margin-top: 4px;font-size: 20px;color:slategrey"><i
                                    class="icon-book"></i></label>
                        <div class=' col-md-8' style="margin-top: 6px;font-size: 16px;color: white">
                            <p class="noteTitle" id="theBook" style="font-weight: 600;color: #31b0d5">你的第一本笔记</p>
                        </div>
                    </div>
                    <div class=" col-md-4" style="margin-left: -40px">
                        <label class="col-md-1" style="margin-top: 3px;font-size: 20px;color:slategrey"><i
                                    class="icon-pencil"></i></label>
                        <div class="titleNote col-md-10">
                            <input type="text" id="theTitle" class="form-control" placeholder="给笔记起个名字吧"
                                   style="color:rgba(26,183,234,1);font-size:20px;border-color:rgba(0,0,0,0);background:rgba(0, 0, 0, 0);">
                        </div>
                    </div>

                    <div class=" col-md-3" style="margin-left: -40px">
                        <label class="col-md-1" style="margin-top: 5px;font-size: 15px;color:slategrey"><i
                                    class="icon-tags"></i></label>
                        <div class="titleNote col-md-10">
                            <input type="text"  id="theTag" class="form-control" placeholder="添加你的标签"
                                   style="color:gray;font-size:13px;border-color:rgba(1,1,1,0);background:rgba(0, 0, 0, 0);">
                        </div>
                    </div>
                    <div id="toggle-state-switch" class="switch switchBt">
                        <input id="thePublic" type="checkbox" >
                    </div>

                </div>
                <div class="text-area" >
                    <div class="summernote mynote" style="z-index: 1000"></div>
                    <div id="modifyImg" style="position: absolute;left:42%;top:42%;z-index: 2" >
                        <label class="col-md-1" style="font-size: 50px;color:honeydew"><i
                                    class="icon-pencil"></i></label></div>
                </div>

            </div>

            <div class="part4" style="display: none">
                <div class="row" style="color: cornflowerblue">
                    <div class="col-md-5 col-md-offset-1">
                        <h3>私人笔记本</h3>
                    </div>
                    <div class="col-md-2 col-md-offset-3">
                        <button class="createBook button button-circle button" style="margin-top: 8px;background-color:   limegreen;
                                border-color: limegreen;"><i class=" icon-copy"></i></button>
                    </div>
                </div>
                <div>
                    <input type="text" class="form-control searchBook" placeholder="输入笔记本名称或者标签"
                           style="color:gray;font-size:13px;border-color:rgba(1,1,1,0);background:rgba(0, 0, 0, 0);">
                </div>
                <div class="bookList">
                </div>
            </div>

            <div class="part5" style="display: none">
                <div class="row">
                    <div class="col-md-6" style="padding-right: 0px;margin-top: 15px;">
                        <div class="col-md-8" style="margin-bottom:8px;margin-left: 30px">
                            <div>
                                <input type="text" class="form-control inputCommunity" placeholder="输入笔记或者标签搜索"
                                       style="color:gray;font-size:13px;border-color:rgba(1,1,1,0);background:rgba(255,255, 255, 0.6);">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btCommunity btn btn-primary" style="background: cornflowerblue;border-color: cornflowerblue" >搜索或换一批</button>
                        </div>
                        <div class="row errorMessage">
                            <div class="col-md-4 col-md-offset-8" style="margin-top: 0px;color: red;font-weight: 600" >
                                 无匹配的笔记 请重新输入
                            </div>
                        </div>
                        <div class="noteListCommunity col-md-12" style="padding-right: 0px">

                        </div>
                    </div>
                    <div class=" col-md-6" style="padding-left: 0px;">
                        <div class="col-md-12">
                            <div class="text-center" style="color: #42abf8">
                                <h3>笔记排行榜</h3>
                            </div>
                        </div>
                        <div class="noteListTop col-md-12" style="padding-right: 0px;" >

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>


</header>

<div class="modal fade" id="bookModify" tabindex="-1" role="dialog" aria-labelledby="loginLabel" aria-hidden="true">
    <div class="modal-dialog" style="margin-top: 100px;width: 27%">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" style="margin-top: 20px">编辑你的笔记本</h4>
            </div>
            <div class="modal-body">
                <div style="display: none;" class="modifyId"></div>
                <div class="form-group row" style="margin-top:20px">
                    <label class=" col-md-offset-1 col-md-1" style="margin-top: 5px;font-size: 15px;color:slategrey"><i
                                class="icon-book"></i></label>
                    <div class="titleNote col-md-9">
                        <input type="text"  id="modifyTitle" class="form-control" placeholder="给笔记本起个名字"
                               style="color:gray;font-size:13px;border-color:rgba(200,200,200,0.2);background:rgba(200,200,200, 0.2);">
                    </div>
                </div>
                <div class="form-group row" style="margin-top:20px">
                    <label class=" col-md-offset-1 col-md-1" style="margin-top: 5px;font-size: 15px;color:slategrey"><i
                                class="icon-tags"></i></label>
                    <div class="titleNote col-md-9">
                        <input type="text"  id="modifyTag" class="form-control" placeholder="添加你的标签"
                               style="color:gray;font-size:13px;border-color:rgba(200,200,200,0.2);background:rgba(200,200,200, 0.2);">
                    </div>
                </div>
                <strong class="col-md-offset-4" style="color:red;" id="errorModify"></strong>
            </div>
            <div class="modal-footer">
                <div class="login-btn-group">
                    <button type="button" class="btn btn-primary" onclick="modifyBook()">修改</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                </div>
            </div>
        </div>
    </div>
</div>

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
<!--<script src="{{ URL::asset('/') }}js/global.js"></script>-->
<script src="{{ URL::asset('/') }}js/icheck.js"></script>
<script src="{{ URL::asset('/') }}js/bootstrap-select.js"></script>
<script src="{{ URL::asset('/') }}js/bootstrap-switch.min.js"></script>
<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.8/summernote.css" rel="stylesheet">
<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.8/summernote.js"></script>
<script src="{{ URL::asset('/') }}js/summernote-zh-CN.js"></script>
<script src="{{ URL::asset('/') }}js/myNote.js"></script>


</body>
</html>