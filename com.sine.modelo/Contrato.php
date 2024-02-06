<?php

class Contrato {

    private $idcontrato;
    private $idcliente;
    private $folio;
    private $fechacreacion;
    private $fechafacturacion;
    private $periodo;
    private $idservicio;
    private $numUnidades;
    private $formaPago;
    private $metodoPago;
    private $moneda;
    private $seriem;
    private $usocfdi;
    private $chfacturar;
    private $idfactura;
    private $sessionid;
    private $tag;
    private $iddatosfacturacion;
    private $chperiodo;
    private $mesinicio;
    private $actualizardiafact;
    private $actualizarinicio;
    private $chenvio1;
    private $chenvio2;
    private $chenvio3;
    private $chenvio4;
    private $chenvio5;
    private $chenvio6;

    function __construct() {
        
    }
    
    function getIdcontrato() {
        return $this->idcontrato;
    }

    function getIdcliente() {
        return $this->idcliente;
    }

    function getFechacreacion() {
        return $this->fechacreacion;
    }

    function getFechafacturacion() {
        return $this->fechafacturacion;
    }

    function getPeriodo() {
        return $this->periodo;
    }

    function getIdservicio() {
        return $this->idservicio;
    }

    function getNumUnidades() {
        return $this->numUnidades;
    }

    function getFormaPago() {
        return $this->formaPago;
    }

    function getMetodoPago() {
        return $this->metodoPago;
    }

    function getMoneda() {
        return $this->moneda;
    }

    function getSeriem() {
        return $this->seriem;
    }

    function getUsocfdi() {
        return $this->usocfdi;
    }

    function getChfacturar() {
        return $this->chfacturar;
    }

    function getIdfactura() {
        return $this->idfactura;
    }

    function getSessionid() {
        return $this->sessionid;
    }

    function getTag() {
        return $this->tag;
    }

    function getIddatosfacturacion() {
        return $this->iddatosfacturacion;
    }

    function getChperiodo() {
        return $this->chperiodo;
    }

    function getMesinicio() {
        return $this->mesinicio;
    }

    function getActualizardiafact() {
        return $this->actualizardiafact;
    }

    function getActualizarinicio() {
        return $this->actualizarinicio;
    }

    function getChenvio1() {
        return $this->chenvio1;
    }

    function getChenvio2() {
        return $this->chenvio2;
    }

    function getChenvio3() {
        return $this->chenvio3;
    }

    function getChenvio4() {
        return $this->chenvio4;
    }

    function getChenvio5() {
        return $this->chenvio5;
    }

    function getChenvio6() {
        return $this->chenvio6;
    }

    function setIdcontrato($idcontrato) {
        $this->idcontrato = $idcontrato;
    }

    function setIdcliente($idcliente) {
        $this->idcliente = $idcliente;
    }

    function setFechacreacion($fechacreacion) {
        $this->fechacreacion = $fechacreacion;
    }

    function setFechafacturacion($fechafacturacion) {
        $this->fechafacturacion = $fechafacturacion;
    }

    function setPeriodo($periodo) {
        $this->periodo = $periodo;
    }

    function setIdservicio($idservicio) {
        $this->idservicio = $idservicio;
    }

    function setNumUnidades($numUnidades) {
        $this->numUnidades = $numUnidades;
    }

    function setFormaPago($formaPago) {
        $this->formaPago = $formaPago;
    }

    function setMetodoPago($metodoPago) {
        $this->metodoPago = $metodoPago;
    }

    function setMoneda($moneda) {
        $this->moneda = $moneda;
    }

    function setSeriem($seriem) {
        $this->seriem = $seriem;
    }

    function setUsocfdi($usocfdi) {
        $this->usocfdi = $usocfdi;
    }

    function setChfacturar($chfacturar) {
        $this->chfacturar = $chfacturar;
    }

    function setIdfactura($idfactura) {
        $this->idfactura = $idfactura;
    }

    function setSessionid($sessionid) {
        $this->sessionid = $sessionid;
    }

    function setTag($tag) {
        $this->tag = $tag;
    }

    function setIddatosfacturacion($iddatosfacturacion) {
        $this->iddatosfacturacion = $iddatosfacturacion;
    }

    function setChperiodo($chperiodo) {
        $this->chperiodo = $chperiodo;
    }

    function setMesinicio($mesinicio) {
        $this->mesinicio = $mesinicio;
    }

    function setActualizardiafact($actualizardiafact) {
        $this->actualizardiafact = $actualizardiafact;
    }

    function setActualizarinicio($actualizarinicio) {
        $this->actualizarinicio = $actualizarinicio;
    }

    function setChenvio1($chenvio1) {
        $this->chenvio1 = $chenvio1;
    }

    function setChenvio2($chenvio2) {
        $this->chenvio2 = $chenvio2;
    }

    function setChenvio3($chenvio3) {
        $this->chenvio3 = $chenvio3;
    }

    function setChenvio4($chenvio4) {
        $this->chenvio4 = $chenvio4;
    }

    function setChenvio5($chenvio5) {
        $this->chenvio5 = $chenvio5;
    }

    function setChenvio6($chenvio6) {
        $this->chenvio6 = $chenvio6;
    }
    
    function getFolio() {
        return $this->folio;
    }

    function setFolio($folio) {
        $this->folio = $folio;
    }

}
