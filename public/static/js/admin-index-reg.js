$(function () {
    $("#update_yzm").click(function(){
        $(this).attr('src','/admin/index/yzm?r='+Math.random());
    });
    $("#yzm").keydown(function(){
        if (event.keyCode == "13") {//keyCode=13是回车键
            $("#reg").click();
        }
    });
    $("#reg").click(function () {
        alert(100);
    });
})