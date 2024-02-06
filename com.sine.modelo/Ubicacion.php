<?php

class Ubicacion {

    private $idubicacion;
    private $tipoubicacion;
    private $nombre;
    private $rfc;
    private $calle;
    private $numext;
    private $numint;
    private $codigopostal;
    private $referencia;
    private $estado;
    private $municipio;
    private $localidad;
    private $colonia;

    function __construct() {
        
    }
    
    function getIdubicacion() {
        return $this->idubicacion;
    }

    function getTipoubicacion() {
        return $this->tipoubicacion;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getRfc() {
        return $this->rfc;
    }

    function getCalle() {
        return $this->calle;
    }

    function getNumext() {
        return $this->numext;
    }

    function getNumint() {
        return $this->numint;
    }

    function getCodigopostal() {
        return $this->codigopostal;
    }

    function getReferencia() {
        return $this->referencia;
    }

    function getEstado() {
        return $this->estado;
    }

    function getMunicipio() {
        return $this->municipio;
    }

    function getLocalidad() {
        return $this->localidad;
    }

    function getColonia() {
        return $this->colonia;
    }

    function setIdubicacion($idubicacion) {
        $this->idubicacion = $idubicacion;
    }

    function setTipoubicacion($tipoubicacion) {
        $this->tipoubicacion = $tipoubicacion;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setRfc($rfc) {
        $this->rfc = $rfc;
    }

    function setCalle($calle) {
        $this->calle = $calle;
    }

    function setNumext($numext) {
        $this->numext = $numext;
    }

    function setNumint($numint) {
        $this->numint = $numint;
    }

    function setCodigopostal($codigopostal) {
        $this->codigopostal = $codigopostal;
    }

    function setReferencia($referencia) {
        $this->referencia = $referencia;
    }

    function setEstado($estado) {
        $this->estado = $estado;
    }

    function setMunicipio($municipio) {
        $this->municipio = $municipio;
    }

    function setLocalidad($localidad) {
        $this->localidad = $localidad;
    }

    function setColonia($colonia) {
        $this->colonia = $colonia;
    }

}
