<?php

if (isset($_FILES["imgprof"]) || isset($_FILES["imagen"])) {
    if (isset($_FILES["imgprof"])){
        $imgtmp = $_FILES["imgprof"];
    }else{
        $imgtmp = $_FILES["imagen"];
    }
    $tempfile = ($imgtmp['tmp_name']);
    $file = $imgtmp;
    $tipo = $file["type"];

    if ($tipo != 'image/jpg' && $tipo != 'image/jpeg' && $tipo != 'image/png' && $tipo != 'image/gif') {
        echo "Error, el archivo no es una imagen<corte>";
    } else {
        require_once '../com.sine.modelo/Session.php';
        Session::start();
        date_default_timezone_set("America/Mexico_City");

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

        if(isset($_POST['fileuser'])){
            $prevfile = $_POST['fileuser'];
        }else if(isset($_POST['filename'])){
            $prevfile = $_POST['filename'];
        }
        
        $ruta_provisional = $file["tmp_name"];
        $nombre = $file["name"];
        $size = $file["size"];

        $carpeta = "../temporal/tmp/";

        if ($prevfile != "") {
            if (file_exists($carpeta . $prevfile)) {
                unlink($carpeta . $prevfile);
            }
        }

        $dimensiones = getimagesize($ruta_provisional);
        $width = $dimensiones[0];
        $height = $dimensiones[1];
        $extension = pathinfo($nombre, PATHINFO_EXTENSION);
        $nombre = $fecha . $ranstr . '_' . $idusuario . $sessionid . '.' . $extension;

        $max_width = 500;
        $max_height = 500;
        $mime = $dimensiones['mime'];

        switch ($mime) {
            case 'image/gif':
                $image_create = "imagecreatefromgif";
                $image = "imagegif";
        		$quality = 80;
                break;

            case 'image/png':
                $image_create = "imagecreatefrompng";
                $image = "imagepng";
                $quality = 8;
                break;

            case 'image/jpeg':
                $image_create = "imagecreatefromjpeg";
                $image = "imagejpeg";
                $quality = 80;
                break;

            default:
                return false;
                break;
        }

        $dst_img = imagecreatetruecolor($max_width, $max_height);
        $src_img = $image_create($ruta_provisional);

        $width_new = $height * $max_width / $max_height;
        $height_new = $width * $max_height / $max_width;
        //if the new width is greater than the actual width of the image, then the height is too large and the rest cut off, or vice versa
        if ($width_new > $width) {
            //cut point by height
            $h_point = (($height - $height_new) / 2);
            //copy image
            imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
        } else {
            //cut point by width
            $w_point = (($width - $width_new) / 2);
            imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
        }

        $image($dst_img, $carpeta . $nombre, $quality);

        if ($dst_img)
            imagedestroy($dst_img);
        if ($src_img)
            imagedestroy($src_img);
        //El 

        $type = pathinfo($carpeta . $nombre, PATHINFO_EXTENSION);
        $data = file_get_contents($carpeta . $nombre);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        echo "<img src='$base64' width='200px' id='img'><corte>$nombre";
    }
}