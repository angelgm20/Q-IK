<?php

class Instalacion {

    private $idorden;
    private $fechacreacion;
    private $nombre;
    private $fechaservicio;
    private $horaservicio;
    private $idservicio;
    private $detalle;

    function __construct() {
        
    }

    function getIdorden() {
        return $this->idorden;
    }

    function getFechacreacion() {
        return $this->fechacreacion;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getFechaservicio() {
        return $this->fechaservicio;
    }

    function getHoraservicio() {
        return $this->horaservicio;
    }

    function getIdservicio() {
        return $this->idservicio;
    }

    function getDetalle() {
        return $this->detalle;
    }

    function setIdorden($idorden) {
        $this->idorden = $idorden;
    }

    function setFechacreacion($fechacreacion) {
        $this->fechacreacion = $fechacreacion;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setFechaservicio($fechaservicio) {
        $this->fechaservicio = $fechaservicio;
    }

    function setHoraservicio($horaservicio) {
        $this->horaservicio = $horaservicio;
    }

    function setIdservicio($idservicio) {
        $this->idservicio = $idservicio;
    }

    function setDetalle($detalle) {
        $this->detalle = $detalle;
    }

}
