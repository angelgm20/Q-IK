<?php

if (isset($_FILES["firma-img"])) {
    $tempfile = ($_FILES['firma-img']['tmp_name']);
    $file = $_FILES["firma-img"];
    $nombre = $file["name"];
    $tipo = $file["type"];
    $ruta_provisional = $file["tmp_name"];
    $size = $file["size"];
    $dimensiones = getimagesize($ruta_provisional);
    $idenc = $_POST['id-encabezado'];
    $width = $dimensiones[0];
    $height = $dimensiones[1];
    $carpeta = "../img/logo/$idenc/";

    if (!is_dir($carpeta)) {
        mkdir($carpeta);
    }

    if (file_exists($carpeta . $nombre)) {
        echo "0Ya existe un archivo con este nombre";
    } else {
        if ($tipo != 'image/jpg' && $tipo != 'image/jpeg' && $tipo != 'image/png' && $tipo != 'image/gif') {
            echo "Error, el archivo no es una imagen";
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
            $imagen = $image_create_func($tempfile);
            $imagen_p = imagecreatetruecolor($newwidth, $newheight);
            $white = imagecolorallocate($imagen_p, 255, 255, 255);
            imagefilledrectangle($imagen_p, 0, 0, $width, $height, $white);
            imagecopyresampled($imagen_p, $imagen, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
            $img = $image_save_func($imagen_p, $carpeta . $nombre);
            imageDestroy($imagen_p);
            //El 
            $vista = "img/logo/$idenc/" . $nombre;
            //Altura responsiva
            if ($tipo != 'application/pdf') {

                if ($width >= $height) {
                    $height = ($height * 100) / $width;
                    $padding = 100 - $height;
                    echo "<img style='margin-top:$padding.;' src='$vista' width='100px' height='$height.px' id='img'>";
                } else {
                    $width = ($width * 100) / $height;
                    echo "<img src='$vista' width='$width.px' height='100px' id='img'>";
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
            $imagen = $image_create_func($tempfile);
            $imagen_p = imagecreatetruecolor($newwidth, $newheight);
            $white = imagecolorallocate($imagen_p, 255, 255, 255);
            imagefilledrectangle($imagen_p, 0, 0, $width, $height, $white);
            imagecopyresampled($imagen_p, $imagen, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
            $img = $image_save_func($imagen_p, $carpeta . $nombre);
            imageDestroy($imagen_p);
            //El 
            $vista = "img/logo/$idenc/" . $nombre;
            //Altura responsiva
            if ($tipo != 'application/pdf') {

                if ($width >= $height) {
                    $height = ($height * 100) / $width;
                    $padding = 100 - $height;
                    echo "<img style='margin-top:$padding.;' src='$vista' width='100px' height='$height.px' id='img'>";
                } else {
                    $width = ($width * 100) / $height;
                    echo "<img src='$vista' width='$width.px' height='100px' id='img'>";
                }
            }
        } else if ($width < 60 || $height < 60) {
            echo "Error la anchura y la altura mínima permitida es 60px";
        } else if ($tipo == 'image/png') {
            $newwidth = $width * 1;
            $newheight = $height * 1;
            $imagen = imagecreatefrompng($tempfile);
            $imagen_p = imagecreatetruecolor($newwidth, $newheight);
            $white = imagecolorallocate($imagen_p, 255, 255, 255);
            imagefilledrectangle($imagen_p, 0, 0, $width, $height, $white);
            imagecopyresampled($imagen_p, $imagen, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
            $img = imagepng($imagen_p, $carpeta . $nombre);
            imageDestroy($imagen_p);
            //El 
            $vista = "img/logo/$idenc/" . $nombre;
            //Altura responsiva
            if ($tipo != 'application/pdf') {

                if ($width >= $height) {
                    $height = ($height * 100) / $width;
                    $padding = 100 - $height;
                    echo "<img style='margin-top:$padding.;' src='$vista' width='100px' height='$height.px' id='img'>";
                } else {
                    $width = ($width * 100) / $height;
                    echo "<img src='$vista' width='$width.px' height='100px' id='img'>";
                }
            }
        } else {
            $src = $carpeta . $nombre;
            move_uploaded_file($ruta_provisional, $src);
            //El 
            $vista = "img/logo/$idenc/" . $nombre;
            //Altura responsiva
            if ($tipo != 'application/pdf') {

                if ($width >= $height) {
                    $height = ($height * 100) / $width;
                    $padding = 100 - $height;
                    echo "<img style='margin-top:$padding.;' src='$vista' width='100px' height='$height.px' id='img'>";
                } else {
                    $width = ($width * 100) / $height;
                    echo "<img src='$vista' width='$width.px' height='100px' id='img'>";
                }
            } else {
                echo "Vista previa del PDF no disponible";
            }
        }
    }
}

