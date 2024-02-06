<?php

require_once '../com.sine.controlador/ControladorSession.php';
require_once '../com.sine.modelo/Usuario.php';

if (isset($_POST['transaccion'])) {
    $transaccion = $_POST['transaccion'];
    switch ($transaccion) {
        case 'login':
            $u = new Usuario();
            $cs = new ControladorSession();
            $usuario = $_POST['usuario'];
            $contrasena = sha1($_POST['contrasena']);
            $u->setUsuario($usuario);
            $u->setContrasena($contrasena);
            $existe = $cs->login($u);
            if ($existe) {
                echo sha1("holamundo");
            } else {
                echo "0Usuario o contraseña invalidos";
            }
            break;
        case 'loginget':
            $u = new Usuario();
            $cs = new ControladorSession();
            $usuario = $_POST['usuario'];
            $contrasena = $_POST['contrasena'];
            $u->setUsuario($usuario);
            $u->setContrasena($contrasena);
            $existe = $cs->login($u);
            if ($existe) {
                echo sha1("holamundo");
            } else {
                echo "0Usuario o contraseña invalidos";
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
        default :
            break;
    }
} else {
    header("Location: ../");
}
