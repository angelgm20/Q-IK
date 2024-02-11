<?php

require_once '../com.sine.modelo/Configuracion.php';
require_once '../com.sine.modelo/Folios.php';
require_once '../com.sine.controlador/ControladorConfiguracion.php';
if (isset($_POST['transaccion'])) {
    $transaccion = $_POST['transaccion'];
    switch ($transaccion) {
        case 'editarencabezado':
            $cf = new ControladorConfiguracion();
            $id = $_POST['encabezado'];
            $datos = $cf->datosEncabezado($id);
            if ($datos != "") {
                echo $datos;
            } else {
                echo "0Boton Activo";
            }
            break;
        case 'actualizarencabezado':
            $c = new Configuracion();
            $cc = new ControladorConfiguracion();
            
            $idencabezado = $_POST['idencabezado'];
            $titulo = $_POST['titulo'];
            $titulocarta = $_POST['titulocarta'];
            $clrtitulo = $_POST['clrtitulo'];
            $colorcelda = $_POST['colorcelda'];
            $clrcuadro = $_POST['clrcuadro'];
            $clrsub = $_POST['clrsub'];
            $clrfdatos = $_POST['clrfdatos'];
            $txtbold = $_POST['txtbold'];
            $clrtxt = $_POST['clrtxt'];
            $colorhtabla = $_POST['colorhtabla'];
            $tittabla = $_POST['tittabla'];
            $pagina = $_POST['pagina'];
            $correo = $_POST['correo'];
            $tel1 = $_POST['tel1'];
            $tel2 = $_POST['tel2'];
            $clrpie = $_POST['clrpie'];
            $chnum = $_POST['chnum'];
            $chlogo = $_POST['chlogo'];
            $observaciones = $_POST['observaciones'];
            $imagen = $_POST["imagen"];
            $imgactualizar = $_POST['imgactualizar'];

            $c->setIdencabezado($idencabezado);
            $c->setTituloencabezado($titulo);
            $c->setTitulocarta($titulocarta);
            $c->setColortitulo($clrtitulo);
            $c->setColorcelda($colorcelda);
            $c->setColorcuadro($clrcuadro);
            $c->setColorsub($clrsub);
            $c->setColorfdatos($clrfdatos);
            $c->setColorbold($txtbold);
            $c->setColortxt($clrtxt);
            $c->setColortabla($colorhtabla);
            $c->setTitulostabla($tittabla);
            $c->setPagina($pagina);
            $c->setCorreo($correo);
            $c->setTel1($tel1);
            $c->setTel2($tel2);
            $c->setColorpie($clrpie);
            $c->setImglogo($imagen);
            $c->setImgactualizar($imgactualizar);
            $c->setNumpagina($chnum);
            $c->setChlogo($chlogo);
            $c->setObservaciones($observaciones);

            $actualizado = $cc->actualizarEncabezado($c); //fsf
            if (!$actualizado) {
                //echo "ya";
            } else {
                echo "0Error en la actualización de encabezados: " ;
            }
            
            break;
            case 'testcolor':
                $cf = new ControladorConfiguracion();
                $color = $_POST['color'];
                $datos = $cf->hex2rgb($color);
                if ($datos != "") {
                    echo $datos;
                } else {
                    echo "0Boton Activo";
                }
                break;
        case 'loadmail':
            $cf = new ControladorConfiguracion();
            $idcorreo = $_POST['idcorreo'];
            $datos = $cf->getMail($idcorreo);
            if ($datos != "") {
                echo $datos;
            } else {
                echo "0No se han encontrado Datos";
            }
            break;
        case 'editarbody':
            $cf = new ControladorConfiguracion();
            $id = $_POST['idbody'];
            $datos = $cf->getMailBody($id);
            if ($datos != "") {
                echo $datos;
            } else {
                echo "0Boton Activo";
            }
            break;

        case 'insertarcorreo':
            $c = new Configuracion();
            $cc = new ControladorConfiguracion();
            
            $correo = $_POST['correo'];
            $pass = $_POST['pass'];
            $remitente = $_POST['remitente'];
            $mailremitente = $_POST['mailremitente'];
            $host = $_POST['host'];
            $puerto = $_POST['puerto'];
            $seguridad = $_POST['seguridad'];
            $chuso1 = $_POST['chuso1'];
            $chuso2 = $_POST['chuso2'];
            $chuso3 = $_POST['chuso3'];
            $chuso4 = $_POST['chuso4'];
            $chuso5 = $_POST['chuso5'];

            $c->setCorreoenvio($correo);
            $c->setPasscorreo($pass);
            $c->setRemitente($remitente);
            $c->setMailremitente($mailremitente);
            $c->setHostcorreo($host);
            $c->setPuertocorreo($puerto);
            $c->setSeguridadcorreo($seguridad);
            $c->setChusocorreo1($chuso1);
            $c->setChusocorreo2($chuso2);
            $c->setChusocorreo3($chuso3);
            $c->setChusocorreo4($chuso4);
            $c->setChusocorreo5($chuso5);

            echo $insertado = $cc->nuevoCorreo($c);
            break;

        case 'actualizarcorreo':
            $c = new Configuracion();
            $cc = new ControladorConfiguracion();
            
            $idcorreo = $_POST['idcorreo'];
            $correo = $_POST['correo'];
            $pass = $_POST['pass'];
            $remitente = $_POST['remitente'];
            $mailremitente = $_POST['mailremitente'];
            $host = $_POST['host'];
            $puerto = $_POST['puerto'];
            $seguridad = $_POST['seguridad'];
            $chuso1 = $_POST['chuso1'];
            $chuso2 = $_POST['chuso2'];
            $chuso3 = $_POST['chuso3'];
            $chuso4 = $_POST['chuso4'];
            $chuso5 = $_POST['chuso5'];

            $c->setIdcorreo($idcorreo);
            $c->setCorreoenvio($correo);
            $c->setPasscorreo($pass);
            $c->setRemitente($remitente);
            $c->setMailremitente($mailremitente);
            $c->setHostcorreo($host);
            $c->setPuertocorreo($puerto);
            $c->setSeguridadcorreo($seguridad);
            $c->setChusocorreo1($chuso1);
            $c->setChusocorreo2($chuso2);
            $c->setChusocorreo3($chuso3);
            $c->setChusocorreo4($chuso4);
            $c->setChusocorreo5($chuso5);

            $insertado = $cc->modificarCorreo($c);
            if ($insertado) {
                echo $insertado;
            } else {
                echo "0Error: no inserto el registro ";
            }
            break;
        case 'actualizarbody':
            $c = new Configuracion();
            $cc = new ControladorConfiguracion();
            
            $idbody = $_POST['idbody'];
            $asunto = $_POST['asunto'];
            $saludo = $_POST['saludo'];
            $txtbd = $_POST['txtbd'];
            $filenm = $_POST['filenm'];
            $imgactualizar = $_POST['imgactualizar'];
            $chlogo = $_POST['chlogo'];

            $c->setIdbodymail($idbody);
            $c->setAsuntobody($asunto);
            $c->setSaludobody($saludo);
            $c->setTxtbody($txtbd);
            $c->setImglogo($filenm);
            $c->setImgactualizar($imgactualizar);
            $c->setChlogo($chlogo);

            $insertado = $cc->actualizarBodyMail($c);
            if ($insertado) {
                echo $insertado;
            } else {
                echo "0Error: no inserto el registro ";
            }
            break;
        case 'loadfirma':
            $cc = new ControladorConfiguracion();
            
            $insertado = $cc->getLogoFirma();
            if ($insertado) {
                echo $insertado;
            } else {
                echo "0Error: no inserto el registro ";
            }
            break;
        case 'guardarfirma':
            $cc = new ControladorConfiguracion();
            
            $firma = $_POST['firma'];
            $firmaanterior = $_POST['firmaanterior'];
            
            $insertado = $cc->guardarFirma($firma, $firmaanterior);
            if ($insertado) {
                echo $insertado;
            } else {
                echo "0Error: no inserto el registro ";
            }
            
            break;
        case 'guardarlogo':
            $cc = new ControladorConfiguracion();
            
            $logoactual = $_POST['logoactual'];
            
            if (!isset($_POST["logo"])) {
                echo "Ha ocurrido un error. No se encontro el Nombre del archivo.";
            } else {
                $datos_imagen = $_POST["logo"];
                $nombre_imagen_aux = explode("\\", $datos_imagen);
                $tamano_arreglo = count($nombre_imagen_aux);
                $nombre_imagen = $nombre_imagen_aux[$tamano_arreglo - 1];
                $tipo_imagen_aux = explode(".", $nombre_imagen);
                $tamano_arreglo = count($tipo_imagen_aux);
                $tipo_imagen = $tipo_imagen_aux[$tamano_arreglo - 1];
                $logo = $nombre_imagen . "/" . $tipo_imagen;
            }
            
            $insertado = $cc->guardarLogo($logo, $logoactual);
            if ($insertado) {
                echo $insertado;
            } else {
                echo "0Error: no inserto el registro ";
            }
            
            break;
        
        case 'datosusuario':
            $cf = new ControladorConfiguracion();
            $idusuario = $_POST['idusuario'];
            $datos = $cf->datosUsuario($idusuario);
            if ($datos != "") {
                echo $datos;
            } else {
                echo "0No hay clientes registrados";
            }
            break;
            

        case 'insertarcomision':
            $f = new Configuracion();
            $cc = new ControladorConfiguracion();
            
            $idusuario = $_POST['idusuario'];
            $porcentaje = $_POST['porcentaje'];
            $chcalculo = $_POST['chcalculo'];
            $chcom = $_POST['chcom'];

            $f->setIdusuario($idusuario);
            $f->setPorcentaje($porcentaje);
            $f->setChcalculo($chcalculo);
            $f->setChcom($chcom);

            $insertado = $cc->insertarComision($f);
            if ($insertado) {
                echo $insertado;
            } else {
                echo "0Error: no inserto la comision ";
            }
            break;

        if ($_POST['transaccion'] == 'quitarcomision') {
                    $idUsuario = $_POST['idUsuario'];
                    echo json_encode(['success' => true, 'message' => 'Comisión quitada con éxito.']);
                    exit;
            }
     
        case 'actualizarcomision':
            $f = new Configuracion();
            $cc = new ControladorConfiguracion();
            
            $idcomision = $_POST['idcomision'];
            $idusuario = $_POST['idusuario'];
            $porcentaje = $_POST['porcentaje'];
            $chcalculo = $_POST['chcalculo'];
            $chcom = $_POST['chcom'];

            $f->setIdcomision($idcomision);
            $f->setIdusuario($idusuario);
            $f->setPorcentaje($porcentaje);
            $f->setChcalculo($chcalculo);
            $f->setChcom($chcom);

            $insertado = $cc->actualizarComision($f);
            if ($actualizado) {
                echo $actualizado;
            } else {
                echo "0Error: no se actualizó el registro ";
            }
            
            break;
        
            case 'quitarcomision':  
                $cp = new ControladorConfiguracion();
                $idcomision = $_POST['idcomision'];
                $eliminado = $cp->quitarComision($idcomision);
                if ($eliminado) {
                    echo "Comision eliminada";
                } else {
                    echo "No se han encontrado datos";
                }
                break; 
            
        
            case 'testcorreo':
                $c = new Configuracion();
                $cc = new ControladorConfiguracion();
                
                $correo = $_POST['correo'];
                $pass = $_POST['pass'];
                $remitente = $_POST['remitente'];
                $mailremitente = $_POST['mailremitente'];
                $host = $_POST['host'];
                $puerto = $_POST['puerto'];
                $seguridad = $_POST['seguridad'];
            
                $c->setCorreoenvio($correo);
                $c->setPasscorreo($pass);
                $c->setRemitente($remitente);
                $c->setMailremitente($mailremitente);
                $c->setHostcorreo($host);
                $c->setPuertocorreo($puerto);
                $c->setSeguridadcorreo($seguridad);
                
                $datos = $cc->mailPrueba($c);
            
                if ($datos != "") {
                    echo $datos;
                } else {
                    $lastError = error_get_last();
                    if ($lastError !== null) {
                        echo "0Error de conexión SMTP: " . $lastError['message'];
                    } else {
                        echo "0No hay clientes registrados";
                    }
                }
                break;
}
}
