/**
* 此文件是一个经常用到的js集合,依赖jquery调用是使用如 yz.isEmpty(a);
* @version:v1.0.0
* @lastModified:2012-5-17 
* @history:2012-3-17 
* @history:2012-07-05:增加全选反选 
**/
//命名空间
//var yz = {};
/*
yz.namespace = function(str){
	var arr = str.split("."),o=yz;
	for(i=(arr[0]=="yz")?1:0;i<arr.length;i++){
		o[arr[i]] = o[arr[i]] ||{};
		o = o[arr[i]];
	}
}
yz.namespace("regexp");
*/
var yz={
	regexp:{
		empty:function(s){if(s.length==0){return true}else{return false}},
        number:function(s){ return s - parseFloat(s) >=0 },
        integer:function(s){if(parseInt(s) == s && s>0) {return true;} else {return false;} },
        email:function(s) { return /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(s); },
        name:function(s) { return /^[a-zA-Z0-9]{4,10}$/.test(s); },
        pwd:function(s) { return /^[a-zA-Z0-9]{8,16}$/.test(s); },
        code:function(s) { return /^[a-zA-Z0-9]{4}$/.test(s); },
        qq:function(s) { return /^[1-9]{1}[0-9]{4,11}$/.test(s); }, 
        mobile:function(s) { return /^1[0-9]{10}$/.test(s); }, 
        url:function(s) { return /^http:\/\/(?:[\w-\.]{0,255})(?:(?:\/?[^\s]{0,255}){0,255})/g.test(s); },
        zipcode:function(s) { return /^[0-9]{6}$/.test(s); },
        isChinese:function(s) { return /.*[\u4e00-\u9fa5]+.*$/.test(s);},
        isPInt:function(s){ var g = /^[0-9]*[1-9][0-9]*$/;return g.test(s);},//验证正整数
        validate:function(s) {var reg = /^\d+(?=\.{0,1}\d+$|$)/;if(reg.test(s)) return true;return false ;
        },
	},
	
	changeTableBg:function(classN){
		//奇偶行变色
		$('.'+classN+' tr').removeClass('on');
		$('.'+classN+'>tbody>tr:odd').addClass('even');		
		$('.'+classN+' tr>td').mouseover(function(){
			if($(this).find('table').html()!=null){
				 $(this).parent().removeClass('on');
			}else{
				$(this).parent().addClass('on');
			}				
		});
		$('.'+classN+' tr>td').mouseout(function(){
			$(this).parent().removeClass('on');
		});
	},
	
	verify:function(arg){	
		$(".verify").each(function(){
			$(this).val('');
		})
		$(".verify").focus(function(){
			$(this).addClass('onInput');
		})
		$(".verify").blur(function(){
			$(this).removeClass('onInput');		
			var verify = $(this).attr('title').split(',');
			for(var i in verify){
				var obj = $(this).parent().find('b');
				switch(verify[i]){
					case "isEmpty":
						if(yz.regexp.empty($(this).val())==false){
							obj.html("不能为空！");
							obj.attr("class","verify_err");
							return false;
						}
					break;
					case "isEmail":
						 if(yz.regexp.email($(this).val())==false){
							obj.html("请输入正确的邮箱格式！");
							obj.attr("class","verify_err");
							return false;
						 }
					break;
					case "isNumber":					 
						if(yz.regexp.number($(this).val())==false){
							obj.attr("class","verify_err");
							obj.html("请输入数字！");
							return ;
						}
					break;	
				}			
			}
			obj.attr("class","verify_ok");	
			obj.html('');	
		});
		$('input:submit').click(function(){
			var flag = true;
			$(".verify").each(function(){	
				var obj = $(this).parent().find('b');
				if(obj.attr('class')!='verify_ok'){
					flag = false;	
					$(this).focus();					
				}
			})
			if(flag){			
				return true;
			}else{
				alert("请正确填写表单信息!");
				return false;
			}
		})
		if(arg){
			var t=arg.ajax;		
			for(var i in t){					
				$('#'+t[i].id).blur(function(i){	
					return function(){
						var obj = $('#'+t[i].id).parent().find('b');
						obj.attr("class","load");
						obj.html("验证中，请稍等！");
						var val = $('#'+t[i].id).val();				
						$.ajax({
							type: "GET",
							url:t[i].url+val,
							success: function(msg){			
								if(msg=='N'){
									obj.attr("class","verify_err");
									obj.html(t[i].errMsg);
								}else{
									obj.attr("class","verify_ok");
									obj.html("");
								}
							}
						});	
                    };
				}(i))					
			}			
		}
	},
	
	GetQueryString:function(name) {
		var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)","i");	 
		var url = window.location;
		var r = url.search.substr(1).match(reg); 
		if (r!=null){
		   return unescape(r[2])
		}else{
			return null;
		};
	},
	
	/**
	* @desc:通过遍历判断一个字符是否在一个数组中。返回字符串
	* @author:yuanhuan
	* @param str：传入的字符串，arr:数组  参数名
	* @return string 参数的值
	**/	
	isInArr:function(str,arr){	
		for(i in arr){
			if(arr[i]==str){
				return str;
				break;
			}
		}	
		return false;
	},
	
	/**
	* @加载完成后执行代码类似jquery ready
	* @author:yuanhuan
	* @time 2012-3-21 9:35:39
	**/	
	addLoadListener:function(fn){
		if (typeof window.addEventListener != 'undefined'){
			window.addEventListener('load', fn, false);
		}else if (typeof document.addEventListener != 'undefined'){
			document.addEventListener('load', fn, false);
		}else if (typeof window.attachEvent != 'undefined'){
			window.attachEvent('onload', fn);
		}else{
			var oldfn = window.onload;
			if (typeof window.onload != 'function')
			{
				window.onload = fn;
			}
			else
			{
				window.onload = function(){
					oldfn();
				}
			}
		}
	},
	
	/**
	* @绑定事件
	* @time 2012-3-21 9:35:39
	**/
	addEventListener:function(target,eventType,functionRef,capture){
		if(typeof target.addEventListener !="undefined")
		{
			target.addEventListener(eventType,functionRef,capture);
		}
		else if(typeof target.attachEvent !="undefined")
		{
			target.attachEvent("on"+eventType,functionRef);
		}
		else
		{
			eventType="on"+eventType;
			if(typeof target[eventType] =="function")
			{
				var old=target[eventType];
				target[eventType]=function()
				{
					old();
					return functionRef();
				};
			}
			else
			{
				target[eventType]=functionRef;
			}
		}
	},
	
	/**
	* @移除事件
	* @time 2012-3-21 9:35:39
	**/
	removeEventSimple:function(obj,evt,fn){
		if(obj.removeEventListener){
			obj.removeEventListener(evt,fn,false);
		}else if(obj.detachEvent){
			obj.detachEvent('on'+evt,fn);
		};
	},
	
	//判断变量是否空值//
	isEmpty:function(v){
		switch (typeof v){
			case 'undefined' : return true;
			case 'string' : if(trim(v).length == 0) return true; break;
			case 'boolean' : if(!v) return true; break;
			case 'number' : if(0 === v) return true; break;
			case 'object' :
				if(null === v) return true;
				if(undefined !== v.length && v.length==0) return true;
				for(var k in v){return false;} return true;
				break;
		}
		return false;
	},
	
	/**
	*功能：将浮点数四舍五入，取小数点后2位，如果不足2位则补0,这个函数返回的是字符串的格式
	*用法：changeTwoDecimal(3.1415926) 返回 3.14
	*changeTwoDecimal(3.1) 返回 3.10
	**/
	changeTwoDecimal_f:function (x){
		var f_x = parseFloat(x);
		if (isNaN(f_x)){
			alert('请输入数字');
			return false;
		}
		var f_x = Math.round(x*100)/100;
		var s_x = f_x.toString();
		var pos_decimal = s_x.indexOf('.');
		if (pos_decimal < 0){
			pos_decimal = s_x.length;
			s_x += '.';
		}
		while (s_x.length <= pos_decimal + 2){
			s_x += '0';
		}
		return s_x;
	},
	
	//模拟php去空白//
	phpTrim:function(s,key){ 		
		s = (""+s).replace(/^\s*/g, "");
		if(key){
			var lengthK = key.length;
			if(s.substr(s.length-1,lengthK)==key){
			s = s.substr(0,s.length-lengthK);
			}
			if(s.substr(0,lengthK)==key){
				s = s.substr(lengthK,s.length);
			}
		}		
		return s;
	},

	/**
	*功能：重新载入js
	*参数：id 要重载的js标签id，重新载入的url
	**/
	reloadJS:function(id,newJS){
		var oldjs = null;
		var oldjs = document.getElementById(id);
		if(oldjs) oldjs.parentNode.removeChild(oldjs);
		var scriptObj = document.createElement("script");
		scriptObj.src = newJS;
		scriptObj.type = "text/javascript";
		scriptObj.id   = id;
		document.getElementsByTagName("head")[0].appendChild(scriptObj);
	},
	
	//json字符转换为json
	toJson:function(str){
		return eval('('+str+')');
	},
	
	//全选反选 
	selectAll:function(id){
		$('#'+id +' .chooseAll').click(function(){			 
			if($(this).attr('checked')=='checked'){
				$('#'+id+" .allC").attr('checked','checked');
			}else{
				$('#'+id+" .allC").attr('checked',false);
			}					
		});
	},
	/*
	*浮点型加法运算，用来得到精确的乘法结果 
	*说明：javascript的乘法结果会有误差，在两个浮点数相乘的时候会比较明显。这个函数返回较为精确的乘法结果。 
	*FloatAdd(arg1,arg2) 
	*返回值：arg1加上arg2的精确结果
	*/
	FloatAdd:function (arg1, arg2) {
	    var r1, r2, m;
	    try {
	        r1 = arg1.toString().split(".")[1].length;
	    } catch(e) {
	        r1 = 0;
	    }
	    try {
	        r2 = arg2.toString().split(".")[1].length;
	    } catch(e) {
	        r2 = 0;
	    }
	    m = Math.pow(10, Math.max(r1, r2));
	    return (arg1 * m + arg2 * m) / m;
	},
	/*
	*浮点型数据减法运算，用来得到精确的乘法结果 
	*说明：javascript的乘法结果会有误差，在两个浮点数相乘的时候会比较明显。这个函数返回较为精确的乘法结果。 
	*FloatSub(arg1,arg2) 
	*返回值：arg1减去arg2的精确结果
	*/
	FloatSub:function(arg1, arg2) {
	    var r1, r2, m, n;
	    try {
	        r1 = arg1.toString().split(".")[1].length;
	    } catch(e) {
	        r1 = 0;
	    }
	    try {
	        r2 = arg2.toString().split(".")[1].length;
	    } catch(e) {
	        r2 = 0;
	    }
	    m = Math.pow(10, Math.max(r1, r2));
	    //动态控制精度长度
	    n = (r1 >= r2) ? r1: r2;
	    return ((arg1 * m - arg2 * m) / m).toFixed(n);
	},
	/*
	*乘法函数，用来得到精确的乘法结果 
	*说明：javascript的乘法结果会有误差，在两个浮点数相乘的时候会比较明显。这个函数返回较为精确的乘法结果。 
	*调用：accMul(arg1,arg2) 
	*返回值：arg1乘以arg2的精确结果 
	*/
	accMul:function(arg1,arg2) { 
		var m=0,s1=arg1.toString(),s2=arg2.toString(); 
		try{m+=s1.split(".")[1].length}catch(e){} 
		try{m+=s2.split(".")[1].length}catch(e){} 
		return Number(s1.replace(".",""))*Number(s2.replace(".",""))/Math.pow(10,m) 
	},
	/*
	*除法函数，用来得到精确的除法结果 
	*说明：javascript的除法结果会有误差，在两个浮点数相除的时候会比较明显。这个函数返回较为精确的除法结果。 
	*调用：accDiv(arg1,arg2) 
	*返回值：arg1除以arg2的精确结果 
	*/
	accDiv:function(arg1,arg2){ 
		var t1=0,t2=0,r1,r2; 
		try{t1=arg1.toString().split(".")[1].length}catch(e){} 
		try{t2=arg2.toString().split(".")[1].length}catch(e){} 
		with(Math){ 
		r1=Number(arg1.toString().replace(".","")) 
		r2=Number(arg2.toString().replace(".","")) 
		return (r1/r2)*pow(10,t2-t1); 
		}
	},
	bind:function(id,type,funTion) {
		document.getElementById(id).addEventListener(type, funcTion, false);
	},
	//获取参数
	GetQueryString:function (name) {
    var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
     var r = window.location.search.substr(1).match(reg);
     if(r!=null)return  unescape(r[2]); return null;

}
}







