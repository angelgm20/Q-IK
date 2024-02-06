<?php

require_once '../com.sine.dao/Consultas.php';

class ControladorAuto {

    function __construct() {
        
    }

    public function getCoincidenciasBusquedaCliente($referencia) {
        $datos = array();
        $consulta = "SELECT * FROM cliente WHERE (nombre_empresa LIKE '%$referencia%') OR (concat(nombre,' ',apaterno,' ',amaterno) LIKE '%$referencia%') OR (rfc LIKE '%$referencia%') OR (razon_social LIKE '%$referencia%') LIMIT 0,5;";
        $c = new Consultas();
        $resultados = $c->getResults($consulta, null);
        $contador = 0;
        foreach ($resultados as $resultado) {
            $estado = $this->getEstadoAux($resultado['idestado']);
            $municipio = $this->getMunicipioAux($resultado['idmunicipio']);
            $int = "";
            if($resultado['numero_interior'] != ""){
                $int = " Int. ".$resultado['numero_interior'];
            }
            $datos[] = array("value" => $resultado['nombre'] . " " . $resultado['apaterno'] . "-" . $resultado['nombre_empresa'],
                "id" => $resultado["id_cliente"],
                "rfc" => $resultado['rfc'],
                "razon" => $resultado['razon_social'],
                "regfiscal" => $resultado['regimen_cliente'],
                "codpostal" => $resultado['codigo_postal'],
                "direccion" => $resultado['calle']." ".$resultado['numero_exterior'].$int." ".$resultado['localidad']." ".$municipio." ".$estado,
                "mailinfo" => $resultado['email_informacion'],
                "mailfacturas" => $resultado['email_facturacion'],
                "mailgerencia" => $resultado['email_gerencia']);
            $contador++;
        }
        if ($contador == 0) {
            $datos [] = array("value" => "No se encontraron registros", "id" => "Ninguno");
        }
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
        $consulta = "SELECT * FROM estado WHERE id_estado=:id;";
        $valores = array("id" => $idestado);
        $consultas = new Consultas();
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
        $consulta = "SELECT * FROM municipio WHERE id_municipio=:id;";
        $valores = array("id" => $idmun);
        $consultas = new Consultas();
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    public function getCoincidenciasBusquedaCliente2($referencia) {
        $datos = array();
        $consulta = "SELECT * FROM cliente WHERE (nombre_empresa LIKE '%$referencia%') OR (concat(nombre,' ',apaterno,' ',amaterno) LIKE '%$referencia%') OR (rfc LIKE '%$referencia%' ) OR (razon_social LIKE '%$referencia%') limit 0,5  ;";
        $c = new Consultas();
        $resultados = $c->getResults($consulta, null);
        $contador = 0;
        foreach ($resultados as $resultado) {
            $datos[] = array("value" => $resultado['nombre'] . " " . $resultado['apaterno'],
                "id" => $resultado["id_cliente"],
                "nombre" => $resultado['nombre_empresa']);
            $contador++;
        }
        if ($contador == 0) {
            $datos [] = array("value" => "No se encontraron registros", "id" => "Ninguno");
        }
        return $datos;
    }

    public function getCoincidenciasBusquedaMail($referencia) {
        $datos = array();
        $consulta = "select distinct * from datos_cotizacion where (emailcot like '%$referencia%') group by emailcot limit 0,5;";
        $c = new Consultas();
        $resultados = $c->getResults($consulta, null);
        $contador = 0;
        foreach ($resultados as $resultado) {
            $datos[] = array("value" => $resultado['emailcot'],
                "id" => $resultado["iddatos_cotizacion"],
                "nombre" => $resultado['emailcot']);
            $contador++;
        }
        if ($contador == 0) {
            $datos [] = array("value" => "No se encontraron registros", "id" => "Ninguno");
        }
        return $datos;
    }

    public function getCoincidenciasCatalogoFiscal($referencia) {
        $datos = array();
        $consulta = "SELECT * FROM catalogo_prodserv WHERE (c_ProdServ LIKE '%$referencia%' OR Descripcion LIKE '%$referencia%') LIMIT 20;";
        $c = new Consultas();
        $resultados = $c->getResults($consulta, null);
        $contador = 0;
        foreach ($resultados as $resultado) {
            $datos[] = array("value" => $resultado['c_ProdServ'] . "-" . utf8_decode($resultado['Descripcion']),
                "id" => $resultado["idcatalogo_prodserv"],
                "nombre" => utf8_decode($resultado['Descripcion']),
                "peligro" => $resultado['material_peligroso']);
            $contador++;
        }
        if ($contador == 0) {
            $datos [] = array("value" => "No se encontraron registros", "id" => "Ninguno");
        }
        return $datos;
    }

    public function getCoincidenciasCliente($referencia) {
        $datos = array();
        $consulta = "select * from datos_fiscales where (razon_social like '%$referencia%' or rfc like'%$referencia%') limit 15;";
        $c = new Consultas();
        $resultados = $c->getResults($consulta, null);
        $contador = 0;
        foreach ($resultados as $resultado) {
            $datos[] = array("value" => $resultado['razon_social'] . "-" . $resultado['rfc'],
                "id" => $resultado["idcliente"],
                "razon" => $resultado['razon_social']);
            $contador++;
        }
        if ($contador == 0) {
            $datos [] = array("value" => "No se encontraron registros", "id" => "Ninguno");
        }
        return $datos;
    }

    public function getCoincidenciasFacturas($referencia, $iddatos) {
        $datos = array();
        $c = new Consultas();
        $consulta = "SELECT * FROM datos_factura WHERE (concat(letra, folio_interno_fac) LIKE '%$referencia%') AND idcliente = '$iddatos' AND status_pago !='3' ORDER BY folio_interno_fac DESC LIMIT 15;";
        $resultados = $c->getResults($consulta, null);
        $contador = 0;
        foreach ($resultados as $resultado) {
            $datos[] = array("value" => "Factura-" . $resultado['letra'].$resultado['folio_interno_fac'],
                "id" => $resultado["iddatos_factura"],
                "type" => 'f');
            $contador++;
        }
        
        $consulta2 = "SELECT * FROM factura_carta WHERE (concat(letra, foliocarta) LIKE '%$referencia%') AND idcliente = '$iddatos' AND status_pago !='3' ORDER BY foliocarta DESC LIMIT 15;";
        $resultados2 = $c->getResults($consulta2, null);
        foreach ($resultados2 as $resultado) {
            $datos[] = array("value" => "Carta Porte-" . $resultado['letra'].$resultado['foliocarta'],
                "id" => $resultado["idfactura_carta"],
                "type" => 'c');
            $contador++;
        }
        if ($contador == 0) {
            $datos [] = array("value" => "No se encontraron registros", "id" => "Ninguno");
        }
        return $datos;
    }

    public function getCoincidenciasCatalogoUnidad($referencia) {
        $datos = array();
        $consulta = "select * from cat_unidad where (c_unidad like '%$referencia%' or nombre_unidad like'%$referencia%') limit 15;";
        $c = new Consultas();
        $resultados = $c->getResults($consulta, null);
        $contador = 0;
        foreach ($resultados as $resultado) {
            $datos[] = array("value" => $resultado['c_unidad'] . "-" . $resultado['nombre_unidad'] . "-" . $resultado['descripción_unidad'],
                "id" => $resultado["idcat_unidad"],
                "nombre" => $resultado['nombre_unidad']);
            $contador++;
        }
        if ($contador == 0) {
            $datos [] = array("value" => "No se encontraron registros", "id" => "Ninguno");
        }
        return $datos;
    }

    public function getCoincidenciasFolioCotizacion($referencia) {
        $datos = array();
        $consulta = "select * from datos_cotizacion where (foliocotizacion like '%$referencia%') limit 15;";
        $c = new Consultas();
        $resultados = $c->getResults($consulta, null);
        $contador = 0;
        foreach ($resultados as $resultado) {
            $datos[] = array("value" => $resultado['foliocotizacion'],
                "id" => $resultado["iddatos_cotizacion"],
                "nombre" => $resultado['nombrecliente']);
            $contador++;
        }
        if ($contador == 0) {
            $datos [] = array("value" => "No se encontraron registros", "id" => "Ninguno");
        }
        return $datos;
    }
    
    public function getCoincidenciasRegimen($referencia) {
        $datos = array();
        $consulta = "select * from catalogo_regimen where (c_regimenfiscal like '%$referencia%' or descripcion_regimen like '%$referencia%') limit 15;";
        $c = new Consultas();
        $resultados = $c->getResults($consulta, null);
        $contador = 0;
        foreach ($resultados as $resultado) {
            $datos[] = array("value" => $resultado['c_regimenfiscal'].'-'.$resultado['descripcion_regimen'],
                "id" => $resultado["idcatalogo_regimen"]);
            $contador++;
        }
        if ($contador == 0) {
            $datos [] = array("value" => "No se encontraron registros", "id" => "Ninguno");
        }
        return $datos;
    }
    
    public function getCoincidenciasClvRegimen($referencia) {
        $datos = array();
        $consulta = "select * from catalogo_regimen where (c_regimenfiscal like '%$referencia%' or descripcion_regimen like '%$referencia%') limit 15;";
        $c = new Consultas();
        $resultados = $c->getResults($consulta, null);
        $contador = 0;
        foreach ($resultados as $resultado) {
            $datos[] = array("value" => $resultado['c_regimenfiscal'],
                "id" => $resultado["idcatalogo_regimen"],
                "regimen" => $resultado['descripcion_regimen']);
            $contador++;
        }
        if ($contador == 0) {
            $datos [] = array("value" => "No se encontraron registros", "id" => "Ninguno");
        }
        return $datos;
    }

    public function getCoincidenciasDireccion($referencia) {
        $datos = array();
        $query = rawurlencode($referencia);
        $dir = file_get_contents('https://autocomplete.geocoder.ls.hereapi.com/6.2/suggest.json?query='.$query.'&apikey=hjP8_Ch4pRoR1ZhzzZl6lmZUsVZWe8x8l2FrhMVqXbE');
        $data = json_decode($dir, true);
        $suggestions = $data['suggestions'];
        $contador = 0;
        foreach ($suggestions as $sug) {
            $address = $sug['address'];
            $country = $address['country'];
            $state = $address['state'];
            $city = $address['city'];
            $district = $address['district'];
            $street = $address['street'];
            $hnumber = " ";
            if (isset($address['houseNumber'])) {
                $hnumber = " ".$address['houseNumber']." ";
            }
            $postalCode = $address['postalCode'];
            $dirurl = rawurlencode($street.$hnumber.$district." ".$postalCode." ".$city." ".$state);
            $datos[] = array("value" => $street.$hnumber.$district." ".$postalCode." ".$city." ".$state,
                "url" => 'https://www.google.com/maps/place/'.$dirurl);
            $contador++;
        }
        if ($contador == 0) {
            $datos [] = array("value" => "No se encontraron registros", "id" => "Ninguno");
        }
        return $datos;
    }
    
    public function getCoincidenciasEmpleado($referencia) {
        $datos = array();
        $consulta = "SELECT * FROM empleado WHERE (nombreempleado LIKE '%$referencia%' OR rfcempleado LIKE '%$referencia%' OR curpempleado LIKE '%$referencia%') LIMIT 15;";
        $c = new Consultas();
        $resultados = $c->getResults($consulta, null);
        $contador = 0;
        foreach ($resultados as $resultado) {
            $datos[] = array("value" => $resultado['nombreempleado'],
                "id" => $resultado["idempleado"],
                "nombre" => $resultado['nombreempleado'],
                "rfc" => $resultado['rfcempleado']);
            $contador++;
        }
        if ($contador == 0) {
            $datos [] = array("value" => "No se encontraron registros", "id" => "Ninguno");
        }
        return $datos;
    }
    
    public function getCoincidenciasLocalidad($referencia) {
        $datos = array();
        $consulta = "SELECT * FROM localidadenvio WHERE (c_Localidad LIKE '%$referencia%' OR Descripcion LIKE '%$referencia%') LIMIT 15;";
        $c = new Consultas();
        $resultados = $c->getResults($consulta, null);
        $contador = 0;
        foreach ($resultados as $resultado) {
            $datos[] = array("value" => $resultado['c_Localidad']."-".$resultado['Descripcion'],
                "id" => $resultado["id_localidad"],
                "nombre" => $resultado['Descripcion']);
            $contador++;
        }
        if ($contador == 0) {
            $datos [] = array("value" => "No se encontraron registros", "id" => "Ninguno");
        }
        return $datos;
    }
    
    public function getCoincidenciasTipoPermiso($referencia) {
        $datos = array();
        $consulta = "SELECT * FROM catalogo_tipopermiso WHERE (clave LIKE '%$referencia%' OR descripcion LIKE '%$referencia%') LIMIT 15;";
        $c = new Consultas();
        $resultados = $c->getResults($consulta, null);
        $contador = 0;
        foreach ($resultados as $resultado) {
            $datos[] = array("value" => $resultado['clave']."-".$resultado['descripcion'],
                "id" => $resultado["idtipopermiso"],
                "nombre" => $resultado['descripcion']);
            $contador++;
        }
        if ($contador == 0) {
            $datos [] = array("value" => "No se encontraron registros", "id" => "Ninguno");
        }
        return $datos;
    }
    
    public function getCoincidenciasConfigTransporte($referencia) {
        $datos = array();
        $consulta = "SELECT * FROM catalogo_configtransporte WHERE (clvnomenclatura LIKE '%$referencia%' OR descripcion LIKE '%$referencia%') LIMIT 15;";
        $c = new Consultas();
        $resultados = $c->getResults($consulta, null);
        $contador = 0;
        foreach ($resultados as $resultado) {
            $datos[] = array("value" => $resultado['clvnomenclatura']."-".$resultado['descripcion'],
                "id" => $resultado["idcatalogo_configtransporte"],
                "nombre" => $resultado['descripcion']);
            $contador++;
        }
        if ($contador == 0) {
            $datos [] = array("value" => "No se encontraron registros", "id" => "Ninguno");
        }
        return $datos;
    }
    
    public function getCoincidenciasTipoRemolque($referencia) {
        $datos = array();
        $consulta = "SELECT * FROM catalogo_tiporemolque WHERE (clavetiporemolque LIKE '%$referencia%' OR desripcionremolque LIKE '%$referencia%') LIMIT 15;";
        $c = new Consultas();
        $resultados = $c->getResults($consulta, null);
        $contador = 0;
        foreach ($resultados as $resultado) {
            $datos[] = array("value" => $resultado['clavetiporemolque']."-".$resultado['desripcionremolque'],
                "id" => $resultado["idtiporemolque"],
                "nombre" => $resultado['clavetiporemolque']);
            $contador++;
        }
        if ($contador == 0) {
            $datos [] = array("value" => "No se encontraron registros", "id" => "Ninguno");
        }
        return $datos;
    }
    
    public function getCoincidenciasProducto($referencia) {
        $datos = array();
        $consulta = "SELECT * FROM productos_servicios WHERE ((codproducto LIKE '%$referencia%') OR (nombre_producto LIKE '%$referencia%') OR (descripcion_producto LIKE '%$referencia%') OR (clave_fiscal LIKE '%$referencia%')) LIMIT 15;";
        $c = new Consultas();
        $resultados = $c->getResults($consulta, null);
        $contador = 0;
        foreach ($resultados as $resultado) {
            $datos[] = array("value" => $resultado['clave_fiscal']."-".$resultado['nombre_producto'],
                "id" => $resultado["idproser"],
                "nombre" => $resultado['nombre_producto'],
                "peligro" => $this->getPeligroByCFiscal($resultado['clave_fiscal']));
            $contador++;
        }
        if ($contador == 0) {
            $datos [] = array("value" => "No se encontraron registros", "id" => "Ninguno");
        }
        return $datos;
    }
    
    private function getPeligroByCFiscal($cfiscal){
        $peligro = "";
        $datos = $this->getPeligroByCFiscalAux($cfiscal);
        foreach($datos as $actual){
            $peligro = $actual['material_peligroso'];
        }
        return $peligro;
    }
    
    private function getPeligroByCFiscalAux($cfiscal){
        $datos = false;
        $consultas = new Consultas();
        $consulta = "SELECT material_peligroso FROM catalogo_prodserv WHERE c_ProdServ='$cfiscal'";
        $datos = $consultas->getResults($consulta, null);
        return $datos;
    }
    
    public function getCoincidenciasUnidadCarta($referencia) {
        $datos = array();
        $consulta = "SELECT * FROM catalogo_unidad_carta WHERE ((c_unidad LIKE '%$referencia%') OR (nombre_unidad LIKE '%$referencia%')) LIMIT 15;";
        $c = new Consultas();
        $resultados = $c->getResults($consulta, null);
        $contador = 0;
        foreach ($resultados as $resultado) {
            $datos[] = array("value" => $resultado['c_unidad']."-".$resultado['nombre_unidad'],
                "id" => $resultado["idcatalogo_unidad_carta"]);
            $contador++;
        }
        if ($contador == 0) {
            $datos [] = array("value" => "No se encontraron registros", "id" => "Ninguno");
        }
        return $datos;
    }
    
    public function getCoincidenciasVehiculo($referencia) {
        $datos = array();
        $consulta = "SELECT * FROM transporte WHERE ((nombrevehiculo LIKE '%$referencia%') OR (placavehiculo LIKE '%$referencia%')) and status ='1' LIMIT 15;";
        $c = new Consultas();
        $resultados = $c->getResults($consulta, null);
        $contador = 0;
        foreach ($resultados as $resultado) {
            $datos[] = array("value" => $resultado['nombrevehiculo'],
                "id" => $resultado["idtransporte"],
                "numpermiso" => $resultado["numeropermiso"],
                "tipopermiso" => $resultado["tipopermiso"],
                "conftransporte" => $resultado['conftransporte'],
                "anhomodelo" => $resultado['anhomodelo'],
                "placavehiculo" => $resultado['placavehiculo'],
                "segurocivil" => $resultado['seguroCivil'],
                "polizaCivil" => $resultado['polizaCivil'],
                "seguroambiente" => $resultado['seguroAmbiente'],
                "polizaambiente" => $resultado['polizaambiente']);
            $contador++;
        }
        if ($contador == 0) {
            $datos [] = array("value" => "No se encontraron registros", "id" => "Ninguno");
        }
        return $datos;
    }
    
    public function getCoincidenciasRemolque($referencia) {
        $datos = array();
        $consulta = "SELECT * FROM remolque WHERE ((nombreremolque LIKE '%$referencia%') OR (placaremolque LIKE '%$referencia%')) and status ='1' LIMIT 15;";
        $c = new Consultas();
        $resultados = $c->getResults($consulta, null);
        $contador = 0;
        foreach ($resultados as $resultado) {
            $datos[] = array("value" => $resultado['nombreremolque'],
                "id" => $resultado['idremolque'],
                "tiporemolque" => $resultado["tiporemolque"],
                "placaremolque" => $resultado["placaremolque"]);
            $contador++;
        }
        if ($contador == 0) {
            $datos [] = array("value" => "No se encontraron registros", "id" => "Ninguno");
        }
        return $datos;
    }
    
    public function getCoincidenciasUbicacion($referencia, $tipo) {
        $datos = array();
        $consulta = "SELECT u.*, e.estado FROM ubicacion u INNER JOIN estado e ON (u.ubicacion_idestado=e.id_estado) WHERE ((u.nombre LIKE '%$referencia%') OR (u.rfcubicacion LIKE '%$referencia%') OR (e.estado LIKE '%$referencia%')) and status ='1' and tipoubicacion='$tipo' LIMIT 15;";
        $c = new Consultas();
        $resultados = $c->getResults($consulta, null);
        $contador = 0;
        foreach ($resultados as $resultado) {
            $datos[] = array("value" => $resultado['nombre'],
                "id" => $resultado['idubicacion'],
                "rfc" => $resultado['rfcubicacion'],
                "tipo" => $resultado["tipoubicacion"],
                "calle" => $resultado['calle'],
                "numext" => $resultado['numext'],
                "numint" => $resultado['numint'],
                "colonia" => $resultado['colonia'],
                "idestado" => $resultado["ubicacion_idestado"],
                "idmunicipio" => $resultado['ubicacion_idmunicipio'],
                "cp" => $resultado['codpostal']);
            $contador++;
        }
        if ($contador == 0) {
            $datos [] = array("value" => "No se encontraron registros", "id" => "Ninguno");
        }
        return $datos;
    }
    
    public function getCoincidenciasOperador($referencia) {
        $datos = array();
        $consulta = "SELECT * FROM operador o INNER JOIN estado e ON (o.operador_idestado=e.id_estado) WHERE ((concat(o.nombreoperador,' ',o.apaternooperador,' ',o.amaternooperador) LIKE '%$referencia%') OR (o.rfcoperador LIKE '%$referencia%')) and opstatus ='1' LIMIT 15;";
        $c = new Consultas();
        $resultados = $c->getResults($consulta, null);
        $contador = 0;
        foreach ($resultados as $resultado) {
            $datos[] = array("value" => $resultado['nombreoperador'].' '.$resultado['apaternooperador'].' '.$resultado['amaternooperador'],
                "id" => $resultado['idoperador'],
                "rfc" => $resultado['rfcoperador'],
                "licencia" => $resultado['numlicencia'],
                "idestado" => $resultado["operador_idestado"],
                "idmunicipio" => $resultado['operador_idmunicipio'],
                "calle" => $resultado["calle"],
                "codpostal" => $resultado["cpoperador"]);
            $contador++;
        }
        if ($contador == 0) {
            $datos [] = array("value" => "No se encontraron registros", "id" => "Ninguno");
        }
        return $datos;
    }
    
    public function getCoincidenciasMaterialPeligroso($referencia) {
        $datos = array();
        $consulta = "SELECT * FROM catalogo_material_peligroso WHERE (c_material_peligroso LIKE '%$referencia%') OR (descripcion_material LIKE '%$referencia%') LIMIT 15;";
        $c = new Consultas();
        $resultados = $c->getResults($consulta, null);
        $contador = 0;
        foreach ($resultados as $resultado) {
            $datos[] = array("value" => $resultado['c_material_peligroso'] . "-" . $resultado['descripcion_material'],
                "id" => $resultado["id_MaterialPeligroso"]);
            $contador++;
        }
        if ($contador == 0) {
            $datos [] = array("value" => "No se encontraron registros", "id" => "Ninguno");
        }
        return $datos;
    }
    
    public function getCoincidenciasEmbalaje($referencia) {
        $datos = array();
        $consulta = "SELECT * FROM catalogo_embalaje WHERE (C_Embalaje LIKE '%$referencia%') OR (Descripcion_Embalaje LIKE '%$referencia%') LIMIT 15;";
        $c = new Consultas();
        $resultados = $c->getResults($consulta, null);
        $contador = 0;
        foreach ($resultados as $resultado) {
            $datos[] = array("value" => $resultado['C_Embalaje'] . "-" . $resultado['Descripcion_Embalaje'],
                "id" => $resultado["id_Catalogo_Embalaje"]);
            $contador++;
        }
        if ($contador == 0) {
            $datos [] = array("value" => "No se encontraron registros", "id" => "Ninguno");
        }
        return $datos;
    }

}
