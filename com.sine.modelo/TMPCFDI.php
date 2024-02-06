<?php

class TMPCFDI {

    private $idtmpcfdi;
    private $tiporel;
    private $uuid;
    private $sessionid;
    
    function __construct() {
        
    }
    
    function getIdtmpcfdi() {
        return $this->idtmpcfdi;
    }

    function getTiporel() {
        return $this->tiporel;
    }

    function getUuid() {
        return $this->uuid;
    }

    function getSessionid() {
        return $this->sessionid;
    }

    function setIdtmpcfdi($idtmpcfdi) {
        $this->idtmpcfdi = $idtmpcfdi;
    }

    function setTiporel($tiporel) {
        $this->tiporel = $tiporel;
    }

    function setUuid($uuid) {
        $this->uuid = $uuid;
    }

    function setSessionid($sessionid) {
        $this->sessionid = $sessionid;
    }

}
