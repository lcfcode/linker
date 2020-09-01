<?php
/**
 * @link https://gitee.com/lcfcode/linker
 * @link https://github.com/lcfcode/linker
 */

namespace app\demo\utils;

use swap\utils\Helper;

class Paging
{
    private $page = 'page'; //分页页码参数
    private $listNum = 'list_num'; //分页数量参数
    private $url = ''; //当前链接URL
    private $listNumSelect = [15, 20, 30, 50];//页面显示可控制的数量
    private $parameter;//参数
    private $pageNow; //当前页
    private $countTotal;//页面总数据
    private $showBtn;//页面显示页数的按钮
    private $pageSize;//每页显示的数量
    private $firstRow; // 起始行数

    /**
     * @param int $totalRows
     * @param int $listRows
     * @param int $showBtn
     * @param string $url
     * @param array $parameter
     * @return Paging
     * @author LCF
     * @date
     */
    public static function instance($totalRows, $listRows = 20, $showBtn = 10, $url = '', $parameter = [])
    {
        return new self($totalRows, $listRows, $showBtn, $url, $parameter);
    }

    /**
     * Paging constructor.
     * @param int $totalRows 设置总记录数
     * @param int $listRows 默认每页显示行数
     * @param int $showBtn 默认显示的按钮数
     * @param string $url url
     * @param array $parameter 参数
     */
    public function __construct($totalRows, $listRows = 20, $showBtn = 10, $url = '', $parameter = [])
    {
        $this->countTotal = $totalRows;
        $param = empty($parameter) ? $_GET : $parameter;
        $param[$this->listNum] = isset($param[$this->listNum]) ? $param[$this->listNum] : $listRows;
        if (intval($param[$this->listNum]) > 50) {
            $param[$this->listNum] = $listRows;
        }
        $this->pageSize = $param[$this->listNum];
        $this->parameter = $param;

        $page = isset($param[$this->page]) ? intval($param[$this->page]) : 1;
        $this->pageNow = $page > 0 ? $page : 1;

        $this->firstRow = $this->pageSize * ($this->pageNow - 1);

        if (empty($url)) {
            $requestUrl = $_SERVER['REQUEST_URI'];
            $index = strpos($requestUrl, '?');
            $url = $index > 0 ? substr($requestUrl, 0, $index) : $requestUrl;
        }
        $this->url = $url;
        $this->showBtn = $showBtn;
    }

    public function page()
    {
        $page = $this->pageNow;
        $showBtn = $this->showBtn;
        $pageCount = ceil($this->countTotal / $this->pageSize) > 1 ? ceil($this->countTotal / $this->pageSize) : 1;

        if ($this->countTotal <= $this->pageSize) {
            //不足一页就直接显示总记录
            return '<div class="self-paging">总记录（' . $this->countTotal . '）</div>';
        }

        if ($pageCount < $showBtn) {
            $pageB = 1;
            $pageE = $pageCount;
        } else {
            if ($page - $showBtn / 2 <= 0) {
                $pageB = 1;
                $pageE = $showBtn;
            } else {
                $pageB = ceil($page - ($showBtn / 2));
                $pageE = ceil(intval($page) + intval(($showBtn / 2)));
            }
        }
        $pageHref = '';
        for ($i = $pageB; $i <= $pageE; $i++) {
            if ($i > $pageCount) {
                break;
            }
            $nowClass = '';
            if ($page == $i) {
                $nowClass = " class='_now_page'";
            }
            $href = $this->purl($i);
            $pageHref .= '<a href="' . $href . '" ' . $nowClass . '>' . $i . '</a>';
        }

        $prevPage = intval($page) - 1;
        if ($prevPage >= 1) {
            $prev = '<a href="' . $this->purl($prevPage) . '">上一页</a>';
        } else {
            $prev = '<a href="javascript:;" class="cursor_default">上一页</a>';
        }
        $nextPage = intval($page) + 1;
        if ($nextPage > 0 && ($nextPage <= $pageCount)) {
            $next = '<a href="' . $this->purl($nextPage) . '">下一页</a>';
        } else {
            $next = '<a href="javascript:;" class="cursor_default">下一页</a>';
        }
        $listNumPg = $this->listNumPg();
        $selectPg = $this->selectPg();
        return '<div class="self-paging">总记录（' . $this->countTotal . '）&nbsp;' . $prev . $pageHref . $next . $listNumPg . $selectPg . $this->js() . '</div>';
    }

    private function js()
    {
        return '
<script>
    (function(){
        $("._select_page").change(function () {
            window.location.href = $(this).val();
        });
    })();
</script>
';
    }

    public function now()
    {
        return $this->firstRow;
    }

    public function size()
    {
        return $this->pageSize;
    }

    private function purl($page = 1, $flag = false)
    {
        $param = $this->parameter;
        $param[$this->page] = $page;
        if ($flag === true) {
            unset($param[$this->page]);
            $param[$this->listNum] = $page;
        }
        $urlStr = '';
        foreach ($param as $key => $value) {
            $urlStr .= '&' . $key . '=' . $value;
        }
        $urlStr = trim($urlStr, '&');
        return $this->url . '?' . $urlStr;
    }

    private function listNumPg()
    {
        $listNum = $this->parameter[$this->listNum];
        $listNumOp = '';
        foreach ($this->listNumSelect as $value) {
            $url = $this->purl($value, true);
            $selected = '';
            if (intval($listNum) == $value) {
                $selected = 'selected';
            }
            $listNumOp .= '<option ' . $selected . ' value="' . $url . '" >' . $value . '</option>';
        }
        return '&nbsp;<select class="_select_page">' . $listNumOp . '</select>条每页&nbsp;';
    }

    private function selectPg()
    {
        $pageCount = ceil($this->countTotal / $this->pageSize) > 1 ? ceil($this->countTotal / $this->pageSize) : 1;
        $selectOp = '';
        for ($i = 1; $i <= $pageCount; $i++) {
            if ($i > $pageCount) {
                break;
            }
            $selected = '';
            if (intval($this->pageNow) == $i) {
                $selected = 'selected';
            }
            $href = $this->purl($i);
            $selectOp .= '<option ' . $selected . ' value="' . $href . '">' . $i . '</option>';
        }
        return '&nbsp;跳转至:<select class="_select_page">' . $selectOp . '<select>页';
    }

    public function css()
    {
        return <<<CSS
/***分页样式**/
.self-paging {
    padding-bottom: 20px;
    margin-left: -2px;
    text-align: center;
}

.self-paging a {
    position: relative;
    padding: 4px 14px;
    line-height: 1.42857143;
    color: #333;
    text-decoration: none;
    background-color: #fff;
    border: 1px solid #EEEEEE;
    display: inline-block;
    box-sizing: border-box;
    vertical-align: middle;
    font-size: 14px;
    margin-left: -1px;
}

/*.self-paging a:nth-child(1),.self-paging a:nth-last-child(1)  {border-top-left-radius: 2px;border-bottom-left-radius: 2px;}*/
/*.self-paging a:last-child{border-top-right-radius: 4px;border-bottom-right-radius: 4px;}*/

.self-paging a:hover {
    background-color: #ddd;
    border-color: #ddd
}

.self-paging .cursor_default {
    cursor: default;
    background-color: #ddd;
    border-color: #ddd;
    color: #9b9b9b
}

.self-paging ._select_page {
    appearance: none;
    -moz-appearance: none;
    -webkit-appearance: none;
    background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAJ0lEQVQ4jWNgGAWDEyQwMDD8x4ETKDGEaM3YDCFZM7IhZGseBfQCANaYDvVK269hAAAAAElFTkSuQmCC') no-repeat scroll right center transparent;
    height: 30px;
    outline: none;
    border: 1px solid #EEEEEE;
    /*border-radius:2px;*/
    color: #555;
    display: inline-block;
    font-size: 14px;
    line-height: 1.42857;
    padding: 4px 17px 4px 6px;
    transition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s;
    box-sizing: border-box;
    vertical-align: middle;
}

.self-paging ._now_page, .self-paging ._now_page:hover {
    background-color: #CA4500;
    color: #fff;
    border: 1px solid #CA4500;
}
CSS;

    }
}
