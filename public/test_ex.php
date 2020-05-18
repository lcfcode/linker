<?php
include __DIR__ . '/../system/Utils/MysqliClass.php';
$config = include __DIR__ . '/../config/global.config.php';
$dbConfig = $config['db'];

$conn = new \mysqli($dbConfig['host'], $dbConfig['user'], $dbConfig['password'], $dbConfig['database'], $dbConfig['port']);
if ($conn->connect_errno) {
    echo 'database link failed! ' . PHP_EOL . PHP_EOL;
    exit;
}
$conn->set_charset($dbConfig['charset']);
$conn->select_db('INFORMATION_SCHEMA');
$sql = "select table_name as t_name,table_comment as table_comment from INFORMATION_SCHEMA.TABLES WHERE table_schema = '{$dbConfig['database']}'";
$result = $conn->query($sql);

$returnData = arrArr($result);


$data = [];

foreach ($returnData as $item) {
    $sql2 = "SELECT column_name as column_name, column_type as column_type, column_comment as column_comment FROM information_schema.COLUMNS WHERE table_schema = '{$dbConfig['database']}'  AND table_name = '{$item['t_name']}'";
    $result1 = $conn->query($sql2);
    $tmp = arrArr($result1);
    $data[$item['t_name'] . '||' . $item['table_comment']] = $tmp;
}

//print_r($data);die;


require __DIR__ . '/../vendor/autoload.php';
$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

//设置列宽
$sheet->getColumnDimension('A')->setAutoSize(true);
$sheet->getColumnDimension('B')->setAutoSize(true);
$sheet->getColumnDimension('C')->setAutoSize(true);

$styleArray = [
    'borders' => [
        'outline' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => 'FF000000'],
        ],
    ],
];

$i = 1;
$j = 1;
foreach ($data as $key => $dataRow) {
    $tmpArr = explode('||', $key);
    $tabName = isset($tmpArr[0]) ? $tmpArr[0] : '';
    $tab_comment = isset($tmpArr[1]) ? $tmpArr[1] : '';

    $sheet->getRowDimension("$i")->setRowHeight(25);
    $sheet->getStyle("A{$i}")->getFont()->setBold(true)->setSize(16);

    $sheet->getStyle("A{$i}:C{$i}")->applyFromArray($styleArray);

    $sheet->mergeCells("A{$i}:C{$i}");
    $sheet->setCellValue("A{$i}", $tabName . ' [' . $tab_comment . ']');
    foreach ($dataRow as $dKey => $data_row) {
        $i++;
        if ($dKey == 0) {
            $sheet->getStyle("A{$i}:A{$i}")->applyFromArray($styleArray);
            $sheet->getStyle("B{$i}:B{$i}")->applyFromArray($styleArray);
            $sheet->getStyle("C{$i}:C{$i}")->applyFromArray($styleArray);
            $sheet->setCellValue("A{$i}", '字段');
            $sheet->setCellValue("B{$i}", '类型和长度');
            $sheet->setCellValue("C{$i}", '注释与说明');
        }
        $sheet->getStyle("A{$i}:A{$i}")->applyFromArray($styleArray);
        $sheet->getStyle("B{$i}:B{$i}")->applyFromArray($styleArray);
        $sheet->getStyle("C{$i}:C{$i}")->applyFromArray($styleArray);
        $sheet->setCellValue("A{$i}", $data_row['column_name']);
        $sheet->setCellValue("B{$i}", $data_row['column_type']);
        $sheet->setCellValue("C{$i}", $data_row['column_comment']);
    }
    $i++;
    $i++;

}
//die;

//合并单元格
//$sheet->mergeCells('A1:C1');

//设置值
//$sheet->setCellValue('A1', 'Hello World !');

$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
$writer->save('hello_world_' . time() . '.xlsx');

unset($sheet);
unset($spreadsheet);
unset($writer);

exit(10);


function getUuid($prefix = null)
{
    return strtolower(md5(uniqid($prefix . mt_rand(), true)));
}


function arrArr($result)
{
    $returnData = [];
    while ($resultRow = $result->fetch_assoc()) {
        $returnData[] = $resultRow;
    }
    return $returnData;
}