<?php

include_once '../com.sine.common/security.php';
require_once '../Enrutador.php';
if (isset($_POST['transaccion']) && isset($_POST['view'])) {
    $transaccion = $_POST['transaccion'];
    $vista = $_POST['view'];
    if ($transaccion != "" && $vista != "") {
        switch ($vista) {
            case 'paginicio':
                $e = new Enrutador();
                $datos = $e->cargarVista($vista);
                break;
            case 'notificacion':
                $e = new Enrutador();
                $datos = $e->cargarVista($vista);
                break;
            case 'comprar':
                $e = new Enrutador();
                $datos = $e->cargarVista($vista);
            case 'nuevousuario':
                $e = new Enrutador();
                $datos = $e->cargarVista($vista);
                break;
            case 'listasuarioaltas':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'asignarpermisos':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'categoria':
                $e = new Enrutador();
                $datos = $e->cargarVista($vista);
                break;
            case 'listacategoria':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'nuevoproducto':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'listaproductoaltas':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'valrfc':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'nuevocliente':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'listaclientealtas':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'comunicado':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'listacomunicado':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'cfdi':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'datosempresa':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'nuevocontrato':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'registrarpago':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'pago':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'listapago':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'factura':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'listafactura':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'cotizacion':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'listacotizacion':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'instalacion':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'listainstalacion':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'listacontrato':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'listacontratos':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'listaprecios':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'listaempresa':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'listacfdi':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'nuevoproveedor':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'listaproveedor':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'forminventario':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'listainventario':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'impuesto':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'listaimpuesto':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'reportefactura':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'reportepago':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'reportegrafica':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'reportesat':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'reporteventas':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'datosiva':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'config':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'encabezado':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'correo':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'folio':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'listafolio':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'comision':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'listafiel':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'nuevafiel':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'listadescsolicitud':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'descsolicitud':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'empleado':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'listaempleado':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'nomina':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'listanomina':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'direccion':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'listadireccion':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'transporte':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'listatransporte':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'remolque':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'listaremolque':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'operador':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'listaoperador':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'listacarta':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'carta':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'listacarta':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            case 'tablas':
                $e = new Enrutador();
                $e->cargarVista($vista);
                break;
            default:
                echo "0Recurso no disponible";
                break;
        }
    } else {
        echo "0Recurso no disponible";
    }
} else {
    echo "0Recurso no disponible";
}


