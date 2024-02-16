<?php

require_once '../com.sine.modelo/TMPPago.php';
require_once '../com.sine.controlador/ControladorPago.php';
require_once '../com.sine.modelo/Usuario.php';
require_once '../com.sine.modelo/Session.php';
require_once '../com.sine.modelo/Pago.php';
Session::start();
if (isset($_POST['transaccion'])) {
    $transaccion = $_POST['transaccion'];
    switch ($transaccion) {
        case 'nuevocomplemento':
            $cp = new ControladorPago();
            $comp = $_POST['comp'];
            $datos = $cp->nuevoComplemento($comp);
            echo $datos;
            break;
        case 'listapagoaltas':
            $REF = $_POST['REF'];
            $pag = $_POST['pag'];
            $numreg = $_POST['numreg'];
            $datos = "";
            $cp = new ControladorPago();
            $datos = $cp->listaServiciosHistorial($REF, $pag, $numreg);
            if ($datos != "") {
                echo $datos;
            } else {
                echo "0Ah ocurrido un error";
            }
            break;
        case 'editarpago':
            $cf = new ControladorPago();
            $idpago = $_POST['idpago'];
            $datos = $cf->getDatosPago($idpago);
            if ($datos != "") {
                echo $datos;
            } else {
                echo "0No se han econtrado datos";
            }
            break;
        case 'pdf':
            $idfactura = $_POST['idpago'];
            $cf = new ControladorPago();
            $datos = $cf->mail($idpago);
            if ($datos != "") {
                echo $datos;
            } else {
                echo "0No hay pagos activos";
            }
            break;
        case 'loadfactura':
            $cf = new ControladorPago();
            $id = $_POST['idfactura'];
            $idpago = $_POST['idpago'];
            $type = $_POST['type'];
            $datos = $cf->loadFactura($id, $idpago, $type);
            if ($datos != "") {
                echo $datos;
            } else {
                echo "0No se encontro la factura";
            }
            break;
        case 'loaddatosfiscales':
            $cf = new ControladorFactura();
            $idcarta = $_POST['idcarta'];
            $datos = $cf->loadFiscales($idcarta);
            if ($datos != "") {
                echo $datos;
            } else {
                echo "0No se encontraron los datos";
            }
            break;
        case 'tipocambio':
            $cf = new ControladorPago();
            $datos = $cf->updateTipoCambio();
            if ($datos != "") {
                echo $datos;
            } else {
                echo "0Error al obtener los datos";
            }
            break;
        case 'gettipocambio':
            $cf = new ControladorPago();
            $idmoneda = $_POST['idmoneda'];
            $datos = $cf->getTipoCambio($idmoneda);
            if ($datos != "") {
                echo $datos;
            } else {
                echo "0Error al obtener los datos";
            }
            break;
        case 'mail':
            $idfactura = $_POST['idfactura'];
            $cp = new ControladorFactura();
            $datos = $cp->mail($idfactura);
            if ($datos != "") {
                echo $datos;
            } else {
                echo "0No Hay Facturas activas";
            }
            break;
        case 'cfdipago':
            $datos = "";
            $f = new Pago();
            $cf = new ControladorPago();
            $idpago = $_POST['idpago'];
            $sessionid = session_id();
            $datos = $cf->cfdisPago($idpago, $sessionid);
            if ($datos != "") {
                echo $datos;
            } else {
                echo "0Ah ocurrido un error";
            }
            break;
        case 'cancelartimbre':
            $cf = new ControladorPago();
            $idpago = $_POST['idpago'];
            $motivo = $_POST['motivo'];
            $reemplazo = $_POST['reemplazo'];
            $eliminado = $cf->cancelarTimbre($idpago, $motivo, $reemplazo);
            if ($eliminado) {
                echo $eliminado;
            } else {
                echo "0No se han encontrado datos";
            }
            break;
        case 'eliminar':
            $cf = new ControladorPago();
            $idtemp = $_POST['idtemp'];
            $sessionid = session_id();
            $eliminado = $cf->eliminar($idtemp, $sessionid);
            if ($eliminado) {
                echo $eliminado;
            } else {
                echo "No se encontro datos";
            }
            break;
        case 'cancelar':
            $cf = new ControladorPago();
            $sessionid = session_id();
            $eliminado = $cf->cancelar($sessionid);
            if ($eliminado) {
                echo $eliminado;
            } else {
                echo "0No se han encontrado datos";
            }
            break;
        case 'actualizarpago':
            $p = new Pago();
            $cf = new ControladorPago();

            $tag = $_POST['tag'];
            $idpago = $_POST['idpago'];
            $foliopago = $_POST['foliopago'];
            $idcliente = $_POST['idcliente'];
            $cliente = $_POST['cliente'];
            $rfccliente = $_POST['rfccliente'];
            $razoncliente = $_POST['razoncliente'];
            $regfiscalcliente = $_POST['regfiscalcliente'];
            $codpostal = $_POST['codpostal'];
            $pago_idfiscales = $_POST['pago_idfiscales'];
            $chfirmar = $_POST['chfirmar'];
            $objimpuesto = $_POST['objimpuesto'];
            $sid = session_id();

            $search = "/[^A-Za-z0-9]/";
            $codpostal = preg_replace($search, "", $codpostal);
            $rfccliente = preg_replace($search, "", $rfccliente);

            $p->setTagpago($tag);
            $p->setIdpago($idpago);
            $p->setFoliopago($foliopago);
            $p->setIdcliente($idcliente);
            $p->setNombrecliente($cliente);
            $p->setRfccliente($rfccliente);
            $p->setRazoncliente($razoncliente);
            $p->setRegfiscalcliente($regfiscalcliente);
            $p->setCodpostal($codpostal);
            $p->setPago_idfiscales($pago_idfiscales);
            $p->setChfirmar($chfirmar);
            $p->setSessionid($sid);

            $insertado = $cf->validarActualizarPago($p, $objimpuesto);
            if ($insertado) {
                echo $insertado;
            } else {
                echo "0Error: no inserto el registro ";
            }
            break;
        case 'actualizarcomplementos':
            $cp = new ControladorPago();
            $p = new Pago();

            $tag = $_POST['tag'];
            $tagcomp = $_POST['tagcomp'];
            $orden = $_POST['orden'];
            $idformapago = $_POST['idformapago'];
            $moneda = $_POST['moneda'];
            $tcambio = $_POST['tcambio'];
            $fechapago = $_POST['fechapago'];
            $horapago = $_POST['horapago'];
            $cuenta = $_POST['cuenta'];
            $beneficiario = $_POST['beneficiario'];
            $numtransaccion = $_POST['numtransaccion'];
            $sid = session_id();

            $p->setTagpago($tag);
            $p->setTagcomp($tagcomp);
            $p->setOrden($orden);
            $p->setPagoidformapago($idformapago);
            $p->setPagoidmoneda($moneda);
            $p->setTipocambio($tcambio);
            $p->setFechapago($fechapago);
            $p->setHorapago($horapago);
            $p->setPago_idbanco($cuenta);
            $p->setIdbancoB($beneficiario);
            $p->setNooperacion($numtransaccion);
            $p->setSessionid($sid);

            $insertado = $cp->actualizarComplemento($p);
            if ($insertado) {
                echo $insertado;
            } else {
                echo "0Error";
            }
            break;
        case 'eliminarfactura':
            $ca = new ControladorFactura();
            $idfactura = $_POST['idfactura'];
            $folio = $_POST['folio'];
            $eliminado = $ca->eliminarFactura($idfactura, $folio);
            if ($eliminado) {
                echo "1Registro eliminado";
            } else {
                echo "0No se han econtrado datos";
            }
            break;
        case 'emisor':
            $ca = new ControladorPago();
            $iddatos = $_POST['iddatos'];
            $folio = $ca->getDatosEmisor($iddatos);
            if ($folio) {
                echo $folio;
            } else {
                echo "0No se han econtrado datos";
            }
            break;
        case 'fecha':
            $ca = new ControladorFactura();
            $fecha = $ca->getFecha();
            if ($fecha) {
                echo $fecha;
            } else {
                echo "0No se han econtrado datos";
            }
            break;
        case 'documento':
            $cp = new ControladorPagoPermi();
            $documento = $cp->getDocumento();
            if ($documento) {
                echo $documento;
            } else {
                echo "0No se han econtrado datos";
            }
            break;
        case 'filtrarfolio':
            $FO = $_POST['FO'];
            $cs = new ControladorFactura();
            $datos = $cs->listaServiciosHistorial($FO);
            if ($datos != "") {
                echo $datos;
            } else {
                echo "0Ah ocurrido un error";
            }
            break;
        case 'xml':
            $cf = new ControladorPago();
            $idpago = $_POST['idpago'];
            $cadena = $cf->checkCancelados($idpago);
            if ($cadena) {
                echo $cadena;
            } else {
                echo "0Error";
            }
            break;
        case 'agregarcfdi':
            $t = new TMPPago();
            $cf = new ControladorPago();
            
            $tag = $_POST['tag'];
            $idmoneda = $_POST['idmoneda'];
            $tcambio = $_POST['tcambio'];
            $type = $_POST['type'];
            $idfactura = $_POST['idfactura'];
            $folio = $_POST['folio'];
            $div = explode("-", $folio);
            if ($div[1]) {
                $folio = $div[1];
            } else {
                $folio = $div[0];
            }
            $uuidtmp = $_POST['uuid'];
            $tcamcfdi = $_POST['tcamcfdi'];
            $idmonedacdfi = $_POST['idmonedacdfi'];
            $cmetodo = $_POST['cmetodo'];
            $parcialidad = $_POST['parcialidad'];
            $montopago = $_POST['montopago'];
            $montoanterior = $_POST['montoanterior'];
            $montoinsoluto = $_POST['montorestante'];
            $totalfactura = $_POST['totalfactura'];
            $sessionid = session_id();

            $t->setTag($tag);
            $t->setIdmoneda($idmoneda);
            $t->setTcambio($tcambio);
            $t->setType($type);
            $t->setParcialidadtmp($parcialidad);
            $t->setIdfacturatmp($idfactura);
            $t->setFoliotmp($folio);
            $t->setUuid($uuidtmp);
            $t->setTcamcfdi($tcamcfdi);
            $t->setIdmonedacdfi($idmonedacdfi);
            $t->setCmetodo($cmetodo);
            $t->setMontopagotmp($montopago);
            $t->setMontoanteriortmp($montoanterior);
            $t->setMontoinsolutotmp($montoinsoluto);
            $t->setTotalfacturatmp($totalfactura);
            $t->setSessionid($sessionid);
            $insertado = $cf->agregarPago($t);
            if ($insertado) {
                echo $insertado;
            } else {
                echo "0Error: no inserto el registro ";
            }
            break;
        case 'borrarcomplemento':
            $cp = new ControladorPago();
            $tab = $_POST['tab'];
            $sid = session_id();
            
            $insertado = $cp->cancelar($sid, $tab);
            if($insertado){
                echo $insertado;
            }else{
                echo "0Error";
            }
            break;
        case 'loadtabla':
            $t = new TMPPago();
            $cf = new ControladorPago();
            
            $tag = $_POST['tag'];
            $idmoneda = $_POST['idmoneda'];
            $tcambio = $_POST['tcambio'];
            $uuid = $_POST['uuid'];
            $sid = session_id();

            $insertado = $cf->getTabla($sid, $tag, $idmoneda, $tcambio, $uuid);
            if ($insertado) {
                echo $insertado;
            } else {
                echo "0Error: no inserto el registro ";
            }
            break;
        case 'resetpagos':
            $cf = new ControladorFactura();
            $sessionid = session_id();
            $eliminado = $cf->resetPagos($sessionid);
            if ($eliminado) {
                echo "$eliminado";
            } else {
                echo "0No se han encontrado datos";
            }
            break;
        case 'eliminarpago':
            $cf = new ControladorPago();
            $idpago = $_POST['idpago'];
            $eliminado = $cf->eliminarPago($idpago);
            if ($eliminado) {
                echo $eliminado;
            } else {
                echo "0No se han encontrado datos";
            }
            break;
        case 'insertarpago':
            $p = new Pago();
            $cf = new ControladorPago();

            $folio = $_POST['folio'];
            $idcliente = $_POST['idcliente'];
            $nombrecliente = $_POST['cliente'];
            $rfccliente = $_POST['rfccliente'];
            $razoncliente = trim($_POST['razoncliente']);
            $regfiscalcliente = $_POST['regfiscalcliente'];
            $codpostal = $_POST['codpostal'];
            $idfiscales = $_POST['datosfiscales'];
            $chfirmar = $_POST['chfirma'];
            $sessionid = session_id();

            $objimpuesto = $_POST['objimpuesto'];

            $search = "/[^A-Za-z0-9]/";
            $codpostal = preg_replace($search, '', $codpostal);
            $rfccliente = preg_replace($search, '', $rfccliente);

            $p->setFoliopago($folio);
            $p->setIdcliente($idcliente);
            $p->setNombrecliente($nombrecliente);
            $p->setRfccliente($rfccliente);
            $p->setRazoncliente($razoncliente);
            $p->setRegfiscalcliente($regfiscalcliente);
            $p->setCodpostal($codpostal);
            $p->setPago_idfiscales($idfiscales);
            $p->setChfirmar($chfirmar);
            $p->setSessionid($sessionid);

            $insertado = $cf->validarPago($p, $objimpuesto);
            if ($insertado) {

                echo $insertado;
            } else {
                echo "0Error: no inserto el registro ";
            }
            break;
        case 'insertarcomplementos':
            $cp = new ControladorPago();
            $p = new Pago();

            $tag = $_POST['tag'];
            $tagcomp = $_POST['tagcomp'];
            $orden = $_POST['orden'];
            $idformapago = $_POST['idformapago'];
            $moneda = $_POST['moneda'];
            $tcambio = $_POST['tcambio'];
            $fechapago = $_POST['fechapago'];
            $horapago = $_POST['horapago'];
            $cuenta = $_POST['cuenta'];
            $beneficiario = $_POST['beneficiario'];
            $numtransaccion = $_POST['numtransaccion'];
            $sid = session_id();

            $p->setTagpago($tag);
            $p->setTagcomp($tagcomp);
            $p->setOrden($orden);
            $p->setPagoidformapago($idformapago);
            $p->setPagoidmoneda($moneda);
            $p->setTipocambio($tcambio);
            $p->setFechapago($fechapago);
            $p->setHorapago($horapago);
            $p->setPago_idbanco($cuenta);
            $p->setIdbancoB($beneficiario);
            $p->setNooperacion($numtransaccion);
            $p->setSessionid($sid);

            $insertado = $cp->insertarComplemento($p);
            if ($insertado) {
                echo $insertado;
            } else {
                echo "0Error";
            }
            break;
        case 'complementos':
            $cp = new ControladorPago();
            
            $tag = $_POST['tag'];
            $idemisor = $_POST['idemisor'];
            $uuid = $_POST['uuid'];
            $sid = session_id();
            
            $complementos = $cp->buildComplementos($tag, $idemisor, $sid, $uuid);
            if($complementos){
                echo $complementos;
            }else{
                echo '0Error';
            }
            break;
        case 'expcomplementos':
            $cp = new ControladorPago();
            
            $idformapago = $_POST['idformapago'];
            $idmoneda = $_POST['idmoneda'];
            $tcambio = $_POST['tcambio'];
            $idfactura = $_POST['idfactura'];
            $foliofactura = $_POST['foliofactura'];
            $sid = session_id();
            
            $complementos = $cp->exportarComplemento($idformapago, $idmoneda, $tcambio, $idfactura, $foliofactura, $sid);
            if($complementos){
                echo $complementos;
            }else{
                echo '0Error';
            }
            break;
        case 'modestado':
            $cf = new ControladorFactura();
            $f = new Factura();
            $idfactura = $_POST['idfactura'];
            $cadena = $cf->modEstado($idfactura);
            if ($cadena != "") {
                echo $cadena;
            } else {
                echo "";
            }
            break;
        case 'enviarrecibo':
            $cf = new ControladorPago();
            $idpago = $_POST['idpago'];
            $chcorreo1 = $_POST['chcorreo1'];
            $chcorreo2 = $_POST['chcorreo2'];
            $chcorreo3 = $_POST['chcorreo3'];
            $chcorreo4 = $_POST['chcorreo4'];
            $chcorreo5 = $_POST['chcorreo5'];
            $chcorreo6 = $_POST['chcorreo6'];

            $datos = $cf->mail($idpago, $chcorreo1, $chcorreo2, $chcorreo3, $chcorreo4, $chcorreo5, $chcorreo6);
            if ($datos != "") {
                echo $datos;
            } else {
                echo "0No Hay Facturas activas";
            }
            break;
        case 'getcorreos':
            $cf = new ControladorPago();
            $idfactura = $_POST['idfactura'];
            $datos = $cf->getCorreo($idfactura);
            if ($datos != "") {
                echo $datos;
            } else {
                echo "0No se encontraron correos registrados";
            }
            break;
        case 'statuscfdi':
            $cf = new ControladorPago();
            $idfactura = $_POST['fid'];
            $datos = $cf->checkStatusCFDI($idfactura);
            if ($datos != "") {
                echo $datos;
            } else {
                echo "0Boton Activo";
            }
            break;
        case 'editarestado':
            $cf = new ControladorPago();
            $idpago = $_POST['idpago'];
            $datos = $cf->estadoPago($idpago);
            if ($datos != "") {
                echo $datos;
            } else {
                echo "0No hay clientes registrados";
            }
            break;
        default:
            break;
    }
}
