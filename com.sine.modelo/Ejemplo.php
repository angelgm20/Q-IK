<?php

class Ejemplo {

    private $nombre;

    function __construct() {
        
    }

    function getNombre() {
        return $this->nombre;
    }


    function setNombre($nombre) {
        $this->nombre = $nombre;
    }


    function setEstatus($estatus) {
        $this->estatus = $estatus;
    }

}
