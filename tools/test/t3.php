<?php
/**
 * Created by PhpStorm.
 * User: ChaoFu
 * Date: 2018/9/7
 * Time: 13:20
 */

include  'QRCodeUtils.php';


//$r=\utils\QRCodeUtils::createQRCode('rr1.png','http://url.serviceark.cn/EErSba',__DIR__);
$r=\utils\QRCodeUtils::createQRCode('rr2.png','http://ssl.f5fz.com/h5/index/m?material_id=d79078132ecf720fe7bc65733f565354',__DIR__);

print_r($r);