/**
 *
 * @param input_id input_file_id
 * @param img_id 需要显示出来的id
 * @param widths 需要的图片高度
 * @param callback 回调函数
 * @constructor
 */
window.imgcompress=function(input_id,img_id,widths,callback){
    var self=this;
    this.inputs=input_id;
    this.images=img_id;
    this.widths=widths;
    this.callbacks=callback;

    this.image_reg=/(gif|jpg|jpeg|png|bmp|BMP|GIF|JPG|PNG)$/;

    this.init=function () {
        var input=document.querySelector(self.inputs);
        if (typeof(FileReader) === 'undefined') {
            console.error('该浏览器不支持此操作上传！请更换浏览器或者其他解决方案！');
        }
        input.removeEventListener('onchange',self.my_change,false);
        input.addEventListener('change', self.my_change, false);
    };
    this.my_change=function () {
        var file=this.files[0];
        //var file=input.files[0];
        var file_type=file.type;console.log(file_type);
        if(!self.image_reg.test(file_type)){
            console.info("上传的图片的格式不对");
            return;
        }
        var reader=new FileReader(file);
        reader.readAsDataURL(file);
        reader.onload=function(){
            var canvas=document.createElement("canvas"),
                ctx=canvas.getContext("2d"),image_src,
                image=new Image();
            image.src=this.result;
            image.onload=function(){
                canvas.width = self.widths;
                canvas.height = self.widths * (image.height / image.width);
                ctx.drawImage(image, 0, 0, self.widths, canvas.height);
                image_src=canvas.toDataURL('image/png',0.8);
                document.querySelector(self.images).setAttribute('src',image_src);
                if(self.callbacks){
                    self.callbacks(image_src);
                }
            }
        }
    };
    this.init();
};

/*

function callback(src){
    p(Math.random());
}
var input_val=300;
$(function(){
    $("#img_with").blur(function () {
        input_val=parseInt($(this).val());
    });
    $("#press").click(function(){
        input_val=parseInt($("#img_with").val());

        $("#press_input").click();
    });
    $("#press_input").click(function(){
        $("#press_input").unbind('click');
        console.log(input_val);
        imgcompress('#press_input','#img',input_val,callback);
    });
});
function  p(s) {
    console.log(s);
}*/
