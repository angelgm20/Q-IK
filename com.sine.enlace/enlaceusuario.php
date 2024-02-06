<?php

require_once '../com.sine.modelo/Usuario.php';
require_once '../com.sine.modelo/Permiso.php';
require_once '../com.sine.controlador/ControladorUsuario.php';
if (isset($_POST['transaccion'])) {
    $transaccion = $_POST['transaccion'];
    switch ($transaccion) {
        case 'gettipousuario':
            $cu = new ControladorUsuario();
            $datos = $cu->getTipoLogin();
            if ($datos != "") {
                echo $datos;
            } else {
                echo "0No se han econtrado datos";
            }
            break;
        case 'insertrausuario':
            $u = new Usuario();
            $cu = new ControladorUsuario();
            $nombre = $_POST['nombre'];
            $apellidopaterno = $_POST['apellidopaterno'];
            $apellidomaterno = $_POST['apellidomaterno'];
            $usuario = $_POST['usuario'];
            $contrasena = sha1($_POST['password']);
            $correo = $_POST['correo'];
            $celular = $_POST['celular'];
            $telefono = $_POST['telefono'];
            $tipou = $_POST['tipo'];
            $img = $_POST["img"];

            $u->setNombre($nombre);
            $u->setApellidoPaterno($apellidopaterno);
            $u->setApellidoMaterno($apellidomaterno);
            $u->setUsuario($usuario);
            $u->setContrasena($contrasena);
            $u->setCorreo($correo);
            $u->setCelular($celular);
            $u->setTelefono($telefono);
            $u->setEstatus("activo");
            $u->setTipo($tipou);
            $u->setImg($img);

            $insertado = $cu->nuevoUsuario($u);
            if (!$insertado) {
                echo "Registro Insertado";
            } else {
                echo "0Error: no inserto el registro ";
            }
            break;
        case 'listausuariosaltas':
            $cu = new ControladorUsuario();
            $datos = $cu->listaUsuariosAltas();
            if ($datos != "") {
                echo $datos;
            } else {
                echo "0No se han econtrado datos";
            }
            break;
        case 'editarusuario':
            $cu = new ControladorUsuario();
            $idusuario = $_POST['idusuario'];
            $datos = $cu->getDatosUsuario($idusuario);
            if ($datos != "") {
                echo $datos;
            } else {
                echo "0No se han econtrado datos";
            }
            break;
        case 'asignarpermiso':
            $cu = new ControladorUsuario();
            $idusuario = $_POST['idusuario'];
            $datos = $cu->checkPermisos($idusuario);
            if ($datos != "") {
                echo $datos;
            } else {
                echo "0No se han econtrado datos";
            }
            break;
        case 'actualizarusuario':
            $u = new Usuario();
            $cu = new ControladorUsuario();
            $idusuario = $_POST['idusuario'];
            $nombre = $_POST['nombre'];
            $apellidopaterno = $_POST['apellidopaterno'];
            $apellidomaterno = $_POST['apellidomaterno'];
            $usuario = $_POST['usuario'];
            $correo = $_POST['correo'];
            $contrasena = $_POST['contrasena'];
            $celular = $_POST['celular'];
            $telefono = $_POST['telefono'];
            $tipo = $_POST['tipo'];
            $chpass = $_POST['chpass'];
            $img = $_POST["img"];
            $imgactualizar = $_POST['imgactualizar'];

            $u->setIdUsuario($idusuario);
            $u->setNombre($nombre);
            $u->setApellidoPaterno($apellidopaterno);
            $u->setApellidoMaterno($apellidomaterno);
            $u->setUsuario($usuario);
            $u->setCorreo($correo);
            $u->setCelular($celular);
            $u->setTelefono($telefono);
            $u->setContrasena($contrasena);
            $u->setEstatus("activo");
            $u->setTipo($tipo);
            $u->setChpass($chpass);
            $u->setImg($img);
            $u->setImgactualizar($imgactualizar);

            $actualizado = $cu->modificarUsuario($u);
            if ($actualizado) {
                echo "Registro guardado";
            } else {
                echo "0Error: no guardo el registro ";
            }
            break;
        case 'actualizarimg':
            $u = new Usuario();
            $cu = new ControladorUsuario();
            $idusuario = $_POST['idusuario'];
            $img = $_POST['img'];
            $imgactualizar = $_POST['imgactualizar'];
            
            $u->setIdUsuario($idusuario);
            $u->setImg($img);
            $u->setImgactualizar($imgactualizar);

            $actualizado = $cu->actualizarImgPerfil($u);
            if ($actualizado) {
                echo "Registro guardado";
            } else {
                echo "0Error: no guardo el registro ";
            }
            break;
        case 'insertarpermisos':
            $p = new Permiso();
            $cu = new ControladorUsuario();

            $idusuario = $_POST['idusuario'];
            $facturas = $_POST['facturas'];
            $crearfactura = $_POST['crearfactura'];
            $editarfactura = $_POST['editarfactura'];
            $eliminarfactura = $_POST['eliminarfactura'];
            $listafactura = $_POST['listafactura'];
            $pago = $_POST['pago'];
            $crearpago = $_POST['crearpago'];
            $editarpago = $_POST['editarpago'];
            $eliminarpago = $_POST['eliminarpago'];
            $listapago = $_POST['listapago'];
            $cotizacion = $_POST['cotizacion'];
            $crearcotizacion = $_POST['crearcotizacion'];
            $editarcot = $_POST['editarcot'];
            $eliminarcot = $_POST['eliminarcot'];
            $listacotizacion = $_POST['listacotizacion'];
            $anticipo = $_POST['anticipo'];
            $cliente = $_POST['cliente'];
            $crearcat = $_POST['crearcat'];
            $editarcat = $_POST['editarcat'];
            $eliminarcat = $_POST['eliminarcat'];
            $listacat = $_POST['listacat'];
            $crearcliente = $_POST['crearcliente'];
            $editarcliente = $_POST['editarcliente'];
            $eliminarcliente = $_POST['eliminarcliente'];
            $listacliente = $_POST['listacliente'];
            $comunicado = $_POST['comunicado'];
            $crearcomunicado = $_POST['crearcomunicado'];
            $editarcomunicado = $_POST['editarcomunicado'];
            $eliminarcomunicado = $_POST['eliminarcomunicado'];
            $listacomunicado = $_POST['listacomunicado'];
            $producto = $_POST['producto'];
            $crearproducto = $_POST['crearproducto'];
            $editarproducto = $_POST['editarproducto'];
            $eliminarproducto = $_POST['eliminarproducto'];
            $listaproducto = $_POST['listaproducto'];
            $proveedor = $_POST['proveedor'];
            $crearproveedor = $_POST['crearproveedor'];
            $editarproveedor = $_POST['editarproveedor'];
            $eliminarproveedor = $_POST['eliminarproveedor'];
            $listaproveedor = $_POST['listaproveedor'];
            $impuesto = $_POST['impuesto'];
            $crearimpuesto = $_POST['crearimpuesto'];
            $editarimpuesto = $_POST['editarimpuesto'];
            $eliminarimpuesto = $_POST['eliminarimpuesto'];
            $listaimpuesto = $_POST['listaimpuesto'];
            $datosfacturacion = $_POST['datosfacturacion'];
            $creardatos = $_POST['creardatos'];
            $editardatos = $_POST['editardatos'];
            $listadatos = $_POST['listadatos'];
            $contrato = $_POST['contrato'];
            $crearcontrato = $_POST['crearcontrato'];
            $editarcontrato = $_POST['editarcontrato'];
            $eliminarcontrato = $_POST['eliminarcontrato'];
            $listacontrato = $_POST['listacontrato'];
            $usuarios = $_POST['usuarios'];
            $crearusuario = $_POST['crearusuario'];
            $listausuario = $_POST['listausuario'];
            $eliminarusuario = $_POST['eliminarusuario'];
            $asigpermiso = $_POST['asigpermiso'];
            $reporte = $_POST['reporte'];
            $reportefactura = $_POST['reportefactura'];
            $reportepago = $_POST['reportepago'];
            $reportegrafica = $_POST['reportegrafica'];
            $reporteiva = $_POST['reporteiva'];
            $datosiva = $_POST['datosiva'];
            $reporteventas = $_POST['reporteventas'];
            $configuracion = $_POST['configuracion'];
            $addfolio = $_POST['addfolio'];
            $listafolio = $_POST['listafolio'];
            $editfolio = $_POST['editfolio'];
            $eliminarfolio = $_POST['eliminarfolio'];
            $addcomision = $_POST['addcomision'];
            $encabezados = $_POST['encabezados'];
            $confcorreo = $_POST['confcorreo'];

            $p->setIdUsuario($idusuario);
            $p->setFacturas($facturas);
            $p->setCrearfactura($crearfactura);
            $p->setEditarfactura($editarfactura);
            $p->setEliminarfactura($eliminarfactura);
            $p->setListafactura($listafactura);
            $p->setPago($pago);
            $p->setCrearpago($crearpago);
            $p->setEditarpago($editarpago);
            $p->setEliminarpago($eliminarpago);
            $p->setListapago($listapago);
            $p->setCotizacion($cotizacion);
            $p->setCrearcotizacion($crearcotizacion);
            $p->setEditarcot($editarcot);
            $p->setEliminarcot($eliminarcot);
            $p->setListacotizacion($listacotizacion);
            $p->setAnticipo($anticipo);
            $p->setCliente($cliente);
            $p->setCrearcat($crearcat);
            $p->setEditarcat($editarcat);
            $p->setEliminarcat($eliminarcat);
            $p->setListacat($listacat);
            $p->setCrearcliente($crearcliente);
            $p->setEditarcliente($editarcliente);
            $p->setEliminarcliente($eliminarcliente);
            $p->setListacliente($listacliente);
            $p->setComunicado($comunicado);
            $p->setCrearcomunicado($crearcomunicado);
            $p->setEditarcomunicado($editarcomunicado);
            $p->setEliminarcomunicado($eliminarcomunicado);
            $p->setListacomunicado($listacomunicado);
            $p->setProducto($producto);
            $p->setCrearproducto($crearproducto);
            $p->setEditarproducto($editarproducto);
            $p->setEliminarproducto($eliminarproducto);
            $p->setListaproducto($listaproducto);
            $p->setProveedor($proveedor);
            $p->setCrearproveedor($crearproveedor);
            $p->setEditarproveedor($editarproveedor);
            $p->setEliminarproveedor($eliminarproveedor);
            $p->setListaproveedor($listaproveedor);
            $p->setImpuesto($impuesto);
            $p->setCrearimpuesto($crearimpuesto);
            $p->setEditarimpuesto($editarimpuesto);
            $p->setEliminarimpuesto($eliminarimpuesto);
            $p->setListaimpuesto($listaimpuesto);
            $p->setDatosfacturacion($datosfacturacion);
            $p->setCreardatos($creardatos);
            $p->setEditardatos($editardatos);
            $p->setListadatos($listadatos);
            $p->setContrato($contrato);
            $p->setCrearcontrato($crearcontrato);
            $p->setEditarcontrato($editarcontrato);
            $p->setEliminarcontrato($eliminarcontrato);
            $p->setListacontrato($listacontrato);
            $p->setUsuarios($usuarios);
            $p->setCrearusuario($crearusuario);
            $p->setListausuario($listausuario);
            $p->setEliminarusuario($eliminarusuario);
            $p->setAsignarpermisos($asigpermiso);
            $p->setReporte($reporte);
            $p->setReportefactura($reportefactura);
            $p->setReportepago($reportepago);
            $p->setReportegrafica($reportegrafica);
            $p->setReporteiva($reporteiva);
            $p->setDatosiva($datosiva);
            $p->setReporteventas($reporteventas);
            $p->setConfiguracion($configuracion);
            $p->setAddfolio($addfolio);
            $p->setListafolio($listafolio);
            $p->setEditfolio($editfolio);
            $p->setEliminarfolio($eliminarfolio);
            $p->setAddcomision($addcomision);
            $p->setEncabezados($encabezados);
            $p->setConfcorreo($confcorreo);
            $actualizado = $cu->insertarPermisosList($p);
            if ($actualizado) {
                echo $actualizado;
            } else {
                echo "0Error: no guardo el registro ";
            }
            break;
        case 'actualizarpermisos':
            $p = new Permiso();
            $cu = new ControladorUsuario();

            $idusuario = $_POST['idusuario'];
            $accion = $_POST['accion'];
            $facturas = $_POST['facturas'];
            $crearfactura = $_POST['crearfactura'];
            $editarfactura = $_POST['editarfactura'];
            $eliminarfactura = $_POST['eliminarfactura'];
            $listafactura = $_POST['listafactura'];
            $pago = $_POST['pago'];
            $crearpago = $_POST['crearpago'];
            $editarpago = $_POST['editarpago'];
            $eliminarpago = $_POST['eliminarpago'];
            $listapago = $_POST['listapago'];
            $nomina = $_POST['nomina'];
            $listaempleado = $_POST['listaempleado'];
            $crearempleado = $_POST['crearempleado'];
            $editarempleado = $_POST['editarempleado'];
            $eliminarempleado = $_POST['eliminarempleado'];
            $listanomina = $_POST['listanomina'];
            $crearnomina = $_POST['crearnomina'];
            $editarnomina = $_POST['editarnomina'];
            $eliminarnomina = $_POST['eliminarnomina'];
            $cartaporte = $_POST['cartaporte'];
            $listaubicacion = $_POST['listaubicacion'];
            $crearubicacion = $_POST['crearubicacion'];
            $editarubicacion = $_POST['editarubicacion'];
            $eliminarubicacion = $_POST['eliminarubicacion'];
            $listatransporte = $_POST['listatransporte'];
            $creartransporte = $_POST['creartransporte'];
            $editartransporte = $_POST['editartransporte'];
            $eliminartransporte = $_POST['eliminartransporte'];
            $listaremolque = $_POST['listaremolque'];
            $crearremolque = $_POST['crearremolque'];
            $editarremolque = $_POST['editarremolque'];
            $eliminarremolque = $_POST['eliminarremolque'];
            $listaoperador = $_POST['listaoperador'];
            $crearoperador = $_POST['crearoperador'];
            $editaroperador = $_POST['editaroperador'];
            $eliminaroperador = $_POST['eliminaroperador'];
            $listacarta = $_POST['listacarta'];
            $crearcarta = $_POST['crearcarta'];
            $editarcarta = $_POST['editarcarta'];
            $eliminarcarta = $_POST['eliminarcarta'];
            $cotizacion = $_POST['cotizacion'];
            $crearcotizacion = $_POST['crearcotizacion'];
            $editarcot = $_POST['editarcot'];
            $eliminarcot = $_POST['eliminarcot'];
            $listacotizacion = $_POST['listacotizacion'];
            $anticipo = $_POST['anticipo'];
            $cliente = $_POST['cliente'];
            $crearcliente = $_POST['crearcliente'];
            $editarcliente = $_POST['editarcliente'];
            $eliminarcliente = $_POST['eliminarcliente'];
            $listacliente = $_POST['listacliente'];
            $comunicado = $_POST['comunicado'];
            $crearcomunicado = $_POST['crearcomunicado'];
            $editarcomunicado = $_POST['editarcomunicado'];
            $eliminarcomunicado = $_POST['eliminarcomunicado'];
            $listacomunicado = $_POST['listacomunicado'];
            $producto = $_POST['producto'];
            $crearproducto = $_POST['crearproducto'];
            $editarproducto = $_POST['editarproducto'];
            $eliminarproducto = $_POST['eliminarproducto'];
            $listaproducto = $_POST['listaproducto'];
            $proveedor = $_POST['proveedor'];
            $crearproveedor = $_POST['crearproveedor'];
            $editarproveedor = $_POST['editarproveedor'];
            $eliminarproveedor = $_POST['eliminarproveedor'];
            $listaproveedor = $_POST['listaproveedor'];
            $impuesto = $_POST['impuesto'];
            $crearimpuesto = $_POST['crearimpuesto'];
            $editarimpuesto = $_POST['editarimpuesto'];
            $eliminarimpuesto = $_POST['eliminarimpuesto'];
            $listaimpuesto = $_POST['listaimpuesto'];
            $datosfacturacion = $_POST['datosfacturacion'];
            $creardatos = $_POST['creardatos'];
            $editardatos = $_POST['editardatos'];
            $listadatos = $_POST['listadatos'];
            $contrato = $_POST['contrato'];
            $crearcontrato = $_POST['crearcontrato'];
            $editarcontrato = $_POST['editarcontrato'];
            $eliminarcontrato = $_POST['eliminarcontrato'];
            $listacontrato = $_POST['listacontrato'];
            $usuarios = $_POST['usuarios'];
            $crearusuario = $_POST['crearusuario'];
            $listausuario = $_POST['listausuario'];
            $eliminarusuario = $_POST['eliminarusuario'];
            $asigpermiso = $_POST['asigpermiso'];
            $reporte = $_POST['reporte'];
            $reportefactura = $_POST['reportefactura'];
            $reportepago = $_POST['reportepago'];
            $reportegrafica = $_POST['reportegrafica'];
            $reporteiva = $_POST['reporteiva'];
            $datosiva = $_POST['datosiva'];
            $reporteventas = $_POST['reporteventas'];
            $configuracion = $_POST['configuracion'];
            $addfolio = $_POST['addfolio'];
            $listafolio = $_POST['listafolio'];
            $editfolio = $_POST['editfolio'];
            $eliminarfolio = $_POST['eliminarfolio'];
            $addcomision = $_POST['addcomision'];
            $encabezados = $_POST['encabezados'];
            $confcorreo = $_POST['confcorreo'];
            $importar = $_POST['importar'];

            $p->setIdUsuario($idusuario);
            $p->setAccion($accion);
            $p->setFacturas($facturas);
            $p->setCrearfactura($crearfactura);
            $p->setEditarfactura($editarfactura);
            $p->setEliminarfactura($eliminarfactura);
            $p->setListafactura($listafactura);
            $p->setPago($pago);
            $p->setCrearpago($crearpago);
            $p->setEditarpago($editarpago);
            $p->setEliminarpago($eliminarpago);
            $p->setListapago($listapago);
            $p->setNomina($nomina);
            $p->setListaempleado($listaempleado);
            $p->setCrearempleado($crearempleado);
            $p->setEditarempleado($editarempleado);
            $p->setEliminarempleado($eliminarempleado);
            $p->setListanomina($listanomina);
            $p->setCrearnomina($crearnomina);
            $p->setEditarnomina($editarnomina);
            $p->setEliminarnomina($eliminarnomina);
            $p->setCartaporte($cartaporte);
            $p->setListaubicacion($listaubicacion);
            $p->setCrearubicacion($crearubicacion);
            $p->setEditarubicacion($editarubicacion);
            $p->setEliminarubicacion($eliminarubicacion);
            $p->setListatransporte($listatransporte);
            $p->setCreartransporte($creartransporte);
            $p->setEditartransporte($editartransporte);
            $p->setEliminartransporte($eliminartransporte);
            $p->setListaremolque($listaremolque);
            $p->setCrearremolque($crearremolque);
            $p->setEditarremolque($editarremolque);
            $p->setEliminarremolque($eliminarremolque);
            $p->setListaoperador($listaoperador);
            $p->setCrearoperador($crearoperador);
            $p->setEditaroperador($editaroperador);
            $p->setEliminaroperador($eliminaroperador);
            $p->setListacarta($listacarta);
            $p->setCrearcarta($crearcarta);
            $p->setEditarcarta($editarcarta);
            $p->setEliminarcarta($eliminarcarta);
            $p->setCotizacion($cotizacion);
            $p->setCrearcotizacion($crearcotizacion);
            $p->setEditarcot($editarcot);
            $p->setEliminarcot($eliminarcot);
            $p->setListacotizacion($listacotizacion);
            $p->setAnticipo($anticipo);
            $p->setCliente($cliente);
            $p->setCrearcliente($crearcliente);
            $p->setEditarcliente($editarcliente);
            $p->setEliminarcliente($eliminarcliente);
            $p->setListacliente($listacliente);
            $p->setComunicado($comunicado);
            $p->setCrearcomunicado($crearcomunicado);
            $p->setEditarcomunicado($editarcomunicado);
            $p->setEliminarcomunicado($eliminarcomunicado);
            $p->setListacomunicado($listacomunicado);
            $p->setProducto($producto);
            $p->setCrearproducto($crearproducto);
            $p->setEditarproducto($editarproducto);
            $p->setEliminarproducto($eliminarproducto);
            $p->setListaproducto($listaproducto);
            $p->setProveedor($proveedor);
            $p->setCrearproveedor($crearproveedor);
            $p->setEditarproveedor($editarproveedor);
            $p->setEliminarproveedor($eliminarproveedor);
            $p->setListaproveedor($listaproveedor);
            $p->setImpuesto($impuesto);
            $p->setCrearimpuesto($crearimpuesto);
            $p->setEditarimpuesto($editarimpuesto);
            $p->setEliminarimpuesto($eliminarimpuesto);
            $p->setListaimpuesto($listaimpuesto);
            $p->setDatosfacturacion($datosfacturacion);
            $p->setCreardatos($creardatos);
            $p->setEditardatos($editardatos);
            $p->setListadatos($listadatos);
            $p->setContrato($contrato);
            $p->setCrearcontrato($crearcontrato);
            $p->setEditarcontrato($editarcontrato);
            $p->setEliminarcontrato($eliminarcontrato);
            $p->setListacontrato($listacontrato);
            $p->setUsuarios($usuarios);
            $p->setCrearusuario($crearusuario);
            $p->setListausuario($listausuario);
            $p->setEliminarusuario($eliminarusuario);
            $p->setAsignarpermisos($asigpermiso);
            $p->setReporte($reporte);
            $p->setReportefactura($reportefactura);
            $p->setReportepago($reportepago);
            $p->setReportegrafica($reportegrafica);
            $p->setReporteiva($reporteiva);
            $p->setDatosiva($datosiva);
            $p->setReporteventas($reporteventas);
            $p->setConfiguracion($configuracion);
            $p->setAddfolio($addfolio);
            $p->setListafolio($listafolio);
            $p->setEditfolio($editfolio);
            $p->setEliminarfolio($eliminarfolio);
            $p->setAddcomision($addcomision);
            $p->setEncabezados($encabezados);
            $p->setConfcorreo($confcorreo);
            $p->setImportar($importar);
            
            $actualizado = $cu->checkAccion($p);
            if ($actualizado) {
                echo $actualizado;
            } else {
                echo "0Error: no guardo el registro ";
            }
            break;
        case 'eliminarusuario':
            $cu = new ControladorUsuario();
            $idusuario = $_POST['idusuario'];
            $eliminado = $cu->quitarUsuario($idusuario);
            if ($eliminado) {
                echo "Registro eliminado";
            } else {
                echo "0No se han econtrado datos";
            }
            break;
        case 'filtrarusuario':
            $cs = new ControladorUsuario();
            $US = $_POST['US'];
            $numreg = $_POST['numreg'];
            $pag = $_POST['pag'];
            $datos = $cs->listaServiciosHistorial($US, $numreg, $pag);
            if ($datos != "") {
                echo $datos;
            } else {
                echo "0Ah ocurrido un error";
            }
            break;
        case 'crearimg':
            $sn = substr('David', 0,1);
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
            $hoy = $y . '-' . $m . '-' . $d . 'T' . $h . '.' . $mi . '.' . $s;
            header("Content-Type: image/png");
            $im = @imagecreate(20, 20)or die("Cannot Initialize new GD image stream");
            $color_fondo = imagecolorallocate($im, 9, 9, 107);
            $color_texto = imagecolorallocate($im, 255, 255, 255);
            imagestring($im, 5, 6, 2.8, "$sn", $color_texto);
            $imgname = "$sn$hoy.png/png";
            imagepng($im, '../temporal/usuarios/' . $sn . $hoy . '.png');
            imagedestroy($im);
            return $imgname;
            break;
        default:
            break;
    }
}
