<?php
class Fiscales {
    
    private $rfc;
    private $razon;
    private $correo;
    private $direccion;
    private $idfiscales;
    function __construct() {
        
    }
    
    function getIdfiscales() {
        return $this->idfiscales;
    }

    function setIdfiscales($idfiscales) {
        $this->idfiscales = $idfiscales;
    }

        function getRfc() {
        return $this->rfc;
    }

    function getRazon() {
        return $this->razon;
    }

    function getCorreo() {
        return $this->correo;
    }

    function getDireccion() {
        return $this->direccion;
    }

    

    function setRfc($rfc) {
        $this->rfc = $rfc;
    }

    function setRazon($razon) {
        $this->razon = $razon;
    }

    function setCorreo($correo) {
        $this->correo = $correo;
    }

    function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

function setEstatus($estatus) {
        $this->estatus = $estatus;
    }
}
