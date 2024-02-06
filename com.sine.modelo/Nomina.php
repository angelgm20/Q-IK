<?php

class Nomina {

    private $idnomina;
    private $chreg;
    private $regpatronal;
    private $idempleado;
    private $idemisor; 
    private $tnomina;
    private $cp;
    private $periodo;
    private $tipoperiodo;
    private $fechainicial;
    private $fechafinal;
    private $fechapago;
    private $rfcorigen;
    private $regfiscal;
    private $diaslaborados;
    private $diaspagados;
    private $incapacidades;
    private $ausencias;
    private $hrsextra;
    private $totalpercepciones;
    private $totaldeducciones;
    private $totalotrospagos;
    private $totalnomina; 
    private $datap;
    private $datad;
    private $datao;

    function __construct() {
        
    }
    
    function getIdnomina() {
        return $this->idnomina;
    }

    function getChreg() {
        return $this->chreg;
    }

    function getRegpatronal() {
        return $this->regpatronal;
    }

    function getIdempleado() {
        return $this->idempleado;
    }

    function getIdemisor() {
        return $this->idemisor;
    }

    function getTnomina() {
        return $this->tnomina;
    }

    function getCp() {
        return $this->cp;
    }

    function getPeriodo() {
        return $this->periodo;
    }

    function getTipoperiodo() {
        return $this->tipoperiodo;
    }

    function getFechainicial() {
        return $this->fechainicial;
    }

    function getFechafinal() {
        return $this->fechafinal;
    }

    function getFechapago() {
        return $this->fechapago;
    }

    function getRfcorigen() {
        return $this->rfcorigen;
    }

    function getRegfiscal() {
        return $this->regfiscal;
    }

    function getDiaslaborados() {
        return $this->diaslaborados;
    }

    function getDiaspagados() {
        return $this->diaspagados;
    }

    function getIncapacidades() {
        return $this->incapacidades;
    }

    function getAusencias() {
        return $this->ausencias;
    }

    function getHrsextra() {
        return $this->hrsextra;
    }

    function getTotalpercepciones() {
        return $this->totalpercepciones;
    }

    function getTotaldeducciones() {
        return $this->totaldeducciones;
    }

    function getTotalotrospagos() {
        return $this->totalotrospagos;
    }

    function getTotalnomina() {
        return $this->totalnomina;
    }

    function getDatap() {
        return $this->datap;
    }

    function getDatad() {
        return $this->datad;
    }

    function getDatao() {
        return $this->datao;
    }

    function setIdnomina($idnomina) {
        $this->idnomina = $idnomina;
    }

    function setChreg($chreg) {
        $this->chreg = $chreg;
    }

    function setRegpatronal($regpatronal) {
        $this->regpatronal = $regpatronal;
    }

    function setIdempleado($idempleado) {
        $this->idempleado = $idempleado;
    }

    function setIdemisor($idemisor) {
        $this->idemisor = $idemisor;
    }

    function setTnomina($tnomina) {
        $this->tnomina = $tnomina;
    }

    function setCp($cp) {
        $this->cp = $cp;
    }

    function setPeriodo($periodo) {
        $this->periodo = $periodo;
    }

    function setTipoperiodo($tipoperiodo) {
        $this->tipoperiodo = $tipoperiodo;
    }

    function setFechainicial($fechainicial) {
        $this->fechainicial = $fechainicial;
    }

    function setFechafinal($fechafinal) {
        $this->fechafinal = $fechafinal;
    }

    function setFechapago($fechapago) {
        $this->fechapago = $fechapago;
    }

    function setRfcorigen($rfcorigen) {
        $this->rfcorigen = $rfcorigen;
    }

    function setRegfiscal($regfiscal) {
        $this->regfiscal = $regfiscal;
    }

    function setDiaslaborados($diaslaborados) {
        $this->diaslaborados = $diaslaborados;
    }

    function setDiaspagados($diaspagados) {
        $this->diaspagados = $diaspagados;
    }

    function setIncapacidades($incapacidades) {
        $this->incapacidades = $incapacidades;
    }

    function setAusencias($ausencias) {
        $this->ausencias = $ausencias;
    }

    function setHrsextra($hrsextra) {
        $this->hrsextra = $hrsextra;
    }

    function setTotalpercepciones($totalpercepciones) {
        $this->totalpercepciones = $totalpercepciones;
    }

    function setTotaldeducciones($totaldeducciones) {
        $this->totaldeducciones = $totaldeducciones;
    }

    function setTotalotrospagos($totalotrospagos) {
        $this->totalotrospagos = $totalotrospagos;
    }

    function setTotalnomina($totalnomina) {
        $this->totalnomina = $totalnomina;
    }

    function setDatap($datap) {
        $this->datap = $datap;
    }

    function setDatad($datad) {
        $this->datad = $datad;
    }

    function setDatao($datao) {
        $this->datao = $datao;
    }

}
