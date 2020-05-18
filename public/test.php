<?php

$cc = trigger_error('自定义错误',E_USER_ERROR);
//$cc = trigger_error('connectInfo function return data err!', E_USER_ERROR);

print_r($cc);die;

//print_r('a'^'b');

//die;
print_r(file_encrypt());

die;
echo date('Y-m-d H:i:s');
echo PHP_EOL;

function file_encrypt()
{
    $source = 'D:/1.uc';
    $dest = 'D:/203.mp3';

//    $string=file_get_contents($source);
//    $sssss='';
//    for($i = 0; $i < strlen($string); $i++){
//        $byte=ord($string[$i]);
//        $sssss.=$byte ^ 0xa3;
//        print_r(ord($string[$i]));
//        echo PHP_EOL;
//    }
//    return file_put_contents($dest, $sssss, true);
//die;
    $content = '';          // 处理后的字符串
    $index = 0;


//    $fps = fopen('D:/234.mp3', 'w');
    $fp = fopen($source, 'rb');
    while (!feof($fp)) {
        $tmp = fread($fp, 1);
//        for ($i=0;$i<strlen($tmp);$i++){
//            print_r($tmp[$i]);
//            echo PHP_EOL;
//        }
//        $tmps = unpack('H*', $tmp);
//        $byte = base_convert($tmps[1], 16, 2);
//        print_r($byte);
//        print_r(($tmp));
//        echo PHP_EOL;
//        print_r(unpack("C*",$tmp));echo PHP_EOL;
        $tt = unpack("C*", $tmp);
//        print_r(ord($tmp));echo PHP_EOL;
//        print_r($tmp);echo PHP_EOL;
//        $tmp = ord($tmp) ^ 0xa3;
        if ($tt) {
            $tmp = $tt[1] ^ 0xa3;
//        fwrite($fps, $tmp ^ '0xa3');
            $content .= $tmp;
        }

//        $index++;
    }
//    fclose($fps);
    fclose($fp);
    echo PHP_EOL;
    echo PHP_EOL;
//    print_r($content);

    return file_put_contents($dest, $content, true);

}


//print_r(getIp());

function getIp()
{
    $defaultIp = '0.0.0.0';
    if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), $defaultIp)) {
        $ip = getenv("HTTP_CLIENT_IP");
    } else {
        if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), $defaultIp)) {
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        } else {
            if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), $defaultIp)) {
                $ip = getenv("REMOTE_ADDR");
            } else {
                if (isset ($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], $defaultIp)) {
                    $ip = $_SERVER['REMOTE_ADDR'];
                } else {
                    $ip = $defaultIp;
                }
            }
        }
    }
    return $ip;
}