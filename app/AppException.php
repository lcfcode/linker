<?php
/**
 * @link https://gitee.com/lcfcode/linker
 * @link https://github.com/lcfcode/linker
 */
    
namespace app;

use swap\core\Error;

/**
 * 应用异常处理类
 */
class AppException extends Error
{

    //其他错误异常都可以重写
    /**
     * @param $debug
     * @author LCF
     * @date 2020/4/25 17:52
     */
    public function render($debug)
    {
        // 开发环境下 Whoops 接管请求异常
        if ($debug === true) {
            $whoops = new \Whoops\Run;
            $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $whoops->pushHandler(new \Whoops\Handler\JsonResponseHandler);
            }
            $whoops->register();
        } else {
            parent::render($debug);
        }
    }

}
