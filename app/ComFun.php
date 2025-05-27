<?php

namespace app;

class ComFun
{
    public static function json($status, $msg, $data = null): array
    {
        header('Content-Type:application/json; charset=utf-8');
        return ['code' => $status, 'msg' => $msg, 'data' => $data];
    }

    public static function fail($msg = '', $data = null): array
    {
        $msg = empty($msg) ? '失败' : $msg;
        return self::json(1, $msg, $data);
    }

    public static function succeed($data = null): array
    {
        $msg = empty($msg) ? '成功' : $msg;
        return self::json(0, $msg, $data);
    }

    public static function redirect($url): void
    {
        header('location:' . $url);
        exit;
    }
}


