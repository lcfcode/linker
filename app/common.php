<?php

/**
 * @param $status
 * @param $msg
 * @param string $url
 * @param array $data
 * @return array
 * @author LCF
 * @date
 */
function output_json($status, $msg, $url = '', $data = [])
{
    return ['code' => $status, 'msg' => $msg, 'url' => $url, 'data' => $data];
}

function output_json_fail($msg = '', $data = [], $url = '')
{
    $msg = empty($msg) ? '保存失败或未更改' : $msg;
    return output_json(0, $msg, $url, $data);
}

function output_json_succeed($msg = '', $data = [], $url = '')
{
    $msg = empty($msg) ? '保存成功' : $msg;
    return output_json(1, $msg, $url, $data);
}


function my_redirect($url)
{
    header('location:' . $url);
    exit;
}
