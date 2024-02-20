<?php

require_once '../com.sine.controlador/ControladorCron.php';
if (isset($_POST['transaccion'])) {
    $transaccion = $_POST['transaccion'];
    switch ($transaccion) {
        case 'refactura':
            $cf = new ControladorCron();
            $datos = $cf->Contratosfacturar();
            if ($datos != "") {
                echo '$datos';
            } else {
                echo '$datos';
            }
            break;
        default:
            break;
    }
}
