<?php

/**
 * @param $status
 * @param $msg
 * @param string $url
 * @param array $data
 * @return bool
 * @author LCF
 * @date
 */
function output_json($status, $msg, $url = '', $data = [])
{
    $data = ['code' => $status, 'msg' => $msg, 'url' => $url, 'data' => $data];
    header('Content-Type:application/json;charset=UTF-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
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

function get_url($action = null, $controller = null)
{
    $gConfig = \swap\utils\Helper::config(true);
    $bind = $gConfig['request.bind'];
    $config = $gConfig['request.route'];
    $module = $config['module'];
    if (empty($controller)) {
        $controller = $config['controller'];
    }
    if (empty($action)) {
        $action = $config['action'];
    }
    return empty($bind) ? '/' . $module . '/' . $controller . '/' . $action : '/' . $controller . '/' . $action;
}

function my_redirect($url)
{
    header('location:' . $url);
    exit;
}

function get_uuid()
{
    return \swap\utils\Helper::utils()->getUuid();
}
