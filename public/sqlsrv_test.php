<?php




$sql="
SELECT TOP (1000) [id]
      ,[name]
  FROM [lcf_test].[dbo].[test_tab]
";


/**
 * @return false|resource
 * @author LCF
 * @date
 */
function cc()
{
    $serverName = "192.168.31.89,51433";
    $database = "lcf_test";
    $uid = "sa";
    $pwd = "Abcd4321@";
    $connectionOptions = array("Database" => $database, "Uid" => $uid, "PWD" => $pwd, 'CharacterSet' => 'UTF-8');


    $conn = sqlsrv_connect($serverName, $connectionOptions);
    return $conn;
}

$conn = cc();

if( $conn === false ){
    die( print_r( sqlsrv_errors(), true));
}
//print_r(sqlsrv_client_info($conn));die;
//$stmt = sqlsrv_query( $conn, $sql);
$sql="
SELECT TOP (1000) [id]
      ,[name]
  FROM [lcf_test].[dbo].[test_tab] where name=?
";
$sql="
SELECT TOP (1000) [id]
      ,[name]
  FROM [lcf_test].[dbo].[test_tab] where name like '?'
";

//$sql="
//SELECT TOP (1000) [id]
//      ,[name]
//  FROM [lcf_test].[dbo].[test_tab] where name like '%_'
//";

$cc='%_';

$params = array($cc);

//$stmt = sqlsrv_query($conn, $sql, $params);
$stmt = sqlsrv_query($conn, $sql);
if( $stmt === false ) {
    die( print_r( sqlsrv_errors(), true));
}
var_dump($stmt);
$ret=[];
while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
    $ret[]=$row;
}
print_r($ret);
die;


$qty = 0; $id = 0;
$stmt = sqlsrv_prepare( $conn, $sql, array( &$qty, &$id));
if( !$stmt ) {
    die( print_r( sqlsrv_errors(), true));
}

$ret=[];
while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
    $ret[]=$row;
}
sqlsrv_free_stmt( $stmt);
print_r($ret);
sqlsrv_close($conn);
exit();


function getUuid($prefix = null)
{
    return strtolower(md5(uniqid($prefix . mt_rand(), true)));
}
