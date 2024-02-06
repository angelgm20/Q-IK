<?php

require_once '../com.sine.modelo/Reportes.php';
require_once '../com.sine.controlador/ControladorReportes.php';

if (isset($_FILES["archivo-sat"])) {

    $f = new Reportes();
    $cf = new ControladorReportes();

    $tempfile = ($_FILES['archivo-sat']['tmp_name']);
    $file = $_FILES["archivo-sat"];
    $tipo = $file["type"];
    if ($tipo == "text/plain") {
        $ruta_provisional = $file["tmp_name"];
        $cont = file_get_contents($ruta_provisional);
        $insertar = $cf->insertarDatos($cont);
    } else {
        $insertar = "0Tipo de Archivo no Permitido";
    }

    echo $insertar;
}
