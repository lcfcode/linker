<?php
/**
 * 运行时配置文件,配置不常更变的数据
 * 2018/8/9 16:57
 */
return [
    //默认数据库配置
    'db' => [
        'host' => '127.0.0.1',
        'user' => 'root',
        'password' => 'Abcd4321',
        'port' => '3306',
        'database' => 'linker',
        'charset' => 'utf8',
        'drive' => 'mysql',
        'separate' => false,//读写分离配置,如果值为 true read_db一定要配置
        'read_db' => [
            'host' => '10.0.0.24',
            'user' => 'liangcf',
            'password' => '123456',
            'port' => '3306',
            'database' => 'linker',
            'charset' => 'utf8',
            'drive' => 'mysql',
        ],
    ],
//    'mssql_db' => [
//        'host' => '10.0.0.24',
//        'user' => 'sa',
//        'password' => 'Abcd4321',
//        'database' => 'lcf_test',
//        'port' => '51433',
//        'charset' => 'utf-8',
//        'drive' => 'mssql',
//    ],
    //配置第二个数据库
    'db_2' => [
        'host' => '127.0.0.1',
        'user' => 'root',
        'password' => 'Abcd4321',
        'database' => 'linker_two',
        'port' => '3306',
        'charset' => 'utf8',
        'drive' => 'mysql',
    ],
    //配置redis
    'redis' => [
        'host' => '127.0.0.1',
        'port' => 6379,
        'db' => '0'
    ],
    'logs' => [
        //配置日志文件路径(linux注意权限) ,尾部不要添加'/'
        'path' => dirname(__DIR__) . '/store/logs',
        //日志大小，单位 M
        'size' => 10
    ],
    //配置首页文件(默认路由) linux上区分大小写,url上只有module和controller首字母不区分，其他位置都区分，大小写规则一致
    'default_route' => [
        'module' => 'view', //与app/下的模块对应
        'controller' => 'Index', //与控制器名称对应IndexController类
        'action' => 'index' //IndexController类下的indexAction 方法
    ],
];
