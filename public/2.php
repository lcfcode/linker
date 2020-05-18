<?php
/**
 * Created by PhpStorm.
 * User: LCF
 * Date: 2019/3/7
 * Time: 15:52
 */

function getClientIp() {
    if (!empty($_SERVER["HTTP_CLIENT_IP"]))
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    else if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
        $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    else if (!empty($_SERVER["REMOTE_ADDR"]))
        $ip = $_SERVER["REMOTE_ADDR"];
    else
        $ip = "err";
    return $ip;
}
echo "IP: " . getClientIp() . "";
echo "referer: " . $_SERVER["HTTP_REFERER"];