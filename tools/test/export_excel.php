<?php

$config = include __DIR__ . '/../../config/dev.php';
$dbConfig = $config['db'];

$conn = new \mysqli($dbConfig['host'], $dbConfig['user'], $dbConfig['password'], $dbConfig['database'], $dbConfig['port']);
if ($conn->connect_errno) {
    echo 'database link failed! ' . "...\r\n\r\n";
    exit;
}
$conn->set_charset($dbConfig['charset']);
$conn->select_db('INFORMATION_SCHEMA');
$sql = "select table_name as t_name,table_comment as table_comment from INFORMATION_SCHEMA.TABLES WHERE table_schema = '{$dbConfig['database']}'";
$result = $conn->query($sql);

$returnData = arrArr($result);


$data = [];

foreach ($returnData as $item) {
    $sql2 = "SELECT column_name as column_name, column_type as column_type, column_comment as column_comment,is_nullable as is_null,column_default as default_val FROM information_schema.COLUMNS WHERE table_schema = '{$dbConfig['database']}'  AND table_name = '{$item['t_name']}'";
    $result1 = $conn->query($sql2);
    $tmp = arrArr($result1);
    $data[$item['t_name'] . '||' . $item['table_comment']] = $tmp;
}

//print_r($data);die;


require __DIR__ . '/../../vendor/autoload.php';
$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$styleArray = [
    'borders' => [
        'outline' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => 'FF000000'],
        ],
    ],
];

//设置列宽
$sheet->getColumnDimension('A')->setAutoSize(true);
$sheet->getColumnDimension('B')->setAutoSize(true);
$sheet->getColumnDimension('C')->setAutoSize(true);
$sheet->getColumnDimension('D')->setAutoSize(true);

$i = 1;
foreach ($data as $key => $dataRow) {
    $tmpArr = explode('||', $key);
    $tabName = isset($tmpArr[0]) ? $tmpArr[0] : '';
    $tab_comment = isset($tmpArr[1]) ? $tmpArr[1] : '';

    $sheet->getRowDimension("$i")->setRowHeight(25);
    $sheet->getStyle("A{$i}")->getFont()->setBold(true)->setSize(16);

    $sheet->getStyle("A{$i}:D{$i}")->applyFromArray($styleArray);

    $sheet->mergeCells("A{$i}:D{$i}");
    $sheet->setCellValue("A{$i}", $tabName . ' [' . $tab_comment . ']');
    foreach ($dataRow as $dKey => $data_row) {
        $i++;
        if ($dKey == 0) {
            $sheet->getStyle("A{$i}:A{$i}")->applyFromArray($styleArray);
            $sheet->getStyle("B{$i}:B{$i}")->applyFromArray($styleArray);
            $sheet->getStyle("C{$i}:C{$i}")->applyFromArray($styleArray);
            $sheet->getStyle("D{$i}:D{$i}")->applyFromArray($styleArray);
            $sheet->setCellValue("A{$i}", '字段');
            $sheet->setCellValue("B{$i}", '类型和长度');
            $sheet->setCellValue("C{$i}", '空');
            $sheet->setCellValue("D{$i}", '注释与说明');
            $i++;
        }
        $sheet->getStyle("A{$i}:A{$i}")->applyFromArray($styleArray);
        $sheet->getStyle("B{$i}:B{$i}")->applyFromArray($styleArray);
        $sheet->getStyle("C{$i}:C{$i}")->applyFromArray($styleArray);
        $sheet->getStyle("D{$i}:D{$i}")->applyFromArray($styleArray);
        $sheet->setCellValue("A{$i}", $data_row['column_name']);
        $sheet->setCellValue("B{$i}", $data_row['column_type']);
        $sheet->setCellValue("C{$i}", $data_row['is_null']);
        $sheet->setCellValue("D{$i}", $data_row['column_comment']);
    }
    $i++;
    $i++;
}

//die;

//合并单元格
//$sheet->mergeCells('A1:C1');

//设置值
//$sheet->setCellValue('A1', 'Hello World !');

$filename = 'tab_construct_' . time() . '.xlsx';

$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
$writer->save($filename);

//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//header('Content-Disposition: attachment;filename="' . $filename . '"');
//header('Cache-Control: max-age=0');
//$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
//$writer->save('php://output');


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