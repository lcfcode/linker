<?php
//declare(strict_types=1);

$serverName = "192.168.31.89,51433";
$database = "lcf_test";
$uid = "sa";
$pwd = "Abcd4321@";


$sql="
SELECT TOP (1000) [id]
      ,[name]
  FROM [lcf_test].[dbo].[test_tab]
";

$link = new PDO("sqlsrv:server=$serverName ; Database = $database", $uid, $pwd);
//$link->exec("set names gbk");
$link->exec("set names UTF-8");
if(!$link){
    die('err');
}
$result=$link->query($sql);
$result1=$result->fetchAll(PDO::FETCH_ASSOC);

//print_r($result1);
echo PHP_EOL.PHP_EOL;
//exit();

$connectionOptions = array("Database"=>$database, "Uid"=>$uid, "PWD"=>$pwd,'CharacterSet'=>'UTF-8');


$conn = sqlsrv_connect($serverName, $connectionOptions);

if( $conn === false ){
    die( print_r( sqlsrv_errors(), true));
}
print_r(sqlsrv_client_info($conn));die;
$stmt = sqlsrv_query( $conn, $sql);

$ret=[];
while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
    $ret[]=$row;
}
sqlsrv_free_stmt( $stmt);
print_r($ret);

exit();


function getUuid($prefix = null)
{
    return strtolower(md5(uniqid($prefix . mt_rand(), true)));
}
