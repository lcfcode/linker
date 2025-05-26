$(function () {
    $("#update_yzm").click(function(){
        $(this).attr('src','/admin/index/yzm?r='+Math.random());
    });
    $("#yzm").keydown(function(){
        if (event.keyCode == "13") {//keyCode=13是回车键
            $("#login").click();
        }
    });
    $("#login").click(function () {
        alert(100);

    });
    function cut_pic(url,x,y,w,h){
        url = url+'@'+x+'-'+y+'-'+w+'-'+h+'a';
        return url;
    }
});