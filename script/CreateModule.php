<?php
/**
 * 模块创建
 * 只支持mysql数据库
 * @date 2018/8/9 16:55
 */

namespace script;

//生成路径
$root = dirname(__DIR__);
//数据库配置文件
$configFile = dirname(__DIR__) . '/config/dev.php';
$moduleName = 'view2';
$app = new CreateModule();
$app->run($root, $configFile, $moduleName, true);

class CreateModule
{
    public function run($root, $configFile, $module, $isView)
    {
        $dbConfigs = $this->configRead($configFile);
//        print_r($dbConfigs);
        $patch = $this->initModule($root, $module, $isView);
        foreach ($dbConfigs as $key => $dbConfig) {
            list($tabStr, $fieldArr) = $this->readDb($dbConfig);
            list($tabDaoArr, $originalTabArr) = $this->packagingData($tabStr);
            if ($key == 'db') {
                $isMulti = false;
            } else {
                $isMulti = true;
            }
            $dbKey = $key;
            $this->createFiles($patch, $module, $tabDaoArr, $originalTabArr, $fieldArr, $dbKey, $isMulti);
        }
        $this->createIndexView($patch, $module, $isView);
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

    private function readDb($dbConfig)
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

    private function initModule($root, $module, $isView)
    {
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
        if ($isView === true) {
            if (!is_dir($patch . '/view/Error/')) {
                mkdir($patch . '/view/Error/', 0777, true);
            }
            if (!is_dir($patch . '/view/Index/')) {
                mkdir($patch . '/view/Index/', 0777, true);
            }
            if (!is_dir($patch . '/view/Layout/')) {
                mkdir($patch . '/view/Layout/', 0777, true);
            }
        }
        return $patch;
    }

    private function createFiles($patch, $module, $tabDaoArr, $originalTabArr, $fieldArr, $dbKey, $isMulti = false)
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
                $daoContent = $this->getDaoContent2($module, $dao, $tabName, $fieldArr[$tabName], trim($dbKey));
            } else {
                $daoContent = $this->getDaoContent($module, $dao, $tabName, $fieldArr[$tabName]);
            }
            $daoFile = $patch . '/dao/' . $dao . '.php';
            if (is_file($daoFile)) {
                $this->generateFile($patch . '/dao/' . $dao . '.new.php', $daoContent);
                echo "create:New " . $dao . ".php done " . PHP_EOL;
            } else {
                $this->generateFile($daoFile, $daoContent);
                echo "create:" . $dao . ".php done " . PHP_EOL;
            }
            $serviceContent = $this->getServiceContent($service, $dao, $module);
            $serviceFile = $patch . '/service/' . $service . '.php';
            if (is_file($serviceFile)) {
                echo $service . ".php exists ---------------------------" . PHP_EOL;
            } else {
                $this->generateFile($serviceFile, $serviceContent);
                echo "create:" . $service . ".php done " . PHP_EOL;
            }
        }
    }

    private function createIndexView($patch, $module, $isView)
    {
        $indexFile = $patch . '/Controller/IndexController.php';
        if (is_file($indexFile)) {
            echo "IndexController.php exists ---------------------------" . PHP_EOL;
        } else {
            $this->generateFile($indexFile, $this->getIndexContent($module, $isView));
            echo "create:IndexController.php done " . PHP_EOL;
        }
        $moduleConfig = $patch . '/config/Config.dev.php';
        if (is_file($moduleConfig)) {
            echo "{$module} Config.dev.php exists ---------------------------" . PHP_EOL;
        } else {
            $this->generateFile($moduleConfig, $this->getModuleConfig());
            echo "create:{$module} Config.dev.php done " . PHP_EOL;
        }
        if ($isView === true) {
            $this->createView($module, $patch);
        }
    }

    private function createView($module, $patch)
    {
        $errFile = $patch . '/view/error/404.phtml';
        if (is_file($errFile)) {
            echo "404.phtml exists ---------------------------" . PHP_EOL;
        } else {
            $this->generateFile($errFile, $this->getErr404());
            echo "create:404.phtml done " . PHP_EOL;
        }
        $indexPageFile = $patch . '/view/index/index.phtml';
        if (is_file($indexPageFile)) {
            echo "index.phtml exists ---------------------------" . PHP_EOL;
        } else {
            $this->generateFile($indexPageFile, $this->getIndexPage());
            echo "create:index.phtml done " . PHP_EOL;
        }
        $layoutPageFile = $patch . '/view/layout/layout.phtml';
        if (is_file($layoutPageFile)) {
            echo "layout.phtml exists ---------------------------" . PHP_EOL;
        } else {
            $this->generateFile($layoutPageFile, $this->getLayoutPage($module));
            echo "create:layout.phtml done " . PHP_EOL;
        }
    }

    private function funNameFile($tableName, $fieldList)
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

    private function getDaoContent($module, $dao, $tableName, $fieldList)
    {
        list($tabFun, $fieldStr) = $this->funNameFile($tableName, $fieldList);
        $str = <<<OOOO
<?php

namespace app\\{$module}\dao;

use Swap\Core\Dao;

class {$dao} extends Dao
{
    //表字段
    public function fieldArr()
    {
        return [{$fieldStr}
        ];
    }
    
    //表名
    public function tabName()
    {
        return '$tableName';
    }
}
OOOO;
        return $str;
    }

    private function getDaoContent2($module, $dao, $tableName, $fieldList, $dbKey)
    {
        list($tabFun, $fieldStr) = $this->funNameFile($tableName, $fieldList);
        $str = <<<OOOO
<?php

namespace app\\{$module}\dao;

use Swap\Core\Dao;

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
    
    //表名
    public function tabName()
    {
        return '$tableName';
    }
}
OOOO;
        return $str;
    }

    private function getServiceContent($service, $dao, $module)
    {
        $name = lcfirst($dao);
        $str = <<<DDD
<?php

namespace app\\{$module}\service;

use Swap\Core\Service;
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
            \$this->{$name} = new {$dao}(\$this->app);
        }
        return \$this->{$name};
    }
}
DDD;

        return $str;
    }

    private function getModuleConfig()
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

    private function getIndexContent($module, $isView)
    {
        if ($isView === true) {
            return <<<DDD
<?php

namespace app\\{$module}\controller;

use Swap\Core\Controller;
use Swap\View\View;

class IndexController extends Controller
{
    public function indexAction()
    {
        \$data = [
            'date' => date('Y-m-d H:m:s'),
            'microtime' => microtime(true),
        ];
        return new View(\$data);
    }
}
DDD;
        }
        return <<<DDD
<?php

namespace app\\{$module}\controller;

use Swap\Core\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        return \$this->msg(0, '成功', ['date' => date('Y-m-d H:i:s')]);
    }
}
DDD;
    }

    private function getErr404()
    {
        return <<<GGG
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
    }

    private function getIndexPage()
    {
        return <<<FFF
<p>后台数据:<?php echo \$this->data['date']; ?></p>
<p>后台数据:<?php echo \$this->data['microtime']; ?></p>
FFF;
    }

    private function getLayoutPage($module)
    {
        return <<<LAYOUT
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
    }

    private function generateFile($filename, $data)
    {
        $filenum = fopen($filename, "w");
        flock($filenum, LOCK_EX);
        fwrite($filenum, $data);
        fclose($filenum);
    }

}

