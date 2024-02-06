<?php

class CFDI {
    private $idcatalogo;
    private $clave;
    private $descripcion;
    function __construct() {
        
    }
    function getIdcatalogo() {
        return $this->idcatalogo;
    }

    function getClave() {
        return $this->clave;
    }

    function getDescripcion() {
        return $this->descripcion;
    }
    
    function setIdcatalogo($idcatalogo) {
        $this->idcatalogo = $idcatalogo;
    }

    function setClave($clave) {
        $this->clave = $clave;
    }

    function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }


}
