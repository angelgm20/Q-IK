<?php

require_once '../com.sine.modelo/Contrato.php';
require_once '../com.sine.modelo/TMPContrato.php';
require_once '../com.sine.controlador/ControladorContrato.php';
require_once '../vendor/autoload.php';
require '../pdf/fpdf.php';

class PDF extends FPDF {

    var $widths;
    var $aligns;
    var $lineHeight;
    var $Tfolio;
    var $iddatos;
    var $chfirmar;
    var $firma;
    var $isFinished;
    var $tipofactura;
    var $heightB = 0;
    var $ycliente;

    function SetWidths($w) {
        $this->widths = $w;
    }

    function SetAligns($a) {
        $this->aligns = $a;
    }

    function SetLineHeight($h) {
        $this->lineHeight = $h;
    }

    function Row($data) {
        // number of line
        $nb = 0;
        // loop each data to find out greatest line number in a row.
        for ($i = 0; $i < count($data); $i++) {
            // NbLines will calculate how many lines needed to display text wrapped in specified width.
            // then max function will compare the result with current $nb. Returning the greatest one. And reassign the $nb.
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        //multiply number of line with line height. This will be the height of current row
        $h = $this->lineHeight * $nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of current row
        for ($i = 0; $i < count($data); $i++) {
            // width of the current col
            $w = $this->widths[$i];
            // alignment of the current col. if unset, make it left.
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            $this->Rect($x, $y, $w, $h);
            //Print the text
            $this->MultiCell($w, 4.5, $data[$i], 0, $a);
            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
        //Go to the next line
        $this->Ln($h);
    }

    function RowT($data) {
        // number of line
        $nb = 0;
        // loop each data to find out greatest line number in a row.
        for ($i = 0; $i < count($data); $i++) {
            // NbLines will calculate how many lines needed to display text wrapped in specified width.
            // then max function will compare the result with current $nb. Returning the greatest one. And reassign the $nb.
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        //multiply number of line with line height. This will be the height of current row
        $h = $this->lineHeight * $nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of current row
        for ($i = 0; $i < count($data); $i++) {
            // width of the current col
            $w = $this->widths[$i];
            // alignment of the current col. if unset, make it left.
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            $this->RoundedRect($x, $y, $w, $h, 4, 'F');
            //$this->Rect($x, $y, $w, $h, 'F');
            //Print the text
            $this->MultiCell($w, $h, $data[$i], 0, $a);
            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
        //Go to the next line
        $this->Ln($h);
    }

    function RowC($data) {
        // number of line
        $nb = 0;
        // loop each data to find out greatest line number in a row.
        for ($i = 0; $i < count($data); $i++) {
            // NbLines will calculate how many lines needed to display text wrapped in specified width.
            // then max function will compare the result with current $nb. Returning the greatest one. And reassign the $nb.
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        //multiply number of line with line height. This will be the height of current row
        $lh = $this->lineHeight;
        $h = $lh * $nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of current row
        for ($i = 0; $i < count($data); $i++) {
            // width of the current col
            $w = $this->widths[$i];
            // alignment of the current col. if unset, make it left.
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            $this->Rect($x, $y, $w, $h, 'F');
            //Print the text
            $this->MultiCell($w, $lh, $data[$i], 0, $a);
            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
        //Go to the next line
        $this->Ln($h);
    }

    function RowNBCount($data) {
        // number of line
        $nb = 0;
        // loop each data to find out greatest line number in a row.
        for ($i = 0; $i < count($data); $i++) {
            // NbLines will calculate how many lines needed to display text wrapped in specified width.
            // then max function will compare the result with current $nb. Returning the greatest one. And reassign the $nb.
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        //multiply number of line with line height. This will be the height of current row
        $h = $this->lineHeight * $nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of current row
        for ($i = 0; $i < count($data); $i++) {
            // width of the current col
            $w = $this->widths[$i];
            // alignment of the current col. if unset, make it left.
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            //$this->RoundedRect($x, $y, $w, $h, 2, 'F');
            //$this->Rect($x, $y, $w, $h);
            //Print the text
            $this->MultiCell($w, 4.5, $data[$i], 0, $a);
            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
        //Go to the next line
        $this->Ln($h);
        $this->heightB += $h;
    }

    function RowNBTitle($data) {
        // number of line
        $nb = 0;
        // loop each data to find out greatest line number in a row.
        for ($i = 0; $i < count($data); $i++) {
            // NbLines will calculate how many lines needed to display text wrapped in specified width.
            // then max function will compare the result with current $nb. Returning the greatest one. And reassign the $nb.
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        //multiply number of line with line height. This will be the height of current row
        $lh = $this->lineHeight;
        $h = $lh * $nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of current row
        for ($i = 0; $i < count($data); $i++) {
            // width of the current col
            $w = $this->widths[$i];
            // alignment of the current col. if unset, make it left.
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            //$this->RoundedRect($x, $y, $w, $h, 2, 'F');
            //$this->Rect($x, $y, $w, $h);
            //Print the text
            $this->MultiCell($w, $lh, $data[$i], 0, $a);
            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
        //Go to the next line
        $this->Ln($h);
    }

    function RowNB($data) {
        // number of line
        $nb = 0;
        // loop each data to find out greatest line number in a row.
        for ($i = 0; $i < count($data); $i++) {
            // NbLines will calculate how many lines needed to display text wrapped in specified width.
            // then max function will compare the result with current $nb. Returning the greatest one. And reassign the $nb.
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        //multiply number of line with line height. This will be the height of current row
        $lh = $this->lineHeight;
        $h = $lh * $nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of current row
        for ($i = 0; $i < count($data); $i++) {
            // width of the current col
            $w = $this->widths[$i];
            // alignment of the current col. if unset, make it left.
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            //$this->RoundedRect($x, $y, $w, $h, 2, 'F');
            //$this->Rect($x, $y, $w, $h);
            //Print the text
            $this->MultiCell($w, $lh, $data[$i], 0, $a);
            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
        //Go to the next line
        $this->Ln($h);
    }

    function RowNBC($data) {
        // number of line
        $nb = 0;
        // loop each data to find out greatest line number in a row.
        for ($i = 0; $i < count($data); $i++) {
            // NbLines will calculate how many lines needed to display text wrapped in specified width.
            // then max function will compare the result with current $nb. Returning the greatest one. And reassign the $nb.
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        //multiply number of line with line height. This will be the height of current row
        $h = $this->lineHeight * $nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of current row
        for ($i = 0; $i < count($data); $i++) {
            // width of the current col
            $w = $this->widths[$i];
            // alignment of the current col. if unset, make it left.
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            //$this->Rect($x, $y, $w, $h);
            //Print the text
            $this->MultiCell($w, 4.5, $data[$i], 0, $a);
            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
        //Go to the next line
        $this->Ln($h);
    }

    function RowRTitle($data) {
        // number of line
        $nb = 0;
        // loop each data to find out greatest line number in a row.
        for ($i = 0; $i < count($data); $i++) {
            // NbLines will calculate how many lines needed to display text wrapped in specified width.
            // then max function will compare the result with current $nb. Returning the greatest one. And reassign the $nb.
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        //multiply number of line with line height. This will be the height of current row
        $h = $this->lineHeight * $nb;
        $h2 = $this->lineHeight;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of current row
        for ($i = 0; $i < count($data); $i++) {
            // width of the current col
            $w = $this->widths[$i];
            // alignment of the current col. if unset, make it left.
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            //$this->Rect($x, $y, $w, $h);
            $this->RoundedRect($x, $y, $w, $h, 4, 'F');
            //Print the text
            $this->MultiCell($w, $h2, $data[$i], 0, $a);
            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
        //Go to the next line
        $this->Ln($h);
    }

    function RowR($data) {
        // number of line
        $nb = 0;
        // loop each data to find out greatest line number in a row.
        for ($i = 0; $i < count($data); $i++) {
            // NbLines will calculate how many lines needed to display text wrapped in specified width.
            // then max function will compare the result with current $nb. Returning the greatest one. And reassign the $nb.
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        //multiply number of line with line height. This will be the height of current row
        $h = $this->lineHeight * $nb;
        $h2 = $this->lineHeight;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of current row
        for ($i = 0; $i < count($data); $i++) {
            // width of the current col
            $w = $this->widths[$i];
            // alignment of the current col. if unset, make it left.
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            //$this->Rect($x, $y, $w, $h);
            $this->RoundedRect($x, $y, $w, $h, 2, 'D');
            //Print the text
            $this->MultiCell($w, $h2, $data[$i], 0, $a);
            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
        //Go to the next line
        $this->Ln($h);
    }

    function CheckPageBreak($h) {
        if ($this->GetY() + $h > $this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }

    function NbLines($w, $txt) {
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 and $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                } else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else
                $i++;
        }
        return $nl;
    }

    function RoundedRect($x, $y, $w, $h, $r, $style = '', $angle = '1234') {
        $k = $this->k;
        $hp = $this->h;
        if ($style == 'F')
            $op = 'f';
        elseif ($style == 'FD' or $style == 'DF')
            $op = 'B';
        else
            $op = 'S';
        $MyArc = 4 / 3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2f %.2f m', ($x + $r) * $k, ($hp - $y) * $k));

        $xc = $x + $w - $r;
        $yc = $y + $r;
        $this->_out(sprintf('%.2f %.2f l', $xc * $k, ($hp - $y) * $k));
        if (strpos($angle, '2') === false)
            $this->_out(sprintf('%.2f %.2f l', ($x + $w) * $k, ($hp - $y) * $k));
        else
            $this->_Arc($xc + $r * $MyArc, $yc - $r, $xc + $r, $yc - $r * $MyArc, $xc + $r, $yc);

        $xc = $x + $w - $r;
        $yc = $y + $h - $r;
        $this->_out(sprintf('%.2f %.2f l', ($x + $w) * $k, ($hp - $yc) * $k));
        if (strpos($angle, '3') === false)
            $this->_out(sprintf('%.2f %.2f l', ($x + $w) * $k, ($hp - ($y + $h)) * $k));
        else
            $this->_Arc($xc + $r, $yc + $r * $MyArc, $xc + $r * $MyArc, $yc + $r, $xc, $yc + $r);

        $xc = $x + $r;
        $yc = $y + $h - $r;
        $this->_out(sprintf('%.2f %.2f l', $xc * $k, ($hp - ($y + $h)) * $k));
        if (strpos($angle, '4') === false)
            $this->_out(sprintf('%.2f %.2f l', ($x) * $k, ($hp - ($y + $h)) * $k));
        else
            $this->_Arc($xc - $r * $MyArc, $yc + $r, $xc - $r, $yc + $r * $MyArc, $xc - $r, $yc);

        $xc = $x + $r;
        $yc = $y + $r;
        $this->_out(sprintf('%.2f %.2f l', ($x) * $k, ($hp - $yc) * $k));
        if (strpos($angle, '1') === false) {
            $this->_out(sprintf('%.2f %.2f l', ($x) * $k, ($hp - $y) * $k));
            $this->_out(sprintf('%.2f %.2f l', ($x + $r) * $k, ($hp - $y) * $k));
        } else
            $this->_Arc($xc - $r, $yc - $r * $MyArc, $xc - $r * $MyArc, $yc - $r, $xc, $yc - $r);
        $this->_out($op);
    }

    function _Arc($x1, $y1, $x2, $y2, $x3, $y3) {
        $h = $this->h;
        $this->_out(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c ', $x1 * $this->k, ($h - $y1) * $this->k, $x2 * $this->k, ($h - $y2) * $this->k, $x3 * $this->k, ($h - $y3) * $this->k));
    }

    function Header() {
        require_once '../com.sine.controlador/ControladorConfiguracion.php';
        $cc = new ControladorConfiguracion();
        $encabezado = $cc->getDatosEncabezado('1');
        foreach ($encabezado as $actual) {
            $titulo = $actual['tituloencabezado'];
            $colortitulo = $actual['colortitulo'];
            $rgbt = explode("-", $cc->hex2rgb($colortitulo));
            $colorcuadro = $actual['colorceltitulo'];
            $rgbc = explode("-", $cc->hex2rgb($colorcuadro));
            $imglogo = $actual['imglogo'];
        }

        $this->SetFont('Arial', '', 19);
        $this->SetFillColor($rgbc[0], $rgbc[1], $rgbc[2]);
        $this->SetTextColor($rgbt[0], $rgbt[1], $rgbt[2]);
        $logo = "../img/logo/$imglogo";
        $dimensiones = getimagesize($logo);
        $width = $dimensiones[0];
        $height = $dimensiones[1];
        $height = ($height * 20) / $width;
        if ($height > 25) {
            $height = 25;
        }
        $this->Cell(25, 5, $this->Image($logo, $this->GetX() + 2.5, $this->GetY(), 20, $height), 0, 0, 'C', false);
        $this->RoundedRect(35, $this->GetY(), 120, 8, 4, 'F');
        $this->SetX(38);
        $this->Write(8, $titulo);

        $this->SetX(160);
        $this->RoundedRect(160, $this->GetY(), 45, 8, 4, 'F');
        $this->SetX(173.5);
        $this->Write(8, 'Folio');

        $this->SetY(18);
        $this->SetX(160);
        $this->SetTextColor(255, 0, 0);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(45, 8, utf8_decode($this->Tfolio), 0, 0, 'C', false);

        $this->Ln(25);
    }

    function Footer() {
        require_once '../com.sine.controlador/ControladorConfiguracion.php';
        $cc = new ControladorConfiguracion();
        $encabezado = $cc->getDatosEncabezado('1');
        foreach ($encabezado as $actual) {
            $pagina = $actual['pagina'];
            $correo = $actual['correo'];
            $telefono1 = $actual['telefono1'];
            $telefono2 = $actual['telefono2'];
            $chnum = $actual['numpag'];
            $colorpie = $actual['colorpie'];
            $rgb = explode("-", $cc->hex2rgb($colorpie));
        }
        $pagin = "";
        if ($chnum == '1') {
            $pagin = utf8_decode('Pagina ' . $this->PageNo() . ' de {nb}');
        }
        $this->SetY(-18);
        if ($this->isFinished) {
            if ($this->chfirmar == '1') {
                $this->Image($this->firma, 75, ($this->GetY() - 25), 60, 0, 'png');
            }
        }
        $this->SetTextColor($rgb[0], $rgb[1], $rgb[2]);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(65, 4, $pagina, 0, 0, 'L');
        $phone = "Tel: " . $telefono1;
        if ($telefono2 != "") {
            $this->Cell(65, 4, '', 0, 0, 'C');
            $this->Cell(65, 4, "Tel: " . $telefono1, 0, 0, 'R');
            $phone = "Tel: " . $telefono2;
        }
        $this->Ln(4);
        $this->Cell(65, 4, $correo, 0, 0, 'L');
        $this->Cell(65, 4, $pagin, 0, 0, 'C');
        $this->Cell(65, 4, $phone, 0, 0, 'R');
    }

    function myCell($w, $h, $x, $t) {
        $height = $h / 3;
        $first = $height + 2;
        $second = $height + $height + $height + 3;
        $len = strlen($t);
        if ($len > 15) {
            $txt = str_split($t, 14);
            $this->SetX($x);
            $this->Cell($w, $first, $txt[0], '', '', 'C');
            $this->SetX($x);
            $this->Cell($w, $second, $txt[1], '', '', 'C');
            $this->SetX($x);
            $this->Cell($w, $h, '', 'LTRB', 0, 'L', 0);
        } else {
            $this->SetX($x);
            $this->Cell($w, $h, $t, 'LTRB', 0, 'C', 0);
        }
    }

    function myCellD($w, $h, $x, $t) {
        $height = $h / 3;
        $first = $height + 2;
        $second = $height + $height + $height + 3;
        $len = strlen($t);
        if ($len > 30) {
            $txt = str_split($t, 30);
            $this->SetX($x);
            $this->Cell($w, $first, $txt[0], '', '', 'C');
            $this->SetX($x);
            $this->Cell($w, $second, $txt[1], '', '', 'C');
            $this->SetX($x);
            $this->Cell($w, $h, '', 'LTRB', 0, 'L', 0);
        } else {
            $this->SetX($x);
            $this->Cell($w, $h, $t, 'LTRB', 0, 'C', 0);
        }
    }

}

setlocale(LC_MONETARY, 'es_MX.UTF-8');

$cc = new ControladorContrato();
$c = new Contrato();
$idcontrato = intval($_GET['idcontrato']);
$contratos = $cc->getContratoById($idcontrato);
foreach ($contratos as $contratoactual) {
    $idcontrato = $contratoactual['idcontrato'];
    $tag = $contratoactual['tagcontrato'];
    $fecha_creacion = $contratoactual['fecha_contrato'];
    $fechafacturacion = $contratoactual['fecha_facturacion'];
    $idcliente = $contratoactual['idclientecontrato'];
    $apaterno = $contratoactual['apaterno'];
    $amaterno = $contratoactual['amaterno'];
    $nombre = $contratoactual['nombre_empresa'];
    $c_formapago = $contratoactual['c_pago'];
    $formapago = $contratoactual['descripcion_pago'];
    $c_metodopago = $contratoactual['c_metodopago'];
    $metodopago = $contratoactual['descripcion_metodopago'];
    $c_moneda = $contratoactual['c_moneda'];
    $moneda = $contratoactual['descripcion_moneda'];
    $c_usocfdi = $contratoactual['c_usocfdi'];
    $usocfdi = $contratoactual['descripcion_cfdi'];
    $subtotal = $contratoactual['subtotal'];
    $subiva = $contratoactual['subtotaliva'];
    $subret = $contratoactual['subtotalret'];
    $totaldescuentos = $contratoactual['totdescuentos'];
    $totalcontrato = $contratoactual['totalcontrato'];
    $iddatosfacturacion = $contratoactual['iddatosfacturacion'];
    $divideF = explode("-", $fecha_creacion);
    $fecha_creacion = $divideF[2] . '/' . $divideF[1] . '/' . $divideF[0];
}
$sine = $cc->getDatosFacturacionbyId($iddatosfacturacion);
$cliente = $cc->getDatosCliente($idcliente);

require_once '../com.sine.controlador/ControladorConfiguracion.php';
$cconf = new ControladorConfiguracion();
$encabezado = $cconf->getDatosEncabezado('1');
foreach ($encabezado as $actual) {
    $colorcuadro = $actual['colorcuadro'];
    $rgbc = explode("-", $cconf->hex2rgb($colorcuadro));
    $colorsubtitulos = $actual['colorsubtitulos'];
    $rgbs = explode("-", $cconf->hex2rgb($colorsubtitulos));
    $colorhtabla = $actual['colorhtabla'];
    $rgbt = explode("-", $cconf->hex2rgb($colorhtabla));
    $colortittabla = $actual['colortittabla'];
    $rgbtt = explode("-", $cconf->hex2rgb($colortittabla));
}

$pdf = new PDF('P', 'mm', 'Letter');
if ($idcontrato < 10) {
    $nocontrato = "00$idcontrato";
} else if ($idcontrato < 100 && $idcontrato >= 10) {
    $nocontrato = "0$idcontrato";
}
$pdf->Tfolio = $nocontrato;
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetFont('Arial', '', 15);
$pdf->SetFillColor($rgbc[0], $rgbc[1], $rgbc[2]);
$pdf->SetTextColor($rgbs[0], $rgbs[1], $rgbs[2]);

$pdf->SetWidths(Array(80));
$pdf->SetLineHeight(8);
$pdf->SetY(36.3);
$pdf->RowT(Array("Datos del Emisor"));
$pdf->Ln(1);

foreach ($sine as $sineactual) {
    $nombre = $sineactual['nombre_contribuyente'];
    $rfc = $sineactual['rfc'];
    $razonsocial = $sineactual['razon_social'];
    $regimen = $sineactual['c_regimenfiscal'] . ' ' . $sineactual['regimen_fiscal'];
    $cp = $sineactual['codigo_postal'];
    $numcertificado = $sineactual['numcsd'];
    $pdf->firma = $sineactual['firma'];

    $pdf->SetWidths(Array(15, 94.5, 25, 60));
    $pdf->SetLineHeight(4.5);

    $pdf->SetFont('Arial', '', 9);
    $pdf->SetX(10);
    $pdf->RowNBCount(Array('', utf8_decode($razonsocial), '', utf8_decode($numcertificado)));

    $pdf->SetFont('Arial', '', 9);
    $pdf->SetX(10);
    $pdf->RowNBCount(Array('', utf8_decode($rfc), '', utf8_decode($regimen)));

    $pdf->SetFont('Arial', '', 9);
    $pdf->SetX(10);
    $pdf->RowNBCount(Array('', utf8_decode($fecha_creacion), '', utf8_decode('I Ingreso')));

    $pdf->SetFont('Arial', '', 9);
    $pdf->SetWidths(Array(32, 77.5, 25, 60));
    $pdf->SetX(10);
    $pdf->RowNBCount(Array('', utf8_decode($cp), '', ''));
}

$heightdatos = $pdf->heightB;

$pdf->SetFillColor(216, 216, 216);
$pdf->RoundedRect(10, 45.3, 195, $heightdatos, 2, 'F');
$pdf->SetWidths(Array(15, 94.5, 25, 60));
$pdf->SetLineHeight(4.5);

$pdf->SetY(45.3);
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetTextColor(23, 23, 124);
$pdf->Write(5, 'Nombre');
$pdf->SetX(119.5);
$pdf->Write(5, 'No Certificado');
$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetX(10);
$pdf->RowNB(Array('', utf8_decode($razonsocial), '', utf8_decode($numcertificado)));

$pdf->SetFont('Arial', 'B', 9);
$pdf->SetTextColor(23, 23, 124);
$pdf->Write(5, 'RFC');
$pdf->SetX(119.5);
$pdf->Write(5, 'Regimen Fiscal');
$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetX(10);
$pdf->RowNB(Array('', utf8_decode($rfc), '', utf8_decode($regimen)));


$pdf->SetFont('Arial', 'B', 9);
$pdf->SetTextColor(23, 23, 124);
$pdf->Write(5, 'Fecha');
$pdf->SetX(119.5);
$pdf->Write(5, 'T. Comprobante');
$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetX(10);
$pdf->RowNB(Array('', utf8_decode($fecha_creacion), '', utf8_decode('I Ingreso')));

$pdf->SetFont('Arial', 'B', 9);
$pdf->SetWidths(Array(32, 77.5, 25, 60));
$pdf->SetTextColor(23, 23, 124);
$pdf->Write(5, 'Lugar de Expedicion');
$pdf->SetX(119.5);
$pdf->Write(5, '');
$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetX(10);
$pdf->RowNB(Array('', utf8_decode($cp), '', ''));

$pdf->heightB = 0;

$pdf->SetFillColor($rgbc[0], $rgbc[1], $rgbc[2]);
$pdf->SetTextColor($rgbs[0], $rgbs[1], $rgbs[2]);
$pdf->SetFont('Arial', '', 15);
$pdf->SetWidths(Array(80));
$pdf->SetLineHeight(8);
$pdf->RowT(Array("Datos del Cliente"));
foreach ($cliente as $clienteactual) {
    $nombreCliente = $clienteactual['razon_social'];
    $rfccliente = $clienteactual['rfc'];
    $calle = $clienteactual['calle'];
    $num = $clienteactual['numero_exterior'];
    $col = $clienteactual['localidad'];
    $idmunicipio = $clienteactual['idmunicipio'];
    $idestadodir = $clienteactual['idestado'];
    $cpcliente = $clienteactual['codigo_postal'];
    
    $direccion = $cc->getDireccionCliente($calle, $num, $col, $cpcliente, $idmunicipio, $idestadodir);

    $pdf->SetFont('Arial', '', 9);

    $pdf->ycliente = $pdf->GetY();

    $pdf->SetWidths(Array(17, 94.5, 23, 60));
    $pdf->SetLineHeight(4.5);

    $pdf->SetFont('Arial', '', 9);
    $pdf->SetX(10);
    $pdf->RowNBCount(Array('', utf8_decode($nombreCliente), '', utf8_decode($c_usocfdi . ' ' . $usocfdi)));

    $pdf->SetFont('Arial', '', 9);
    $pdf->SetX(10);
    $pdf->RowNBCount(Array('', utf8_decode($rfccliente), '', ''));

    $pdf->SetFont('Arial', '', 9);
    $pdf->SetWidths(Array(17, 174.5));
    $pdf->SetX(10);
    $pdf->RowNBCount(Array('', utf8_decode($direccion)));
}

$heightcliente = $pdf->heightB;

$pdf->SetFillColor(216, 216, 216);
$pdf->RoundedRect(10, ($pdf->ycliente + 0.7), 195, $heightcliente, 2, 'F');

$pdf->SetY(($pdf->ycliente + 0.7));
$pdf->SetWidths(Array(17, 94.5, 23, 60));
$pdf->SetLineHeight(4.5);

$pdf->SetFont('Arial', 'B', 9);
$pdf->SetTextColor(23, 23, 124);
$pdf->Write(5, 'Nombre');
$pdf->SetX(119.5);
$pdf->Write(5, 'Uso de CFDI');
$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetX(10);
$pdf->RowNB(Array('', utf8_decode($nombreCliente), '', utf8_decode($c_usocfdi . ' ' . $usocfdi)));

$pdf->SetFont('Arial', 'B', 9);
$pdf->SetTextColor(23, 23, 124);
$pdf->Write(5, 'RFC');
$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetX(10);
$pdf->RowNB(Array('', utf8_decode($rfccliente), '', ''));

$pdf->SetFont('Arial', 'B', 9);
$pdf->SetTextColor(23, 23, 124);
$pdf->Write(5, 'Direccion');
$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetWidths(Array(17, 174.5));
$pdf->SetLineHeight(4.5);
$pdf->SetX(10);
$pdf->RowNB(Array('', utf8_decode($direccion)));
$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(23, 23, 124);
$pdf->RoundedRect(10, $pdf->GetY(), 195, 5.5, 2, 'D');
$pdf->SetX(92.5);
$pdf->Write(6, 'CONCEPTOS');
$pdf->Ln(7);

$pdf->SetFont('Arial', '', 9);
$pdf->SetFillColor($rgbt[0], $rgbt[1], $rgbt[2]);
$pdf->SetTextColor($rgbtt[0], $rgbtt[1], $rgbtt[2]);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(15, 6, 'Cantidad', 1, 0, 'C', 1);
$pdf->Cell(20, 6, 'Clave Fiscal', 1, 0, 'C', 1);
$pdf->Cell(25, 6, 'Unidad', 1, 0, 'C', 1);
$pdf->Cell(46, 6, 'Descripcion', 1, 0, 'C', 1);
$pdf->Cell(45, 6, 'Observaciones', 1, 0, 'C', 1);
$pdf->Cell(22, 6, 'Precio', 1, 0, 'C', 1);
$pdf->Cell(22, 6, 'Importe', 1, 0, 'C', 1);
$pdf->Ln(6);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetWidths(Array(15, 20, 25, 46, 45, 22, 22));
$pdf->SetLineHeight(4.5);

$detallecontrato = $cc->getDetalleContrato($tag);
$iva = 0;
$ieps = 0;
$retiva = 0;
$retieps = 0;
$isr = 0;
foreach ($detallecontrato as $detalleactual) {
    $pdf->SetFont('Arial', '', 9);
    $claveFiscal = $detalleactual['clave_fiscal'];
    $precioV = $detalleactual['precio'];
    $cantidad = $detalleactual['cantidad'];
    $unidad = $detalleactual['clv_unidad'] . " " . $detalleactual['desc_unidad'];
    $descripcion = $detalleactual['nombre_producto'];
    $totalu = $detalleactual['totalunitario'];
    $traslados = $detalleactual['traslados'];
    $retenciones = $detalleactual['retenciones'];
    $observacionesprod = $detalleactual['observacionesprod'];

    if ($traslados != "") {
        $divT = explode("<impuesto>", $traslados);
        foreach ($divT as $tactual) {
            $impuestos = $tactual;
            $div = explode("-", $impuestos);
            if ($div[2] == '2') {
                $iva += (bcdiv($div[0], '1', 2));
            } else if ($div[2] == '3') {
                $ieps += (bcdiv($div[0], '1', 2));
            }
        }
    }

    if ($retenciones != "") {
        $divR = explode("<impuesto>", $retenciones);
        foreach ($divR as $ractual) {
            $impuestos = $ractual;
            $div = explode("-", $impuestos);
            if ($div[2] == '1') {
                $isr += (bcdiv($div[0], '1', 2));
            } else if ($div[2] == '2') {
                $retiva += (bcdiv($div[0], '1', 2));
            } else if ($div[2] == '3') {
                $retieps += (bcdiv($div[0], '1', 2));
            }
        }
    }

    $pdf->Row(Array($cantidad, $claveFiscal, $unidad, utf8_decode($descripcion), utf8_decode($observacionesprod), utf8_decode('$ ' . number_format($precioV, 2, '.', ',')), utf8_decode('$ ' . number_format($totalu, 2, '.', ','))));
}
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(30, 8, 'Moneda ', 0, 0, 'C', 0);
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(60, 8, utf8_decode($c_moneda), 0, 0, 'L', 0);
$pdf->Ln(8);

$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(30, 8, 'Forma de Pago ', 0, 0, 'C', 0);
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(60, 8, utf8_decode($c_formapago . ' ' . $formapago), 0, 0, 'L', 0);
$pdf->Ln(8);

$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(30, 8, 'Metodo de pago ', 0, 0, 'C', 0);
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(60, 8, utf8_decode($c_metodopago . ' ' . $metodopago), 0, 0, 'L', 0);
$pdf->Ln(10);

$pdf->Ln(-26);
$pdf->Cell(151, 8, '', 0, 0, 'C', 0);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(22, 8, 'Subtotal ', 1, 0, 'C', 0);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(22, 8, utf8_decode('$ ' . number_format($subtotal, 2, '.', ',')), 1, 0, 'C', 0);
$pdf->Ln(8);

if ($iva > 0) {
    $pdf->Cell(151, 8, '', 0, 0, 'C', 0);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(22, 8, 'IVA ', 1, 0, 'C', 0);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(22, 8, utf8_decode('$ ' . bcdiv($iva, '1', 2)), 1, 0, 'C', 0);
    $pdf->Ln(8);
}

if ($ieps > 0) {
    $pdf->Cell(151, 8, '', 0, 0, 'C', 0);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(22, 8, 'IEPS ', 1, 0, 'C', 0);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(22, 8, utf8_decode('$ ' . bcdiv($ieps, '1', 2)), 1, 0, 'C', 0);
    $pdf->Ln(8);
}

if ($isr > 0) {
    $pdf->Cell(151, 8, '', 0, 0, 'C', 0);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(22, 8, 'ISR ', 1, 0, 'C', 0);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(22, 8, utf8_decode('$ ' . bcdiv($isr, '1', 2)), 1, 0, 'C', 0);
    $pdf->Ln(8);
}

if ($retiva > 0) {
    $pdf->Cell(151, 8, '', 0, 0, 'C', 0);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(22, 8, 'Retencion IVA ', 1, 0, 'C', 0);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(22, 8, utf8_decode('$ ' . bcdiv($retiva, '1', 2)), 1, 0, 'C', 0);
    $pdf->Ln(8);
}

if ($retieps > 0) {
    $pdf->Cell(151, 8, '', 0, 0, 'C', 0);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(22, 8, 'Retencion IEPS ', 1, 0, 'C', 0);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(22, 8, utf8_decode('$ ' . bcdiv($retieps, '1', 2)), 1, 0, 'C', 0);
    $pdf->Ln(8);
}

if ($totaldescuentos > 0) {
    $pdf->Cell(151, 8, '', 0, 0, 'C', 0);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(22, 8, 'Descuentos ', 1, 0, 'C', 0);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(22, 8, utf8_decode('$ ' . bcdiv($totaldescuentos, '1', 2)), 1, 0, 'C', 0);
    $pdf->Ln(8);
}

$pdf->Cell(151, 8, '', 0, 0, 'C', 0);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(22, 8, 'Total ', 1, 0, 'C', 0);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(22, 8, utf8_decode('$ ' . bcdiv($totalcontrato, '1', 2)), 1, 0, 'C', 0);
$pdf->Ln(13);

$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(30, 8, 'Importe con Letra ', 0, 0, 'C', 0);
$pdf->SetFont('Arial', '', 9);
$letras = NumeroALetras::convertir(bcdiv($totalcontrato, '1', 2), 'pesos', 'centavos');
$div = explode(".", bcdiv($totalcontrato, '1', 2));
$pdf->Cell(26, 8, utf8_decode("$letras $div[1]/100 M.N."), 0, 0, 'L', 0);
$pdf->Ln(8);

$pdf->SetFillColor($rgbc[0], $rgbc[1], $rgbc[2]);
$pdf->RoundedRect(10, $pdf->GetY(), 195, 3, 1.5, 'F');
$pdf->Ln(5);

$pdf->SetFont('Arial', 'BI', 8);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(10, 3, '*Ejemplo de Factura basada en el contrato, este documento no posee efectos fiscales ', 0, 0, 'L', 0);

$pdf->Output('factura' . $idcontrato . '.pdf', 'I');
