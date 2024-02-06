<?php

require_once '../com.sine.controlador/ControladorRegistro.php';
require_once '../com.sine.modelo/Registro.php';

if (isset($_POST['transaccion'])) {
    $transaccion = $_POST['transaccion'];
    switch ($transaccion) {
        case 'insertarusuario':
            $r = new Registro();
            $cr = new ControladorRegistro();
            $nombre = $_POST['nombre'];
            $apellidopaterno = $_POST['apellidopaterno'];
            $apellidomaterno = $_POST['apellidomaterno'];
            $usuario = $_POST['usuario'];
            $contrasena = sha1($_POST['contrasena']);
            $correo = $_POST['correo'];
            $celular = $_POST['celular'];
            $telefono = $_POST['telefono'];
            $estatus = $_POST['estatus'];
            $tipo = $_POST['tipo'];
            
            $r->setNombre($nombre);
            $r->setApellidoPaterno($apellidopaterno);
            $r->setApellidoMaterno($apellidomaterno);
            $r->setUsuario($usuario);
            $r->setContrasena($contrasena);
            $r->setCorreo($correo);
            $r->setCelular($celular);
            $r->setTelefono($telefono);
            $r->setEstatus("activo");
            $r->setTipo($tipo);
            $insertado = $cr->nuevoUsuario($r);
            if ($insertado) {
                echo $insertado;
            } else {
                echo "0Error: no inserto el registro ";
            }
            break;
        case 'logout':
            $cs = new ControladorSession();
            $destruido = $cs->logout('ab125?=o9_.2');
            if ($destruido) {
                echo "salir";
            } else {
                echo "0Ah ocurrido un error";
            }
            break;
        case 'validarsession':
            $cs = new ControladorSession();
            $activa=$cs->sessionIsActive();
            if($activa){
                echo '1';
            }
            else{
                echo "0";
            }
            break;
    	case 'opcionespaquete':
            $cr = new ControladorRegistro();
            $insertado = $cr->loadOpcionesPaquete();
            if ($insertado) {
                echo $insertado;
            } else {
                echo "0Error: no inserto el registro ";
            }
            break;
        case 'detallepaquete':
            $cr = new ControladorRegistro();
            $idpaquete = $_POST['idpaquete'];
            $insertado = $cr->loadDetallePaquete($idpaquete);
            echo $insertado;
            break;
        default :
            break;
    }
} else {
    header("Location: ../");
}
