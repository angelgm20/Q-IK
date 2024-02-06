<?php

class DescMasiva {
    private $idsolicitud;
    private $idsolicitante;
    private $fechacreacion;
    private $tipo;
    private $fechainicio;
    private $fechafin;
    private $rfc;
    private $passfiel;
    private $key;
    private $csd;

    function __construct() {
        
    }
    
    function getIdsolicitud() {
        return $this->idsolicitud;
    }

    function getIdsolicitante() {
        return $this->idsolicitante;
    }

    function getFechacreacion() {
        return $this->fechacreacion;
    }

    function getTipo() {
        return $this->tipo;
    }

    function getFechainicio() {
        return $this->fechainicio;
    }

    function getFechafin() {
        return $this->fechafin;
    }

    function getRfc() {
        return $this->rfc;
    }

    function getPassfiel() {
        return $this->passfiel;
    }

    function getKey() {
        return $this->key;
    }

    function getCsd() {
        return $this->csd;
    }

    function setIdsolicitud($idsolicitud) {
        $this->idsolicitud = $idsolicitud;
    }

    function setIdsolicitante($idsolicitante) {
        $this->idsolicitante = $idsolicitante;
    }

    function setFechacreacion($fechacreacion) {
        $this->fechacreacion = $fechacreacion;
    }

    function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    function setFechainicio($fechainicio) {
        $this->fechainicio = $fechainicio;
    }

    function setFechafin($fechafin) {
        $this->fechafin = $fechafin;
    }

    function setRfc($rfc) {
        $this->rfc = $rfc;
    }

    function setPassfiel($passfiel) {
        $this->passfiel = $passfiel;
    }

    function setKey($key) {
        $this->key = $key;
    }

    function setCsd($csd) {
        $this->csd = $csd;
    }

}
