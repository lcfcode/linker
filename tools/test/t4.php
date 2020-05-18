<?php
/**
 * Created by PhpStorm.
 * User: LCF
 * Date: 2018/11/27
 * Time: 13:11
 */


$result=http_get('http://www.gzu.edu.cn/635/list.htm');
var_dump($result);
if($result){
    $html=utf($result);
    print_r($html);
//    $pattern = '|<a[^>]*>(.*)</a>|isU';
//    preg_match_all($pattern, $html, $matches);
    $reg1='/<a href=\"(.*?)\".*?>(.*?)<\/a>/i';//匹配所有A标签

    preg_match_all($reg1,$html,$aarray);
//这个$aarray 你可以打印一下看下你具体的业务需要哪个数组
//这个$aarray 是整个抓取的核心

    $reg2="/href=\"([^\"]+)/";//获取href中的值

    $arr = array();

    for($i=1;$i<=3;$i++){
//这里讲一下我抓取的是前三个所以只需要 1=< i <=3就可以了
//如果想取出所有需要将for改为
//for($i=0;$i<count($aarray[0][$i]);$i++)

        preg_match_all($reg2,$aarray[0][$i],$hrefarray);

        $reg3="/>(.*)<\/a>/";//a标签中的内容

        preg_match_all($reg3,$aarray[0][$i],$acontent);

        $arr[$i]['title'] = $acontent[1][0];

        $arr[$i]['url'] = $hrefarray[1][0];

    }
    $data = array();

    foreach ($arr as $key=>$val){

        $data[] = $val;

    }
    print_r($data);
}

//var_dump($result);

function http_get($url, $timeOut = 5)
{
    $oCurl = curl_init();
    if (stripos($url, "http://") !== FALSE || stripos($url, "https://") !== FALSE) {
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
    }
    //设置超时
    curl_setopt($oCurl, CURLOPT_TIMEOUT, $timeOut);
    curl_setopt($oCurl, CURLOPT_URL, $url);
    curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
    $sContent = curl_exec($oCurl);
    $aStatus = curl_getinfo($oCurl);
    curl_close($oCurl);
    if (intval($aStatus ["http_code"]) == 200) {
        return $sContent;
    } else {
        return null;
    }
}
function utf($html){
    $coding = mb_detect_encoding($html);
    if ($coding != "UTF-8" || !mb_check_encoding($html, "UTF-8"))
        $html = mb_convert_encoding($html, 'utf-8', 'GBK,UTF-8,ASCII');
    return $html;
}