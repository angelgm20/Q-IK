<?php

if (isset($_POST['datosid'])) {
    require_once '../com.sine.controlador/ControladorImgs.php';

    $con = new ControladorImgs();

    $id = intval($_POST['datosid']);

    $data = $con->getDatosFacturacion($id);

    $zip = new ZipArchive();

    $filename = '../temporal/csd.zip';
    if(file_exists($filename)){
        unlink($filename);
    }

    if ($zip->open($filename, ziparchive::CREATE) !== TRUE) {
        exit("cannot open <$filename>\n");
    }
    
    $zip->addFile("../temporal/$data/csd.cer", "$data.cer"); // to add current file
    $zip->addFile("../temporal/$data/key.key", "$data.key"); // to add current file
// close and save archive
    $zip->close();

// download file
    if (file_exists($filename)) {
        header('Content-Type: application/zip');
        header('Content-Length: ' . filesize($filename));

        // download zip
        readfile($filename);
    }
} else {
    echo "Error no se encontro el archivo";
}

