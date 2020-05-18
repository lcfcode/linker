<?php

function generateFile($filename, $data) {
    $filenum = fopen($filename, "w");
    flock($filenum, LOCK_EX);
    fwrite($filenum, $data);
    fclose($filenum);
}


file_put_contents('/work/personal/2019/code/woh/tools/test/lcf-t.txt','测试数据   '.time().PHP_EOL,FILE_APPEND);