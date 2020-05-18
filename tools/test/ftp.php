<?php
date_default_timezone_set('Asia/Shanghai');
//dev
//$config = ['dir' => '/work/web', 'host' => '192.168.15.60', 'name' => 'liangchaofu', 'pwd' => 'd', 'port' => 21];
$config = ['dir' => '/work/web', 'host' => '120.79.230.121', 'name' => 'liangchaofu', 'pwd' => 'd', 'port' => 21];
//$config = ['dir' => '/', 'host' => '47.99.78.95', 'name' => '', 'pwd' => '', 'port' => ];


$files = [];
$dirArr = [
    __DIR__ . '/application',
    __DIR__ . '/public/static/admin/js',
    __DIR__ . '/public/static/admin/css',
];
foreach ($dirArr as $dir) {
    searchDir($dir, $files);
}
$baseDir = $config['dir'];

$conn = ftp_connect($config['host'], $config['port'], 5) or exit("Could not connect");
//$conn = ftp_ssl_connect($config['host'], $config['port'], 5) or exit("Could not connect");
ftp_login($conn, $config['name'], $config['pwd']);
ftp_set_option($conn, FTP_USEPASVADDRESS, false);
//ftp_pasv($conn, true);
ftp_chdir($conn, $baseDir);

upfile($files, $conn, $baseDir);

ftp_close($conn);


function searchDir($path, &$files)
{
    $myDir = dir($path);
    while ($file = $myDir->read()) {
        if (is_file($path . '/' . $file)) {
            $files[] = $path . '/' . $file;
        }
        if (is_dir($path . '/' . $file) && ($file != ".") && ($file != "..")) {
            searchDir($path . '/' . $file, $files);
        }
    }
    $myDir->close();
}

/**
 * @param array $files
 * @param $conn resource
 * @param string $baseDir
 * @author LCF
 * @date
 */
function upfile(array $files, $conn, string $baseDir): void
{
    foreach ($files as $localName) {
        ftp_chdir($conn, $baseDir);
        $ftpName = ltrim(stristr($localName, 'huatian-zjxy/'), 'huatian-zjxy');
        $ftpDir = $baseDir . dirname($ftpName);
        $cDir = dirname($ftpName);
        $parts = explode('/', trim($cDir, '/'));
        foreach ($parts as $part) {
            if (!@ftp_chdir($conn, $part)) {
                ftp_mkdir($conn, $part);
                ftp_chdir($conn, $part);
            }
        }
        ftp_chdir($conn, $ftpDir);
        $result = ftp_put($conn, basename($ftpName), $localName, FTP_BINARY, 0);
//        $result = ftp_put($conn, basename($localName), $localName, FTP_ASCII, 0);
        var_dump($result);
        echo $localName . PHP_EOL;
    }
}