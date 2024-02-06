<?php
require_once '../com.sine.modelo/Cliente.php';
require_once '../com.sine.controlador/Controladorcliente.php';

if (isset($_POST['transaccion'])) {
    $transaccion = $_POST['transaccion'];
    switch ($transaccion) {
        case 'insertarcliente':
            $p = new Cliente(); 
            $cp = new Controladorcliente();
            $nombre = $_POST["nombre"];
            $apellidoPaterno = $_POST["apellidopaterno"];
            $apellidoMaterno = $_POST["apellidomaterno"];
            $rfc = $_POST["rfc"];
            $nombreEmpresa = $_POST["nombreEmpresa"];
            $correoinfo = $_POST["correoinfo"];
            $emailFacturacion = $_POST["emailfacturacion"];
            $correo_gerencia = $_POST["emailGerencia"];
            $telefono = $_POST["telefono"];
            $banco = $_POST["banco"];
            $cuenta = $_POST["cuenta"];
            $clabe = $_POST["clabe"];

            $p->setNombre($nombre);
            $p->setapellidoPaterno($apellidoPaterno);
            $p->setApellidoMaterno($apellidoMaterno);
            $p->setRfc($rfc);
            $p->setNombre_empresa($nombreEmpresa);
            $p->setCorreoinfo($correoinfo);
            $p->setCorreo_fact($emailFacturacion);
            $p->setCorreo_gerencia($correo_gerencia);
            $p->setTelefono($telefono);
            $p->setIdbanco($banco);
            $p->setCuenta($cuenta);
            $p->setClabe($clabe);

            $insertado = $cp->nuevoCliente($p);
            if ($insertado) {
                echo "Registro insertado";
            } else {
                echo "Error: no insertó el registro";
            }
            break;

        case 'listaclientes':
            $cu = new Controladorcliente();
            $datos = $cu->listaclientealtas();
            if ($datos != "") {
                echo $datos;
            } else {
                echo "0No se han encontrado datos";
            }
            break;

        case 'editarcliente':
            $cu = new Controladorcliente();
            $idcliente = $_POST['idcliente'];
            $datos = $cu->editarCliente($idcliente);
            if ($datos != "") { 
                echo $datos;
            } else {
                echo "0o se han encontrado datos";
                
            }
            break;

        case 'actualizarcliente':
            $p = new Cliente(); 
            $cp = new Controladorcliente();
            $idCliente = $_POST["idcliente"];   
            $nombre = $_POST["nombre"];
            $apellidoPaterno = $_POST["apellidopaterno"];
            $apellidoMaterno = $_POST["apellidomaterno"];
            $rfc = $_POST["rfc"];
            $nombreEmpresa = $_POST["nombreEmpresa"];
            $correoinfo = $_POST["correoinfo"];
            $emailFacturacion = $_POST["emailfacturacion"];
            $correo_gerencia = $_POST["emailGerencia"];
            $telefono = $_POST["telefono"];
            $banco = $_POST["banco"];
            $cuenta = $_POST["cuenta"];
            $clabe = $_POST["clabe"];

            
            $p->setIdCliente($idCliente);
            $p->setNombre($nombre);
            $p->setapellidoPaterno($apellidoPaterno);
            $p->setApellidoMaterno($apellidoMaterno);
            $p->setRfc($rfc);
            $p->setNombre_empresa($nombreEmpresa);
            $p->setCorreoinfo($correoinfo);
            $p->setCorreo_fact($emailFacturacion);
            $p->setCorreo_gerencia($correo_gerencia);
            $p->setTelefono($telefono);
            $p->setIdbanco($banco);
            $p->setCuenta($cuenta);
            $p->setClabe($clabe);

            $actualizado = $cp->modificarCliente($p); 
            if ($actualizado) {
                echo "Registro guardado";
            } else {
                echo "Error: no guardó el registro";
            }
            break;

        case 'eliminarcliente':
            $cp = new Controladorcliente();
            $idcliente = $_POST['idcliente'];
            $eliminado = $cp->eliminarCliente($idcliente);
            if ($eliminado) {
                echo "Registro eliminado";
            } else {
                echo "No se han encontrado datos";
            }
            break;

            case 'filtrarcliente':
            $cp = new Controladorcliente();
            $REF = $_POST['REF'];
            $pag = $_POST['pag'];
            $numreg = $_POST['numreg'];

            $datos = $cp->listaServiciosHistorialCliente($REF, $pag, $numreg);
            if ($datos != "") {
                echo $datos;
            } else {
                echo "0Ah ocurrido un error";
            }
            break;

        default:
            break;
    }
}
?>
