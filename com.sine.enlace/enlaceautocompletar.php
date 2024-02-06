<?php

if (isset($_GET['transaccion'])) {
    $transaccion = $_GET['transaccion'];
    include_once '../com.sine.controlador/ControladorUsuario.php';
    include_once '../com.sine.controlador/ControladorAuto.php';
    switch ($transaccion) {
        case 'nombrecliente':
            $cp = new ControladorAuto();
            echo json_encode($cp->getCoincidenciasBusquedaCliente($_GET['term']));
            break;
        case 'nomcliente':
            $cp = new ControladorAuto();
            echo json_encode($cp->getCoincidenciasBusquedaCliente2($_GET['term']));
            break;
        case 'emailcliente':
            $cp = new ControladorAuto();
            echo json_encode($cp->getCoincidenciasBusquedaMail($_GET['term']));
            break;
        case 'catfiscal':
            $cp = new ControladorAuto();
            echo json_encode($cp->getCoincidenciasCatalogoFiscal($_GET['term']));
            break;
        case 'datosfiscales':
            $cp = new ControladorAuto();
            echo json_encode($cp->getCoincidenciasCliente($_GET['term']));
            break;
        case 'facturas':
            $iddatos = $_GET['iddatos'];
            $cp = new ControladorAuto();
            echo json_encode($cp->getCoincidenciasFacturas($_GET['term'], $iddatos));
            break;
        case 'catunidad':
            $cp = new ControladorAuto();
            echo json_encode($cp->getCoincidenciasCatalogoUnidad($_GET['term']));
            break;
        case 'foliocotizaion':
            $cp = new ControladorAuto();
            echo json_encode($cp->getCoincidenciasFolioCotizacion($_GET['term']));
            break;
        case 'direccion':
            $cp = new ControladorAuto();
            echo json_encode($cp->getCoincidenciasDireccion($_GET['term']));
            break;
        case 'regimenfiscal':
            $cp = new ControladorAuto();
            echo json_encode($cp->getCoincidenciasRegimen($_GET['term']));
            break;
        case 'claveregimen':
            $cp = new ControladorAuto();
            echo json_encode($cp->getCoincidenciasClvRegimen($_GET['term']));
            break;
        case 'empleado':
            $cp = new ControladorAuto();
            echo json_encode($cp->getCoincidenciasEmpleado($_GET['term']));
            break;
        case 'localidad':
            $cp = new ControladorAuto();
            echo json_encode($cp->getCoincidenciasLocalidad($_GET['term']));
            break;
        case 'tipopermiso':
            $cp = new ControladorAuto();
            echo json_encode($cp->getCoincidenciasTipoPermiso($_GET['term']));
            break;
        case 'conftransporte':
            $cp = new ControladorAuto();
            echo json_encode($cp->getCoincidenciasConfigTransporte($_GET['term']));
            break;
        case 'subtiporemolque':
            $cp = new ControladorAuto();
            echo json_encode($cp->getCoincidenciasTipoRemolque($_GET['term']));
            break;
        case 'mercancia':
            $cp = new ControladorAuto();
            $b = $_GET['b'];
            $result = "";
            if ($b == '1') {
                $result = json_encode($cp->getCoincidenciasProducto($_GET['term']));
            } else if ($b == '2') {
                $result = json_encode($cp->getCoincidenciasCatalogoFiscal($_GET['term']));
            }
            echo $result;
            break;
        case 'peligro':
            $cp = new ControladorAuto();
            echo json_encode($cp->getCoincidenciasMaterialPeligroso($_GET['term']));;
            break;
        case 'embalaje':
            $cp = new ControladorAuto();
            echo json_encode($cp->getCoincidenciasEmbalaje($_GET['term']));;
            break;
        case 'unitcarta':
            $cp = new ControladorAuto();
            echo json_encode($cp->getCoincidenciasUnidadCarta($_GET['term']));
            break;
        case 'vehiculo':
            $cp = new ControladorAuto();
            echo json_encode($cp->getCoincidenciasVehiculo($_GET['term']));
            break;
        case 'remolque':
            $cp = new ControladorAuto();
            echo json_encode($cp->getCoincidenciasRemolque($_GET['term']));
            break;
        case 'ubicacion':
            $cp = new ControladorAuto();
            $b = $_GET['b'];
            echo json_encode($cp->getCoincidenciasUbicacion($_GET['term'], $b));
            break;
        case 'operador':
            $cp = new ControladorAuto();
            echo json_encode($cp->getCoincidenciasOperador($_GET['term']));
            break;
        default:
            break;
    }
} else {
    header("Location: ../");
}
