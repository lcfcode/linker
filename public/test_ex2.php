<?php

require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$data = array(0 => array(0 => 1, 1 => 'alex1', 2 => 1,), 1 => array(0 => 2, 1 => 'alex2', 2 => 2,), 2 => array(0 => 3, 1 => 'alex3', 2 => 1,), 3 => array(0 => 4, 1 => 'alex4', 2 => 2,), 4 => array(0 => 5, 1 => 'alex5', 2 => 1,), 5 => array(0 => 6, 1 => 'alex6', 2 => 2,));

$title = ['id', 'name', 'sex'];


$spreadsheet = new Spreadsheet();
$worksheet = $spreadsheet->getActiveSheet();
//设置工作表标题名称
$worksheet->setTitle('测试Excel');

$w2=$spreadsheet->createSheet(1);

$w2->setTitle('测试Excel22');

//表头
//设置单元格内容
foreach ($title as $key => $value) {
    $worksheet->setCellValueByColumnAndRow($key + 1, 1, $value);
    $w2->setCellValueByColumnAndRow($key + 1, 1, $value);
}

$row = 2; //第二行开始
foreach ($data as $item) {
    $column = 1;
    foreach ($item as $value) {
        $worksheet->setCellValueByColumnAndRow($column, $row, $value);
        $w2->setCellValueByColumnAndRow($column, $row, $value);
        $column++;
    }
    $row++;
}



# 保存为xlsx
$filename = '测试_' . time() . 'Excel.xlsx';
$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save($filename);

