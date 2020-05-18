<?php
/**
 * 运行时配置文件,配置不常更变的数据
 * 2018/8/9 16:57
 */
return [
    //默认数据库配置
    'db' => [
        'host' => '192.168.31.89',
        'user' => 'root',
        'password' => 'Abcd4321',
        'port' => '53306',
        'database' => 'linker',
        'charset' => 'utf8',
        'drive' => 'mysql',
        'separate' => false,//读写分离配置,如果值为 true read_db一定要配置
        'read_db' => [
            'host' => '192.168.31.89',
            'user' => 'liangcf',
            'password' => '123456',
            'port' => '53306',
            'database' => 'linker',
            'charset' => 'utf8',
            'drive' => 'mysql',
        ],
    ],
    'mssql_db' => [
        'host' => '192.168.31.89',
        'user' => 'sa',
        'password' => 'Abcd4321',
        'database' => 'lcf_test',
        'port' => '51433',
        'charset' => 'utf-8',
        'drive' => 'mssql',
    ],
    //配置第二个数据库
    'db_2' => [
        'host' => '192.168.31.89',
        'user' => 'root',
        'password' => 'Abcd4321',
        'database' => 'linker_two',
        'port' => '53306',
        'charset' => 'utf8',
        'drive' => 'mysql',
    ],
    //配置redis
    'redis' => [
        'host' => '192.168.31.89',
        'port' => 6379,
        'db' => '0'
    ],
    //配置日志文件路径(linux注意权限) 前面不要缺少'/',尾部不要添加'/'  路径是相对项目路径开始,
    'logs' => __DIR__ . '/../tools/logs',
    //模块配置文件
    'module_file' => 'Config.dev.php',
    //包含可变的配置
    'multi_profile' => 'common.config.php',
];