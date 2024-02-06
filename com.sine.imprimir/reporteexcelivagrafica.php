<?php

$type = $_POST['type'];
if ($type == '1') {
    $fiscales = $_POST['datos'];
    $dataactual = $_POST['dataactual'];
    $datapasado = $_POST['datapasado'];
    $dataantep = $_POST['dataantep'];

    $dataactual = str_replace('data:image/png;base64,', '', $dataactual);
    $dataactual = str_replace(' ', '+', $dataactual);
    $fileData = base64_decode($dataactual);
    $fileName = '../temporal/actual.png';
    file_put_contents($fileName, $fileData);

    $datapasado = str_replace('data:image/png;base64,', '', $datapasado);
    $datapasado = str_replace(' ', '+', $datapasado);
    $fileData = base64_decode($datapasado);
    $fileName = '../temporal/pasado.png';
    file_put_contents($fileName, $fileData);

    $dataantep = str_replace('data:image/png;base64,', '', $dataantep);
    $dataantep = str_replace(' ', '+', $dataantep);
    $fileData = base64_decode($dataantep);
    $fileName = '../temporal/antep.png';
    file_put_contents($fileName, $fileData);
} else if ($type == '2') {
    $dataactual = $_POST['dataactual'];

    $dataactual = str_replace('data:image/png;base64,', '', $dataactual);
    $dataactual = str_replace(' ', '+', $dataactual);
    $fileData = base64_decode($dataactual);
    $fileName = '../temporal/graficaBusqueda.png';
    file_put_contents($fileName, $fileData);
}

require_once '../com.sine.controlador/ControladorConfiguracion.php';
require_once '../vendor/autoload.php';

$cc = new ControladorConfiguracion();
$encabezado = $cc->getDatosEncabezado('8');
foreach ($encabezado as $actual) {
    $titulo = $actual['tituloencabezado'];
    $rgbt = explode("#", $actual['colortitulo']);
    $pagina = $actual['pagina'];
    $correo = $actual['correo'];
    $telefono1 = $actual['telefono1'];
    $telefono2 = $actual['telefono2'];
    $rgbc = explode("#", $actual['colorceltitulo']);
    $rgbht = explode("#", $actual['colorhtabla']);
    $rgbtt = explode("#", $actual['colortittabla']);
    $imglogo = $actual['imglogo'];
    $logo = explode("/", $imglogo);
}

if ($correo != "") {
    $correo = "Email: $correo";
}
if ($telefono2 != "") {
    $telefono2 = " & $telefono2";
}

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setTitle('Reporte');

$sheet->mergeCells('A2:L2');
$sheet->getStyle('A2:A3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($rgbc[1]);
$sheet->getStyle('A2:A3')->getFont()->setSize(14);
$sheet->getStyle('A2:A3')->getFont()->getColor()->setARGB($rgbt[1]);
$sheet->getStyle('A2:A3')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
$sheet->getStyle('A2:A3')->getAlignment()->setWrapText(true);
$sheet->getStyle('A2:A3')->getFont()->setBold(true);
$sheet->getRowDimension('2')->setRowHeight(75);
$sheet->setCellValue('A2', utf8_decode($titulo . "\r" . $pagina . " " . $correo . " " . $telefono1 . " " . $telefono2));

$sheet->mergeCells('A3:L3');
$sheet->setCellValue('A3', 'Reporte Bimestral de IVA Facturado y de Recargo');

$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
$drawing->setName('Logo');
$drawing->setDescription('Logo');
$drawing->setPath("../img/logo/$logo[0]");
$drawing->setCoordinates('M2');
$drawing->setHeight(100);
$drawing->setWorksheet($spreadsheet->getActiveSheet());

if ($type == '1') {
    $drawing2 = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing2->setName('Actual');
    $drawing2->setDescription('Bimestre Actual');
    $drawing2->setPath("../temporal/actual.png");
    $drawing2->setHeight(300);
    $drawing2->setWorksheet($spreadsheet->getActiveSheet());
    $drawing2->setCoordinates('A6');

    $drawing3 = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing3->setName('Pasado');
    $drawing3->setDescription('Bimestre Anterior');
    $drawing3->setPath("../temporal/pasado.png");
    $drawing3->setHeight(300);
    $drawing3->setWorksheet($spreadsheet->getActiveSheet());
    $drawing3->setCoordinates('L6');

    $drawing4 = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing4->setName('Antepasado');
    $drawing4->setDescription('Bimestre Antepasado');
    $drawing4->setPath("../temporal/antep.png");
    $drawing4->setHeight(300);
    $drawing4->setWorksheet($spreadsheet->getActiveSheet());
    $drawing4->setCoordinates('G22');
    
} else if ($type == '2') {
    
    $drawing2 = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing2->setName('Actual');
    $drawing2->setDescription('Bimestre Actual');
    $drawing2->setPath("../temporal/graficaBusqueda.png");
    $drawing2->setHeight(300);
    $drawing2->setWorksheet($spreadsheet->getActiveSheet());
    $drawing2->setCoordinates('A6');
}

$objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
$objWriter->save('../reporteExcel/reporteBimestral.xlsx');
?>