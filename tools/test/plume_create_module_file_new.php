<?php
/**
 * 配置好数据库配置文件和生成的路径 运行 输入生成的模块名称 回车即可
 * 用法：please enter the module: 输入模块名；例如：mobile
 * @date 2018/8/9 16:55
 */
header('Content-Type: text/html; charset=UTF-8');

//生成路径
$root = __DIR__ . '/..';
//数据库配置文件
$configFile = __DIR__ . '/dev.php';

run($root, $configFile);

function run($root, $configFile)
{
    $dbConfigs = configRead($configFile);
    list($module, $patch) = inputModule($root);
    foreach ($dbConfigs as $key => $dbConfig) {
        list($tabStr, $fieldArr) = readDb($dbConfig);
        list($tabDaoArr, $originalTabArr) = packagingData($tabStr);
        if ($key == 'db') {
            $dbKey = 'db';
            $isMulti = false;
        } else {
            $dbKey = $key;
            $isMulti = true;
        }
        createFiles($patch, $module, $tabDaoArr, $originalTabArr, $fieldArr, $dbKey, $isMulti);
    }
    createIndexView($patch, $module);
}

function configRead($configFile)
{
    if (!is_file($configFile)) {
        echo 'no ' . $configFile . ' file ' . "...\r\n\r\n";
        exit;
    }
    $config = include $configFile;
    $dbConfig = [];
    foreach ($config as $keys => $rows) {
        if (isset($rows['host']) && isset($rows['username']) && isset($rows['password']) && isset($rows['database']) && isset($rows['port'])) {
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
    $conn = new \mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['database'], $dbConfig['port']);
    if ($conn->connect_errno) {
        echo 'database link failed! ' . "...\r\n\r\n";
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
    $modules = trim(fgets(STDIN));
    $modules = preg_replace("/\n/", '', $modules);
    if (strstr(" ", $modules)) {
        $modules = preg_replace(" ", '', $modules);
    }
    if ($modules) {
        $modules = ucfirst(strtolower($modules));
        $patch = $root . '/modules/' . $modules;
        if (!is_dir($patch)) {
            mkdir($patch, 0777, true);
        }
        if (!is_dir($patch . '/Dao/')) {
            mkdir($patch . '/Dao/', 0777, true);
        }
        if (!is_dir($patch . '/Service/')) {
            mkdir($patch . '/Service/', 0777, true);
        }
        if (!is_dir($patch . '/Controller/')) {
            mkdir($patch . '/Controller/', 0777, true);
        }
        if (!is_dir($patch . '/View/index/')) {
            mkdir($patch . '/View/index/', 0777, true);
        }
        if (!is_dir($patch . '/View/layout/')) {
            mkdir($patch . '/View/layout/', 0777, true);
        }
        return [$modules, $patch];
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
        $daoFile = $patch . '/Dao/' . $dao . '.php';
        if (is_file($daoFile)) {
            echo $dao . ".php exists ---------------------------\r\n";
        } else {
            generateFile($daoFile, $daoContent);
            echo "create:" . $dao . ".php done \r\n";
        }
        $serviceContent = getServiceContent($service, $dao, $module, lcfirst($daoPrefix));
        $serviceFile = $patch . '/Service/' . $service . '.php';
        if (is_file($serviceFile)) {
            echo $service . ".php exists ---------------------------\r\n";
        } else {
            generateFile($serviceFile, $serviceContent);
            echo "create:" . $service . ".php done \r\n";
        }
    }
}

function createIndexView($patch, $module)
{
    $indexFile = $patch . '/Controller/IndexController.php';
    if (is_file($indexFile)) {
        echo "IndexController.php exists ---------------------------\r\n";
    } else {
        generateFile($indexFile, getIndexContent($module));
        echo "create:IndexController.php done \r\n";
    }
    createView($module, $patch);
}

function createView($module, $patch)
{
    $indexPageFile = $patch . '/View/index/index.phtml';
    if (is_file($indexPageFile)) {
        echo "index.phtml exists ---------------------------\r\n";
    } else {
        generateFile($indexPageFile, getIndexPage($module));
        echo "create:index.phtml done \r\n";
    }
    $layoutPFile = $patch . '/View/layout/layoutP.phtml';
    if (is_file($layoutPFile)) {
        echo "layoutP.phtml exists ---------------------------\r\n";
    } else {
        generateFile($layoutPFile, getLayoutP());
        echo "create:layoutP.phtml done \r\n";
    }
    $layoutMFile = $patch . '/View/layout/layoutM.phtml';
    if (is_file($layoutMFile)) {
        echo "layoutM.phtml exists ---------------------------\r\n";
    } else {
        generateFile($layoutMFile, getLayoutM());
        echo "create:layoutM.phtml done \r\n";
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

namespace {$module}\Dao;

use Plume\Core\Dao;

class {$dao} extends Dao
{
    public function __construct(\$app)
    {
        parent::__construct(\$app, \$this->tabName(), \$this->defaultId());
    }

    //表名
    public function tabName()
    {
        return '{$tableName}';
    }

    //默认主键字段
    public function defaultId()
    {
        return 'id';
    }

    //表字段
    public function fieldArr()
    {
        return [{$fieldStr}
        ];
    }

    //表字段,直接返回字符串或者对应字段生成的别名
    public function field(\$prefix = '', \$tabAlias = '')
    {
        if (empty(\$prefix)) {
            return implode(',', \$this->fieldArr());
        }
        if (empty(\$tabAlias)) {
            \$tabAlias = \$prefix;
        }
        \$str = '';
        \$fieldArr = \$this->fieldArr();
        foreach (\$fieldArr as \$key => \$row) {
            \$str .= ',' . \$tabAlias . '.' . \$row . ' as ' . \$prefix . \$key;
        }
        return trim(\$str, ',');
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

namespace {$module}\Dao;

use Plume\Core\Dao;

class {$dao} extends Dao
{
    public function __construct(\$app)
    {
        parent::__construct(\$app, \$this->tabName(), \$this->defaultId(), '{$dbKey}');
    }

    //表名
    public function tabName()
    {
        return '{$tableName}';
    }

    //默认主键字段
    public function defaultId()
    {
        return 'id';
    }

    //表字段
    public function fieldArr()
    {
        return [{$fieldStr}
        ];
    }

    //表字段,直接返回字符串或者对应字段生成的别名
    public function field(\$prefix = '', \$tabAlias = '')
    {
        if (empty(\$prefix)) {
            return implode(',', \$this->fieldArr());
        }
        if (empty(\$tabAlias)) {
            \$tabAlias = \$prefix;
        }
        \$str = '';
        \$fieldArr = \$this->fieldArr();
        foreach (\$fieldArr as \$key => \$row) {
            \$str .= ',' . \$tabAlias . '.' . \$row . ' as ' . \$prefix . \$key;
        }
        return trim(\$str, ',');
    }
}
OOOO;
    return $str;
}

function getServiceContent($service, $dao, $module, $tableName)
{
    $str = <<<YYY
<?php

namespace {$module}\Service;

use {$module}\Dao\{$dao};
use Plume\Core\Service;

class {$service} extends Service
{
    public function __construct(\$app)
    {
        parent::__construct(\$app, new {$dao}(\$app));
    }
}
YYY;
    return $str;
}

function getModuleConfig()
{
    $str = <<<EOF
<?php
//该模块下的配置文件
return [
    'session_key' => 'my_session_key',
    'user_config' => [
        'user_name' => 'admin',
        'password' => 'admin',
    ],
];
EOF;
    return $str;
}

function getIndexContent($module)
{
    $str = <<<EEE
<?php

namespace {$module}\Controller;

use Plume\Core\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        return \$this->result(array())->response();
    }
}
EEE;
    return $str;
}

function getLayoutP()
{
    $str = <<<GGG
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>
        <?php echo isset(\$data['title']) ? \$data['title'] : "Example by Plume"; ?>
    </title>
    <link rel="stylesheet" href="/css/bootstrap.min.css" type="text/css"/>
    <link rel="stylesheet" href="/css/common/style-p.css" type="text/css"/>
    <link rel="stylesheet" href="/css/common/page.css" type="text/css"/>
    <script src="/js/common/jquery.min.js"></script>
    <script src="/js/common/base.js"></script>
    <script src="/js/common/page.js"></script>
</head>
<body>
GGG;
    return $str;
}

function getLayoutM()
{
    $str = <<<GGG
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width">
    <title>
        <?php echo isset(\$data['title']) ? \$data['title'] : "Example by Plume"; ?>
    </title>
    <link rel="stylesheet" href="/css/common/style-m.css" type="text/css"/>
    <script src="/js/common/jquery.min.js"></script>
    <script src="/js/common/base.js"></script>
</head>
<body>
GGG;
    return $str;
}

function getIndexPage($module)
{
    $str = <<<FFF
<?php include(\$plume['ROOT_PATH'] . "modules/{$module}/View/layout/layoutP.phtml"); ?>
<h1>{$module}</h1>
<?php include(\$plume['ROOT_PATH'] . "modules/{$module}/View/layout/footer.phtml"); ?>
FFF;
    return $str;
}

function getLayoutF()
{
    $str = <<<LAY
</body>
</html>
LAY;
    return $str;
}

function generateFile($filename, $data)
{
    $filenum = fopen($filename, "w");
    flock($filenum, LOCK_EX);
    fwrite($filenum, $data);
    fclose($filenum);
}