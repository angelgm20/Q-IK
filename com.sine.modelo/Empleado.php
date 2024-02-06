<?php

class Empleado {

    private $idempleado;
    private $nombre;
    private $rfc;
    private $curp;
    private $idregimen;
    private $mail;
    private $idperiodicidad;
    private $idjornada;
    private $tipocontrato;
    private $departamento;
    private $sindicato;
    private $puesto;
    private $idestado;
    private $idbanco;
    private $cuentabanco;
    private $iniciolaboral;
    private $nss;
    private $idriesgo;
    private $sdi;
    private $sbc;

    function __construct() {
        
    }
    
    function getIdempleado() {
        return $this->idempleado;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getRfc() {
        return $this->rfc;
    }

    function getCurp() {
        return $this->curp;
    }

    function getIdregimen() {
        return $this->idregimen;
    }

    function getMail() {
        return $this->mail;
    }

    function getIdperiodicidad() {
        return $this->idperiodicidad;
    }

    function getIdjornada() {
        return $this->idjornada;
    }

    function getTipocontrato() {
        return $this->tipocontrato;
    }

    function getDepartamento() {
        return $this->departamento;
    }

    function getSindicato() {
        return $this->sindicato;
    }

    function getPuesto() {
        return $this->puesto;
    }

    function getIdestado() {
        return $this->idestado;
    }

    function getIdbanco() {
        return $this->idbanco;
    }

    function getCuentabanco() {
        return $this->cuentabanco;
    }

    function getIniciolaboral() {
        return $this->iniciolaboral;
    }

    function getNss() {
        return $this->nss;
    }

    function getIdriesgo() {
        return $this->idriesgo;
    }

    function getSdi() {
        return $this->sdi;
    }

    function getSbc() {
        return $this->sbc;
    }

    function setIdempleado($idempleado) {
        $this->idempleado = $idempleado;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setRfc($rfc) {
        $this->rfc = $rfc;
    }

    function setCurp($curp) {
        $this->curp = $curp;
    }

    function setIdregimen($idregimen) {
        $this->idregimen = $idregimen;
    }

    function setMail($mail) {
        $this->mail = $mail;
    }

    function setIdperiodicidad($idperiodicidad) {
        $this->idperiodicidad = $idperiodicidad;
    }

    function setIdjornada($idjornada) {
        $this->idjornada = $idjornada;
    }

    function setTipocontrato($tipocontrato) {
        $this->tipocontrato = $tipocontrato;
    }

    function setDepartamento($departamento) {
        $this->departamento = $departamento;
    }

    function setSindicato($sindicato) {
        $this->sindicato = $sindicato;
    }

    function setPuesto($puesto) {
        $this->puesto = $puesto;
    }

    function setIdestado($idestado) {
        $this->idestado = $idestado;
    }

    function setIdbanco($idbanco) {
        $this->idbanco = $idbanco;
    }

    function setCuentabanco($cuentabanco) {
        $this->cuentabanco = $cuentabanco;
    }

    function setIniciolaboral($iniciolaboral) {
        $this->iniciolaboral = $iniciolaboral;
    }

    function setNss($nss) {
        $this->nss = $nss;
    }

    function setIdriesgo($idriesgo) {
        $this->idriesgo = $idriesgo;
    }

    function setSdi($sdi) {
        $this->sdi = $sdi;
    }

    function setSbc($sbc) {
        $this->sbc = $sbc;
    }

}
