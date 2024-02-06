<?php
if(isset($_POST['transaccion']) && $_POST['transaccion']!=""){
    require '../com.sine.controlador/ControladorListas.php';
    $transaccion=$_POST['transaccion'];
    switch ($transaccion) {
        case 'opcionespermisionario':
            $ce=new ControladorListas();
            $datos=$ce->opcionesPermisionario();
            echo $datos;
            break;
        default:
            break;
    }
    
}
else{
    header("Location: ../");
}