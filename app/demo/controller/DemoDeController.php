<?php
/**
 * @link https://gitee.com/lcfcode/linker
 * @link https://github.com/lcfcode/linker
 */
    
namespace app\demo\controller;

use swap\core\Controller;
use swap\linker\View;

class DemoDeController extends Controller
{
    public function TndeXAction()
    {
        return new View();
    }

    public function indexAction()
    {
        return new View();
    }

    public function tAction()
    {
        $this->x('分割线');

        $tmp = array(
            //数据库配置信息
            'db' => array(
                'driver' => 'mysql',
                'host' => '127.0.0.1',
                'username' => 'liangcf',
                'password' => '123456',
                'database' => 'linker',
                'port' => '3306',
                'charset' => 'utf8'
            ),
            //培训数据库
            'db_2' => array(
                'driver' => 'mysql',
                'host' => '127.0.0.1',
                'username' => 'liangcf',
                'password' => '123456',
                'database' => 'linker_two',
                'port' => '3306',
                'charset' => 'utf8'
            ),
            //分页配置
            'page_info' => array(
                'page_size' => 10, //每页显示条数
                'page_btn' => 5 //分页按钮默认显示的个数
            ),
            //分页配置
            'page_info_index' => array(
                'page_size' => 20, //每页显示条数
                'page_btn' => 5 //分页按钮默认显示的个数
            ),
            'upload_js' => array(
                "storage_js" => "http://storage.f5fz.cn:12080",//上传图片、视频、音乐、地址
                "env" => "dev",//上传环境
            ),
            //本项目域名
            'host_url' => 'http://120.76.113.249:11039',//https://ssl.f5fz.com
            //小程序api
            'wei_api' => array(
                'getWxLogin' => 'https://api.weixin.qq.com/sns/jscode2session',
                'appid' => 'wx556153dc797343bf',
                'secret' => '1bf1dad1611a9cbbe7f13dbad9b84f9b',
            ),
            //直播配置
            'live_config' => array(
                'service_url' => 'rtmp://video-center.alivecdn.com/bpo-cloudservice-dev/',//直播推流服务器地址
                'stream_name' => '?vhost=live.51nianhui.cn',//直播推流唯一流名称参数部分
                'user_live_url' => '://live.51nianhui.cn/bpo-cloudservice-dev/'//用户端观看直播地址
            ),
            /*----------------------------IVR配置-----------------------------------*/
            // 审核状态
            'audit_status' => array(
                '未审核', '审核中', '审核通过', '审核失败'
            ),
            // 根据企业id获取电话列表接口
            'get_telephone_api' => 'http://118.178.112.3:6006/api/getsmalllist',
            //ceh-design项目 地址
            'ceh_design' => array(
                'domain' => 'http://120.76.119.30:52008'//域名
            ),
            //voice 分页
            'voice_page_info' => array(
                'page_size' => 10, //每页显示条数
                'page_btn' => 5 //分页按钮默认显示的个数
            ),
        );

        $this->x($tmp);
        return 'test';
    }

    private function x($content)
    {
//        p(gettype(var_dump($content)));
        $content = json_encode($content, JSON_UNESCAPED_UNICODE);

        echo "<script>console.group('debug.info');console.info({$content});console.groupEnd();</script>";
//        echo "<script>console.group('debug.info');console.info(JSON.parse('{$content}'));console.groupEnd();</script>";
    }
}