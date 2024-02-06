<?php
class Operador {

    private $idoperador;
    private $nombre;
    private $apaterno;
    private $amaterno;
    private $numlicencia;
    private $rfc;
    private $empresa;
    private $idestado;
    private $idmunicipio;
    private $calle;
    private $codpostal;

    function __construct() {
    }
    
    function getIdoperador() {
        return $this->idoperador;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getApaterno() {
        return $this->apaterno;
    }

    function getAmaterno() {
        return $this->amaterno;
    }

    function getNumlicencia() {
        return $this->numlicencia;
    }

    function getRfc() {
        return $this->rfc;
    }

    function getEmpresa() {
        return $this->empresa;
    }

    function getIdestado() {
        return $this->idestado;
    }

    function getIdmunicipio() {
        return $this->idmunicipio;
    }

    function getCalle() {
        return $this->calle;
    }

    function getCodpostal() {
        return $this->codpostal;
    }

    function setIdoperador($idoperador) {
        $this->idoperador = $idoperador;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setApaterno($apaterno) {
        $this->apaterno = $apaterno;
    }

    function setAmaterno($amaterno) {
        $this->amaterno = $amaterno;
    }

    function setNumlicencia($numlicencia) {
        $this->numlicencia = $numlicencia;
    }

    function setRfc($rfc) {
        $this->rfc = $rfc;
    }

    function setEmpresa($empresa) {
        $this->empresa = $empresa;
    }

    function setIdestado($idestado) {
        $this->idestado = $idestado;
    }

    function setIdmunicipio($idmunicipio) {
        $this->idmunicipio = $idmunicipio;
    }

    function setCalle($calle) {
        $this->calle = $calle;
    }

    function setCodpostal($codpostal) {
        $this->codpostal = $codpostal;
    }
    
}
