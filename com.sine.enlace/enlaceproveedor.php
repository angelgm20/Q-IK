<?php

require_once '../com.sine.modelo/Proveedor.php';
require_once '../com.sine.controlador/ControladorProveedor.php';
if (isset($_POST['transaccion'])) {
    $transaccion = $_POST['transaccion'];
    switch ($transaccion) {
        case 'insertarproveedor':
            $p = new Proveedor();
            $cp = new ControladorProveedor();
            $empresa = $_POST['empresa'];
            $representante = $_POST['representante'];
            $correo = $_POST['correo'];
            $telefono = $_POST['telefono'];
            $cuenta = $_POST['cuenta'];
            $clabe = $_POST['clabe'];
            $idbanco = $_POST['idbanco'];
            $sucursal = $_POST['sucursal'];
            $rfc = $_POST['rfc'];
            $razon = $_POST['razon'];
            
            $p->setEmpresa($empresa);
            $p->setRepresentante($representante);
            $p->setTelefono($telefono);
            $p->setEmail($correo);
            $p->setNum_cuenta($cuenta);
            $p->setClave_interbancaria($clabe);
            $p->setId_banco($idbanco);
            $p->setSucursal($sucursal);
            $p->setRfc($rfc);
            $p->setRazon($razon);
            
            $insertado = $cp->nuevoProveedor($p);
            if ($insertado) {
                echo "Registro insertado";
            } else {
                echo "0Error: no inserto el registro ";
            }
            break;
            
        case 'listaproveedor':
            $cu = new ControladorProveedor();
            $datos = $cu->listaProveedor();
            if ($datos != "") {
                echo $datos;
            } else {
                echo "0No se han econtrado datos";
            }
            break;
        case 'editarproveedor':
            $cu = new ControladorProveedor();
            $idproveedor = $_POST['idproveedor'];
            $datos = $cu->getDatosProveedor($idproveedor);
            if ($datos != "") {
                echo $datos;
            } else {
                echo "0No se han encontrado datos";
            }
            break;
        case 'actualizarproveedor':
            $p = new Proveedor();
            $cp = new ControladorProveedor();
            
            $idproveedor = $_POST['idproveedor'];
            $empresa = $_POST['empresa'];
            $representante = $_POST['representante'];
            $correo = $_POST['correo'];
            $telefono = $_POST['telefono'];
            $cuenta = $_POST['cuenta'];
            $clabe = $_POST['clabe'];
            $idbanco = $_POST['idbanco'];
            $sucursal = $_POST['sucursal'];
            $p->setId_proveedor($idproveedor);
            $p->setEmpresa($empresa);
            $p->setRepresentante($representante);
            $p->setTelefono($telefono);
            $p->setEmail($correo);
            $p->setNum_cuenta($cuenta);
            $p->setClave_interbancaria($clabe);
            $p->setId_banco($idbanco);
            $p->setSucursal($sucursal);
            $actualizado = $cp->modificarProveedor($p);
            if ($actualizado) {
                echo "Registro guardado";
            } else {
                echo "0Error: no guardo el registro ";
            }
            break;
        case 'eliminarproveedor':
            $cp = new ControladorProveedor();
            $idproveedor = $_POST['idproveedor'];
            $eliminado = $cp->quitarProveedor($idproveedor);
            if ($eliminado) {
                echo "Registro eliminado";
            } else {
                echo "0No se han econtrado datos";
            }
            break;
        case 'filtrarproveedor':
            $cp = new ControladorProveedor();
            $REF = $_POST['REF'];
            $pag = $_POST['pag'];
            $numreg = $_POST['numreg'];
            
            $datos = $cp->listaServiciosHistorial($REF, $pag, $numreg);
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
