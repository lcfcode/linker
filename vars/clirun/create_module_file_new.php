<?php
/**
 * 配置好数据库配置文件和生成的路径 运行 输入生成的模块名称 回车
 * 用法：please enter the module: 输入模块名；例如：mobile
 * 只支持mysql数据库
 * @date 2018/8/9 16:55
 */

//生成路径
$root = __DIR__ . '/../..';
//数据库配置文件
$configFile = __DIR__ . '/../../config/db.php';

run($root, $configFile);

function run($root, $configFile)
{
    $dbConfigs = configRead($configFile);
    list($module, $patch) = inputModule($root);
    foreach ($dbConfigs as $key => $dbConfig) {
        list($tabStr, $fieldArr) = readDb($dbConfig);
        list($tabDaoArr, $originalTabArr) = packagingData($tabStr);
        if ($key == 'db') {
            $isMulti = false;
        } else {
            $isMulti = true;
        }
        $dbKey = $key;
        createFiles($patch, $module, $tabDaoArr, $originalTabArr, $fieldArr, $dbKey, $isMulti);
    }
    createIndexView($patch, $module);
}

function configRead($configFile)
{
    if (!is_file($configFile)) {
        echo 'no ' . $configFile . ' file ...' . PHP_EOL . PHP_EOL;
        exit;
    }
    $config = include $configFile;
    $dbConfig = [];
    foreach ($config as $keys => $rows) {
        if (isset($rows['host']) && isset($rows['user']) && isset($rows['password']) && isset($rows['database']) && isset($rows['port'])) {
            $dbConfig[$keys] = $rows;
        }
    }
    if ($dbConfig) {
        return $dbConfig;
    }
    exit('no db config');
}

function readDb($dbConfig)
{
    $conn = new \mysqli($dbConfig['host'], $dbConfig['user'], $dbConfig['password'], $dbConfig['database'], $dbConfig['port']);
    if ($conn->connect_errno) {
        echo 'database link failed! ...' . PHP_EOL . PHP_EOL;
        exit;
    }
    $conn->set_charset($dbConfig['charset']);
    $conn->select_db('INFORMATION_SCHEMA');
    $sql = "select table_name as t_name from INFORMATION_SCHEMA.TABLES WHERE table_schema = '{$dbConfig['database']}'";
    $result = $conn->query($sql);
    $returnData = [];
    while ($resultRow = $result->fetch_assoc()) {
        $returnData[] = $resultRow;
    }
    $tabStr = '';
    $fieldArr = [];
    if (!empty($returnData)) {
        foreach ($returnData as $key => $row) {
            $sqlTmp = "select column_name as field_name from information_schema.columns where table_name='{$row['t_name']}' and TABLE_SCHEMA='{$dbConfig['database']}' ORDER BY ordinal_position asc";
            $result2 = $conn->query($sqlTmp);
            $tmpData = [];
            while ($resultRow2 = $result2->fetch_assoc()) {
                $tmpData[] = $resultRow2;
            }
            $strTmp = '';
            foreach ($tmpData as $keyTmp => $rowTmp) {
                if ($keyTmp == 0) {
                    $strTmp .= "'{$rowTmp['field_name']}'";
                } else {
                    $strTmp .= ",'{$rowTmp['field_name']}'";
                }
            }
            $fieldArr[$row['t_name']] = $strTmp;
            if ($key == 0) {
                $tabStr .= $row['t_name'];
            } else {
                $tabStr .= ',' . $row['t_name'];
            }
        }
    }
    $conn->close();
    if ($tabStr) {
        return [$tabStr, $fieldArr];
    } else {
        exit('ERROR: database no table');
    }
}

function packagingData($str)
{
    $str = preg_replace("/\n/", '', $str);
    if (strstr(" ", $str)) {
        $str = preg_replace(" ", '', $str);
    }
    if (empty($str)) {
        exit('packagingData str null:1');
    }
    $convert = ucwords(str_replace('_', ' ', $str));
    $convert = str_replace(' ', '', $convert);
    $convert = ucwords(str_replace(',', ' ', $convert));
    $convert = str_replace(' ', ',', $convert);
    $tabDaoArr = explode(',', $convert);
    $originalTabArr = explode(',', $str);
    if (empty($tabDaoArr) || empty($originalTabArr)) {
        exit('packagingData str null:2');
    }
    return [$tabDaoArr, $originalTabArr];
}

function inputModule($root)
{
    fwrite(STDOUT, 'please enter the module: ');
    $module = trim(fgets(STDIN));
    $module = preg_replace("/\n/", '', $module);
    if (strstr(" ", $module)) {
        $module = preg_replace(" ", '', $module);
    }
    if ($module) {
        $module = lcfirst($module);
        $patch = $root . '/app/' . $module;
        if (!is_dir($patch)) {
            mkdir($patch, 0777, true);
        }
        if (!is_dir($patch . '/dao/')) {
            mkdir($patch . '/dao/', 0777, true);
        }
        if (!is_dir($patch . '/service/')) {
            mkdir($patch . '/service/', 0777, true);
        }
        if (!is_dir($patch . '/controller/')) {
            mkdir($patch . '/controller/', 0777, true);
        }
        if (!is_dir($patch . '/config/')) {
            mkdir($patch . '/config/', 0777, true);
        }
        if (!is_dir($patch . '/view/Error/')) {
            mkdir($patch . '/view/Error/', 0777, true);
        }
        if (!is_dir($patch . '/view/Index/')) {
            mkdir($patch . '/view/Index/', 0777, true);
        }
        if (!is_dir($patch . '/view/Layout/')) {
            mkdir($patch . '/view/Layout/', 0777, true);
        }
        return [$module, $patch];
    }
    exit('input module error!');
}

function createFiles($patch, $module, $tabDaoArr, $originalTabArr, $fieldArr, $dbKey, $isMulti = false)
{
    $len = count($tabDaoArr);
    if ($len <= 0) {
        exit('createFiles error:1');
    }
    for ($i = 0; $i < $len; $i++) {
        $daoPrefix = trim($tabDaoArr[$i]);
        if (!$daoPrefix) {
            continue;
        }
        $tabName = trim($originalTabArr[$i]);
        $dao = $daoPrefix . 'Dao';
        $service = $daoPrefix . 'Service';
        if ($isMulti === true) {
            $daoContent = getDaoContent2($module, $dao, $tabName, $fieldArr[$tabName], trim($dbKey));
        } else {
            $daoContent = getDaoContent($module, $dao, $tabName, $fieldArr[$tabName]);
        }
        $daoFile = $patch . '/dao/' . $dao . '.php';
        if (is_file($daoFile)) {
            generateFile($patch . '/dao/' . $dao . '.new.php', $daoContent);
            echo "create:New " . $dao . ".php done " . PHP_EOL;
        } else {
            generateFile($daoFile, $daoContent);
            echo "create:" . $dao . ".php done " . PHP_EOL;
        }
        $serviceContent = getServiceContent($service, $dao, $module, lcfirst($daoPrefix));
        $serviceFile = $patch . '/service/' . $service . '.php';
        if (is_file($serviceFile)) {
            echo $service . ".php exists ---------------------------" . PHP_EOL;
        } else {
            generateFile($serviceFile, $serviceContent);
            echo "create:" . $service . ".php done " . PHP_EOL;
        }
    }
}

function globalConfigCreate($module, $configPath)
{
    $gConfigFile = $configPath . '/config/global.config.php';
    $configArr = file($gConfigFile);
    $configContentArr = array_slice($configArr, 0, -2);
    $moduleLoadConfig = namespaces($module);
    array_push($configContentArr, "" . PHP_EOL);
    foreach ($moduleLoadConfig as $keys => $rows) {
        array_push($configContentArr, "        '" . $keys . "'=>'" . $rows . "', " . PHP_EOL);
    }
    array_push($configContentArr, "    )," . PHP_EOL);
    array_push($configContentArr, ");" . PHP_EOL);
    if (is_file($gConfigFile)) {
        rename($gConfigFile, $configPath . '/config/global.config.php_' . date('YmdHis') . '_bak.php');
    }
    generateFile($gConfigFile, join('', $configContentArr));
    echo "generate:global.config.php done " . PHP_EOL;
}

function createIndexView($patch, $module)
{
    $indexFile = $patch . '/Controller/IndexController.php';
    if (is_file($indexFile)) {
        echo "IndexController.php exists ---------------------------" . PHP_EOL;
    } else {
        generateFile($indexFile, getIndexContent($module));
        echo "create:IndexController.php done " . PHP_EOL;
    }
    $moduleConfig = $patch . '/config/Config.dev.php';
    if (is_file($moduleConfig)) {
        echo "{$module} Config.dev.php exists ---------------------------" . PHP_EOL;
    } else {
        generateFile($moduleConfig, getModuleConfig());
        echo "create:{$module} Config.dev.php done " . PHP_EOL;
    }
    createView($module, $patch);
}

function createView($module, $patch)
{
    $errFile = $patch . '/view/error/404.phtml';
    if (is_file($errFile)) {
        echo "404.phtml exists ---------------------------" . PHP_EOL;
    } else {
        generateFile($errFile, getErr404());
        echo "create:404.phtml done " . PHP_EOL;
    }
    $indexPageFile = $patch . '/view/index/index.phtml';
    if (is_file($indexPageFile)) {
        echo "index.phtml exists ---------------------------" . PHP_EOL;
    } else {
        generateFile($indexPageFile, getIndexPage($module));
        echo "create:index.phtml done " . PHP_EOL;
    }
    $layoutPageFile = $patch . '/view/layout/layout.phtml';
    if (is_file($layoutPageFile)) {
        echo "layout.phtml exists ---------------------------" . PHP_EOL;
    } else {
        generateFile($layoutPageFile, getLayoutPage($module));
        echo "create:layout.phtml done " . PHP_EOL;
    }
}

function funNameFile($tableName, $fieldList)
{
    $fieldArr = explode(',', $fieldList);
    $fieldStr = '';
    foreach ($fieldArr as $keyTmp => $rowTmp) {
        if ($keyTmp % 5 == 0) {
            $fieldStr .= PHP_EOL . "            {$rowTmp} => {$rowTmp},";
        } else {
            $fieldStr .= " {$rowTmp} => {$rowTmp},";
        }
    }
    $tabFun = ucwords(str_replace('_', ' ', $tableName));
    $tabFun = lcfirst(str_replace(' ', '', $tabFun));
    return [$tabFun, $fieldStr];
}

function getDaoContent($module, $dao, $tableName, $fieldList)
{
    list($tabFun, $fieldStr) = funNameFile($tableName, $fieldList);
    $str = <<<OOOO
<?php

namespace app\\{$module}\dao;

use swap\core\Dao;

class {$dao} extends Dao
{
    //表字段
    public function fieldArr()
    {
        return [{$fieldStr}
        ];
    }
}
OOOO;
    return $str;
}

function getDaoContent2($module, $dao, $tableName, $fieldList, $dbKey)
{
    list($tabFun, $fieldStr) = funNameFile($tableName, $fieldList);
    $str = <<<OOOO
<?php

namespace app\\{$module}\dao;

use swap\core\Dao;

class {$dao} extends Dao
{
    public function setConnect()
    {
        return '{$dbKey}';
    }

    //表字段
    public function fieldArr()
    {
        return [{$fieldStr}
        ];
    }
}
OOOO;
    return $str;
}

function getServiceContent($service, $dao, $module, $tableName)
{
    $name = lcfirst($dao);
    $str = <<<DDD
<?php

namespace app\\{$module}\service;

use swap\core\Service;
use app\\{$module}\\dao\\{$dao};

class {$service} extends Service
{
    private \${$name} = null;

    /**
     * @return {$dao}
     */
    public function dao()
    {
        if (\$this->{$name} === null) {
            \$this->{$name} = new {$dao}();
        }
        return \$this->{$name};
    }
}
DDD;

    return $str;
}

function getModuleConfig()
{
    $str = <<<EOF
<?php
//该模块下的配置文件,一般是与环境有关系的
return [
    //'msg_url' => 'https://www.baidu.com',
];
EOF;
    return $str;
}

function getIndexContent($module)
{
    $str = <<<DDD
<?php

namespace app\\{$module}\Controller;

use swap\core\Controller;
use swap\linker\View;

class IndexController extends Controller
{
    public function indexAction()
    {
        //返回给页面的数据放到 View 的构造内
        return new View();
    }
}
DDD;
    return $str;
}

function getErr404()
{
    $str = <<<GGG
<!DOCTYPE html>
<html lang="en" style="height: 100%;width: 100%;overflow-x: hidden">
<head>
    <meta charset="UTF-8">
    <title>服务器瞌睡了</title>
</head>
<style>
    *{font-size: 3rem;margin: 0;padding: 0}
    div{
        position: absolute;
        margin: auto;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        width: 6rem;
        height: 5rem;
        line-height: 5rem;
    }
</style>
<body style="height: 100%;width: 100%;">
<div>错误提示页</div>
</body>
</html>
GGG;
    return $str;
}

function getIndexPage($module)
{
    $str = <<<FFF
<h1>这是 {$module} 模块的中间部分</h1>
FFF;
    return $str;
}

function getLayoutPage($module)
{
    $str = <<<LAYOUT
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{$module} index page</title>
    <link rel="shortcut icon" href="/static/common/favicon.ico">
    <link rel="stylesheet" href="/static/common/css/pc-rest.css" type="text/css">
    <script src="/static/common/js/jquery-2.2.4.min.js"></script>
    <script src="/static/common/js/main.min.js"></script>
</head>
<body>
<h3>这是 {$module} 模块的顶部</h3>
<?php require \$this->_content; ?>
<h3>这是 {$module} 模块的底部</h3>
</body>
</html>
LAYOUT;
    return $str;
}

function generateFile($filename, $data)
{
    $filenum = fopen($filename, "w");
    flock($filenum, LOCK_EX);
    fwrite($filenum, $data);
    fclose($filenum);
}