	function GetQueryString (name) {
	    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)","i");  
	    var url = window.location;
	    var r = url.search.substr(1).match(reg); 
	    if(r==null){
	        url=url+'';
	        if(url.indexOf('?') > 0 ){
	            url = url.split('?');
	            return url[1].replace(name+'=');
	        }
	    }
	    if (r!=null){
	       return unescape(r[2])
	    }else{
	        return -1;
	    };
	}

	/* js分页函数 */
	//dataTable:分页表格id，
	//pagingZero：分页按钮区域id,
	//page:当前页，
	//pageSize：显示条数，
	//totalCount：总条数，
	//totalCount：分页按钮个数
	function getPageDh(page,pageSize,totalCount,showCount,dataTable,pagingZero,url){
		var $total_record_count = totalCount;
	    var $page = page;
	    var $pageSize = pageSize;
	    var $showCount = showCount;
	    $pageCount = Math.ceil($total_record_count/$pageSize)>1?Math.ceil($total_record_count/$pageSize):1;
	    $PrevPage  = $page-1;
	    if(url.indexOf("?") > 0) {
	    	if(url.indexOf("?page") > 0){
	    		url = url.substr(0,url.indexOf("?page")) + "?page=";
	    	}else{
	    		if(url.indexOf("&page") > 0){
	    			url = url.substr(0,url.indexOf("&page")) + "&page=";
	    		}else{
	    			url = url + "&page=";
	    		}
	    	}
	    }else{
	    	url = url + "?page=";
	    }

	    if ($PrevPage>=1){
	        $Prev = "<a  href='"+url+$PrevPage+"' class='bg1 pagePrev'>上一页</a>";
	    }else{
	        $Prev = "<a  href='javascript:;' class='bg1 pagePrev gray cursor_default'>上一页</a>";
	    }

	    $nextPage = Number($page)+1;

	    if ($nextPage>0 && ($nextPage<=$pageCount)){        
	        $Next = "<a class='bg1  pageNext' page='"+$nextPage+"' href='"+url+$nextPage+"' class='next'>下一页</a>";
	    }else{
	        $Next = "<a class='bg1  pageNext gray cursor_default' page='0' href='javascript:;' class='next'>下一页</a>";
	    }

	    if($pageCount<$showCount){
	        $pageB=1;
	        $pageE=$pageCount;
	    }else if($pageCount>=$showCount){
	        if($page-$showCount/2 <=0){
	            $pageB = 1;
	            $pageE = $showCount;
	        }else{
	            $pageB = Math.ceil($page-($showCount/2));
	            $pageE = Math.ceil(Number($page)+Number(($showCount/2))-1);
	        }
	    }

	    $pageDH = '';
	    for($i=$pageB;$i<=$pageE;$i++){
	        if($i>$pageCount){
	            break;
	        }
	        if($page==$i){
	            $pageDH += "<a href='"+url+$i+"' class='nowPage'>"+$i+"</a>";
			}else{
	            $pageDH += "<a href='"+url+$i+"' >"+$i+"</a>";
	        }           
	    }

		if(page>$pageCount){
			alert("error!");
			return false;
		}
	    if($total_record_count>0){
	        $("#"+pagingZero).html($Prev+$pageDH+$Next);
	        var page_index = GetQueryString("page");
	        if(page_index > 0){
	        	$("#changePage").val(url + page_index);
	        }else{
	        	$("#changePage").val(url + 1);
	        }
	        $("#"+pagingZero+" a").unbind();
	    }
	}

	var loading = {
		show:function(selector){
			var obj = [];
			obj.push('<div class="spinner" id="css3loading">');
				obj.push('<div class="rect1"></div>');
				obj.push('<div class="rect2"></div>');
				obj.push('<div class="rect3"></div>');
				obj.push('<div class="rect4"></div>');
				obj.push('<div class="rect5"></div>');
			obj.push('</div>');
			$(selector).html(obj.join(''));
		},
		hide:function(){
			$("#css3loading").remove();
		}
	}


$(function(){
	/* 模态框begin 
	//配置
	$("#myModal").modal({
		keyboard:true
	});
	*/
    $("#myModal").on("hidden.bs.modal", function() {
    	$(this).removeData("bs.modal");
    	loading.show(".yh-model .modal-content");
    });

    /* 模态框end */
})

