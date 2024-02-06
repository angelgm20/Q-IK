<?php


require_once '../vendor/autoload.php';
require_once '../pdf/fpdf.php';
require_once '../com.sine.controlador/ControladorConfiguracion.php';

setlocale(LC_MONETARY, 'es_MX.UTF-8');

class PDF extends FPDF {

    var $titulopagina;
    var $idencabezado;
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
    var $styles;
    var $rowtextcolor;
    var $sizes;
    var $lineHeight;
    var $rowborder;
    var $borderfill;
    var $Tfolio;
    var $rfc;
    var $firma;
    var $nmfirma;
    var $chfirmar;
    var $isFinished;
    var $tipofactura;
    var $heightB = 0;
    var $ycliente;
    var $rgbfd0;
    var $rgbfd1;
    var $rgbfd2;

    var $titulo;
    var $clrtitulo;
    var $colorcelda;
    var $tel1;
    var $tel2;
    var $clrpie;
    var $margen;
    var $body;

    var $chkdata = 0;
    var $nombreempresa = '';
    var $razonsocial = '';
    var $direccion = '';
    var $RFC = '';
   
    function SetWidths($w) {
        $this->widths = $w;
    }

    function SetAligns($a) {
        $this->aligns = $a;
    }

    function SetStyles($s = '') {
        $this->styles = $s;
    }

    function setRowColorText($t = "#000000") {
        $this->rowtextcolor = $t;
    }

    function SetSizes($sz = 9) {
        $this->sizes = $sz;
    }

    function SetLineHeight($h) {
        $this->lineHeight = $h;
    }

    function SetRowBorder($b = 'NB', $f = 'D') {
        $this->rowborder = $b;
        $this->borderfill = $f;
    }

    function Row($data) {
        require_once '../com.sine.controlador/ControladorConfiguracion.php';
        $cc = new ControladorConfiguracion();
        // number of line
        $nb = 0;
        // loop each data to find out greatest line number in a row.
        for ($i = 0; $i < count($data); $i++) {
            // NbLines will calculate how many lines needed to display text wrapped in specified width.
            // then max function will compare the result with current $nb. Returning the greatest one. And reassign the $nb.
            //$nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
            $nb = max($nb, $this->NbLines($this->widths[$i] ?? 0, $data[$i] ?? ''));
        }

        //multiply number of line with line height. This will be the height of current row
        $lh = $this->lineHeight;
        $h = $lh * $nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of current row
        for ($i = 0; $i < count($data); $i++) {
            // width of the current col
            //$w = $this->widths[$i];
            $w = $this->widths[$i] ?? 0;
            // alignment of the current col. if unset, make it left.
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : (isset($this->aligns[0]) ? $this->aligns[0] : 'L');
            $s = isset($this->styles[$i]) ? $this->styles[$i] : (isset($this->styles[0]) ? $this->styles[0] : '');
            $tc = isset($this->rowtextcolor[$i]) ? $this->rowtextcolor[$i] : (isset($this->rowtextcolor[0]) ? $this->rowtextcolor[0] : "#000000");
            $sz = isset($this->sizes[$i]) ? $this->sizes[$i] : (isset($this->sizes[0]) ? $this->sizes[0] : 9);
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            if ($this->rowborder == 'B') {
                $this->Rect($x, $y, $w, $h, $this->borderfill);
            } else if ($this->rowborder == 'R') {
                $this->RoundedRect($x, $y, $w, $h, 2, $this->borderfill);
            }

            //Print the text
            $this->SetFont('Arial', $s, $sz);
            $rgb = explode("-", $cc->hex2rgb($tc));
            $this->SetTextColor($rgb[0], $rgb[1], $rgb[2]);
            $this->MultiCell($w, $lh, $data[$i], 0, $a);
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
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
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
        if ($this->idencabezado == '12') {
            $this->SetFont('Arial', '', 19);
            $rgbc = explode("-", $this->celdatitulo);
            $rgbt = explode("-", $this->colortitulo);
            $this->SetFillColor($rgbc[0], $rgbc[1], $rgbc[2]);
            $this->SetTextColor($rgbt[0], $rgbt[1], $rgbt[2]);
            $logo = "../temporal/tmp/$this->imglogo";
            if (!file_exists($logo)) {
                $logo = "../img/logo/$this->imglogo";
            }
            $dimensiones = getimagesize($logo);
            $width = $dimensiones[0];
            $height = $dimensiones[1];
            $height = ($height * 20) / $width;
            
            if ($height > 25) {
                $height = 25;
            }

            if($this->chkdata == 1) {
                
                $this->SetRowBorder('NB');
                $this->setRowColorText(array($this->clrtitulo));
                $this->SetY(3);
                $this->SetX($this->margen);
                $this->SetWidths(Array(bcdiv(($this->body * 0.33),'1',0), bcdiv(($this->body * 0.66),'1',0)+1));
                if($this->body >= 76){
                    $this->SetSizes(array(1, 10));
                    $this->SetLineHeight(5);
                }else {
                    $this->SetSizes(array(1, 7));
                    $this->SetLineHeight(4);
                }                
                
                $this->SetStyles(array('', 'B'));
                $this->SetAligns('C', 'C');
                if($this->body >= 76){
                    $this->Row(Array($this->Image($logo,($this->margen+5), 3, 20, $height), utf8_decode($this->nombreempresa)));
                }else{
                    $this->Row(Array($this->Image($logo,($this->margen), 3, bcdiv(($this->body * 0.33),'1',0), $height), utf8_decode($this->nombreempresa)));
                }
                
                $this->SetX($this->margen);
                $this->Row(Array('', utf8_decode($this->razonsocial)));
                $this->SetX($this->margen);
                $this->Row(Array('', utf8_decode($this->direccion)));
                $this->SetX($this->margen);
                $this->Row(Array('', utf8_decode($this->RFC)));
                $this->SetX($this->margen);
                $this->RoundedRect($this->margen, 30, $this->body, 2, 1, 'F');
    
            } else if($this->chkdata == 2) {
                
                $this->SetRowBorder('NB');
                $this->SetY(3);
                $this->SetX($this->margen);
                $this->SetWidths(Array($this->body));
                $this->SetSizes(array(10));
                $this->SetLineHeight(25);
                $this->SetAligns('C');
                $this->Row(Array($this->Image($logo,(bcdiv(($this->body * 0.33),'1',0)+2), 3, 20, $height)));
                $this->RoundedRect($this->margen, 30, $this->body, 2, 1, 'F');
    
            } else if($this->chkdata == 3){
    
                $this->SetRowBorder('NB');
                $this->setRowColorText(array($this->clrtitulo));
                $this->SetY(3);
                $this->SetX($this->margen);
                $this->SetWidths(Array($this->body));
                if($this->body >= 76){
                    $this->SetSizes(array(12));
                }else{
                    $this->SetSizes(array(9));
                }                
                $this->SetLineHeight(5);
                $this->SetStyles(array('B'));
                $this->SetAligns('C');
                $this->Row(Array(utf8_decode($this->nombreempresa)));
                $this->SetX($this->margen);
                $this->Row(Array(utf8_decode($this->razonsocial)));
                $this->SetX($this->margen);
                $this->Row(Array(utf8_decode($this->direccion)));
                $this->SetX($this->margen);
                $this->Row(Array(utf8_decode($this->RFC)));
                $this->SetX($this->margen);
                $this->RoundedRect($this->margen, 30, $this->body, 2, 1, 'F');
    
            }
        } else {
            require_once '../com.sine.controlador/ControladorConfiguracion.php';
            $cc = new ControladorConfiguracion();

            $rgbt = explode("-", $cc->hex2rgb($this->clrtitulo));
            $rgbc = explode("-", $cc->hex2rgb($this->colorcelda));

            $this->SetFont('Arial', '', 19);
            $this->SetFillColor($rgbc[0], $rgbc[1], $rgbc[2]);
            $this->SetTextColor($rgbt[0], $rgbt[1], $rgbt[2]);
            $logo = "../temporal/tmp/$this->imglogo";
            if (!file_exists($logo)) {
                $logo = "../img/logo/$this->imglogo";
            }
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
            $this->Write(8, $this->titulo);

            $this->SetX(160);
            $this->RoundedRect(160, $this->GetY(), 45, 8, 4, 'F');
            $this->SetX(173.5);
            $this->Write(8, 'Folio');

            $this->SetY(18);
            $this->SetX(160);
            $this->SetTextColor(255, 0, 0);
            $this->SetFont('Arial', 'B', 14);
            $this->Cell(45, 8, utf8_decode('001'), 0, 0, 'C', false);

            $this->Ln(26);
        }
    }

    function Footer() {
        if ($this->idencabezado == '12') {
            
        } else {
            require_once '../com.sine.controlador/ControladorConfiguracion.php';
            $cc = new ControladorConfiguracion();
            $rgb = explode("-", $cc->hex2rgb($this->clrpie));
            $pagin = "";
            if ($this->chnum == '1') {
                $pagin = utf8_decode('Pagina ' . $this->PageNo() . ' de {nb}');
            }

            $this->SetY(-18);
            if ($this->isFinished) {
                $chfirmar = $this->chfirmar;
                if ($chfirmar == '1') {
                    $firma = $cc->getFirma($this->iddatos);
                    $this->Image($firma, 75, ($this->GetY() - 25), 60, 0, 'png');
                }
            }
            $this->SetTextColor($rgb[0], $rgb[1], $rgb[2]);
            $this->SetFont('Arial', 'B', 8);
            $this->Cell(65, 4, $this->pagina, 0, 0, 'L');
            $phone = "Tel: " . $this->tel1;
            if ($this->tel2 != "") {
                $this->Cell(65, 4, '', 0, 0, 'C');
                $this->Cell(65, 4, "Tel: " . $this->tel1, 0, 0, 'R');
                $phone = "Tel: " . $this->tel2;
            }
            $this->Ln(4);
            $this->Cell(65, 4, $this->correo, 0, 0, 'L');
            $this->Cell(65, 4, $pagin, 0, 0, 'C');
            $this->Cell(65, 4, $phone, 0, 0, 'R');
        }
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

    function TextWithDirection($x, $y, $txt, $direction = 'R') {
        if ($direction == 'R')
            $s = sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET', 1, 0, 0, 1, $x * $this->k, ($this->h - $y) * $this->k, $this->_escape($txt));
        elseif ($direction == 'L')
            $s = sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET', -1, 0, 0, -1, $x * $this->k, ($this->h - $y) * $this->k, $this->_escape($txt));
        elseif ($direction == 'U')
            $s = sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET', 0, 1, -1, 0, $x * $this->k, ($this->h - $y) * $this->k, $this->_escape($txt));
        elseif ($direction == 'D')
            $s = sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET', 0, -1, 1, 0, $x * $this->k, ($this->h - $y) * $this->k, $this->_escape($txt));
        else
            $s = sprintf('BT %.2F %.2F Td (%s) Tj ET', $x * $this->k, ($this->h - $y) * $this->k, $this->_escape($txt));
        if ($this->ColorFlag)
            $s = 'q ' . $this->TextColor . ' ' . $s . ' Q';
        $this->_out($s);
    }

    function TextWithRotation($x, $y, $txt, $txt_angle, $font_angle = 0) {
        $font_angle += 90 + $txt_angle;
        $txt_angle *= M_PI / 180;
        $font_angle *= M_PI / 180;

        $txt_dx = cos($txt_angle);
        $txt_dy = sin($txt_angle);
        $font_dx = cos($font_angle);
        $font_dy = sin($font_angle);

        $s = sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET', $txt_dx, $txt_dy, $font_dx, $font_dy, $x * $this->k, ($this->h - $y) * $this->k, $this->_escape($txt));
        if ($this->ColorFlag)
            $s = 'q ' . $this->TextColor . ' ' . $s . ' Q';
        $this->_out($s);
    }

}


$cc = new ControladorConfiguracion();

$idencabezado = $_POST['idencabezado'];
$titulo = $_POST['titulo'];
$clrtitulo = $_POST['clrtitulo'];
$colorcelda = $_POST['colorcelda'];
$clrcuadro = $_POST['clrcuadro'];
$clrsub = $_POST['clrsub'];
$clrfdatos = $_POST['clrfdatos'];
$txtbold = $_POST['txtbold'];
$clrtxt = $_POST['clrtxt'];
$colorhtabla = $_POST['colorhtabla'];
$tittabla = $_POST['tittabla'];
$pagina = $_POST['pagina'];
$correo = $_POST['correo'];
$tel1 = $_POST['tel1'];
$tel2 = $_POST['tel2'];
$clrpie = $_POST['clrpie'];
$imagen = $_POST['imagen'];
$chnum = $_POST['chnum'];
$chlogo = $_POST['chlogo'];
$observaciones = $_POST['observaciones'];
$anchoTicket = $_POST['widthticket'];
$titulo2 = $_POST['titulo2'];
$rfcempresa = $_POST['rfcempresa'];
$direccion = $_POST['direccion'];
$razonsocial = $_POST['razonsocial'];
$nombreempresa = $_POST['nombreempresa'];
$chkdata = $_POST['chkdata'];

/*$rgbctit = explode("-", $cc->hex2rgb($clrtitulo));
/*$rgbcelda = explode("-", $cc->hex2rgb($colorcelda));*/
$rgbc = explode("-", $cc->hex2rgb($clrcuadro));
$rgbs = explode("-", $cc->hex2rgb($clrsub));
$rgbfd = explode("-", $cc->hex2rgb($clrfdatos));
$rgbbld = explode("-", $cc->hex2rgb($txtbold));
$rgbtxt = explode("-", $cc->hex2rgb($clrtxt));
$rgbt = explode("-", $cc->hex2rgb($colorhtabla));
$rgbtt = explode("-", $cc->hex2rgb($tittabla));

if( $idencabezado == 12){
    $pdf = new PDF('P', 'mm', array($anchoTicket, 150));
    if($anchoTicket >= 80){
        $pdf->margen = bcdiv(($anchoTicket - 76) / 2, '1', 1);
        $pdf->body = 76;
    }else{
        $pdf->margen = 5;
        $pdf->body = ($anchoTicket - 10);
    }
    
    $pdf->chkdata = $chkdata;
    $pdf->nombreempresa = $nombreempresa;
    $pdf->razonsocial = $razonsocial;
    $pdf->direccion = $direccion;
    $pdf->RFC = $rfcempresa;
} else {
    $pdf = new PDF('P', 'mm', 'Letter');
}

$pdf->idencabezado = $idencabezado;
$pdf->imglogo = $imagen;
$pdf->titulo = $titulo;
$pdf->clrtitulo = $clrtitulo;
$pdf->colorcelda = $colorcelda;
$pdf->colortitulo = $cc->hex2rgb($clrtitulo);
$pdf->celdatitulo = $cc->hex2rgb($colorcelda);

$pdf->pagina = $pagina;
$pdf->correo = $correo;
$pdf->tel1 = $tel1;
$pdf->tel2 = $tel2;
$pdf->clrpie = $clrpie;
$pdf->chnum = $chnum;

$pdf->chfirmar = '';
$pdf->iddatos = '';
$pdf->tipofactura = '';

$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);
$pdf->SetFillColor($rgbc[0], $rgbc[1], $rgbc[2]);
$pdf->SetTextColor($rgbs[0], $rgbs[1], $rgbs[2]);

switch ($idencabezado) {
    case 1:
        $pdf->SetWidths(Array(80));
        $pdf->SetLineHeight(8);
        $pdf->SetY(36.3);
        $pdf->RowT(Array("Datos del Emisor"));
        $pdf->Ln(1);

        $pdf->SetFillColor($rgbfd[0], $rgbfd[1], $rgbfd[2]);
        $pdf->RoundedRect(10, 45.3, 195, 19, 2, 'F');

        $pdf->SetLineHeight(4.5);

        $pdf->SetY(45.3);

        $pdf->SetX(10);
        $pdf->SetWidths(Array(15, 94.5, 30, 55));
        $pdf->SetRowBorder('');
        $pdf->setRowColorText(array($txtbold, $clrtxt, $txtbold, $clrtxt));
        $pdf->SetLineHeight(0.1);
        $pdf->Row(Array('', '', '', ''));
        $pdf->SetStyles(array('B', '', 'B', ''));
        $pdf->SetLineHeight(4.5);
        $pdf->Row(Array('Nombre', utf8_decode('Contribuyente'), 'No Certificado', utf8_decode('007')));

        $pdf->SetX(10);
        $pdf->setRowColorText(array($txtbold, $clrtxt, $txtbold, $clrtxt));
        $pdf->SetStyles(array('B', '', 'B', ''));
        $pdf->Row(Array('RFC', utf8_decode('XAXX010101000'), 'Regimen Fiscal', utf8_decode('01 Regimen')));

        $pdf->SetX(10);
        $pdf->SetStyles(array('B', '', 'B', ''));
        $pdf->setRowColorText(array($txtbold, $clrtxt, $txtbold, $clrtxt));
        $pdf->Row(Array('Fecha', utf8_decode('01/01/2021'), 'T. Comprobante', utf8_decode('I Ingreso')));

        $pdf->SetWidths(Array(35, 77.5, 25, 58));
        $pdf->SetStyles(array('B', '', 'B', ''));
        $pdf->setRowColorText(array($txtbold, $clrtxt, $txtbold, $clrtxt));
        $pdf->Row(Array('Lugar de Expedicion', utf8_decode('76800'), '', ''));
        $pdf->Ln(1);

        $pdf->SetFillColor($rgbc[0], $rgbc[1], $rgbc[2]);
        $pdf->SetTextColor($rgbs[0], $rgbs[1], $rgbs[2]);
        $pdf->SetFont('Arial', '', 15);
        $pdf->SetWidths(Array(80));
        $pdf->SetLineHeight(8);
        $pdf->RowT(Array("Datos del Cliente"));

        $pdf->SetFillColor($rgbfd[0], $rgbfd[1], $rgbfd[2]);
        $pdf->RoundedRect(10, ($pdf->GetY() + 0.7), 195, 14, 2, 'F');

        $pdf->SetY(($pdf->GetY() + 0.7));
        $pdf->SetWidths(Array(17, 94.5, 23, 60));
        $pdf->SetLineHeight(0.1);
        $pdf->Row(Array('', '', '', ''));
        $pdf->SetLineHeight(4.5);
        $pdf->SetStyles(array('B', '', 'B', ''));
        $pdf->setRowColorText(array($txtbold, $clrtxt, $txtbold, $clrtxt));
        $pdf->Row(Array('Nombre', 'Receptor', 'Uso de CFDI', 'P01 Por Definir'));

        $pdf->SetStyles(array('B', '', 'B', ''));
        $pdf->setRowColorText(array($txtbold, $clrtxt, $txtbold, $clrtxt));
        $pdf->Row(Array('RFC', 'XAXX010101000', '', ''));

        $pdf->SetWidths(Array(17, 174.5));
        $pdf->SetLineHeight(4.5);
        $pdf->SetStyles(array('B', ''));
        $pdf->setRowColorText(array($txtbold, $clrtxt));
        $pdf->Row(Array('Direccion', utf8_decode('Calle Falsa #123, Colonia: desconocida, Municipio, Estado')));
        $pdf->Ln(1);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetTextColor($rgbbld[0], $rgbbld[1], $rgbbld[2]);
        $pdf->RoundedRect(10, $pdf->GetY(), 195, 5.5, 2, 'D');
        $pdf->SetX(92.5);
        $pdf->Write(6, 'CONCEPTOS');
        $pdf->Ln(7);

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

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetStyles('');
        $pdf->setRowColorText();
        $pdf->SetRowBorder('B');
        $pdf->SetAligns('C');
        $pdf->Row(Array('1', '01', 'H87 Pieza', 'Descripcion de Producto', '', '$ 100.00', '$ 100.00'));

        $pdf->Cell(151, 8, '', 0, 0, 'C', 0);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(22, 8, 'Subtotal ', 1, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(22, 8, '$ 100.00', 1, 0, 'C', 0);
        $pdf->Ln(8);

        $pdf->Cell(151, 8, '', 0, 0, 'C', 0);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(22, 8, 'IVA ', 1, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(22, 8, '$ 16.00', 1, 0, 'C', 0);
        $pdf->Ln(8);

        $pdf->Cell(151, 8, '', 0, 0, 'C', 0);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(22, 8, 'Total ', 1, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(22, 8, '$ 116.00', 1, 0, 'C', 0);
        $pdf->Ln(15);

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(30, 8, 'Importe con Letra ', 0, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 9);
        $letras = NumeroALetras::convertir(bcdiv(116.00, '1', 2), 'pesos', 'centavos');
        $pdf->Cell(26, 8, utf8_decode("$letras 00/100 M.N."), 0, 0, 'L', 0);
        $pdf->Ln(8);


        $pdf->SetFillColor($rgbc[0], $rgbc[1], $rgbc[2]);
        $pdf->RoundedRect(10, $pdf->GetY(), 195, 3, 1.5, 'F');
        break;
    case 2:
        $pdf->SetWidths(Array(80));
        $pdf->SetLineHeight(8);
        $pdf->SetY(36.3);
        $pdf->RowT(Array("Datos del Emisor"));
        $pdf->Ln(1);

        $pdf->SetFillColor($rgbfd[0], $rgbfd[1], $rgbfd[2]);
        $pdf->RoundedRect(10, 45.3, 195, 15, 2, 'F');
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
        $pdf->RowNB(Array('', utf8_decode('Contribuyente'), '', utf8_decode('XAXX010101000')));

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetTextColor($rgbbld[0], $rgbbld[1], $rgbbld[2]);
        $pdf->Write(5, 'Regimen Fiscal');
        $pdf->SetX(147.5);
        $pdf->Write(5, 'Fecha Emision');
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetTextColor($rgbtxt[0], $rgbtxt[1], $rgbtxt[2]);
        $pdf->SetX(10);
        $pdf->RowNB(Array('', utf8_decode('01-Regimen'), '', utf8_decode('01-01-2021')));

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetWidths(Array(24, 170.5));
        $pdf->SetTextColor($rgbbld[0], $rgbbld[1], $rgbbld[2]);
        $pdf->Write(5, 'Direccion');
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetTextColor($rgbtxt[0], $rgbtxt[1], $rgbtxt[2]);
        $pdf->SetX(10);
        $pdf->RowNB(Array('', utf8_decode('Calle Falsa #123, Colonia: desconocida, Municipio, Estado')));

        $pdf->Ln(1);

        $pdf->SetFont('Arial', '', 15);
        $pdf->SetFillColor($rgbc[0], $rgbc[1], $rgbc[2]);
        $pdf->SetTextColor($rgbs[0], $rgbs[1], $rgbs[2]);
        $pdf->SetWidths(Array(80));
        $pdf->SetLineHeight(8);
        $pdf->RowT(Array("Datos del Cliente"));

        $pdf->Ln(0.5);
        $pdf->SetTextColor(0, 0, 0);

        $pdf->SetFillColor($rgbfd[0], $rgbfd[1], $rgbfd[2]);
        $pdf->RoundedRect(10, ($pdf->GetY() + 0.4), 195, 25, 2, 'F');

        $pdf->SetY(($pdf->GetY() + 0.4));
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
        $pdf->RowNB(Array('', utf8_decode('Cliente'), '', utf8_decode('XAXX010101000')));

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetTextColor($rgbbld[0], $rgbbld[1], $rgbbld[2]);
        $pdf->Write(5, 'Uso del CFDI');
        $pdf->SetX(151.5);
        $pdf->Write(5, 'Fecha y Hora');
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetTextColor($rgbtxt[0], $rgbtxt[1], $rgbtxt[2]);
        $pdf->SetX(10);
        $pdf->RowNB(Array('', utf8_decode('P01-Por Definir'), '', utf8_decode('01-01-2021')));

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetTextColor($rgbbld[0], $rgbbld[1], $rgbbld[2]);
        $pdf->Write(5, 'Metodo, Forma y');
        $pdf->SetX(151.5);
        $pdf->Write(5, 'Moneda');
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetTextColor($rgbtxt[0], $rgbtxt[1], $rgbtxt[2]);
        $pdf->SetX(10);
        $pdf->RowNB(Array('', 'XAXX010101000', '', utf8_decode('MXN')));

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetTextColor($rgbbld[0], $rgbbld[1], $rgbbld[2]);
        $pdf->Write(5, 'Condiciones de pago');
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetTextColor($rgbtxt[0], $rgbtxt[1], $rgbtxt[2]);
        $pdf->SetX(10);
        $pdf->RowNB(Array('', utf8_decode('PPD-Pago en parcialidades o diferido '), '', ''));

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetTextColor($rgbbld[0], $rgbbld[1], $rgbbld[2]);
        $pdf->Write(5, 'Direccion');
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetWidths(Array(30, 177.8));
        $pdf->SetLineHeight(4.5);
        $pdf->SetTextColor($rgbtxt[0], $rgbtxt[1], $rgbtxt[2]);
        $pdf->SetX(10);
        $pdf->RowNB(Array('', utf8_decode('Calle Falsa #123, Colonia: desconocida, Municipio, Estado')));
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

        $pdf->Ln(1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Row(Array(utf8_decode('00000000-0000-0000-0000-000000000000'), utf8_decode('F0001'), utf8_decode("PPD"), utf8_decode('$ ' . number_format("1000", 2, '.', ',')), utf8_decode("MXN/1"), utf8_decode('1'), utf8_decode('$ ' . number_format("1000", 2, '.', ',')), utf8_decode('$ ' . number_format('1000', 2, '.', ',')), utf8_decode('$ ' . number_format('0', 2, '.', ','))));

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetX(140.7);
        $pdf->SetWidths(Array(25, 20, 20));
        $pdf->SetLineHeight(4.5);
        $pdf->SetAligns(array('R', 'C', 'C'));
        $pdf->Row(Array(utf8_decode("Total Pagado: "), utf8_decode("$ " . number_format('1000', 2, '.', ',')), utf8_decode('MXN')));
        $pdf->Ln(2);
        break;
    case 3:
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetWidths(Array(40));
        $pdf->SetLineHeight(8);
        $pdf->SetY(36.3);
        $pdf->RowT(Array("FECHA: 01/01/2021"));

        $pdf->SetTextColor($rgbs[0], $rgbs[1], $rgbs[2]);
        $pdf->SetFont('Arial', '', 15);
        $pdf->SetWidths(Array(80));
        $pdf->SetLineHeight(8);
        $pdf->RowT(Array("Datos de la Cotizacion"));

        $pdf->SetWidths(array(15, 72, 32, 75.5));
        $pdf->SetLineHeight(4.5);
        $pdf->SetFont('Arial', '', 9);
        $pdf->setRowColorText(array($txtbold, $clrtxt, $txtbold, $clrtxt));
        $pdf->SetStyles(array('B', '', 'B', ''));
        $pdf->SetAligns(array('L'));
        $pdf->SetRowBorder();
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetX(10);
        $pdf->SetFillColor($rgbfd[0], $rgbfd[1], $rgbfd[2]);
        $pdf->RoundedRect(10, $pdf->GetY(), 195, 10, 2, 'F');
        $pdf->Row(Array("Nombre", utf8_decode('Cliente cotizacion'), "Lugar de Expedicion", utf8_decode('Calle Falsa #123, Colonia: Imaginaria, Municipio, Estado')));

        $pdf->Ln(3);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetTextColor(23, 23, 124);
        $pdf->RoundedRect(10, $pdf->GetY(), 195, 5.5, 2, 'D');
        $pdf->SetX(92.5);
        $pdf->Write(6, 'CONCEPTOS');
        $pdf->Ln(6.7);

        $pdf->SetFillColor($rgbt[0], $rgbt[1], $rgbt[2]);
        $pdf->SetTextColor($rgbtt[0], $rgbtt[1], $rgbtt[2]);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetWidths(Array(10, 22, 25, 19, 44, 35, 20, 20));
        $pdf->SetLineHeight(4.5);
        $pdf->RoundedRect(10, $pdf->GetY(), 195, 4.5, 2, 'FD');
        $pdf->RowNBC(Array("Cant", "Codigo", "Imagen", "Unidad", "Descripcion", "Observaciones", "Precio", "Importe"));
        $pdf->SetTextColor(0, 0, 0);

        $file = '../img/monitor.jpg';
        $dimensiones = getimagesize($file);
        $width = $dimensiones[0];
        $height = $dimensiones[1];
        $height = ($height * 25) / $width;
        if ($height > 22.1) {
            $height = 22.1;
        }

        $nj = $height / 4.42;
        $j = "";
        for ($i = 1; $i <= number_format($nj); $i++) {
            $j .= "\n";
        }

        $pdf->Cell(194.5, 24, '', 0, 0, 'C', 0);
        $img = $pdf->Image($file, 42, $pdf->GetY(), 25, $height) . $j;
        $pdf->SetX(-205.9);

        $pdf->setRowColorText($clrtxt);
        $pdf->SetStyles('');
        $pdf->SetAligns('C');
        $pdf->SetRowBorder('B');
        $pdf->Row(Array('1', utf8_decode('01'), $img, 'E48', utf8_decode('Producto'), utf8_decode(''), utf8_decode('$ ' . number_format('100', 2, '.', ',')), utf8_decode('$ ' . number_format('100', 2, '.', ','))));

        $pdf->SetWidths(Array(20, 20));
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetX(165);
        $pdf->Row(Array('Subtotal', utf8_decode('$ ' . number_format('100', 2, '.', ''))));

        $pdf->SetX(165);
        $pdf->Row(Array('IVA', utf8_decode('$ ' . number_format('16', 2, '.', ''))));

        $pdf->SetX(165);
        $pdf->Row(Array('Ret IVA', utf8_decode('$ ' . number_format('4', 2, '.', ''))));

        $pdf->SetX(165);
        $pdf->Row(Array('Total', utf8_decode('$ ' . number_format('112', 2, '.', ''))));
        $pdf->Ln(2);

        $pdf->SetFillColor($rgbc[0], $rgbc[1], $rgbc[2]);
        $pdf->RoundedRect(10, $pdf->GetY(), 195, 3, 1.5, 'F');

        $pdf->Ln(5);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 10);
        $obser = str_replace("<ent>", "\n", $observaciones);
        $pdf->Write(3, utf8_decode('Observaciones: ' . $obser));

        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'I', 9);
        $pdf->SetWidths(Array(85, 85));
        $pdf->SetLineHeight(4.5);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->RowR(Array('Datos Fiscales:', 'Datos Transferencia Bancaria: '));

        $pdf->SetWidths(Array(85, 85));
        $pdf->SetLineHeight(4.5);
        $pdf->RowR(Array(utf8_decode("RFC: XAXX010101000 \nRazon Social: Publico en General \nCalle: Calle Falsa #123 Col. Imaginaria \nLocalidad: Municipio \nEstado: Estado \nCodigo Postal: 76800 \nCorreo: ejemplo@ejemplo \nTel: 4271234567"), utf8_decode("Banco: Banco Olmeca    Sucursal: 123 \nBeneficiario: Yo \nCuenta: 7...8 \nClabe:0987654321  \nN° Tarjeta: Olmecard")));
        break;
    case 4:
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetWidths(Array(65));
        $pdf->SetLineHeight(8);
        $pdf->SetX(140);
        $pdf->RowT(Array(utf8_decode('BUENO POR: $' . bcdiv('100', 1, 2))));

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetWidths(Array(125));
        $pdf->SetLineHeight(8);
        $pdf->SetX(80);
        $pdf->RowT(Array(utf8_decode('San Juan del Rio, Queretaro a 01 de Enero de 2021')));

        $pdf->Ln(8);
        $pdf->SetFont('Arial', '', 15);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Write(8, utf8_decode('Se recibio de Rodrigo Antonio Moran-Clarion la cantidad de $ 555.61 (QUINIENTOS CINCUENTA Y CINCO  PESOS CON SESENTA Y UN  CENTAVOS 61/100 M.N.) por concepto del 50% de anticipo por la cotizacion de servicios con folio COT0008.'));

        $pdf->Ln(4);
        $pdf->SetFont('courier', 'B', 18);
        $pdf->SetTextColor(50, 50, 50);

        $pdf->TextWithRotation(25.5, 143, '01 01 2021', 25.5, 0);
        $pdf->Image('../img/SelloSine2.png', $pdf->GetX(), 100, 70);
        break;
    case 5:
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetWidths(Array(125));
        $pdf->SetLineHeight(6);
        $pdf->SetY(36.3);
        $pdf->SetX(80);
        $pdf->RowT(Array(utf8_decode('San Juan del Rio, Queretaro a 01 de Ene de 2021')));

        $pdf->Ln(2);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetWidths(Array(195));
        $pdf->RowNBC(Array(utf8_decode('Comunicado')));

        $pdf->Ln(3);
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetTextColor($rgbtxt[0], $rgbtxt[1], $rgbtxt[2]);
        //ANGELGM $espL
        $pdf->Write(0 , utf8_decode('Por medio de este comunicado le proporcionamos la siguiente informacion.'));
        $pdf->Ln(5);

        $pdf->Cell(195, 80, '', 0, 0, 'C', 0);
        $pdf->Image('../comunicado/exampleimg.jpg', 10, $pdf->GetY(), 0, 80);
        $pdf->TextWithRotation(25.5, 223, '01 01 2021', 25.5, 0);
        $pdf->Image('../img/SelloSine2.png', 10, 180, 70);
        break;
    case 6: 
        $pdf->SetFont('Arial', '', 15);
        $pdf->SetFillColor($rgbc[0], $rgbc[1], $rgbc[2]);
        $pdf->SetTextColor($rgbs[0], $rgbs[1], $rgbs[2]);
        $pdf->SetWidths(Array(195));
        $pdf->SetLineHeight(8);
        $pdf->RowT(Array('Facturas generadas en el periodo:'));
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetTextColor(23, 23, 124);
        $pdf->RoundedRect(10, $pdf->GetY(), 195, 5.5, 2, 'D');
        $pdf->SetX(80);
        $pdf->Write(6, 'Del 2024-01-12 al 2024-02-15'); //elimino de variable $fechainicio, $fechafin
        $pdf->Ln(8);

        $pdf->SetFillColor($rgbt[0], $rgbt[1], $rgbt[2]);
        $pdf->SetTextColor($rgbtt[0], $rgbtt[1], $rgbtt[2]);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetWidths(Array(20, 26, 30, 30, 28, 18, 18, 25));
        $pdf->SetLineHeight(5);
        $pdf->RoundedRect(10, $pdf->GetY(), 195, 5, 2, 'FD');
        $pdf->RowNBC(Array("Folio", "Fecha creacion", "Emisor", "Cliente", "RFC", "Tipo", "Estado", "Total"));
        $pdf->SetTextColor(0, 0, 0);

        $ivaperiodo = 0;
        $retperiodo = 0;
        $descperiodo = 0;
        $totalPeriodo = 0;
        $totalPendientes = 0;
        $totalCanceladas = 0;
        $totalPagadas = 0;

    
        $nombre_cliente = "";
        if (!empty($tipo) && !empty($cf)) {  //Verificar si las variables están definidas 
        if ($tipo == "" || $tipo == '1' || $tipo == '3') {
            $facturas = $cf->getReporteFactura($f);
            foreach ($facturas as $reporteactual) {
                $fid = $reporteactual['iddatos_factura'];
                $folio = $reporteactual['letra'] . $reporteactual['folio_interno_fac'];
                $fecha = $reporteactual['fecha_creacion'];
                $emisor = $reporteactual['factura_rzsocial'];
                $nombre_cliente = $reporteactual['rzreceptor'];
                $rfc = $reporteactual['rfcreceptor'];
                $estado = $reporteactual['status_pago'];
                $subtotal = $reporteactual['subtotal'];
                $iva = $reporteactual['subtotaliva'];
                $ret = $reporteactual['subtotalret'];
                $totaldescuentos = $reporteactual['totaldescuentos'];
                $total = $reporteactual['totalfactura'];
                $tipofactura = $reporteactual['tipofactura'];
                $tcambio = $reporteactual['tcambio'];
                $monedaF = $reporteactual['id_moneda'];
                $cmoneda = $reporteactual['c_moneda'];

                $divideF = explode("-", $fecha);
                $mes = $cf->translateMonth($divideF[1]);
                $fecha = $divideF[2] . '/' . $mes . '/' . $divideF[0];

                if ($estado == '1') {
                    $estadofac = 'Pagada';
                    $fillcolor = $pdf->SetFillColor(19, 202, 1);
                    $totalPagadas += $cf->totalDivisa($total, $tcambio, $idmonedaC, $monedaF);
                } else if ($estado == '2') {
                    $estadofac = 'Pendiente';
                    $fillcolor = $pdf->SetFillColor(255, 0, 0);
                    $totalPendientes += $cf->totalDivisa($total, $tcambio, $idmonedaC, $monedaF);
                } else if ($estado == '3') {
                    $estadofac = 'Cancelada';
                    $fillcolor = $pdf->SetFillColor(255, 181, 67);
                    $totalCanceladas += $cf->totalDivisa($total, $tcambio, $idmonedaC, $monedaF);
                } else if ($estado == '4') {
                    $estadofac = 'Pago Parcial';
                    $fillcolor = $pdf->SetFillColor(2, 231, 239);
                    $total = $cf->restanteParcial($fid, $total,'f');
                    $totalPendientes += $cf->totalDivisa($total, $tcambio, $idmonedaC, $monedaF);
                }

                if ($tipofactura == '1') {
                    $type = "Factura";
                } else if ($tipofactura == '2') {
                    $type = "Recibo";
                }

                $diviva = explode("<impuesto>", $iva);
                foreach ($diviva as $ivan) {
                    $traslados = $ivan;
                    $divt = explode("-", $traslados);
                    $ivaperiodo += $cf->totalDivisa($divt[0], $tcambio, $idmonedaC, $monedaF);
                }

                $divret = explode("<impuesto>", $ret);
                foreach ($divret as $retn) {
                    $retenciones = $retn;
                    $divr = explode("-", $retenciones);
                    $retperiodo += $cf->totalDivisa($divr[0], $tcambio, $idmonedaC, $monedaF);
                }
                $descperiodo += $cf->totalDivisa($totaldescuentos, $tcambio, $idmonedaC, $monedaF);
                $totalPeriodo += $cf->totalDivisa($total, $tcambio, $idmonedaC, $monedaF);

                $pdf->Row(Array(utf8_decode($folio), utf8_decode($fecha), utf8_decode($emisor), utf8_decode($nombre_cliente), utf8_decode($rfc), utf8_decode($type), utf8_decode($estadofac), utf8_decode('$ ' . number_format($total, 2, '.', ',') . ' ' . $cmoneda)));
            }
        }
    

        if ($tipo == '2' || $tipo == '3') {
            $facturas = $cf->getReporteCarta($f);
            foreach ($facturas as $reporteactual) {
                $fid = $reporteactual['idfactura_carta'];
                $folio = $reporteactual['letra'] . $reporteactual['foliocarta'];
                $fecha = $reporteactual['fecha_creacion'];
                $emisor = $reporteactual['factura_rzsocial'];
                $nombre_cliente = $reporteactual['rzreceptor'];
                $rfc = $reporteactual['rfcreceptor'];
                $estado = $reporteactual['status_pago'];
                $subtotal = $reporteactual['subtotal'];
                $iva = $reporteactual['subtotaliva'];
                $ret = $reporteactual['subtotalret'];
                $totaldescuentos = $reporteactual['totaldescuentos'];
                $total = $reporteactual['totalfactura'];
                $tcambio = $reporteactual['tcambio'];
                $monedaF = $reporteactual['id_moneda'];
                $cmoneda = $reporteactual['c_moneda'];

                $divideF = explode("-", $fecha);
                $mes = $cf->translateMonth($divideF[1]);
                $fecha = $divideF[2] . '/' . $mes . '/' . $divideF[0];

                if ($estado == '1') {
                    $estadofac = 'Pagada';
                    $fillcolor = $pdf->SetFillColor(19, 202, 1);
                    $totalPagadas += $cf->totalDivisa($total, $tcambio, $idmonedaC, $monedaF);
                } else if ($estado == '2') {
                    $estadofac = 'Pendiente';
                    $fillcolor = $pdf->SetFillColor(255, 0, 0);
                    $totalPendientes += $cf->totalDivisa($total, $tcambio, $idmonedaC, $monedaF);
                } else if ($estado == '3') {
                    $estadofac = 'Cancelada';
                    $fillcolor = $pdf->SetFillColor(255, 181, 67);
                    $totalCanceladas += $cf->totalDivisa($total, $tcambio, $idmonedaC, $monedaF);
                } else if ($estado == '4') {
                    $estadofac = 'Pago Parcial';
                    $fillcolor = $pdf->SetFillColor(2, 231, 239);
                    $total = $cf->restanteParcial($fid, $total,'c');
                    $totalPendientes += $cf->totalDivisa($total, $tcambio, $idmonedaC, $monedaF);
                }

                $diviva = explode("<impuesto>", $iva);
                foreach ($diviva as $ivan) {
                    $traslados = $ivan;
                    $divt = explode("-", $traslados);
                    $ivaperiodo += $cf->totalDivisa($divt[0], $tcambio, $idmonedaC, $monedaF);
                }

                $divret = explode("<impuesto>", $ret);
                foreach ($divret as $retn) {
                    $retenciones = $retn;
                    $divr = explode("-", $retenciones);
                    $retperiodo += $cf->totalDivisa($divr[0], $tcambio, $idmonedaC, $monedaF);
                }
                $descperiodo += $cf->totalDivisa($totaldescuentos, $tcambio, $idmonedaC, $monedaF);
                $totalPeriodo += $cf->totalDivisa($total, $tcambio, $idmonedaC, $monedaF);

                $pdf->Row(Array(utf8_decode($folio), utf8_decode($fecha), utf8_decode($emisor), utf8_decode($nombre_cliente), utf8_decode($rfc), utf8_decode('Carta'), utf8_decode($estadofac), utf8_decode('$ ' . number_format($total, 2, '.', ',') . ' ' . $cmoneda)));
            }
        }
        }// $tipo, $cf

        $pdf->Ln(3);
        $pdf->SetFillColor($rgbt[0], $rgbt[1], $rgbt[2]);
        $pdf->SetTextColor($rgbtt[0], $rgbtt[1], $rgbtt[2]);
        $pdf->SetWidths(Array(45, 45, 45));
        $pdf->SetLineHeight(6);
        $pdf->SetX(70);
        $pdf->RowR(Array('Total Facturas Pagadas', 'Total Facturas Pendientes', 'Total Facturas Canceladas'));

        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetX(70);
        $pdf->RowR(Array(
            utf8_decode('$ ' . number_format($totalPagadas, 2, '.', ',') . ' ' . '120'), 
            utf8_decode('$ ' . number_format($totalPendientes, 2, '.', ',') . ' ' . '130'), 
            utf8_decode('$ ' . number_format($totalCanceladas, 2, '.', ',') . ' ' . '140')));

        if ($ivaperiodo > 0) {
            $pdf->Ln(8);
            $pdf->SetFillColor($rgbt[0], $rgbt[1], $rgbt[2]);
            $pdf->SetTextColor($rgbtt[0], $rgbtt[1], $rgbtt[2]);
            $pdf->Cell(120, 8, '', 0, 0, 'C', 0);
            $pdf->Cell(40, 8, 'Impuestos Trasladados', 1, 0, 'C', 1);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(35, 8, utf8_decode('$ ' . number_format($ivaperiodo, 2, '.', ',')), 1, 0, 'C', 0);
        }
        if ($retperiodo > 0) {
            $pdf->Ln(8);
            $pdf->SetFillColor($rgbt[0], $rgbt[1], $rgbt[2]);
            $pdf->SetTextColor($rgbtt[0], $rgbtt[1], $rgbtt[2]);
            $pdf->Cell(120, 8, '', 0, 0, 'C', 0);
            $pdf->Cell(40, 8, 'Impuestos Retenidos', 1, 0, 'C', 1);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(35, 8, utf8_decode('$ ' . number_format($retperiodo, 2, '.', ',')), 1, 0, 'C', 0);
        }
        if ($descperiodo > 0) {
            $pdf->Ln(8);
            $pdf->SetFillColor($rgbt[0], $rgbt[1], $rgbt[2]);
            $pdf->SetTextColor($rgbtt[0], $rgbtt[1], $rgbtt[2]);
            $pdf->Cell(120, 8, '', 0, 0, 'C', 0);
            $pdf->Cell(40, 8, 'Total Descuentos', 1, 0, 'C', 1);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(35, 8, utf8_decode('$ ' . number_format($descperiodo, 2, '.', ',')), 1, 0, 'C', 0);
        }

        $pdf->Ln(8);
        $pdf->SetFillColor($rgbt[0], $rgbt[1], $rgbt[2]);
        $pdf->SetTextColor($rgbtt[0], $rgbtt[1], $rgbtt[2]);
        $pdf->Cell(120, 8, '', 0, 0, 'C', 0);
        $pdf->Cell(40, 8, 'Total del Periodo en ' . '12', 1, 0, 'C', 1);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(35, 8, utf8_decode('$ ' . number_format($totalPeriodo, 2, '.', ',')), 1, 0, 'C', 0);

        /*$cliente = "";
        if ($clienteB != "") {
            $cliente = $nombre_cliente;
        }*/
                $cliente = "";
        if (isset($clienteB) && $clienteB != "") {
            $cliente = $nombre_cliente;
        }

        //$pdf->Output('Reporte' . $fechainicio . '-' . $fechafin . '' . $cliente . '' . $est . '.pdf', 'I');
        break;
    case 7:
        $pdf->SetFont('Arial', '', 15);
        $pdf->SetY(36.3);
        $pdf->SetFillColor($rgbc[0], $rgbc[1], $rgbc[2]);
        $pdf->SetTextColor($rgbs[0], $rgbs[1], $rgbs[2]);
        $pdf->SetWidths(Array(195));
        $pdf->SetLineHeight(8);
        $pdf->RowT(Array('Pagos generados en el periodo:'));
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetTextColor(23, 23, 124);
        $pdf->RoundedRect(10, $pdf->GetY(), 195, 5.5, 2, 'D');
        $pdf->SetX(80);
        $pdf->Write(6, 'Del 2024-01-12 al 2024-02-15'); //variable no definida intercambio
        $pdf->Ln(8);

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetFillColor($rgbt[0], $rgbt[1], $rgbt[2]);
        $pdf->SetTextColor($rgbtt[0], $rgbtt[1], $rgbtt[2]);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetWidths(Array(20, 30, 32, 32, 30, 25, 26));
        $pdf->SetLineHeight(5);
        $pdf->RoundedRect(10, $pdf->GetY(), 195, 5, 2, 'FD');
        $pdf->RowNBC(Array(utf8_decode("N° de Folio") ,"Facturas Pagadas","Emisor","Cliente", "RFC Cliente", "Fecha de Pago", "Total Pago"));

        $pdf->SetTextColor(0, 0, 0);
        $totalpagado = 0;
        $pdf->SetFont('Arial', '', 9); //fin visual

            if (isset($pagos) && is_array($pagos)) { // Verificar si $pagos es un array antes de iterar sobre él
        foreach ($pagos as $reporteactual) {
            $idpago = $reporteactual['idpago'];
            $folio = $reporteactual['letra'].$reporteactual['foliopago'];
            $fechapago = $reporteactual['fechapago'];
            $horapago = $reporteactual['horapago'];
            $emisor = $reporteactual['emisor'];
            $nombre_cliente = $reporteactual['cliente'];
            $rfc = $reporteactual['rfc'];
            $total = $reporteactual['totalpagado'];
            $tcambio = $reporteactual['pago_tcambio'];
            $monedaF = $reporteactual['pago_idmoneda'];
            $cmoneda = $reporteactual['c_moneda'];
            
            $divFP = explode("-", $fechapago);
            $mes = $cf->translateMonth($divFP[1]);
            $fechapago = $divFP[2] . '/' . $mes . "/" . $divFP[0];
            $cfdis = $cf->getCfdisPDF($idpago);

            $totalpagado += $cf->totalDivisa($total, $tcambio, $idmonedaC, $monedaF);

            $pdf->SetTextColor(0, 0, 0);
            $pdf->Row(Array($folio, $cfdis, utf8_decode($emisor), utf8_decode($nombre_cliente), utf8_decode($rfc), utf8_decode("$fechapago - $horapago"), utf8_decode('$ ' . number_format($total, 2, '.', ',').' '.$cmoneda)));
        }
        } //cierra

        /*if ($clienteB != "") {
            $nombrecliente = $nombre_cliente;
        }*/
        if (isset($clienteB) && $clienteB != "") {  // Verifica si $clienteB está definida y no es vacía
            $nombrecliente = $nombre_cliente;
        }
        
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Ln(8);
        $pdf->Cell(120, 8, '', 0, 0, 'C', 0);
        $pdf->SetTextColor($rgbtt[0], $rgbtt[1], $rgbtt[2]);
        $cmonedaT = '344'; //añade valor a la variable 
        $pdf->Cell(40, 8, 'Total del Periodo en '.$cmonedaT, 1, 0, 'C', 1);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(35, 8, utf8_decode('$ ' . number_format($totalpagado, 2, '.', ',')), 1, 0, 'C', 0);
        //$pdf->Output('Reporte' . $fechainicio . '-' . $fechafin . '' . $nombrecliente . '.pdf', 'I');

        break;
    case 8:
        $pdf->SetFont('Helvetica', '', 15);
        $pdf->SetFillColor($rgbc[0], $rgbc[1], $rgbc[2]);
        $pdf->SetTextColor($rgbs[0], $rgbs[1], $rgbs[2]);

        $pdf->SetWidths(Array(195));
        $pdf->SetLineHeight(8);
        $pdf->SetY(36.3);
        $pdf->RowT(Array("Reporte Bimestral de Impuestos facturados y de recargo"));
        $pdf->Ln(1);

        $type = 2; //añad la varibale erronea
        $pic = "../img/EjemplosEnc/graficaa.jpeg";

        if ($type == "1" ) {
            $pdf->Image($pic, 8, 45, 96, 0, 'png');
            $pdf->Image($pic2, 110, 45, 96, 0, 'png');
            $pdf->Ln(76);
            $pdf->Image($pic3, 60, $pdf->GetY(), 96, 0, 'png');
        } else if ($type == "2") {
            $pdf->Image($pic, 10, 45, 195, 0, 'jpeg');
        }
        break;
    case 9:
        break;
    case 10:
        $pdf->SetFont('Arial', '', 15);
        $pdf->SetFillColor($rgbc[0], $rgbc[1], $rgbc[2]);
        $pdf->SetTextColor($rgbs[0], $rgbs[1], $rgbs[2]);
        $pdf->SetWidths(Array(195));
        $pdf->SetLineHeight(8);
        $pdf->SetY(36.3);
        $pdf->RowT(Array("Ventas generadas en el periodo:"));
        $pdf->SetTextColor(23, 23, 124);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetLineHeight(6);
        $pdf->RowR(Array('Del 2024-01-12 al 2024-02-15')); //sustitucion por escrito
        $pdf->Ln(4);

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetFillColor($rgbt[0], $rgbt[1], $rgbt[2]);
        $pdf->SetTextColor($rgbtt[0], $rgbtt[1], $rgbtt[2]);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetWidths(Array(25, 20, 35, 25, 30, 35, 25));
        $pdf->SetLineHeight(4.5);
        $pdf->RoundedRect(10, $pdf->GetY(), 195, 9, 2, 'FD');
        $pdf->RowNBC(Array(utf8_decode('Folio Cotizacion'), utf8_decode('Fecha creacion'), utf8_decode('Realizo'), utf8_decode('Total Cot'), utf8_decode('Folio Factura'), utf8_decode('Cliente'), utf8_decode('Total Factura')));
        $pdf->SetTextColor(0, 0, 0); //fin vista

            if (isset($ventas) && is_array($ventas)) {  // Verificar si $ventas es un array antes de iterar sobre él
        foreach ($ventas as $reporteactual) {
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

            $pdf->SetFont('Arial', '', 9);
            $pdf->SetTextColor(0, 0, 0);

            $pdf->Row(Array($folio, $fecha, $realizo, utf8_decode('$ ' . number_format($totalcot, 2, '.', ',')), utf8_decode($foliofactura), utf8_decode($cliente), utf8_decode('$ ' . number_format($totalfactura, 2, '.', ','))));
        }}

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetTextColor($rgbtt[0], $rgbtt[1], $rgbtt[2]);
        $pdf->SetWidths(Array(145));
        $pdf->SetLineHeight(8);
        $pdf->RowR(Array("Ventas Individuales"));
        $pdf->SetFont('Arial', '', 9);

            if (isset($cf)) { // Verificar si $cf está definido antes de usarlo
        $usuarios = $cf->getUsuariosVentas($f);
            }
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetWidths(Array(45, 40, 35, 25));
        $pdf->SetLineHeight(4.5);
        foreach ($usuarios as $actual) {
            $idusuario = $actual['idusuario'];
            $nombre = $actual['nombre'] . " " . $actual['apellido_paterno'] . " " . $actual['apellido_materno'];
            $comision = $actual['comisionporcentaje'];
            if ($actual['calculo'] == '1') {
                $calculo = "antes de impuestos";
            } else if ($actual['calculo'] == '2') {
                $calculo = "despues de impuestos";
            }
            $total = $cf->getTotalVentas($f, $idusuario, $actual['calculo']);
            $totalcom = $total * ($comision / 100);
            if ($totalcom > 0) {
                $pdf->Row(Array(utf8_decode($nombre), utf8_decode("Comision: $comision% $calculo"), utf8_decode("Total Ventas: $ " . number_format($total, 2, '.', ',')), utf8_decode('Comision: $ ' . number_format($totalcot, 2, '.', ','))));
            }
        }
        //$pdf->Output('Reporte' . $fechainicio . '-' . $fechafin . '.pdf', 'I');
        break;
    case 11:
        $pdf->SetWidths(Array(80));
        $pdf->SetLineHeight(8);
        $pdf->SetY(36.3);
        $pdf->RowT(Array("Datos del Emisor"));
        $pdf->Ln(1);

        $pdf->SetFillColor($rgbfd[0], $rgbfd[1], $rgbfd[2]);
        $pdf->RoundedRect(10, 45.3, 195, 19, 2, 'F');

        $pdf->SetLineHeight(4.5);

        $pdf->SetY(45.3);

        $pdf->SetX(10);
        $pdf->SetWidths(Array(15, 94.5, 30, 55));
        $pdf->SetRowBorder('');
        $pdf->setRowColorText(array($txtbold, $clrtxt, $txtbold, $clrtxt));
        $pdf->SetLineHeight(0.1);
        $pdf->Row(Array('', '', '', ''));
        $pdf->SetStyles(array('B', '', 'B', ''));
        $pdf->SetLineHeight(4.5);
        $pdf->Row(Array('Nombre', utf8_decode('Contribuyente'), 'No Certificado', utf8_decode('007')));

        $pdf->SetX(10);
        $pdf->setRowColorText(array($txtbold, $clrtxt, $txtbold, $clrtxt));
        $pdf->SetStyles(array('B', '', 'B', ''));
        $pdf->Row(Array('RFC', utf8_decode('XAXX010101000'), 'Regimen Fiscal', utf8_decode('01 Regimen')));

        $pdf->SetX(10);
        $pdf->SetStyles(array('B', '', 'B', ''));
        $pdf->setRowColorText(array($txtbold, $clrtxt, $txtbold, $clrtxt));
        $pdf->Row(Array('Fecha', utf8_decode('01/01/2021'), 'T. Comprobante', utf8_decode('I Ingreso')));

        $pdf->SetWidths(Array(35, 77.5, 25, 58));
        $pdf->SetStyles(array('B', '', 'B', ''));
        $pdf->setRowColorText(array($txtbold, $clrtxt, $txtbold, $clrtxt));
        $pdf->Row(Array('Lugar de Expedicion', utf8_decode('76800'), '', ''));
        $pdf->Ln(1);

        $pdf->SetFillColor($rgbc[0], $rgbc[1], $rgbc[2]);
        $pdf->SetTextColor($rgbs[0], $rgbs[1], $rgbs[2]);
        $pdf->SetFont('Arial', '', 15);
        $pdf->SetWidths(Array(80));
        $pdf->SetLineHeight(8);
        $pdf->RowT(Array("Datos del Cliente"));

        $pdf->SetFillColor($rgbfd[0], $rgbfd[1], $rgbfd[2]);
        $pdf->RoundedRect(10, ($pdf->GetY() + 0.7), 195, 14, 2, 'F');

        $pdf->SetY(($pdf->GetY() + 0.7));
        $pdf->SetWidths(Array(17, 94.5, 23, 60));
        $pdf->SetLineHeight(0.1);
        $pdf->Row(Array('', '', '', ''));
        $pdf->SetLineHeight(4.5);
        $pdf->SetStyles(array('B', '', 'B', ''));
        $pdf->setRowColorText(array($txtbold, $clrtxt, $txtbold, $clrtxt));
        $pdf->Row(Array('Nombre', 'Receptor', 'Uso de CFDI', 'P01 Por Definir'));

        $pdf->SetStyles(array('B', '', 'B', ''));
        $pdf->setRowColorText(array($txtbold, $clrtxt, $txtbold, $clrtxt));
        $pdf->Row(array('RFC', 'XAXX010101000', '', ''));

        $pdf->SetWidths(Array(17, 174.5));
        $pdf->SetLineHeight(4.5);
        $pdf->SetStyles(array('B', ''));
        $pdf->setRowColorText(array($txtbold, $clrtxt));
        $pdf->Row(Array('Direccion', utf8_decode('Calle Falsa #123, Colonia: desconocida, Municipio, Estado')));
        $pdf->Ln(1);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetTextColor($rgbbld[0], $rgbbld[1], $rgbbld[2]);
        $pdf->RoundedRect(10, $pdf->GetY(), 195, 5.5, 2, 'D');
        $pdf->SetX(92.5);
        $pdf->Write(6, 'CONCEPTOS');
        $pdf->Ln(7);

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

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetStyles('');
        $pdf->setRowColorText();
        $pdf->SetRowBorder('B');
        $pdf->SetAligns('C');
        $pdf->Row(Array('1', '01', 'H87 Pieza', 'Descripcion de Producto', '', '$ 100.00', '$ 100.00'));

        $pdf->Cell(151, 8, '', 0, 0, 'C', 0);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(22, 8, 'Subtotal ', 1, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(22, 8, '$ 100.00', 1, 0, 'C', 0);
        $pdf->Ln(8);

        $pdf->Cell(151, 8, '', 0, 0, 'C', 0);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(22, 8, 'IVA ', 1, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(22, 8, '$ 16.00', 1, 0, 'C', 0);
        $pdf->Ln(8);

        $pdf->Cell(151, 8, '', 0, 0, 'C', 0);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(22, 8, 'Total ', 1, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(22, 8, '$ 116.00', 1, 0, 'C', 0);
        $pdf->Ln(15);

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(30, 8, 'Importe con Letra ', 0, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 9);
        $letras = NumeroALetras::convertir(bcdiv(116.00, '1', 2), 'pesos', 'centavos');
        $pdf->Cell(26, 8, utf8_decode("$letras 00/100 M.N."), 0, 0, 'L', 0);
        $pdf->Ln(8);

        $pdf->SetFillColor($rgbc[0], $rgbc[1], $rgbc[2]);
        $pdf->RoundedRect(10, $pdf->GetY(), 195, 3, 1.5, 'F');

        $pdf->titulo = $titulo2;
        $pdf->AddPage();

        $pdf->heightB = 0;
        $pdf->SetFont('Helvetica', '', 15);
        $pdf->SetFillColor($rgbfd[0], $rgbfd[1], $rgbfd[2]);
        $pdf->RoundedRect(10, 36, 195, 10, 2, 'F');

        $pdf->SetY(36.3);
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetWidths(Array(40, 66, 46, 43));
        $pdf->SetLineHeight(4.5);
        $pdf->SetStyles(array('B', '', 'B', ''));
        $pdf->SetRowBorder();
        $pdf->SetAligns(array('L'));
        $pdf->setRowColorText(array($txtbold, $clrtxt, $txtbold, $clrtxt));
        $pdf->Row(Array('Version', utf8_decode('2.0'), 'Distancia Total Recorrida', utf8_decode('100')));

        $pdf->SetStyles(array('B', '', 'B', ''));
        $pdf->SetRowBorder();
        $pdf->SetAligns(array('L'));
        $pdf->setRowColorText(Array($txtbold, $clrtxt, $txtbold, $clrtxt));
        $pdf->Row(Array('Transporte Internacional', utf8_decode('No'), 'Entrada/Salida de mercancia', utf8_decode('Entrada')));
        $pdf->Ln(2);

        $pdf->SetFont('Helvetica', '', 15);
        $pdf->SetFillColor($rgbc[0], $rgbc[1], $rgbc[2]);
        $pdf->SetTextColor($rgbs[0], $rgbs[1], $rgbs[2]);
        $pdf->SetWidths(Array(80));
        $pdf->SetLineHeight(8);
        $pdf->SetAligns(array('C'));
        $pdf->RowT(Array("Mercancias"));
        $pdf->Ln(1);

        $pdf->setRowColorText();
        $pdf->SetWidths(Array(20, 45, 15, 25, 25, 30, 35));
        $pdf->SetLineHeight(4);
        $pdf->SetAligns(array('C'));
        $pdf->SetFillColor($rgbt[0], $rgbt[1], $rgbt[2]);
        $pdf->setRowColorText(array($tittabla));
        $pdf->SetRowBorder('B', 'FD');
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetStyles('B');
        $pdf->Row(Array("Clave Fiscal", "Descripcion", "Cant", "Unidad", "Peso en Kg", "Material Peligroso", "Embalaje"));

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetRowBorder('B');
        $pdf->SetStyles();
        $pdf->setRowColorText();
        $pdf->Row(Array(utf8_decode('01'), utf8_decode('Mercancia'), '1', utf8_decode('Pieza'), '10', utf8_decode('No'), utf8_decode('')));
        $pdf->Ln(2);

        $pdf->SetFont('Helvetica', '', 15);
        $pdf->SetFillColor($rgbc[0], $rgbc[1], $rgbc[2]);
        $pdf->SetTextColor($rgbs[0], $rgbs[1], $rgbs[2]);
        $pdf->SetWidths(Array(80));
        $pdf->SetAligns(array('C'));
        $pdf->SetLineHeight(8);
        $pdf->RowT(Array("Origenes"));

        $pdf->SetWidths(Array(28, 66, 33, 68));
        $pdf->SetLineHeight(4.5);
        $pdf->setRowColorText(Array($txtbold, $clrtxt, $txtbold, $clrtxt));
        $pdf->SetStyles(array('B', '', 'B', ''));
        $pdf->SetAligns(array('L'));
        $pdf->SetRowBorder();
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetX(10);
        $pdf->SetFillColor($rgbfd[0], $rgbfd[1], $rgbfd[2]);
        $pdf->RoundedRect(10, $pdf->GetY(), 195, 20, 2, 'F');
        $pdf->Row(Array("Remitente", utf8_decode('Nombre Remitente'), "RFC Remitente", utf8_decode('XAXX010101000')));
        $pdf->Row(Array("Tipo", utf8_decode('Origen'), "Distancia Recorrida", utf8_decode('0')));
        $pdf->Row(Array("Fecha Salida", utf8_decode('01/01/2021 12:00'), "", ""));

        $pdf->SetWidths(Array(20, 167));
        $pdf->SetLineHeight(5);
        $pdf->setRowColorText(Array($txtbold, $clrtxt));
        $pdf->SetStyles(array('B', ''));
        $pdf->SetAligns(array('L'));
        $pdf->SetRowBorder();
        $pdf->Row(Array("Direccion", utf8_decode('Calle Falsa #123, Colonia: desconocida, Municipio, Estado')));
        $pdf->Ln(1.5);

        $pdf->SetFont('Helvetica', '', 15);
        $pdf->SetFillColor($rgbc[0], $rgbc[1], $rgbc[2]);
        $pdf->SetTextColor($rgbs[0], $rgbs[1], $rgbs[2]);
        $pdf->SetWidths(Array(80));
        $pdf->SetAligns(array('C'));
        $pdf->SetLineHeight(8);
        $pdf->RowT(Array("Destinos"));

        $pdf->SetWidths(Array(28, 66, 33, 68));
        $pdf->SetLineHeight(4.5);
        $pdf->setRowColorText(Array($txtbold, $clrtxt, $txtbold, $clrtxt));
        $pdf->SetStyles(array('B', '', 'B', ''));
        $pdf->SetAligns(array('L'));
        $pdf->SetRowBorder();
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetX(10);
        $pdf->SetFillColor($rgbfd[0], $rgbfd[1], $rgbfd[2]);
        $pdf->RoundedRect(10, $pdf->GetY(), 195, 20, 2, 'F');
        $pdf->Row(Array("Destino", utf8_decode('Nombre Remitente'), "RFC Destinatario", utf8_decode('XAXX010101000')));
        $pdf->Row(Array("Tipo", utf8_decode('Destino'), "Distancia Recorrida", utf8_decode('100')));
        $pdf->Row(Array("Fecha Llegada", utf8_decode('02/01/2021 12:00'), "", ""));

        $pdf->SetWidths(Array(20, 167));
        $pdf->SetLineHeight(5);
        $pdf->setRowColorText(Array($txtbold, $clrtxt));
        $pdf->SetStyles(array('B', ''));
        $pdf->SetAligns(array('L'));
        $pdf->SetRowBorder();
        $pdf->Row(Array("Direccion", utf8_decode('Calle Falsa #123, Colonia: desconocida, Municipio, Estado')));
        $pdf->Ln(1.5);

        $pdf->SetFont('Helvetica', '', 15);
        $pdf->SetFillColor($rgbc[0], $rgbc[1], $rgbc[2]);
        $pdf->SetTextColor($rgbs[0], $rgbs[1], $rgbs[2]);
        $pdf->SetWidths(Array(80));
        $pdf->SetAligns(array('C'));
        $pdf->SetLineHeight(8);
        $pdf->RowT(Array("Autotransporte Federal"));

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetWidths(Array(30, 70, 27, 68));
        $pdf->SetLineHeight(5);
        $pdf->setRowColorText(Array($txtbold, $clrtxt, $txtbold, $clrtxt));
        $pdf->SetStyles(array('B', '', 'B', ''));
        $pdf->SetAligns(array('L'));
        $pdf->SetRowBorder();

        $pdf->SetFillColor($rgbfd[0], $rgbfd[1], $rgbfd[2]);
        $pdf->RoundedRect(10, $pdf->GetY(), 195, 21, 2, 'F');

        $pdf->SetY($pdf->GetY());
        $pdf->Row(Array("Num Permiso", utf8_decode('1234567'), "Tipo permiso", utf8_decode('01')));
        $pdf->Row(Array(utf8_decode("Año Vehiculo"), utf8_decode('2000'), "Placa Vehiculo", utf8_decode('XYZ321')));
        $pdf->Row(Array(utf8_decode("Tipo Transporte"), utf8_decode('Camion'), "", ''));
        $pdf->Row(Array(utf8_decode("Aseguradora"), utf8_decode('Seguros Polilla'), "Num Poliza", utf8_decode('012421954')));

        $pdf->Ln(1.5);

        $pdf->SetFont('Helvetica', '', 15);
        $pdf->SetFillColor($rgbc[0], $rgbc[1], $rgbc[2]);
        $pdf->SetTextColor($rgbs[0], $rgbs[1], $rgbs[2]);
        $pdf->SetWidths(Array(80));
        $pdf->SetAligns(array('C'));
        $pdf->SetLineHeight(8);
        $pdf->RowT(Array("Operadores"));

        $pdf->SetWidths(Array(25, 70, 25, 70));
        $pdf->SetLineHeight(4.5);
        $pdf->setRowColorText(Array($txtbold, $clrtxt, $txtbold, $clrtxt));
        $pdf->SetStyles(array('B', '', 'B', ''));
        $pdf->SetAligns(array('L'));
        $pdf->SetRowBorder();

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetX(10);
        $pdf->SetFillColor($rgbfd[0], $rgbfd[1], $rgbfd[2]);
        $pdf->RoundedRect(10, $pdf->GetY(), 195, 15, 2, 'F');

        $pdf->SetY($pdf->GetY());

        $pdf->SetWidths(array(25, 70, 25, 70));
        $pdf->SetLineHeight(4.5);
        $pdf->setRowColorText(array($txtbold, $clrtxt, $txtbold, $clrtxt));
        $pdf->SetStyles(array('B', '', 'B', ''));
        $pdf->SetAligns(array('L'));
        $pdf->SetRowBorder();
        $pdf->Row(Array("Nombre", utf8_decode('Ramiro Perez Martinez'), "RFC", utf8_decode('XAXX010101000')));
        $pdf->Row(Array("Num Licencia", utf8_decode('1234567'), "", ""));

        $pdf->SetWidths(Array(25, 170));
        $pdf->SetLineHeight(4.5);
        $pdf->setRowColorText(Array($txtbold, $clrtxt));
        $pdf->SetStyles(array('B', ''));
        $pdf->SetAligns(array('L'));
        $pdf->SetRowBorder();
        $pdf->Row(array("Direccion", utf8_decode('Calle Falsa #123, Colonia: desconocida, Municipio, Estado')));
        $pdf->Ln(1.5);
        break;
    
    case 12:
        
        $pdf->SetY(34.3);
        $pdf->SetX($pdf->margen);
        $pdf->SetWidths(Array($pdf->body));
        $pdf->SetRowBorder('NB');
        $pdf->SetStyles(array(''));
        $pdf->setRowColorText(array($clrtitulo));
        if($pdf->body >= 76){
            $pdf->SetSizes(array(15));
            $pdf->SetLineHeight(7);
        }else{
            $pdf->SetSizes(array(13));
            $pdf->SetLineHeight(4);
        }
        $pdf->SetAligns('C');
        $pdf->Row(Array(utf8_decode($titulo)));
        $pdf->Ln(3);

        $pdf->SetX($pdf->margen);
        $pdf->SetWidths(Array($pdf->body));
        $pdf->setRowColorText(array($clrtxt));
        if($pdf->body >= 76){
            $pdf->SetSizes(array(10));
            $pdf->SetLineHeight(5);
        }else{
            $pdf->SetSizes(array(6));
            $pdf->SetLineHeight(4);
        }
        $pdf->SetAligns('L');
        $pdf->Row(Array(utf8_decode('VENTA: 0001')));
        $pdf->SetX($pdf->margen);
        $pdf->SetWidths(array(bcdiv(($pdf->body / 2),'1',1), bcdiv(($pdf->body / 2),'1',1)));
        $pdf->SetSizes(array(1, 1));
        $pdf->SetLineHeight(1);
        $pdf->Row(array('', ''));

        $pdf->SetX($pdf->margen);
        $pdf->SetStyles(array('B', ''));
        $pdf->SetAligns(array('L', 'L'));
        if($pdf->body >= 76){
            $pdf->SetSizes(array(8, 8));
            $pdf->SetLineHeight(5);
        }else{
            $pdf->SetSizes(array(6, 6));
            $pdf->SetLineHeight(4);
        }
        $pdf->Row(array('FECHA VENTA', '01/01/2023  00:00'));

        
        $pdf->SetX($pdf->margen);
        $pdf->SetStyles(array('B', ''));
        $pdf->SetAligns(array('L', 'L'));
        $pdf->Row(Array('FECHA IMPRESION',  '01/01/2023  00:00'));
        $pdf->Ln(1);
        $pdf->Rect($pdf->margen, $pdf->GetY(), $pdf->body, 0.2);
        $pdf->Ln(1);

        $pdf->SetX($pdf->margen);
        //$pdf->SetWidths(Array(32, 16, 12, 16));
        $pdf->SetWidths(Array(bcdiv(($pdf->body * 0.42),'1',1), bcdiv(($pdf->body * 0.20),'1',1), bcdiv(($pdf->body * 0.15),'1',1), bcdiv(($pdf->body * 0.23),'1',1)));
        $pdf->SetStyles('');
        $pdf->setRowColorText(array($clrtxt));
        if($pdf->body >= 76){
            $pdf->SetSizes(array(8, 8, 8, 8));
            $pdf->SetLineHeight(5);
        }else{
            $pdf->SetSizes(array(5.6, 5.6, 5, 5.6));
            $pdf->SetLineHeight(2);
        } 
        $pdf->SetAligns(array('L', 'L', 'C', 'L'));
        $pdf->Row(Array(utf8_decode('PRODUCTO'), utf8_decode('UNIT'), utf8_decode('CANT'), utf8_decode('IMP')));
        $pdf->Ln(1);

        if($pdf->body >= 76){
            $pdf->SetSizes(array(8, 8, 8, 8));
        }else{
            $pdf->SetSizes(array(5.6, 5.6, 5.6, 5.6));
        } 
        $pdf->SetX($pdf->margen);
        $pdf->SetLineHeight(4);
        $pdf->Row(Array(utf8_decode('010101010101 Nombre Producto'), utf8_decode('$275.00'), utf8_decode('2'), utf8_decode('$550.00')));
        $pdf->Ln(1);        

        $pdf->Rect($pdf->margen, $pdf->GetY(), $pdf->body, 0.2);
        $pdf->Ln(1);

        $pdf->SetX($pdf->margen);
        $pdf->SetWidths(Array(bcdiv(($pdf->body * 0.62),'1',1), bcdiv(($pdf->body * 0.38),'1',1)));
        $pdf->SetStyles('B');
        $pdf->SetAligns(array('R', 'L'));
        $pdf->Row(array('CANT PRODUCTOS:', '   2'));
        $pdf->Ln(1);

        $pdf->SetX($pdf->margen);
        $pdf->Row(array('SUBTOTAL:', utf8_decode('   $ 550.00')));
        $pdf->Ln(1);

        $pdf->SetX($pdf->margen);
        $pdf->Row(array('DESCUENTO:', utf8_decode('   $ 0.00')));
        $pdf->Ln(1);

        $pdf->SetX($pdf->margen);
        $pdf->Row(array('TOTAL VENTA:', utf8_decode('   $ s550.00')));
        $pdf->Ln(1.5);
        $pdf->Rect($pdf->margen, $pdf->GetY(), $pdf->body, 0.2);
        $pdf->Ln(1);

        $pdf->SetX($pdf->margen);
        $pdf->SetStyles('B');
        $pdf->SetWidths(array(bcdiv(($pdf->body / 2),'1',1), bcdiv(($pdf->body / 2),'1',1)));
        $pdf->SetAligns(array('L', 'L'));

        
        $pdf->Row(array('FORMA DE PAGO:', 'Efectivo'));
        $pdf->SetX($pdf->margen);
        $pdf->Row(array('EFECTIVO:', utf8_decode('$ 600.00')));
        $pdf->SetX($pdf->margen);
        $pdf->Row(array('CAMBIO:', utf8_decode('$ 50.00')));
        $pdf->Ln(1);
        $pdf->Rect($pdf->margen, $pdf->GetY(), $pdf->body, 0.2);
        $pdf->Ln(1);

        $pdf->SetX($pdf->margen);
        $pdf->SetWidths(Array($pdf->body));
        $pdf->SetStyles('B');
        $pdf->SetAligns(array('C'));
        $pdf->Row(array($observaciones));
        $pdf->Ln(3);

        $pdf->SetX($pdf->margen);
        $pdf->SetWidths(array(bcdiv(($pdf->body / 2),'1',1), bcdiv(($pdf->body / 2),'1',1)));
        $pdf->SetStyles('B');
        $pdf->SetAligns(array('L', 'R'));
        $pdf->Row(array($pdf->pagina, $pdf->correo));

      
        break;
    case 13:
        $pdf->SetFont('Helvetica', '', 15);
        $pdf->SetFillColor($rgbc[0], $rgbc[1], $rgbc[2]);
        $pdf->SetTextColor($rgbs[0], $rgbs[1], $rgbs[2]);

        $pdf->SetWidths(Array(80));
        $pdf->SetLineHeight(8);
        $pdf->SetY(36.3);
        $dateformat='12/23/2002';
        $pdf->RowT(Array("Fecha de Corte " . $dateformat));

        $pdf->SetY(48);
            if (isset($uid) && isset($cv)) { ////Verificar si las variables están definidas 
        if ($uid != '0') {
            $usuario = $cv->getUserbyID($uid);
            $pdf->SetWidths(Array(22, 173));
            $pdf->SetRowBorder('NB');
            $pdf->SetLineHeight(4.5);
            $pdf->SetSizes(array(13, 13));
            $pdf->SetStyles(array('B', ''));
            $pdf->setRowColorText(array($txtbold, $clrtxt));
            $pdf->Row(Array('Usuario', utf8_decode($usuario)));
            $pdf->Ln(2);
        }
         }

        $pdf->SetFillColor($rgbfd[0], $rgbfd[1], $rgbfd[2]);
        $pdf->SetWidths(Array(45, 50, 5, 45, 50));
        $pdf->SetLineHeight(0.1);
        $pdf->Row(Array('', '','', '', ''));

        $pdf->SetRowBorder('NB');
        $pdf->SetLineHeight(4.5);
        $pdf->SetSizes(array(13, 13, 13, 13, 13));
        $pdf->SetStyles(array('B', '','', 'B', ''));
        $pdf->setRowColorText(array($txtbold, $clrtxt, '', $txtbold, $clrtxt));
        $totventas = 0; //valor
        $totganancia = 0; //valor
        $pdf->Row(Array('Ventas totales:', "$ " . number_format($totventas, 2, '.', ','),'', 'Ganancias:', "$ " . number_format($totganancia, 2, '.', ',')));

        $pdf->Ln(3);
        $pdf->SetAligns('C');
        $pdf->SetRowBorder('NB');
        $pdf->SetLineHeight(6);
        $pdf->SetSizes(array(13, 13, 13));
        $pdf->SetWidths(Array(95, 5, 95));
        $pdf->SetStyles(array('B', '', 'B'));
        $pdf->setRowColorText(array($txtbold, $clrtxt, $txtbold));
        $pdf->Row(Array('Entradas de efectivo', '', 'Dinero en caja'));
        $y = $pdf->GetY();

        $fondo = 0;
        $total = 0;
        if (isset($cv)) { // Verificar si $cv está definido antes de usarlo
        $datf = $cv->getFondoCaja($uid, $fecha);
        foreach ($datf as $actual) {
            $fondo += $actual['fondocaja'];
            $total += $actual['fondocaja'];
        }
        $pdf->SetAligns(array('L', 'C'));
        $pdf->SetSizes(array(9, 9));
        $pdf->SetWidths(Array(40, 55));
        $pdf->SetStyles(array('B', ''));
        $pdf->setRowColorText(array($txtbold, $clrtxt));
        $pdf->SetLineHeight(0.1);
        $pdf->SetRowBorder('NB');
        $pdf->Row(Array('', ''));

        $pdf->SetRowBorder('B');
        $pdf->SetLineHeight(4.5);
        $pdf->Row(Array('Dinero inicial en caja:', "$ " . number_format($fondo, 2, '.', ',')));

        $entradas = $cv->getMovEfectivo('1', $fecha, $uid);
        foreach ($entradas as $actual) {
            $concepto = utf8_decode($actual['conceptomov']);
            $monto = $actual['montomov'];
            $total += $actual['montomov'];
            $pdf->Row(Array(utf8_decode($concepto) . ":", "$ " . number_format($monto, 2, '.', ',')));
        }
     

        $pdf->Row(Array('Total:', "$ " . number_format($total, 2, '.', ',')));
        $y1 = $pdf->GetY();

        $total = 0;
        $efectivo = 0;
        $tarjeta = 0;
        $vales = 0;
        $entradas = 0;
        $salidas = 0;
        $datf = $cv->getVentasByTipo($fecha, 'cash', $uid);
        foreach ($datf as $actual) {
            $total += $actual['totalventa'];
            $efectivo += $actual['totalventa'];
        }

        $pdf->SetY($y);
        $pdf->SetX(110);
        $pdf->Row(Array(utf8_decode('Ventas en efectivo:'), "$ " . number_format($efectivo, 2, '.', ',')));

        $datcd = $cv->getVentasByTipo($fecha, 'card', $uid);
        foreach ($datcd as $actual) {
            $total += $actual['totalventa'];
            $tarjeta += $actual['totalventa'];
        }

        if ($tarjeta > 0) {
            $pdf->SetX(110);
            $pdf->Row(Array(utf8_decode('Ventas con tarjeta:'), "$ " . number_format($tarjeta, 2, '.', ',')));
        }

        $datvl = $cv->getVentasByTipo($fecha, 'val', $uid);
        foreach ($datvl as $actual) {
            $total += $actual['totalventa'];
            $vales += $actual['totalventa'];
        }

        if ($vales > 0) {
            $pdf->SetX(110);
            $pdf->Row(Array(utf8_decode('Ventas con vales:'), "$ " . number_format($vales, 2, '.', ',')));
        }

        $datf = $cv->getFondoCaja($uid, $fecha);
        foreach ($datf as $actual) {
            $total += $actual['fondocaja'];
            $entradas += $actual['fondocaja'];
        }

        $ent = $cv->getMovEfectivo('1', $fecha, $uid);
        foreach ($ent as $actual) {
            $entradas += $actual['montomov'];
            $total += $actual['montomov'];
        }

        if ($entradas > 0) {
            $pdf->SetX(110);
            $pdf->Row(Array(utf8_decode('Entradas:'), "$ " . number_format($entradas, 2, '.', ',')));
        }

        $out = $cv->getMovEfectivo('2', $fecha, $uid);
        foreach ($out as $actual) {
            $salidas += $actual['montomov'];
            $total -= $actual['montomov'];
        }

        if ($salidas > 0) {
            $pdf->SetX(110);
            $pdf->Row(Array(utf8_decode('Salidas:'), "$ " . number_format($salidas, 2, '.', ',')));
        }

        $pdf->SetX(110);
        $pdf->Row(Array(utf8_decode('Total:'), "$ " . number_format($total, 2, '.', ',')));
        $y2 = $pdf->GetY();

        $ylast = $y2;
        if ($y1 > $y2) {
            $ylast = $y1;
        }

        $pdf->SetY($ylast);
        $pdf->Ln(3);
        $pdf->SetAligns('C');
        $pdf->SetRowBorder('NB');
        $pdf->SetLineHeight(6);
        $pdf->SetSizes(array(13));
        $pdf->SetWidths(Array(95));
        $pdf->SetStyles(array('B', '', 'B'));
        $pdf->setRowColorText(array($txtbold));
        $pdf->Row(Array('Salidas de efectivo'));

        $pdf->SetAligns(array('L', 'C'));
        $pdf->SetSizes(array(9, 9));
        $pdf->SetWidths(Array(40, 55));
        $pdf->SetStyles(array('B', ''));
        $pdf->setRowColorText(array($txtbold, $clrtxt));
        $pdf->SetLineHeight(0.1);
        $pdf->SetRowBorder('NB');
        $pdf->Row(Array('', ''));

        $salidas = 0;
        $pdf->SetRowBorder('B');
        $pdf->SetLineHeight(4.5);
        $out = $cv->getMovEfectivo('2', $fecha, $uid);
        foreach ($out as $actual) {
            $concepto = $actual['conceptomov'];
            $monto = $actual['montomov'];
            $salidas += $actual['montomov'];

            $pdf->Row(Array(utf8_decode($concepto . ":"), "$ " . number_format($monto, 2, '.', ',')));
        }

        $pdf->Row(Array(utf8_decode("Total:"), "$ " . number_format($salidas, 2, '.', ',')));

        $pdf->isFinished = true;

        $nm = str_replace(" ", "_", $usuario);
        $pdf->Output('corte_'.$fecha.'_'.$nm.'.pdf', 'I');
        }//cierra $cv

        break;
}


$pdf->isFinished = true;
$pdf->Output('./ejemplo.pdf', 'F');
?>