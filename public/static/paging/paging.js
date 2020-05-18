function GetQueryString(name) {
    var param = window.location.search;
    param = param.substr(1);
    var item,
        parameter = [];
    var paramArr = param.split('&');
    for (var i = 0, len = paramArr.length; i < len; i++) {
        item = paramArr[i].split('=');
        parameter[item[0]] = item[1];
    }
    if (parameter[name]) {
        return parameter[name];
    }
    return null;
}


function getPaging(totalCount, showBtn, dom, pageSize = 20) {
    var url = window.location.href;
    var pathname = window.location.pathname;
    var param = '';
    // param = window.location.search;
    if (url.indexOf('?') > -1) {
        param = url.substring(url.indexOf('?') + 1);
    }
    var page = GetQueryString("page");
    if (!page) {
        page = 1;
    }
    pageSize = GetQueryString("list_num");
    if (!pageSize) {
        pageSize = 15;
    }

    var item,
        listNumParam = '',
        pageParam = '',
        listNum = [15, 20, 30, 50];
    if (param) {
        var paramArr = param.split('&');
        for (var i = 0, len = paramArr.length; i < len; i++) {
            item = paramArr[i].split('=');
            if ('page' != item[0]) {
                pageParam += item[0] + '=' + item[1] + '&';
            }
            if ('list_num' != item[0] && 'page' != item[0]) {
                listNumParam += item[0] + '=' + item[1] + '&';
            }
        }
    } else {
        pageParam += 'list_num=' + pageSize + '&';
    }
    var $pageDH = '',
        pageUrl = pathname + '?' + pageParam + 'page=',
        listNumUrl = pathname + '?' + listNumParam + 'list_num=';

    var pageCount = Math.ceil(totalCount / pageSize) > 1 ? Math.ceil(totalCount / pageSize) : 1;
    var pageB, pageE;
    if (pageCount < showBtn) {
        pageB = 1;
        pageE = pageCount;
    } else if (pageCount >= showBtn) {
        if (page - showBtn / 2 <= 0) {
            pageB = 1;
            pageE = showBtn;
        } else {
            pageB = Math.ceil(page - (showBtn / 2));
            pageE = Math.ceil(Number(page) + Number((showBtn / 2)));
        }
    }
    for (var $i = pageB; $i <= pageE; $i++) {
        if ($i > pageCount) {
            break;
        }
        if (page == $i) {
            $pageDH += '<a href="' + pageUrl + $i + '" class="_now_page">' + $i + '</a>';
        } else {
            $pageDH += '<a href="' + pageUrl + $i + '">' + $i + '</a>';
        }
    }

    var numSelected, selectedPg, numOption = '';
    for (var j in listNum) {
        numSelected = '';
        if (pageSize == listNum[j]) {
            numSelected = 'selected';
        }
        numOption += '<option value="' + listNumUrl + listNum[j] + '" ' + numSelected + '>' + listNum[j] + '</option>';
    }
    var numSelect = '<select class="_select_page">' + numOption + '</select>条每页';

    var selectPg = '<span>跳转至:</span><select class="_select_page">';
    for ($i = 1; $i <= pageCount; $i++) {
        selectedPg = '';
        if (page == $i) {
            selectedPg = 'selected';
        }
        selectPg += '<option value="' + pageUrl + $i + '" ' + selectedPg + '>' + $i + '</option>';
    }
    selectPg += '<select>页';
    var $prevPage = Number(page) - 1, $prev;
    if ($prevPage >= 1) {
        $prev = '<a href="' + pageUrl + $prevPage + '">上一页</a>';
    } else {
        $prev = '<a href="javascript:void(0);" class="cursor_default">上一页</a>';
    }
    var $nextPage = Number(page) + 1, $next;
    if ($nextPage > 0 && ($nextPage <= pageCount)) {
        $next = '<a href="' + pageUrl + $nextPage + '">下一页</a>';
    } else {
        $next = '<a href="javascript:void(0);" class="cursor_default">下一页</a>';
    }

    var html;
    if (totalCount <= pageSize) {
        //不足一页就直接显示总记录
        html = '<div class="self-paging">总记录（' + totalCount + '）</div>';
    } else {
        html = '<div class="self-paging">总记录（' + totalCount + '）&nbsp;' + $prev + $pageDH + $next + numSelect + selectPg + '</div>';
    }
    $("#" + dom).html(html);
    $("._select_page").change(function () {
        window.location.href = $(this).val();
    });
}

/* js分页函数 */
//dataTable:分页表格id，
//pagingZero：分页按钮区域id,
//page:当前页，
//pageSize：显示条数，
//totalCount：总条数，
//totalCount：分页按钮个数
function getPageDh(page, pageSize, totalCount, showCount, dataTable, pagingZero, url) {
    var $total_record_count = totalCount;
    //var $total_record_count = total_record_count;
    var $page = page;
    var $pageSize = pageSize;
    var $showCount = showCount;
    $pageCount = Math.ceil($total_record_count / $pageSize) > 1 ? Math.ceil($total_record_count / $pageSize) : 1;
    $PrevPage = $page - 1;
    url = get_url(url, $pageSize);
    var new_add = '<select class="pageNum" id="_list_num">';
    if ($pageSize == 50) {
        new_add +=
            '<option value="' + url + '10" >10</option>' +
            '<option value="' + url + '30">30</option>' +
            '<option value="' + url + '50" selected="selected">50</option>';

    } else if ($pageSize == 30) {
        new_add += '<option value="' + url + '10" >10</option>' +
            '<option value="' + url + '30" selected="selected">30</option>' +
            '<option value="' + url + '50">50</option>';
    } else {
        new_add += '<option value="' + url + '10" selected="selected">10</option>' +
            '<option value="' + url + '30">30</option>' +
            '<option value="' + url + '50">50</option>';
    }
    new_add += '</select>条每页';
    url = url + $pageSize;
    console.log(url);
    if (url.indexOf("?") > 0) {
        var page_num;
        if ($pageSize >= 1 && $pageSize < 10) {
            page_num = 7;
        } else if ($pageSize >= 10 && $pageSize < 100) {
            page_num = 8;
        } else if ($pageSize >= 100 && $pageSize < 1000) {
            page_num = 9;
        } else {
            page_num = 10;
        }

        if (url.indexOf("?page") > 0) {
            url = url.substr(0, url.indexOf("?page")) + url.substr(url.indexOf("?page") + page_num) + "?page=";
        } else {
            if (url.indexOf("&page") > 0) {
                url = url.substr(0, url.indexOf("&page")) + url.substr(url.indexOf("&page") + page_num) + "&page=";
            } else {
                url = url + "&page=";
            }
        }
    } else {
        url = url + "?page=";
    }
    if ($PrevPage >= 1) {
        $Prev = "<a  href='" + url + $PrevPage + "' class='bg1 pagePrev'>上一页</a>";
    } else {
        $Prev = "<a  href='javascript:;' class='bg1 pagePrev gray cursor_default'>上一页</a>";
    }
    $nextPage = Number($page) + 1;
    if ($nextPage > 0 && ($nextPage <= $pageCount)) {
        $Next = "<a class='bg1  pageNext' page='" + $nextPage + "' href='" + url + $nextPage + "' class='next'>下一页</a>";
    } else {
        $Next = "<a class='bg1  pageNext gray cursor_default' page='0' href='javascript:;' class='next'>下一页</a>";
    }
    if ($pageCount < $showCount) {
        $pageB = 1;
        $pageE = $pageCount;
    } else if ($pageCount >= $showCount) {
        if ($page - $showCount / 2 <= 0) {
            $pageB = 1;
            $pageE = $showCount;
        } else {
            $pageB = Math.ceil($page - ($showCount / 2));
            $pageE = Math.ceil(Number($page) + Number(($showCount / 2)));
        }
    }
    $pageDH = '';
    for ($i = $pageB; $i <= $pageE; $i++) {
        if ($i > $pageCount) {
            break;
        }
        if ($page == $i) {
            $pageDH += "<a href='" + url + $i + "' class='nowPage'>" + $i + "</a>";
        } else {
            $pageDH += "<a href='" + url + $i + "' >" + $i + "</a>";
        }
    }
    $selectPg = "<span style='margin-left:20px;'>跳转至:</span><select id='changePage'>";
    for ($i = 1; $i <= $pageCount; $i++) {
        $selectPg += "<option value='" + url + $i + "'>" + $i + "</option>";
    }
    $selectPg += '<select>页';


    if ($total_record_count > 0) {
        $("#" + pagingZero).html("<label class='pr' style='margin-right:15px;'>共<span style='color:red;padding:0 5px;'>" + $total_record_count + "</span>条数据</label>" + $Prev + $pageDH + $Next + new_add + $selectPg);
        var page_index = GetQueryString("page");
        if (page_index > 0) {
            $("#changePage").val(url + page_index);
        } else {
            $("#changePage").val(url + 1);
        }
        $("#" + pagingZero + " a").unbind();
        $("#changePage").change(function () {
            var value = $(this).val();
            window.location.href = value;
        });
        $("#_list_num").change(function () {
            window.location.href = $(this).val();
        });
    }
}

function get_url(url, page_num) {
    var page_nums, page_num2;
    if (page_num >= 10 && page_num < 100) {
        page_nums = 12;
        page_num2 = 8;
    } else if (page_num > 100 && page_num < 1000) {
        page_nums = 13;
        page_num2 = 9;
    } else if (page_num > 1000 && page_num < 10000) {
        page_nums = 14;
        page_num2 = 10;
    } else {
        page_nums = 11;
        page_num2 = 7;
    }

    if (url.indexOf("?") > 0) {
        if (url.indexOf("?page") > 0) {
            if (url.substr(url.indexOf("?page") + page_num2) != '') {
                url = url.substr(0, url.indexOf("?page")) + '?' + url.substr(url.indexOf("?page") + page_num2 + 1);
            } else {
                url = url.substr(0, url.indexOf("?page")) + url.substr(url.indexOf("?page") + page_num2);
            }
        } else {
            if (url.indexOf("&page") > 0) {
                url = url.substr(0, url.indexOf("&page")) + url.substr(url.indexOf("&page") + page_num2);
            }
        }
    }

    if (url.indexOf("?") > 0) {
        if (url.indexOf("?list_num") > 0) {
            if (url.substr(url.indexOf("?list_num") + page_nums) != '') {
                url = url.substr(0, url.indexOf("?list_num")) + '?' + url.substr(url.indexOf("?list_num") + page_nums + 1) != '' + "&list_num=";
            } else {
                url = url.substr(0, url.indexOf("?list_num")) + '?list_num=';
            }
        } else {
            if (url.indexOf("&list_num") > 0) {
                url = url.substr(0, url.indexOf("&list_num")) + url.substr(url.indexOf("&list_num") + page_nums) + "&list_num=";
            } else {
                url = url + "&list_num=";
            }
        }
    } else {
        url = url + "?list_num=";
    }
    return url;
}

function replaceParamVal(url, paramName, replaceWith) {
    var oUrl = url.toString();
    var re = eval('/(' + paramName + '=)([^&]*)/gi');
    return oUrl.replace(re, paramName + '=' + replaceWith);
}

