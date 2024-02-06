<?php

require_once 'com.sine.modelo/Session.php';
require_once 'com.sine.dao/Consultas.php';

class ControladorSessionPost {

    function __construct() {
        
    }

    private function autenticarUsuarioPost($u, $p) {
        $consultado = false;
        $consulta = "SELECT * FROM usuario WHERE usuario=:usuario and password=:contrasena LIMIT 1;";
        $valores = array("usuario" => $u, "contrasena" => $p);
        $c = new Consultas();
        $consultado = $c->getResults($consulta, $valores);
        return $consultado;
    }

    public function loginPost($u,$p) {
        $existe = false;
        $resultados = $this->autenticarUsuarioPost($u,$p);
        foreach ($resultados as $resultado) {
            Session::start();
            $existe = true;
            $_SESSION[sha1("usuario")] = $resultado['usuario'];
            $_SESSION[sha1("idusuario")] = $resultado['idusuario'];
            $_SESSION[sha1("tipousuario")] = $resultado['tipo'];
        }
        return $existe;
    }

    private function checkAcceso($fecha) {
        $inter = false;
        $d = new DateTime(date('Y-m-d H:i:s'));
        $d2 = new DateTime($fecha);
        $intervalo = $d->diff($d2);
        if ($intervalo->format('%a') >= '15') {
            $inter = true;
        }
        return $inter;
    }

    public function sessionIsActive() {
        $activa = false;
        Session::start();
        $existe = true;
        if (isset($_SESSION[sha1("usuario")])) {
            $activa = true;
        }
        return $activa;
    }

    public function logout($b) {
        $destruido = false;
        if ($b == 'ab125?=o9_.2') {
            Session::start();
            Session::destroy();
            $destruido = true;
        }
        return $destruido;
    }

}
