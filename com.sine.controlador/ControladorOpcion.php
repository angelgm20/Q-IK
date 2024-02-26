<?php

require_once '../com.sine.dao/Consultas.php';

class ControladorOpcion {

    function __construct() {
        
    }

    private function getCliente() {
        $consultado = false;
        $consulta = "select * from cliente order by nombre_empresa;";
        $consultas = new Consultas();
        $consultado = $consultas->getResults($consulta, null);
        return $consultado;
    }

    public function opcionesCliente() {
        $cliente = $this->getCliente();
        $r = "";
        foreach ($cliente as $clienteactual) {
            $r .= "<option value='" . $clienteactual['id_cliente'] . "'>" . $clienteactual['nombre_empresa'] . "</option>";
        }
        return $r;
    }

    private function getClienteID($idcliente) {
        $consultado = false;
        $consulta = "select * from cliente where id_cliente=:cid;";
        $consultas = new Consultas();
        $val = array("cid" => $idcliente);
        $consultado = $consultas->getResults($consulta, $val);
        return $consultado;
    }

    private function getNombancoaux($idbanco) {
        $consultado = false;
        $consulta = "select nombre_banco from catalogo_banco where idcatalogo_banco=:bid;";
        $consultas = new Consultas();
        $val = array("bid" => $idbanco);
        $consultado = $consultas->getResults($consulta, $val);
        return $consultado;
    }

    private function getNomBanco($idbanco) {
        $banco = $this->getNombancoaux($idbanco);
        $nombre = "";
        foreach ($banco as $bactual) {
            $nombre = $bactual['nombre_banco'];
        }
        return $nombre;
    }

    public function opcionesBancobyCliente($idcliente) {
        $cliente = $this->getClienteID($idcliente);
        $r = "";
        foreach ($cliente as $clienteactual) {
            $idbanco = $clienteactual['idbanco'];
            $cuenta = $clienteactual['cuenta'];
            $idbanco1 = $clienteactual['idbanco1'];
            $cuenta1 = $clienteactual['cuenta1'];
            $idbanco2 = $clienteactual['idbanco2'];
            $cuenta2 = $clienteactual['cuenta2'];
            $idbanco3 = $clienteactual['idbanco3'];
            $cuenta3 = $clienteactual['cuenta3'];
        }
        $banco = $this->getNomBanco($idbanco);
        if ($idbanco != '0') {
            $banco = $this->getNomBanco($idbanco);
            $r .= "<option value='1'>" . $banco . " - Cuenta:" . $cuenta . "</option>";
        }

        if ($idbanco1 != '0') {
            $banco1 = $this->getNomBanco($idbanco1);
            $r .= "<option value='2'>" . $banco1 . " - Cuenta:" . $cuenta1 . "</option>";
        }

        if ($idbanco2 != '0') {
            $banco2 = $this->getNomBanco($idbanco2);
            $r .= "<option value='3'>" . $banco2 . " - Cuenta:" . $cuenta2 . "</option>";
        }

        if ($idbanco3 != '0') {
            $banco3 = $this->getNomBanco($idbanco3);
            $r .= "<option value='4'>" . $banco3 . " - Cuenta:" . $cuenta3 . "</option>";
        }

        return $r;
    }

    private function getMetodoPago() {
        $consultado = false;
        $consulta = "select * from catalogo_metodo_pago order by c_metodopago;";
        $consultas = new Consultas();
        $consultado = $consultas->getResults($consulta, null);
        return $consultado;
    }

    public function opcionesMetodoPago($selected) {
        $mpago = $this->getMetodoPago();
        $r = "";
        foreach ($mpago as $mpagoactual) {
            $opt = "";
            if($selected == $mpagoactual['idmetodo_pago']){
                $opt = "selected";
            }
            $r = $r . "<option $opt id=metodo" . $mpagoactual['idmetodo_pago'] . " value='" . $mpagoactual['idmetodo_pago'] . "'>" . $mpagoactual['c_metodopago'] . ' ' . $mpagoactual['descripcion_metodopago'] . "</option>";
        }
        return $r;
    }

    private function getFormaPago($condicion) {
        $consultado = false;
        $consulta = "select * from catalogo_pago $condicion order by c_pago;";
        $consultas = new Consultas();
        $consultado = $consultas->getResults($consulta, null);
        return $consultado;
    }

    public function opcionesFormaPago($condicion, $selected = "") {
        $pago = $this->getFormaPago($condicion);
        $r = "";
        foreach ($pago as $pagoactual) {
            $opt = "";
            if($selected == $pagoactual['idcatalogo_pago']){
                $opt = "selected";
            }
            $r = $r . "<option $opt id=formapago" . $pagoactual['idcatalogo_pago'] . " value='" . $pagoactual['idcatalogo_pago'] . "'>" . $pagoactual['c_pago'] . ' ' . $pagoactual['descripcion_pago'] . "</option>";
        }
        return $r;
    }

    private function getMoneda() {
        $consultado = false;
        $consulta = "select * from catalogo_moneda order by idcatalogo_moneda;";
        $consultas = new Consultas();
        $consultado = $consultas->getResults($consulta, null);
        return $consultado;
    }

    public function opcionesMoneda($idmoneda = "") {
        $moneda = $this->getMoneda();
        $r = "";
        foreach ($moneda as $monedaactual) {
            $selected = "";
            if($idmoneda == $monedaactual['idcatalogo_moneda']){
                $selected = "selected";
            }
            $r .= "<option $selected id=moneda" . $monedaactual['idcatalogo_moneda'] . " value='" . $monedaactual['idcatalogo_moneda'] . "'>" . $monedaactual['c_moneda'] . ' ' . $monedaactual['descripcion_moneda'] . "</option>";
        }
        return $r;
    }

    private function getUso() {
        $consultado = false;
        $consulta = "select * from catalogo_uso_cfdi order by c_usocfdi;";
        $consultas = new Consultas();
        $consultado = $consultas->getResults($consulta, null);
        return $consultado;
    }

    public function opcionesUsoCFDI($iduso = "") {
        $uso = $this->getUso();
        $r = "";
        foreach ($uso as $usoactual) {
            $selected = "";
            if($iduso == $usoactual['iduso_cfdi']){
                $selected = "selected";
            }
            $r .= "<option $selected id=uso" . $usoactual['iduso_cfdi'] . " value='" . $usoactual['iduso_cfdi'] . "'>" . $usoactual['c_usocfdi'] . ' ' . $usoactual['descripcion_cfdi'] . "</option>";
        }
        return $r;
    }

    private function getTipoComprobante() {
        $consultado = false;
        $consulta = "select * from catalogo_comprobante order by c_tipocomprobante;";
        $consultas = new Consultas();
        $consultado = $consultas->getResults($consulta, null);
        return $consultado;
    }

    public function opcionesComprobante($id = "") {
        $comprobante = $this->getTipoComprobante();
        $r = "";
        foreach ($comprobante as $comprobanteactual) {
            $selected = "";
            if($id == $comprobanteactual['idcatalogo_comprobante']){
                $selected = "selected";
            }
            $r .= "<option $selected id=comprobante" . $comprobanteactual['idcatalogo_comprobante'] . " value='" . $comprobanteactual['idcatalogo_comprobante'] . "'>" . $comprobanteactual['c_tipocomprobante'] . ' ' . $comprobanteactual['descripcion_comprobante'] . "</option>";
        }
        return $r;
    }

    private function getDatosFacturacion() {
        $consultado = false;
        $consulta = "SELECT * FROM datos_facturacion order by nombre_contribuyente;";
        $consultas = new Consultas();
        $consultado = $consultas->getResults($consulta, null);
        return $consultado;
    }

    public function opcionesDatFacturacion($id = "") {
        $cliente = $this->getDatosFacturacion();
        $r = "";
        foreach ($cliente as $clienteactual) {
            $selected = "";
            if($id == $clienteactual['id_datos']){
                $selected = "selected";
            }
            $r .= "<option $selected value='" . $clienteactual['id_datos'] . "'>" . $clienteactual['nombre_contribuyente'] . "</option>";
        }
        return $r;
    }

    private function getProveedor() {
        $consultado = false;
        $consulta = "select * from proveedor order by empresa";
        $consultas = new Consultas();
        $consultado = $consultas->getResults($consulta, null);
        return $consultado;
    }

    public function opcionesProveedor($idprov = "") {
        $proveedor = $this->getProveedor();
        $r = "";
        foreach ($proveedor as $proveedoractual) {
            $selected = "";
            if($idprov == $proveedoractual['idproveedor']){
                $selected = "selected";
            }
            $r = $r . "<option $selected value='" . $proveedoractual['idproveedor'] . "' id='proveedor" . $proveedoractual['idproveedor'] . "'>" . $proveedoractual['empresa'] . "</option>";
        }
        return $r;
    }

    private function getDatosFacturacionbyID($id) {
        $consultado = false;
        $consulta = "select * from datos_facturacion where id_datos=:id";
        $val = array("id" => $id);
        $consultas = new Consultas();
        $consultado = $consultas->getResults($consulta, $val);
        return $consultado;
    }

    public function opcionesBeneficiario($iddatos) {
        $datos = $this->getDatosFacturacionbyID($iddatos);
        $r = "";
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
        $banco = $this->getNomBanco($idbanco);
        if ($idbanco != '0') {
            $banco = $this->getNomBanco($idbanco);
            $r .= "<option value='1'>" . $banco . " - Cuenta:" . $cuenta . "</option>";
        }
        if ($idbanco1 != '0') {
            $banco1 = $this->getNomBanco($idbanco1);
            $r .= "<option value='2'>" . $banco1 . " - Cuenta:" . $cuenta1 . "</option>";
        }
        if ($idbanco2 != '0') {
            $banco2 = $this->getNomBanco($idbanco2);
            $r .= "<option value='3'>" . $banco2 . " - Cuenta:" . $cuenta2 . "</option>";
        }
        if ($idbanco3 != '0') {
            $banco3 = $this->getNomBanco($idbanco3);
            $r .= "<option value='4'>" . $banco3 . " - Cuenta:" . $cuenta3 . "</option>";
        }

        return $r;
    }

    private function getRegimenaux() {
        $consultado = false;
        $consulta = "SELECT * FROM cat_regimencontrato ORDER BY c_tiporegimen;";
        $consultas = new Consultas();
        $consultado = $consultas->getResults($consulta, null);
        return $consultado;
    }

    public function opcionesRegimen($idregimen = "") {
        $opciones = $this->getRegimenaux();
        $op = "";
        foreach ($opciones as $actual) {
            $selected = "";
            if($selected == $actual['idcat_regimencontrato']){
                $selected = "selected";
            }
            $op .= "<option $selected id='regimen" . $actual['idcat_regimencontrato'] . "' value='" . $actual['idcat_regimencontrato'] . "'>" . $actual['c_tiporegimen'] . ' ' . $actual['descripcion_regimen'] . "</option>";
        }
        return $op;
    }

    private function getPeriodicidadaux() {
        $consultado = false;
        $consulta = "SELECT * FROM catalogo_periodicidad ORDER BY c_periodicidadpago;";
        $consultas = new Consultas();
        $consultado = $consultas->getResults($consulta, null);
        return $consultado;
    }

    public function opcionesPeriodicidad($idper = "") {
        $opciones = $this->getPeriodicidadaux();
        $op = "";
        foreach ($opciones as $actual) {
            $selected = "";
            if($idper == $actual['idcatalogo_periodicidad']){
                $selected = "selected";
            }
            $op .= "<option $selected id='periodicidad" . $actual['idcatalogo_periodicidad'] . "' value='" . $actual['idcatalogo_periodicidad'] . "'>" . $actual['c_periodicidadpago'] . ' ' . $actual['descripcion_pp'] . "</option>";
        }
        return $op;
    }

    private function getJornadaaux() {
        $consultado = false;
        $consulta = "SELECT * FROM catalogo_jornada ORDER BY c_tipojornada;";
        $consultas = new Consultas();
        $consultado = $consultas->getResults($consulta, null);
        return $consultado;
    }

    public function opcionesJornada($idjor = "") {
        $opciones = $this->getJornadaaux();
        $op = "";
        foreach ($opciones as $actual) {
            $selected = "";
            if($idjor == $actual['idcatalogo_jornada']){
                $selected = "selected";
            }
            $op .= "<option $selected id='jornada" . $actual['idcatalogo_jornada'] . "' value='" . $actual['idcatalogo_jornada'] . "'>" . $actual['c_tipojornada'] . ' ' . $actual['descripcion_jornada'] . "</option>";
        }
        return $op;
    }

    private function getContratoAux() {
        $consultado = false;
        $consulta = "select * from catalogo_contrato order by idcatalogo_contrato;";
        $consultas = new Consultas();
        $consultado = $consultas->getResults($consulta, null);
        return $consultado;
    }

    public function opcionesContrato($idcontrato = "") {
        $opciones = $this->getContratoAux();
        $op = "";
        foreach ($opciones as $actual) {
            $selected = "";
            if($idcontrato == $actual['idcatalogo_contrato']){
                $selected = "selected";
            }
            $op .= "<option $selected id='contrato" . $actual['idcatalogo_contrato'] . "' value='" . $actual['idcatalogo_contrato'] . "'>" . $actual['c_tipocontrato'] . ' ' . $actual['descripcion_contrato'] . "</option>";
        }
        return $op;
    }
    
    private function getCEstado($codpostal) {
        $c_estado = "0";
        $servidor = "localhost";
        $basedatos = "sineacceso";
        $puerto = "3306";
        $mysql_user = "root";
        $mysql_password = ""; //S1ne15QvikXJWc
        
        try {
            $db = new PDO("mysql:host=$servidor;port=$puerto;dbname=$basedatos", $mysql_user, $mysql_password);
            $stmttable = $db->prepare("SELECT * FROM catalogo_codpostal WHERE c_CodigoPostal='$codpostal'");

            if ($stmttable->execute()) {
                $resultado = $stmttable->fetchAll(PDO::FETCH_ASSOC);
                foreach ($resultado as $actual) {
                    $c_estado = $actual["c_Estado"];
                }
                return "$c_estado";
            } else {
                return "0";
            }
        } catch (PDOException $ex) {
            echo '<e>No se puedeeeee conectar a la bd ' . $ex->getMessage();
        }
    }
    
    private function getEstadobyCPAUX($cp) {
        $consultado = false;
        $consultas = new Consultas();
        $c_estado = $this->getCEstado($cp);
        $consulta = "SELECT * FROM estado WHERE c_estado=:cestado;";
        $val = array("cestado" => $c_estado);
        $consultado = $consultas->getResults($consulta, $val);
        return $consultado;
    }
    
    private function getEstadobyCP($cp) {
        $eid = "";
        $datos = $this->getEstadobyCPAUX($cp);
        foreach ($datos as $actual) {
            $eid = $actual['id_estado'];
        }
        return $eid;
    }

    public function opcionesEstadoCP($cp) {
        $eid = $this->getEstadobyCP($cp);
        $opciones = $this->getEstadoClv();
        $op = "";
        foreach ($opciones as $actual) {
            $selected = "";
            if($eid == $actual['id_estado']){
                $selected = "selected";
            }
            $op .= "<option $selected id='estado" . $actual['id_estado'] . "' value='" . $actual['id_estado'] . "'>" . $actual['c_estado'] . ' - ' . $actual['estado'] . "</option>";
        }
        return $op;
    }

    private function getEstadoClv() {
        $consultado = false;
        $consulta = "SELECT * FROM estado ORDER BY c_estado;";
        $consultas = new Consultas();
        $consultado = $consultas->getResults($consulta, null);
        return $consultado;
    }

    public function opcionesEstadoClv($idestado = "") {
        $opciones = $this->getEstadoClv();
        $op = "";
        foreach ($opciones as $actual) {
            $selected = "";
            if($idestado == $actual['id_estado']){
                $selected = "selected";
            }
            $op .= "<option $selected id='estado" . $actual['id_estado'] . "' value='" . $actual['id_estado'] . "'>" . $actual['c_estado'] . ' - ' . $actual['estado'] . "</option>";
        }
        return $op;
    }

    private function getMunicipiosByEstado($id) {
        $consultado = false;
        $consulta = "select * from municipio where id_estado=:idestado order by municipio asc";
        $valores = array("idestado" => $id);
        $consultas = new Consultas();
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    public function opcionesMunicipioByEstado($id, $idmunicipio = '') {
        $municipios = $this->getMunicipiosByEstado($id);
        $r = "";
        foreach ($municipios as $municipio) {
            $selected = "";
            if ($idmunicipio == $municipio['id_municipio']) {
                $selected = "selected";
            }
            $r .= "<option value='" . $municipio['id_municipio'] . "' $selected>" . $municipio['municipio'] . "</option>";
        }
        return $r;
    }

    private function getBancoAux() {
        $consultado = false;
        $consulta = "select * from catalogo_banco order by c_banco;";
        $consultas = new Consultas();
        $consultado = $consultas->getResults($consulta, null);
        return $consultado;
    }

    public function opcionesBanco($idbanco = "") {
        $opciones = $this->getBancoAux();
        $op = "";
        foreach ($opciones as $actual) {
            $selected = "";
            if($idbanco == $actual['idcatalogo_banco']){
                $selected = "selected";
            }
            $op .= "<option $selected id='banco" . $actual['idcatalogo_banco'] . "' value='" . $actual['idcatalogo_banco'] . "'>" . $actual['c_banco'] . ' - ' . $actual['nombre_banco'] . "</option>";
        }
        return $op;
    }

    public function addopcionesBanco($a, $idbanco = "") {
        $banco = $this->getBancoAux();
        $r = "";
        foreach ($banco as $bancoactual) {
            $selected = "";
            if($idbanco == $bancoactual['idcatalogo_banco']){
                $selected = "selected";
            }
            $r .= "<option $selected id='" . $a . "banco" . $bancoactual['idcatalogo_banco'] . "' value='" . $bancoactual['idcatalogo_banco'] . "'>" . $bancoactual['c_banco'] . ' - ' . $bancoactual['nombre_banco'] . "</option>";
        }
        return $r;
    }

    private function getRiesgoAux() {
        $consultado = false;
        $consulta = "select * from catalogo_riesgo order by c_riesgopuesto;";
        $consultas = new Consultas();
        $consultado = $consultas->getResults($consulta, null);
        return $consultado;
    }

    public function opcionesRiesgo($idriesgo = "") {
        $opciones = $this->getRiesgoAux();
        $op = "";
        foreach ($opciones as $actual) {
            $selected = "";
            if($idriesgo == $actual['idcatalogo_riesgo']){
                $selected = "selected";
            }
            $op .= "<option $selected id='riesgo" . $actual['idcatalogo_riesgo'] . "' value='" . $actual['idcatalogo_riesgo'] . "'>" . $actual['c_riesgopuesto'] . ' - ' . $actual['descripcion_riesgo'] . "</option>";
        }
        return $op;
    }

    private function getVendedoresAux() {
        $consultado = false;
        $consulta = "select * from usuario order by nombre;";
        $consultas = new Consultas();
        $consultado = $consultas->getResults($consulta, null);
        return $consultado;
    }

    public function opcionesVendedor() {
        $get = $this->getVendedoresAux();
        $op = "<option id='usuario0' value='0'>Usuarios vendedores</option>";
        foreach ($get as $actual) {
            $idusuario = $actual['idusuario'];
            $op .= "<option id='usuario" . $idusuario . "' value='" . $idusuario . "'>" . $actual['nombre'] . " " . $actual['apellido_paterno'] . " " . $actual['apellido_materno'] . "</option>";
        }
        return $op;
    }

    public function opcionesAno() {
        $anio_de_inicio = 2020;
        $fecha = getdate();
        $y = $fecha['year'];
        $r = "";
        foreach (range($anio_de_inicio, $y) as $x) {
            $r = $r . "<option id='ano" . $x . "' value='" . $x . "'>" . $x . "  " . "</option>";
        }
        return $r;
    }

    private function getUsuariosAux() {
        $consultado = false;
        $consulta = "select * from usuario order by nombre;";
        $consultas = new Consultas();
        $consultado = $consultas->getResults($consulta, null);
        return $consultado;
    }

    public function opcionesUsuario() {
        $get = $this->getUsuariosAux();
        $op = "";
        foreach ($get as $actual) {
            $idusuario = $actual['idusuario'];
            $op .= "<option id='usuario" . $idusuario . "' value='" . $idusuario . "'>" . $actual['nombre'] . " " . $actual['apellido_paterno'] . " " . $actual['apellido_materno'] . "</option>";
        }
        return $op;
    }

    private function getCorreoListAux() {
        $consultado = false;
        $consulta = "select * from correoenvio order by correo;";
        $consultas = new Consultas();
        $consultado = $consultas->getResults($consulta, null);
        return $consultado;
    }

    public function opcionesCorreoList() {
        $correos = $this->getCorreoListAux();
        $r = "";
        foreach ($correos as $actual) {
            $r = $r . "<option id='correo" . $actual['idcorreoenvio'] . "' value='" . $actual['idcorreoenvio'] . "'>" . $actual['correo'] . "</option>";
        }
        return $r;
    }

    private function getMotivosAux() {
        $consultado = false;
        $consulta = "SELECT * FROM catalogo_motivo order by clvmotivo;";
        $consultas = new Consultas();
        $consultado = $consultas->getResults($consulta, null);
        return $consultado;
    }

    public function opcionesMotivo() {
        $get = $this->getMotivosAux();
        $op = "";
        foreach ($get as $actual) {
            $clv = $actual['clvmotivo'];
            $descripcion = $actual['descripcionmotivo'];
            $op .= "<option id='motivo" . $clv . "' value='" . $clv . "'>" . $clv . " " . $descripcion . "</option>";
        }
        return $op;
    }

    private function getImpuestos($tipo) {
        $consultado = false;
        $consulta = "SELECT * FROM impuesto where tipoimpuesto=:tipo";
        $consultas = new Consultas();
        $valores = array("tipo" => $tipo);
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    public function opcionesImpuestos($t) {
        $impuestos = $this->getImpuestos($t);
        $checkedT = "";
        $iconT = "";
        $optraslados = "";
        $nm = "";

        if ($t == '1') {
            $nm = 'chtrasladoobra';
        } else if ($t == '2') {
            $nm = 'chretencionobra';
        }
        foreach ($impuestos as $actual) {
            if ($actual['chuso'] == '1') {
                $iconT = "glyphicon-check";
                $checkedT = "checked";
            } else {
                $iconT = "glyphicon-unchecked";
                $checkedT = "";
            }
            $optraslados .= "<li data-location='form' data-id=''><label class='dropdown-menu-item checkbox'><input type='checkbox' $checkedT value='" . $actual['porcentaje'] . "' name='$nm' data-impuesto='" . $actual['impuesto'] . "' data-tipo='" . $actual['tipoimpuesto'] . "'/><span class='glyphicon $iconT' id='chuso1span'></span>" . $actual['nombre'] . " (" . $actual['porcentaje'] . ")" . "</label></li>";
        }
        return $optraslados;
    }

    private function getTipoRelacionAux() {
        $consultado = false;
        $consulta = "SELECT * FROM catalogo_relacion ORDER BY c_tiporelacion;";
        $consultas = new Consultas();
        $consultado = $consultas->getResults($consulta, null);
        return $consultado;
    }

    public function opcionesTipoRelacion() {
        $get = $this->getTipoRelacionAux();
        $op = "";
        foreach ($get as $actual) {
            $clv = $actual['c_tiporelacion'];
            $descripcion = $actual['descripcion_tiporelacion'];
            $op .= "<option id='relacion" . $clv . "' value='" . $clv . "'>" . $clv . " " . $descripcion . "</option>";
        }
        return $op;
    }

    private function getFoliosAux() {
        $consultado = false;
        $con = new Consultas();
        $consulta = "SELECT * FROM folio ORDER BY serie;";
        $consultado = $con->getResults($consulta, null);
        return $consultado;
    }

    public function opcionesFolios($id, $comprobante, $folio) {
        $datos = $this->getFoliosAux();
        $op = "";
    	$check = false;
        foreach ($datos as $actual) {
            $idfolio = $actual['idfolio'];
            $consecutivo = $actual['consecutivo'];
            $iduso = $actual['usofolio'];

            if ($consecutivo < 10) {
                $consecutivo = "000$consecutivo";
            } else if ($consecutivo < 100 && $consecutivo >= 10) {
                $consecutivo = "00$consecutivo";
            } else if ($consecutivo < 1000 && $consecutivo >= 100) {
                $consecutivo = "0$consecutivo";
            }

            $divuso = explode("-", $iduso);
            $selected = "";
            foreach ($divuso as $uso) {
                if ($id == $uso) {
                    $selected = "selected";
                	$check = true;
                    break;
                }
            }

            $op .= "<option class='option-folio' id='folio" . $idfolio . "' value='" . $idfolio . "' $selected> Serie " . $actual['serie'] . "-" . $actual['letra'] . $consecutivo . "</option>";
        }
    	if ($id == "0" && !$check) {
            $op .= "<option selected id='folio" . $id . "' value='" . $id . "'> Serie " . $serie . "-" . $folio . "</option>";
        }
        return $op;
    }
    
    private function getPeriodoGlobalAux() {
        $consultado = false;
        $consulta = "SELECT * FROM catalogo_periodoglobal ORDER BY c_periodoglobal;";
        $consultas = new Consultas();
        $consultado = $consultas->getResults($consulta, null);
        return $consultado;
    }

    public function opcionesPeriodoGlobal($id = "") {
        $get = $this->getPeriodoGlobalAux();
        $op = "";
        foreach ($get as $actual) {
            $clv = $actual['c_periodoglobal'];
            $descripcion = $actual['descripcion_periodoglobal'];
            $selected = "";
            if($id == $clv){
                $selected = "selected";
            }
            $op .= "<option $selected id='periodo" . $clv . "' value='" . $clv . "'>" . $clv . " " . $descripcion . "</option>";
        }
        return $op;
    }
    
    private function getMesesAux() {
        $consultado = false;
        $consulta = "SELECT * FROM catalogo_meses ORDER BY c_meses;";
        $consultas = new Consultas();
        $consultado = $consultas->getResults($consulta, null);
        return $consultado;
    }

    public function opcionesMesesPeriodo($id = "") {
        $get = $this->getMesesAux();
        $op = "";
        foreach ($get as $actual) {
            $clv = $actual['c_meses'];
            $descripcion = $actual['descripcion_meses'];
            $selected = "";
            if($id == $clv){
                $selected = "selected";
            }
            $op .= "<option $selected id='mes" . $clv . "' value='" . $clv . "'>" . $clv . " " . $descripcion . "</option>";
        }
        return $op;
    }
    
    public function opcionesAnoGlobal() {
        $fecha = getdate();
        $y = $fecha['year'];
        $anio_de_inicio = $y-1;
        $op = "";
        foreach (range($anio_de_inicio, $y) as $x) {
            $op .= "<option id='ano" . $x . "' value='" . $x . "'>" . $x . "  " . "</option>";
        }
        return $op;
    }

}
