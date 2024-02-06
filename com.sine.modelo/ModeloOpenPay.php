<?php

class ModeloOpenPay {

    private $idcargo;
    private $idtransaccion;
    private $noautorizacion;
    private $idpago;
    private $idcliente;
    private $metodo;
    private $descripcion;
    private $monto;
    private $numtarjeta;
    private $cardtitular;
    private $cardexp;
    private $banco;
    private $clabe;
    private $acuerdo;
    private $referencia;
    private $fechalimite;
    private $barcode;
    private $refpaynet;
            
    function __construct() {
        
    }
    
    function getIdcargo() {
        return $this->idcargo;
    }

    function getIdtransaccion() {
        return $this->idtransaccion;
    }

    function getNoautorizacion() {
        return $this->noautorizacion;
    }

    function getIdpago() {
        return $this->idpago;
    }

    function getIdcliente() {
        return $this->idcliente;
    }

    function getMetodo() {
        return $this->metodo;
    }

    function getDescripcion() {
        return $this->descripcion;
    }

    function getMonto() {
        return $this->monto;
    }

    function getNumtarjeta() {
        return $this->numtarjeta;
    }

    function getCardtitular() {
        return $this->cardtitular;
    }

    function getCardexp() {
        return $this->cardexp;
    }

    function getBanco() {
        return $this->banco;
    }

    function getClabe() {
        return $this->clabe;
    }

    function getAcuerdo() {
        return $this->acuerdo;
    }

    function getReferencia() {
        return $this->referencia;
    }

    function getFechalimite() {
        return $this->fechalimite;
    }

    function getBarcode() {
        return $this->barcode;
    }

    function getRefpaynet() {
        return $this->refpaynet;
    }

    function setIdcargo($idcargo) {
        $this->idcargo = $idcargo;
    }

    function setIdtransaccion($idtransaccion) {
        $this->idtransaccion = $idtransaccion;
    }

    function setNoautorizacion($noautorizacion) {
        $this->noautorizacion = $noautorizacion;
    }

    function setIdpago($idpago) {
        $this->idpago = $idpago;
    }

    function setIdcliente($idcliente) {
        $this->idcliente = $idcliente;
    }

    function setMetodo($metodo) {
        $this->metodo = $metodo;
    }

    function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    function setMonto($monto) {
        $this->monto = $monto;
    }

    function setNumtarjeta($numtarjeta) {
        $this->numtarjeta = $numtarjeta;
    }

    function setCardtitular($cardtitular) {
        $this->cardtitular = $cardtitular;
    }

    function setCardexp($cardexp) {
        $this->cardexp = $cardexp;
    }

    function setBanco($banco) {
        $this->banco = $banco;
    }

    function setClabe($clabe) {
        $this->clabe = $clabe;
    }

    function setAcuerdo($acuerdo) {
        $this->acuerdo = $acuerdo;
    }

    function setReferencia($referencia) {
        $this->referencia = $referencia;
    }

    function setFechalimite($fechalimite) {
        $this->fechalimite = $fechalimite;
    }

    function setBarcode($barcode) {
        $this->barcode = $barcode;
    }

    function setRefpaynet($refpaynet) {
        $this->refpaynet = $refpaynet;
    }

}
