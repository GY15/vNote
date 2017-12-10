    $(document).ready(function () {
        getNote(0);
        part3_show();
    })
function getNote(bookid) {
    hideBookChoose();
    $('.curbookname').html('全部笔记');
    $('.curbookid').html('0');
    $.ajax({
        type: 'POST',
        url: '/Ajax/allnotes',
        dataType: 'json',
        data: { bookid : bookid},
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        success: function(data){
            if(data.success==1){
                $('.noteList').empty();
                $('.noteList').append( data.notes);//这里根据你数据库字段不同而不同
                initNoteList();
                if($(".noteSummary").length!=0) {
                    openNote(data.noteid);
                    $(".noteSummary").eq(0).css({"border": "2px solid  dodgerblue",
                        "padding-top":"13px",
                        "padding-right": "0px",
                        "padding-left": "0px"});
//                        part3_show();
                }
                $(".noteSummary").click(function(){
                    var id = $(this).find('.noteidSummary').html();
                    openNote(id);
                    hideBookChoose();
                });
            }else {
//                    alert(data.msg[0])
            }
        },
        error: function(xhr, type){
//                alert("发生了未知的错误");
        }

    });
}
function openNote(id) {
    $.ajax({
        type: 'POST',
        url: '/Ajax/open_note',
        data: { noteid : id},
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        success: function(data){
            $('#thisNoteId').html(data.noteid);
            $('#theTitle').val(data.noteTitle);
            $('#theBook').html(data.bookname);
            $('#theTag').val(data.tag);
            $('.mynote').html(data.content);
            if(data.visible==0) {
                $('#toggle-state-switch input').bootstrapSwitch('state',false);
            }else{
                $('#toggle-state-switch input').bootstrapSwitch('state',true);
            }
        },
        error: function(xhr, type){
//                alert("发生了未知的错误");
        }
    });
};

function saveNote() {
    if(instantType==0){

    } else {
        var noteid = $('#thisNoteId').html();
        var title = $('#theTitle').val();
        var tag = $('#theTag').val();
        var note = $('.mynote').html();
        var bookid = 0;
        if (instantType == 1) {
            bookid = $('.selectpicker').val();
        }
        var state = $('#toggle-state-switch input').bootstrapSwitch('state')?1:0;
        $.ajax({
            type: 'POST',
            url: '/Ajax/updateNote',
            data: { noteid : noteid,title:title,tag:tag,note:note,bookid:bookid,state:state},
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function(data){
                $(".noteSummary").each(function(e){
                    if($(this).find('.noteidSummary').html()==noteid) {
                        $(this).find('.titleSummary').html(title);
                        $(this).find('.contentSummary').html(note);
                    }
                });
            },
            error: function(xhr, type){
//                    alert("发生了未知的错误");
            }
        });
    }
}

$('.addNote').on('click',function () {
    var curbook = $('.curbookid').html();
    $.ajax({
        type: 'POST',
        url: '/Ajax/addNewNote',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        success: function(data){
            $('#thisNoteId').append(data.noteid);
            var str ="<select id=\"chooseBook\"  data-live-search=\"true\"\n" +
                "                                        data-live-search-placeholder=\"查找笔记本\" data-style=\"btn-info\"\n" +
                "                                        data-actions-box=\"true\" data-size=\"10\" data-width=\"170px\"\n" +
                "                                        class=\"selectpicker show-tick \">\n" +data.books+
                "   </select>"
            $('#selectBlock').empty();
            $('#selectBlock').append(str);
            $('#theTitle').val(data.noteTitle);
            $('.selectpicker').selectpicker();
            $('.searchNote').val("");
            getNote(curbook);
            showBookChoose();
        },
        error: function(xhr, type){
//                alert("发生了未知的错误");
        }
    });
});
$('.allBook').on('click',function(){
    getAllBook()
});
function getAllBook() {
    $.ajax({
        type: 'POST',
        url: '/Ajax/getAllBook',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        success: function(data){
            if(data.success==1){
                $('.bookList').empty();
                $('.bookList').append(data.books);
                initBookList();
            }
        },
        error: function(xhr, type){
//                alert("发生了未知的错误");
        }

    });
}
$('.createBook').on('click',function(e){
    $.ajax({
        type: 'POST',
        url: '/Ajax/addBook',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        success: function(data){
            if(data.success==1){
                getAllBook();
            }
        },
        error: function(xhr, type){
//                alert("发生了未知的错误");
        }

    });
    e.stopPropagation();
})
function modifyBook() {
    var bookid = $('.modifyId').html();
    var title = $('#modifyTitle').val();
    var tag = $('#modifyTag').val();
    if(title==null||title==""){
        $('#errorModify').html("笔记本的名称不能为空");
        setTimeout(function () {
            $('#errorModify').html("")
        },2000);
    }else {
        $.ajax({
            type: 'POST',
            url: '/Ajax/updateBook',
            data: {bookid: bookid, title: title, tag: tag},
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function (data) {
                $(".bookSummary").each(function (e) {
                    if ($(this).find('.bookid').html() == bookid) {
                        $(this).find('.bookTitleSummary ').html(title);
                        $(this).find('.bookTag').html(tag);
                    }
                    $('#bookModify').modal('hide')
                });
            },
            error: function (xhr, type) {
//                    alert("发生了未知的错误");
            }
        });
    }
}
$('.searchNote').bind('input propertychange', function() {
    var bookid = $('.curbookid').html();
    var tag = $('.searchNote').val();
    $.ajax({
        type: 'POST',
        url: '/Ajax/getNote0fTag',
        data: {bookid: bookid, tag: tag},
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        success: function (data) {
            if(data.success==1){
                $('.noteList').empty();
                $('.noteList').append( data.notes);//这里根据你数据库字段不同而不同
                initNoteList();
                if($(".noteSummary").length!=0) {
                    var curNoteID = $('#thisNoteId').html();
                    $(".noteSummary").each(function (e) {
                        if ($(this).find('.noteidSummary').html() == curNoteID) {
                            $(this).css({"border": "2px solid  dodgerblue",
                                "padding-top":"13px",
                                "padding-right": "0px",
                                "padding-left": "0px"});
                        }
                    });
                }
                $(".noteSummary").click(function(){
                    var id = $(this).find('.noteidSummary').html();
                    openNote(id);
                    hideBookChoose();
                });
            }else {
                alert(data.msg[0])
            }
        },
        error: function (xhr, type) {
//                alert("发生了未知的错误");
        }
    });
});

$('.searchBook').bind('input propertychange', function() {
    var tag = $('.searchBook').val();
    $.ajax({
        type: 'POST',
        url: '/Ajax/getBookOfTag',
        data: {tag: tag},
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        success: function (data) {
            $('.bookList').empty();
            $('.bookList').append(data.books);
            initBookList();
        },
        error: function (xhr, type) {
//                alert("发生了未知的错误");
        }
    });
});
$('.community').click(function () {
    searchNote("");
    getTop10();

});
function searchNote(message) {
    $.ajax({
        type: 'POST',
        url: '/Ajax/searchCommunity',
        data: {message: message},
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        success: function (data) {
            if(data.success==1){
                $(".noteListCommunity").empty();
                $(".noteListCommunity").append(data.notes);
                $(".errorMessage").hide();
            }else{
                $(".noteListCommunity").empty();
                $(".errorMessage").show();
            }
        },
        error: function (xhr, type) {
//                alert("发生了未知的错误");
        }
    });
}
function getTop10() {
    $.ajax({
        type: 'POST',
        url: '/Ajax/searchTop',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        success: function (data) {
            if(data.success==1){
                $(".noteListTop").empty();
                $(".noteListTop").append(data.notes);
                initCommunity();
            }else{
                alert("失败")
            }
        },
        error: function (xhr, type) {
//                alert("发生了未知的错误");
        }
    });
}
$(".btCommunity").on('click',function () {
    searchNote($(".inputCommunity").val());
    getTop10();
});

$('.logo').click(function () {
    $("#userModify").modal();
    $.ajax({
        type: 'POST',
        url: '/Ajax/getUserMessage',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        success: function(data){
            $('#emailModify').val(data.email);
            $('#nicknameModify').val(data.nickname);
            $('#passwordModify').val(data.password);
        },
        error: function(xhr, type){
            alert("发生了未知的错误");
        }
    });
})
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
            $("#userModify").modal("hide");
        },
        error: function(xhr, type){
            alert("发生了未知的错误");
        }
    });
}

$("#toggle-state-switch input").bootstrapSwitch({
        onText: "public",
        offText: "private",
        onColor: "success",
        offColor: "info",
        size:'small',
        labelText: "note"
});
var instantType = 0;
function showBookChoose() {
    $('.newNote').show();
    $('.oldNote').hide();
    instantType = 1;
}
function hideBookChoose() {
    $(".newNote").hide();
    $('.oldNote').show();
    instantType = 2;
}

$(".text-area").click(function (e) {
    showTextArea();
    $('#modifyImg').hide();
    e.stopPropagation();
});

function showTextArea() {
    $('.mynote').summernote({
            toolbar: [
                ['style', ['fontname', 'style']],
                ['font', ['bold', 'italic', 'underline', 'clear', 'strikethrough']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['insert', ['table', 'picture', 'fullscreen']]
            ]
            , placeholder: 'write here...'
            , height: 540
            // ,dialogsFade:true
            // ,dialogsInBody:true
            // ,disableDragAndDrop:true
            // ,focus :true
        }
    );
    $('.part3').removeClass('bg-blur');

    var part4 = $('.part4').width();
    if (part4 != 0) {
        part4_hide();
    }
    var part2 = $('.part2').width();
    if (part2 == 0) {
        part2_show();
    }
    var part3 = $('.part3').width();
    if (part3 == 0) {
        part3_show();
    }
};

$("body").click(function (e) {
    var markup = $('.mynote').summernote('code');
    $('.mynote').summernote('destroy');
    $('#modifyImg').show();
    saveNote();
});
function initNoteList() {

    $(".noteSummary").hover(function () {
        $(this).find('.shareSummary').fadeIn();
        $(this).find('.deleteSummary').fadeIn();
    }, function () {
        $(this).find('.shareSummary').fadeOut();
        $(this).find('.deleteSummary').fadeOut();
    });
    $(".noteSummary").click(function () {
        $(".noteSummary").each(function () {
            $(this).css("border", "0px solid lightskyblue");
            $(this).css("padding-top", "15px");
            $(this).css("padding-right", "2px");
            $(this).css("padding-left", "2px");
        });
        $(this).css("border", "2px solid  dodgerblue");
        $(this).css("padding-top", "13px");
        $(this).css("padding-right", "0px");
        $(this).css("padding-left", "0px");
    });

    $(".shareSummary").click(function (e) {
        e.stopPropagation()
    });
    $(".deleteSummary").click(function (e) {
        var id = $(this).parent().parent().find('.noteidSummary').html();

        $.ajax({
            type: 'POST',
            url: '/Ajax/deleteNote',
            dataType: 'json',
            data:{noteid : id},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function(data){
                if(data.success==1){
                   getNote($('.curbookid').html());
                }
            },
            error: function(xhr, type){
                alert("发生了未知的错误");
            }
        });
        e.stopPropagation()
    });
}

function initBookList() {
    $(".deletebook").click(function (e) {
        var id = $(this).parent().parent().parent().find('.bookid').html();
        $.ajax({
            type: 'POST',
            url: '/Ajax/deleteBook',
            dataType: 'json',
            data:{bookid : id},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function(data){
                if(data.success==1){
                    getAllBook();
                }
            },
            error: function(xhr, type){
                alert("发生了未知的错误");
            }
        });
        e.stopPropagation()
    });
    $(".editbook").click(function (e) {
        $('#bookModify').modal();
        var parent= $(this).parents().parents().parents();
        var theBookId = parent.find('.bookid').html();
        var title = parent.find('.bookTitleSummary').html();
        var tag =  parent.find('.bookTag').html();
        $('.modifyId').html(theBookId);
        $('#modifyTitle').val(title);
        $('#modifyTag').val(tag);
        e.stopPropagation()
    });
    $(".bookSummary").click(function (e) {
        var theBookId = $(this).find('.bookid').html();
        $('.searchNote').val("");
        getNote(theBookId);
        $('.curbookid').html(theBookId);
        $('.curbookname').html($(this).find('.bookTitleSummary').html())
        part2_show();
        part4_hide();
        part3_show();
        e.stopPropagation();
    })
}
function initCommunity() {
    $('.openDetail').click(function (e) {
        var element = $(this).parent().parent().parent();
        element.find(".contentCommunity").removeClass("max_line");
        element.find(".close1").toggle();
        $(this).parent(".open1").toggle();
        e.stopPropagation();
    })

    $('.closeDetail').click(function (e) {
        var element = $(this).parent().parent().parent();
        element.find(".contentCommunity").addClass("max_line");
        element.find(".open1").toggle();
        $(this).parent('.close1').toggle();
        e.stopPropagation();
    });
    $('.likeNote').click(function () {
        var element= $(this).parent().parent().parent();
        var id = element.find(".noteidCommunity").html();
        var score = element.find(".score").html();
        element.find(".score").html(parseInt(score)+1);
        $.ajax({
            type: 'POST',
            url: '/Ajax/likeNote',
            data: {noteid : id},
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function (data) {

            },
            error: function (xhr, type) {
//                alert("发生了未知的错误");
            }
        });
    })
}


function part2_hide() {
    var width = 20;
    var t = setInterval(function () {
        if (width > 0) {
            width = width - 1;
            $('.part2').css("width", width + "%");
        }
        else {
            $('.part2').css("width", "0");
            $('.part2').hide()
            clearInterval(t);
        }
    }, 8);
}

function part2_show() {
    var width = 0;
    $('.part2').show();
    var t = setInterval(function () {
        if (width < 20) {
            width = width + 1;
            $('.part2').css("width", width + "%");
        }
        else {
            $('.part2').css("width", "20%");
            clearInterval(t);
        }
    }, 8);
}

function part3_show() {
    var width = 0;
    $('.part3').removeClass('bg-blur')
    var part3 = $('.part3').width();
    if (part3 == 0) {
        var t = setInterval(function () {
            if (width < 67) {
                width = width + 2;
                $('.part3').css("width", width + "%");
                $('.part3').show();
            }
            else {
                $('.part3').css("width", "67%");
                clearInterval(t);
            }
        }, 1);
    }
    $('.part3').show();
}

function part3_hide() {
    var width = 68;
    var t = setInterval(function () {
        if (width > 0) {
            width = width - 2;
            $('.part3').css("width", width + "%");
        }
        else {
            $('.part3').css("width", 0);
            $('.part3').hide()
            clearInterval(t);
        }
    }, 1);
    $('.part3').hide();
}

function part4_hide() {
    var width = 26;
    var t = setInterval(function () {
        if (width > 0) {
            width = width - 1;
            $('.part4').css("width", width + "%");
        }
        else {
            $('.part4').css("width", "0%");
            $('.part4').hide()
            clearInterval(t);
        }
    }, 8);
}

function part4_show() {
    var width = 0;
    $('.part4').show();
    var t = setInterval(function () {
        if (width < 26) {
            width = width + 1;
            $('.part4').css("width", width + "%");
        }
        else {
            $('.part4').css("width", "26%");
            clearInterval(t);
        }
    }, 8);
}

function part5_hide() {
    var width = 87;
    var t = setInterval(function () {
        if (width > 0) {
            width = width - 5;
            $('.part5').css("width", width + "%");
        }
        else {
            $('.part5').css("width", "0%");
            $('.part5').hide()
            clearInterval(t);
        }
    }, 1);
}

function part5_show() {
    var width = 0;
    $('.part5').show();
    var t = setInterval(function () {
        if (width < 87) {
            width = width + 5;
            $('.part5').css("width", width + "%");
        }
        else {
            $('.part5').css("width", "87%");
            clearInterval(t);
        }
    }, 1);
}

$('.addNote').click(
    function (e) {

        $('.part3').removeClass('bg-blur');

        var part3 = $('.part3').width();
        if (part3 == 0) {
            part3_show();
        }

        var part4 = $('.part4').width();
        if (part4 != 0) {
            part4_hide();
        }

        var part2 = $('.part2').width();
        if (part2 == 0) {
            part2_show();
        }
        var part5 = $('.part5').width();
        if (part5 != 0) {
            part5_hide();
        }
        $(".newNote").show();
        $(".oldNote").hide();
        e.stopPropagation();
    }
);
$('.allNote').click(
    function () {
        if(instantType!=2){
            $('.searchNote').val("");
            getNote(0);
        }
        if(instantType==2&&$('.curbookid')!=0){
            getNote(0);
        }
        // $(".newNote").hide();
        // $(".oldNote").show();
        $('.part3').removeClass('bg-blur');

        var part3 = $('.part3').width();
        if(part3==0){
            part3_show()
        }

        var part4 = $('.part4').width();
        if (part4 != 0) {
            part4_hide();
        }

        var part2 = $('.part2').width();
        if (part2 == 0) {
            part2_show();
        }
        var part5 = $('.part5').width();
        if (part5 != 0) {
            part5_hide();
        }

    }
);

$('.community').click(function () {
    var part5 = $('.part5').width();
    if (part5 == 0) {
        part5_show();
    }
    var part2 = $('.part2').width();
    if (part2 != 0) {
        part2_hide();
    }
    var part3 = $('.part3').width();
    if (part3 != 0) {
        part3_hide();
    }

    var part4 = $('.part4').width();
    if (part4 != 0) {
        part4_hide();
    }
})

$('.allBook').click(
    function () {
        $('.searchBook').val("");
        var part4 = $('.part4').width();
        if (part4 == 0) {
            part4_show();
        }

        $('.part3').addClass('bg-blur');


        var part2 = $('.part2').width();
        if (part2 != 0) {
            part2_hide();
        }
        var part5 = $('.part5').width();
        if (part5 != 0) {
            part5_hide();
        }
    }
);