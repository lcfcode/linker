$(function () {
    $("#upload").off('click').on("click", (function () {
        var file = $("#upload_file")[0].files;

        if (file.length === 0) {
            alert("请选择要上传的文件！");
            return;
        }
        var fd = new FormData();
        fd.append('avatar', file[0]);
        console.log(file[0].length);
        $.ajax({
            url: "/upload?filename=" + file[0].name,
            type: "POST",
            //数据不需要编码
            contentType: false,
            //数据对象不需要转换成键值对格式
            processData: false,
            dataType: "json",
            data: fd,
            beforeSend: function () {
                $(".progress").show();
            },
            success: function (data) {
                console.log(data)
            },
            xhr: function () {
                var xhr = new XMLHttpRequest();
                //获取文件上传进度
                xhr.upload.onprogress = function (e) {
                    //e.lengthComputable表示当前的进度是否可以计算，返回布尔值
                    if (e.lengthComputable) {
                        //e.loaded 表示下载了多少数据，e.total表示数据总量
                        var percentComplete = Math.ceil((e.loaded / e.total) * 100);
                        $("#progress").css({"width": percentComplete + "%"}).html(percentComplete + "%");
                    }
                }
                xhr.upload.onload = function () {
                    // $("#progress").removeClass('progress-bar progress-bar-striped').addClass('progress-bar progress-bar-success');
                }
                return xhr;
            },
            error: function (e) {
                console.log(e);
            },
            complete: function (XMLHttpRequest, status) {
                $(".progress").hide();
            }
        });
    }));
});