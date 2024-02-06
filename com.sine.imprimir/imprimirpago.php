<?php

require_once '../com.sine.modelo/TMP.php';
require_once '../com.sine.controlador/ControladorPago.php';
require_once '../vendor/autoload.php';
require '../pdf/fpdf.php';
setlocale(LC_MONETARY, 'es_MX.UTF-8');

class PDF extends FPDF {

    var $titulopagina;
    var $imglogo;
    var $celdatitulo;
    var $colortitulo;
    var $pagina;
    var $correo;
    var $telefono1;
    var $telefono2;
    var $chnum;
    var $colorpie;
    var $widths;
    var $aligns;
    var $lineHeight;
    var $Tfolio;
    var $rfc;
    var $firma;
    var $nmfirma;
    var $chfirmar;
    var $isFinished;
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

    function RowFill($data) {
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
            $this->Rect($x, $y, $w, $h, 'FD');
            //Print the text
            $this->MultiCell($w, 4.5, $data[$i], 0, $a);
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
        $this->SetFont('Arial', '', 19);
        $rgbc = explode("-", $this->celdatitulo);
        $rgbt = explode("-", $this->colortitulo);
        $this->SetFillColor($rgbc[0], $rgbc[1], $rgbc[2]);
        $this->SetTextColor($rgbt[0], $rgbt[1], $rgbt[2]);
        $logo = "../img/logo/" . $this->imglogo;
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
        $this->Write(8, $this->titulopagina);

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
        $pagin = "";
        if ($this->chnum == '1') {
            $pagin = utf8_decode('Pagina ' . $this->PageNo() . ' de {nb}');
        }
        $this->SetY(-18);
        if ($this->isFinished) {
            if ($this->chfirmar == '1') {
                $this->Image($this->firma, 75, ($this->GetY() - 25), 60, 0, 'png');
                $this->SetFont('Arial', 'I', 9);
                $this->Cell(195, 4, $this->nmfirma, 0, 0, 'C');
                $this->Ln(4);
            }
        }
        $rgb = explode("-", $this->colorpie);
        $this->SetTextColor($rgb[0], $rgb[1], $rgb[2]);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(65, 4, $this->pagina, 0, 0, 'L');
        $phone = "Tel: " . $this->telefono1;
        if ($this->telefono2 != "") {
            $this->Cell(65, 4, '', 0, 0, 'C');
            $this->Cell(65, 4, "Tel: " . $this->telefono1, 0, 0, 'R');
            $phone = "Tel: " . $this->telefono2;
        }
        $this->Ln(4);
        $this->Cell(65, 4, $this->correo, 0, 0, 'L');
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

$cf = new ControladorPago();

if (isset($_GET['pago'])) {
    $idpago = intval($_GET['pago']);
} else if (isset($_POST['idpago'])) {
    $idpago = intval($_POST['idpago']);
}

$pagos = $cf->getPagoById($idpago);
foreach ($pagos as $pagoactual) {
    $idpago = $pagoactual['idpago'];
    $folio = $pagoactual['foliopago'];
    $foliopago = $pagoactual['letra'] . $folio;
    $fechaemision = $pagoactual['fechacreacion'];
    $idcliente = $pagoactual['pago_idcliente'];
    $nombrecontribuyente = $pagoactual['razonemisor'];
    $razonemisor = $pagoactual['razonemisor'];
    $rfcemisor = $pagoactual['rfcemisor'];
    $firmaemisor = $pagoactual['firma'];
    $regimen = $pagoactual['clvregemisor'] . " " . $pagoactual['regfiscalemisor'];
    $codpemisor = $pagoactual['codpemisor'];
    $rfc = $pagoactual['rfcreceptor'];
    $razonsocial = $pagoactual['razonreceptor'];
    $cpcliente = $pagoactual['codpreceptor'];
    $c_formapago = $pagoactual['c_pago'];
    $formapago = $pagoactual['descripcion_pago'];
    $fechapago = $pagoactual['fechapago'];
    $c_monedapago = $pagoactual['c_moneda'];
    $horapago = $pagoactual['horapago'];
    $horapago = date('g:i a', strtotime($horapago));
    $totalpagado = $pagoactual['totalpagado'];
    $cadenaoriginal = $pagoactual['cadenaoriginalpago'];
    $certSAT = $pagoactual['nocertsatpago'];
    $certcfdi = $pagoactual['nocertcfdipago'];
    $uuid = $pagoactual['uuidpago'];
    $selloSat = $pagoactual['sellosatpago'];
    $sellocfdi = $pagoactual['sellocfdipago'];
    $fechatimbrado = $pagoactual['fechatimbrado'];
    $qrcode = $pagoactual['qrcode'];
    $chfirmar = $pagoactual['chfirmar'];
    $cancelado = $pagoactual['cancelado'];
    $cfdistring = $pagoactual['cfdipago'];
}

$divfecha = explode("-", $fechaemision);
$mesE = $cf->translateMonth($divfecha[1]);
$fechaemision = "$divfecha[2]/$mesE/$divfecha[0]";

$divfecha2 = explode("-", $fechapago);
$mesP = $cf->translateMonth($divfecha2[1]);
$fechapago = "$divfecha2[2]/$mesP/$divfecha2[0]";

require_once '../com.sine.controlador/ControladorConfiguracion.php';
$cc = new ControladorConfiguracion();
$encabezado = $cc->getDatosEncabezado('2');
foreach ($encabezado as $actual) {
    $titulo = $actual['tituloencabezado'];
    $colortitulo = $cc->hex2rgb($actual['colortitulo']);
    $celdatitulo = $cc->hex2rgb($actual['colorceltitulo']);
    $imglogo = $actual['imglogo'];
    $titulocarta = $actual['titulocarta'];
    $pagina = $actual['pagina'];
    $correo = $actual['correo'];
    $telefono1 = $actual['telefono1'];
    $telefono2 = $actual['telefono2'];
    $chnum = $actual['numpag'];
    $colorpie = $cc->hex2rgb($actual['colorpie']);
    $colorcuadro = $actual['colorcuadro'];
    $rgbc = explode("-", $cc->hex2rgb($colorcuadro));
    $colorsubtitulos = $actual['colorsubtitulos'];
    $rgbs = explode("-", $cc->hex2rgb($colorsubtitulos));
    $clrfdatos = $actual['colorfdatos'];
    $rgbfd = explode("-", $cc->hex2rgb($clrfdatos));
    $txtbold = $actual['colorbold'];
    $rgbbld = explode("-", $cc->hex2rgb($txtbold));
    $clrtxt = $actual['colortexto'];
    $rgbtxt = explode("-", $cc->hex2rgb($clrtxt));
    $colorhtabla = $actual['colorhtabla'];
    $rgbt = explode("-", $cc->hex2rgb($colorhtabla));
    $colortittabla = $actual['colortittabla'];
    $rgbtt = explode("-", $cc->hex2rgb($colortittabla));
}

$pdf = new PDF('P', 'mm', 'Letter');
$pdf->titulopagina = $titulo;
$pdf->imglogo = $imglogo;
$pdf->colortitulo = $colortitulo;
$pdf->celdatitulo = $celdatitulo;
$pdf->pagina = $pagina;
$pdf->correo = $correo;
$pdf->telefono1 = $telefono1;
$pdf->telefono2 = $telefono2;
$pdf->chnum = $chnum;
$pdf->colorpie = $colorpie;
$pdf->Tfolio = $foliopago;

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

$pdf->SetFont('Arial', '', 8);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetWidths(Array(24, 117.5, 25, 28));
$pdf->SetLineHeight(4.5);

$pdf->SetX(10);
$pdf->RowNBCount(Array('', utf8_decode($razonemisor), '', utf8_decode($rfcemisor)));

$pdf->SetX(10);
$pdf->RowNBCount(Array('', utf8_decode($regimen), '', utf8_decode($fechaemision)));

$pdf->SetWidths(Array(24, 170.5));
$pdf->SetX(10);
$pdf->RowNBCount(Array('', utf8_decode($codpemisor)));

$heightdatos = $pdf->heightB;

$pdf->SetFillColor($rgbfd[0], $rgbfd[1], $rgbfd[2]);
$pdf->RoundedRect(10, 45.3, 195, $heightdatos, 2, 'F');
$pdf->SetWidths(Array(24, 113, 22, 35.5));
$pdf->SetLineHeight(4.5);

$pdf->SetY(45);

$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor($rgbbld[0], $rgbbld[1], $rgbbld[2]);
$pdf->Write(5, 'Razon Social');
$pdf->SetX(147.5);
$pdf->Write(5, 'RFC Emisor');
$pdf->SetFont('Arial', '', 8);
$pdf->SetTextColor($rgbtxt[0], $rgbtxt[1], $rgbtxt[2]);
$pdf->SetX(10);
$pdf->RowNB(Array('', utf8_decode($razonemisor), '', utf8_decode($rfcemisor)));

$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor($rgbbld[0], $rgbbld[1], $rgbbld[2]);
$pdf->Write(5, 'Regimen Fiscal');
$pdf->SetX(147.5);
$pdf->Write(5, 'Fecha Emision');
$pdf->SetFont('Arial', '', 8);
$pdf->SetTextColor($rgbtxt[0], $rgbtxt[1], $rgbtxt[2]);
$pdf->SetX(10);
$pdf->RowNB(Array('', utf8_decode($regimen), '', utf8_decode($fechaemision)));

$pdf->SetFont('Arial', 'B', 8);
$pdf->SetWidths(Array(24, 170.5));
$pdf->SetTextColor($rgbbld[0], $rgbbld[1], $rgbbld[2]);
$pdf->Write(5, 'Direccion');
$pdf->SetFont('Arial', '', 8);
$pdf->SetTextColor($rgbtxt[0], $rgbtxt[1], $rgbtxt[2]);
$pdf->SetX(10);
$pdf->RowNB(Array('', utf8_decode($codpemisor)));

$pdf->heightB = 0;
$pdf->Ln(1);

$pdf->SetFont('Arial', '', 15);
$pdf->SetFillColor($rgbc[0], $rgbc[1], $rgbc[2]);
$pdf->SetTextColor($rgbs[0], $rgbs[1], $rgbs[2]);
$pdf->SetWidths(Array(80));
$pdf->SetLineHeight(8);
$pdf->RowT(Array("Datos del Cliente"));

$pdf->chfirmar = $chfirmar;
$pdf->nmfirma = $nombrecontribuyente;
$pdf->firma = $firmaemisor;

$pdf->Ln(0.5);
$pdf->SetTextColor(0, 0, 0);

$pdf->SetX(10);
$pdf->SetFont('Arial', '', 8);
$pdf->SetWidths(Array(30, 110, 23, 31.5));
$pdf->SetLineHeight(4.5);
$pdf->SetX(10);
$pdf->ycliente = $pdf->GetY();
$pdf->RowNBCount(Array('Nombre', utf8_decode($razonsocial), 'RFC', utf8_decode($rfc)));

$pdf->SetFont('Arial', '', 8);
$pdf->SetX(10);
$pdf->RowNBCount(Array('Uso del CFDI', utf8_decode('P01-Por Definir'), 'Fecha y hora', utf8_decode($fechapago . ' ' . $horapago)));

$pdf->SetFont('Arial', '', 8);
$pdf->SetX(10);
$pdf->RowNBCount(Array('Metodo, Forma y', '', 'Moneda', utf8_decode($c_monedapago)));

$pdf->SetX(10);
$pdf->RowNBCount(Array('Condiciones de pago', utf8_decode('PPD-Pago en parcialidades o diferido ' . $c_formapago . '-' . $formapago), '', ''));

$pdf->SetFont('Arial', '', 8);
$pdf->SetWidths(Array(30, 177.8));
$pdf->SetLineHeight(4.5);
$pdf->SetX(10);
$direccion = "CP. $cpcliente";
if ($idcliente != '0') {
    $direccion = $cf->getDireccionCliente($idcliente, $idpago);
}
$pdf->RowNBCount(Array('Direccion', utf8_decode($direccion)));

$heightcliente = $pdf->heightB;

$pdf->SetFillColor($rgbfd[0], $rgbfd[1], $rgbfd[2]);
$pdf->RoundedRect(10, ($pdf->ycliente + 0.4), 195, $heightcliente, 2, 'F');

$pdf->SetY(($pdf->ycliente + 0.4));
$pdf->SetWidths(Array(30, 110, 23, 31.5));
$pdf->SetLineHeight(4.5);

$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor($rgbbld[0], $rgbbld[1], $rgbbld[2]);
$pdf->Write(5, 'Nombre');
$pdf->SetX(151.5);
$pdf->Write(5, 'RFC');
$pdf->SetFont('Arial', '', 8);
$pdf->SetTextColor($rgbtxt[0], $rgbtxt[1], $rgbtxt[2]);
$pdf->SetX(10);
$pdf->RowNB(Array('', utf8_decode($razonsocial), '', utf8_decode($rfc)));

$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor($rgbbld[0], $rgbbld[1], $rgbbld[2]);
$pdf->Write(5, 'Uso del CFDI');
$pdf->SetX(151.5);
$pdf->Write(5, 'Fecha y Hora');
$pdf->SetFont('Arial', '', 8);
$pdf->SetTextColor($rgbtxt[0], $rgbtxt[1], $rgbtxt[2]);
$pdf->SetX(10);
$pdf->RowNB(Array('', utf8_decode('P01-Por Definir'), '', utf8_decode($fechapago . ' ' . $horapago)));

$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor($rgbbld[0], $rgbbld[1], $rgbbld[2]);
$pdf->Write(5, 'Metodo, Forma y');
$pdf->SetX(151.5);
$pdf->Write(5, 'Moneda');
$pdf->SetFont('Arial', '', 8);
$pdf->SetTextColor($rgbtxt[0], $rgbtxt[1], $rgbtxt[2]);
$pdf->SetX(10);
$pdf->RowNB(Array('', '', '', utf8_decode($c_monedapago)));

$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor($rgbbld[0], $rgbbld[1], $rgbbld[2]);
$pdf->Write(5, 'Condiciones de pago');
$pdf->SetFont('Arial', '', 8);
$pdf->SetTextColor($rgbtxt[0], $rgbtxt[1], $rgbtxt[2]);
$pdf->SetX(10);
$pdf->RowNB(Array('', utf8_decode('PPD-Pago en parcialidades o diferido ' . $c_formapago . '-' . $formapago), '', ''));

$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor($rgbbld[0], $rgbbld[1], $rgbbld[2]);
$pdf->Write(5, 'Direccion');
$pdf->SetFont('Arial', '', 8);
$pdf->SetWidths(Array(30, 177.8));
$pdf->SetLineHeight(4.5);
$pdf->SetTextColor($rgbtxt[0], $rgbtxt[1], $rgbtxt[2]);
$pdf->SetX(10);
$pdf->RowNB(Array('', utf8_decode($direccion)));
$pdf->Ln(1.2);

$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetTextColor(23, 23, 124);
$pdf->RoundedRect(10, $pdf->GetY(), 195, 5.5, 2, 'FD');
$pdf->Cell(195, 6, 'CFDIS RELACIONADOS', 0, 0, 'C');
$pdf->Ln(7);
$pdf->SetWidths(Array(30, 24, 20, 23, 17, 16.7, 25, 20, 20));
$pdf->SetLineHeight(4.5);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetFillColor($rgbt[0], $rgbt[1], $rgbt[2]);
$pdf->SetTextColor($rgbtt[0], $rgbtt[1], $rgbtt[2]);
$pdf->RoundedRect(10, $pdf->GetY(), 195, 9, 2, 'FD');
$pdf->RowNBC(Array(utf8_decode('UUID'), utf8_decode('FOLIO'), utf8_decode('METODO PAGO'), utf8_decode('TOTAL FACTURA'), utf8_decode('MONEDA/CAMBIO'), utf8_decode('PARC.'), utf8_decode('ANTERIOR'), utf8_decode('PAGADO'), utf8_decode('RESTANTE')));

$detallepago = $cf->getDetallePago($idpago);
$pdf->SetFont('Arial', '', 8);
$pdf->SetTextColor(0, 0, 0);
$pdf->Ln(1);
$totalanteriores = 0;
$totalrestante = 0;
foreach ($detallepago as $actualcfdi) {
    $idfactura = $actualcfdi['pago_idfactura'];
    $foliofactura = $actualcfdi['foliodoc'];
    $uuidfactura = $actualcfdi['uuiddoc'];
    $tcambioF = $actualcfdi['tcambiodoc'];
    $idmonedaF = $actualcfdi['idmonedadoc'];
    $monedaF = $actualcfdi['cmonedadoc'];
    $c_metodopago = $actualcfdi['cmetododoc'];
    $totalfactura = $actualcfdi['totalfactura'];
    $parcialidad = $actualcfdi['noparcialidad'];
    $montoanterior = $actualcfdi['montoanterior'];
    $monto = $actualcfdi['monto'];
    $montoinsoluto = $actualcfdi['montoinsoluto'];
    $type = $actualcfdi['type'];

    $totalanteriores += $montoanterior;
    $totalrestante += $montoinsoluto;

    $pdf->Row(Array(utf8_decode($uuidfactura), utf8_decode($foliofactura), utf8_decode("$c_metodopago"), utf8_decode('$ ' . number_format($totalfactura, 2, '.', ',')), utf8_decode("$monedaF $" . $tcambioF), utf8_decode($parcialidad), utf8_decode('$ ' . number_format($montoanterior, 2, '.', ',')), utf8_decode('$ ' . number_format($monto, 2, '.', ',')), utf8_decode('$ ' . number_format($montoinsoluto, 2, '.', ','))));
}

$pdf->SetFont('Arial', 'B', 9);
$pdf->SetX(140.7);
$pdf->SetWidths(Array(25, 20, 20));
$pdf->SetLineHeight(4.5);
$pdf->SetAligns(array('R', 'C', 'C'));
$pdf->Row(Array(utf8_decode("Total Pagado: "), utf8_decode("$ " . number_format($totalpagado, 2, '.', ',')), utf8_decode($c_monedapago)));
$pdf->Ln(2);

if ($uuid == "") {
    $pdf->SetFont('Arial', 'BI', 8);
    $pdf->Write(10, utf8_decode("*Este documento no posee efectos fiscales"), '');
} else if ($cancelado == '0') {
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
    $pdf->Write(10, utf8_decode("N° Certificado SAT: "), '');
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
    $pdf->rgbfd0 = $rgbfd[0];
    $pdf->rgbfd1 = $rgbfd[1];
    $pdf->rgbfd2 = $rgbfd[2];
    $pdf->SetTextColor($rgbtxt[0], $rgbtxt[1], $rgbtxt[2]);
    $pdf->RowR(Array(utf8_decode($sellocfdi), "", utf8_decode($selloSat), "", utf8_decode($cadenaoriginal)));
    $pdf->SetFont('Arial', '', 9);
    $pdf->Write(8, utf8_decode("Este documento es una representacion impresa de un cfdi-."), '');
} else if ($cancelado == '1') {
    $pdf->SetFont('Arial', 'BI', 8);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(10, 3, '*El pago ' . $foliopago . ' ha sido oficialmente cancelado', 0, 0, 'L', 0);
}

$pdf->isFinished = true;

if (isset($_GET['pago'])) {
    $pdf->Output($foliopago . '_' . $rfcemisor . '_' . $uuid . '.pdf', 'I');
} else if (isset($_POST['idpago'])) {
    require_once '../com.sine.modelo/SendMail.php';
    $sm = new SendMail();
    $sm->setIdcliente($idcliente);
    $sm->setRfcemisor($rfcemisor);
    $sm->setRazonsocial($razonemisor);
    $sm->setFolio($foliopago);
    $sm->setUuid($uuid);
    $sm->setChmail1($_POST['ch1']);
    $sm->setChmail2($_POST['ch2']);
    $sm->setChmail3($_POST['ch3']);
    $sm->setChmail4($_POST['ch4']);
    $sm->setChmail5($_POST['ch5']);
    $sm->setChmail6($_POST['ch6']);
    $sm->setMailalt1($_POST['mailalt1']);
    $sm->setMailalt2($_POST['mailalt2']);
    $sm->setMailalt3($_POST['mailalt3']);
    $sm->setMailalt4($_POST['mailalt4']);
    $sm->setMailalt5($_POST['mailalt5']);
    $sm->setMailalt6($_POST['mailalt6']);
    $sm->setPdfstring($pdf->Output('S'));
    $sm->setCfdistring($cfdistring);
    $send = $cf->mail($sm);
    echo $send;
}
