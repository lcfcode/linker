<script src="/js/qrcode.js?r=20170505.001"></script>
<script>
    $(function () {
        var meet_id=$("#meet_id").val();
        var guest_id=$("#guest_id").val();
        var guest_introduce_id=$("#guest_introduce_id").val();
       $("#save").click(function () {
           window.location.href='/card/index/edit?meet_id='+meet_id+'&guest_introduce_id='+guest_introduce_id+'&guest_id='+guest_id;
       });
       $("#cancel").click(function () {
           window.location.href='/card/index/setguests?meet_id='+meet_id+'&sequence='+guest_introduce_id+'&guest_id='+guest_id;
       });
       var imgs_down=$("#imgs_down");
        $("#img_down").mouseover(function (e) {
            imgs_down.css('display','block');
        }).mouseout(function () { //当鼠标指针从元素上移开时
            imgs_down.css('display','none');
        }).mousemove(function (e) { //当鼠标指针从元素上移动时
            console.log(Math.random());
        });
        // imgs_down.click(function () {
        //     window.location.href='/card/create/downqr?guest_id='+guest_id;
        // });
        var qrcode = new QRCode(document.getElementById("bd_qr1"), {
            width : 130,//设置宽高
            height : 130
        });
        qrcode.makeCode($("#qr_x").val());
        setTimeout(function () {
            down_url=$("#bd_qr1").find('img').attr('src');
            $("#dow_qr2").attr('src',down_url).show();
            down_qrcode();
        },200);
        //下载二维码的  保存成功后再激活该函数
        function down_qrcode() {
            $("#imgs_down").click(function () {
                if(down_url===null){
                    console.error('down_url=null');
                    return false;
                }
                $(this).attr("href", down_url).attr("download",guest_id+'.png');
                // $(this).attr("download",guest_id+'.png');
                // $("#down").attr("href", down_url).attr("download",guest_id+'.png');
                // $(this).click();
                // window.location.href='/card/create/downqr?guest_id='+guest_id;
            });
        }
    });
</script>
