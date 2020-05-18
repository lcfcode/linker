<?php
/**
 * Created by PhpStorm.
 * User: LCF
 * Date: 2019/3/7
 * Time: 15:52
 */

/**
 * PHP用CURL伪造IP和来源
IT生涯 2019-03-06 18:55:06
今天群里一个朋友在问这个问题。

查了下，CURL确实很强悍的可以伪造IP和来源。

1.php 请求 2.php 。
 *
 */
while (1){
    print_r(httpPost('http://localhost:8090',[]));
}

function httpPost($url, $param, $timeOut = 5, $connectTimeOut = 5)
{
    $oCurl = curl_init();
    if (stripos($url, "http://") !== FALSE || stripos($url, "https://") !== FALSE) {
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
    }
    if (is_string($param)) {
        $strPOST = $param;
    } else {
        $aPOST = array();
        foreach ($param as $key => $val) {
            $aPOST [] = $key . "=" . urlencode($val);
        }
        $strPOST = join("&", $aPOST);
    }
    $ip=mt_rand(1,253).'.'.mt_rand(1,253).'.'.mt_rand(1,253).'.'.mt_rand(1,253);
    curl_setopt($oCurl, CURLOPT_URL, $url);
    curl_setopt($oCurl, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:'.$ip, 'CLIENT-IP:'.$ip));
    curl_setopt($oCurl, CURLOPT_REFERER, "http://{$ip}/ ");
    curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($oCurl, CURLOPT_POST, true);
    curl_setopt($oCurl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($oCurl, CURLOPT_POSTFIELDS, $strPOST);
    curl_setopt($oCurl, CURLOPT_TIMEOUT, $timeOut);
    curl_setopt($oCurl, CURLOPT_CONNECTTIMEOUT, $connectTimeOut);
    $sContent = curl_exec($oCurl);
    $aStatus = curl_getinfo($oCurl);
    $error = curl_error($oCurl);
    curl_close($oCurl);
    if (intval($aStatus ["http_code"]) == 200) {
        return ['status' => true, 'content' => $sContent, 'code' => 200,];
    }
    return ['status' => false, 'content' => json_encode(["error" => $error, "url" => $url]), 'code' => $aStatus ["http_code"],];
}