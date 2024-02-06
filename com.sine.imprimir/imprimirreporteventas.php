<?php

$fechainicio = $_GET['fechainicio'];
$fechafin = $_GET['fechafin'];
$idcliente = $_GET['idcliente'];
$estado = $_GET['estado'];
$datos = $_GET['datos'];
$usuario = $_GET['usuario'];

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

$controlador_reporte = new ControladorReportes();
$r = new Reportes();
$r->setFechainicio($fechainicio);
$r->setFechafin($fechafin);
$r->setIdcliente($idcliente);
$r->setEstado($estado);
$r->setDatos($datos);
$r->setUsuario($usuario);

$divideFI = explode("-", $fechainicio);
$fechainicio2 = $divideFI[2] . '/' . $divideFI[1] . '/' . $divideFI[0];

$divideFF = explode("-", $fechafin);
$fechafin2 = $divideFF[2] . '/' . $divideFF[1] . '/' . $divideFF[0];

$datosreporte = $controlador_reporte->getReporteVentas($r);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setTitle('Reporte');

$sheet->mergeCells('A2:F2');
$sheet->getStyle('A2:A3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($rgbc[1]);
$sheet->getStyle('A2:A3')->getFont()->setSize(14);
$sheet->getStyle('A2:A3')->getFont()->setBold(true);
$sheet->getStyle('A2:A3')->getFont()->getColor()->setARGB($rgbt[1]);
$sheet->getStyle('A2:A3')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
$sheet->getRowDimension('2')->setRowHeight(75);
$sheet->setCellValue('A2', utf8_decode($titulo . "\r" . $pagina . " " . $correo . " " . $telefono1 . " " . $telefono2));

$sheet->mergeCells('A3:G3');
$sheet->setCellValue('A3', 'Ventas generadas en el periodo : ' . $fechainicio2 . ' al ' . $fechafin2);

$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
$drawing->setName('Logo');
$drawing->setDescription('Logo');
$drawing->setPath("../img/logo/$logo[0]");
$drawing->setHeight(100);
$drawing->setWorksheet($spreadsheet->getActiveSheet());
$drawing->setCoordinates('G2');

$sheet->getStyle('A5:G5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($rgbht[1]);
$sheet->getStyle('A5:G5')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
$sheet->getStyle('A5:G5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A5:G5')->getAlignment()->setWrapText(true);
$sheet->getStyle('A5:G5')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('A5:G5')->getFont()->setSize(12);
$sheet->getStyle('A5:G5')->getFont()->setBold(true);
$sheet->getStyle('A5:G5')->getFont()->getColor()->setARGB($rgbtt[1]);

$sheet->getColumnDimension('A')->setWidth(20);
$sheet->getColumnDimension('B')->setWidth(15);
$sheet->getColumnDimension('C')->setWidth(30);
$sheet->getColumnDimension('D')->setWidth(18);
$sheet->getColumnDimension('E')->setWidth(20);
$sheet->getColumnDimension('F')->setWidth(30);
$sheet->getColumnDimension('G')->setWidth(18);
$sheet->getColumnDimension('I')->setWidth(25);
$sheet->getColumnDimension('J')->setWidth(28);
$sheet->getColumnDimension('K')->setWidth(15);
$sheet->getColumnDimension('L')->setWidth(15);

$sheet->setCellValue('A5', 'Folio Cotizacion');
$sheet->setCellValue('B5', 'Fecha Creacion');
$sheet->setCellValue('C5', 'Realizo');
$sheet->setCellValue('D5', 'Total Cotizacion');
$sheet->setCellValue('E5', 'Folio Factura');
$sheet->setCellValue('F5', 'Cliente');
$sheet->setCellValue('G5', 'Total Factura');

$row = 6; //<--Variable que autoincrementa segun el numero de filas que genere la consulta, su valor es el numero de celda en que comenzara a mostrar los resultados

foreach ($datosreporte as $reporteactual) {
    $idcotizacion = $reporteactual['iddatos_cotizacion'];
    $folio = $reporteactual['letra'] . $reporteactual['foliocotizacion'];
    $fecha = $reporteactual['fecha_creacion'];
    $realizo = $reporteactual['documento'];
    $totalcot = $reporteactual['totalcotizacion'];
    $idfactura = $reporteactual['expfactura'];
    $foliofactura = $reporteactual['foliofactura'];
    $cliente = $reporteactual['razon_social'];
    $totalfactura = $reporteactual['totalfactura'];

    $divideF = explode("-", $fecha);
    $fecha = $divideF[2] . '/' . $divideF[1] . '/' . $divideF[0];

    $sheet->getStyle('A' . $row . ':G' . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $sheet->getStyle('A' . $row . ':G' . $row)->getAlignment()->setWrapText(true);
    $sheet->getStyle('A' . $row . ':G' . $row)->getFont()->setSize(12);
    $sheet->getStyle('A' . $row . ':G' . $row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    $sheet->getStyle('A' . $row . ':G' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('D' . $row)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
    $sheet->getStyle('G' . $row)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);

    $sheet->setCellValue('A' . $row, $folio);
    $sheet->setCellValue('B' . $row, $fecha);
    $sheet->setCellValue('C' . $row, utf8_decode($realizo));
    $sheet->setCellValue('D' . $row, number_format($totalcot, 2, '.', ''));
    $sheet->setCellValue('E' . $row, $foliofactura);
    $sheet->setCellValue('F' . $row, $cliente);
    $sheet->setCellValue('G' . $row, number_format($totalfactura, 2, '.', ''));

    $row++; //<--Incremento de la variable row por cada fila generada
}

$sheet->mergeCells('I4:L4');
$sheet->getStyle('I4:L5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($rgbht[1]);
$sheet->getStyle('I4:L5')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
$sheet->getStyle('I4:L5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('I4:L5')->getAlignment()->setWrapText(true);
$sheet->getStyle('I4:L5')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('I4:L5')->getFont()->setSize(12);
$sheet->getStyle('I4:L5')->getFont()->setBold(true);
$sheet->getStyle('I4:L5')->getFont()->getColor()->setARGB($rgbt[1]);

$sheet->setCellValue('I4', 'Ventas Individuales');
$sheet->setCellValue('I5', 'Vendedor');
$sheet->setCellValue('J5', 'Comision');
$sheet->setCellValue('K5', 'Total Ventas');
$sheet->setCellValue('L5', 'Comision Total');

$row = 6;
$usuarios = $controlador_reporte->getUsuariosVentas($r);
foreach ($usuarios as $actual) {
    $idusuario = $actual['idusuario'];
    $nombre = $actual['nombre'] . " " . $actual['apellido_paterno'] . " " . $actual['apellido_materno'];
    $comision = $actual['comisionporcentaje'];
    if ($actual['calculo'] == '1') {
        $calculo = "antes de impuestos";
    } else if ($actual['calculo'] == '2') {
        $calculo = "despues de impuestos";
    }
    $total = $controlador_reporte->getTotalVentas($r, $idusuario, $actual['calculo']);
    $totalcom = $total * ($comision / 100);
    if ($totalcom > 0) {
        $sheet->getStyle('I' . $row . ':L' . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle('I' . $row . ':L' . $row)->getAlignment()->setWrapText(true);
        $sheet->getStyle('I' . $row . ':L' . $row)->getFont()->setSize(12);
        $sheet->getStyle('I' . $row . ':L' . $row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('I' . $row . ':L' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('K' . $row)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
        $sheet->getStyle('L' . $row)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);

        $sheet->setCellValue('I' . $row, $nombre);
        $sheet->setCellValue('J' . $row, utf8_decode("$comision% $calculo"));
        $sheet->setCellValue('K' . $row, number_format($total, 2, '.', ''));
        $sheet->setCellValue('L' . $row, number_format($totalcom, 2, '.', ''));
        $row++;
    }
}

$writer = new Xlsx($spreadsheet);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="reporte_' . $fechainicio . '_' . $fechafin . '.xlsx"');
$writer->save('php://output');
exit();
?>