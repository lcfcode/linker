<?php
/**
 * 多余的，主要用于全局配置不同环境修改问题
 * 该文件配置如果和default.php又重复的，将会覆盖default.php配置
 * 2018/8/9 16:57
 */
return [
    //配置首页文件(默认路由) linux上区分大小写,url上只有module和controller首字母不区分，其他位置都区分，大小写规则一致
    'default_route' => [
        'module' => 'demo', //与app/下的模块对应
        'controller' => 'Index', //与控制器名称对应IndexController类
        'action' => 'index' //IndexController类下的indexAction 方法
    ],
    //一下配置都是测试用的，没实际项
    're_debug' => 122,
    're_logs' => '我是测试的',
    're_logs2' => '我是测试的2',
];