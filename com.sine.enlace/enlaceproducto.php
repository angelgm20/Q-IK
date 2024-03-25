<?php

require_once '../com.sine.modelo/Producto.php';
require_once '../com.sine.controlador/ControladorProducto.php';

if (isset($_POST['transaccion'])) {
    $transaccion = $_POST['transaccion'];
    switch ($transaccion) {
        case 'insertarproducto':
            $p = new Producto();
            $cp = new ControladorProducto();
            $codproducto = $_POST['codproducto'];
            $producto = $_POST['producto'];
            $tipop = $_POST['tipo'];
            $unidad = $_POST['unidad'];
            $divide = explode("-", $unidad);
            $clvunidad = $divide[0];
            $descripcionunidad = $divide[1];
            $descripcion = $_POST['descripcion'];
            $pcompra = $_POST['pcompra'];
            if ($pcompra == '') {
                $pcompra = '0';
            }
            $porcentaje = $_POST['porcentaje'];
            $ganancia = $_POST['ganancia'];
            $pventa = $_POST['pventa'];
            if ($pventa == '') {
                $pventa = '0';
            }
            $clavefiscal = $_POST['clavefiscal'];
            $divide2 = explode("-", $clavefiscal);
            $clvfiscal = $divide2[0];
            $descripcionfiscal = $divide2[1];
            $idproveedor = $_POST['idproveedor'];
            $chinventario = $_POST['chinventario'];
            $cantidad = $_POST['cantidad'];
            $img = $_POST["imagen"];
            $insert = "";
            if (isset($_POST['insert'])) {
                $insert = $_POST['insert'];
            }

            $p->setCodproducto($codproducto);
            $p->setProducto($producto);
            $p->setClvunidad($clvunidad);
            $p->setDescripcionunidad($descripcionunidad);
            $p->setDescripcion($descripcion);
            $p->setPrecio_compra($pcompra);
            $p->setPorcentaje($porcentaje);
            $p->setGanancia($ganancia);
            $p->setPrecio_venta($pventa);
            $p->setTipo($tipop);
            $p->setClavefiscal($clvfiscal);
            $p->setDescripcionfiscal($descripcionfiscal);
            $p->setIdproveedor($idproveedor);
            $p->setChinventario($chinventario);
            $p->setCantidad($cantidad);
            $p->setImagen($img);
            $p->setInsert($insert);

            $insertado = $cp->validarCodigo($p);
            if ($insertado) {
                echo $insertado;
            } else {
                echo "No registrooo  producto";
            }
            break;
        case 'listaproductosaltas':
            $datos = "";
            $cp = new ControladorProducto();
            $datos = $cp->listaProductos();
            if ($datos != "") {
                echo $datos;
            } else {
                echo "0Ah ocurrido un error";
            }
            break;
        case 'editarproducto':
            $datos = "";
            $idproducto = $_POST['idproducto'];
            $cp = new ControladorProducto();
            $datos = $cp->datosProductos($idproducto);
            if ($datos != "") {
                echo $datos;
            } else {
                echo "0Ah ocurrido un error";
            }
            break;
        case 'actualizarproducto':
            $p = new Producto();
            $cp = new ControladorProducto();
            $idproducto = $_POST['idproducto'];
            $codproducto = $_POST['codproducto'];
            $producto = $_POST['producto'];
            $tipop = $_POST['tipo'];
            $unidad = $_POST['unidad'];
            $divide = explode("-", $unidad);
            $clvunidad = $divide[0];
            $descripcionunidad = $divide[1];
            $descripcion = $_POST['descripcion'];
            $pcompra = $_POST['pcompra'];
            $porcentaje = $_POST['porcentaje'];
            $ganancia = $_POST['ganancia'];
            $pventa = $_POST['pventa'];
            $clavefiscal = $_POST['clavefiscal'];
            $divide2 = explode("-", $clavefiscal);
            $clvfiscal = $divide2[0];
            $descripcionfiscal = $divide2[1];
            $idproveedor = $_POST['idproveedor'];
            $chinventario = $_POST['chinventario'];
            $cantidad = $_POST['cantidad'];
            $img = $_POST["imagen"];
            $imgactualizar = $_POST['imgactualizar'];
            $insert = $_POST['insert'];
            $idtmp = $_POST['idtmp'];

            $p->setIdProducto($idproducto);
            $p->setCodproducto($codproducto);
            $p->setProducto($producto);
            $p->setClvunidad($clvunidad);
            $p->setDescripcionunidad($descripcionunidad);
            $p->setDescripcion($descripcion);
            $p->setPrecio_compra($pcompra);
            $p->setPorcentaje($porcentaje);
            $p->setGanancia($ganancia);
            $p->setPrecio_venta($pventa);
            $p->setTipo($tipop);
            $p->setClavefiscal($clvfiscal);
            $p->setDescripcionfiscal($descripcionfiscal);
            $p->setIdproveedor($idproveedor);
            $p->setChinventario($chinventario);
            $p->setCantidad($cantidad);
            $p->setImagen($img);
            $p->setImgactualizar($imgactualizar);
            $p->setInsert($insert);
            $p->setIdtmp($idtmp);

            $actualizado = $cp->valCodigoActualizar($p);
            if ($actualizado) {
                echo "Registro guardado correctamente";
            } else {
                echo "ocurrido un error";
            }
            break;
        case 'actualizarinventario':
            $p = new Producto();
            $cp = new ControladorProducto();
            $idinventario = $_POST['idinventario'];
            $idproducto = $_POST['idproducto'];
            $cantidad = $_POST['cantidad'];
            $p->setIdProducto($idproducto);
            $p->setIdinventario($idinventario);
            $p->setCantidad($cantidad);
            $actualizado = $cp->actualizarInventario($p);
            if ($actualizado) {
                echo "Registro guardado correctamente";
            } else {
                echo "0Ah ocurrido un error";
            }
            break;
        case 'activarinventario':
            $datos = "";
            $idproducto = $_POST['idproducto'];
            $cantidad = $_POST['cantidad'];
            $estado = '1';

            $c = new Producto();
            $c->setIdProducto($idproducto);
            $c->setCantidad($cantidad);
            $c->setChinventario($estado);
            $cp = new ControladorProducto();
            $inventario = $cp->estadoInventario($c);
            if ($inventario) {
                echo "Registro eliminado";
            } else {
                echo "0Ah ocurrido un error";
            }
            break;
        case 'desactivarinventario':
            $datos = "";
            $idproducto = $_POST['idproducto'];
            $cantidad = '0';
            $estado = '0';

            $c = new Producto();
            $c->setIdProducto($idproducto);
            $c->setCantidad($cantidad);
            $c->setChinventario($estado);
            $cp = new ControladorProducto();
            $inventario = $cp->estadoInventario($c);
            if ($inventario) {
                echo "Registro eliminado";
            } else {
                echo "0Ah ocurrido un error";
            }
            break;
        case 'cambiarcantidad':
            $inventario = "";
            $idproducto = $_POST['idproducto'];
            $cantidad = $_POST['cantidad'];

            $c = new Producto();
            $c->setIdProducto($idproducto);
            $c->setCantidad($cantidad);
            $cp = new ControladorProducto();
            $inventario = $cp->insertarInventario($c);
            if ($inventario) {
                echo "Registro eliminado";
            } else {
                echo "0Ah ocurrido un error";
            }
            break;
        case 'eliminarproducto':
            $datos = "";
            $idproducto = $_POST['idproducto'];
            $cp = new ControladorProducto();
            $eliminado = $cp->quitarProducto($idproducto);
            if ($eliminado) {
                echo "Registro eliminado";
            } else {
                echo "0Ah ocurrido un error";
            }
            break;

        case 'filtrarproducto':
            $cp = new ControladorProducto();
            $NOM = $_POST['NOM'];
            $pag = $_POST['pag'];
            $numreg = $_POST['numreg'];
            $datos = $cp->listaProductosHistorial($NOM, $pag, $numreg);
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