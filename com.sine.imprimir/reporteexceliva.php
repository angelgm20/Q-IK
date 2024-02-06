<?php

$emisor = $_GET['emisor'];
$receptor = $_GET['receptor'];


$ano = "";
$mes = "";

if ($_GET['anho'] != '' || $_GET['mes'] != '') {
    $ano = $_GET['anho'];
    $mes = $_GET['mes'];
}

require_once '../com.sine.controlador/ControladorReportes.php';
require_once '../com.sine.controlador/ControladorConfiguracion.php';
require_once '../com.sine.modelo/Reportes.php';
require_once '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$cc = new ControladorConfiguracion();
$encabezado = $cc->getDatosEncabezado('9');
foreach ($encabezado as $actual) {
    $titulo = $actual['tituloencabezado'];
    $colortitulo = $actual['colortitulo'];
    $rgbt = explode("#", $colortitulo);
    $pagina = $actual['pagina'];
    $correo = $actual['correo'];
    $telefono1 = $actual['telefono1'];
    $telefono2 = $actual['telefono2'];
    $colorcuadro = $actual['colorceltitulo'];
    $rgbc = explode("#", $colorcuadro);
    $colorhtabla = $actual['colorhtabla'];
    $rgbht = explode("#", $colorhtabla);
    $colortittabla = $actual['colortittabla'];
    $rgbtt = explode("#", $colortittabla);
    $imglogo = $actual['imglogo'];
    $logo = explode("/", $imglogo);
}

if ($correo != "") {
    $correo = "Email: $correo";
}
if ($telefono2 != "") {
    $telefono2 = " & $telefono2";
}

$controlador_reporte = new ControladorReportes();
$r = new Reportes();

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setTitle('Reporte');

$sheet->mergeCells('A2:E2');
$sheet->getStyle('A2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($rgbc[1]);
$sheet->getStyle('A2')->getFont()->setSize(14);
$sheet->getStyle('A2')->getFont()->setBold(true);
$sheet->getStyle('A2')->getFont()->getColor()->setARGB($rgbt[1]);
$sheet->getStyle('A2')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

$sheet->getRowDimension('2')->setRowHeight(75);
$sheet->setCellValue('A2', utf8_decode($titulo."\r".$pagina." ".$correo." ".$telefono1." ".$telefono2));
$sheet->getStyle('A2')->getAlignment()->setWrapText(true);
$sheet->mergeCells('A3:F3');
$sheet->getStyle('A3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($rgbc[1]);
$sheet->getStyle('A3')->getFont()->setSize(14);
$sheet->getStyle('A3')->getFont()->setBold(true);
$sheet->getStyle('A3')->getFont()->getColor()->setARGB($rgbt[1]);
$sheet->setCellValue('A3', 'Reporte de IVA facturado y de recargo');

$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
$drawing->setName('Logo');
$drawing->setDescription('Logo');
$drawing->setPath("../img/logo/$logo[0]");
$drawing->setCoordinates('F2');
$drawing->setHeight(100);
$drawing->setWorksheet($spreadsheet->getActiveSheet());

$sheet->getStyle('A5:F5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($rgbht[1]);
$sheet->getStyle('A5:G5')->getFont()->getColor()->setARGB($rgbtt[1]);
$sheet->getStyle('A5:F5')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
$sheet->getStyle('A5:F5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A5:F5')->getAlignment()->setWrapText(true);
$sheet->getStyle('A5:F5')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

$sheet->getStyle('A5:F5')->getFont()->setSize(12);
$sheet->getStyle('A5:F5')->getFont()->setBold(true);

$sheet->getColumnDimension('A')->setWidth(30);
$sheet->getColumnDimension('B')->setWidth(20);
$sheet->getColumnDimension('C')->setWidth(30);
$sheet->getColumnDimension('D')->setWidth(25);
$sheet->getColumnDimension('E')->setWidth(20);
$sheet->getColumnDimension('F')->setWidth(20);

$sheet->setCellValue('A5', 'UUID');
$sheet->setCellValue('B5', 'RFC Emisor');
$sheet->setCellValue('C5', 'Nombre Emisor');
$sheet->setCellValue('D5', 'RFC Receptor');
$sheet->setCellValue('E5', 'Fecha Emision');
$sheet->setCellValue('F5', 'Monto');

$row = 6; //<--Variable que autoincrementa segun el numero de filas que genere la consulta, su valor es el numero de celda en que comenzara a mostrar los resultados
$datosreporte = $controlador_reporte->getIVAReporte($emisor, $receptor, $ano, $mes);
foreach ($datosreporte as $reporteactual) {
    $uuid = $reporteactual['uuid'];
    $rfcemisor = $reporteactual['rfcemisor'];
    $nombreemisor = $reporteactual['nombreemisor'];
    $rfcreceptor = $reporteactual['rfcreceptor'];
    $fechaemision = $reporteactual['fechaemision'];
    $horaemision = $reporteactual['horaemision'];
    $monto = $reporteactual['monto'];

    $sheet->getStyle('A' . $row . ':F' . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $sheet->getStyle('A' . $row . ':F' . $row)->getAlignment()->setWrapText(true);
    $sheet->getStyle('A' . $row . ':F' . $row)->getFont()->setSize(12);
    $sheet->getStyle('A' . $row . ':F' . $row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    $sheet->getStyle('A' . $row . ':F' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    $sheet->getStyle('F' . $row)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);

    $sheet->setCellValue('A' . $row, $uuid);
    $sheet->setCellValue('B' . $row, $rfcemisor);
    $sheet->setCellValue('C' . $row, $nombreemisor);
    $sheet->setCellValue('D' . $row, $rfcreceptor);
    $sheet->setCellValue('E' . $row, $fechaemision.' '.$horaemision);
    $sheet->setCellValue('F' . $row, number_format($monto, 2, '.', ''));

    $row++; //<--Incremento de la variable row por cada fila generada
}

$writer = new Xlsx($spreadsheet);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="reporteIVA.xlsx"');
$writer->save('php://output');
exit();
?>