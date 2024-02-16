<?php

require_once '../com.sine.dao/Consultas.php';
require_once '../com.sine.modelo/TMPPago.php';
require_once '../com.sine.modelo/SendMail.php';
require_once '../com.sine.modelo/Session.php';
require_once '../vendor/autoload.php';

use SWServices\Toolkit\SignService as Sellar;
use SWServices\Stamp\StampService as StampService;
use SWServices\Cancelation\CancelationService as CancelationService;
use SWServices\SatQuery\SatQueryService as consultaCfdiSAT;

date_default_timezone_set("America/Mexico_City");

class ControladorPago {

    function __construct() {
        
    }

    private function genTag() {
        $fecha = getdate();
        $d = $fecha['mday'];
        $m = $fecha['mon'];
        $y = $fecha['year'];
        $h = $fecha['hours'];
        $mi = $fecha['minutes'];
        $sec = $fecha['seconds'];
        if ($d < 10) {
            $d = "0$d";
        }
        if ($m < 10) {
            $m = "0$m";
        }
        if ($h < 10) {
            $h = "0$h";
        }
        if ($mi < 10) {
            $mi = "0$mi";
        }
        if ($sec < 10) {
            $sec = "0$sec";
        }

        $idusu = $_SESSION[sha1("idusuario")];
        $sid = session_id();

        $dtag = $m . $y . $d . $h . $mi . $sec;
        $ranstr = "";
        $chars = "0123456789abcdefghijklmnopqrstuvwxyz";
        $charsize = strlen($chars);
        for ($r = 0; $r < 5; $r++) {
            $ranstr .= $chars[rand(0, $charsize - 1)];
        }
        $tag = $ranstr . $dtag . $idusu . $sid;
        return $tag;
    }

    public function nuevoComplemento($comp) {
        $tag = $this->genTag();
        $datos = "<button id='tab-$tag' class='tab-pago sub-tab-active' data-tab='$tag' data-ord='$comp' name='tab-complemento' >Complemento $comp &nbsp; <span data-tab='$tag' type='button' class='close-button' aria-label='Close'><span aria-hidden='true'>&times;</span></span></button>
                <cut>
                <div id='complemento-$tag' class='sub-div'>
                <div class='row'>
            <div class='col-md-4'>
                <label class='label-form text-right' for='forma-$tag'>Forma de Pago</label> <label class='mark-required text-right'>*</label>
                <div class='form-group'>
                        <select class='form-control text-center input-form' id='forma-$tag' name='forma-$tag' onchange='disableCuenta();'>
                            <option value='' id='default-fpago-$tag'>- - - -</option>
                            <optgroup id='forma-pago-$tag' class='cont-fpago-$tag text-left'> </optgroup>
                        </select>
                    <div id='forma-$tag-errors'></div>
                </div>
            </div>

            <div class='col-md-2'>
                <label class='label-form text-right' for='moneda-$tag'>Moneda de Pago</label> <label class='mark-required text-right'>*</label>
                <div class='form-group'>
                    <select class='form-control text-center input-form' id='moneda-$tag' name='moneda-$tag' onchange='getTipoCambio(); loadTablaCFDI();'>
                        <option value='' id='default-moneda-$tag'>- - - -</option>
                        <optgroup id='mpago-$tag' class='contmoneda-$tag text-left'> </optgroup>
                    </select>
                    <div id='moneda-$tag-errors'></div>
                </div>
            </div>

            <div class='col-md-2'>
                <label class='label-form text-right' for='cambio-$tag'>Tipo de Cambio</label>
                <div class='form-group'>
                    <input type='text' class='form-control input-form' id='cambio-$tag' placeholder='Tipo de cambio de Moneda' disabled=''>
                    <div id='cambio-$tag-errors'></div>
                </div>
            </div>

            <div class='col-md-4'>
                <label class='label-form text-right' for='fecha-$tag'>Fecha de Pago</label> <label class='mark-required text-right'>*</label>
                <div class='form-group'>
                    <input class='form-control text-center input-form' id='fecha-$tag' name='fecha-$tag' type='date'/>
                    <div id='fecha-$tag-errors'></div>
                </div>
            </div>
        </div>

        <div class='row'>
            <div class='col-md-4'>
                <label class='label-form text-right' for='hora-$tag'>Hora de Pago</label> <label class='mark-required text-right'>*</label>
                <div class='form-group'>
                    <input class='form-control text-center input-form' id='hora-$tag' name='hora-$tag' type='time' />
                    <div id='hora-$tag-errors'></div>
                </div>
            </div>

            <div class='col-md-4'>
                <label class='label-form text-right' for='uenta-$tag'>Cuenta Ordenante (Cliente)</label>
                <div class='form-group'>
                    <select class='form-control text-center input-form' id='cuenta-$tag' name='cuenta-$tag' disabled>
                        <option value='' id='default-cuenta-$tag'>- - - -</option>
                        <optgroup id='ordenante-$tag' class='contenedor-cuenta-$tag text-left'> </optgroup>
                    </select>
                    <div id='cuenta-$tag-errors'></div>
                </div>
            </div>

            <div class='col-md-4'>
                <label class='label-form text-right' for='benef-$tag'>Cuenta Beneficiario (Mis Cuentas)</label>
                <div class='form-group'>
                    <select class='form-control text-center input-form' id='benef-$tag' name='benef-$tag' disabled>
                        <option value='' id='default-benef-$tag'>- - - -</option>
                        <optgroup id='beneficiario-$tag' class='contenedor-beneficiario-$tag text-left'> </optgroup>
                    </select>
                    <div id='benef-$tag-errors'></div>
                </div>
            </div>
        </div>

        <div class='row'>
            <div class='col-md-4'>
                <label class='label-form text-right' for='transaccion-$tag'>N° de Transaccion</label>
                <div class='form-group'>
                    <input class='form-control text-center input-form' id='transaccion-$tag' name='transaccion-$tag' placeholder='N° de Transaccion' type='number' disabled/>
                    <div id='transaccion-$tag-errors'>
                    </div>
                </div>
            </div>
        </div>

        <div class='row'>
            <div class='col-md-12'>
                <div class='new-tooltip icon tip'> 
                    <label class='label-sub' for='fecha-creacion'>CFDIS RELACIONADOS </label> <span class='glyphicon glyphicon-question-sign'></span>
                    <span class='tiptext'>Para agregar una factura realice la b&uacute;squeda por Folio de la factura y se cargaran los datos, la b&uacute;squeda se limita a las facturas asignadas al cliente seleccionado en el campo Cliente.</span>
                </div>
            </div>
        </div>

        <div class='row scrollX'>
            <div class='col-md-12'>
                <table class='table tab-hover table-condensed table-responsive table-row thead-form'>
                    <tbody >
                        <tr>
                            <td colspan='2'>
                                <label class='label-form text-right' for='factura-$tag'>Folio Factura</label>
                                <input id='id-factura-$tag' type='hidden' /><input class='form-control text-center input-form' id='factura-$tag' name='factura-$tag' placeholder='Factura' type='text' oninput='aucompletarFactura();'/>
                            </td>
                            <td colspan='2'>
                                <label class='label-form text-right' for='uuid-$tag'>UUID Factura</label>
                                <input class='form-control cfdi text-center input-form' id='uuid-$tag' name='uuid-$tag' placeholder='UUID del cfdi' type='text'/>
                            </td>
                            <td>
                                <label class='label-form text-right' for='type-$tag'>Tipo Factura</label>
                                <select class='form-control text-center input-form' id='type-$tag' name='type-$tag'>
                                    <option value='' id='default-tipo-$tag'>- - - -</option>
                                    <option value='f' id='tipo-f-$tag'>Factura</option>
                                    <option value='c' id='tipo-c-$tag'>Carta Porte</option>
                                </select>
                            </td>
                            <td>
                                <label class='label-form text-right' for='monedarel-$tag'>Moneda Factura</label>
                                <input id='cambiocfdi-$tag' type='hidden' />
                                <input id='metcfdi-$tag' type='hidden' />
                                <select class='form-control text-center input-form' id='monedarel-$tag' name='monedarel-$tag'>
                                    <option value='' id='default-moneda-$tag'>- - - -</option>
                                    <optgroup id='moncfdi-$tag' class='contenedor-moneda-$tag text-left'> </optgroup>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class='label-form text-right' for='parcialidad-$tag'>N° Parcialidad</label>
                                <input class='form-control text-center input-form' id='parcialidad-$tag' name='parcialidad-$tag' placeholder='No Parcialidad' type='text'/>
                            </td>
                            <td>
                                <label class='label-form text-right' for='total-$tag'>Total Factura</label>
                                <input class='form-control text-center input-form' id='total-$tag' name='total-$tag' placeholder='Total de Factura' type='number' step='any'/>
                            </td>
                            <td>
                                <label class='label-form text-right' for='anterior-$tag'>Monto Anterior</label>
                                <input class='form-control text-center input-form' id='anterior-$tag' name='anterior-$tag' placeholder='Monto Anterior' type='number' step='any'/>
                            </td>
                            <td>
                                <label class='label-form text-right' for='monto-$tag'>Monto a Pagar</label>
                                <input class='form-control text-center input-form' id='monto-$tag' name='monto-$tag' placeholder='Monto Pagado' type='number' step='any' oninput='calcularRestante()'/>
                            </td>
                            <td>
                                <label class='label-form text-right' for='restante-$tag'>Monto Restante</label>
                                <input class='form-control text-center input-form' id='restante-$tag' name='cantidad' placeholder='Monto Restante' type='number' step='any'/>
                            </td>
                            <td>
                                <label class='label-space' for='btn-agregar-cfdi'></label>
                                <button id='btn-agregar-cfdi' class='button-modal' onclick='agregarCFDI();'><span class='glyphicon glyphicon-plus'></span> Agregar</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class='table tab-hover table-condensed table-responsive table-row table-head' id='lista-cfdi-$tag'>

                </table>
            </div>
        </div>
        </div><cut>$tag";
        return $datos;
    }

    private function getSWAccessAux() {
        $consultado = false;
        $consultas = new Consultas();
        $consulta = "SELECT * FROM swaccess WHERE idswaccess=:id;";
        $valores = array("id" => '1');
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    private function getSWAccess() {
        $datos = "";
        $get = $this->getSWAccessAux();
        foreach ($get as $actual) {
            $token = $actual['accesstoken'];
            $url = $actual['sw_url'];
        }
        $datos = "$url</tr>$token";
        return $datos;
    }

    private function getEstadoAux($idestado) {
        $estado = "";
        $est = $this->getEstadoById($idestado);
        foreach ($est as $actual) {
            $estado = $actual['estado'];
        }
        return $estado;
    }

    private function getEstadoById($idestado) {
        $consultado = false;
        $consultas = new Consultas();
        $consulta = "SELECT * FROM estado WHERE id_estado=:id;";
        $valores = array("id" => $idestado);
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    private function getMunicipioAux($idmun) {
        $municipio = "";
        $mun = $this->getMunicipioById($idmun);
        foreach ($mun as $actual) {
            $municipio = $actual['municipio'];
        }
        return $municipio;
    }

    private function getMunicipioById($idmun) {
        $consultado = false;
        $consultas = new Consultas();
        $consulta = "SELECT * FROM municipio WHERE id_municipio=:id;";
        $valores = array("id" => $idmun);
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    public function getCorreo($idfactura) {
        $datos = "";
        $correos = $this->getCorreosAux($idfactura);
        foreach ($correos as $actual) {
            $correo1 = $actual['email_informacion'];
            $correo2 = $actual['email_facturacion'];
            $correo3 = $actual['email_gerencia'];
            $correo4 = $actual['correoalt1'];
            $correo5 = $actual['correoalt2'];
            $correo6 = $actual['correoalt3'];
            $datos .= "$correo1<corte>$correo2<corte>$correo3<corte>$correo4<corte>$correo5<corte>$correo6";
        }
        return $datos;
    }

    public function getCorreosAux($idfactura) {
        $consultado = false;
        $consultas = new Consultas();
        $consulta = "SELECT c.email_informacion, c.email_facturacion, c.email_gerencia, c.correoalt1, c.correoalt2, c.correoalt3 FROM pagos p INNER JOIN cliente c ON (p.pago_idcliente=c.id_cliente) WHERE p.idpago=:id;";
        $valores = array("id" => $idfactura);
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    private function getDolarEuro() {
        $val = "";
        $xml = simplexml_load_file("http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml");
        foreach ($xml->Cube->Cube->Cube as $c) {
            $attr = $c->attributes();
            if ($attr[0] == "USD") {
                $val = $attr[1];
                break;
            }
        }
        return $val;
    }

    public function updateTipoCambio() {
        $consulta = json_decode(file_get_contents('https://www.banxico.org.mx/SieAPIRest/service/v1/series/SF43718,SF46410/datos/oportuno?token=95d80f26330b774d2b66fd83c8c6039bf11a7cea167d6f1848f6a2135d2c1433'), true);
        $bmx = $consulta['bmx'];
        $serie = $bmx['series'];
        foreach ($serie as $sactual) {
            $idserie = $sactual['idSerie'];
            $datos = $sactual['datos'];
            foreach ($datos as $dactual) {
                $cambio = $dactual['dato'];
            }
            $euro = $this->getDolarEuro();
            $update = $this->saveTipoCambio($idserie, $cambio, $euro);
            $peso = $this->saveCambioPeso($cambio, $idserie);
        }
        return $idserie;
    }

    private function saveTipoCambio($serie, $cambio, $cEuro) {
        switch ($serie) {
            case "SF43718":
                $doleuro = 1 / $cEuro;
                $consulta = "UPDATE `catalogo_moneda` SET tipo_cambio=:tcambio, cambioEuro=:ceuro WHERE seriebanxico=:id;";
                $valores = array("id" => $serie,
                    "tcambio" => $cambio,
                    "ceuro" => bcdiv($doleuro, '1', 6));
                break;
            case "SF46410":
                $consulta = "UPDATE `catalogo_moneda` SET tipo_cambio=:tcambio, cambioDolar=:cdolar WHERE seriebanxico=:id;";
                $valores = array("id" => $serie,
                    "tcambio" => $cambio,
                    "cdolar" => bcdiv($cEuro, '1', 6));
                break;
            default:
                $field = "";
                break;
        }
        $con = new Consultas();
        $insertado = $con->execute($consulta, $valores);
    }

    private function saveCambioPeso($cambio, $serie) {
        switch ($serie) {
            case "SF43718":
                $field = "cambioDolar";
                break;
            case "SF46410":
                $field = "cambioEuro";
                break;
            default:
                $field = "";
                break;
        }
        $valor = 1 / $cambio;
        $con = new Consultas();
        $consulta = "UPDATE `catalogo_moneda` SET $field=:tcambio WHERE idcatalogo_moneda=:id;";
        $valores = array("id" => '1',
            "tcambio" => bcdiv($valor, '1', 6));
        $insertado = $con->execute($consulta, $valores);
    }

    private function getTipoCambioAux($idmoneda) {
        $consultado = false;
        $consultas = new Consultas();
        $consulta = "SELECT * FROM catalogo_moneda WHERE idcatalogo_moneda=:id;";
        $val = array("id" => $idmoneda);
        $consultado = $consultas->getResults($consulta, $val);
        return $consultado;
    }

    public function getTipoCambio($idmoneda, $idmonedaF = '0', $tcambioF = '0', $tcambioP = '0') {
        $tcambio = null; // Inicializa la variable $tcambio
        $moneda = $this->getTipoCambioAux($idmoneda);
        foreach ($moneda as $actual) {
            $tcambio = $actual['tipo_cambio'];
        }
        if ($idmoneda == '1') {
            if ($idmonedaF == '1') {
                if ($tcambioF != "0") {
                    $tcambio = $tcambioF;
                } else {
                    foreach ($moneda as $actual) {
                        $tcambio = $actual['tipo_cambio'];
                    }
                }
            } else if ($idmonedaF == '2') {
                if ($tcambioF != "0") {
                    $tcambio = bcdiv(1, $tcambioF, 6);
                } else {
                    foreach ($moneda as $actual) {
                        $tcambio = $actual['cambioDolar'];
                    }
                }
            } else if ($idmonedaF == '4') {
                if ($tcambioF != "0") {
                    $tcambio = bcdiv(1, $tcambioF, 6);
                } else {
                    foreach ($moneda as $actual) {
                        $tcambio = $actual['cambioEuro'];
                    }
                }
            }
        } else if ($idmoneda == '2') {
            if ($idmonedaF == '1') {
                if ($tcambioP != '0') {
                    $tcambio = $tcambioP;
                } else {
                    foreach ($moneda as $actual) {
                        $tcambio = $actual['tipo_cambio'];
                    }
                }
            } else if ($idmonedaF == '2') {
                foreach ($moneda as $actual) {
                    $tcambio = $actual['cambioDolar'];
                }
            } else if ($idmonedaF == '4') {
                foreach ($moneda as $actual) {
                    $tcambio = $actual['cambioEuro'];
                }
            }
        } else if ($idmoneda == '4') {
            if ($idmonedaF == '1') {
                foreach ($moneda as $actual) {
                    $tcambio = $actual['tipo_cambio'];
                }
            } else if ($idmonedaF == '2') {
                foreach ($moneda as $actual) {
                    $tcambio = $actual['cambioDolar'];
                }
            } else if ($idmonedaF == '4') {
                foreach ($moneda as $actual) {
                    $tcambio = $actual['cambioEuro'];
                }
            }
        }
        return $tcambio; //linea 451
    }

    private function getClientebyID($idcliente) {
        $consultado = false;
        $con = new Consultas();
        $consulta = "SELECT * FROM cliente WHERE id_cliente=:cid;";
        $val = array("cid" => $idcliente);
        $consultado = $con->getResults($consulta, $val);
        return $consultado;
    }

    private function getNombreCliente($idcliente) {
        $nombre = "";
        $datos = $this->getClientebyID($idcliente);
        foreach ($datos as $actual) {
            $nombre = $actual['nombre'] . " " . $actual['apaterno'] . "-" . $actual['nombre_empresa'];
        }
        return $nombre;
    }

    public function getPagoById($idpago) {
        $consultado = false;
        $consultas = new Consultas();
        $consulta = "SELECT p.*, df.nombre_contribuyente, df.firma, df.rfc, df.razon_social, df.codigo_postal, df.c_regimenfiscal, df.regimen_fiscal FROM pagos p INNER JOIN datos_facturacion df ON (df.id_datos=p.pago_idfiscales) WHERE idpago=:idpago;";
        $val = array("idpago" => $idpago);
        $consultado = $consultas->getResults($consulta, $val);
        return $consultado;
    }

    public function getDatosPago($idpago) {
        $datos = "";
        $pago = $this->getPagoById($idpago);
        foreach ($pago as $pagoactual) {
            $idpago = $pagoactual['idpago'];
            $serie = $pagoactual['serie'];
            $letra = $pagoactual['letra'];
            $foliopago = $pagoactual['foliopago'];
            $fechacreacion = $pagoactual['fechacreacion'];
            $pago_idfiscales = $pagoactual['pago_idfiscales'];
            $nombrefiscales = $pagoactual['nombre_contribuyente'];
            $rfcemisor = $pagoactual['rfcemisor'];
            $razonemisor = $pagoactual['razonemisor'];
            $clvregemisor = $pagoactual['clvregemisor'];
            $regfiscalemisor = $pagoactual['regfiscalemisor'];
            $codpemisor = $pagoactual['codpemisor'];
            $pago_idcliente = $pagoactual['pago_idcliente'];
            $nombrecliente = $pagoactual['nombrecliente'];
            $rfcreceptor = $pagoactual['rfcreceptor'];
            $razonreceptor = $pagoactual['razonreceptor'];
            $regfiscalreceptor = $pagoactual['regfiscalreceptor'];
            $codpreceptor = $pagoactual['codpreceptor'];
            $totalpagado = $pagoactual['totalpagado'];
            $chfirmar = $pagoactual['chfirmar'];
            $uuidpago = $pagoactual['uuidpago'];
            $tag = $pagoactual['tagpago'];
            $objimpuesto = $pagoactual['objimpuesto'];

            $datos = "$idpago</tr>$serie</tr>$letra</tr>$foliopago</tr>$fechacreacion</tr>$pago_idfiscales</tr>$nombrefiscales</tr>$rfcemisor</tr>$razonemisor</tr>$clvregemisor</tr>$regfiscalemisor</tr>$codpemisor</tr>$pago_idcliente</tr>$nombrecliente</tr>$rfcreceptor</tr>$razonreceptor</tr>$regfiscalreceptor</tr>$codpreceptor</tr>$totalpagado</tr>$chfirmar</tr>$uuidpago</tr>$tag</tr>$objimpuesto";

            break;
        }
        return $datos;
    }

    private function getComplementosAux($tag) {
        $consultado = false;
        $con = new Consultas();
        $consulta = "SELECT * FROM complemento_pago WHERE tagpago=:tag ORDER BY ordcomplemento ASC;";
        $val = array("tag" => $tag);
        $consultado = $con->getResults($consulta, $val);
        return $consultado;
    }

    public function buildComplementos($tag, $idemisor, $sid, $uuid) {
        $datos = "";
        $complementos = $this->getComplementosAux($tag);
        foreach ($complementos as $actual) {
            $orden = $actual['ordcomplemento'];
            $fpid = $actual['complemento_idformapago'];
            $mid = $actual['complemento_idmoneda'];
            $tcambio = $actual['complemento_tcambio'];
            $fechapago = $actual['complemento_fechapago'];
            $horapago = $actual['complemento_horapago'];
            $idcuentaord = $actual['complemento_idcuentaOrd'];
            $idcuentabnf = $actual['complemento_idcuentaBnf'];
            $numtransaccion = $actual['complemento_notransaccion'];
            $total = $actual['total_complemento'];
            $tagcomp = $actual['tagcomplemento'];
            $tagpago = $actual['tagpago'];
            $disabled = "";
            $close = "<span data-tab='$tagcomp' type='button' class='close-button' aria-label='Close'><span aria-hidden='true'>&times;</span></span>";
            if ($uuid != "") {
                $disabled = "disabled";
                $close = "";
            }

            $this->cfdisPago($tagpago, $tagcomp, $sid);

            $datos .= "<button id='tab-$tagcomp' class='tab-pago sub-tab-active' data-tab='$tagcomp' data-ord='$orden' name='tab-complemento' >Complemento $orden &nbsp; $close</button>
                <cut>
                <div id='complemento-$tagcomp' class='sub-div'>
                <div class='row'>
            <div class='col-md-4'>
                <label class='label-form text-right' for='forma-$tagcomp'>Forma de Pago</label> <label class='mark-required text-right'>*</label>
                <div class='form-group'>
                        <select class='form-control text-center input-form' id='forma-$tagcomp' name='forma-$tagcomp' onchange='disableCuenta();' $disabled>
                            <option value='' id='default-fpago-$tagcomp'>- - - -</option>
                            <optgroup id='forma-pago-$tagcomp' class='cont-fpago-$tagcomp text-left'>" .
                    $this->opcionesFormaPago($fpid)
                    . "</optgroup>
                        </select>
                    <div id='forma-$tag-errors'></div>
                </div>
            </div>

            <div class='col-md-2'>
                <label class='label-form text-right' for='moneda-$tagcomp'>Moneda de Pago</label> <label class='mark-required text-right'>*</label>
                <div class='form-group'>
                    <select class='form-control text-center input-form' id='moneda-$tagcomp' name='moneda-$tagcomp' onchange='getTipoCambio(); loadTablaCFDI();' $disabled>
                        <option value='' id='default-moneda-$tagcomp'>- - - -</option>
                        <optgroup id='mpago-$tagcomp' class='contmoneda-$tagcomp text-left'>" .
                    $this->opcionesMoneda($mid)
                    . "</optgroup>
                    </select>
                    <div id='moneda-$tag-errors'></div>
                </div>
            </div>

            <div class='col-md-2'>
                <label class='label-form text-right' for='cambio-$tagcomp'>Tipo de Cambio</label>
                <div class='form-group'>
                    <input type='text' class='form-control input-form' id='cambio-$tagcomp' placeholder='Tipo de cambio de Moneda' disabled='' value='$tcambio'>
                    <div id='cambio-$tagcomp-errors'></div>
                </div>
            </div>

            <div class='col-md-4'>
                <label class='label-form text-right' for='fecha-$tagcomp'>Fecha de Pago</label> <label class='mark-required text-right'>*</label>
                <div class='form-group'>
                    <input class='form-control text-center input-form' id='fecha-$tagcomp' name='fecha-$tagcomp' type='date' value='$fechapago' $disabled/>
                    <div id='fecha-$tagcomp-errors'></div>
                </div>
            </div>
        </div>

        <div class='row'>
            <div class='col-md-4'>
                <label class='label-form text-right' for='hora-$tagcomp'>Hora de Pago</label> <label class='mark-required text-right'>*</label>
                <div class='form-group'>
                    <input class='form-control text-center input-form' id='hora-$tagcomp' name='hora-$tagcomp' type='time' value='$horapago' $disabled/>
                    <div id='hora-$tagcomp-errors'></div>
                </div>
            </div>

            <div class='col-md-4'>
                <label class='label-form text-right' for='uenta-$tagcomp'>Cuenta Ordenante (Cliente)</label>
                <div class='form-group'>
                    <select class='form-control text-center input-form' id='cuenta-$tagcomp' name='cuenta-$tagcomp' disabled>
                        <option value='' id='default-cuenta-$tagcomp'>- - - -</option>
                        <optgroup id='ordenante-$tagcomp' class='contenedor-cuenta-$tagcomp text-left'></optgroup>
                    </select>
                    <div id='cuenta-$tagcomp-errors'></div>
                </div>
            </div>

            <div class='col-md-4'>
                <label class='label-form text-right' for='benef-$tagcomp'>Cuenta Beneficiario (Mis Cuentas)</label>
                <div class='form-group'>
                    <select class='form-control text-center input-form' id='benef-$tagcomp' name='benef-$tagcomp' disabled>
                        <option value='' id='default-benef-$tagcomp'>- - - -</option>
                        <optgroup id='beneficiario-$tagcomp' class='contenedor-beneficiario-$tagcomp text-left'>" .
                    $this->opcionesBeneficiario($idemisor, $idcuentabnf)
                    . "</optgroup>
                    </select>
                    <div id='benef-$tagcomp-errors'></div>
                </div>
            </div>
        </div>

        <div class='row'>
            <div class='col-md-4'>
                <label class='label-form text-right' for='transaccion-$tagcomp'>N° de Transaccion</label>
                <div class='form-group'>
                    <input class='form-control text-center input-form' id='transaccion-$tagcomp' name='transaccion-$tagcomp' placeholder='N° de Transaccion' type='number' disabled value='$numtransaccion'/>
                    <div id='transaccion-$tagcomp-errors'>
                    </div>
                </div>
            </div>
        </div>

        <div class='row'>
            <div class='col-md-12'>
                <div class='new-tooltip icon tip'> 
                    <label class='label-sub' for='fecha-creacion'>CFDIS RELACIONADOS </label> <span class='glyphicon glyphicon-question-sign'></span>
                    <span class='tiptext'>Para agregar una factura realice la b&uacute;squeda por Folio de la factura y se cargaran los datos, la b&uacute;squeda se limita a las facturas asignadas al cliente seleccionado en el campo Cliente.</span>
                </div>
            </div>
        </div>

        <div class='row scrollX'>
            <div class='col-md-12'>
                <table class='table tab-hover table-condensed table-responsive table-row thead-form'>
                    <tbody >
                        <tr>
                            <td colspan='2'>
                                <label class='label-form text-right' for='factura-$tagcomp'>Folio Factura</label>
                                <input id='id-factura-$tagcomp' type='hidden' /><input class='form-control text-center input-form' id='factura-$tagcomp' name='factura-$tagcomp' placeholder='Factura' type='text' oninput='aucompletarFactura();'/>
                            </td>
                            <td colspan='2'>
                                <label class='label-form text-right' for='uuid-$tagcomp'>UUID Factura</label>
                                <input class='form-control cfdi text-center input-form' id='uuid-$tagcomp' name='uuid-$tagcomp' placeholder='UUID del cfdi' type='text'/>
                            </td>
                            <td>
                                <label class='label-form text-right' for='type-$tagcomp'>Tipo Factura</label>
                                <select class='form-control text-center input-form' id='type-$tagcomp' name='type-$tagcomp'>
                                    <option value='' id='default-tipo-$tagcomp'>- - - -</option>
                                    <option value='f' id='tipo-f-$tagcomp'>Factura</option>
                                    <option value='c' id='tipo-c-$tagcomp'>Carta Porte</option>
                                </select>
                            </td>
                            <td>
                                <label class='label-form text-right' for='monedarel-$tagcomp'>Moneda Factura</label>
                                <input id='cambiocfdi-$tagcomp' type='hidden' />
                                <input id='metcfdi-$tagcomp' type='hidden' />
                                <select class='form-control text-center input-form' id='monedarel-$tagcomp' name='monedarel-$tagcomp'>
                                    <option value='' id='default-moneda-$tagcomp'>- - - -</option>
                                    <optgroup id='moncfdi-$tagcomp' class='contenedor-moneda-$tagcomp text-left'> </optgroup>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class='label-form text-right' for='parcialidad-$tagcomp'>N° Parcialidad</label>
                                <input class='form-control text-center input-form' id='parcialidad-$tagcomp' name='parcialidad-$tagcomp' placeholder='No Parcialidad' type='text'/>
                            </td>
                            <td>
                                <label class='label-form text-right' for='total-$tagcomp'>Total Factura</label>
                                <input class='form-control text-center input-form' id='total-$tagcomp' name='total-$tagcomp' placeholder='Total de Factura' type='number' step='any'/>
                            </td>
                            <td>
                                <label class='label-form text-right' for='anterior-$tagcomp'>Monto Anterior</label>
                                <input class='form-control text-center input-form' id='anterior-$tagcomp' name='anterior-$tagcomp' placeholder='Monto Anterior' type='number' step='any'/>
                            </td>
                            <td>
                                <label class='label-form text-right' for='monto-$tagcomp'>Monto a Pagar</label>
                                <input class='form-control text-center input-form' id='monto-$tagcomp' name='monto-$tagcomp' placeholder='Monto Pagado' type='number' step='any' oninput='calcularRestante()'/>
                            </td>
                            <td>
                                <label class='label-form text-right' for='restante-$tagcomp'>Monto Restante</label>
                                <input class='form-control text-center input-form' id='restante-$tagcomp' name='cantidad' placeholder='Monto Restante' type='number' step='any'/>
                            </td>
                            <td>
                                <label class='label-space' for='btn-agregar-cfdi'></label>
                                <button id='btn-agregar-cfdi' class='button-modal' onclick='agregarCFDI();' $disabled><span class='glyphicon glyphicon-plus'></span> Agregar</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class='table tab-hover table-condensed table-responsive table-row table-head' id='lista-cfdi-$tagcomp'>

                </table>
            </div>
        </div>
        </div><cut>$tagcomp<cut>$orden<comp>";
        }
        return $datos;
    }

    public function cfdisPago($tagpago, $tagcomp, $sid) {
        $datos = "";
        $cfdi = $this->getDetallePago($tagpago, $tagcomp);
        foreach ($cfdi as $cfdiactual) {
            $parcialidad = $cfdiactual['noparcialidad'];
            $idfactura = $cfdiactual['pago_idfactura'];
            $foliodoc = $cfdiactual['foliodoc'];
            $uuiddoc = $cfdiactual['uuiddoc'];
            $tcambiodoc = $cfdiactual['tcambiodoc'];
            $idmonedadoc = $cfdiactual['idmonedadoc'];
            $cmetododoc = $cfdiactual['cmetododoc'];
            $monto = $cfdiactual['monto'];
            $montoanterior = $cfdiactual['montoanterior'];
            $montoinsoluto = $cfdiactual['montoinsoluto'];
            $totalfactura = $cfdiactual['totalfactura'];
            $tagcmp = $cfdiactual['detalle_tagcomplemento'];
            $type = $cfdiactual['type'];

            $con = new Consultas();
            $consulta = "INSERT INTO `tmppago` VALUES (:id, :parcialidad, :idfactura, :folio, :uuid, :tcambio, :idmoneda, :cmetodo,  :monto, :montoanterior, :montoinsoluto, :totalfactura, :type, :tmptag, :sid);";
            $valores = array("id" => null,
                "parcialidad" => $parcialidad,
                "idfactura" => $idfactura,
                "folio" => $foliodoc,
                "uuid" => $uuiddoc,
                "tcambio" => $tcambiodoc,
                "idmoneda" => $idmonedadoc,
                "cmetodo" => $cmetododoc,
                "monto" => $monto,
                "montoanterior" => $montoanterior,
                "montoinsoluto" => $montoinsoluto,
                "totalfactura" => $totalfactura,
                "type" => $type,
                "tmptag" => $tagcmp,
                "sid" => $sid);
            $datos = $con->execute($consulta, $valores);
        }
        return $datos;
    }

    private function getFormaPago($condicion) {
        $consultado = false;
        $consultas = new Consultas();
        $consulta = "SELECT * FROM catalogo_pago $condicion ORDER BY c_pago;";
        $consultado = $consultas->getResults($consulta, null);
        return $consultado;
    }

    private function opcionesFormaPago($selected = "") {
        $pago = $this->getFormaPago("WHERE c_pago !='99'");
        $r = "";
        foreach ($pago as $pagoactual) {
            $opt = "";
            if ($selected == $pagoactual['idcatalogo_pago']) {
                $opt = "selected";
            }
            $r = $r . "<option $opt id=formapago" . $pagoactual['idcatalogo_pago'] . " value='" . $pagoactual['idcatalogo_pago'] . "'>" . $pagoactual['c_pago'] . ' ' . $pagoactual['descripcion_pago'] . "</option>";
        }
        return $r;
    }

    private function getMoneda() {
        $consultado = false;
        $consultas = new Consultas();
        $consulta = "SELECT * FROM catalogo_moneda ORDER BY idcatalogo_moneda;";
        $consultado = $consultas->getResults($consulta, null);
        return $consultado;
    }

    private function opcionesMoneda($idmoneda = "") {
        $r = "";
        $moneda = $this->getMoneda();
        foreach ($moneda as $monedaactual) {
            $selected = "";
            if ($idmoneda == $monedaactual['idcatalogo_moneda']) {
                $selected = "selected";
            }
            $r .= "<option $selected id=moneda" . $monedaactual['idcatalogo_moneda'] . " value='" . $monedaactual['idcatalogo_moneda'] . "'>" . $monedaactual['c_moneda'] . ' ' . $monedaactual['descripcion_moneda'] . "</option>";
        }
        return $r;
    }

    private function opcionesBeneficiario($iddatos, $selected) {
        $r = "";
        $idbanco = '0';
        $idbanco1 = '0';
        $idbanco2 = '0';
        $idbanco3 = '0';
        $datos = $this->getDatosFacturacion($iddatos);
        foreach ($datos as $actual) {
            $idbanco = $actual['idbanco'];
            $cuenta = $actual['cuenta'];
            $idbanco1 = $actual['idbanco1'];
            $cuenta1 = $actual['cuenta1'];
            $idbanco2 = $actual['idbanco2'];
            $cuenta2 = $actual['cuenta2'];
            $idbanco3 = $actual['idbanco3'];
            $cuenta3 = $actual['cuenta3'];
        }
        $selected = "";
        if ($idbanco != '0') {
            if ($selected == '1') {
                $selected = "selected";
            }
            $banco = $this->getNomBanco($idbanco);
            $r .= "<option value='1' $selected>" . $banco . " - Cuenta:" . $cuenta . "</option>";
        }
        if ($idbanco1 != '0') {
            if ($selected == '12') {
                $selected = "selected";
            }
            $banco1 = $this->getNomBanco($idbanco1);
            $r .= "<option value='2' $selected>" . $banco1 . " - Cuenta:" . $cuenta1 . "</option>";
        }
        if ($idbanco2 != '0') {
            if ($selected == '3') {
                $selected = "selected";
            }
            $banco2 = $this->getNomBanco($idbanco2);
            $r .= "<option value='3' $selected>" . $banco2 . " - Cuenta:" . $cuenta2 . "</option>";
        }
        if ($idbanco3 != '0') {
            if ($selected == '4') {
                $selected = "selected";
            }
            $banco3 = $this->getNomBanco($idbanco3);
            $r .= "<option value='4' $selected>" . $banco3 . " - Cuenta:" . $cuenta3 . "</option>";
        }

        return $r;
    }

    public function getXMLInfo($id) {
        $consultado = false;
        $consultas = new Consultas();
        $consulta = "SELECT * FROM pagos p WHERE p.idpago=:id;";
        $val = array("id" => $id);
        $consultado = $consultas->getResults($consulta, $val);
        return $consultado;
    }

    public function checkDetallePagosaux($id) {
        $consultado = false;
        $consultas = new Consultas();
        $consulta = "SELECT * FROM detallepago WHERE pagoidfactura=:id;";
        $valores = array("id" => $id);
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    public function checkDetallePagos($id) {
        $pagos = $this->checkDetallePagosaux($id);
        $idpago = "";
        foreach ($pagos as $actual) {
            $idpago = $actual['iddetallepago'];
        }
        if ($idpago == "") {
            $datos = "";
        } else {
            $datos = $idpago;
        }
        return $datos;
    }

    public function checkPagosaux($id) {
        $consultado = false;
        $consultas = new Consultas();
        $consulta = "SELECT * FROM datospago WHERE pago_idfactura=:id;";
        $valores = array("id" => $id);
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    public function checkPagos($id) {
        $pagos = $this->checkPagosaux($id);
        $idpago = "";
        foreach ($pagos as $actual) {
            $idpago = $actual['iddatospago'];
        }
        if ($idpago == "") {
            $datos = "";
        } else {
            $datos = $idpago;
        }
        return $datos;
    }

    public function verificarPagos($sessionid) {
        $consultado = false;
        $consultas = new Consultas();
        $consulta = "SELECT * FROM tmppago WHERE sessionid=:idsession;";
        $valores = array("idsession" => $sessionid);
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    public function resetPagos($sessionid) {
        $eliminado = false;
        $consultas = new Consultas();
        $consulta = "DELETE FROM `tmppago` WHERE sessionid=:id;";
        $valores = array("id" => $sessionid);
        $eliminado = $consultas->execute($consulta, $valores);
        return $eliminado;
    }

    public function getClienteID($idcliente) {
        $consultado = false;
        $consultas = new Consultas();
        $consulta = "SELECT * FROM datosfiscales WHERE ClaveUnicaDF=:cid;";
        $val = array("cid" => $idcliente);
        $consultado = $consultas->getResults($consulta, $val);
        return $consultado;
    }

    private function getNombancoaux($idbanco) {
        $consultado = false;
        $consultas = new Consultas();
        $consulta = "SELECT nombre_banco FROM catalogo_banco WHERE idcatalogo_banco=:idbanco;";
        $val = array("idbanco" => $idbanco);
        $consultado = $consultas->getResults($consulta, $val);
        return $consultado;
    }

    public function getNomBanco($idbanco) {
        $nombre = "";
        $banco = $this->getNombancoaux($idbanco);
        foreach ($banco as $bactual) {
            $nombre = $bactual['nombre_banco'];
        }
        return $nombre;
    }

    public function getTabla($sessionid, $tag, $idmoneda, $tcambio, $uuid) {
        //$tcambio = $this->getTipoCambio($idmoneda);
        $tabla = $this->tablaPago($sessionid, $tag, $tcambio, $idmoneda, $uuid);
        return $tabla;
    }

    public function getPagosTMP($sid, $tag) {
        $consultado = false;
        $consultas = new Consultas();
        $consulta = "SELECT t.* FROM tmppago t WHERE sessionid=:sid AND tmptagcomp=:tag ORDER BY idtmppago ASC;";
        $val = array("sid" => $sid, "tag" => $tag);
        $consultado = $consultas->getResults($consulta, $val);
        return $consultado;
    }

    private function getCMetodoAux($mid) {
        $consultado = false;
        $consultas = new Consultas();
        $consulta = "SELECT * FROM catalogo_metodo_pago WHERE idmetodo_pago=:id;";
        $val = array("id" => $mid);
        $consultado = $consultas->getResults($consulta, $val);
        return $consultado;
    }

    public function getCMetodo($mid) {
        $c_moneda = "";
        $moneda = $this->getCMetodoAux($mid);
        foreach ($moneda as $actual) {
            $c_moneda = $actual['c_metodopago'];
        }
        return $c_moneda;
    }

    private function getCMonedaAux($idmoneda) {
        $consultado = false;
        $consultas = new Consultas();
        $consulta = "SELECT * FROM catalogo_moneda WHERE idcatalogo_moneda=:id;";
        $val = array("id" => $idmoneda);
        $consultado = $consultas->getResults($consulta, $val);
        return $consultado;
    }

    public function getCMoneda($idmoneda) {
        $c_moneda = "";
        $moneda = $this->getCMonedaAux($idmoneda);
        foreach ($moneda as $actual) {
            $c_moneda = $actual['c_moneda'];
        }
        return $c_moneda;
    }

    private function getFacturaTablaAux($id) {
        $consultado = false;
        $consultas = new Consultas();
        $consulta = "SELECT f.letra, f.folio_interno_fac, f.tcambio, f.uuid, f.status_pago, m.idcatalogo_moneda, m.c_moneda, mp.c_metodopago, mp.descripcion_metodopago FROM datos_factura f INNER JOIN catalogo_moneda m ON (f.id_moneda=m.idcatalogo_moneda) INNER JOIN catalogo_metodo_pago mp ON (mp.idmetodo_pago=f.id_metodo_pago) WHERE f.iddatos_factura=:id;";
        $val = array("id" => $id);
        $consultado = $consultas->getResults($consulta, $val);
        return $consultado;
    }

    public function getFacturaTabla($id) {
        $datos = "";
        $facturas = $this->getFacturaTablaAux($id);
        foreach ($facturas as $actual) {
            $folio = $actual['letra'] . $actual['folio_interno_fac'];
            $tcambio = $actual['tcambio'];
            $idmoneda = $actual['idcatalogo_moneda'];
            $cmoneda = $actual['c_moneda'];
            $uuid = $actual['uuid'];
            $cmetodo = $actual['c_metodopago'];
            $metodopago = $actual['descripcion_metodopago'];
            $datos .= "$folio</tr>$tcambio</tr>$idmoneda</tr>$cmoneda</tr>$uuid</tr>$cmetodo</tr>$metodopago";
        }
        return $datos;
    }

    private function getCartaTablaAux($id) {
        $consultado = false;
        $consultas = new Consultas();
        $consulta = "SELECT f.letra, f.foliocarta, f.tcambio, f.uuid, m.idcatalogo_moneda, m.c_moneda, mp.c_metodopago, mp.descripcion_metodopago FROM factura_carta f INNER JOIN catalogo_moneda m ON (f.id_moneda=m.idcatalogo_moneda) INNER JOIN catalogo_metodo_pago mp ON (mp.idmetodo_pago=f.id_metodo_pago) WHERE f.idfactura_carta=:id;";
        $val = array("id" => $id);
        $consultado = $consultas->getResults($consulta, $val);
        return $consultado;
    }

    public function getCartaTabla($id) {
        $datos = "";
        $facturas = $this->getCartaTablaAux($id);
        foreach ($facturas as $actual) {
            $folio = $actual['letra'] . $actual['foliocarta'];
            $tcambio = $actual['tcambio'];
            $idmoneda = $actual['idcatalogo_moneda'];
            $cmoneda = $actual['c_moneda'];
            $uuid = $actual['uuid'];
            $cmetodo = $actual['c_metodopago'];
            $metodopago = $actual['descripcion_metodopago'];
            $datos .= "$folio</tr>$tcambio</tr>$idmoneda</tr>$cmoneda</tr>$uuid</tr>$cmetodo</tr>$metodopago";
        }
        return $datos;
    }

    private function tablaPago($sessionid, $tag, $tcambio = 1, $idmoneda = "1", $uuid = "") {
        $table = "<corte><thead class='sin-paddding'>
            <tr>
                <th class='text-center'>FACTURA</th>
                <th class='text-center'>PARCIALIDAD</th>
                <th class='text-center'>TOTAL FACTURA</th>
                <th class='text-center'>MONEDA</th>
                <th class='text-center'>MONTO ANT.</th>
                <th class='text-center'>MONTO PAGADO</th>
                <th class='text-center'>RESTANTE</th>
                <th class='text-center'>OPCIONES</th>
                </thead><tbody>";
        $totalpagados = 0;
        $disuuid = "";
        if ($uuid != "") {
            $disuuid = "disabled";
        }
        $productos = $this->getPagosTMP($sessionid, $tag);
        foreach ($productos as $pagoactual) {
            $idtmp = $pagoactual['idtmppago'];
            $idfactura = $pagoactual['idfacturatmp'];
            $folio = $pagoactual['foliofacturatmp'];
            $idmonedaF = $pagoactual['idmonedatmp'];
            $tcambioF = $pagoactual['tcambiotmp'];
            $cmoneda = $this->getCMoneda($idmonedaF);
            $noparcialidad = $pagoactual['noparcialidadtmp'];
            $monto = $pagoactual['montotmp'];
            $montoanterior = $pagoactual['montoanteriortmp'];
            $montoinsoluto = $pagoactual['montoinsolutotmp'];
            $totalfactura = $pagoactual['totalfacturatmp'];
            $type = $pagoactual['type'];

            $totalpagados += $this->totalDivisa($monto, $idmoneda, $idmonedaF, $tcambioF, $tcambio);
            $table .= "
                     <tr>
                        <td>$folio</td>
                        <td>$noparcialidad</td>
                        <td>$ " . number_format(bcdiv($totalfactura, '1', 2), 2, '.', ',') . "</td>
                        <td>$cmoneda</td>
                        <td>$ " . number_format(bcdiv($montoanterior, '1', 2), 2, '.', ',') . "</td>
                        <td>$ " . number_format(bcdiv($monto, '1', 2), 2, '.', ',') . "</td>
                        <td>$ " . number_format(bcdiv($montoinsoluto, '1', 2), 2, '.', ',') . "</td>
                        <td><a $disuuid class='btn button-list' title='Eliminar' onclick='eliminarcfdi($idtmp);'><span class='glyphicon glyphicon-remove'></span></a></td>
                    </tr>";
        }
        $monedapago = $this->getCMoneda($idmoneda);
        $table .= "
            </tbody>
            <tfoot>
            <tr>
            <th colspan='3' align='right'></th>
            <th align='right'><b>TOTAL PAGADO:</b></th>
            <th>$ " . number_format(bcdiv($totalpagados, '1', 2), 2, '.', ',') . " $monedapago</th>
            <th></th>
            </tr>
        </tfoot>";
        return $table;
    }

    private function totalDivisa($total, $monedaP, $monedaF, $tcambioF = '0', $tcambioP = '0') {
        if ($monedaP == $monedaF) {
            $OP = bcdiv($total, '1', 2);
        } else {
            $tcambio = $this->getTipoCambio($monedaP, $monedaF, $tcambioF, $tcambioP);
            $OP = bcdiv($total, '1', 2) / bcdiv($tcambio, '1', 6);
        }
        return $OP;
    }

    private function checkParcialidadAux($p, $id, $type) {
        $datos = false;
        $con = new Consultas();
        //$consulta = "SELECT * FROM detallepago WHERE noparcialidad=:p AND pago_idfactura=:id AND type=:type";
        $consulta = "SELECT * 
                    FROM detallepago dp 
                    INNER JOIN pagos p ON p.tagpago = dp.detalle_tagencabezado 
                    WHERE dp.noparcialidad=:p 
                    AND dp.pago_idfactura=:id
                    AND dp.type=:type
                    AND p.cancelado = 0";
        $val = array("p" => $p,
            "id" => $id,
            "type" => $type);
        $datos = $con->getResults($consulta, $val);
        return $datos;
    }

    private function checkParcialidadTmp($p, $id, $type) {
        $datos = false;
        $con = new Consultas();
        $consulta = "SELECT * FROM tmppago WHERE noparcialidadtmp=:p AND idfacturatmp=:id AND type=:type";
        $val = array("p" => $p,
            "id" => $id,
            "type" => $type);
        $datos = $con->getResults($consulta, $val);
        return $datos;
    }

    private function checkPago($t) {
        $check = false;
        $montoins = $t->getMontoinsolutotmp();
        if (bcdiv($montoins, '1', 2) < 0) {
            echo "0El monto ingresado supera al total de la factura";
            $check = true;
        }

        if (!$check) {
            $datos = $this->checkParcialidadAux($t->getParcialidadtmp(), $t->getIdfacturatmp(), $t->getType());
            foreach ($datos as $actual) {
                echo "00Ya esta registrado este numero de parcialidad";
                $check = true;
                break;
            }
        }

        if (!$check) {
            $datos = $this->checkParcialidadTmp($t->getParcialidadtmp(), $t->getIdfacturatmp(), $t->getType());
            foreach ($datos as $actual) {
                echo "01Ya esta registrado este numero de parcialidad";
                $check = true;
                break;
            }
        }
        return $check;
    }

    public function agregarPago($t) {
        $insertado = false;
        $check = $this->checkPago($t);
        if (!$check) {
            $con = new Consultas();
            $consulta = "INSERT INTO `tmppago` VALUES (:id, :parcialidad, :idfactura, :folio, :uuid, :tcambio, :idmoneda, :cmetodo, :monto, :montoant, :montoins, :total, :type, :tag, :session);";
            $valores = array("id" => null,
                "parcialidad" => $t->getParcialidadtmp(),
                "idfactura" => $t->getIdfacturatmp(),
                "folio" => $t->getFoliotmp(),
                "uuid" => $t->getUuid(),
                "tcambio" => $t->getTcamcfdi(),
                "idmoneda" => $t->getIdmonedacdfi(),
                "cmetodo" => $t->getCmetodo(),
                "monto" => bcdiv($t->getMontopagotmp(), '1', 2),
                "montoant" => bcdiv($t->getMontoanteriortmp(), '1', 2),
                "montoins" => bcdiv($t->getMontoinsolutotmp(), '1', 2),
                "total" => $t->getTotalfacturatmp(),
                "type" => $t->getType(),
                "session" => $t->getSessionid(),
                "tag" => $t->getTag());
            $insertado = $con->execute($consulta, $valores);
        }
        return $insertado;
    }

    private function getTagPagoAux($idpago) {
        $resultados = "";
        $con = new Consultas();
        $consulta = "SELECT * FROM pagos WHERE idpago=:id;";
        $val = array("id" => $idpago);
        $resultados = $con->getResults($consulta, $val);
        return $resultados;
    }

    private function getTagPago($idpago) {
        $tag = "";
        $datos = $this->getTagPagoAux($idpago);
        foreach ($datos as $actual) {
            $tag = $actual['tagpago'];
        }
        return $tag;
    }

    public function eliminarPago($idpago) {
        $tag = $this->getTagPago($idpago);
        $eliminado = false;
        $consultas = new Consultas();
        $consulta = "DELETE FROM `pagos` WHERE idpago=:id;";
        $valores = array("id" => $idpago);
        $eliminado = $consultas->execute($consulta, $valores);
        $detalle = $this->eliminarDetallePago($tag);
        return $detalle;
    }

    private function getestadoFacturaAux($id, $type) {
        $consultado = false;
        $consultas = new Consultas();
        if ($type == 'f') {
            $consulta = "SELECT * FROM datos_factura WHERE iddatos_factura=:id;";
        } else if ($type == 'c') {
            $consulta = "SELECT * FROM factura_carta WHERE idfactura_carta=:id;";
        }
        $val = array("id" => $id);
        $consultado = $consultas->getResults($consulta, $val);
        return $consultado;
    }

    private function getestadoFactura($id, $type) {
        $status = "";
        $datos = $this->getestadoFacturaAux($id, $type);
        foreach ($datos as $actual) {
            $status = $actual['status_pago'];
        }
        return $status;
    }

    private function getDetalleEliminar($tag) {
        $consultado = false;
        $con = new Consultas();
        $consulta = "SELECT * FROM detallepago WHERE detalle_tagencabezado=:tag ORDER BY iddetallepago;";
        $val = array("tag" => $tag);    
        $consultado = $con->getResults($consulta, $val);
        return $consultado;
    }

    private function eliminarDetallePago($tag) {
        $getpago = $this->getDetalleEliminar($tag);
        foreach ($getpago as $actual) {
            $idfactura = $actual['pago_idfactura'];
            $type = $actual['type'];
            $estado = $this->getestadoFactura($idfactura, $type);
            if ($estado != '3') {
                $actualizar = $this->estadoFactura($idfactura, '2', $type);
            }
        }

        $eliminado = false;
        $con = new Consultas();
        $consulta = "DELETE FROM complemento_pago WHERE tagpago=:tag;";
        $val = array("tag" => $tag);
        $eliminado = $con->execute($consulta, $val);

        $consulta2 = "DELETE FROM detallepago WHERE detalle_tagencabezado=:tag;";
        $val2 = array("tag" => $tag);
        $eliminado2 = $con->execute($consulta2, $val2);
        return $eliminado;
    }

    private function getIDPagoAux($folio, $iddatos) {
        $consultado = false;
        $consultas = new Consultas();
        $consulta = "SELECT * FROM pagos WHERE foliopago=:folio AND pago_idfiscales=:iddatos;";
        $valores = array("folio" => $folio,
            "iddatos" => $iddatos);
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    public function getIDPago($folio, $iddatos) {
        $idfactura = "";
        $facturas = $this->getIDPagoAux($folio, $iddatos);
        foreach ($facturas as $facturaactual) {
            $idfactura = $facturaactual['idpago'];
        }
        return $idfactura;
    }

    private function checkTMPPago($sessionid) {
        $tmp = $this->getPagosTMP($sessionid);
        $contador = 0;
        foreach ($tmp as $tmpactual) {
            $contador++;
        }
        return $contador;
    }

    public function validarPago($p, $objimpuesto) {
        /*$sessionid = $p->getSessionid();
        $check = $this->checkTMPPago($sessionid);
        if ($check == 0) {
            $datos = "0No hay CFDIs agregados para pago.";
        } else { */
            $datos = $this->insertarPago($p, $objimpuesto);
        //}
        return $datos;
    }

    private function updateFolioConsecutivo($id) {
        $consultado = false;
        $consultas = new Consultas();
        $consulta = "UPDATE folio SET consecutivo=(consecutivo+1) WHERE idfolio=:id;";
        $val = array("id" => $id);
        $consultado = $consultas->execute($consulta, $val);
        return $consultado;
    }

    private function getFoliobyID($id) {
        $consultado = false;
        $consultas = new Consultas();
        $consulta = "SELECT * FROM folio WHERE idfolio=:id;";
        $val = array("id" => $id);
        $consultado = $consultas->getResults($consulta, $val);
        return $consultado;
    }

    private function getFolio($id) {
        $datos = "";
        $folios = $this->getFoliobyID($id);
        foreach ($folios as $actual) {
            $serie = $actual['serie'];
            $letra = $actual['letra'];
            $consecutivo = $actual['consecutivo'];
            if ($consecutivo < 10) {
                $consecutivo = "000$consecutivo";
            } else if ($consecutivo < 100 && $consecutivo >= 10) {
                $consecutivo = "00$consecutivo";
            } else if ($consecutivo < 1000 && $consecutivo >= 100) {
                $consecutivo = "0$consecutivo";
            }
            $datos = "$serie</tr>$letra</tr>$consecutivo";
            $update = $this->updateFolioConsecutivo($id);
        }
        return $datos;
    }

    private function insertarPago($p, $objimpuesto) {
        $insertado = false;

        $fecha = getdate();
        $d = $fecha['mday'];
        $m = $fecha['mon'];
        $y = $fecha['year'];
        if ($d < 10) {
            $d = "0$d";
        }
        if ($m < 10) {
            $m = "0$m";
        }
        $hoy = "$y-$m-$d";
        $tag = $this->genTag();

        $folios = $this->getFolio($p->getFoliopago());
        $Fdiv = explode("</tr>", $folios);
        $serie = $Fdiv[0];
        $letra = $Fdiv[1];
        $nfolio = $Fdiv[2];
        $con = new Consultas();
        $consulta = "INSERT INTO `pagos` VALUES (:idpago, :rfcemisor, :razonemisor, :clvemisor, :regfisemisor, :codpemisor, :serie, :letra, :foliopago, :fechacreacion, :idcliente, :cliente, :rfcreceptor, :rzreceptor, :regfiscalreceptor, :codpreceptor, :iddatosfiscales, :totalpagado, :chfirmar, :cadenaoriginalpago, :nocertsatpago, :nocertcfdipago, :uuidpago, :sellosatpago, :sellocfdipago, :fechatimbrado, :qrcode, :cfdipago, :cfdicancel, :cancelado, :tagpago, :objimpuesto);";
        $valores = array("idpago" => null,
            "rfcemisor" => '',
            "razonemisor" => '',
            "clvemisor" => '',
            "regfisemisor" => '',
            "codpemisor" => '',
            "serie" => $serie,
            "letra" => $letra,
            "foliopago" => $nfolio,
            "fechacreacion" => $hoy,
            "idcliente" => $p->getIdcliente(),
            "cliente" => $p->getNombrecliente(),
            "rfcreceptor" => $p->getRfccliente(),
            "rzreceptor" => $p->getRazoncliente(),
            "regfiscalreceptor" => $p->getRegfiscalcliente(),
            "codpreceptor" => $p->getCodpostal(),
            "iddatosfiscales" => $p->getPago_idfiscales(),
            "totalpagado" => '0',
            "chfirmar" => $p->getChfirmar(),
            "cadenaoriginalpago" => null,
            "nocertsatpago" => null,
            "nocertcfdipago" => null,
            "uuidpago" => null,
            "sellosatpago" => null,
            "sellocfdipago" => null,
            "fechatimbrado" => null,
            "qrcode" => null,
            "cfdipago" => null,
            "cfdicancel" => null,
            "cancelado" => '0',
            "tagpago" => $tag,
            "objimpuesto" => $objimpuesto
        );
        $insertado = $con->execute($consulta, $valores);
        return "<cut>$tag<cut>";
    }

    public function insertarComplemento($p) {
        $insertar = false;
        $con = new Consultas();
        $consulta = "INSERT INTO complemento_pago VALUES (:id, :orden, :idforma, :idmoneda, :tcambio, :fechapago, :horapago, :cuentaord, :cuentabnf, :notransaccion, :total, :tagcomp, :tagpago);";
        $val = array("id" => null,
            "orden" => $p->getOrden(),
            "idforma" => $p->getPagoidformapago(),
            "idmoneda" => $p->getPagoidmoneda(),
            "tcambio" => $p->getTipocambio(),
            "fechapago" => $p->getFechapago(),
            "horapago" => $p->getHorapago(),
            "cuentaord" => $p->getPago_idbanco(),
            "cuentabnf" => $p->getIdbancoB(),
            "notransaccion" => $p->getNooperacion(),
            "total" => '0',
            "tagcomp" => $p->getTagcomp(),
            "tagpago" => $p->getTagpago());
        $insertar = $con->execute($consulta, $val);
        $this->detallePago($p->getSessionid(), $p->getTagpago(), $p->getTagcomp(), $p->getTipocambio(), $p->getPagoidmoneda());
        return $insertar;
    }

    public function detallePago($idsession, $tagpago, $tagcomp, $tcambio, $monedapago) {
        $totalpagado = 0;
        $con = new Consultas();
        $cfdi = $this->getPagosTMP($idsession, $tagcomp);
        foreach ($cfdi as $actual) {
            $parcialidad = $actual['noparcialidadtmp'];
            $idfactura = $actual['idfacturatmp'];
            $folio = $actual['foliofacturatmp'];
            $uuid = $actual['uuidtmp'];
            $tcambiodoc = $actual['tcambiotmp'];
            $idmonedaF = $actual['idmonedatmp'];
            $cmetodotmp = $actual['idmetodotmp'];
            $montopagado = $actual['montotmp'];
            $montoanterior = $actual['montoanteriortmp'];
            $montoinsoluto = $actual['montoinsolutotmp'];
            $totalfactura = $actual['totalfacturatmp'];
            $type = $actual['type'];
            $tag = $actual['tmptagcomp'];

            $totalpagado += $montopagado;

            $consulta2 = "INSERT INTO `detallepago` VALUES (:iddetallepago, :noparcialidad, :pagoidfactura, :folio, :uuid, :tcambio, :idmoneda, :cmetodo, :monto, :montoanterior, :montoinsoluto, :totalfactura, :type, :tagpago, :tagcomp);";
            $valores2 = array("iddetallepago" => null,
                "noparcialidad" => $parcialidad,
                "pagoidfactura" => $idfactura,
                "folio" => $folio,
                "uuid" => $uuid,
                "tcambio" => $tcambiodoc,
                "idmoneda" => $idmonedaF,
                "cmetodo" => $cmetodotmp,
                "monto" => $montopagado,
                "montoanterior" => $montoanterior,
                "montoinsoluto" => $montoinsoluto,
                "totalfactura" => $totalfactura,
                "type" => $type,
                "tagpago" => $tagpago,
                "tagcomp" => $tagcomp);
            $insertado = $con->execute($consulta2, $valores2);

            if ($montoinsoluto != '0') {
                $estado = '4';
            } else if ($montoinsoluto == '0') {
                $estado = '1';
            }
            if ($idfactura != '0') {
                $this->estadoFactura($idfactura, $estado, $type);
            }
        }

        $this->cancelar($idsession, $tagcomp);
        $update = "UPDATE `complemento_pago` SET total_complemento=:total WHERE tagcomplemento=:tag;";
        $valores4 = array("tag" => $tagcomp,
            "total" => $totalpagado);
            //"total" => bcdiv($totalpagado, '1', 6));
        $insertado = $con->execute($update, $valores4);

        $update1 = "UPDATE `pagos` SET totalpagado=(totalpagado+$totalpagado) WHERE tagpago=:tag;";
        $valores5 = array("tag" => $tagpago);
            //"total" => bcdiv($totalpagado, '1', 6));
        $insertado = $con->execute($update1, $valores5);
        return $insertado;
    }

    public function getConceptobyClave($clave) {
        $consultado = false;
        $consultas = new Consultas();
        $consulta = "SELECT * FROM catalogo_conceptos WHERE codigo_concepto=:clv;";
        $valores = array("clv" => $clave);
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    public function getProductobyClave($clave) {
        $consultado = false;
        $consultas = new Consultas();
        $consulta = "SELECT * FROM catalogo_prodserv WHERE c_ProdServ=:clv;";
        $valores = array("clv" => $clave);
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    public function getDatosConcepto($id) {
        $concepto = $this->getTMPbyId($id);
        $datos = "";
        foreach ($concepto as $actual) {
            $idtmp = $actual['idtmp'];
            $codigo = $actual['codigotmp'];
            $getcodigo = $this->getConceptobyClave($codigo);
            foreach ($getcodigo as $codactual) {
                $nombrecodigo = $codactual['nombre'];
            }
            $codigoprod = "$codigo-$nombrecodigo";
            $clave = $actual['clavetmp'];
            $getclave = $this->getProductobyClave($clave);
            foreach ($getclave as $clvactual) {
                $descripcionprodserv = $clvactual['Descripcion'];
            }
            $clv = "Clave-$clave-$descripcionprodserv";
            $cantidad = $actual['cantidadtmp'];
            $unidad = $actual['unidadtmp'];
            $clvunidad = $actual['clvunidadtmp'];
            $descripcion = $actual['descripciontmp'];
            $precioun = $actual['preciounitariotmp'];
            $totunitario = $actual['totunitariotmp'];
            $descuento = $actual['descuentotmp'];
            $importetmp = $actual['importetmp'];
            $chiva = $actual['chiva'];
            $chret = $actual['chret'];
            $datos = "$idtmp</tr>$codigoprod</tr>$clv</tr>$cantidad</tr>$unidad</tr>$clvunidad</tr>$descripcion</tr>$precioun</tr>$totunitario</tr>$descuento</tr>$importetmp</tr>$chiva</tr>$chret";
        }
        return $datos;
    }

    public function validarActualizarPago($p, $objimpuesto) {
        /* $check = $this->checkTMPPago($p->getSessionid());
          if ($check == 0) {
          $datos = "0No hay CFDIs agregados para pago.";
          } else { */
        $datos = $this->actualizarPago($p, $objimpuesto);
        //}
        return $datos;
    }

    private function actualizarPago($p, $objimpuesto) {
        $updfolio = "";
        $serie = "";
        $letra = "";
        $nfolio = "";

        if ($p->getFoliopago() != '0') {
            $updfolio = "serie=:serie, letra=:letra, foliopago=:folio,";
            $folios = $this->getFolio($p->getFoliopago());
            $Fdiv = explode("</tr>", $folios);
            $serie = $Fdiv[0];
            $letra = $Fdiv[1];
            $nfolio = $Fdiv[2];
        }

        $insertado = false;
        $con = new Consultas();
        $consulta = "UPDATE `pagos` SET $updfolio pago_idcliente=:pago_idcliente, nombrecliente=:cliente, rfcreceptor=:rfcreceptor, razonreceptor=:razonreceptor, regfiscalreceptor=:regfiscalreceptor, codpreceptor=:codpreceptor, pago_idfiscales=:pago_idfiscales, chfirmar=:chfirmar, objimpuesto=:objimpuesto WHERE idpago=:id;";
        $valores = array("id" => $p->getIdpago(),
            "serie" => $serie,
            "letra" => $letra,
            "folio" => $nfolio,
            "pago_idcliente" => $p->getIdcliente(),
            "cliente" => $p->getNombrecliente(),
            "rfcreceptor" => $p->getRfccliente(),
            "razonreceptor" => $p->getRazoncliente(),
            "regfiscalreceptor" => $p->getRegfiscalcliente(),
            "codpreceptor" => $p->getCodpostal(),
            "pago_idfiscales" => $p->getPago_idfiscales(),
            "chfirmar" => $p->getChfirmar(),
            "objimpuesto" => $objimpuesto
        );
        $insertado = $con->execute($consulta, $valores);

        $this->eliminarComplementos($p->getTagpago());

        $update1 = "UPDATE `pagos` SET totalpagado='0' WHERE tagpago=:tag;";
        $valores5 = array("tag" => $p->getTagpago());
        $insertado = $con->execute($update1, $valores5);
        return $insertado;
    }

    private function eliminarComplementos($tag) {
        $eliminado = false;
        $con = new Consultas();
        $consulta = "DELETE FROM complemento_pago WHERE tagpago=:tag;";
        $val = array("tag" => $tag);
        $eliminado = $con->execute($consulta, $val);

        $consulta2 = "DELETE FROM detallepago WHERE detalle_tagencabezado=:tag;";
        $val2 = array("tag" => $tag);
        $eliminado2 = $con->execute($consulta2, $val2);
        return $eliminado;
    }

    public function actualizarComplemento($p) {
        $insertar = false;
        $con = new Consultas();
        $consulta = "INSERT INTO complemento_pago VALUES (:id, :orden, :idforma, :idmoneda, :tcambio, :fechapago, :horapago, :cuentaord, :cuentabnf, :notransaccion, :total, :tagcomp, :tagpago)";
        $val = array("id" => null,
            "orden" => $p->getOrden(),
            "idforma" => $p->getPagoidformapago(),
            "idmoneda" => $p->getPagoidmoneda(),
            "tcambio" => $p->getTipocambio(),
            "fechapago" => $p->getFechapago(),
            "horapago" => $p->getHorapago(),
            "cuentaord" => $p->getPago_idbanco(),
            "cuentabnf" => $p->getIdbancoB(),
            "notransaccion" => $p->getNooperacion(),
            "total" => '0',
            "tagcomp" => $p->getTagcomp(),
            "tagpago" => $p->getTagpago());
        $insertar = $con->execute($consulta, $val);
        $this->actualizarDetalle($p->getSessionid(), $p->getTagpago(), $p->getTagcomp(), $p->getTipocambio(), $p->getPagoidmoneda());
        return $insertar;
    }

    public function actualizarDetalle($idsession, $tagpago, $tagcomp, $tcambio, $monedapago) {
        $totalpagado = 0;
        $con = new Consultas();
        $cfdi = $this->getPagosTMP($idsession, $tagcomp);
        foreach ($cfdi as $actual) {
            $parcialidad = $actual['noparcialidadtmp'];
            $idfactura = $actual['idfacturatmp'];
            $folio = $actual['foliofacturatmp'];
            $uuid = $actual['uuidtmp'];
            $tcambiodoc = $actual['tcambiotmp'];
            $idmonedaF = $actual['idmonedatmp'];
            $cmetodotmp = $actual['idmetodotmp'];
            $montopagado = $actual['montotmp'];
            $montoanterior = $actual['montoanteriortmp'];
            $montoinsoluto = $actual['montoinsolutotmp'];
            $totalfactura = $actual['totalfacturatmp'];
            $type = $actual['type'];
            $tag = $actual['tmptagcomp'];

            $totalpagado += $montopagado;

            $consulta2 = "INSERT INTO `detallepago` VALUES (:iddetallepago, :noparcialidad, :pagoidfactura, :folio, :uuid, :tcambio, :idmoneda, :cmetodo, :monto, :montoanterior, :montoinsoluto, :totalfactura, :type, :tagpago, :tagcomp);";
            $valores2 = array("iddetallepago" => null,
                "noparcialidad" => $parcialidad,
                "pagoidfactura" => $idfactura,
                "folio" => $folio,
                "uuid" => $uuid,
                "tcambio" => $tcambiodoc,
                "idmoneda" => $idmonedaF,
                "cmetodo" => $cmetodotmp,
                "monto" => $montopagado,
                "montoanterior" => $montoanterior,
                "montoinsoluto" => $montoinsoluto,
                "totalfactura" => $totalfactura,
                "type" => $type,
                "tagpago" => $tagpago,
                "tagcomp" => $tagcomp);
            $insertado = $con->execute($consulta2, $valores2);

            if ($montoinsoluto != '0') {
                $estado = '4';
            } else if ($montoinsoluto == '0') {
                $estado = '1';
            }
            if ($idfactura != '0') {
                //echo "ID: ".$idfactura." ESTADO: ".$estado." Type: ".$type."<br>";
                $this->estadoFactura($idfactura, $estado, $type);
            }
        }

        $this->cancelar($idsession, $tagcomp);
        $update = "UPDATE `complemento_pago` SET total_complemento=:total WHERE tagcomplemento=:tag";
        $valores4 = array("tag" => $tagcomp,
            "total" => $totalpagado);
            //"total" => bcdiv($totalpagado, '1', 6));
        $insertado = $con->execute($update, $valores4);

        $update1 = "UPDATE `pagos` SET totalpagado=(totalpagado+$totalpagado) WHERE tagpago=:tag";
        $valores5 = array("tag" => $tagpago);
            //"total" => bcdiv($totalpagado, '1', 6));
        $insertado = $con->execute($update1, $valores5);
        return $insertado;
    }

    public function eliminar($idtemp, $sessionid) {
        $eliminado = false;
        $consultas = new Consultas();
        $consulta = "DELETE FROM `tmppago` WHERE idtmppago=:id;";
        $valores = array("id" => $idtemp);
        $eliminado = $consultas->execute($consulta, $valores);
        return $eliminado;
    }

    public function cancelar($sid, $tag = "") {
        $com = "";
        if ($tag != "") {
            $com = " AND tmptagcomp=:tag";
        }
        $eliminado = false;
        $consultas = new Consultas();
        $consulta = "DELETE FROM `tmppago` WHERE sessionid=:id$com;";
        $valores = array("id" => $sid,
            "tag" => $tag);
        $eliminado = $consultas->execute($consulta, $valores);
        return $eliminado;
    }

    public function estadoFactura($idfactura, $estado, $type) {
        $consulta = "";
        $con = new Consultas();
        $actualizado = false;
        if ($type == 'f') {
            $consulta = "UPDATE `datos_factura` SET status_pago=:estado WHERE iddatos_factura=:id";
        } else if ($type == 'c') {
            $consulta = "UPDATE `factura_carta` SET status_pago=:estado WHERE idfactura_carta=:id";
        }        
        $valores = array("id" => $idfactura, "estado" => $estado);
        $actualizado = $con->execute($consulta, $valores);
        return $actualizado;
    }

    public function verificarProductos($sessionId) {
        $consultado = false;
        $consultas = new Consultas();
        $consulta = "SELECT * FROM tmpfactura WHERE sessionid=:idsession;";
        $valores = array("idsession" => $sessionId);
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    public function getTMPbyId($id) {
        $consultado = false;
        $consultas = new Consultas();
        $consulta = "SELECT * FROM tmpfactura WHERE idtmp=:id;";
        $valores = array("id" => $id);
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    public function loadFiscales($idcarta) {
        $remitente = $this->getIdRemitente($idcarta);
        foreach ($remitente as $clienteactual) {
            $remitenteid = $clienteactual['RemitenteD'];
        }
        $dfiscales = $this->getDatosFiscales($remitenteid);
        $fiscales = "";
        foreach ($dfiscales as $dfiscalesactual) {
            $idfiscales = $dfiscalesactual['ClaveUnicaDF'];
            $rfcCliente = $dfiscalesactual['Rfc'];
            $razonCliente = $dfiscalesactual['RazonSocial'];
            $fiscales = "ID-$idfiscales-$razonCliente-$rfcCliente";
        }
        return $fiscales;
    }

    private function getParcialidad($idfactura, $idpago) {
        $datos = "1";
        $datos2 = "";
        $parcialidad = $this->getParcialidadAux($idfactura, $idpago);
        foreach ($parcialidad as $p) {
            $datos = $p['par'];
        }

        $parcialidad2 = $this->getParcialidadAux2($idfactura);
        foreach ($parcialidad2 as $p) {
            $datos2 = $p['par'];
        }
        if ($datos2 > $datos) {
            $datos = $datos2;
        }

        return $datos;
    }

    private function getParcialidadAux($idfactura, $idpago) {
        $consultado = false;
        $consultas = new Consultas();
        $consulta = "SELECT (noparcialidad)+1 par FROM detallepago dt INNER JOIN pagos p ON (p.tagpago=dt.detalle_tagencabezado) WHERE pago_idfactura=:id AND cancelado != '1' AND p.tagpago != :idpago AND type=:tipo;";
        $valores = array("id" => $idfactura,
            "idpago" => $idpago,
            "tipo" => 'f');
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    private function getParcialidadAux2($idfactura) {
        $consultado = false;
        $sessionid = session_id();
        $consultas = new Consultas();
        $consulta = "SELECT (noparcialidadtmp)+1 as par FROM tmppago WHERE idfacturatmp=:id and sessionid=:sid AND type=:tipo;";
        $valores = array("id" => $idfactura,
            "sid" => $sessionid,
            "tipo" => 'f');
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    private function getMontoAnteriorAux2($noparcialidad_tmp, $idfactura_tmp) {
        $consultado = false;
        $consultas = new Consultas();
        $consulta = "SELECT montoinsolutotmp FROM tmppago WHERE noparcialidadtmp=:noparcialidad AND idfacturatmp=:idfactura AND type=:tipo;";
        $val = array("noparcialidad" => $noparcialidad_tmp,
            "idfactura" => $idfactura_tmp,
            "tipo" => 'f');
        $consultado = $consultas->getResults($consulta, $val);
        return $consultado;
    }

    private function getMontoAnteriorAux($noparcialidad, $idfactura_tmp) {
        $consultado = false;
        $consultas = new Consultas();
        $consulta = "SELECT montoinsoluto FROM detallepago WHERE noparcialidad=:noparcialidad AND pago_idfactura=:idfactura AND type=:tipo;";
        $val = array("noparcialidad" => $noparcialidad,
            "idfactura" => $idfactura_tmp,
            "tipo" => 'f');
        $consultado = $consultas->getResults($consulta, $val);
        return $consultado;
    }

    private function getMontoAnterior($noparcialidad_tmp, $idfactura_tmp) {
        $totalfactura = "";
        $totalfactura2 = "";
        $total = $this->getMontoAnteriorAux($noparcialidad_tmp, $idfactura_tmp);
        foreach ($total as $actual) {
            $totalfactura = $actual['montoinsoluto'];
        }
        if ($totalfactura == "") {
            $total2 = $this->getMontoAnteriorAux2($noparcialidad_tmp, $idfactura_tmp);
            foreach ($total2 as $actual2) {
                $totalfactura2 = $actual2['montoinsolutotmp'];
            }
            $totalfactura = $totalfactura2;
        }
        return $totalfactura;
    }

    private function getFactura($id) {
        $consultado = false;
        $consultas = new Consultas();
        $consulta = "SELECT f.iddatos_factura, f.letra, f.folio_interno_fac, f.tcambio, f.uuid, f.totalfactura,f.status_pago, f.id_moneda, f.id_metodo_pago FROM datos_factura f WHERE f.iddatos_factura=:id;";
        $val = array("id" => $id);
        $consultado = $consultas->getResults($consulta, $val);
        return $consultado;
    }

    private function dataFactura($id, $idpago) {
        $datos = "";
        $getfactura = $this->getFactura($id);
        foreach ($getfactura as $actual) {
            $iddatosfactura = $actual['iddatos_factura'];
            $tcambio = $actual['tcambio'];
            $idmoneda = $actual['id_moneda'];
            $uuid = $actual['uuid'];
            $cmetodo = $actual['id_metodo_pago'];
            $totalfactura = $actual['totalfactura'];

            $parcialidad = $this->getParcialidad($iddatosfactura, $idpago);
            $noparc = $parcialidad - 1;
            if ($noparc == '0') {
                $montoanterior = $actual['totalfactura'];
            } else {
                $montoanterior = $this->getMontoAnterior($noparc, $iddatosfactura);
            }
            $datos = "$iddatosfactura</tr>$uuid</tr>$tcambio</tr>$idmoneda</tr>$cmetodo</tr>$totalfactura</tr>$parcialidad</tr>$montoanterior</tr>$noparc";
        }
        return $datos;
    }

    public function loadFactura($id, $idpago, $type) {
        $datos = false;
        if ($type == 'f') {
            $datos = $this->dataFactura($id, $idpago);
        } else if ($type == 'c') {
            $datos = $this->dataFacturaCarta($id, $idpago);
        }
        return $datos;
    }

    private function getParcialidadCarta($idfactura, $idpago) {
        $datos = "";
        $datos2 = "";
        $parcialidad = $this->getParcialidadCartaAux($idfactura, $idpago);
        foreach ($parcialidad as $p) {
            $datos = $p['par'];
        }
        if ($datos == "") {
            $parcialidad2 = $this->getParcialidadCartaAux2($idfactura);
            foreach ($parcialidad2 as $p2) {
                $datos2 = $p2['par'];
            }
            if ($datos2 == "") {
                $datos = "1";
            } else {
                $datos = $datos2;
            }
        }
        return $datos;
    }

    private function getParcialidadCartaAux($idfactura, $idpago) {
        $consultado = false;
        $consultas = new Consultas();
        $consulta = "SELECT (noparcialidad)+1 par FROM detallepago dt INNER JOIN pagos p ON (p.tagpago=dt.detalle_tagencabezado) WHERE pago_idfactura=:id AND cancelado != '1' AND p.tagpago != :idpago AND type=:tipo;";
        $valores = array("id" => $idfactura,
            "idpago" => $idpago,
            "tipo" => 'c');
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    private function getParcialidadCartaAux2($idfactura) {
        $consultado = false;
        $sessionid = session_id();
        $consultas = new Consultas();
        $consulta = "SELECT (noparcialidadtmp)+1 as par FROM tmppago WHERE idfacturatmp=:id and sessionid=:sid AND type=:tipo";
        $valores = array("id" => $idfactura,
            "sid" => $sessionid,
            "tipo" => 'c');
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    private function getMontoAnteriorCartaAux2($noparcialidad_tmp, $idfactura_tmp) {
        $consultado = false;
        $consultas = new Consultas();
        $consulta = "SELECT montoinsolutotmp FROM tmppago WHERE noparcialidadtmp=:noparcialidad AND idfacturatmp=:idfactura AND type=:tipo;";
        $val = array("noparcialidad" => $noparcialidad_tmp,
            "idfactura" => $idfactura_tmp,
            "tipo" => 'c');
        $consultado = $consultas->getResults($consulta, $val);
        return $consultado;
    }

    private function getMontoAnteriorCartaAux($noparcialidad, $idfactura_tmp) {
        $consultado = false;
        $consultas = new Consultas();
        $consulta = "SELECT montoinsoluto FROM detallepago WHERE noparcialidad=:noparcialidad AND pago_idfactura=:idfactura AND type=:tipo;";
        $val = array("noparcialidad" => $noparcialidad,
            "idfactura" => $idfactura_tmp,
            "tipo" => 'c');
        $consultado = $consultas->getResults($consulta, $val);
        return $consultado;
    }

    private function getMontoAnteriorCarta($noparcialidad_tmp, $idfactura_tmp) {
        $total = $this->getMontoAnteriorCartaAux($noparcialidad_tmp, $idfactura_tmp);
        $totalfactura = "";
        $totalfactura2 = "";
        foreach ($total as $actual) {
            $totalfactura = $actual['montoinsoluto'];
        }
        if ($totalfactura == "") {
            $total2 = $this->getMontoAnteriorCartaAux2($noparcialidad_tmp, $idfactura_tmp);
            foreach ($total2 as $actual2) {
                $totalfactura2 = $actual2['montoinsolutotmp'];
            }
            $totalfactura = $totalfactura2;
        }
        return $totalfactura;
    }

    private function getFacturaCarta($id) {
        $consultado = false;
        $consultas = new Consultas();
        $consulta = "SELECT f.idfactura_carta, f.letra, f.foliocarta, f.tcambio, f.uuid, f.totalfactura, f.status_pago, f.id_moneda, f.id_metodo_pago FROM factura_carta f WHERE f.idfactura_carta=:id;";
        $val = array("id" => $id);
        $consultado = $consultas->getResults($consulta, $val);
        return $consultado;
    }

    private function dataFacturaCarta($id, $idpago) {
        $datos = "";
        $getfactura = $this->getFacturaCarta($id);
        foreach ($getfactura as $actual) {
            $iddatosfactura = $actual['idfactura_carta'];
            $tcambio = $actual['tcambio'];
            $idmoneda = $actual['id_moneda'];
            $uuid = $actual['uuid'];
            $idmetodo = $actual['id_metodo_pago'];
            $totalfactura = $actual['totalfactura'];

            $parcialidad = $this->getParcialidadCarta($iddatosfactura, $idpago);
            $noparc = $parcialidad - 1;
            if ($noparc == '0') {
                $montoanterior = $actual['totalfactura'];
            } else {
                $montoanterior = $this->getMontoAnteriorCarta($noparc, $iddatosfactura);
            }

            $datos = "$iddatosfactura</tr>$uuid</tr>$tcambio</tr>$idmoneda</tr>$idmetodo</tr>$totalfactura</tr>$parcialidad</tr>$montoanterior</tr>$noparc";
        }
        return $datos;
    }

    public function getDocumento() {
        $datos = "";
        $documento = $this->getDocumentoAux();
        foreach ($documento as $d) {
            $do = $d['doc'];
            $datos = '' . $do;
        }
        return $datos;
    }

    public function getDocumentoAux() {
        $consultado = false;
        $idusuario = $_SESSION[sha1("idusuario")];
        $consultas = new Consultas();
        $consulta = "SELECT concat(nombre,' ',apellido_paterno) doc FROM usuario WHERE id_usuario=:id;";
        $val = array("id" => $idusuario);
        $consultado = $consultas->getResults($consulta, $val);
        return $consultado;
    }

    public function getFecha() {
        $datos = "";
        $f = getdate();
        $d = $f['mday'];
        $m = $f['mon'];
        $y = $f['year'];
        $h = $f['hours'];
        $mi = $f['minutes'];
        $s = $f['seconds'];
        if ($d < 10) {
            $d = "0$d";
        }
        if ($m < 10) {
            $m = "0$m";
        }
        if ($h < 10) {
            $h = "0$h";
        }
        if ($mi < 10) {
            $mi = "0$mi";
        }
        if ($s < 10) {
            $s = "0$s";
        }
        $datos = "$d/$m/$y - $h:$mi:$s";
        return $datos;
    }

    public function getDatosEmisor($fid) {
        $datos = "";
        $sine = $this->getDatosFacturacion($fid);
        foreach ($sine as $dactual) {
            $rfc = $dactual['rfc'];
            $razonsocial = $dactual['razon_social'];
            $clvreg = $dactual['c_regimenfiscal'];
            $regimen = $dactual['regimen_fiscal'];
            $codpos = $dactual['codigo_postal'];
            $datos .= "$rfc</tr>$razonsocial</tr>$clvreg</tr>$regimen</tr>$codpos";
        }
        return $datos;
    }

    private function getNumrowsAux($condicion) {
        $consultado = false;
        $con = new Consultas();
        $consulta = "SELECT count(*) numrows FROM pagos p INNER JOIN datos_facturacion d ON (d.id_datos=p.pago_idfiscales) $condicion;";
        $consultado = $con->getResults($consulta, null);
        return $consultado;
    }

    private function getNumrows($condicion) {
        $numrows = 0;
        $rows = $this->getNumrowsAux($condicion);
        foreach ($rows as $actual) {
            $numrows = $actual['numrows'];
        }
        return $numrows;
    }

    public function getServicios($condicion) {
        $consultado = false;
        $con = new Consultas();
        $consulta = "SELECT p.*, d.color FROM pagos p  INNER JOIN datos_facturacion d ON (d.id_datos=p.pago_idfiscales) $condicion;";
        $consultado = $con->getResults($consulta, null);
        return $consultado;
    }

    private function getPermisoById($idusuario) {
        $consultado = false;
        $c = new Consultas();
        $consulta = "SELECT * FROM usuariopermiso p WHERE permiso_idusuario=:idusuario;";
        $valores = array("idusuario" => $idusuario);
        $consultado = $c->getResults($consulta, $valores);
        return $consultado;
    }

    private function getPermisos($idusuario) {
        $datos = "";
        $permisos = $this->getPermisoById($idusuario);
        foreach ($permisos as $actual) {
            $lista = $actual['listapago'];
            $editar = $actual['editarpago'];
            $eliminar = $actual['eliminarpago'];
            //$timbrar = $actual['timbrarpago'];
            $timbrar = $actual['pago'];
            $datos .= "$lista</tr>$editar</tr>$eliminar</tr>$timbrar";
        }
        return $datos;
    }

    private function getNombreEmisor($fid) {
        $razonsocial = "";
        $sine = $this->getDatosFacturacion($fid);
        foreach ($sine as $dactual) {
            $razonsocial = $dactual['razon_social'];
        }
        return $razonsocial;
    }

    public function listaServiciosHistorial($REF, $pag, $numreg) {
        include '../com.sine.common/pagination.php';
        $idlogin = $_SESSION[sha1("idusuario")];
        $datos = "<thead class='sin-paddding'>
            <tr >
                <th></th>
                <th>N°Folio </th>
                <th>Fecha Creacion </th>
                <th class='col-md-3'>Emisor </th>
                <th>Receptor </th>
                <th>Timbre</th>
                <th>Total </th>
                <th>Opcion</th>
            </tr>
        </thead>
        <tbody>";

        $condicion = "";
        if ($REF == "") {
            $condicion = "ORDER BY idpago DESC";
        } else {
            $condicion = "WHERE (concat(p.letra,p.foliopago) LIKE '%$REF%') OR (p.razonreceptor LIKE '%$REF%') OR (p.razonemisor LIKE '%$REF%') ORDER BY idpago DESC";
        }

        $permisos = $this->getPermisos($idlogin);
        $div = explode("</tr>", $permisos);

        if ($div[0] == "1") {
            $numrows = $this->getNumrows($condicion);
            $page = (isset($pag) && !empty($pag)) ? $pag : 1;
            $per_page = $numreg;
            $adjacents = 4;
            $offset = ($page - 1) * $per_page;
            $total_pages = ceil($numrows / $per_page);
            $con = $condicion . " LIMIT $offset,$per_page ";
            $listapago = $this->getServicios($con);
            $finales = 0;
            foreach ($listapago as $actual) {
                $idpago = $actual['idpago'];
                $folio = $actual['foliopago'];
                $foliopago = $actual['letra'] . $folio;
                $idfiscales = $actual['pago_idfiscales'];
                $fechaemision = $actual['fechacreacion'];
                $divideF = explode("-", $fechaemision);
                $m = $divideF[1];
                $mes = $this->translateMonth($m);
                $fechaemision = $divideF[2] . '/' . $mes . '/' . $divideF[0];
                $receptor = $actual['razonreceptor'];
                $emisor = $actual['razonemisor'];
                $totalpagado = $actual['totalpagado'];
                $uuidpago = $actual['uuidpago'];
                $cancelado = $actual['cancelado'];
                $colorrow = $actual['color'];

                if ($uuidpago != "") {
                    $iconbell = "glyphicon-bell";
                    $colorB = "#34A853";
                    $titbell = "Pago Timbrado";
                    $titletimbre = "Cancelar Pago";
                    $functiontimbre = "data-toggle='modal' data-target='#modalcancelar' onclick='setCancelarPago($idpago)'";
                    if ($cancelado == '1') {
                        $btn = "btn-warning";
                        $titletimbre = "Pago Cancelado";
                        $functiontimbre = "href='./com.sine.imprimir/imprimirxml.php?p=$idpago&t=c' target='_blank'";
                        $iconbell = "glyphicon-bell";
                        $colorB = "#f0ad4e";
                        $titbell = "Pago Cancelado";
                    }
                } else {
                    $titletimbre = "Timbrar Pago";
                    $functiontimbre = "onclick=\"xml($idpago);\"";
                    $iconbell = "glyphicon-bell";
                    $colorB = "#ED495C";
                    $titbell = "Pago sin Timbrar";
                    $emisor = $this->getNombreEmisor($idfiscales);
                }

                $datos .= "<tr>
                        <td style='background-color: $colorrow;'></td>
                        <td>$foliopago</td>
                        <td>$fechaemision</td>
                        <td>$emisor</td>
                        <td>$receptor</td>
                        <td>
                            <div class='small-tooltip icon tip'>
                                <span style='color: $colorB;' class='glyphicon $iconbell'></span>
                                <span class='tiptext'>$titbell</span>
                            </div>
                        </td>
                        <td>$ " . number_format($totalpagado, 2, '.', ',') . "</td>
                        <td align='center'><div class='dropdown'>
                        <button class='button-list dropdown-toggle' title='Opciones'  type='button' data-toggle='dropdown'><span class='glyphicon glyphicon-option-vertical'></span>
                        <span class='caret'></span></button>
                        <ul class='dropdown-menu dropdown-menu-right'>";
                if ($div[1] == '1') {
                    $datos .= "<li><a onclick='editarPago($idpago);'>Editar Pago <span class='glyphicon glyphicon-edit'></span></a></li>";
                }
                if ($div[2] == '1') {
                    $datos .= "<li><a onclick=\"eliminarPago('$idpago');\">Eliminar Pago <span class='glyphicon glyphicon-remove'></span></a></li>";
                }
                $datos .= "<li><a onclick=\"imprimirpago($idpago);\">Ver Pago <span class='glyphicon glyphicon-file'></span></a></li>
                        <li><a href='./com.sine.imprimir/imprimirxml.php?p=$idpago&t=a' target='_blank'>Ver XML <span class='glyphicon glyphicon-download-alt'></span></a></li>";

                if ($div[3] == '1') {
                    $datos .= "<li><a $functiontimbre> $titletimbre <span class='glyphicon glyphicon-bell'></span></a></li>";
                }

                $datos .= "<li><a data-toggle='modal' data-target='#enviarrecibo' onclick='showCorreos($idpago);'>Enviar <span class='glyphicon glyphicon-envelope'></span></a></li>";

                if ($uuidpago != "") {
                    $datos .= "<li><a data-toggle='modal' data-target='#modal-stcfdi' onclick='checkStatusCancelacion($idpago);'>Comprobar estado del CFDI <span class='glyphicon glyphicon-ok-sign'></span></a></li>";
                }
                $datos .= "</ul>
                        </div></td>
                       </tr>";
                $finales++;
            }
            $inicios = $offset + 1;
            $finales += $inicios - 1;
            $function = "buscarPago";

            $datos .= "</tbody><tfoot><tr><th colspan='10'>Mostrando $inicios al $finales de $numrows registros " . paginate($page, $total_pages, $adjacents, $function) . "</th></tr></tfoot>";
            if ($finales == 0) {
                $datos .= "<tr><td class='text-center' colspan='10'>No se encontraron registros</td></tr>";
            }
        }
        return $datos;
    }

    public function translateMonth($m) {
        switch ($m) {
            case '01':
                $mes = 'Ene';
                break;
            case '02':
                $mes = 'Feb';
                break;
            case '03':
                $mes = "Mar";
                break;
            case '04':
                $mes = 'Abr';
                break;
            case '05':
                $mes = 'May';
                break;
            case '06':
                $mes = 'Jun';
                break;
            case '07':
                $mes = 'Jul';
                break;
            case '08':
                $mes = 'Ago';
                break;
            case '09':
                $mes = 'Sep';
                break;
            case '10':
                $mes = 'Oct';
                break;
            case '11':
                $mes = 'Nov';
                break;
            case '12':
                $mes = 'Dic';
                break;
            default :
                $mes = "";
                break;
        }
        return $mes;
    }

    public function getTimbre($idpago) {
        $datos = "";
        $uuid = $this->getTimbreAux($idpago);
        foreach ($uuid as $u) {
            $datos = $u['uuid'];
        }
        return $datos;
    }

    public function getTimbreAux($idpago) {
        $consultado = false;
        $con = new Consultas();
        $consulta = "SELECT uuid FROM timbrepagopermi WHERE idpago=:pid;";
        $val = array("pid" => $idpago);
        $consultado = $con->getResults($consulta, $val);
        return $consultado;
    }

    public function getMetodoPagoPDF($idmp) {
        $consultado = false;
        $con = new Consultas();
        $consulta = "SELECT * FROM catalogo_metodo_pago WHERE idmetodo_pago=:mid;";
        $val = array("mid" => $idmp);
        $consultado = $con->getResults($consulta, $val);
        return $consultado;
    }

    private function getCFormaPagoAux($idfp) {
        $consultado = false;
        $con = new Consultas();
        $consulta = "SELECT * FROM catalogo_pago WHERE idcatalogo_pago=:id;";
        $val = array("id" => $idfp);
        $consultado = $con->getResults($consulta, $val);
        return $consultado;
    }

    public function getCFormaPago($idfp) {
        $cforma = "";
        $datos = $this->getCFormaPagoAux($idfp);
        foreach ($datos as $actual) {
            $cforma = $actual['c_pago'] . " " . $actual['descripcion_pago'];
        }
        return $cforma;
    }

    public function getCFormaPagoXML($idfp) {
        $cforma = "";
        $datos = $this->getCFormaPagoAux($idfp);
        foreach ($datos as $actual) {
            $cforma = $actual['c_pago'];
        }
        return $cforma;
    }

    public function getMonedaPDF($idm) {
        $consultado = false;
        $con = new Consultas();
        $consulta = "SELECT * FROM catalogo_moneda WHERE idcatalogo_moneda=:idm;";
        $val = array("idm" => $idm);
        $consultado = $con->getResults($consulta, $val);
        return $consultado;
    }

    public function getUsoPDF($idu) {
        $consultado = false;
        $con = new Consultas();
        $consulta = "SELECT * FROM catalogo_usocfdi WHERE id_catalogousocfdi=:idu;";
        $val = array("idu" => $idu);
        $consultado = $consultas->getResults($consulta, $val);
        return $consultado;
    }

    public function getComprobantePDF($idc) {
        $consultado = false;
        $con = new Consultas();
        $consulta = "select * from catalogo_comprobante WHERE idcatalogo_comprobante=:cid;";
        $val = array("cid" => $idc);
        $consultado = $consultas->getResults($consulta, $val);
        return $consultado;
    }

    public function modEstado($idfactura) {
        $actualizado2 = false;
        $con = new Consultas();
        $consulta2 = "UPDATE `datosfactura` SET statusfactura=:estado WHERE iddatosfactura=:id;";
        $valores2 = array("estado" => '1',
            "id" => $idfactura);
        $actualizado2 = $con->execute($consulta2, $valores2);
    }

    public function getComplementoPago($tag) {
        $consultado = false;
        $con = new Consultas();
        $consulta = "SELECT * FROM complemento_pago WHERE tagpago=:tag ORDER BY ordcomplemento ASC;";
        $val = array("tag" => $tag);
        $consultado = $con->getResults($consulta, $val);
        return $consultado;
    }

    public function getDetallePago($tag, $comp) {
        $consultado = false;
        $con = new Consultas();
        //$consulta = "SELECT * FROM detallepago WHERE detalle_tagencabezado=:tag AND detalle_tagcomplemento=:comp ORDER BY iddetallepago;";
        $consulta = "SELECT dp.*, df.subtotal, df.subtotaliva, df.subtotalret 
                    FROM detallepago dp
                    INNER JOIN datos_factura df ON df.uuid = dp.uuiddoc
                    WHERE dp.detalle_tagencabezado=:tag
                    AND dp.detalle_tagcomplemento=:comp
                    UNION ALL                    
                    SELECT dp.*, fc.subtotal, fc.subtotaliva, fc.subtotalret 
                    FROM detallepago dp
                    INNER JOIN factura_carta fc ON fc.uuid = dp.uuiddoc
                    WHERE dp.detalle_tagencabezado=:tag
                    AND dp.detalle_tagcomplemento=:comp
                    ORDER BY iddetallepago";
        $val = array("tag" => $tag,
            "comp" => $comp);
        $consultado = $con->getResults($consulta, $val);
        return $consultado;
    }

    public function getDatosFacturacion($id) {
        $consultado = false;
        $con = new Consultas();
        $consulta = "SELECT * FROM datos_facturacion WHERE id_datos=:id";
        $val = array("id" => $id);
        $consultado = $con->getResults($consulta, $val);
        return $consultado;
    }

    private function getSaldoAux() {
        $consultado = false;
        $con = new Consultas();
        $consulta = "SELECT * FROM contador_timbres WHERE idtimbres=:id;";
        $valores = array("id" => '1');
        $consultado = $con->getResults($consulta, $valores);
        return $consultado;
    }

    private function checkSaldoAux() {
        $restantes = "0";
        $saldo = $this->getSaldoAux();
        foreach ($saldo as $actual) {
            $restantes = $actual['timbresRestantes'];
        }
        return $restantes;
    }

    private function checkCanceladosAux($idpago) {
        $datos = "";
        $existe = false;
        $saldo = $this->checkSaldoAux();
        if ($saldo == 0) {
            echo "0Su saldo de timbres se ha agotado";
            $existe = true;
        }
        if (!$existe) {
            $cfdis = $this->getDetallePago($idpago);
            foreach ($cfdis as $cfdiactual) {
                $fid = $cfdiactual['pago_idfactura'];
                $tp = $cfdiactual['type'];
                $foliofac = $cfdiactual['foliodoc'];
                $status = $this->getStatuspago($fid, $tp);

                if ($status == '3') {
                    $datos .= "0La factura $foliofac ha sido cancelada ";
                    $existe = true;
                } else if ($cfdiactual['uuiddoc'] == '') {
                    $datos .= "0La factura $foliofac no ha sido timbrada ";
                    $existe = true;
                }
            }
            echo $datos;
        }
        return $existe;
    }

    private function getStatuspago($fid, $tp) {
        $status = "";
        if ($tp == 'f') {
            $datos = $this->getFactura($fid);
        } else if ($tp == 'c') {
            $datos = $this->getFacturaCarta($fid);
        }
        foreach ($datos as $actual) {
            $status = $actual['status_pago'];
        }
        return $status;
    }

    public function checkCancelados($idpago) {
        /* $datos = false;
          $check = $this->checkCanceladosAux($idpago);
          if (!$check) { */
        $datos = $this->guardarXML($idpago);
        //}
        return $datos;
    }

    private function getRFCBancoBenAux($idfiscales, $id) {
        switch ($id) {
            case '1':
                $field = "idbanco";
                break;
            case '2':
                $field = "idbanco1";
                break;
            case '3':
                $field = "idbanco2";
                break;
            case '4':
                $field = "idbanco3";
                break;
            default:
                $field = "idbanco";
                break;
        }
        $consultado = false;
        $con = new Consultas();
        $consulta = "SELECT b.* FROM datos_facturacion d INNER JOIN catalogo_banco b ON (d.$field=b.idcatalogo_banco) WHERE d.id_datos=:idfiscales";
        $val = array("idfiscales" => $idfiscales);
        $consultado = $con->getResults($consulta, $val);
        return $consultado;
    }

    private function getRFCBancoBeneficiario($idcliente, $id) {
        $rfc = "";
        $datos = $this->getRFCBancoBenAux($idcliente, $id);
        foreach ($datos as $actual) {
            $rfc = $actual['rfcbanco'];
        }
        return $rfc;
    }

    private function getRFCBancoOrdAux($idcliente, $id) {
        switch ($id) {
            case '1':
                $field = "idbanco";
                break;
            case '2':
                $field = "idbanco1";
                break;
            case '3':
                $field = "idbanco2";
                break;
            case '4':
                $field = "idbanco3";
                break;
            default:
                $field = "idbanco";
                break;
        }
        $consultado = false;
        $con = new Consultas();
        $consulta = "SELECT b.* FROM cliente c INNER JOIN catalogo_banco b ON (c.$field=b.idcatalogo_banco) WHERE c.id_cliente=:idcliente";
        $val = array("idcliente" => $idcliente);
        $consultado = $con->getResults($consulta, $val);
        return $consultado;
    }

    private function getRFCBancoOrdenante($idcliente, $id) {
        $rfc = "";
        $datos = $this->getRFCBancoOrdAux($idcliente, $id);
        foreach ($datos as $actual) {
            $rfc = $actual['rfcbanco'];
        }
        return $rfc;
    }

    private function getMontoTotalPagos($idpago, $idmoneda) {
        $total = 0;
        $cfdis = $this->getDetallePago($idpago);
        foreach ($cfdis as $cfdiactual) {
            $monedadoc = $cfdiactual['idmonedadoc'];
            $monto = $cfdiactual['monto'];
            $total += $this->totalDivisa($monto, $idmoneda, $monedadoc);
        }
        return $total;
    }

    private function getFechaFactura($difverano, $difinvierno) {
        $hoy = date('Y-m-d');
        $hoy = date('Y-m-d', strtotime($hoy));
        $primer = date('Y-m-d', strtotime($_SESSION[sha1("primerdomingo")]));
        $ultimo = date('Y-m-d', strtotime($_SESSION[sha1("ultimodomingo")]));
        if (($hoy >= $primer) && ($hoy <= $ultimo)) {
            $tz = $difverano;
        } else {
            $tz = $difinvierno;
        }
        date_default_timezone_set("UTC");
        $utc = date('Y-m-d H:i:s');
        $gmt = strtotime($tz . ' hour', strtotime($utc));
        $gmt = date('Y-m-d H:i:s', $gmt);
        $fecha = str_replace(" ", "T", $gmt);

        return $fecha;
    }

    public function getBaseRetenciones($folio, $option){
        $base = 0;
        $con = new Consultas();
        $consulta = "";

        if($option == 1)
        {
            $consulta = "select ifnull(sum(dc.totalunitario),0) AS base 
                        from datos_factura fc 
                        inner join detalle_factura dc on dc.tagdetallef = fc.tagfactura 
                        where concat(fc.letra,fc.folio_interno_fac) like '%$folio%' 
                        and dc.traslados != '' 
                        union all 
                        select ifnull(sum(dc.totalunitario),0) AS base 
                        from factura_carta fc 
                        inner join detallefcarta dc on dc.tagdetfactura = fc.tagfactura 
                        where concat(fc.letra,fc.foliocarta) like '%$folio%' 
                        and dc.traslados != ''";
        }else if($option == 2)
        {
            $consulta = "select ifnull(sum(dc.totalunitario),0) AS base 
                        from datos_factura fc
                        inner join detalle_factura dc on dc.tagdetallef = fc.tagfactura
                        where concat(fc.letra,fc.folio_interno_fac) like '%$folio%' 
                        and dc.retenciones != ''
                        union all
                        select ifnull(sum(dc.totalunitario),0) AS base 
                        from factura_carta fc
                        inner join detallefcarta dc on dc.tagdetfactura = fc.tagfactura 
                        where concat(fc.letra,fc.foliocarta) like '%$folio%' 
                        and dc.retenciones != ''";
        }
        $stmt = $con->getResults($consulta, null);
        foreach($stmt as $rs){
            $base += $rs['base'];
        }
        return $base;
    }

    private function guardarXML($idpago) {
        $timbre = false;
        $pagos = $this->getPagoById($idpago);
        foreach ($pagos as $pagoactual) {
            $idpago = $pagoactual['idpago'];
            $serie = $pagoactual['serie'];
            $letra = $pagoactual['letra'];
            $folio = $pagoactual['foliopago'];
            $foliopago = $letra . $folio;
            $idcliente = $pagoactual['pago_idcliente'];
            $totalpagado = $pagoactual['totalpagado'];
            $rfcCliente = $pagoactual['rfcreceptor'];
            $razonCliente = $pagoactual['razonreceptor'];
            $regfcliente = $pagoactual['regfiscalreceptor'];
            $codpreceptor = $pagoactual['codpreceptor'];
            $idfiscales = $pagoactual['pago_idfiscales'];
            $totalpago = $pagoactual['totalpagado'];
            $tagpago = $pagoactual['tagpago'];
            $objimpuesto = $pagoactual['objimpuesto'];
        }


        $empresa = $this->getDatosFacturacion($idfiscales);
        foreach ($empresa as $eactual) {
            $rfcemisor = $eactual['rfc'];
            $razonemisor = $eactual['razon_social'];
            $clvreg = $eactual['c_regimenfiscal'];
            $regimen = $eactual['regimen_fiscal'];
            $codpemisor = $eactual['codigo_postal'];
            $csd = $eactual['csd'];
            $nocertificado = $eactual['numcsd'];
            $difverano = $eactual['difhorarioverano'];
            $difinvierno = $eactual['difhorarioinvierno'];
        }

        $xml = new DomDocument('1.0', 'UTF-8');
        $raiz = $xml->createElementNS('http://www.sat.gob.mx/cfd/4', 'cfdi:Comprobante');
        $raiz = $xml->appendChild($raiz);

        ////$fecha = $this->getFechaFactura($difverano, $difinvierno); Esta es la funcion que tenia David, la sustitui para poder timbrar
   
   ///// Se usa para obtener la fecha 
    $f = getdate();

        $d = $f['mday'];
        $m = $f['mon'];
        $y = $f['year'];
        $h = $f['hours']-1;
        $mi = $f['minutes'];
        $s = $f['seconds'];
        if ($d < 10) {
            $d = "0$d";
        }
        if ($m < 10) {
            $m = "0$m";
        }
        if ($h < 10) {
            $h = "0$h";
        }
        if ($mi < 10) {
            $mi = "0$mi";
        }
        if ($s < 10) {
            $s = "0$s";
        }
        $fecha = $y . '-' . $m . '-' . $d . 'T' . $h . ':' . $mi . ':' . $s;
    
    
    ///final de obtener la fecha

        $raiz->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $raiz->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'schemaLocation', 'http://www.sat.gob.mx/cfd/4 http://www.sat.gob.mx/sitio_internet/cfd/4/cfdv40.xsd http://www.sat.gob.mx/Pagos20 http://www.sat.gob.mx/sitio_internet/cfd/Pagos/Pagos20.xsd');
        $raiz->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:pago20', 'http://www.sat.gob.mx/Pagos20');
        $raiz->setAttribute('Version', '4.0');
        $raiz->setAttribute('Serie', $serie);
        $raiz->setAttribute('Folio', $foliopago);
        $raiz->setAttribute('Fecha', $fecha);
        $raiz->setAttribute('SubTotal', '0');
        $raiz->setAttribute('Moneda', 'XXX');
        $raiz->setAttribute('Total', '0');
        $raiz->setAttribute('TipoDeComprobante', 'P');
        $raiz->setAttribute('Exportacion', '01');
        $raiz->setAttribute('LugarExpedicion', $codpemisor);
        $raiz->setAttribute('NoCertificado', $nocertificado);
        //Convertir certificado a B64 con openssl enc -in "CSD/00001000000407565367.cer" -a -A -out "cerB64.txt" 
        $raiz->setAttribute('Certificado', $csd);

        $emisor = $xml->createElement('cfdi:Emisor');
        $emisor = $raiz->appendChild($emisor);
        $emisor->setAttribute('Rfc', $rfcemisor);
        $emisor->setAttribute('Nombre', strtoupper($razonemisor));
        $emisor->setAttribute('RegimenFiscal', $clvreg);

        $receptor = $xml->createElement('cfdi:Receptor');
        $receptor = $raiz->appendChild($receptor);
        $receptor->setAttribute('Rfc', $rfcCliente);
        $receptor->setAttribute('Nombre', strtoupper($razonCliente));
        $receptor->setAttribute('DomicilioFiscalReceptor', $codpreceptor);
        $divreg = explode("-", $regfcliente);
        $receptor->setAttribute('RegimenFiscalReceptor', $divreg[0]);
        $receptor->setAttribute('UsoCFDI', 'CP01');

        $conceptos = $xml->createElement('cfdi:Conceptos');
        $conceptos = $raiz->appendChild($conceptos);
        $concepto = $xml->createElement('cfdi:Concepto');
        $concepto = $conceptos->appendChild($concepto);
        $concepto->setAttribute('ClaveProdServ', '84111506');
        $concepto->setAttribute('Cantidad', '1');
        $concepto->setAttribute('ClaveUnidad', 'ACT');
        $concepto->setAttribute('Descripcion', 'Pago');
        $concepto->setAttribute('ValorUnitario', '0');
        $concepto->setAttribute('Importe', '0');
        $concepto->setAttribute('ObjetoImp', '01');

        $complemento = $xml->createElement('cfdi:Complemento');
        $complemento = $raiz->appendChild($complemento);
        $pagos = $xml->createElement('pago20:Pagos');
        $pagos = $complemento->appendChild($pagos);
        $pagos->setAttribute('Version', '2.0');

        $totales = $xml->createElement('pago20:Totales');
        $totales = $pagos->appendChild($totales);
        //$montototalP = $this->getMontoTotalPagos($idpago, $idmoneda);
        /* if ($moneda != 'MXN') {
          $montototalP = number_format($montototalP, 2) * $tcambio;
          $tcambio = bcdiv($tcambio, '1', 6);
          } */

        $div_obj_imp = explode("-", $objimpuesto);

        if($div_obj_imp[0] == '01'){

            $totales->setAttribute('MontoTotalPagos', number_format($totalpago, 2, '.', ''));

        }else if($div_obj_imp[0] == '02'){

            /*$subTotlGral =  bcdiv(($totalpago / 1.16), '1', 4);
            $subIvaGral =  bcdiv(($subTotlGral * 0.16), '1', 4);
            $retencionIVA =  bcdiv(($subTotlGral * 0.04), '1', 4);*/
            $totalesCFDIS = $this->getTotalesImpuestos($tagpago, 2);
            $divTotales = explode("</tr>",$totalesCFDIS);
            $subTotlGral = $divTotales[0];
            $subIvaGral = $divTotales[1];
            $retencionIVA = $divTotales[2];
             
            
            $totales->setAttribute('MontoTotalPagos', number_format($totalpago, 2, '.', ''));
            /*$totales->setAttribute('TotalTrasladosBaseIVA16', number_format(round($subTotlGral, 2), 2, '.', ''));
            $totales->setAttribute('TotalTrasladosImpuestoIVA16', number_format(round($subIvaGral, 2), 2, '.', ''));

            if($retencionIVA > 0){
                $totales->setAttribute('TotalRetencionesIVA', number_format(round($retencionIVA, 2), 2, '.', ''));
            }  */         
            $subTotlGral = 0;

        }
        

        $complementos = $this->getComplementoPago($tagpago);
        foreach ($complementos as $actualcfdi) {
            $idformapago = $actualcfdi['complemento_idformapago'];
            $cformapago = $this->getCFormaPagoXML($idformapago);
            $idmoneda = $actualcfdi['complemento_idmoneda'];
            $tcambio = $actualcfdi['complemento_tcambio'];
            $cmoneda = $this->getCMoneda($idmoneda);
            $fechapago = $actualcfdi['complemento_fechapago'];
            $horapago = $actualcfdi['complemento_horapago'];
            $cuentaord = $actualcfdi['complemento_idcuentaOrd'];
            $cuentabnf = $actualcfdi['complemento_idcuentaBnf'];
            $numtransaccion = $actualcfdi['complemento_notransaccion'];
            $totalcomp = $actualcfdi['total_complemento'];
            $tagcomplemento = $actualcfdi['tagcomplemento'];

            $pago = $xml->createElement('pago20:Pago');
            $pago = $pagos->appendChild($pago);
            $fechapago2 = $fechapago . 'T' . $horapago . ':00';
            $pago->setAttribute('FechaPago', $fechapago2);
            $pago->setAttribute('FormaDePagoP', $cformapago);
            $pago->setAttribute('MonedaP', $cmoneda);
            $pago->setAttribute('TipoCambioP', $tcambio);
            $pago->setAttribute('Monto', number_format($totalcomp, 2, '.', ''));
            if ($cuentaord != '0') {
                $banco = $this->getRFCBancoOrdenante($idcliente, $cuentaord);
                $pago->setAttribute('RfcEmisorCtaOrd', $banco);
            }

            if ($cuentabnf != '0') {
                $bancoB = $this->getRFCBancoBeneficiario($idfiscales, $cuentabnf);
                $pago->setAttribute('RfcEmisorCtaBen', $bancoB);
            }

            $bandera_tras = 0;
            $bandera_ret = 0;
            $base_tras = 0;
            $base_ret = 0;
            $imp_tras = 0;
            $imp_ret = 0; 

            $cfdis = $this->getDetallePago($tagpago, $tagcomplemento);
            foreach ($cfdis as $cfdiactual) {
                $noparcialidad = $cfdiactual['noparcialidad'];
                $monto = $cfdiactual['monto'];
                $montoanterior = $cfdiactual['montoanterior'];
                $montoinsoluto = $cfdiactual['montoinsoluto'];
                $folioF = $cfdiactual['foliodoc'];
                $uuid = $cfdiactual['uuiddoc'];
                $idmonedadoc = $cfdiactual['idmonedadoc'];
                $monedaP = $this->getCMoneda($idmonedadoc);
                $tipocambioF = $cfdiactual['tcambiodoc'];

                $subtot = $cfdiactual['subtotal'];
                $traslado = $cfdiactual['subtotaliva'];
                $retencio = $cfdiactual['subtotalret'];

                if ($tcambio != $tipocambioF) {
                    $tipocambioF = bcdiv($tcambio, '1', 6);
                }

                if ($idmoneda == $idmonedadoc) {
                    $tipocambioF = '1';
                }

                $doctorel = $xml->createElement('pago20:DoctoRelacionado');
                $doctorel = $pago->appendChild($doctorel);
                $doctorel->setAttribute('IdDocumento', $uuid);
                $doctorel->setAttribute('Folio', $folioF);
                $doctorel->setAttribute('MonedaDR', $monedaP);
                $doctorel->setAttribute('EquivalenciaDR', $tipocambioF);
                //$doctorel->setAttribute('MetodoDePagoDR', $metodopago);
                $doctorel->setAttribute('NumParcialidad', $noparcialidad);
                $doctorel->setAttribute('ImpSaldoAnt', $montoanterior);
                $doctorel->setAttribute('ImpPagado', $monto);
                $doctorel->setAttribute('ImpSaldoInsoluto', $montoinsoluto);
                $doctorel->setAttribute('ObjetoImpDR', $div_obj_imp[0]);
                if($div_obj_imp[0] == '02'){
                    $nodoImpuestos = $xml->createElement('pago20:ImpuestosDR');
                    $nodoImpuestos = $doctorel->appendChild($nodoImpuestos);

                    if($retencio != ""){
                        $bandera_ret = 1;
                        $subnodoRetencion = $xml->createElement('pago20:RetencionesDR');
                        $subnodoRetencion = $nodoImpuestos->appendChild($subnodoRetencion);
                        
                        $divRetencion = explode('<impuesto>',$retencio);
                        foreach($divRetencion as $arrayretnecion){
                            $divret = explode('-',$arrayretnecion);

                            /*$sub_monto = bcdiv(($monto / 1.16), '1', 4);
                            $total = round($sub_monto,2);
                            $impuesto = round(bcdiv(($total * $divret[1]), '1', 4),2);
                            $porcentaje =  $divret[1] * 100;
                            $restante = 100 - $porcentaje;
                            $impuesto = round((($monto * $porcentaje) / $restante),2);
                            $total = round(($monto + $impuesto), 2);*/
                            $subtot = $this->getBaseRetenciones($folioF, 2);
                            $total = $subtot;
                            $impuesto = $divret[0];

                            $base_ret += $total;
                            $imp_ret += $impuesto;
                            
                            $hijoretencion = $xml->createElement('pago20:RetencionDR');
                            $hijoretencion = $subnodoRetencion->appendChild($hijoretencion);
                            $hijoretencion->setAttribute('BaseDR', $total );
                            $hijoretencion->setAttribute('ImpuestoDR', '00'.$divret[2] );
                            $hijoretencion->setAttribute('TipoFactorDR', 'Tasa' );
                            $hijoretencion->setAttribute('TasaOCuotaDR', bcdiv($divret[1],'1',6) );
                            $hijoretencion->setAttribute('ImporteDR', round($impuesto,2) );
                        }
                    }
    
                    if($traslado != ""){
                        $bandera_tras = 1;
                        $subnodoTraslado = $xml->createElement('pago20:TrasladosDR');
                        $subnodoTraslado = $nodoImpuestos->appendChild($subnodoTraslado);
                        
                        $divTraslado = explode('<impuesto>',$traslado);
                        foreach($divTraslado as $arraytraslado){
                            $divtras = explode('-',$arraytraslado);

                            /*$sub_monto = bcdiv(($monto / 1.16), '1', 4);
                            $sub_monto_iva = bcdiv(($sub_monto * 0.16), '1', 4);*/
                            $subtot = $this->getBaseRetenciones($folioF, 1);
                            $sub_monto = $subtot;
                            $sub_monto_iva = $divtras[0];

                            $base_tras += $sub_monto;
                            $imp_tras += $sub_monto_iva;

                            $subTotlGral += $sub_monto;
                            
                            $hijotraslado = $xml->createElement('pago20:TrasladoDR');
                            $hijotraslado = $subnodoTraslado->appendChild($hijotraslado);
                            $hijotraslado->setAttribute('BaseDR', round($sub_monto, 2) );
                            $hijotraslado->setAttribute('ImpuestoDR', '00'.$divtras[2] );
                            $hijotraslado->setAttribute('TipoFactorDR', 'Tasa' );
                            $hijotraslado->setAttribute('TasaOCuotaDR', bcdiv($divtras[1], '1', 6) );
                            $hijotraslado->setAttribute('ImporteDR',  $sub_monto_iva );
                        }
                    }
    
                    
                }
            }

            if( $div_obj_imp[0] == '02'){
                $pagoImpuestos = $xml->createElement('pago20:ImpuestosP');
                $pagoImpuestos = $pago->appendChild($pagoImpuestos);
                
                if( $bandera_ret > 0){
                    $pagoRetenciones = $xml->createElement('pago20:RetencionesP');
                    $pagoRetenciones = $pagoImpuestos->appendChild($pagoRetenciones);
    
                    $retencionesPago = $xml->createElement('pago20:RetencionP');
                    $retencionesPago = $pagoRetenciones->appendChild($retencionesPago);
                    //$retencionesPago->setAttribute('BaseP', round($base_ret, 2));
                    $retencionesPago->setAttribute('ImpuestoP', "00".$divret[2]);
                    //$retencionesPago->setAttribute('TipoFactorP', 'Tasa');
                    //$retencionesPago->setAttribute('TasaOCuotaP', bcdiv($divret[1],'1',6));
                    $retencionesPago->setAttribute('ImporteP', bcdiv(round($imp_ret, 2),'1',2));
                }
                
                if( $bandera_tras > 0){
                    $pagoTraslados = $xml->createElement('pago20:TrasladosP');
                    $pagoTraslados = $pagoImpuestos->appendChild($pagoTraslados);
    
                    $trasladosPago = $xml->createElement('pago20:TrasladoP');
                    $trasladosPago = $pagoTraslados->appendChild($trasladosPago);
                    $trasladosPago->setAttribute('BaseP', round($base_tras, 2));
                    $trasladosPago->setAttribute('ImpuestoP', "00".$divtras[2]);
                    $trasladosPago->setAttribute('TipoFactorP', 'Tasa');
                    $trasladosPago->setAttribute('TasaOCuotaP', "0.160000");
                    $trasladosPago->setAttribute('ImporteP', bcdiv(round($imp_tras, 2),'1',2));
    
                }

                
                $totales->setAttribute('TotalTrasladosBaseIVA16', number_format(round($subTotlGral, 2), 2, '.', ''));
                $totales->setAttribute('TotalTrasladosImpuestoIVA16', number_format(round($subIvaGral, 2), 2, '.', ''));

                if($retencionIVA > 0){
                    $totales->setAttribute('TotalRetencionesIVA', number_format(round($retencionIVA, 2), 2, '.', ''));
                }  
            }
        }

        $sello = $this->SelloXML($xml->saveXML(), $rfcemisor);
        $obj = json_decode($sello);
        $xml2 = new DOMDocument("1.0", "UTF-8");
        $xml2->loadXML($xml->saveXML());
        $c = $xml2->getElementsByTagNameNS('http://www.sat.gob.mx/cfd/4', 'Comprobante')->item(0);
        $c->setAttribute('Sello', $obj->sello);
        $doc = "../XML/XML2.xml";
        $xml2->save($doc);
        $timbre = $this->timbradoPago($xml2->saveXML(), $idpago, $rfcemisor, $razonemisor, $clvreg, $regimen, $codpemisor);
        return $timbre;
        //return "XML generado";
    }

    function SelloXML($doc, $rfc) {
        $xmlFile = $doc;
        $carpeta = '../temporal/' . $rfc . '/';
        $xslFile = "../vendor/recursos/cadenaoriginal_4_0.xslt";
        $xml = new DOMDocument("1.0", "UTF-8");
        $xml->loadXML($xmlFile);
        $xsl = new DOMDocument();
        $xsl->load($xslFile);
        $proc = new XSLTProcessor;
        $proc->importStyleSheet($xsl);
        $cadenaOriginal = $proc->transformToXML($xml);
        $fichero = "../vendor/recursos/cadenaOriginal.txt";
        file_put_contents($fichero, $cadenaOriginal, LOCK_EX);
        $params = array(
            "cadenaOriginal" => "../vendor/recursos/cadenaOriginal.txt",
            //Archivo key pem: pkcs8 -inform DET -in CSD/cer.key -passin pass:12345678a -out llaveprivada.pem
            "archivoKeyPem" => $carpeta . 'keyPEM.pem',
            //archivo cer pem: x509 -inform der -in CSD/cer.cer -out certificado.pem
            "archivoCerPem" => $carpeta . 'csdPEM.pem'
        );
        try {
            $result = Sellar::ObtenerSello($params);
            return $result;
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
    }

    private function timbradoPago($doc, $idpago, $rfcemisor, $razonemisor, $clvreg, $regimen, $codpemisor) {
        $swaccess = $this->getSWAccess();
        $div = explode("</tr>", $swaccess);
        $url = $div[0];
        $token = $div[1];

        $params = array(
            "url" => $url,
            "token" => $token
        );

        try {
            header("Content-type: text/plain");
            $stamp = StampService::Set($params);
            $result = $stamp::StampV4($doc);
            if ($result->status == "error") {
                return '0' . $result->message . " " . $result->messageDetail;
            } else if ($result->status == "success") {
                $guardar = $this->guardarTimbrePago($result, $idpago, $rfcemisor, $razonemisor, $clvreg, $regimen, $codpemisor);
                //var_dump($result);
                return $guardar;
            }
        } catch (Exception $e) {
            header("Content-type: text/plain");
            echo $e->getMessage();
        }
    }

    private function guardarTimbrePago($result, $idpago, $rfcemisor, $razonemisor, $clvreg, $regimen, $codpemisor) {
        $actualizado = false;
        $con = new Consultas();
        $consulta = "UPDATE `pagos` SET rfcemisor=:rfc, razonemisor=:rzemisor, clvregemisor=:creg, regfiscalemisor=:regimen, codpemisor=:cp, cadenaoriginalpago=:cadena, nocertsatpago=:certSAT, nocertcfdipago=:certCFDI, uuidpago=:uuid, sellosatpago=:selloSAT, sellocfdipago=:selloCFDI, fechatimbrado=:fechatimbrado, qrcode=:qrcode, cfdipago=:cfdipago WHERE idpago=:id;";
        $valores = array("rfc" => $rfcemisor,
            "rzemisor" => $razonemisor,
            "creg" => $clvreg,
            "regimen" => $regimen,
            "cp" => $codpemisor,
            "cadena" => $result->data->cadenaOriginalSAT,
            "certSAT" => $result->data->noCertificadoSAT,
            "certCFDI" => $result->data->noCertificadoCFDI,
            "uuid" => $result->data->uuid,
            "selloSAT" => $result->data->selloSAT,
            "selloCFDI" => $result->data->selloCFDI,
            "fechatimbrado" => $result->data->fechaTimbrado,
            "qrcode" => $result->data->qrCode,
            "cfdipago" => $result->data->cfdi,
            "id" => $idpago);
        $actualizado = $con->execute($consulta, $valores);
        $this->updateTimbres();
        return '+Timbre Guardado';
    }

    private function updateTimbres() {
        $actualizado = false;
        $con = new Consultas();
        $consulta = "UPDATE `contador_timbres` SET  timbresUtilizados=timbresUtilizados+1, timbresRestantes=timbresRestantes-1 WHERE idtimbres=:idtimbres;";
        $valores = array("idtimbres" => '1');
        $actualizado = $con->execute($consulta, $valores);
        return $actualizado;
    }

    function generarXML($filename, $content) {
        $f = fopen($filename, "w");
        fwrite($f, pack("CCC", 0xef, 0xbb, 0xbf));
        fwrite($f, $content);
        fclose($f);
    }

    private function getUUID($idfactura) {
        $datos = "";
        $uuid = $this->getUUIDAux($idfactura);
        foreach ($uuid as $u) {
            $uuid = $u['uuidpago'];
            $folio = $u['letra'] . $u['foliopago'];
            $rfc = $u['rfc'];
            $pass = $u['passcsd'];
            $csd = $u['csd'];
            $key = $u['keyb64'];
            $datos = "$uuid</tr>$folio</tr>$rfc</tr>$pass</tr>$csd</tr>$key";
        }
        return $datos;
    }

    private function getUUIDAux($idpago) {
        $consultado = false;
        $con = new Consultas();
        $consulta = "SELECT p.uuidpago, p.letra, p.foliopago, df.rfc, df.passcsd, df.keyb64,df.csd FROM pagos p INNER JOIN datos_facturacion df ON (p.pago_idfiscales=df.id_datos) WHERE p.idpago=:id;";
        $val = array("id" => $idpago);
        $consultado = $con->getResults($consulta, $val);
        return $consultado;
    }

    function cancelarTimbre($idpago, $motivo, $reemplazo) {
        $swaccess = $this->getSWAccess();
        $div = explode("</tr>", $swaccess);
        $url = $div[0];
        $token = $div[1];

        $get = $this->getUUID($idpago);
        $divideU = explode("</tr>", $get);
        $uuid = $divideU[0];
        $rfc = $divideU[2];
        $pass = $divideU[3];
        $csd = $divideU[4];
        $key = $divideU[5];

        if ($motivo == '01') {
            $params = array(
                "url" => $url,
                "token" => $token,
                "uuid" => $uuid,
                "password" => $pass,
                "rfc" => $rfc,
                "motivo" => $motivo,
                "foliosustitucion" => $reemplazo,
                "cerB64" => $csd,
                "keyB64" => $key
            );
        } else {
            $params = array(
                "url" => $url,
                "token" => $token,
                "uuid" => $uuid,
                "password" => $pass,
                "rfc" => $rfc,
                "motivo" => $motivo,
                "cerB64" => $csd,
                "keyB64" => $key
            );
        }

        try {
            header('Content-type: text/plain');
            $cancelationService = CancelationService::Set($params);
            $result = $cancelationService::CancelationByCSD();
            if ($result->status == "error") {
                echo '0';
                return var_dump($result);
            } else if ($result->status == "success") {
                $guardar = $this->cancelarPago($idpago, $result->data->acuse);
                var_dump($result);
                return $guardar;
            }
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
        return var_dump($result);
    }

    private function cancelarPago($idpago, $cfdi) {
        $actualizado = false;
        $con = new Consultas();
        $consulta = "UPDATE `pagos` SET cancelado=:estado, cfdicancel=:cfdi WHERE idpago=:id;);";
        $valores = array("id" => $idpago,
            "estado" => '1',
            "cfdi" => $cfdi);
        $actualizado = $con->execute($consulta, $valores);
        return $actualizado;
    }

    private function getConfigMailAux() {
        $consultado = false;
        $con = new Consultas();
        $consulta = "SELECT * FROM correoenvio WHERE chuso2=:id;";
        $valores = array("id" => '1');
        $consultado = $con->getResults($consulta, $valores);
        return $consultado;
    }

    private function getConfigMail() {
        $datos = "";
        $get = $this->getConfigMailAux();
        foreach ($get as $actual) {
            $correo = $actual['correo'];
            $pass = $actual['password'];
            $remitente = $actual['remitente'];
            $host = $actual['host'];
            $puerto = $actual['puerto'];
            $seguridad = $actual['seguridad'];
            $datos = "$correo</tr>$pass</tr>$remitente</tr>$host</tr>$puerto</tr>$seguridad";
        }
        return $datos;
    }

    public function mail($sm) {
        require_once '../com.sine.controlador/ControladorConfiguracion.php';
        $cc = new ControladorConfiguracion();
        $body = $cc->getMailBody('2');
        $divM = explode("</tr>", $body);
        $asunto = $divM[1];
        $saludo = $divM[2];
        $msg = $divM[3];
        $logo = $divM[4];

        $config = $this->getConfigMail();
        if ($config != "") {
            $div = explode("</tr>", $config);
            $correoenvio = $div[0];
            $pass = $div[1];
            $remitente = $div[2];
            $host = $div[3];
            $puerto = $div[4];
            $seguridad = $div[5];

            $mail = new PHPMailer;
            $mail->isSMTP();
            $mail->Mailer = 'smtp';
            $mail->SMTPAuth = true;
            $mail->Host = $host;
            $mail->Port = $puerto;
            $mail->SMTPSecure = $seguridad;
            $mail->Username = $correoenvio;
            $mail->Password = $pass;
            $mail->From = $correoenvio;
            $mail->FromName = $remitente;

            $mail->Subject = utf8_decode($asunto);
            $mail->isHTML(true);
            $mail->Body = $this->bodyMail($asunto, $saludo, $sm->getRazonsocial(), $msg, $logo);

            if ($sm->getChmail1() == '1') {
                $mail->addAddress($sm->getMailalt1());
            }
            if ($sm->getChmail2() == '1') {
                $mail->addAddress($sm->getMailalt2());
            }
            if ($sm->getChmail3() == '1') {
                $mail->addAddress($sm->getMailalt3());
            }
            if ($sm->getChmail4() == '1') {
                $mail->addAddress($sm->getMailalt4());
            }
            if ($sm->getChmail5() == '1') {
                $mail->addAddress($sm->getMailalt5());
            }
            if ($sm->getChmail6() == '1') {
                $mail->addAddress($sm->getMailalt6());
            }

            $mail->addStringAttachment($sm->getPdfstring(), $sm->getFolio() . "_" . $sm->getRfcemisor() . "_" . $sm->getUuid() . ".pdf");
            if ($sm->getCfdistring() != "") {
                $mail->addStringAttachment($sm->getCfdistring(), $sm->getFolio() . "_" . $sm->getRfcemisor() . "_" . $sm->getUuid() . ".xml");
            }
            if (!$mail->send()) {
                echo '0No se envio el mensaje';
                echo '0Mailer Error: ' . $mail->ErrorInfo;
            } else {
                return '1Se ha enviado el pago';
            }
        } else {
            return "0No se ha configurado un correo de envio para esta area";
        }
    }

    private function bodyMail($asunto, $saludo, $nombre, $msg, $logo) {
        $archivo = "../com.sine.dao/configuracion.ini";
        $ajustes = parse_ini_file($archivo, true);
        if (!$ajustes) {
            throw new Exception("No se puede abrir el archivo " . $archivo);
        }
        $rfcfolder = $ajustes['cron']['rfcfolder'];

        $txt = str_replace("<corte>", "</p><p style='font-size:18px; text-align: justify;'>", $msg);
        $message = "<html>
                        <body>
                            <table width='100%' bgcolor='#e0e0e0' cellpadding='0' cellspacing='0' border='0' style='border-radius: 25px;'>
                                <tr>
                                    <td>
                                        <table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' style='max-width:650px; border-radius: 20px; background-color:#fff; font-family:sans-serif;'>
                                            <thead>
                                                <tr height='80'>
                                                    <th align='left' colspan='4' style='padding: 6px; background-color:#f5f5f5; border-radius: 20px; border-bottom:solid 1px #bdbdbd;' ><img src='https://q-ik.mx/$rfcfolder/img/$logo' height='100px'/></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr align='center' height='10' style='font-family:sans-serif; '>
                                                    <td style='background-color:#09096B; text-align:center; border-radius: 5px;'></td>
                                                </tr>
                                                <tr>
                                                    <td colspan='4' style='padding:15px;'>
                                                        <h1>$asunto</h1>
                                                        <p style='font-size:20px;'>$saludo $nombre</p>
                                                        <hr/>
                                                        <p style='font-size:18px; text-align: justify;'>$txt</p>
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </body>
                    </html>";
        return $message;
    }

    public function getDireccionCliente($idcliente, $fid) {
        $direccion = "";
        $datos = $this->getDatosCliente($idcliente, $fid);
        foreach ($datos as $actual) {
            $codpostal = $actual['codigo_postal'];
            $cpreceptor = $actual['codpreceptor'];
            $direccion = "CP. $cpreceptor";
            if ($codpostal == $cpreceptor) {
                $calle = $actual['calle'];
                $numext = $actual['numero_exterior'];
                $localidad = $actual['localidad'];
                $idmunicipio = $actual['idmunicipio'];
                $idestadodir = $actual['idestado'];

                $next = "";
                if ($numext != "") {
                    $next = " #$numext";
                }

                $col = "";
                if ($localidad != "") {
                    $col = ", Colonia: $localidad";
                }

                $cp = "";
                if ($codpostal != "" && $codpostal != "0") {
                    $cp = " CP. $codpostal";
                }

                $municipio = "";
                if ($idmunicipio != "0") {
                    $muni = $this->getMunicipioAux($idmunicipio);
                    $municipio = ", $muni";
                }

                $estadodir = "";
                if ($idestadodir != "0") {
                    $est = $this->getEstadoAux($idestadodir);
                    $estadodir = ", $est";
                }

                $direccion = $calle . $next . $col . $cp . $municipio . $estadodir;
            }
        }
        return $direccion;
    }

    private function getDatosCliente($idcliente, $fid) {
        $consultado = false;
        $con = new Consultas();
        $consulta = "SELECT c.*, p.codpreceptor FROM cliente c INNER JOIN pagos p ON (c.id_cliente=p.pago_idcliente) WHERE id_cliente=:cid AND p.idpago=:fid;";
        $val = array("cid" => $idcliente,
            "fid" => $fid);
        $consultado = $con->getResults($consulta, $val);
        return $consultado;
    }

    public function checkStatusCFDI($idpago) {
        $datos = false;
        $factura = $this->getPagoById($idpago);
        foreach ($factura as $actual) {
            $emisor = $actual['rfcemisor'];
            $receptor = $actual['rfcreceptor'];
            $uuid = $actual['uuidpago'];
            $cfdistring = $actual['cfdipago'];
        }

        $xml = simplexml_load_string($cfdistring);
        $comprobante = $xml->xpath('/cfdi:Comprobante');
        $attr = $comprobante[0]->attributes();
        $sello = $attr['Sello'];
        $subsello = substr($sello, -8);
        //$soapUrl = "https://consultaqr.facturaelectronica.sat.gob.mx/ConsultaCFDIService.svc";
        $soapUrl = "https://pruebacfdiconsultaqr.cloudapp.net/ConsultaCFDIService.svc";
        $consultaCfdi = consultaCfdiSAT::ServicioConsultaSAT($soapUrl, $emisor, $receptor, 0, $uuid, $subsello);
        $codstatus = $consultaCfdi->CodigoEstatus;
        $estado = $consultaCfdi->Estado;
        $cancelable = $consultaCfdi->EsCancelable;
        $statusCancelacion = $consultaCfdi->EstatusCancelacion;

        if (is_array($consultaCfdi->EsCancelable)) {
            $cancelable = "";
        }

        if (is_array($consultaCfdi->EstatusCancelacion)) {
            $statusCancelacion = "";
        }
        $reset = "";
        if ($statusCancelacion === "Solicitud rechazada") {
            $reset = "<button class='button-modal' onclick='resetCfdiPago($idpago)' id='btn-reset-cfdi'>Restaurar Pago <span class='glyphicon glyphicon-repeat'></span></button>";
        }
        $datos = "$codstatus</tr>$estado</tr>$cancelable</tr>$statusCancelacion</tr>$reset";
        return $datos;
    }

    public function estadoPago($idpago) {
        $actualizado = false;
        $con = new Consultas();
        $consulta = "UPDATE `pagos` SET cfdicancel=:cfdi, cancelado=:estado WHERE idpago=:id;";
        $valores = array("id" => $idpago,
            "estado" => '0',
            "cfdi" => '');
        $actualizado = $con->execute($consulta, $valores);
        return $actualizado;
    }

    public function exportarComplemento($idformapago, $idmoneda, $tcambio, $idfactura, $folio, $sid) {
        $datos = "";
        $tagcomp = $this->genTag();

        $datos .= "<button id='tab-$tagcomp' class='tab-pago sub-tab-active' data-tab='$tagcomp' data-ord='1' name='tab-complemento' >Complemento 1 &nbsp; <span data-tab='$tagcomp' type='button' class='close-button' aria-label='Close'><span aria-hidden='true'>&times;</span></span></button>
                <cut>
                <div id='complemento-$tagcomp' class='sub-div'>
                <div class='row'>
            <div class='col-md-4'>
                <label class='label-form text-right' for='forma-$tagcomp'>Forma de Pago</label> <label class='mark-required text-right'>*</label>
                <div class='form-group'>
                        <select class='form-control text-center input-form' id='forma-$tagcomp' name='forma-$tagcomp' onchange='disableCuenta();'>
                            <option value='' id='default-fpago-$tagcomp'>- - - -</option>
                            <optgroup id='forma-pago-$tagcomp' class='cont-fpago-$tagcomp text-left'>" .
                $this->opcionesFormaPago($idformapago)
                . "</optgroup>
                        </select>
                    <div id='forma-$tagcomp-errors'></div>
                </div>
            </div>

            <div class='col-md-2'>
                <label class='label-form text-right' for='moneda-$tagcomp'>Moneda de Pago</label> <label class='mark-required text-right'>*</label>
                <div class='form-group'>
                    <select class='form-control text-center input-form' id='moneda-$tagcomp' name='moneda-$tagcomp' onchange='getTipoCambio(); loadTablaCFDI();'>
                        <option value='' id='default-moneda-$tagcomp'>- - - -</option>
                        <optgroup id='mpago-$tagcomp' class='contmoneda-$tagcomp text-left'>" .
                $this->opcionesMoneda($idmoneda)
                . "</optgroup>
                    </select>
                    <div id='moneda-$tagcomp-errors'></div>
                </div>
            </div>

            <div class='col-md-2'>
                <label class='label-form text-right' for='cambio-$tagcomp'>Tipo de Cambio</label>
                <div class='form-group'>
                    <input type='text' class='form-control input-form' id='cambio-$tagcomp' placeholder='Tipo de cambio de Moneda' disabled='' value='$tcambio'>
                    <div id='cambio-$tagcomp-errors'></div>
                </div>
            </div>

            <div class='col-md-4'>
                <label class='label-form text-right' for='fecha-$tagcomp'>Fecha de Pago</label> <label class='mark-required text-right'>*</label>
                <div class='form-group'>
                    <input class='form-control text-center input-form' id='fecha-$tagcomp' name='fecha-$tagcomp' type='date' />
                    <div id='fecha-$tagcomp-errors'></div>
                </div>
            </div>
        </div>

        <div class='row'>
            <div class='col-md-4'>
                <label class='label-form text-right' for='hora-$tagcomp'>Hora de Pago</label> <label class='mark-required text-right'>*</label>
                <div class='form-group'>
                    <input class='form-control text-center input-form' id='hora-$tagcomp' name='hora-$tagcomp' type='time'/>
                    <div id='hora-$tagcomp-errors'></div>
                </div>
            </div>

            <div class='col-md-4'>
                <label class='label-form text-right' for='uenta-$tagcomp'>Cuenta Ordenante (Cliente)</label>
                <div class='form-group'>
                    <select class='form-control text-center input-form' id='cuenta-$tagcomp' name='cuenta-$tagcomp' disabled>
                        <option value='' id='default-cuenta-$tagcomp'>- - - -</option>
                        <optgroup id='ordenante-$tagcomp' class='contenedor-cuenta-$tagcomp text-left'></optgroup>
                    </select>
                    <div id='cuenta-$tagcomp-errors'></div>
                </div>
            </div>

            <div class='col-md-4'>
                <label class='label-form text-right' for='benef-$tagcomp'>Cuenta Beneficiario (Mis Cuentas)</label>
                <div class='form-group'>
                    <select class='form-control text-center input-form' id='benef-$tagcomp' name='benef-$tagcomp' disabled>
                        <option value='' id='default-benef-$tagcomp'>- - - -</option>
                        <optgroup id='beneficiario-$tagcomp' class='contenedor-beneficiario-$tagcomp text-left'></optgroup>
                    </select>
                    <div id='benef-$tagcomp-errors'></div>
                </div>
            </div>
        </div>

        <div class='row'>
            <div class='col-md-4'>
                <label class='label-form text-right' for='transaccion-$tagcomp'>N° de Transaccion</label>
                <div class='form-group'>
                    <input class='form-control text-center input-form' id='transaccion-$tagcomp' name='transaccion-$tagcomp' placeholder='N° de Transaccion' type='number' disabled />
                    <div id='transaccion-$tagcomp-errors'>
                    </div>
                </div>
            </div>
        </div>

        <div class='row'>
            <div class='col-md-12'>
                <div class='new-tooltip icon tip'> 
                    <label class='label-sub' for='fecha-creacion'>CFDIS RELACIONADOS </label> <span class='glyphicon glyphicon-question-sign'></span>
                    <span class='tiptext'>Para agregar una factura realice la b&uacute;squeda por Folio de la factura y se cargaran los datos, la b&uacute;squeda se limita a las facturas asignadas al cliente seleccionado en el campo Cliente.</span>
                </div>
            </div>
        </div>

        <div class='row scrollX'>
            <div class='col-md-12'>
                <table class='table tab-hover table-condensed table-responsive table-row thead-form'>
                    <tbody >
                        <tr>
                            <td colspan='2'>
                                <label class='label-form text-right' for='factura-$tagcomp'>Folio Factura</label>
                                <input id='id-factura-$tagcomp' type='hidden' value='$idfactura' /><input class='form-control text-center input-form' id='factura-$tagcomp' name='factura-$tagcomp' placeholder='Factura' type='text' oninput='aucompletarFactura();' value='Factura-$folio'/>
                            </td>
                            <td colspan='2'>
                                <label class='label-form text-right' for='uuid-$tagcomp'>UUID Factura</label>
                                <input class='form-control cfdi text-center input-form' id='uuid-$tagcomp' name='uuid-$tagcomp' placeholder='UUID del cfdi' type='text'/>
                            </td>
                            <td>
                                <label class='label-form text-right' for='type-$tagcomp'>Tipo Factura</label>
                                <select class='form-control text-center input-form' id='type-$tagcomp' name='type-$tagcomp'>
                                    <option value='' id='default-tipo-$tagcomp'>- - - -</option>
                                    <option value='f' id='tipo-f-$tagcomp'>Factura</option>
                                    <option value='c' id='tipo-c-$tagcomp'>Carta Porte</option>
                                </select>
                            </td>
                            <td>
                                <label class='label-form text-right' for='monedarel-$tagcomp'>Moneda Factura</label>
                                <input id='cambiocfdi-$tagcomp' type='hidden' />
                                <input id='metcfdi-$tagcomp' type='hidden' />
                                <select class='form-control text-center input-form' id='monedarel-$tagcomp' name='monedarel-$tagcomp'>
                                    <option value='' id='default-moneda-$tagcomp'>- - - -</option>
                                    <optgroup id='moncfdi-$tagcomp' class='contenedor-moneda-$tagcomp text-left'> </optgroup>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class='label-form text-right' for='parcialidad-$tagcomp'>N° Parcialidad</label>
                                <input class='form-control text-center input-form' id='parcialidad-$tagcomp' name='parcialidad-$tagcomp' placeholder='No Parcialidad' type='text'/>
                            </td>
                            <td>
                                <label class='label-form text-right' for='total-$tagcomp'>Total Factura</label>
                                <input class='form-control text-center input-form' id='total-$tagcomp' name='total-$tagcomp' placeholder='Total de Factura' type='number' step='any'/>
                            </td>
                            <td>
                                <label class='label-form text-right' for='anterior-$tagcomp'>Monto Anterior</label>
                                <input class='form-control text-center input-form' id='anterior-$tagcomp' name='anterior-$tagcomp' placeholder='Monto Anterior' type='number' step='any'/>
                            </td>
                            <td>
                                <label class='label-form text-right' for='monto-$tagcomp'>Monto a Pagar</label>
                                <input class='form-control text-center input-form' id='monto-$tagcomp' name='monto-$tagcomp' placeholder='Monto Pagado' type='number' step='any' oninput='calcularRestante()'/>
                            </td>
                            <td>
                                <label class='label-form text-right' for='restante-$tagcomp'>Monto Restante</label>
                                <input class='form-control text-center input-form' id='restante-$tagcomp' name='cantidad' placeholder='Monto Restante' type='number' step='any'/>
                            </td>
                            <td>
                                <label class='label-space' for='btn-agregar-cfdi'></label>
                                <button id='btn-agregar-cfdi' class='button-modal' onclick='agregarCFDI();'><span class='glyphicon glyphicon-plus'></span> Agregar</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class='table tab-hover table-condensed table-responsive table-row table-head' id='lista-cfdi-$tagcomp'>

                </table>
            </div>
        </div>
        </div><cut>$tagcomp<comp>";
        return $datos;
    }

    private function getTotalesImpuestos($tag){
        $subtotal = 0;
        $traslado = 0;
        $retencion = 0;

        $con = new Consultas();
        $consulta = "SELECT dp.*, df.subtotal, df.subtotaliva, df.subtotalret 
                    FROM detallepago dp
                    INNER JOIN datos_factura df ON df.uuid = dp.uuiddoc
                    WHERE dp.detalle_tagencabezado=:tag
                    UNION ALL                    
                    SELECT dp.*, fc.subtotal, fc.subtotaliva, fc.subtotalret 
                    FROM detallepago dp
                    INNER JOIN factura_carta fc ON fc.uuid = dp.uuiddoc
                    WHERE dp.detalle_tagencabezado=:tag
                    ORDER BY iddetallepago";
        $val = array( "tag" => $tag);
        $consultado = $con->getResults($consulta, $val);
        foreach( $consultado AS $actual ){
            $subtotal += $actual['subtotal'];

            if($actual['subtotaliva'] != ""){
                $imp_tras = explode("<impuesto>", $actual['subtotaliva']);
                foreach($imp_tras AS $im_tras){
                    $divIVA = explode("-", $im_tras);
                    $traslado += $divIVA[0];
                }
            }
            
            if($actual['subtotalret'] != ""){
                $imp_ret = explode("<impuesto>", $actual['subtotalret']);
                foreach($imp_ret AS $im_ret){
                    $divRET = explode("-", $im_ret);
                    $retencion += $divRET[0];
                }
            }
        }
        return "$subtotal</tr>$traslado</tr>$retencion";

    }

}
