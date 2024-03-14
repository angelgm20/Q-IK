<?php

require_once '../com.sine.controlador/ControladorOpcion.php';
if (isset($_POST['transaccion'])) {
    $transaccion = $_POST['transaccion'];
    $co = new ControladorOpcion();
    switch ($transaccion) {
        case 'opcionesbancocliente':
            $idcliente = $_POST['idcliente'];
            $datos = $co->opcionesBancobyCliente($idcliente);
            echo $datos;
            break;
        case 'opcionesbeneficiario':
            $iddatos = $_POST['iddatos'];
            $datos = $co->opcionesBeneficiario($iddatos);
            echo $datos;
            break;
        case 'opcionescliente':
            $datos = $co->opcionesCliente();
            echo $datos;
            break;
        case 'opcionesfacturacion':
            $id = $_POST['id'];
            $datos = $co->opcionesDatFacturacion($id);
            echo $datos;
            break;
        case 'opcionesmpago':
            $selected = $_POST['selected'];
            $datos = $co->opcionesMetodoPago($selected);
            echo $datos;
            break;
        /*case 'opcionesformapago':
            $selected = $_POST['selected'];
            $datos = $co->opcionesFormaPago('', $selected);
            echo $datos;
            break;
        case 'opcionesformapago2':
            $selected = $_POST['selected'];
            $condicion = "where c_pago !='99'";
            $datos = $co->opcionesFormaPago($condicion, $selected);
            echo $datos;
            break;*/
        case 'opcionesmoneda':
            $idmoneda = $_POST['idmoneda'];
            $datos = $co->opcionesMoneda($idmoneda);
            echo $datos;
            break;
        /*case 'opcionesusocfdi':
            $iduso = $_POST['iduso'];
            $datos = $co->opcionesUsoCFDI($iduso);
            echo $datos;
            break;
        case 'opcionescomprobante':
            $id = $_POST['id'];
            $datos = $co->opcionesComprobante($id);
            echo $datos;
            break;*/
        case 'opcionesproveedor':
            $idproveedor = $_POST['idprov'];
            $datos = $co->opcionesProveedor($idproveedor);
            echo $datos;
            break;
        case 'opcionesregimen':
            $idregimen = $_POST['idregimen'];
            $datos = $co->opcionesRegimen($idregimen);
            echo $datos;
            break;
        case 'opcionesperiodicidad':
            $idper = $_POST['idper'];
            $datos = $co->opcionesPeriodicidad($idper);
            echo $datos;
            break;
        case 'opcionesjornada':
            $idjor = $_POST['idjor'];
            $datos = $co->opcionesJornada($idjor);
            echo $datos;
            break;
        case 'opcionescontrato':
            $idcontrato = $_POST['idcontrato'];
            $datos = $co->opcionesContrato($idcontrato);
            echo $datos;
            break;
        case 'buscarcp':
            $cp = $_POST['cp'];
            $datos = $co->opcionesEstadoCP($cp);
            echo $datos;
            break;
        case 'opcionesestado':
            $idestado = $_POST['idestado'];
            $datos = $co->opcionesEstadoClv($idestado);
            echo $datos;
            break;
        case 'opcionesmunicipio':
            $id = $_POST['idestado'];
            $idmunicipio = $_POST['idmunicipio'];
            $datos = $co->opcionesMunicipioByEstado($id, $idmunicipio);
            echo $datos;
            break;
        /*case 'opcionesbanco':
            $idbanco = $_POST['idbanco'];
            $datos = $co->opcionesBanco($idbanco);
            echo $datos;
            break;*/
        case 'addopcionesbanco':
            $a = $_POST['a'];
            $idbanco = $_POST['idbanco'];
            $datos = $co->addopcionesBanco($a, $idbanco);
            echo $datos;
            break;
        case 'opcionesriesgo':
            $idriesgo = $_POST['idriesgo'];
            $datos = $co->opcionesRiesgo($idriesgo);
            echo $datos;
            break;
        case 'opcionesvendedor':
            $datos = $co->opcionesVendedor();
            echo $datos;
            break;
        case 'opcionesano':
            $datos = $co->opcionesAno();
            echo $datos;
            break;
        case 'opcionesusuario':
            $datos = $co->opcionesUsuario();
            echo $datos;
            break;
        case 'opcionesfolio':
            $id = $_POST['id'];
    		$serie = $_POST['serie'];
            $folio = $_POST['folio'];
            $datos = $co->opcionesFolios($id, $folio,  $serie );
            echo $datos;
            break;
        case 'correolist':
            $datos = $co->opcionesCorreoList();
            echo $datos;
            break;
        case 'opcionesmotivo':
            $datos = $co->opcionesMotivo();
            echo $datos;
            break;
        case 'opcionesimpuestos':
            $t = $_POST['t'];
            $datos = $co->opcionesImpuestos($t);
            echo $datos;
            break;
        /*case 'opcionesrelacion':
            $datos = $co->opcionesTipoRelacion();
            echo $datos;
            break;*/
        case 'periodoglobal':
            $id = $_POST['id'];
            $datos = $co->opcionesPeriodoGlobal($id);
            echo $datos;
            break;
        case 'opcionesmeses':
            $id = $_POST['id'];
            $datos = $co->opcionesMesesPeriodo($id);
            echo $datos;
            break;
        case 'anoglobal':
            $datos = $co->opcionesAnoGlobal();
            echo $datos;
            break;
        default:
            break;
    }
}
