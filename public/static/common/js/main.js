/**
 * 常用的js方法
 */

/* ajax 请求 */
function _ajax_post(url, data, success_fun,error_fun) {
    $.ajax({
        url: url,
        type: 'post',
        async: true,
        cache: false,
        data: data,
        dataType: 'json',
        success: function (ret) {
            if(success_fun){
                success_fun(ret);
            }
        },
        error: function (e) {
            if(error_fun){
                error_fun(e);
            }
        },
        timeout:function(e){
            if(error_fun){
                error_fun(e);
            }
        }
    });
}
function _my_ajax(url,data,success_fun,error_fun,method) {
    if(!method){
        method='post';
    }
    $.ajax({
        url: url,
        type: method,
        async: true,
        cache: false,
        data: data,
        dataType: 'json',
        success: function (ret) {
            if(success_fun){
                success_fun(ret);
            }
        },
        error: function (e) {
            if(error_fun){
                error_fun(e);
            }
        },
        timeout:function(e){
            if(error_fun){
                error_fun(e);
            }
        }
    });
}
/* 跳转请求*/
function _my_href(url) {
    window.location.href=url;
}
/* 刷新 */
function _reload(){
    window.location.reload();
}
/* 命令打印 */
function p(ret) {
    console.log(ret);
}
/*新窗口方式打开*/
function open(url){
    window.open('http://'+window.location.host+url);
}
/*判断是否是手机*/
function _is_phone(){
    if(/AppleWebKit.*Mobile/i.test(navigator.userAgent) || (/MIDP|SymbianOS|NOKIA|SAMSUNG|LG|NEC|TCL|Alcatel|BIRD|DBTEL|Dopod|PHILIPS|HAIER|LENOVO|MOT-|Nokia|SonyEricsson|SIE-|Amoi|ZTE/.test(navigator.userAgent))){
        return true;
    }
    return false;
}

function check_phone(s){
    return /^1[34578]\d{9}$/.test(s);
}
function check_usr_id(s){
    return /^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$|^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/.test(s);
}
function check_email(s) {
    return /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(s);
}
function check_empty(s) {
    if(s.length==0){return true}else{return false}
}