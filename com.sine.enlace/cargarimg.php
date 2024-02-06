<?php
if (isset($_FILES["imagen"])) {
    require_once '../com.sine.modelo/Session.php';
    Session::start();
    date_default_timezone_set("America/Mexico_City");

    $maxsz = 200;
    if (isset($_POST['imgsz'])) {
        $maxsz = $_POST['imgsz'];
    }
    $sessionid = session_id();
    $idusuario = $_SESSION[sha1("idusuario")];

    $f = getdate();
    $d = $f['mday'];
    $m = $f['mon'];
    $y = $f['year'];
    $h = $f['hours'];
    $mi = $f['minutes'];
    $s = $f['seconds'];
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
    if ($s < 10) {
        $s = "0$s";
    }
    $fecha = $m . $y . $d . $h . $mi . $s;

    $ranstr = "";
    $chars = "0123456789011121314151617181920";
    $charsize = strlen($chars);
    for ($i = 0; $i < 5; $i++) {
        $ranstr .= $chars[rand(0, $charsize - 1)];
    }

    $tempfile = ($_FILES['imagen']['tmp_name']);
    $prevfile = $_POST['filename'];

    $file = $_FILES["imagen"];
    $nombre = $file["name"];
    $tipo = $file["type"];
    $ruta_provisional = $file["tmp_name"];
    $size = $file["size"];

    if ($tipo == 'image/jpg' || $tipo == 'image/jpeg' || $tipo == 'image/png' || $tipo == 'image/gif') {
        $dimensiones = getimagesize($ruta_provisional);
        $width = $dimensiones[0];
        $height = $dimensiones[1];
    }//Para permitir avance del sistema cuando es pdf
    $carpeta = "../temporal/tmp/";

    if ($prevfile != "") {
        if (file_exists($carpeta . $prevfile)) {
            unlink($carpeta . $prevfile);
        }
    }

    $extension = pathinfo($nombre, PATHINFO_EXTENSION);
	if ($extension == 'jfif') {
        $extension = 'jpg';
    }
    $nombre = $ranstr . $fecha . '_' . $idusuario . $sessionid . '.' . $extension;

    if ($tipo != 'image/jpg' && $tipo != 'image/jpeg' && $tipo != 'image/png' && $tipo != 'image/gif' && $tipo != 'application/pdf' && $tipo != 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' && $tipo != 'application/vnd.ms-excel' && $tipo != 'application/x-zip-compressed' && $tipo != 'application/octet-stream' && $tipo != 'application/zip' && $tipo != 'application/x-rar-compressed' && $tipo != 'multipart/x-zip') {
        echo "Error, tipo de archivo no permitido<corte>";
    } else if ($tipo == 'application/pdf' || $tipo == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' || $tipo == 'application/vnd.ms-excel' || $tipo == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' || $tipo == 'application/msword') {

        $src = $carpeta . $nombre;
        move_uploaded_file($ruta_provisional, $src);
        //$nom2 = str_replace(" ", "%20", $nombre);

        echo "<a href='#' onclick='displayDocAnticipo();' class='btn btn-sm button-modal' title='Ver archivo' ><span class='glyphicon glyphicon-file'></span></a><corte>$nombre";
    } else if ($size > 900 * 900) {
        $newwidth = $width * 0.5;
        $newheight = $height * 0.5;
        switch ($tipo) {
            case 'image/jpeg':
                $image_create_func = 'imagecreatefromjpeg';
                $image_save_func = 'imagejpeg';
                break;
            case 'image/png':
                $image_create_func = 'imagecreatefrompng';
                $image_save_func = 'imagepng';
                break;
            case 'image/gif':
                $image_create_func = 'imagecreatefromgif';
                $image_save_func = 'imagegif';
                break;
            default:
                throw Exception('Unknown image type.');
        }
        //ANGEL
        $imagen = $image_create_func($tempfile);
        //$imagen = imagecreatefrompng($tempfile);
        $imagen_p = imagecreatetruecolor($newwidth, $newheight);
        $white = imagecolorallocate($imagen_p, 255, 255, 255);
        imagefilledrectangle($imagen_p, 0, 0, $width, $height, $white);
        imagecopyresampled($imagen_p, $imagen, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        $img = $image_save_func($imagen_p, $carpeta . $nombre);
        imageDestroy($imagen_p);
        //El 
        $vista = "temporal/tmp/" . $nombre;
        $type = pathinfo("../" . $vista, PATHINFO_EXTENSION);
        $data = file_get_contents("../" . $vista);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        //Altura responsiva
        if ($tipo != 'application/pdf') {

            if ($width >= $height) {
                $height = ($height * $maxsz) / $width;
                $padding = $maxsz - $height;
                echo "<img style='margin-top:$padding.;' src='$base64' width='" . $maxsz . "px' height='$height" . "px' id='img'><corte>$nombre";
            } else {
                $width = ($width * $maxsz) / $height;
                echo "<img src='$base64' width='$width.px' height='" . $maxsz . "px' id='img'><corte>$nombre";
            }
        }
    } else if ($width > 2000 || $height > 2000) {
        $newwidth = $width * 0.5;
        $newheight = $height * 0.5;
        switch ($tipo) {
            case 'image/jpeg':
                $image_create_func = 'imagecreatefromjpeg';
                $image_save_func = 'imagejpeg';
                break;
            case 'image/png':
                $image_create_func = 'imagecreatefrompng';
                $image_save_func = 'imagepng';
                break;
            case 'image/gif':
                $image_create_func = 'imagecreatefromgif';
                $image_save_func = 'imagegif';
                break;
            default:
                throw Exception('Unknown image type.');
        }
         //ANGEL
        $imagen = $image_create_func($tempfile);
        //$imagen = imagecreatefrompng($tempfile);
        $imagen_p = imagecreatetruecolor($newwidth, $newheight);
        $white = imagecolorallocate($imagen_p, 255, 255, 255);
        imagefilledrectangle($imagen_p, 0, 0, $width, $height, $white);
        imagecopyresampled($imagen_p, $imagen, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

        $img = $image_save_func($imagen_p, $carpeta . $nombre);
        imageDestroy($imagen_p);
        //El 
        $vista = "temporal/tmp/" . $nombre;
        $type = pathinfo("../" . $vista, PATHINFO_EXTENSION);
        $data = file_get_contents("../" . $vista);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        //Altura responsiva
        if ($tipo != 'application/pdf') {

            if ($width >= $height) {
                $height = ($height * $maxsz) / $width;
                $padding = $maxsz - $height;
                echo "<img style='margin-top:$padding.;' src='$base64' width='" . $maxsz . "px' height='$height" . "px' id='img'><corte>$nombre";
            } else {
                $width = ($width * $maxsz) / $height;
                echo "<img src='$base64' width='$width.px' height='" . $maxsz . "px' id='img'><corte>$nombre";
            }
        }
    } else if ($width < 60 || $height < 60) {
        echo "Error la anchura y la altura mínima permitida es 60px<corte>";
    } else if ($tipo == 'image/png') {
        $newwidth = $width * 1;
        $newheight = $height * 1;
        //ANGEL
        $imagen = imagecreatefrompng($tempfile); //+++++++++++++++++++
        $imagen_p = imagecreatetruecolor($newwidth, $newheight);
        $white = imagecolorallocate($imagen_p, 255, 255, 255);
        imagefilledrectangle($imagen_p, 0, 0, $width, $height, $white);
        imagecopyresampled($imagen_p, $imagen, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

        $img = imagepng($imagen_p, $carpeta . $nombre);
        imageDestroy($imagen_p);
        //El 
        $vista = "temporal/tmp/" . $nombre;
        $type = pathinfo("../" . $vista, PATHINFO_EXTENSION);
        $data = file_get_contents("../" . $vista);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        //Altura responsiva
        if ($tipo != 'application/pdf') {

            if ($width >= $height) {
                $height = ($height * $maxsz) / $width;
                $padding = $maxsz - $height;
                echo "<img style='margin-top:$padding.;' src='$base64' width='" . $maxsz . "px' height='$height" . "px' id='img'><corte>$nombre";
            } else {
                $width = ($width * $maxsz) / $height;
                echo "<img src='$base64' width='$width.px' height='" . $maxsz . "px' id='img'><corte>$nombre";
            }
        }
    } else {
        $rawBaseName = pathinfo($nombre, PATHINFO_FILENAME);
        $extension = pathinfo($nombre, PATHINFO_EXTENSION);

        $src = $carpeta . $nombre;
        move_uploaded_file($ruta_provisional, $src);
        //El 
        $vista = "temporal/tmp/" . $nombre;
        $type = pathinfo("../" . $vista, PATHINFO_EXTENSION);
        $data = file_get_contents("../" . $vista);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        //Altura responsiva
        if ($tipo != 'application/pdf') {

            if ($width >= $height) {
                $height = ($height * $maxsz) / $width;
                $padding = $maxsz - $height;
                echo "<img style='margin-top:$padding.;' src='$base64' width='" . $maxsz . "px' height='$height" . "px' id='img'><corte>$nombre";
            } else {
                $width = ($width * $maxsz) / $height;
                echo "<img src='$base64' width='$width.px' height='" . $maxsz . "px' id='img'><corte>$nombre";
            }
        } else {
            echo "Vista previa del PDF no disponible";
        }
    }
}

