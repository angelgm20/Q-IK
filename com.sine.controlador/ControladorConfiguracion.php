<?php

require_once '../com.sine.dao/Consultas.php';
require_once '../com.sine.modelo/Session.php';
require_once '../com.sine.modelo/Factura.php';
require_once '../com.sine.modelo/Configuracion.php';
require_once '../vendor/autoload.php';

//use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

date_default_timezone_set("America/Mexico_City");

class ControladorConfiguracion {

    function __construct() {
        
    }

    private function getLogoFirmaAux() {
        $consultado = false;
        $consulta = "SELECT * FROM logofirma WHERE idlogofirma=:id;";
        $consultas = new Consultas();
        $valores = array("id" => '1');
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    public function getLogoFirma() {
        $datos = "";
        $data = $this->getLogoFirmaAux();
        foreach ($data as $actual) {
            $logo = $actual['logo'];
            $firma = $actual['firma'];
            $datos = "$logo</tr>$firma";
        }
        return $datos;
    }

    public function guardarFirma($firma, $firmaanterior) {
        $f = getdate();
        $d = $f['mday'];
        $m = $f['mon'];
        $y = $f['year'];
        $h = $f['hours'];
        $mi = $f['minutes'];
        $s = $f['seconds'];
        if ($d < 10) {
            $d = "0$d";
        }
        if ($m < 10) {
            $m = "0$m";
        }
        if ($h < 10) {
            $h = "0$h";
        }
        if ($mi < 10) {
            $mi = "0$mi";
        }
        if ($s < 10) {
            $s = "0$s";
        }
        $hoy = $y . '.' . $m . '.' . $d . '.' . $h . '.' . $mi . '.' . $s;
        $firma = str_replace('data:image/png;base64,', '', $firma);
        $firma = str_replace(' ', '+', $firma);
        $fileData = base64_decode($firma);
        $fileName = 'firma' . $hoy . '.png';
        file_put_contents("../img/logo/" . $fileName, $fileData);
        $actualizar = $this->actualizarFirma($fileName);
        unlink("../img/logo/$firmaanterior");
        return "<corte>" . $fileName . "<corte>";
    }

    private function actualizarFirma($firma) {
        $consulta = "UPDATE `logofirma` set firma=:firma WHERE idlogofirma=:id;);";
        $valores = array("id" => '1',
            "firma" => $firma);
        $con = new Consultas();
        $actualizado = $con->execute($consulta, $valores);
        return $actualizado;
    }

    public function guardarLogo($logo, $logoactual) {
        $consulta = "UPDATE `logofirma` set logo=:logo WHERE idlogofirma=:id;);";
        $valores = array("id" => '1',
            "logo" => $logo);
        $con = new Consultas();
        $actualizado = $con->execute($consulta, $valores);
        $div1 = explode("/", $logoactual);
        unlink("../img/logo/$div1[0]");
        $div = explode("/", $logo);
        rename("../img/" . $div[0], "../img/logo/" . $div[0]);
        return "<corte>" . $logo . "<corte>";
    }

    private function getFirmarAux($id) {
        $consultado = false;
        $consulta = "SELECT firma,nombre_contribuyente FROM datos_facturacion WHERE id_datos=:id;";
        $consultas = new Consultas();
        $valores = array("id" => $id);
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    public function getFirma($id) {
        $datos = "";
        $encabezado = $this->getFirmarAux($id);
        foreach ($encabezado as $actual) {
            $firma = $actual['firma'];
            $nombre = $actual['nombre_contribuyente'];
            $datos .= "$firma</tr>$nombre";
        }
        return $datos;
    }

    public function getDatosEncabezado($id) {
        $consultado = false;
        $consulta = "SELECT * FROM encabezados WHERE idencabezado=:id;";
        $consultas = new Consultas();
        $valores = array("id" => $id);
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    public function datosEncabezado($id) {
        $datos = "";
        $encabezado = $this->getDatosEncabezado($id);
        foreach ($encabezado as $actual) {
            $tituloencabezado = $actual['tituloencabezado'];
            $titulocarta = $actual['titulocarta'];
            $colortitulo = $actual['colortitulo'];
            $colorcelda = $actual['colorceltitulo'];
            $colorcuadro = $actual['colorcuadro'];
            $colorsubtitulos = $actual['colorsubtitulos'];
            $colorfdatos = $actual['colorfdatos'];
            $colorbold = $actual['colorbold'];
            $colortexto = $actual['colortexto'];
            $colorhtabla = $actual['colorhtabla'];
            $colortittabla = $actual['colortittabla'];
            $pagina = $actual['pagina'];
            $correo = $actual['correo'];
            $telefono1 = $actual['telefono1'];
            $telefono2 = $actual['telefono2'];
            $numpag = $actual['numpag'];
            $colorpie = $actual['colorpie'];
            $imglogo = $actual['imglogo'];
            $observaciones = $actual['observacionescot'];

            $datos = "$tituloencabezado</tr>$titulocarta</tr>$colortitulo</tr>$colorcelda</tr>$colorcuadro</tr>$colorsubtitulos</tr>$colorfdatos</tr>$colorbold</tr>$colortexto</tr>$colorhtabla</tr>$colortittabla</tr>$pagina</tr>$correo</tr>$telefono1</tr>$telefono2</tr>$numpag</tr>$colorpie</tr>$imglogo</tr>$observaciones";
        }
        return $datos;
    }

    public function actualizarEncabezado($c) {
        
        $img = $c->getImglogo();
        if ($img == "") {
            $img = $c->getImgactualizar();
        } else if ($c->getImglogo() != $c->getImgactualizar()) {
            if ($c->getImglogo() != "") {
                rename('../temporal/tmp/' . $img, '../img/logo/' . $img);
                if ($c->getChlogo() == '1') {
                    unlink('../img/logo/' . $c->getImgactualizar());
                }
            }
        }
      
        $actualizado = false;
        $consulta = "UPDATE `encabezados` set tituloencabezado=:tituloencabezado, titulocarta=:titulocarta, colortitulo=:colortitulo, colorceltitulo=:colorceltitulo, colorcuadro=:colorcuadro, colorsubtitulos=:colorsubtitulos, colorfdatos=:colorfdatos, colorbold=:colorbold, colortexto=:colortexto, colorhtabla=:colorhtabla, colortittabla=:colortittabla, pagina=:pagina, correo=:correo, telefono1=:telefono1, telefono2=:telefono2, numpag=:numpag, colorpie=:colorpie, imglogo=:imglogo, observacionescot=:observaciones WHERE idencabezado=:id";
        $valores = array("id" => $c->getIdencabezado(),
            "tituloencabezado" => $c->getTituloencabezado(),
            "titulocarta" => $c->getTitulocarta(),
            "colortitulo" => $c->getColortitulo(),
            "colorceltitulo" => $c->getColorcelda(),
            "colorcuadro" => $c->getColorcuadro(),
            "colorsubtitulos" => $c->getColorsub(),
            "colorfdatos" => $c->getColorfdatos(),
            "colorbold" => $c->getColorbold(),
            "colortexto" => $c->getColortxt(),
            "colorhtabla" => $c->getColortabla(),
            "colortittabla" => $c->getTitulostabla(),
            "pagina" => $c->getPagina(),
            "correo" => $c->getCorreo(),
            "telefono1" => $c->getTel1(),
            "telefono2" => $c->getTel2(),
            "numpag" => $c->getNumpagina(),
            "colorpie" => $c->getColorpie(),
            "imglogo" => $img,
            "observaciones" => $c->getObservaciones()
        );
        
        $con = new Consultas();
        $actualizado = $con->execute($consulta, $valores);
        if ($c->getChlogo() == '1') {
            $logos = $this->actualizarLogos($c); //dddd
        }
        return $actualizado;
    }

    private function getEncabezadosLogos($id) {
        $consultado = false;
        $consulta = "SELECT * FROM encabezados WHERE idencabezado !=:id;";
        $consultas = new Consultas();
        $valores = array("id" => $id);
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    private function actualizarLogos($c) {
        $id = $c->getIdencabezado();
        $img = $c->getImglogo();
        if ($img == "") {
            $img = $c->getImgactualizar();
        }
        $logos = $this->getEncabezadosLogos($id);
        foreach ($logos as $actual) {
            $idencabezado = $actual['idencabezado'];
            $actualizar = $this->actualizarLogoGeneral($idencabezado, $c); //dd
            copy("../img/logo/$id/$img", "../img/logo/$idencabezado/$img");
        }
    }

    public function actualizarLogoGeneral($idencabezado, $c) {
        $img = $c->getImglogo();
        if ($img == "/") {
            $img = $c->getImgactualizar();
        }
        $actualizado = false;
        $consulta = "UPDATE `encabezados` set colortitulo=:colortitulo, colorceltitulo=:colorceltitulo, colorcuadro=:colorcuadro, colorsubtitulos=:colorsubtitulos, colorfdatos=:colorfdatos, colorbold=:colorbold, colortexto=:colortexto, colorhtabla=:colorhtabla, colortittabla=:colortittabla, pagina=:pagina, correo=:correo, telefono1=:telefono1, telefono2=:telefono2, numpag=:numpag, colorpie=:colorpie, imglogo=:imglogo WHERE idencabezado=:id";
        $valores = array("id" => $idencabezado,
            "colortitulo" => $c->getColortitulo(),
            "colorceltitulo" => $c->getColorcelda(),
            "colorcuadro" => $c->getColorcuadro(),
            "colorsubtitulos" => $c->getColorsub(),
            "colorfdatos" => $c->getColorfdatos(),
            "colorbold" => $c->getColorbold(),
            "colortexto" => $c->getColortxt(),
            "colorhtabla" => $c->getColortabla(),
            "colortittabla" => $c->getTitulostabla(),
            "pagina" => $c->getPagina(),
            "correo" => $c->getCorreo(),
            "telefono1" => $c->getTel1(),
            "telefono2" => $c->getTel2(),
            "numpag" => $c->getNumpagina(),
            "colorpie" => $c->getColorpie(),
            "imglogo" => $img);
    
        $con = new Consultas();
        $actualizado = $con->execute($consulta, $valores); //sfsdf
        return $actualizado;
       
    }
    public function hex2rgb($hex) {
        $hex = str_replace("#", "", $hex);
        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        return "$r-$g-$b";
    }

    public function getMail($idcorreo) {
        $datos = "";
        $get = $this->getMailAux($idcorreo);
        foreach ($get as $actual) {
            $correo = $actual['correo'];
            $pass = $actual['password'];
            $remitente = $actual['remitente'];
            $correoremitente = $actual['correoremitente'];
            $host = $actual['host'];
            $puerto = $actual['puerto'];
            $seguridad = $actual['seguridad'];
            $chuso1 = $actual['chuso1'];
            $chuso2 = $actual['chuso2'];
            $chuso3 = $actual['chuso3'];
            $chuso4 = $actual['chuso4'];
            $chuso5 = $actual['chuso5'];
            $datos = "$correo</tr>$pass</tr>$remitente</tr>$correoremitente</tr>$host</tr>$puerto</tr>$seguridad</tr>$chuso1</tr>$chuso2</tr>$chuso3</tr>$chuso4</tr>$chuso5";
        }
        return $datos;
    }

    private function getMailAux($idcorreo) {
        $consultado = false;
        $consulta = "SELECT * FROM correoenvio WHERE idcorreoenvio=:id;";
        $consultas = new Consultas();
        $valores = array("id" => $idcorreo);
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    public function getMailBody($id) {
        $datos = "";
        $get = $this->getMailBodyAux($id);
        foreach ($get as $actual) {
            $idmailbody = $actual['idmailbody'];
            $asunto = $actual['asunto'];
            $saludo = $actual['saludo'];
            $mensaje = $actual['mensaje'];
            $imagen = $actual['logomsg'];
            $img = "";
            if ($imagen != "") {
                $imgfile = "../img/" . $imagen;
                $type = pathinfo($imgfile, PATHINFO_EXTENSION);
                $data = file_get_contents($imgfile);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                $img = "<img src=\"$base64\" height=\"100px\">";
            }
            $datos = "$idmailbody</tr>$asunto</tr>$saludo</tr>$mensaje</tr>$imagen</tr>$img";
        }
        return $datos;
    }

    private function getMailBodyAux($id) {
        $consultado = false;
        $consulta = "SELECT * FROM mailbody WHERE idmailbody=:id;";
        $consultas = new Consultas();
        $valores = array("id" => $id);
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    private function getMailbyUsoAux($uso) {
        $consultado = false;
        $consulta = "SELECT * FROM correoenvio WHERE chuso$uso=:chuso;";
        $consultas = new Consultas();
        $valores = array("chuso" => '1');
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }
    private function getMailval($correo) {
        $consultado = false;
        $consulta = "SELECT COUNT(*) maximo FROM correoenvio WHERE correo=:correo OR correoremitente = :correo";
        $consultas = new Consultas();
        $valores = array("correo" => $correo);
        $consultado = $consultas->getResults($consulta, $valores);
        foreach($consultado as $rs){
            $maximo = $rs['maximo'];
        }
        return $maximo;
    }

    private function verificarCorreo($c) {
        $existe = false;

        if($this->getMailval($c->getCorreoenvio()) > 0){
            echo "0Ya existe un correo registrado";
            $existe = true;
        } else {
            if ($c->getChusocorreo1() == '1') {
                $correos = $this->getMailbyUsoAux('1');
                foreach ($correos as $actual) {
                    echo "0Ya existe un correo asignado a Facturas";
                    $existe = true;
                    break;
                }
            }
            
            /*
            if (!$existe) {
                //if ($c->getCorreo() == $c  ) {
                    $correos = $this->getMailval($c->getCorreoenvio());
                    echo "0Ya existe un correo registrado";
                    $existe = true;
                //}
            }*/
            
            if (!$existe) {
                if ($c->getChusocorreo2() == '1') {
                    $correos = $this->getMailbyUsoAux('2');
                    foreach ($correos as $actual) {
                        echo "0Ya existe un correo asignado a Pagos";
                        $existe = true;
                        break;
                    }
                }
            }
            if (!$existe) {
                if ($c->getChusocorreo3() == '1') {
                    $correos = $this->getMailbyUsoAux('3');
                    foreach ($correos as $actual) {
                        echo "0Ya existe un correo asignado a Cotizaciones";
                        $existe = true;
                        break;
                    }
                }
            }
            if (!$existe) {
                if ($c->getChusocorreo4() == '1') {
                    $correos = $this->getMailbyUsoAux('4');
                    foreach ($correos as $actual) {
                        echo "0Ya existe un correo asignado a Comunicados";
                        $existe = true;
                        break;
                    }
                }
            }
            if (!$existe) {
                if ($c->getChusocorreo5() == '1') {
                    $correos = $this->getMailbyUsoAux('5');
                    foreach ($correos as $actual) {
                        echo "0Ya existe un correo asignado a Contratos";
                        $existe = true;
                        break;
                    }
                }
            }
        }
        return $existe;
    }
   //VAL
    public function nuevoCorreo($c) {
        $insertar = "";
        $check = $this-> verificarCorreo($c);
       
        if (!$check) {
            $insertar = $this->insertarCorreoEnvio($c);
        }
        return $insertar;
    }
    

    private function insertarCorreoEnvio($c) {
        $actualizado = false;
        $consulta = "INSERT INTO `correoenvio` VALUES (:id, :correo, :password, :remitente, :correoremitente, :host, :puerto, :seguridad, :chuso1, :chuso2, :chuso3, :chuso4, :chuso5);";
        $valores = array("id" => null,
            "correo" => $c->getCorreoenvio(),
            "password" => $c->getPasscorreo(),
            "remitente" => $c->getRemitente(),
            "correoremitente" => $c->getMailremitente(),
            "host" => $c->getHostcorreo(),
            "puerto" => $c->getPuertocorreo(),
            "seguridad" => $c->getSeguridadcorreo(),
            "chuso1" => $c->getChusocorreo1(),
            "chuso2" => $c->getChusocorreo2(),
            "chuso3" => $c->getChusocorreo3(),
            "chuso4" => $c->getChusocorreo4(),
            "chuso5" => $c->getChusocorreo5());
        $con = new Consultas();
        $actualizado = $con->execute($consulta, $valores);
        return $actualizado;
    }

    private function getMailbyUsoAux2($uso, $id) {
        $consultado = false;
        $consulta = "SELECT * FROM correoenvio WHERE chuso$uso=:chuso and idcorreoenvio!=:id;";
        $consultas = new Consultas();
        $valores = array("chuso" => '1',
            "id" => $id);
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    private function verificarActualizarCorreo($c) {
        $existe = false;
        if ($c->getChusocorreo1() == '1') {
            $correos = $this->getMailbyUsoAux2('1', $c->getIdcorreo());
            foreach ($correos as $actual) {
                echo "0Ya existe un correo asignado a Facturas";
                $existe = true;
                break;
            }
        }
        if (!$existe) {
            if ($c->getChusocorreo2() == '1') {
                $correos = $this->getMailbyUsoAux2('2', $c->getIdcorreo());
                foreach ($correos as $actual) {
                    echo "0Ya existe un correo asignado a Pagos";
                    $existe = true;
                    break;
                }
            }
        }
        if (!$existe) {
            if ($c->getChusocorreo3() == '1') {
                $correos = $this->getMailbyUsoAux2('3', $c->getIdcorreo());
                foreach ($correos as $actual) {
                    echo "0Ya existe un correo asignado a Cotizaciones";
                    $existe = true;
                    break;
                }
            }
        }
        if (!$existe) {
            if ($c->getChusocorreo4() == '1') {
                $correos = $this->getMailbyUsoAux2('4', $c->getIdcorreo());
                foreach ($correos as $actual) {
                    echo "0Ya existe un correo asignado a Comunicados";
                    $existe = true;
                    break;
                }
            }
        }
        if (!$existe) {
            if ($c->getChusocorreo5() == '1') {
                $correos = $this->getMailbyUsoAux2('5', $c->getIdcorreo());
                foreach ($correos as $actual) {
                    echo "0Ya existe un correo asignado a Contratos";
                    $existe = true;
                    break;
                }
            }
        }
        return $existe;
    }

    public function modificarCorreo($c) {
        $insertar = "";
        $check = $this->verificarActualizarCorreo($c);
        if (!$check) {
            $insertar = $this->actualizarCorreoEnvio($c);
        }
        return $insertar;
    }

    private function actualizarCorreoEnvio($c) {
        $actualizado = false;
        $consulta = "UPDATE `correoenvio` set correo=:correo, password=:password, remitente=:remitente, correoremitente=:correoremitente, host=:host, puerto=:puerto, seguridad=:seguridad, chuso1=:chuso1, chuso2=:chuso2, chuso3=:chuso3, chuso4=:chuso4, chuso5=:chuso5 WHERE idcorreoenvio=:id;);";
        $valores = array("id" => $c->getIdcorreo(),
            "correo" => $c->getCorreoenvio(),
            "password" => $c->getPasscorreo(),
            "remitente" => $c->getRemitente(),
            "correoremitente" => $c->getMailremitente(),
            "host" => $c->getHostcorreo(),
            "puerto" => $c->getPuertocorreo(),
            "seguridad" => $c->getSeguridadcorreo(),
            "chuso1" => $c->getChusocorreo1(),
            "chuso2" => $c->getChusocorreo2(),
            "chuso3" => $c->getChusocorreo3(),
            "chuso4" => $c->getChusocorreo4(),
            "chuso5" => $c->getChusocorreo5());
        $con = new Consultas();
        $actualizado = $con->execute($consulta, $valores);
        return $actualizado;
    }

    public function actualizarBodyMail($c) {
        $actualizado = false;
        $con = new Consultas();
        $img = $c->getImglogo();
        if ($img == '') {
            $img = $c->getImgactualizar();
        }
        $consulta = "UPDATE `mailbody` SET asunto=:asunto, saludo=:saludo, mensaje=:mensaje, logomsg=:img WHERE idmailbody=:id;";
        $valores = array("id" => $c->getIdbodymail(),
            "asunto" => $c->getAsuntobody(),
            "saludo" => $c->getSaludobody(),
            "mensaje" => $c->getTxtbody(),
            "img" => $img);
        rename("../temporal/tmp/" . $img, "../img/" . $img);
        $actualizado = $con->execute($consulta, $valores);
        if ($c->getChlogo()) {
            $this->updateLogoBody($c->getIdbodymail(), $img);
        }
        return $actualizado;
    }

    private function updateLogoBody($id, $img) {
        $actualizado = FALSE;
        $con = new Consultas();
        $consulta = $consulta = "UPDATE `mailbody` SET logomsg=:img WHERE idmailbody!=:id;";
        $val = array("img" => $img,
            "id" => $id);
        $actualizado = $con->execute($consulta, $val);
        return $actualizado;
    }

    private function getUsuariosAux($con = "") {
        $consultado = false;
        $consulta = "select * from usuario $con order by nombre;";
        $consultas = new Consultas();
        $consultado = $consultas->getResults($consulta, null);
        return $consultado;
    }

    private function checkComisionUsuarioAux($idusuario) {
        $consultado = false;
        $consulta = "select * from comisionusuario where comision_idusuario=:id;";
        $val = array("id" => $idusuario);
        $consultas = new Consultas();
        $consultado = $consultas->getResults($consulta, $val);
        return $consultado;
    }

    private function checkComisionUsuario($idusuario) {
        $datos = "";
        $get = $this->checkComisionUsuarioAux($idusuario);
        foreach ($get as $actual) {
            $idcomisionusuario = $actual['idcomisionusuario'];
            $idusu = $actual['comision_idusuario'];
            $porcentaje = $actual['comisionporcentaje'];
            $calculo = $actual['calculo'];
            $datos = "$idcomisionusuario</tr>$idusu</tr>$porcentaje</tr>$calculo";
        }
        return $datos;
    }

    public function datosUsuario($idusuario) {
        $get = $this->getUsuariosAux("where idusuario='$idusuario'");
        $datos = "";
        $check = '0';
        foreach ($get as $actual) {
            $tipo = $actual['tipo'];
            $datcom = $this->checkComisionUsuario($idusuario);
            if ($datcom != "") {
                $check = '1';
            }
            $datos .= "$tipo</tr>$check</tr>$datcom";
        }
        return $datos;
    }

    public function insertarComision($f) {
        $insertado = false;
        $consulta = "INSERT INTO `comisionusuario` VALUES (:id, :idusuario, :porcentaje, :calculo);";
        $valores = array("id" => null,
            "idusuario" => $f->getIdusuario(),
            "porcentaje" => $f->getPorcentaje(),
            "calculo" => $f->getChcalculo());
        $con = new Consultas();
        $con->execute($consulta, $valores);
        $insertado = true;

        if ($f->getChcom() == '1') {
            $auto = $this->checkComision($f);
        }
        return $insertado;
    }

    public function actualizarComision($f) {
        $insertado = false;
        $consulta = "UPDATE `comisionusuario` SET comisionporcentaje=:porcentaje, calculo=:calculo where idcomisionusuario=:id;";
        $valores = array("id" => $f->getIdcomision(),
            "porcentaje" => $f->getPorcentaje(),
            "calculo" => $f->getChcalculo());
        $con = new Consultas();
        $con->execute($consulta, $valores);
        if ($f->getChcom() == '1') {
            $auto = $this->checkComision($f);
        }
        return $insertado;
    }

    public function quitarComision($idcomision) {
        $eliminado = false;
        $consulta = "DELETE FROM `comisionusuario` WHERE idcomisionusuario=:id;";
        $valores = array("id" => $idcomision);
        $consultas = new Consultas();
        $eliminado = $consultas->execute($consulta, $valores);
        return $eliminado;
    }

    private function checkComision($f) {
        $comision = "";
        $getusu = $this->getUsuariosAux();
        foreach ($getusu as $actual) {
            $check = false;
            $idusuario = $actual['idusuario'];
            $com = $this->checkComisionUsuarioAux($idusuario);
            foreach ($com as $comactual) {
                $idcom = $comactual['idcomisionusuario'];
                $check = true;
            }
            if ($check) {
                $comision = $this->actualizarComision2($f, $idcom);
            } else {
                $comision = $this->insertarComision2($f, $idusuario);
            }
        }
        return $comision;
    }

    private function insertarComision2($f, $idusuario) {
        $insertado = false;
        $consulta = "INSERT INTO `comisionusuario` VALUES (:id, :idusuario, :porcentaje, :calculo);";
        $valores = array("id" => null,
            "idusuario" => $idusuario,
            "porcentaje" => $f->getPorcentaje(),
            "calculo" => $f->getChcalculo());
        $con = new Consultas();
        $con->execute($consulta, $valores);
        return $insertado;
    }

    private function actualizarComision2($f, $idcom) {
        $insertado = false;
        $consulta = "UPDATE `comisionusuario` SET comisionporcentaje=:porcentaje, calculo=:calculo where idcomisionusuario=:id;";
        $valores = array("id" => $idcom,
            "porcentaje" => $f->getPorcentaje(),
            "calculo" => $f->getChcalculo());
        $con = new Consultas();
        $con->execute($consulta, $valores);
        return $insertado;
    }

    public function mailPrueba($sm) {
        $mail = new PHPMailer;
        //$mail->isSMTP();
        $mail->Mailer = 'smtp';
        $mail->SMTPAuth = true;
        $mail->Host = $sm->getHostcorreo();
        $mail->Port = $sm->getPuertocorreo();
        $mail->SMTPSecure = $sm->getSeguridadcorreo();

        $mail->Username = $sm->getCorreoenvio();
        $mail->Password = $sm->getPasscorreo();
        $mail->From = $sm->getMailremitente();
        $mail->FromName = $sm->getRemitente();

        $mail->Subject = iconv("utf-8","windows-1252","Prueba de conexion de correo");
        

        $mail->isHTML(true);
        $mail->Body = "<html>
    <body>
        <table width='100%' bgcolor='#e0e0e0' cellpadding='0' cellspacing='0' border='0' style='border-radius: 25px;'>
            <tr><td>
                    <table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' style='max-width:650px; border-radius: 20px; background-color:#fff; font-family:sans-serif;'>
                        <thead>
                            <tr height='80'>
                                <th align='left' colspan='4' style='padding: 6px; background-color:#f5f5f5; border-radius: 20px; border-bottom:solid 1px #bdbdbd;' ><img src='https://q-ik.mx/Demo/img/LogoQik.png' height='100px'/></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr align='center' height='10' style='font-family:sans-serif; '>
                                <td style='background-color:#09096B; text-align:center; border-radius: 5px;'></td>
                            </tr>
                            <tr>
                                <td colspan='4' style='padding:15px;'>
                                    <h1>Prueba de conexion</h1>
                                    <p style='font-size:20px;'>Hola soy un correo de prueba</p>
                                    <hr/>
                                    <p style='font-size:18px; text-align: justify;'>Si recibiste este mensaje, significa que la configuracion del correo que ingresaste para el envio de tus facturas se hizo correctamente.</p>
                                    <p style='font-size:18px; text-align: justify;'>Tu sistema ahora esta listo para enviar correos, recuerda que los correos se envian desde esta cuenta por lo que las posibles respuestas de tus clientes o avisos de errores de envio por direcciones incorrectas o inexistentes llegaran aqui.</p>
                                    <p style='font-size:18px; text-align: justify;'>Gracias y un saludo por parte del equipo de <span style='font-family:sans-serif; color: #17177c; font-weight: bolder;'>Q-ik</span>.</p>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </td></tr>
        </table>
    </body>
</html>";
        $mail->addAddress($sm->getCorreoenvio());
        if (!$mail->send()) {
            echo '0No se envio el mensaje';
            echo '0Mailer Error: ' . $mail->ErrorInfo;
        } else {
            return '1Se ha enviado la factura';
        }
    }

    


  

   

}
