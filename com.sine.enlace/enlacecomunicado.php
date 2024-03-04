<?php

require_once '../com.sine.modelo/Comunicado.php';
require_once '../com.sine.controlador/ControladorComunicado.php';
require_once '../com.sine.modelo/Usuario.php';
require_once '../com.sine.modelo/Session.php';
Session::start();
if (isset($_POST['transaccion'])) {
    $transaccion = $_POST['transaccion'];
    switch ($transaccion) {
        case 'insertarcomunicado':
            $c = new Comunicado();
            $cc = new ControladorComunicado();
            $chcom =$_POST['chcom'];
            $idcontactos = $_POST['idcontactos'];
            $asunto = $_POST['asunto'];
            $emision = $_POST['emision'];
            $color = $_POST['color'];
            $size = $_POST['size'];
            $texto = $_POST['txtbd'];
            $sellar = $_POST['sellar'];
            $firma = $_POST['firma'];
            $iddatos = $_POST['iddatos'];
            $sessionid = session_id();
            
            $c->setChcom($chcom);
            $c->setIdcontactos($idcontactos);
            $c->setAsunto($asunto);
            $c->setEmision($emision);
            $c->setColor($color);
            $c->setSize($size);
            $c->setMensaje($texto);
            $c->setSellar($sellar);
            $c->setFirma($firma);
            $c->setIddatos($iddatos);
            $c->setSid($sessionid);
            
            $insertado = $cc->insertarComunicado($c);
            if ($insertado) {
                echo $insertado;
            }
            break;
        case 'listacomunicado':
            $cc = new ControladorComunicado();
            $REF = $_POST['REF'];
            $pag = $_POST['pag'];
            $numreg = $_POST['numreg'];
            $datos = $cc->listaComunicado($REF, $pag, $numreg);
            if ($datos != "") {
                echo $datos;
            } else {
                echo "0No se han encontrado datos";
            }
            break;
        case 'editarcomunicado':
            $cf = new ControladorComunicado();
            $idcomunicado = $_POST['idcomunicado'];
            $datos = $cf->getDatosComunicado($idcomunicado);
            if ($datos != "") {
                echo $datos;
            } else {
                echo "0No se han econtrado datos";
            }
            break;
        case 'editarestado':
            $cf = new ControladorFactura();
            $idfactura = $_POST['idfactura'];
            $estado = $_POST['estado'];
            $datos = $cf->estadoFactura($idfactura, $estado);
            if ($datos != "") {
                echo $datos;
            } else {
                echo "0No hay clientes registrados";
            }
            break;
        case 'actualizarcomunicado':
            $c = new Comunicado();
            $cc = new ControladorComunicado();
            $idcomunicado = $_POST['idcomunicado'];
            $tag = $_POST['tag'];
            $chcom =$_POST['chcom'];
            $idcontactos = $_POST['idcontactos'];
            $asunto = $_POST['asunto'];
            $emision = $_POST['emision'];
            $color = $_POST['color'];
            $size = $_POST['size'];
            $texto = $_POST['txtbd'];
            $sellar = $_POST['sellar'];
            $firma = $_POST['firma'];
            $iddatos = $_POST['iddatos'];
            $sessionid = session_id();
            
            $c->setIdcomunicado($idcomunicado);
            $c->setTag($tag);
            $c->setChcom($chcom);
            $c->setIdcontactos($idcontactos);
            $c->setAsunto($asunto);
            $c->setEmision($emision);
            $c->setColor($color);
            $c->setSize($size);
            $c->setMensaje($texto);
            $c->setSellar($sellar);
            $c->setFirma($firma);
            $c->setIddatos($iddatos);
            $c->setSid($sessionid);
            $insertado = $cc->actualizarComunicado($c);
            if ($insertado) {
                echo $insertado;
            }
            break;
        case 'eliminarcomunicado':
            $ca = new ControladorComunicado();
            $idcomunicado = $_POST['idcomunicado'];
            $eliminado = $ca->eliminarComunicado($idcomunicado);
            if ($eliminado) {
                echo "1Registro eliminado";
            }
            break;
        case 'fecha':
            $ca = new ControladorComunicado();
            $fecha = $ca->getFecha();
            if ($fecha) {
                echo $fecha;
            } else {
                echo "0No se han encontrado datos";
            }
            break;
        case 'opcionescategoria':
            $cf = new ControladorComunicado();
            $datos = $cf->opcionesCategoria();
            if ($datos != "") {
                echo $datos;
            } else {
                echo "0No hay categorias registradas";
            }
            break;
        case 'loadcategoria':
            $idcategoria = $_POST['idcategoria'];
            $cf = new ControladorComunicado();
            $datos = $cf->getCategoriaById($idcategoria);
            if ($datos != "") {
                echo $datos;
            } else {
                echo "0No hay categorias registradas";
            }
            break;
        case 'loadcontactos':
            $cf = new ControladorComunicado();
            $datos = $cf->getContactoByCat();
            if ($datos != "") {
                echo $datos;
            } else {
                echo "0No hay categorias registradas";
            }
            break;
        case 'imgscom':
            $datos = "";
            $cf = new ControladorComunicado();
            $sid = session_id();
            $tag = $_POST['tag'];
            
            $datos = $cf->getImgsComunicado($sid, $tag);
            if ($datos != "") {
                echo $datos;
            } else {
                echo "0Ah ocurrido un error";
            }
            break;
        case 'prodcotizacion':
            $datos = "";
            $f = new Cotizacion();
            $cf = new ControladorCotizacion();
            $idcotizacion = $_POST['idcotizacion'];
            $sessionid = session_id();
            $datos = $cf->productosCotizacion($idcotizacion, $sessionid);
            if ($datos != "") {
                echo $datos;
            } else {
                echo "0Ah ocurrido un error";
            }
            break;
        case 'eliminar':
            $cf = new ControladorFactura();
            $idtemp = $_POST['idtemp'];
            $cantidad = $_POST['cantidad'];
            $idproducto = $_POST['idproducto'];
            $sessionid = session_id();
            $eliminado = $cf->eliminar($idtemp, $sessionid, $cantidad, $idproducto);
            if ($eliminado) {
                echo $eliminado;
            } else {
                echo "0No se han encontrado datos";
            }
            break;
        case 'cancelar':
            $cf = new ControladorCotizacion();
            $sessionid = session_id();
            $eliminado = $cf->cancelar($sessionid);
            if ($eliminado) {
                echo "1Registro eliminado";
            } else {
                echo "0No se han encontrado datos";
            }
            break;

        case 'tablaimg':
                $datos = "";
                $cf = new ControladorComunicado();
                $datos = $cf->tablaIMG();
                if ($datos != "") {
                    echo  $datos;
                } else {
                    echo 'error al obtener la tabla de imágenes';
                }
            break;

        case 'eliminarimg':
            if (isset($_POST['idtmp'])) {
                $idtmp = $_POST['idtmp'];
                $cf = new ControladorComunicado();
                $resultado = $cf->eliminarIMG($idtmp);
                if ($resultado) {
                    echo '1Eliminado correctamente';
                } else {
                    echo '0Error al eliminar la imagen';
                }
            } else {
                echo '0ID de imagen no proporcionado';
            }
            break;
            
       


        default:
            break;
    }
}
