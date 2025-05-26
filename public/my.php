<?php
declare (strict_types = 1);

use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Output\QROutputInterface;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

if (is_file(__DIR__ . '/../vendor/autoload.php')) {
    include __DIR__ . '/../vendor/autoload.php';
}


$path=__DIR__.'/../test/log/1715063672_498927324.png';

try{
    $options = new QROptions([
        'version'    => 5,                             //二维码版本
        'outputType' => QROutputInterface::GDIMAGE_PNG,      //生成图片
//        'outputType' => QRCode::OUTPUT_IMAGE_JPG,      //生成图片
//        'eccLevel'   => QRCode::ECC_L,                 //错误级别
        'eccLevel'   => EccLevel::L,                 //错误级别
        'scale'=>10,                                   //二维码大小
    ]);
    $QRCode=new QRCode($options);
//    $result = $QRCode->readFromFile($path); // -> DecoderResult
    // you can now use the result instance...
//    $content = $result->data;
//    echo $content.PHP_EOL;
//    $matrix  = $result->getMatrix(); // -> QRMatrix

    // ...or simply cast it to string to get the content:
//    $content = (string)$result;
//    echo $content.PHP_EOL;
    $path=__DIR__.'/../test/log/'.time().mt_rand().'.png';
    $QRCode->render('htttp://www.baidu.com',$path);
}
catch(Throwable $e){
    // oopsies!
}