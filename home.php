<?php
require_once 'Enrutador.php';
?>
<html>
    <head>
        <?php
        include 'com.sine.common/commonhead.php';
        include './com.sine.controlador/ControladorPermiso.php';
        $cp = new ControladorPermiso();
        $permisos = $cp->getPermisos();

        $div = explode("</tr>", $permisos);
        $uid = $div[0];
        $nombreusuario = $div[1];
        $facturas = $div[2];
        $pago = $div[3];
        $nomina = $div[4];
        $listaempleado = $div[5];
        $listanomina = $div[6];
        $cartaporte = $div[7];
        $listaubicacion = $div[8];
        $listatransporte = $div[9];
        $listaremolque = $div[10];
        $listaoperador = $div[11];
        $listacarta = $div[12];
        $cotizacion = $div[13];
        $cliente = $div[14];
        $comunicado = $div[16];
        $producto = $div[17];
        $proveedor = $div[18];
        $impuesto = $div[19];
        $datosfacturacion = $div[20];
        $contrato = $div[21];
        $listausuario = $div[22];
        $reporte = $div[23];
        $reportefactura = $div[24];
        $reportepago = $div[25];
        $reportegrafica = $div[26];
        $reporteiva = $div[27];
        $datosiva = $div[28];
        $reporteventa = $div[29];
        $configuracion = $div[30];
        $acceso = $div[31];
        $imgperfil = $div[32];
        $modulos = $div[33];

        $notificaciones = $cp->getNotificacion();
        $divN = explode("<corte>", $notificaciones);
        $listN = $divN[0];
        $countN = $divN[1];
        $Mactive = "";
        if ($countN > 0) {
            $Mactive = "notification-marker-active";
        }
        $mod = explode("-", $modulos);
        ?>
    </head>
    
    <body style="position: fixed; width: 100%;">
        <div style="overflow-y: scroll; height: 100%;" id="">
            <header class="header">
                <div class="smh-square"></div>
                <div class="mdh-square"></div>
                <span id="menu-icon" class="lnr lnr-menu show-menu"></span>

                <div id="head-info">
                    <div class="logo-color"></div>
                    <div class="user-info">
    <div class="dropdown">
        <button class="button-home btn dropdown-toggle" title="Opciones" type="button" data-bs-toggle="dropdown"><span id="user-name"><?php echo utf8_decode($nombreusuario); ?></span> <span class="caret"></span> </button>
         <ul class="dropdown-menu dropdown-menu-right  user-option">
             <?php
        if ($configuracion == 1) {
        ?>
            <li><a class="option-link list-conf" data-submenu="config">Configuración</a></li>
        <?php
        }
        if ($listausuario == 1) {
        ?>
            <li><a class="option-link list-conf" data-submenu="listasuarioaltas">Usuarios</a></li>
        <?php
        }
        ?>
        <li><a class="logout-link"  onclick="logout();">Cerrar Sesión</a></li>
    </ul>
</div>

                    </div>
                    <div id="notification-alert" class="notification-marker <?php echo $Mactive; ?>"></div>
                    <div class="img-user">
                        <div class='dropdown text-center' style="height: 70px;">
                            <button class='button-home dropdown-toggle' title='Notificaciones'  type='button' data-toggle='dropdown'><img src="img/usuarios/<?php echo $imgperfil; ?>" /></button>
                            <ul class='dropdown-menu dropdown-menu-right user-option' id="list-notificaciones">
                                <li><a class="notification-link" onclick="loadImgPerfil(<?php echo $uid; ?>)" data-toggle='modal' data-target='#modal-profile-img' title="Cambiar imagen de perfil"><span class="glyphicon glyphicon-user"></span> Cambiar imagen de perfil </a></li>
                                <?php
                                echo $listN;
                                ?>
                                <li><a class="notification-link list-conf" data-submenu="notificacion">Ver todas las notificaciones </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </header>
            <main>
                <?php
                require_once 'hmodals.php';
                ?>
                <div id="main-menu" class="content-menu">
                    <div class="elipse">

                    </div>
                    <div id="accordion" class="scroll">
                        <div class="item-direction">
                            <li class="list-element list-menu menu-active" data-submenu='paginicio'><div class="marker marker-active"></div> <div class="pad"></div><label> Inicio</label></li>
                            <?php
                            foreach ($mod as $modactual) {
                                switch ($modactual) {
                                    case '1':
                                        if ($facturas == '1') {
                                            ?>
                                            <li id="factura-menu" class='list-element list-menu' data-submenu='listafactura'><div class='marker'></div> <div class='pad'></div><label> Facturas</label></li>
                                            <?php
                                        }
                                        break;
                                    case '2':
                                        if ($pago == '1') {
                                            ?>
                                            <li id="pago-menu" class='list-element list-menu' data-submenu='listapago'><div class='marker'></div> <div class='pad'></div><label> Pagos</label></li>
                                            <?php
                                        }
                                        break;
                                    case '3':
                                        if ($nomina == '1') {
                                            ?>
                                            <a href='#colnomina' data-toggle='collapse'><li class='list-element'><div class='marker'></div> <div class='pad'></div><label> N&oacute;minas</label></li></a>
                                            <div id='colnomina' class='panel-collapse collapse'>
                                                <ul>
                                                    <?php
                                                    if ($listaempleado == '1') {
                                                        ?>
                                                        <li class='lista-submenu-elemento list-menu' data-submenu='listaempleado'> Empleados</li>
                                                        <?php
                                                    }
                                                    if ($listanomina == '1') {
                                                        ?>
                                                        <li class='lista-submenu-elemento list-menu' data-submenu='listanomina'> N&oacute;minas</li>
                                                        <?php
                                                    }
                                                    ?>
                                                </ul>
                                            </div>
                                            <?php
                                        }
                                        break;
                                    case '4':
                                        if ($cartaporte == '1') {
                                            ?>
                                            <a href='#colcartaporte' data-toggle='collapse'><li class='list-element'><div class='marker'></div> <div class='pad'></div><label> Carta Porte</label></li></a>
                                            <div id='colcartaporte' class='panel-collapse collapse'>
                                                <ul>
                                                    <?php
                                                    if ($listaubicacion == '1') {
                                                        ?>
                                                        <li class='lista-submenu-elemento list-menu' data-submenu='listadireccion'> Ubicaciones</li>
                                                        <?php
                                                    }
                                                    if ($listatransporte == '1') {
                                                        ?>
                                                        <li class='lista-submenu-elemento list-menu' data-submenu='listatransporte'> Transportes</li>
                                                        <?php
                                                    }
                                                    if ($listaremolque == '1') {
                                                        ?>
                                                        <li class='lista-submenu-elemento list-menu' data-submenu='listaremolque'> Remolques</li>
                                                        <?php
                                                    }
                                                    if ($listaoperador == '1') {
                                                        ?>
                                                        <li class='lista-submenu-elemento list-menu' data-submenu='listaoperador'> Operadores</li>
                                                        <?php
                                                    }
                                                    if ($listacarta == '1') {
                                                        ?>
                                                        <li class='lista-submenu-elemento list-menu' data-submenu='listacarta'> Carta Porte</li>
                                                        <?php
                                                    }
                                                    ?>
                                                </ul>
                                            </div>
                                            <?php
                                        }
                                        break;
                                    case '5':
                                        if ($cotizacion == '1') {
                                            ?>
                                            <li class='list-element list-menu' data-submenu='listacotizacion'><div class='marker'></div> <div class='pad'></div><label> Cotizaciones</label></li>
                                            <?php
                                        }
                                        break;
                                    case '6':
                                        if ($cliente == '1') {
                                            ?>
                                            <li class='list-element list-menu' data-submenu='listaclientealtas'><div class='marker'></div> <div class='pad'></div><label> Clientes</label></li>
                                            <?php
                                        }
                                        break;
                                    case '7':
                                        if ($comunicado == '1') {
                                            ?>
                                            <li class='list-element list-menu' data-submenu='listacomunicado'><div class='marker'></div> <div class='pad'></div><label> Comunicados</label></li>
                                            <?php
                                        }
                                        break;
                                    case '8':
                                        if ($producto == '1') {
                                            ?>
                                            <li class='list-element list-menu' data-submenu='listaproductoaltas'><div class='marker'></div> <div class='pad'></div><label> Productos</label></li>
                                            <?php
                                        }
                                        break;
                                    case '9':
                                        if ($proveedor == '1') {
                                            ?>
                                            <li class='list-element list-menu' data-submenu='listaproveedor'><div class='marker'></div> <div class='pad'></div><label> Proveedor</label></li>
                                            <?php
                                        }
                                        break;
                                    case '10':
                                        if ($impuesto == '1') {
                                            ?>
                                            <li class='list-element list-menu' data-submenu='listaimpuesto'><div class='marker'></div> <div class='pad'></div><label> Impuestos</label></li>
                                            <?php
                                        }
                                        break;
                                    case '11':
                                        if ($datosfacturacion == '1') {
                                            ?>
                                            <li class='list-element list-menu' data-submenu='listaempresa'><div class='marker'></div> <div class='pad'></div><label> Datos Facturacion</label></li>
                                            <?php
                                        }
                                        break;
                                    case '12':
                                        if ($contrato == '1') {
                                            ?>
                                            <li class="list-element list-menu" data-submenu='listacontratos'><div class="marker"></div> <div class="pad"></div><label> Factura Automatica</label></li>
                                            <?php
                                        }
                                        break;
                                    case '13':
                                        if ($reporte == '1') {
                                            ?>
                                            <a href="#colreporte" data-toggle='collapse'><li class=" list-element"><div class="marker"></div> <div class="pad"></div><label> Reportes</label></li></a>
                                            <div id="colreporte" class="panel-collapse collapse">
                                                <ul>
                                                    <?php
                                                    if ($reportefactura == '1') {
                                                        ?>
                                                        <li class="lista-submenu-elemento list-menu" data-submenu='reportefactura'> Facturas</li>
                                                        <?php
                                                    }
                                                    if ($reportepago == '1') {
                                                        ?>
                                                        <li class="lista-submenu-elemento list-menu" data-submenu='reportepago'> Pagos</li>
                                                        <?php
                                                    }

                                                    if ($reportegrafica == '1') {
                                                        ?>
                                                        <li class="lista-submenu-elemento list-menu" data-submenu='reportegrafica'> Grafica</li>
                                                        <?php
                                                    }
                                                    if ($reporteiva == '1') {
                                                        ?>
                                                        <li class="lista-submenu-elemento list-menu" data-submenu='reportesat'> Impuestos</li>
                                                        <?php
                                                    }
                                                    if ($datosiva == '1') {
                                                        ?>
                                                        <li class="lista-submenu-elemento list-menu" data-submenu='datosiva'> Datos Impuestos</li>
                                                        <?php
                                                    }
                                                    if ($reporteventa == '1') {
                                                        ?>
                                                        <li class="lista-submenu-elemento list-menu" data-submenu='reporteventas'> Ventas</li>
                                                        <?php
                                                    }
                                                    ?>
                                                </ul>
                                            </div>
                                            <?php
                                        }
                                        break;
                                    default:
                                        break;
                                }
                            }
                            ?>
                            <?php
                            ?>
                            <!--<a href="#coldmasiva" data-toggle='collapse'><li class="list-element"><div class="marker"></div> <div class="pad"></div><label> Descarga Masiva</label></li></a>
                            <div id="coldmasiva" class="panel-collapse collapse">
                                <ul>
                                    <li class="lista-submenu-elemento list-menu" data-submenu='listafiel'> Archivos FIEL</li>
                                    <li class="lista-submenu-elemento list-menu" data-submenu='listadescsolicitud'> Solicitudes</li>
                                </ul>
                            </div>-->
                            <?php
                            ?>
                            <li data-bs-toggle='modal' data-bs-target='#modal-contacto' class='list-element list-menu' onclick='getNombreUsuario();'><div class='marker'></div> <div class='pad'></div><label> Soporte Tecnico</label></li>
                            <br/>
                        </div>
                    </div>
                </div>

                <article id="contenedor-vista-right" class="wrapper left-pad">
                    <?php
                    $e = new Enrutador();
                    if (isset($_GET['view'])) {
                        $vista = $_GET['view'];
                        $e->cargarVista($vista);
                    } else {
                        $e->cargarVista("venta");
                    }
                    ?>

                </article>
            </main>
            <div id="help-js"></div>
        </div>
    </body>
    <script>
        resetMenu();
        window.addEventListener('resize', resetMenu);

    </script>
</html>
