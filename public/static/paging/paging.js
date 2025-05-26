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


function getPaging(totalCount, showBtn, dom, pageSizes) {
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
    var pageSize = GetQueryString("list_num");
    if (!pageSize) {
        pageSize = pageSizes;
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

function replaceParamVal(url, paramName, replaceWith) {
    var oUrl = url.toString();
    var re = eval('/(' + paramName + '=)([^&]*)/gi');
    return oUrl.replace(re, paramName + '=' + replaceWith);
}

