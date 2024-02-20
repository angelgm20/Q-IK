<?php

require_once '../com.sine.dao/Consultas.php';
require_once '../vendor/autoload.php';
require_once '../com.sine.modelo/Session.php';
require_once '../com.sine.modelo/Factura.php';
require_once '../com.sine.modelo/TMP.php';
require '../pdf/fpdf.php';

use SWServices\Toolkit\SignService as Sellar;
use SWServices\Stamp\StampService as StampService;

date_default_timezone_set("America/Mexico_City");

class PDF extends FPDF {

    var $widths;
    var $aligns;
    var $lineHeight;
    var $Tfolio;
    var $folder;
    var $rfc;
    var $firma;
    var $chfirmar;
    var $isFinished;
    var $tipofactura;
    var $heightB = 0;
    var $ycliente;
    var $rgbfd0;
    var $rgbfd1;
    var $rgbfd2;

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
        $ln = $this->lineHeight;
        $h = $ln * $nb;
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
            $this->MultiCell($w, $ln, $data[$i], 0, $a);
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
            if ($i == 1 || $i == 3) {
                $this->SetFillColor(255, 255, 255);
            } else {
                $this->SetFillColor($this->rgbfd0, $this->rgbfd1, $this->rgbfd2);
            }
            $this->RoundedRect($x, $y, $w, $h, 2, 'F');
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
        //calculate the number of lines a MultiCell of width w will take
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
        $folder = $this->folder;
        require_once $folder . 'cron/ControladorFacturaCron.php';
        $cc = new ControladorFacturaCron();
        $encabezado = $cc->getDatosEncabezado('1');
        foreach ($encabezado as $actual) {
            $titulo = $actual['tituloencabezado'];
            $colortitulo = $actual['colortitulo'];
            $rgbt = explode("-", $cc->hex2rgb($colortitulo));
            $colorcuadro = $actual['colorceltitulo'];
            $rgbc = explode("-", $cc->hex2rgb($colorcuadro));
            $imglogo = $actual['imglogo'];
        }

        if ($this->tipofactura == '2') {
            $titulo = "Nota de Remision";
        }

        $this->SetFont('Arial', '', 19);
        $this->SetFillColor($rgbc[0], $rgbc[1], $rgbc[2]);
        $this->SetTextColor($rgbt[0], $rgbt[1], $rgbt[2]);
        $logo = $folder . 'img/logo/' . $imglogo;
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

        $this->Ln(26);
    }

    function Footer() {
        require_once $this->folder . 'cron/ControladorFacturaCron.php';
        $cc = new ControladorFacturaCron();
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
            $chfirmar = $this->chfirmar;
            if ($chfirmar == '1') {
                $fileFirma = $this->folder . "temporal/" . $this->rfc . "/" . $this->firma;
                if (file_exists($fileFirma)) {
                    $this->Image($fileFirma, 75, ($this->GetY() - 25), 60);
                }
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

class ControladorCron {

    function __construct() {
        
    }

    private function getRFCFolder() {
        $archivo = "../cron/configuracion.ini";
        $ajustes = parse_ini_file($archivo, true);
        if (!$ajustes) {
            throw new Exception("No se puede abrir el archivo " . $archivo);
        }
        $rfcfolder = $ajustes['cron']['rfcfolder'];
        $mainfolder = "/home/q-ik/public_html/$rfcfolder/";
        return $mainfolder;
    }

    public function getDatosEncabezado($id) {
        $consultado = false;
        $consulta = "SELECT * FROM encabezados WHERE idencabezado=:id;";
        $consultas = new Consultas();
        $valores = array("id" => $id);
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    private function getFirmarAux($id) {
        $consultado = false;
        $consulta = "SELECT rfc, firma FROM datos_facturacion WHERE id_datos=:id;";
        $consultas = new Consultas();
        $valores = array("id" => $id);
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    public function getFirma($id) {
        $datos = "";
        $encabezado = $this->getFirmarAux($id);
        foreach ($encabezado as $actual) {
            $rfc = $actual['rfc'];
            $firma = $actual['firma'];
            $datos .= "$rfc</tr>$firma";
        }
        return $datos;
    }

    public function hex2rgb($hex) {
        $hex = str_replace("#", "", $hex);
        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        return "$r-$g-$b";
    }

    private function getReceptorAux($id) {
        $datos = false;
        $con = new Consultas();
        $consulta = "SELECT * FROM cliente WHERE id_cliente=:id";
        $val = array("id" => $id);
        $datos = $con->getResults($consulta, $val);
        return $datos;
    }

    private function getDatosReceptor($cid) {
        $datos = "";
        $sine = $this->getReceptorAux($cid);
        foreach ($sine as $dactual) {
            $rfc = $dactual['rfc'];
            $razonsocial = $dactual['razon_social'];
            $clvreg = $dactual['regimen_cliente'];
            $codpos = $dactual['codigo_postal'];

            $estado = $this->getEstadoAux($dactual['idestado']);
            $municipio = $this->getMunicipioAux($dactual['idmunicipio']);
            $int = "";
            if ($dactual['numero_interior'] != "") {
                $int = " Int. " . $dactual['numero_interior'];
            }

            $direccion = $dactual['calle'] . " " . $dactual['numero_exterior'] . $int . " " . $dactual['localidad'] . " " . $municipio . " " . $estado;
            $datos .= "$rfc</tr>$razonsocial</tr>$clvreg</tr>$codpos</tr>$direccion";
        }
        return $datos;
    }

    private function getDatosEmisor($fid) {
        $datos = "";
        $sine = $this->getDatosFacturacionbyId($fid);
        foreach ($sine as $dactual) {
            $rfc = $dactual['rfc'];
            $razonsocial = $dactual['razon_social'];
            $clvreg = $dactual['c_regimenfiscal'];
            $regimen = $dactual['regimen_fiscal'];
            $codpos = $dactual['codigo_postal'];
            $datos .= "$rfc</tr>$razonsocial</tr>$clvreg</tr>$regimen</tr>$codpos";
        }
        return $datos;
    }

    private function getIDFacturaAux($folio, $iddatos) {
        $consultado = false;
        $consulta = "SELECT iddatos_factura FROM datos_factura WHERE folio_interno_fac=:folio and iddatosfacturacion=:iddatos;";
        $consultas = new Consultas();
        $valores = array("folio" => $folio,
            "iddatos" => $iddatos);
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    public function getIDFactura($folio, $iddatos) {
        $idfactura = "";
        $facturas = $this->getIDFacturaAux($folio, $iddatos);
        foreach ($facturas as $facturaactual) {
            $idfactura = $facturaactual['iddatos_factura'];
        }
        return $idfactura;
    }

    private function updateFolioConsecutivo($id) {
        $consultado = false;
        $consultas = new Consultas();
        $consulta = "UPDATE folio SET consecutivo=(consecutivo+1) WHERE idfolio=:id;";
        $val = array("id" => $id);
        $consultado = $consultas->execute($consulta, $val);
        return $consultado;
    }

    private function getFolio($id) {
        $datos = "";
        $folios = $this->getFoliobyID($id);
        foreach ($folios as $actual) {
            $serie = $actual['serie'];
            $letra = $actual['letra'];
            $consecutivo = $actual['consecutivo'];

            if ($consecutivo < 10) {
                $consecutivo = "000$consecutivo";
            } else if ($consecutivo < 100 && $consecutivo >= 10) {
                $consecutivo = "00$consecutivo";
            } else if ($consecutivo < 1000 && $consecutivo >= 100) {
                $consecutivo = "0$consecutivo";
            }

            $datos = "$serie</tr>$letra</tr>$consecutivo";
            $update = $this->updateFolioConsecutivo($id);
        }
        return $datos;
    }

    private function getFoliobyID($id) {
        $consultado = false;
        $consultas = new Consultas();
        $consulta = "SELECT * FROM folio WHERE idfolio=:id;";
        $val = array("id" => $id);
        $consultado = $consultas->getResults($consulta, $val);
        return $consultado;
    }

    public function getFacturas($idfactura) {
        $consultado = false;
        $consultas = new Consultas();
        $consulta = "SELECT * FROM datos_factura dat 
        INNER JOIN catalogo_metodo_pago cmp ON (dat.id_metodo_pago= cmp.idmetodo_pago) 
        INNER JOIN catalogo_pago cp ON (dat.id_forma_pago = cp.idcatalogo_pago) 
        INNER JOIN catalogo_moneda cm ON (cm.idcatalogo_moneda = dat.id_moneda)
        INNER JOIN catalogo_uso_cfdi cuc ON (dat.id_uso_cfdi= cuc.iduso_cfdi) 
        INNER JOIN catalogo_comprobante cc ON (dat.id_tipo_comprobante=cc.idcatalogo_comprobante) WHERE dat.tagfactura=:id;";
        $val = array("id" => $idfactura);
        $consultado = $consultas->getResults($consulta, $val);
        return $consultado;
    }

    public function getDatosFacturacionbyId($iddatos) {
        $consultado = false;
        $consulta = "SELECT * FROM datos_facturacion WHERE id_datos=:id;";
        $val = array("id" => $iddatos);
        $consultas = new Consultas();
        $consultado = $consultas->getResults($consulta, $val);
        return $consultado;
    }

    private function getDatosCliente($idcliente, $fid) {
        $consultado = false;
        $consultas = new Consultas();
        $consulta = "SELECT c.*, d.cpreceptor FROM cliente c INNER JOIN datos_factura d ON (c.id_cliente=d.idcliente) WHERE id_cliente=:cid AND tagfactura=:fid;";
        $val = array("cid" => $idcliente,
            "fid" => $fid);
        $consultado = $consultas->getResults($consulta, $val);
        return $consultado;
    }

    public function getDetalle($idfactura) {
        $consultado = false;
        $consulta = "SELECT det.* FROM detalle_factura det WHERE tagdetallef=:id";
        $val = array("id" => $idfactura);
        $consultas = new Consultas();
        $consultado = $consultas->getResults($consulta, $val);
        return $consultado;
    }

    private function getDiasHorarioAux() {
        $consultado = false;
        $con = new Consultas();
        $consulta = "SELECT * FROM horario_verano;";
        $consultado = $con->getResults($consulta, null);
        return $consultado;
    }

    private function getDiasHorario() {
        $dias = "";
        $datos = $this->getDiasHorarioAux();
        foreach ($datos as $actual) {
            $primer = $actual['primerdomingoabril'];
            $ultimo = $actual['ultimodomingooctubre'];
            $dias .= "$primer</tr>$ultimo";
        }
        return $dias;
    }

    private function getFechaFactura($difverano, $difinvierno) {
        $dias = $this->getDiasHorario();
        $div = explode("</tr>", $dias);

        $hoy = date('Y-m-d');
        $hoy = date('Y-m-d', strtotime($hoy));
        $primer = date('Y-m-d', strtotime($div[0]));
        $ultimo = date('Y-m-d', strtotime($div[1]));
        if (($hoy >= $primer) && ($hoy <= $ultimo)) {
            $tz = $difverano;
        } else {
            $tz = $difinvierno;
        }
        date_default_timezone_set("UTC");
        $utc = date('Y-m-d H:i:s');
        $gmt = strtotime($tz . ' hour', strtotime($utc));
        $gmt = date('Y-m-d H:i:s', $gmt);
        $fecha = str_replace(" ", "T", $gmt);
        return $fecha;
    }

    public function guardarXML($tagfactura) {
        $facturas = $this->getFacturas($tagfactura);
        foreach ($facturas as $facturaactual) {
            $idcliente = $facturaactual['idcliente'];
            $rfcCliente = $facturaactual['rfcreceptor'];
            $razonSocial = $facturaactual['rzreceptor'];
            $cpreceptor = $facturaactual['cpreceptor'];
            $regfiscalreceptor = $facturaactual['regfiscalreceptor'];
            $iddatos = $facturaactual['iddatosfacturacion'];
            $rzemisor = $facturaactual['factura_rzsocial'];
            $rfcemisor = $facturaactual['factura_rfcemisor'];
            $clvregemisor = $facturaactual['factura_clvregimen'];
            $cp = $facturaactual['factura_cpemisor'];
            $cuso = $facturaactual['c_usocfdi'];
            $serie = $facturaactual['serie'];
            $letra = $facturaactual['letra'];
            $folio = $facturaactual['folio_interno_fac'];
            $subtotal = $facturaactual['subtotal'];
            $subiva = $facturaactual['subtotaliva'];
            $subret = $facturaactual['subtotalret'];
            $totdescuentos = $facturaactual['totaldescuentos'];
            $total = $facturaactual['totalfactura'];
            $c_moneda = $facturaactual['c_moneda'];
            $tcambio = $facturaactual['tipo_cambio'];
            $c_metodopago = $facturaactual['c_metodopago'];
            $c_formapago = $facturaactual['c_pago'];
            $c_tipoComprobante = $facturaactual['c_tipocomprobante'];
        }

        $sine = $this->getDatosFacturacionbyId($iddatos);
        foreach ($sine as $sineactual) {
            $nocertificado = $sineactual['numcsd'];
            $csd = $sineactual['csd'];
            $difverano = $sineactual['difhorarioverano'];
            $difinvierno = $sineactual['difhorarioinvierno'];
        }

        $xml = new DomDocument('1.0', 'UTF-8');
        $raiz = $xml->createElementNS('http://www.sat.gob.mx/cfd/4', 'cfdi:Comprobante');
        $raiz = $xml->appendChild($raiz);

        $fecha = $this->getFechaFactura($difverano, $difinvierno);

        $raiz->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $raiz->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'schemaLocation', 'http://www.sat.gob.mx/cfd/4 http://www.sat.gob.mx/sitio_internet/cfd/4/cfdv40.xsd');
        $raiz->setAttribute('Version', '4.0');
        $raiz->setAttribute('Serie', $serie);
        $raiz->setAttribute('Folio', $letra . $folio);
        $raiz->setAttribute('Fecha', $fecha);
        $raiz->setAttribute('FormaPago', $c_formapago);
        $raiz->setAttribute('SubTotal', bcdiv($subtotal, '1', 2));
        if ($totdescuentos > 0) {
            $raiz->setAttribute('Descuento', bcdiv($totdescuentos, '1', 2));
        }
        $raiz->setAttribute('TipoCambio', $tcambio);
        $raiz->setAttribute('Moneda', $c_moneda);
        $raiz->setAttribute('Total', bcdiv($total, '1', 2));
        $raiz->setAttribute('TipoDeComprobante', $c_tipoComprobante);
        $raiz->setAttribute('Exportacion', '01');
        $raiz->setAttribute('MetodoPago', $c_metodopago);
        $raiz->setAttribute('LugarExpedicion', utf8_decode($cp));
        $raiz->setAttribute('NoCertificado', $nocertificado);
        //Convertir certificado a B64 con openssl: enc -in "CSD/00001000000407565367.cer" -a -A -out "cerB64.txt" 
        $raiz->setAttribute('Certificado', $csd);

        $emisor = $xml->createElement('cfdi:Emisor');
        $emisor = $raiz->appendChild($emisor);
        $emisor->setAttribute('Rfc', $rfcemisor);
        $emisor->setAttribute('Nombre', strtoupper($rzemisor));
        $emisor->setAttribute('RegimenFiscal', $clvregemisor);

        $receptor = $xml->createElement('cfdi:Receptor');
        $receptor = $raiz->appendChild($receptor);
        $receptor->setAttribute('Rfc', $rfcCliente);
        $receptor->setAttribute('Nombre', strtoupper($razonSocial));
        $receptor->setAttribute('DomicilioFiscalReceptor', $cpreceptor);
        $divreg = explode("-", $regfiscalreceptor);
        $receptor->setAttribute('RegimenFiscalReceptor', $divreg[0]);
        $receptor->setAttribute('UsoCFDI', $cuso);
        $baseT = 0;
        $baseR = 0;

        $conceptos = $xml->createElement('cfdi:Conceptos');
        $conceptos = $raiz->appendChild($conceptos);
        $detallefactura = $this->getDetalle($tagfactura);
        foreach ($detallefactura as $detalleactual) {
            $claveFiscal = $detalleactual['clvfiscal'];
            $precioV = $detalleactual['precio'];
            $cantidad = $detalleactual['cantidad'];
            $unidad = $detalleactual['clvunidad'];
            $descripcion = $detalleactual['factura_producto'];
            $totalu = $detalleactual['totalunitario'];
            $impdescuento = $detalleactual['impdescuento'];
            $traslados = $detalleactual['traslados'];
            $retenciones = $detalleactual['retenciones'];
            $objimp = "01";
            $importe = bcdiv($totalu, '1', 2) - bcdiv($impdescuento, '1', 2);

            $divfiscal = explode("-", $claveFiscal);
            $cfiscal = $divfiscal[0];

            $divunit = explode("-", $unidad);
            $cunidad = $divunit[0];
            $descunidad = $divunit[1];

            $concepto = $xml->createElement('cfdi:Concepto');
            $concepto = $conceptos->appendChild($concepto);
            $concepto->setAttribute('ClaveProdServ', $cfiscal);
            $concepto->setAttribute('Cantidad', $cantidad);
            $concepto->setAttribute('ClaveUnidad', $cunidad);
            $concepto->setAttribute('Unidad', $descunidad);
            $concepto->setAttribute('Descripcion', utf8_decode($descripcion));
            $concepto->setAttribute('ValorUnitario', bcdiv($precioV, '1', 2));
            $concepto->setAttribute('Importe', bcdiv($totalu, '1', 2));
            if ($traslados != "" || $retenciones != "") {
                $objimp = "02";
            }
            $concepto->setAttribute('ObjetoImp', $objimp);

            if ($impdescuento > 0) {
                $concepto->setAttribute('Descuento', bcdiv($impdescuento, '1', 2));
            }

            if ($traslados != "" || $retenciones != "") {
                $impuestos = $xml->createElement('cfdi:Impuestos');
                $impuestos = $concepto->appendChild($impuestos);
                $baseT += bcdiv($importe, '1', 2);
            }

            if ($traslados != "") {
                $nodetraslados = $xml->createElement('cfdi:Traslados');
                $nodetraslados = $impuestos->appendChild($nodetraslados);

                $divt = explode("<impuesto>", $traslados);
                foreach ($divt as $tras) {
                    $divt = explode("-", $tras);
                    $imp = "00$divt[2]";
                    $traslado = $xml->createElement('cfdi:Traslado');
                    $traslado = $nodetraslados->appendChild($traslado);
                    $traslado->setAttribute('Base', bcdiv($importe, '1', 2));
                    $traslado->setAttribute('Impuesto', $imp);
                    $traslado->setAttribute('TipoFactor', 'Tasa');
                    $traslado->setAttribute('TasaOCuota', bcdiv($divt[1], '1', 6));
                    $traslado->setAttribute('Importe', bcdiv($divt[0], '1', 2));
                }
            }

            if ($retenciones != "") {
                $noderet = $xml->createElement('cfdi:Retenciones');
                $noderet = $impuestos->appendChild($noderet);

                $divr = explode("<impuesto>", $retenciones);
                foreach ($divr as $ret) {
                    $divr = explode("-", $ret);
                    $imp = "00$divr[2]";
                    $retencion = $xml->createElement('cfdi:Retencion');
                    $retencion = $noderet->appendChild($retencion);
                    $retencion->setAttribute('Base', bcdiv($importe, '1', 2));
                    $retencion->setAttribute('Impuesto', $imp);
                    $retencion->setAttribute('TipoFactor', 'Tasa');
                    $retencion->setAttribute('TasaOCuota', bcdiv($divr[1], '1', 6));
                    $retencion->setAttribute('Importe', bcdiv($divr[0], '1', 2));
                }
            }
        }

        if ($subiva != "" || $subret != "") {
            $impuestosT = $xml->createElement('cfdi:Impuestos');
            $impuestosT = $raiz->appendChild($impuestosT);
        }

        $totalR = 0;
        if ($subret != "") {
            $noderet = $xml->createElement('cfdi:Retenciones');
            $noderet = $impuestosT->appendChild($noderet);
            $div2 = explode("<impuesto>", $subret);
            foreach ($div2 as $ret1) {
                $divr = explode("-", $ret1);
                $impr = "00$divr[2]";
                $retencion = $xml->createElement('cfdi:Retencion');
                $retencion = $noderet->appendChild($retencion);
                $retencion->setAttribute('Impuesto', $impr);
                $retencion->setAttribute('Importe', bcdiv($divr[0], '1', 2));
                $totalR += bcdiv($divr[0], '1', 2);
            }
            $impuestosT->setAttribute('TotalImpuestosRetenidos', bcdiv($totalR, '1', 2));
        }

        $totalT = 0;
        if ($subiva != "") {
            $nodetraslados = $xml->createElement('cfdi:Traslados');
            $nodetraslados = $impuestosT->appendChild($nodetraslados);
            $div1 = explode("<impuesto>", $subiva);
            foreach ($div1 as $tras1) {
                $divt = explode("-", $tras1);
                $imp = "00$divt[2]";
                $traslado = $xml->createElement('cfdi:Traslado');
                $traslado = $nodetraslados->appendChild($traslado);
                $traslado->setAttribute('Base', bcdiv($baseT, '1', 2));
                $traslado->setAttribute('Impuesto', $imp);
                $traslado->setAttribute('TipoFactor', 'Tasa');
                $traslado->setAttribute('TasaOCuota', bcdiv($divt[1], '1', 6));
                $traslado->setAttribute('Importe', bcdiv($divt[0], '1', 2));
                $totalT += bcdiv($divt[0], '1', 2);
            }
            $impuestosT->setAttribute('TotalImpuestosTrasladados', bcdiv($totalT, '1', 2));
        }

        $sello = $this->SelloXML($xml->saveXML(), $rfcemisor);
        $obj = json_decode($sello);
        $xml2 = new DOMDocument("1.0", "UTF-8");
        $xml2->loadXML($xml->saveXML());
        $c = $xml2->getElementsByTagNameNS('http://www.sat.gob.mx/cfd/4', 'Comprobante')->item(0);
        $c->setAttribute('Sello', $obj->sello);
        $doc = "../XML/XML2.xml";
        $xml2->save($doc);
        //$timbre = $this->timbrado($xml2->saveXML(), $tagfactura, $letra . $folio);
        return $sello;
    }

    function SelloXML($xmlFile, $rfc) {
        $folder = $this->getRFCFolder();
        $carpeta = '../temporal/' . $rfc . '/';
        $xslFile = "../cron/cadenaoriginal_4_0.xslt";
        $xml = new DOMDocument("1.0", "UTF-8");
        $xml->loadXML($xmlFile);
        $xsl = new DOMDocument();
        $xsl->load($xslFile);
        $proc = new XSLTProcessor;
        $proc->importStyleSheet($xsl);
        $cadenaOriginal = $proc->transformToXML($xml);
        $fichero = "../cron/cadenaOriginal.txt";
        file_put_contents($fichero, $cadenaOriginal);
        $params = array(
            "cadenaOriginal" => $fichero,
            //Archivo key pem: pkcs8 -inform DER -in recursos/LAN7008173R5.key -out key.pem -passin pass:12345678a
            "archivoKeyPem" => $carpeta . 'keyPEM.pem',
            //archivo cer pem: x509 -inform der -in CSD/cer.cer -out certificado.pem
            "archivoCerPem" => $carpeta . 'csdPEM.pem'
        );
        try {
            $result = Sellar::ObtenerSello($params);
            return $result;
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
    }

    function generarXML($filename, $content) {
        $f = fopen($filename, "w");
        fwrite($f, pack("CCC", 0xef, 0xbb, 0xbf));
        fwrite($f, $content);
        fclose($f);
    }

    private function getSWAccessAux() {
        $consultado = false;
        $consulta = "SELECT * FROM swaccess WHERE idswaccess=:id;";
        $consultas = new Consultas();
        $valores = array("id" => '1');
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    private function getSWAccess() {
        $datos = "";
        $get = $this->getSWAccessAux();
        foreach ($get as $actual) {
            $token = $actual['accesstoken'];
            $url = $actual['sw_url'];
        }
        $datos = "$url</tr>$token";
        return $datos;
    }

    function timbrado($doc, $tag, $folio) {
        $swaccess = $this->getSWAccess();
        $div = explode("</tr>", $swaccess);
        $url = $div[0];
        $token = $div[1];

        $params = array(
            "url" => $url,
            "token" => $token
        );

        try {
            header("Content-type: text/plain");
            $stamp = StampService::Set($params);
            $result = $stamp::StampV4($doc);
            if ($result->status == "error") {
                $notificacion = $this->notificacionFactura($folio, $result->message . " " . $result->messageDetail);
                return '0' . $result->message . " " . $result->messageDetail;
            } else {
                $guardar = $this->guardarTimbre($result, $tag);
                var_dump($result);
                return $guardar;
            }
        } catch (Exception $e) {
            header("Content-type: text/plain");
            echo $e->getMessage();
        }
    }

    private function notificacionFactura($folio, $errmsg) {
        $f = getdate();
        $d = $f['mday'];
        $m = $f['mon'];
        $y = $f['year'];
        $h = $f['hours'];
        $mi = $f['minutes'];

        if ($d < 10) {
            $d = "0$d";
        }
        if ($m < 10) {
            $m = "0$m";
        }
        if ($h < 10) {
            $h = "0$h";
        }
        if ($mi < 10) {
            $mi = "0$mi";
        }

        $msg = "La factura $folio no se timbro correctamente, $errmsg.";

        $consulta = "INSERT INTO `notificacion` VALUES (:id,:fecha,:hora,:msg,:readed);";
        $valores = array("id" => null,
            "fecha" => "$y-$m-$d",
            "hora" => "$h:$mi",
            "msg" => $msg,
            "readed" => '0');
        $con = new Consultas();
        $insertado = $con->execute($consulta, $valores);
        return $insertado;
    }

    function guardarTimbre($result, $tag) {
        $actualizado = false;
        $consultas = new Consultas();
        $consulta = "UPDATE `datos_factura` SET cadenaoriginal=:cadena, nocertificadosat=:certSAT, nocertificadocfdi=:certCFDI, uuid=:uuid, sellosat=:selloSAT, sellocfdi=:selloCFDI, fechatimbrado=:fechatimbrado, qrcode=:qrcode, cfdistring=:cfdi, tipofactura=:tipo WHERE tagfactura=:tag;";
        $valores = array("cadena" => $result->data->cadenaOriginalSAT,
            "certSAT" => $result->data->noCertificadoSAT,
            "certCFDI" => $result->data->noCertificadoCFDI,
            "uuid" => $result->data->uuid,
            "selloSAT" => $result->data->selloSAT,
            "selloCFDI" => $result->data->selloCFDI,
            "fechatimbrado" => $result->data->fechaTimbrado,
            "qrcode" => $result->data->qrCode,
            "cfdi" => $result->data->cfdi,
            "tipo" => "1",
            "tag" => $tag);
        $actualizado = $consultas->execute($consulta, $valores);
        $timbres = $this->updateTimbres();
        return '+Timbre Guardado';
    }

    private function updateTimbres() {
        $actualizado = false;
        $consulta = "UPDATE `contador_timbres` SET  timbresUtilizados=timbresUtilizados+1, timbresRestantes=timbresRestantes-1 WHERE idtimbres=:idtimbres;";
        $valores = array("idtimbres" => '1');
        $consultas = new Consultas();
        $actualizado = $consultas->execute($consulta, $valores);
        return $actualizado;
    }

    private function getContratosActivos() {
        $consultado = false;
        $consultas = new Consultas();
        $consulta = "SELECT * FROM contrato_servicio WHERE estadocontrato = '1'";
        $consultado = $consultas->getResults($consulta, null);
        return $consultado;
    }

    private function getDatosRefactura($idcontrato) {
        $consultado = false;
        $consultas = new Consultas();
        $consulta = "SELECT * FROM contrato_servicio WHERE idcontrato=:id";
        $val = array("id" => $idcontrato);
        $consultado = $consultas->getResults($consulta, $val);
        return $consultado;
    }

    function getDetalleRefactura($tag) {
        $consultado = false;
        $consultas = new Consultas();
        $consulta = "SELECT * FROM detalle_contrato WHERE tagdetalle=:tag";
        $val = array("tag" => $tag);
        $consultado = $consultas->getResults($consulta, $val);
        return $consultado;
    }

    public function getTipoCambio($seriem) {
        $cambio = "";
        $consulta = json_decode(file_get_contents('https://www.banxico.org.mx/SieAPIRest/service/v1/series/' . $seriem . '/datos/oportuno?token=95d80f26330b774d2b66fd83c8c6039bf11a7cea167d6f1848f6a2135d2c1433'), true);
        $bmx = $consulta['bmx'];
        $serie = $bmx['series'];
        foreach ($serie as $sactual) {
            $idserie = $sactual['idSerie'];
            $datos = $sactual['datos'];
            foreach ($datos as $dactual) {
                $cambio = $dactual['dato'];
            }
        }
        return $cambio;
    }

    private function genTag() {
        $fecha = getdate();
        $d = $fecha['mday'];
        $m = $fecha['mon'];
        $y = $fecha['year'];
        $h = $fecha['hours'];
        $mi = $fecha['minutes'];
        $sec = $fecha['seconds'];
        if ($d < 10) {
            $d = "0$d";
        }
        if ($m < 10) {
            $m = "0$m";
        }
        if ($h < 10) {
            $h = "0$h";
        }
        if ($mi < 10) {
            $mi = "0$mi";
        }
        if ($sec < 10) {
            $sec = "0$sec";
        }

        $dtag = $m . $y . $d . $h . $mi . $sec;
        $ranstr = "";
        $ranstr2 = "";
        $chars = "0123456789abcdefghijklmnopqrstuvwxyz";
        $charsize = strlen($chars);
        for ($r = 0; $r < 8; $r++) {
            $ranstr .= $chars[rand(0, $charsize - 1)];
        }

        for ($r = 0; $r < 8; $r++) {
            $ranstr2 .= $chars[rand(0, $charsize - 1)];
        }

        $fecha2 = getdate();
        $d2 = $fecha2['mday'];
        $m2 = $fecha2['mon'];
        $y2 = $fecha2['year'];
        $h2 = $fecha2['hours'];
        $mi2 = $fecha2['minutes'];
        $sec2 = $fecha2['seconds'];
        if ($d2 < 10) {
            $d2 = "0$d2";
        }
        if ($m2 < 10) {
            $m2 = "0$m2";
        }
        if ($h2 < 10) {
            $h2 = "0$h2";
        }
        if ($mi2 < 10) {
            $mi2 = "0$mi2";
        }
        if ($sec2 < 10) {
            $sec2 = "0$sec2";
        }

        $dtag2 = $sec2 . $mi2 . $h2 . $d2 . $y2 . $m2;

        $tag = $ranstr . $dtag . $dtag2 . $ranstr2;
        return $tag;
    }

    function reFacturar($idcontrato, $tag) {
        $fecha = getdate();
        $d = $fecha['mday'];
        $m = $fecha['mon'];
        $y = $fecha['year'];
        if ($d < 10) {
            $d = "0$d";
        }
        if ($m < 10) {
            $m = "0$m";
        }
        $hoy = "$y-$m-$d";

        $datcontrato = $this->getDatosRefactura($idcontrato);
        foreach ($datcontrato as $contratoactual) {
            $idfolio = $contratoactual['idfolio'];
            $idcliente = $contratoactual['idclientecontrato'];
            $idmetodo_pago = $contratoactual['metodopago'];
            $idforma_pago = $contratoactual['formapago'];
            $idmoneda = $contratoactual['moneda'];
            $seriem = $contratoactual['seriemoneda'];
            $iduso = $contratoactual['usocfdi'];
            $subtotal = $contratoactual['subtotal'];
            $subiva = $contratoactual['subtotaliva'];
            $subret = $contratoactual['subtotalret'];
            $totdescuentos = $contratoactual['totdescuentos'];
            $total = $contratoactual['totalcontrato'];
            $tipofactura = $contratoactual['chfacturar'];
            $chperiodo = $contratoactual['chperiodo'];
            $iddatos = $contratoactual['iddatosfacturacion'];
            $periodocobro = $contratoactual['periodocobro'];
        }

        $emisor = $this->getDatosEmisor($iddatos);
        $div = explode("</tr>", $emisor);
        $rfc = $div[0];
        $rzsocial = $div[1];
        $clvreg = $div[2];
        $regimen = $div[3];
        $codpos = $div[4];

        $folios = $this->getFolio($idfolio);
        $Fdiv = explode("</tr>", $folios);
        $serie = $Fdiv[0];
        $letra = $Fdiv[1];
        $folioFac = $Fdiv[2];

        $receptor = $this->getDatosReceptor($idcliente);
        $div2 = explode("</tr>", $receptor);
        $rfcliente = $div2[0];
        $rzcliente = $div2[1];
        $regcliente = $div2[2];
        $cpcliente = $div2[3];

        $tcambio = "1";
        if ($seriem != "NA") {
            $tcambio = $this->getTipoCambio($seriem);
        }

        $tagfactura = $this->genTag();

        $con = new Consultas();
        $consulta = "INSERT INTO `datos_factura` VALUES (:id, :fecha, :rfcemisor, :rzsocial, :clvregimen, :regimen, :codp, :serie,:letra,:folio, :idcliente, :rfcreceptor, :rzreceptor, :dircliente, :cpreceptor, :regfiscalreceptor,:chfirmar,:cadena,:certSAT,:certCFDI,:uuid,:selloSAT,:sellocfdi,:fechatimbrado,:qrcode,:cfdistring,:cfdicancel,:status,:idmetodopago,:idformapago,:idmoneda, :tcambio, :iduso,:tipocomprobante, :tipofactura,:iddatosfacturacion, :cfdis,:subtotal,:subiva,:subret,:totdescuentos,:total, :chperiodo, :periodocobro, :tag, :periodoglobal, :mesperiodo, :anhoperiodo);";
        $valores = array("id" => null,
            "fecha" => $hoy,
            "rfcemisor" => $rfc,
            "rzsocial" => $rzsocial,
            "clvregimen" => $clvreg,
            "regimen" => $regimen,
            "codp" => $codpos,
            "serie" => $serie,
            "letra" => $letra,
            "folio" => $folioFac,
            "idcliente" => $idcliente,
            "rfcreceptor" => $rfcliente,
            "rzreceptor" => $rzcliente,
            "dircliente" => '',
            "cpreceptor" => $cpcliente,
            "regfiscalreceptor" => $regcliente,
            "chfirmar" => '0',
            "cadena" => null,
            "certSAT" => null,
            "certCFDI" => null,
            "uuid" => null,
            "selloSAT" => null,
            "sellocfdi" => null,
            "fechatimbrado" => null,
            "qrcode" => null,
            "cfdistring" => null,
            "cfdicancel" => null,
            "status" => '2',
            "idmetodopago" => $idmetodo_pago,
            "idformapago" => $idforma_pago,
            "idmoneda" => $idmoneda,
            "tcambio" => $tcambio,
            "iduso" => $iduso,
            "tipocomprobante" => '1',
            "tipofactura" => '2',
            "iddatosfacturacion" => $iddatos,
            "cfdis" => '0',
            "subtotal" => $subtotal,
            "subiva" => $subiva,
            "subret" => $subret,
            "totdescuentos" => $totdescuentos,
            "total" => $total,
            "chperiodo" => $chperiodo,
            "periodocobro" => $periodocobro,
            "tag" => $tagfactura,
            "periodoglobal" => '',
            "mesperiodo" => '',
            "anhoperiodo" => '',);

        $insertado = $con->execute($consulta, $valores);

        $detcontrato = $this->getDetalleRefactura($tag);
        foreach ($detcontrato as $dcontratoactual) {
            $cantidad = $dcontratoactual['cantidad'];
            $precio = $dcontratoactual['precio'];
            $totalunitario = $dcontratoactual['totalunitario'];
            $descuento = $dcontratoactual['descuento'];
            $impdescuento = $dcontratoactual['impdescuento'];
            $totaldescuento = $dcontratoactual['totaldescuento'];
            $chiva = $dcontratoactual['traslados'];
            $chret = $dcontratoactual['retenciones'];
            $observaciones = $dcontratoactual['observacionesprod'];
            $idproducto = $dcontratoactual['id_prod_servicio'];
            $nombreprod = $dcontratoactual['contrato_producto'];
            $chinventario = $dcontratoactual['chinventario'];
            $clvfiscal = $dcontratoactual['clvfiscal'];
            $clvunidad = $dcontratoactual['clvunidad'];

            $consulta2 = "INSERT INTO `detalle_factura` VALUES (:id, :cantidad, :precio, :subtotal, :descuento, :impdescuento, :totdescuento, :chiva, :chret, :observaciones, :idproducto, :nombreprod, :chinv, :clvfiscal, :clvunidad, :tag);";
            $valores2 = array("id" => null,
                "cantidad" => $cantidad,
                "precio" => bcdiv($precio, '1', 2),
                "subtotal" => bcdiv($totalunitario, '1', 2),
                "descuento" => bcdiv($descuento, '1', 2),
                "impdescuento" => bcdiv($impdescuento, '1', 2),
                "totdescuento" => bcdiv($totaldescuento, '1', 2),
                "chiva" => $chiva,
                "chret" => $chret,
                "observaciones" => $observaciones,
                "idproducto" => $idproducto,
                "nombreprod" => $nombreprod,
                "chinv" => $chinventario,
                "clvfiscal" => $clvfiscal,
                "clvunidad" => $clvunidad,
                "tag" => $tagfactura);
            $insertado2 = $con->execute($consulta2, $valores2);
        }

        if ($tipofactura == '1') {
            $saldo = $this->checkSaldoAux();
            if ($saldo > 0) {
                $acceso = $this->checkAcceso();
                if (!$acceso) {
                    $xml = $this->guardarXML($tagfactura);
                } else {
                    $h = $fecha['hours'];
                    $mi = $fecha['minutes'];
                    if ($h < 10) {
                        $h = "0$h";
                    }
                    if ($mi < 10) {
                        $mi = "0$mi";
                    }
                    $hora = "$h:$mi";
                    $msg = "Su periodo de prueba ha concluido.";
                    $notificacion = $this->notificacionContrato($idcontrato, $fecha, $hora, $msg);
                }
            } else {
                $h = $fecha['hours'];
                $mi = $fecha['minutes'];
                if ($h < 10) {
                    $h = "0$h";
                }
                if ($mi < 10) {
                    $mi = "0$mi";
                }
                $hora = "$h:$mi";
                $msg = "El saldo de timbres es insuficiente.";
                $notificacion = $this->notificacionContrato($idcontrato, $fecha, $hora, $msg);
            }
        }

        return $tagfactura;
    }

    private function notificacionContrato($idcontrato, $fecha, $hora, $not) {
        if ($idcontrato < 10) {
            $idcontrato = "00$idcontrato";
        } else if ($idcontrato < 100 && $idcontrato >= 10) {
            $idcontrato = "0$idcontrato";
        }
        $msg = "El contrato N° $idcontrato no se timbro correctamente, $not";

        $consulta = "INSERT INTO `notificacion` VALUES (:id,:fecha,:hora,:msg,:readed);";
        $valores = array("id" => null,
            "fecha" => $fecha,
            "hora" => $hora,
            "msg" => $msg,
            "readed" => '0');
        $con = new Consultas();
        $insertado = $con->execute($consulta, $valores);
        return $insertado;
    }

    public function Contratosfacturar() {
        $datos = "";
        $facturas = array();
        $contrato = $this->getContratosActivos();
        if ($contrato != "") {
            foreach ($contrato as $contratoactual) {
                $fechafacturacion = $contratoactual['fecha_facturacion'];
                $idcontrato = $contratoactual['idcontrato'];
                $tag = $contratoactual['tagcontrato'];
                $periodocobro = $contratoactual['periodocobro'];
                $lastfecha = $contratoactual['ultfechafacturacion'];
                $mesinicio = $contratoactual['mesinicio'];
                $iniciado = $contratoactual['iniciado'];

                $dia2 = $this->checkDia($fechafacturacion);
                $intervalo = $this->checkIntervalos($dia2, $lastfecha, $periodocobro, $mesinicio, $iniciado);
                if ($intervalo) {
                    $fecha = getdate();
                    $d = $fecha['mday'];
                    $m = $fecha['mon'];
                    $y = $fecha['year'];
                    if ($d < 10) {
                        $d = "0$d";
                    }
                    if ($m < 10) {
                        $m = "0$m";
                    }
                    $hoy = "$y-$m-$d";

                    $facturas[] = $this->reFacturar($idcontrato, $tag) . '<id>' . $idcontrato;
                    $this->actualizarFechaContrato($hoy, $idcontrato);
                }
            }
            $datos = implode("<tag>", $facturas);
            $this->mail($datos);
        } else {
            $datos = "0No hay facturas activas";
        }
        return $datos;
    }

    function checkIntervalos($dia2, $lastfecha, $periodocobro, $mesinicio, $iniciado) {
        $check = false;
        $f = getdate();
        $d = $f['mday'];
        $m = $f['mon'];
        if ($d < 10) {
            $d = "0$d";
        }
        if ($m < 10) {
            $m = "0$m";
        }
        if ($dia2 == $d) {
            if ($iniciado == '0') {
                if ($mesinicio == $m) {
                    $check = true;
                }
            } else if ($iniciado == '1') {
                $dt = date('Y-m-d');
                $intervalo = $this->getMonthDiff($lastfecha, $dt);
                if ($intervalo == $periodocobro) {
                    $check = true;
                }
            }
        }
        return $check;
    }

    private function getSaldoAux() {
        $consultado = false;
        $consulta = "SELECT * FROM contador_timbres WHERE idtimbres=:id;";
        $consultas = new Consultas();
        $valores = array("id" => '1');
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    private function checkSaldoAux() {
        $restantes = "0";
        $saldo = $this->getSaldoAux();
        foreach ($saldo as $actual) {
            $restantes = $actual['timbresRestantes'];
        }
        return $restantes;
    }

    private function getFechaAccAux() {
        $consultado = false;
        $consulta = "SELECT fecharegistro,acceso FROM usuario order by idusuario asc limit 1;";
        $c = new Consultas();
        $consultado = $c->getResults($consulta, null);
        return $consultado;
    }

    public function checkAcceso() {
        Session::start();
        $inter = false;
        $data = $this->getFechaAccAux();
        foreach ($data as $actual) {
            $acceso = $actual['acceso'];
            $fecha = $actual['fecharegistro'];
        }
        if ($acceso == '0') {
            $d = new DateTime(date('Y-m-d H:i:s'));
            $d2 = new DateTime($fecha);
            $intervalo = $d->diff($d2);
            if ($intervalo->format('%a') >= '15') {
                $inter = true;
            }
        }
        return $inter;
    }

    function checkDia($day) {
        $dia = $day;
        $f = getdate();
        $m = $f['mon'];
        $d = $f['mday'];
        if ($d < 10) {
            $d = "0$d";
        }
        if ($m < 10) {
            $m = "0$m";
        }
        $hoy = "$m-$d";
        if ($dia == '29') {
            if ($hoy == '03-01') {
                $dia = '01';
            }
        } else if ($dia == '30') {
            if ($hoy == '03-02') {
                $dia = '02';
            }
        } else if ($dia == '31') {
            if ($hoy == '03-03') {
                $dia = '03';
            }
            if ($hoy == '05-01' || $hoy == '07-01' || $hoy == '10-01' || $hoy == '12-01') {
                $dia = '01';
            }
        }
        return $dia;
    }

    function getMonthDiff($start, $end = FALSE) {
        $end OR $end = time();
        $start = new DateTime("$start");
        $end = new DateTime("$end");
        $diff = $start->diff($end);
        return $diff->format('%y') * 12 + $diff->format('%m');
    }

    private function actualizarFechaContrato($dt, $id) {
        $actualizado = false;
        $consulta = "UPDATE `contrato_servicio` SET ultfechafacturacion=:dt, iniciado=:iniciado WHERE idcontrato=:id;";
        $valores = array("id" => $id,
            "dt" => $dt,
            "iniciado" => '1');
        $consultas = new Consultas();
        $actualizado = $consultas->execute($consulta, $valores);
        return '+Timbre Guardado';
    }

    private function getDatosPDF() {
        $consultado = false;
        $consulta = "SELECT * from datos_factura limit 15;";
        $consultas = new Consultas();
        $consultado = $consultas->getResults($consulta, null);
        return $consultado;
    }

    public function getPDFS() {
        $pdf = "";
        $ids = "";
        $facturas = $this->getDatosPDF();
        foreach ($facturas as $actual) {
            $idfactura = $actual['iddatos_factura'];
            $ids .= $idfactura . '-';
            //$pdf .= $this->pdf($idfactura);
        }
        return $ids . $pdf;
    }

    public function getEstadoAux($idestado) {
        $estado = "";
        $est = $this->getEstadoById($idestado);
        foreach ($est as $actual) {
            $estado = $actual['estado'];
        }
        return $estado;
    }

    private function getEstadoById($idestado) {
        $consultado = false;
        $consultas = new Consultas();
        $consulta = "SELECT * FROM estado WHERE id_estado=:id;";
        $valores = array("id" => $idestado);
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    public function getMunicipioAux($idmun) {
        $municipio = "";
        $mun = $this->getMunicipioById($idmun);
        foreach ($mun as $actual) {
            $municipio = $actual['municipio'];
        }
        return $municipio;
    }

    private function getMunicipioById($idmun) {
        $consultado = false;
        $consulta = "SELECT * FROM municipio WHERE id_municipio=:id;";
        $valores = array("id" => $idmun);
        $consultas = new Consultas();
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    private function getDireccionCliente($idcliente, $tag) {
        $direccion = "";
        $datos = $this->getDatosCliente($idcliente, $tag);
        foreach ($datos as $actual) {
            $codpostal = $actual['codigo_postal'];
            $cpreceptor = $actual['cpreceptor'];
            $direccion = "CP. $cpreceptor";
            if ($codpostal == $cpreceptor) {
                $calle = $actual['calle'];
                $numext = $actual['numero_exterior'];
                $localidad = $actual['localidad'];
                $idmunicipio = $actual['idmunicipio'];
                $idestadodir = $actual['idestado'];

                $next = "";
                if ($numext != "") {
                    $next = " #$numext";
                }

                $col = "";
                if ($localidad != "") {
                    $col = ", Colonia: $localidad";
                }

                $cp = "";
                if ($codpostal != "" && $codpostal != "0") {
                    $cp = " CP. $codpostal";
                }

                $municipio = "";
                if ($idmunicipio != "0") {
                    $muni = $this->getMunicipioAux($idmunicipio);
                    $municipio = ", $muni";
                }

                $estadodir = "";
                if ($idestadodir != "0") {
                    $est = $this->getEstadoAux($idestadodir);
                    $estadodir = ", $est";
                }

                $direccion = $calle . $next . $col . $cp . $municipio . $estadodir;
            }
        }
        return $direccion;
    }

    function pdf($tagfactura) {
        $fecha = getdate();
        $d = $fecha['mday'];
        $m = $fecha['mon'];
        $y = $fecha['year'];
        if ($d < 10) {
            $d = "0$d";
        }
        if ($m < 10) {
            $m = "0$m";
        }
        $hoy = "$d/$m/$y";
        $facturas = $this->getFacturas($tagfactura);
        foreach ($facturas as $facturaactual) {
            $idcliente = $facturaactual['idcliente'];
            $nombreCliente = $facturaactual['rzreceptor'];
            $rfccliente = $facturaactual['rfcreceptor'];
            $cpcliente = $facturaactual['cpreceptor'];
            $regcliente = $facturaactual['regfiscalreceptor'];
            $cuso = $facturaactual['c_usocfdi'];
            $descripcionuso = $facturaactual['descripcion_cfdi'];
            $rfc = $facturaactual['factura_rfcemisor'];
            $razonsocial = $facturaactual['factura_rzsocial'];
            $regimen = $facturaactual['factura_clvregimen'] . ' ' . $facturaactual['factura_regimen'];
            $cp = $facturaactual['factura_cpemisor'];
            $folio = $facturaactual['letra'] . $facturaactual['folio_interno_fac'];
            $tipocomprobante = $facturaactual['c_tipocomprobante'] . ' ' . $facturaactual['descripcion_comprobante'];
            $fecha_creacion = $facturaactual['fecha_creacion'];
            $cadenaoriginal = $facturaactual['cadenaoriginal'];
            $certSAT = $facturaactual['nocertificadosat'];
            $certcfdi = $facturaactual['nocertificadocfdi'];
            $uuid = $facturaactual['uuid'];
            $selloSat = $facturaactual['sellosat'];
            $sellocfdi = $facturaactual['sellocfdi'];
            $fechatimbrado = $facturaactual['fechatimbrado'];
            $qrcode = $facturaactual['qrcode'];
            $c_metodo = $facturaactual['c_metodopago'];
            $des_metodo = $facturaactual['descripcion_metodopago'];
            $c_pago = $facturaactual['c_pago'];
            $des_pago = $facturaactual['descripcion_pago'];
            $idmoneda = $facturaactual['id_moneda'];
            $c_moneda = $facturaactual['c_moneda'];
            $subtotal = $facturaactual['subtotal'];
            $totdescuentos = $facturaactual['totaldescuentos'];
            $totalfactura = $facturaactual['totalfactura'];
            $estado = $facturaactual['status_pago'];
            $chfirmar = $facturaactual['chfirmar'];
            $iddatosfacturacion = $facturaactual['iddatosfacturacion'];
            $chperiodo = $facturaactual['chperiodo'];
            $periodocobro = $facturaactual['periodocobro'];
            $tipofactura = $facturaactual['tipofactura'];
            $divideF = explode("-", $fecha_creacion);
            $fecha_creacion = $divideF[2] . '/' . $divideF[1] . '/' . $divideF[0];
        }
        $sine = $this->getDatosFacturacionbyId($iddatosfacturacion);
        foreach ($sine as $sineactual) {
            $nombre = $sineactual['nombre_contribuyente'];
            $firma = $sineactual['firma'];
        }

        $encabezado = $this->getDatosEncabezado('1');
        foreach ($encabezado as $actual) {
            $colorcuadro = $actual['colorcuadro'];
            $rgbc = explode("-", $this->hex2rgb($colorcuadro));
            $colorsubtitulos = $actual['colorsubtitulos'];
            $rgbs = explode("-", $this->hex2rgb($colorsubtitulos));
            $clrfdatos = $actual['colorfdatos'];
            $rgbfd = explode("-", $this->hex2rgb($clrfdatos));
            $txtbold = $actual['colorbold'];
            $rgbbld = explode("-", $this->hex2rgb($txtbold));
            $clrtxt = $actual['colortexto'];
            $rgbtxt = explode("-", $this->hex2rgb($clrtxt));
            $colorhtabla = $actual['colorhtabla'];
            $rgbt = explode("-", $this->hex2rgb($colorhtabla));
            $colortittabla = $actual['colortittabla'];
            $rgbtt = explode("-", $this->hex2rgb($colortittabla));
        }

        $pdf = new PDF('P', 'mm', 'Letter');
        $pdf->Tfolio = $folio;
        $pdf->chfirmar = $chfirmar;
        $pdf->tipofactura = $tipofactura;
        $pdf->folder = $this->getRFCFolder();
        $pdf->rfc = $rfc;
        $pdf->firma = $firma;
        //$pdf->Open();
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


        $pdf->SetWidths(Array(15, 94.5, 25, 60));
        $pdf->SetLineHeight(4.5);

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetX(10);
        $pdf->RowNBCount(Array('', utf8_decode($razonsocial), '', utf8_decode($certcfdi)));

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetX(10);
        $pdf->RowNBCount(Array('', utf8_decode($rfc), '', utf8_decode($regimen)));

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetX(10);
        $pdf->RowNBCount(Array('', utf8_decode($fecha_creacion), '', utf8_decode($tipocomprobante)));

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetWidths(Array(32, 77.5, 25, 60));
        $pdf->SetX(10);
        $pdf->RowNBCount(Array('', utf8_decode($cp), '', ''));

        $heightdatos = $pdf->heightB;

        $pdf->SetFillColor($rgbfd[0], $rgbfd[1], $rgbfd[2]);
        $pdf->RoundedRect(10, 45.3, 195, $heightdatos, 2, 'F');
        $pdf->SetWidths(Array(15, 94.5, 25, 60));
        $pdf->SetLineHeight(4.5);

        $pdf->SetY(45);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetTextColor($rgbbld[0], $rgbbld[1], $rgbbld[2]);
        $pdf->Write(5, 'Nombre');
        $pdf->SetX(119.5);
        $pdf->Write(5, 'No Certificado');
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor($rgbtxt[0], $rgbtxt[1], $rgbtxt[2]);
        $pdf->SetX(10);
        $pdf->RowNB(Array('', utf8_decode($razonsocial), '', utf8_decode($certcfdi)));

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetTextColor($rgbbld[0], $rgbbld[1], $rgbbld[2]);
        $pdf->Write(5, 'RFC');
        $pdf->SetX(119.5);
        $pdf->Write(5, 'Regimen Fiscal');
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor($rgbtxt[0], $rgbtxt[1], $rgbtxt[2]);
        $pdf->SetX(10);
        $pdf->RowNB(Array('', utf8_decode($rfc), '', utf8_decode($regimen)));

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetTextColor($rgbbld[0], $rgbbld[1], $rgbbld[2]);
        $pdf->Write(5, 'Fecha');
        $pdf->SetX(119.5);
        $pdf->Write(5, 'T. Comprobante');
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor($rgbtxt[0], $rgbtxt[1], $rgbtxt[2]);
        $pdf->SetX(10);
        $pdf->RowNB(Array('', utf8_decode($fecha_creacion), '', utf8_decode($tipocomprobante)));

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetWidths(Array(32, 77.5, 25, 60));
        $pdf->SetTextColor($rgbbld[0], $rgbbld[1], $rgbbld[2]);
        $pdf->Write(5, 'Lugar de Expedicion');
        $pdf->SetX(119.5);
        $pdf->Write(5, '');
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor($rgbtxt[0], $rgbtxt[1], $rgbtxt[2]);
        $pdf->SetX(10);
        $pdf->RowNB(Array('', utf8_decode($cp), '', ''));
        $pdf->Ln(0.5);

        $pdf->heightB = 0;

        $pdf->SetFillColor($rgbc[0], $rgbc[1], $rgbc[2]);
        $pdf->SetTextColor($rgbs[0], $rgbs[1], $rgbs[2]);
        $pdf->SetFont('Arial', '', 15);
        $pdf->SetWidths(Array(80));
        $pdf->SetLineHeight(8);
        $pdf->RowT(Array("Datos del Cliente"));
        $direccion = "CP. $cpcliente";
        if ($idcliente != '0') {
            $direccion = $this->getDireccionCliente($idcliente, $tagfactura);
        }

        $pdf->ycliente = $pdf->GetY();

        $pdf->SetWidths(Array(17, 94.5, 23, 60));
        $pdf->SetLineHeight(4.5);

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetX(10);
        $pdf->RowNBCount(Array('', utf8_decode($nombreCliente), '', utf8_decode($cuso . ' ' . $descripcionuso)));

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetX(10);
        $pdf->RowNBCount(Array('', utf8_decode($rfccliente), '', ''));

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetWidths(Array(17, 174.5));
        $pdf->SetX(10);
        $pdf->RowNBCount(Array('', utf8_decode($direccion)));

        $heightcliente = $pdf->heightB;

        $pdf->SetFillColor($rgbfd[0], $rgbfd[1], $rgbfd[2]);
        $pdf->RoundedRect(10, ($pdf->ycliente + 0.7), 195, $heightcliente, 2, 'F');

        $pdf->SetY(($pdf->ycliente + 0.7));
        $pdf->SetWidths(Array(17, 94.5, 23, 60));
        $pdf->SetLineHeight(4.5);

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetTextColor($rgbbld[0], $rgbbld[1], $rgbbld[2]);
        $pdf->Write(5, 'Nombre');
        $pdf->SetX(119.5);
        $pdf->Write(5, 'Uso de CFDI');
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor($rgbtxt[0], $rgbtxt[1], $rgbtxt[2]);
        $pdf->SetX(10);
        $pdf->RowNB(Array('', utf8_decode($nombreCliente), '', utf8_decode($cuso . ' ' . $descripcionuso)));

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetTextColor($rgbbld[0], $rgbbld[1], $rgbbld[2]);
        $pdf->Write(5, 'RFC');
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor($rgbtxt[0], $rgbtxt[1], $rgbtxt[2]);
        $pdf->SetX(10);
        $pdf->RowNB(Array('', utf8_decode($rfccliente), '', ''));

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetTextColor($rgbbld[0], $rgbbld[1], $rgbbld[2]);
        $pdf->Write(5, 'Direccion');
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor($rgbtxt[0], $rgbtxt[1], $rgbtxt[2]);
        $pdf->SetWidths(Array(17, 174.5));
        $pdf->SetLineHeight(4.5);
        $pdf->SetX(10);
        $pdf->RowNB(Array('', utf8_decode($direccion)));
        $pdf->Ln(1);

        $pdf->heightB = 0;

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetTextColor(23, 23, 124);
        $pdf->RoundedRect(10, $pdf->GetY(), 195, 5.5, 2, 'D');
        $pdf->SetX(92.5);
        $pdf->Write(6, 'CONCEPTOS');
        $pdf->Ln(7);

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetFillColor($rgbt[0], $rgbt[1], $rgbt[2]);
        $pdf->SetTextColor($rgbtt[0], $rgbtt[1], $rgbtt[2]);
        $pdf->SetWidths(Array(15, 20, 25, 46, 45, 22, 22));
        $pdf->SetLineHeight(4.5);
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

        $detallefactura = $this->getDetalle($tagfactura);
        $iva = 0;
        $ieps = 0;
        $retiva = 0;
        $retieps = 0;
        $isr = 0;
        foreach ($detallefactura as $detalleactual) {
            $pdf->SetFont('Arial', '', 9);
            $claveFiscal = $detalleactual['clvfiscal'];
            $precioV = $detalleactual['precio'];
            $cantidad = $detalleactual['cantidad'];
            $unidad = $detalleactual['clvunidad'];
            $descripcion = $detalleactual['factura_producto'];
            $totalu = $detalleactual['totalunitario'];
            $traslados = $detalleactual['traslados'];
            $retenciones = $detalleactual['retenciones'];
            $observacionesprod = $detalleactual['observacionesproducto'];
            $obser = str_replace("<ent>", "\n", $observacionesprod);
            $divclv = explode("-", $claveFiscal);

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

            $pdf->Row(Array($cantidad, $divclv[0], $unidad, utf8_decode($descripcion), utf8_decode($obser), utf8_decode('$ ' . number_format($precioV, 2, '.', ',')), utf8_decode('$ ' . number_format($totalu, 2, '.', ','))));
        }

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(30, 8, 'Moneda ', 0, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(60, 8, utf8_decode($c_moneda), 0, 0, 'L', 0);
        $pdf->Ln(8);

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(30, 8, 'Forma de Pago ', 0, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(60, 8, utf8_decode($c_pago . ' ' . $des_pago), 0, 0, 'L', 0);
        $pdf->Ln(8);

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(30, 8, 'Metodo de pago ', 0, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(60, 8, utf8_decode($c_metodo . ' ' . $des_metodo), 0, 0, 'L', 0);
        $pdf->Ln(10);

        $pdf->Ln(-26);
        $pdf->Cell(151, 8, '', 0, 0, 'C', 0);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(22, 8, 'Subtotal ', 1, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(22, 8, utf8_decode('$ ' . number_format($subtotal, 2, '.', ',')), 1, 0, 'C', 0);
        $pdf->Ln(8);
        $lnJ = 15;

        if ($iva > 0) {
            $pdf->Cell(151, 8, '', 0, 0, 'C', 0);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(22, 8, 'IVA', 1, 0, 'C', 0);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(22, 8, utf8_decode('$ ' . bcdiv($iva, '1', 2)), 1, 0, 'C', 0);
            $pdf->Ln(8);
            $lnJ = 10;
        }

        if ($ieps > 0) {
            $pdf->Cell(151, 8, '', 0, 0, 'C', 0);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(22, 8, 'IEPS', 1, 0, 'C', 0);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(22, 8, utf8_decode('$ ' . bcdiv($ieps, '1', 2)), 1, 0, 'C', 0);
            $pdf->Ln(8);
            $lnJ = 10;
        }

        if ($isr > 0) {
            $pdf->Cell(151, 8, '', 0, 0, 'C', 0);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(22, 8, 'ISR', 1, 0, 'C', 0);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(22, 8, utf8_decode('$ ' . bcdiv($isr, '1', 2)), 1, 0, 'C', 0);
            $pdf->Ln(8);
            $lnJ = 10;
        }

        if ($retiva > 0) {
            $pdf->Cell(151, 8, '', 0, 0, 'C', 0);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(22, 8, 'Retencion IVA', 1, 0, 'C', 0);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(22, 8, utf8_decode('$ ' . bcdiv($retiva, '1', 2)), 1, 0, 'C', 0);
            $pdf->Ln(8);
            $lnJ = 10;
        }

        if ($retieps > 0) {
            $pdf->Cell(151, 8, '', 0, 0, 'C', 0);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(22, 8, 'Retencion IEPS', 1, 0, 'C', 0);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(22, 8, utf8_decode('$ ' . bcdiv($retieps, '1', 2)), 1, 0, 'C', 0);
            $pdf->Ln(8);
            $lnJ = 10;
        }

        if ($totdescuentos > 0) {
            $pdf->Cell(151, 8, '', 0, 0, 'C', 0);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(22, 8, 'Descuentos', 1, 0, 'C', 0);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(22, 8, utf8_decode('$ ' . bcdiv($totdescuentos, '1', 2)), 1, 0, 'C', 0);
            $pdf->Ln(8);
            $lnJ = 10;
        }

        $pdf->Cell(151, 8, '', 0, 0, 'C', 0);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(22, 8, 'Total ', 1, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(22, 8, utf8_decode('$ ' . bcdiv($totalfactura, '1', 2)), 1, 0, 'C', 0);
        $pdf->Ln($lnJ);

        $div = explode(".", bcdiv($totalfactura, '1', 2));
        switch ($idmoneda) {
            case '1':
                $letramoneda = "pesos";
                $letracent = "centavos";
                $mn = "$div[1]/100 M.N.";
                break;
            case '2':
                $letramoneda = "dolares";
                $letracent = "centavos";
                $mn = "";
                break;
            case '4':
                $letramoneda = "euros";
                $letracent = "centimos";
                $mn = "";
                break;
            default:
                $letramoneda = "pesos";
                $letracent = "centavos";
                $mn = "$div[1]/100 M.N.";
                break;
        }

        $pdf->SetWidths(Array(30, 165));
        $pdf->SetLineHeight(4.5);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Write(4.5, 'Importe con Letra');
        $pdf->SetFont('Arial', '', 9);
        $letras = NumeroALetras::convertir(bcdiv($totalfactura, '1', 2), utf8_decode($letramoneda), utf8_decode($letracent));
        $pdf->SetX(10);
        $pdf->RowNB(Array('', utf8_decode("$letras $mn")));
        $pdf->Ln(1);

        if ($chperiodo == '1') {
            $month = $divideF[1];
            $year = $divideF[0];
            $year2 = $year;
            $month2 = $month + $periodocobro;

            if ($month2 >= '13') {
                $month2 = $month2 - 12;
                $year2 = $year + 1;
            }

            switch ($periodocobro) {
                case '1':
                    $periodo = "Mensual";
                    break;
                case '2':
                    $periodo = "Bimestral";
                    break;
                case '3':
                    $periodo = "Trimestral";
                    break;
                case '4':
                    $periodo = "Cuatrimestral";
                    break;
                case '6':
                    $periodo = "Semestral";
                    break;
                case '12':
                    $periodo = "Anual";
                    break;
                default:
                    break;
            }

            $mes = $this->translateMonth($month);
            $mes2 = $this->translateMonth($month2);

            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(30, 8, 'Periodo de Cobro ', 0, 0, 'C', 0);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(26, 8, utf8_decode("$periodo del " . $divideF[2] . " de " . $mes . " del $year al " . $divideF[2] . " de " . $mes2 . " del $year2"), 0, 0, 'L', 0);
            $pdf->Ln(8);
        }

        $pdf->SetFillColor($rgbc[0], $rgbc[1], $rgbc[2]);
        $pdf->RoundedRect(10, $pdf->GetY(), 195, 3, 1.5, 'F');

        if ($estado != "3") {
            if ($uuid == "") {
                $pdf->Ln(5);
                $pdf->SetFont('Arial', 'BI', 8);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->Cell(10, 3, '*Este documento no posee efectos fiscales ', 0, 0, 'L', 0);
            } else {
                $pdf->Ln(4.5);
                $pdf->SetFillColor($rgbfd[0], $rgbfd[1], $rgbfd[2]);
                $pdf->RoundedRect(10, $pdf->GetY(), 95, 30, 2, 'F');
                $pdf->SetTextColor(0, 0, 0);
                if ($qrcode != "") {
                    $pic = 'data://text/plain;base64,' . $qrcode;
                    $pdf->Write(10, $pdf->Image($pic, $pdf->GetX(), $pdf->GetY(), 30, 0, 'png'), '');
                }
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(40);
                $pdf->SetTextColor($rgbbld[0], $rgbbld[1], $rgbbld[2]);
                $pdf->Write(10, utf8_decode("Folio Fiscal (UUID): "), '');
                $pdf->Ln(4);
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(40);
                $pdf->SetTextColor($rgbtxt[0], $rgbtxt[1], $rgbtxt[2]);
                $pdf->Write(10, utf8_decode($uuid), '');
                $pdf->Ln(4);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(40);
                $pdf->SetTextColor($rgbbld[0], $rgbbld[1], $rgbbld[2]);
                $pdf->Write(10, utf8_decode("N¡Æ Certificado SAT: "), '');
                $pdf->Ln(4);
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(40);
                $pdf->SetTextColor($rgbtxt[0], $rgbtxt[1], $rgbtxt[2]);
                $pdf->Write(10, utf8_decode($certSAT), '');
                $pdf->Ln(4);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(40);
                $pdf->SetTextColor($rgbbld[0], $rgbbld[1], $rgbbld[2]);
                $pdf->Write(10, utf8_decode("Fecha de Certificacion: "), '');
                $pdf->Ln(4);
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(40);
                $pdf->SetTextColor($rgbtxt[0], $rgbtxt[1], $rgbtxt[2]);
                $pdf->Write(10, utf8_decode($fechatimbrado), '');
                $pdf->Ln(11.5);

                $pdf->SetFont('Arial', 'B', 9);

                $pdf->SetFillColor($rgbc[0], $rgbc[1], $rgbc[2]);
                $pdf->SetTextColor($rgbs[0], $rgbs[1], $rgbs[2]);
                $pdf->RoundedRect(10, $pdf->GetY(), 35, 5, 2, 'F');
                $pdf->SetX(12);
                $pdf->Write(5, utf8_decode("Sello CFDI"), '');

                $pdf->RoundedRect(75, $pdf->GetY(), 35, 5, 2, 'F');
                $pdf->SetX(77);
                $pdf->Write(5, utf8_decode("Sello SAT"), '');

                $pdf->RoundedRect(140, $pdf->GetY(), 35, 5, 2, 'F');
                $pdf->SetX(142);
                $pdf->Write(5, utf8_decode("Cadena Original"), '');
                $pdf->Ln(5);
                $pdf->SetWidths(Array(62.5, 2.5, 62.5, 2.5, 65));
                $pdf->SetLineHeight(2.5);
                $pdf->SetFont('Arial', '', 5);
                $pdf->SetTextColor($rgbtxt[0], $rgbtxt[1], $rgbtxt[2]);
                $pdf->rgbfd0 = $rgbfd[0];
                $pdf->rgbfd1 = $rgbfd[1];
                $pdf->rgbfd2 = $rgbfd[2];
                $pdf->RowR(Array(utf8_decode($sellocfdi), "", utf8_decode($selloSat), "", utf8_decode($cadenaoriginal)));
                $pdf->SetFont('Arial', '', 9);
                $pdf->Write(8, utf8_decode("Este documento es una representacion impresa de un cfdi-."), '');
            }
        } else {
            $pdf->Ln(5);
            $pdf->SetFont('Arial', 'BI', 8);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(10, 3, '*La Factura ' . $folio . ' ha sido oficialmente cancelada', 0, 0, 'L', 0);
        }
        $pdf->isFinished = true;
        return $pdf->Output('S');
    }

    public function getMailBody($id) {
        $datos = "";
        $get = $this->getMailBodyAux($id);
        foreach ($get as $actual) {
            $idmailbody = $actual['idmailbody'];
            $asunto = $actual['asunto'];
            $saludo = $actual['saludo'];
            $mensaje = $actual['mensaje'];
            $datos = "$idmailbody</tr>$asunto</tr>$saludo</tr>$mensaje";
        }
        return $datos;
    }

    private function getMailBodyAux($id) {
        $consultado = false;
        $consulta = "SELECT * FROM mailbody WHERE idmailbody=:id;";
        $consultas = new Consultas();
        $valores = array("id" => $id);
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    private function getConfigMailAux() {
        $consultado = false;
        $consulta = "SELECT * FROM correoenvio WHERE chuso5=:id;";
        $consultas = new Consultas();
        $valores = array("id" => '1');
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    private function getConfigMail() {
        $datos = "";
        $get = $this->getConfigMailAux();
        foreach ($get as $actual) {
            $correo = $actual['correo'];
            $pass = $actual['password'];
            $remitente = $actual['remitente'];
            $correoremitente = $actual['correoremitente'];
            $host = $actual['host'];
            $puerto = $actual['puerto'];
            $seguridad = $actual['seguridad'];
            $datos .= "$correo</tr>$pass</tr>$remitente</tr>$correoremitente</tr>$host</tr>$puerto</tr>$seguridad";
        }
        return $datos;
    }

    private function getmail($tag) {
        $consultado = false;
        $consultas = new Consultas();
        $consulta = "SELECT * FROM datos_factura dat INNER JOIN cliente c ON (dat.idcliente=c.id_cliente) WHERE dat.tagfactura=:id";
        $val = array("id" => $tag);
        $consultado = $consultas->getResults($consulta, $val);
        return $consultado;
    }

    public function mail($tags) {
        $body = $this->getMailBody('1');
        $divM = explode("</tr>", $body);
        $asunto = $divM[1];
        $saludo = $divM[2];
        $msg = $divM[3];
        $txt = str_replace("<corte>", "<br>", $msg);

        $config = $this->getConfigMail();
        $div = explode("</tr>", $config);
        $correoenvio = $div[0];
        $pass = $div[1];
        $remitente = $div[2];
        $correoremitente = $div[3];
        $host = $div[4];
        $puerto = $div[5];
        $seguridad = $div[6];

        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Mailer = 'smtp';
        $mail->SMTPAuth = true;
        $mail->Host = $host;
        $mail->Port = $puerto;
        $mail->SMTPSecure = $seguridad;
        //$mail->SMTPDebug = 2;
        $mail->Username = $correoenvio;
        $mail->Password = $pass;
        $mail->From = $correoremitente;
        $mail->FromName = $remitente;
        $mail->Subject = utf8_decode($asunto);

        $facturas = explode("<tag>", $tags);
        foreach ($facturas as $actual) {
            $div = explode("<id>", $actual);
            $tagfactura = $div[0];
            $idcontrato = $div[1];
            $datcontrato = $this->getDatosRefactura($idcontrato);
            foreach ($datcontrato as $conactual) {
                $ch1 = $conactual['chenvio1'];
                $ch2 = $conactual['chenvio2'];
                $ch3 = $conactual['chenvio3'];
                $ch4 = $conactual['chenvio4'];
                $ch5 = $conactual['chenvio5'];
                $ch6 = $conactual['chenvio6'];
            }
            $correo = $this->getmail($tagfactura);
            foreach ($correo as $correoactual) {
                $email1 = $correoactual['email_informacion'];
                $email2 = $correoactual['email_facturacion'];
                $email3 = $correoactual['email_gerencia'];
                $email4 = $correoactual['correoalt1'];
                $email5 = $correoactual['correoalt2'];
                $email6 = $correoactual['correoalt3'];
                $folio = $correoactual['letra'] . $correoactual['folio_interno_fac'];
                $nombre = $correoactual['razon_social'];
                $xmlstring = $correoactual['cfdistring'];
                $rfcemisor = $correoactual['factura_rfcemisor'];
                $uuid = $correoactual['uuid'];
            }

            $mail->Body = utf8_decode($saludo . ' ' . $nombre . ' ' . $txt);

            //if ($ch1 == '1' && $email1 != "") {
                $mail->addAddress("dsedge23@gmail.com");
            //}

            /*if ($ch2 == '1' && $email2 != "") {
                $mail->addAddress($email2);
            }

            if ($ch3 == '1' && $email3 != "") {
                $mail->addAddress($email3);
            }

            if ($ch4 == '1' && $email4 != "") {
                $mail->addAddress($email4);
            }

            if ($ch5 == '1' && $email5 != "") {
                $mail->addAddress($email5);
            }

            if ($ch6 == '1' && $email6 != "") {
                $mail->addAddress($email6);
            }*/

            //$pdf = $this->pdf($tagfactura);
            $mail->isHTML(true);
            //$mail->addStringAttachment($pdf, $folio . '_' . $rfcemisor . '_' . $uuid . '.pdf');
            if ($xmlstring != "") {
                $mail->addStringAttachment($xmlstring, $folio . '_' . $rfcemisor . '_' . $uuid . ".xml");
            }
            if (!$mail->send()) {
                echo '0No se envio el mensaje';
                echo '0Mailer Error: ' . $mail->ErrorInfo;
            } else {
                echo '1Se ha enviado la factura';
            }
            $mail->clearAddresses();
            $mail->clearAttachments();
        }
    }

    private function translateMonth($m) {
        switch ($m) {
            case '1':
                $mes = 'Enero';
                break;
            case '2':
                $mes = 'Febrero';
                break;
            case '3':
                $mes = "Marzo";
                break;
            case '4':
                $mes = 'Abril';
                break;
            case '5':
                $mes = 'Mayo';
                break;
            case '6':
                $mes = 'Junio';
                break;
            case '7':
                $mes = 'Julio';
                break;
            case '8':
                $mes = 'Agosto';
                break;
            case '9':
                $mes = 'Septiembre';
                break;
            case '10':
                $mes = 'Octubre';
                break;
            case '11':
                $mes = 'Noviembre';
                break;
            case '12':
                $mes = 'Diciembre';
                break;
            default :
                $mes = '';
                break;
        }
        return $mes;
    }

}
