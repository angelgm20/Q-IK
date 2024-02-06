function checkAll() {
    if ($("#checkall").prop('checked')) {
        $(".collapse-permission").removeClass('in');
        $("input:checkbox").removeAttr('checked');
    } else {
        $(".collapse-permission").addClass('in');
        $("input:checkbox").prop('checked', true);
    }
}

function checkUsuario() {
    $.ajax({
        url: "com.sine.enlace/enlaceusuario.php",
        type: "POST",
        data: {transaccion: "gettipousuario"},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
            } else {
                if (datos == '2') {
                    $("#tipo-usuario").val('2');
                    $("#tipo-usuario").attr('disabled', true);
                }
            }
        }
    });
}

function cargarImgUsuario() {
    var formData = new FormData(document.getElementById("form-usuario"));
    var img = $("#imagen").val();
    if (isnEmpty(img, 'imagen')) {
        $.ajax({
            url: 'com.sine.enlace/cargarimguser.php',
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function (datos) {
                var array = datos.split("<corte>");
                var view = array[0];
                var fn = array[1];
                $("#muestraimagen").html(view);
                $("#filename").val(fn);
                $("#imagen").val('');
            }
        });
    }
}

function crearIMG() {
    $.ajax({
        url: "com.sine.enlace/enlaceusuario.php",
        type: "POST",
        data: {transaccion: "crearimg"},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
            } else {
                alert(datos);
            }
        }
    });
}

function insertarUsuario() {
    var img = $("#filename").val();
    var nombre = $("#nombre").val();
    var apellidopaterno = $("#apellido-paterno").val();
    var apellidomaterno = $("#apellido-materno").val();
    var telefono = $("#telefono").val();
    var celular = $("#celular").val();
    var correo = $("#correo").val();
    var usuario = $("#usuario").val();
    var contrasena = $("#contrasena").val();
    var estatus = $("#estatus").val();
    var tipo = $("#tipo-usuario").val();

    if (isnEmpty(nombre, "nombre") && isnEmpty(apellidopaterno, "apellido-paterno") && isnEmpty(apellidomaterno, "apellido-materno") && isnEmpty(usuario, "usuario") && isnEmpty(contrasena, "contrasena") && isEmail(correo, "correo") && isPhoneNumber(telefono, "telefono") && isnEmpty(tipo, "tipo-usuario")) {
        $.ajax({
            url: "com.sine.enlace/enlaceusuario.php",
            type: "POST",
            data: {transaccion: "insertrausuario", nombre: nombre, apellidopaterno: apellidopaterno, apellidomaterno: apellidomaterno, telefono: telefono, celular: celular, usuario: usuario, password: contrasena, correo: correo, estatus: estatus, tipo: tipo, img: img},
            success: function (datos) {
                var texto = datos.toString();
                var bandera = texto.substring(0, 1);
                var res = texto.substring(1, 1000);
                if (bandera == '0') {
                    alertify.error(res);
                } else {
                    alertify.success('Datos de usuario registrado');
                    loadView('listasuarioaltas');
                }
            }
        });
    }
}

function loadListaUsuariosaltas() {
    cargandoShow();
    $.ajax({
        url: "com.sine.enlace/enlaceusuario.php",
        type: "POST",
        data: {transaccion: "listausuariosaltas"},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
            } else {
                $("#body-lista-usuarios-altas").html(datos);
            }
            cargandoHide();
        }
    });
}

function asignarPermisos(idusuario) {
    cargandoHide();
    cargandoShow();
    $.ajax({
        url: "com.sine.enlace/enlaceusuario.php",
        type: "POST",
        data: {transaccion: "asignarpermiso", idusuario: idusuario},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                cargandoHide();
                alertify.error(res);
            } else {
                cargandoHide();
                loadView('asignarpermisos');
                window.setTimeout("setValoresAsignarPermisos('" + datos + "')", 400);
            }
        }
    });
}

function setValoresAsignarPermisos(datos) {
    var array = datos.split("</tr>");
    var idusuario = array[0];
    var nombre = array[1];
    var facturas = array[2];
    var crearfactura = array[3];
    var editarfactura = array[4];
    var eliminarfactura = array[5];
    var listafactura = array[6];
    var pago = array[7];
    var crearpago = array[8];
    var editarpago = array[9];
    var eliminarpago = array[10];
    var listapago = array[11];
    var nomina = array[12];
    var listaempleado = array[13];
    var crearempleado = array[14];
    var editarempleado = array[15];
    var eliminarempleado = array[16];
    var listanomina = array[17];
    var crearnomina = array[18];
    var editarnomina = array[19];
    var eliminarnomina = array[20];
    var cartaporte = array[21];
    var listaubicacion = array[22];
    var crearubicacion = array[23];
    var editarubicacion = array[24];
    var eliminarubicacion = array[25];
    var listatransporte = array[26];
    var creartransporte = array[27];
    var editartransporte = array[28];
    var eliminartransporte = array[29];
    var listaremolque = array[30];
    var crearremolque = array[31];
    var editarremolque = array[32];
    var eliminarremolque = array[33];
    var listaoperador = array[34];
    var crearoperador = array[35];
    var editaroperador = array[36];
    var eliminaroperador = array[37];
    var listacarta = array[38];
    var crearcarta = array[39];
    var editarcarta = array[40];
    var eliminarcarta = array[41];
    var cotizacion = array[42];
    var crearcotizacion = array[43];
    var editarcotizacion = array[44];
    var eliminarcot = array[45];
    var listacotizacion = array[46];
    var anticipo = array[47];
    var cliente = array[48];
    var crearcliente = array[49];
    var editarcliente = array[50];
    var eliminarcliente = array[51];
    var listacliente = array[52];
    var comunicado = array[53];
    var crearcomunicado = array[54];
    var editarcomunicado = array[55];
    var eliminarcomunicaco = array[56];
    var listacomunicado = array[57];
    var producto = array[58];
    var crearproducto = array[59];
    var editarproducto = array[60];
    var eliminarproducto = array[61];
    var listaproducto = array[62];
    var proveedor = array[63];
    var crearproveedor = array[64];
    var editarproveedor = array[65];
    var eliminarproveedor = array[66];
    var listaproveedor = array[67];
    var impuesto = array[68];
    var crearimpuesto = array[69];
    var editarimpuesto = array[70];
    var eliminarimpuesto = array[71];
    var listaimpuesto = array[72];
    var datosfacturacion = array[73];
    var creardatos = array[74];
    var editardatos = array[75];
    var listadatos = array[76];
    var contrato = array[77];
    var crearcontrato = array[78];
    var editarcontrato = array[79];
    var eliminarcontrato = array[80];
    var listacontrato = array[81];
    var usuarios = array[82];
    var crearusuario = array[83];
    var listausuario = array[84];
    var eliminarusuario = array[85];
    var asigpermiso = array[86];
    var reporte = array[87];
    var reportefactura = array[88];
    var reportepago = array[89];
    var reportegrafica = array[90];
    var reporteiva = array[91];
    var datosiva = array[92];
    var reporteventa = array[93];
    var configuracion = array[94];
    var addfolio = array[95];
    var listafolio = array[96];
    var editfolio = array[97];
    var eliminarfolio = array[98];
    var addcomision = array[99];
    var encabezados = array[100];
    var confcorreo = array[101];
    var importar = array[102];
    var accion = array[103];
    var idlogin = array[104];

    changeText("#titulo-asignar", "Asignando permisos a: " + nombre);
    
    if (facturas == '1') {
        $("#collapse1").addClass('in');
    }
    if (crearfactura == '1') {
        $("#checkall").prop('checked', true);
        $("#nuevafactura").attr('checked', true);
    }
    if (editarfactura == '1') {
        $("#editarfactura").attr('checked', true);
    }
    if (eliminarfactura == '1') {
        $("#eliminarfactura").attr('checked', true);
    }
    if (listafactura == '1') {
        $("#listafactura").attr('checked', true);
    }

    if (pago == '1') {
        $("#collapse2").addClass('in');
    }
    if (crearpago == '1') {
        $("#crearpago").attr('checked', true);
    }
    if (editarpago == '1') {
        $("#editarpago").attr('checked', true);
    }
    if (eliminarpago == '1') {
        $("#eliminarpago").attr('checked', true);
    }
    if (listapago == '1') {
        $("#listapago").attr('checked', true);
    }
    
    if (nomina == '1') {
        $("#collapse14").addClass('in');
    }
    if (listaempleado == '1') {
        $("#listaempleado").attr('checked', true);
    }
    if (crearempleado == '1') {
        $("#nuevoempleado").attr('checked', true);
    }
    if (editarempleado == '1') {
        $("#editarempleado").attr('checked', true);
    }
    if (eliminarempleado == '1') {
        $("#eliminarempleado").attr('checked', true);
    }
    
    if (listanomina == '1') {
        $("#listanomina").attr('checked', true);
    }
    if (crearnomina == '1') {
        $("#nuevanomina").attr('checked', true);
    }
    if (editarnomina == '1') {
        $("#editarnomina").attr('checked', true);
    }
    if (eliminarnomina == '1') {
        $("#eliminarnomina").attr('checked', true);
    }
    
    if (cartaporte == '1') {
        $("#collapse15").addClass('in');
    }
    if (listaubicacion == '1') {
        $("#listaubicacion").attr('checked', true);
    }
    if (crearubicacion == '1') {
        $("#nuevaubicacion").attr('checked', true);
    }
    if (editarubicacion == '1') {
        $("#editarubicacion").attr('checked', true);
    }
    if (eliminarubicacion == '1') {
        $("#eliminarubicacion").attr('checked', true);
    }
    
    if (listatransporte == '1') {
        $("#listatransporte").attr('checked', true);
    }
    if (creartransporte == '1') {
        $("#nuevotransporte").attr('checked', true);
    }
    if (editartransporte == '1') {
        $("#editartransporte").attr('checked', true);
    }
    if (eliminartransporte == '1') {
        $("#eliminartransporte").attr('checked', true);
    }
    
    if (listaremolque == '1') {
        $("#listaremolque").attr('checked', true);
    }
    if (crearremolque == '1') {
        $("#nuevoremolque").attr('checked', true);
    }
    if (editarremolque == '1') {
        $("#editarremolque").attr('checked', true);
    }
    if (eliminarremolque == '1') {
        $("#eliminarremolque").attr('checked', true);
    }
    
    if (listaoperador == '1') {
        $("#listaoperador").attr('checked', true);
    }
    if (crearoperador == '1') {
        $("#nuevooperador").attr('checked', true);
    }
    if (editaroperador == '1') {
        $("#editaroperador").attr('checked', true);
    }
    if (eliminaroperador == '1') {
        $("#eliminaroperador").attr('checked', true);
    }
    
    if (listacarta == '1') {
        $("#listacarta").attr('checked', true);
    }
    if (crearcarta == '1') {
        $("#nuevacarta").attr('checked', true);
    }
    if (editarcarta == '1') {
        $("#editarcarta").attr('checked', true);
    }
    if (eliminarcarta == '1') {
        $("#eliminarcarta").attr('checked', true);
    }

    if (cotizacion == '1') {
        $("#collapse3").addClass('in');
    }
    if (crearcotizacion == '1') {
        $("#crearcot").attr('checked', true);
    }
    if (editarcotizacion == '1') {
        $("#editarcot").attr('checked', true);
    }
    if (eliminarcot == '1') {
        $("#eliminarcot").attr('checked', true);
    }
    if (listacotizacion == '1') {
        $("#listacot").attr('checked', true);
    }
    if (anticipo == '1') {
        $("#anticipo").attr('checked', true);
    }

    if (cliente == '1') {
        $("#collapse4").addClass('in');
    }
    if (crearcliente == '1') {
        $("#crearcliente").attr('checked', true);
    }
    if (editarcliente == '1') {
        $("#editarcliente").attr('checked', true);
    }
    if (eliminarcliente == '1') {
        $("#eliminarcliente").attr('checked', true);
    }
    if (listacliente == '1') {
        $("#listacliente").attr('checked', true);
    }

    if (comunicado == '1') {
        $("#collapse5").addClass('in');
    }
    if (crearcomunicado == '1') {
        $("#crearcomunicado").attr('checked', true);
    }
    if (editarcomunicado == '1') {
        $("#editarcomunicado").attr('checked', true);
    }
    if (eliminarcomunicaco == '1') {
        $("#eliminarcomunicado").attr('checked', true);
    }
    if (listacomunicado == '1') {
        $("#listacomunicado").attr('checked', true);
    }

    if (producto == '1') {
        $("#collapse6").addClass('in');
    }
    if (crearproducto == '1') {
        $("#crearproducto").attr('checked', true);
    }
    if (editarproducto == '1') {
        $("#editarproducto").attr('checked', true);
    }
    if (eliminarproducto == '1') {
        $("#eliminarproducto").attr('checked', true);
    }
    if (listaproducto == '1') {
        $("#listaproducto").attr('checked', true);
    }

    if (proveedor == '1') {
        $("#collapse7").addClass('in');
    }
    if (crearproveedor == '1') {
        $("#crearproveedor").attr('checked', true);
    }
    if (editarproveedor == '1') {
        $("#editarproveedor").attr('checked', true);
    }
    if (eliminarproveedor == '1') {
        $("#eliminarproveedor").attr('checked', true);
    }
    if (listaproveedor == '1') {
        $("#listaproveedor").attr('checked', true);
    }

    if (impuesto == '1') {
        $("#collapse13").addClass('in');
    }
    if (crearimpuesto == '1') {
        $("#crearimpuesto").attr('checked', true);
    }
    if (editarimpuesto == '1') {
        $("#editarimpuesto").attr('checked', true);
    }
    if (eliminarimpuesto == '1') {
        $("#eliminarimpuesto").attr('checked', true);
    }
    if (listaimpuesto == '1') {
        $("#listaimpuesto").attr('checked', true);
    }

    if (datosfacturacion == '1') {
        $("#collapse8").addClass('in');
    }
    if (creardatos == '1') {
        $("#creardatos").attr('checked', true);
    }
    if (editardatos == '1') {
        $("#editardatos").attr('checked', true);
    }
    if (listadatos == '1') {
        $("#listadatos").attr('checked', true);
    }

    if (contrato == '1') {
        $("#collapse9").addClass('in');
    }
    if (crearcontrato == '1') {
        $("#crearcontrato").attr('checked', true);
    }
    if (editarcontrato == '1') {
        $("#editarcontrato").attr('checked', true);
    }
    if (eliminarcontrato == '1') {
        $("#eliminarcontrato").attr('checked', true);
    }
    if (listacontrato == '1') {
        $("#listacontrato").attr('checked', true);
    }

    if (usuarios == '1') {
        $("#collapse10").addClass('in');
    }
    if (crearusuario == '1') {
        $("#crearusuario").attr('checked', true);
    }
    if (listausuario == '1') {
        $("#listausuario").attr('checked', true);
    }
    if (eliminarusuario == '1'){
        $("#eliminarusuario").attr('checked',true);
    }
    if (asigpermiso == '1') {
        $("#asgpermiso").attr('checked', true);
    }

    if (reporte == '1') {
        $("#collapse11").addClass('in');
    }
    if (reportefactura == '1') {
        $("#repfactura").attr('checked', true);
    }
    if (reportepago == '1') {
        $("#reppago").attr('checked', true);
    }
    if (reportegrafica == '1') {
        $("#repgrafica").attr('checked', true);
    }
    if (reporteiva == '1') {
        $("#repiva").attr('checked', true);
    }
    if (datosiva == '1') {
        $("#listaiva").attr('checked', true);
    }
    if (reporteventa == '1') {
        $("#repventas").attr('checked', true);
    }

    if (configuracion == '1') {
        $("#collapse12").addClass('in');
    }
    if (addfolio == '1') {
        $("#addfolio").attr('checked', true);
    }
    if (listafolio == '1') {
        $("#listfolio").attr('checked', true);
    }
    if (editfolio == '1') {
        $("#editfolio").attr('checked', true);
    }
    if (eliminarfolio == '1') {
        $("#delfolio").attr('checked', true);
    }
    if (addcomision == '1') {
        $("#addcomision").attr('checked', true);
    }
    if (encabezados == '1') {
        $("#confencabezado").attr('checked', true);
    }
    if (confcorreo == '1') {
        $("#confcorreo").attr('checked', true);
    }
    if(importar == '1'){
        $("#imptablas").attr('checked', true);
    }

    $("#form-permisos").append("<input type='hidden' id='idlogin' value='"+idlogin+"'/>");
    $("#form-permisos").append("<input type='hidden' id='accion' value='"+accion+"'/>");
    $("#btn-guardar-permisos").attr("onclick", "actualizarPermisos(" + idusuario + ");");

}

function actualizarPermisos(idusuario) {
    var idlogin = $("#idlogin").val();
    var accion = $("#accion").val();
    var facturas = 0;
    var crearfactura = 0;
    var editarfactura = 0;
    var eliminarfactura = 0;
    var listafactura = 0;
    var pago = 0;
    var crearpago = 0;
    var editarpago = 0;
    var eliminarpago = 0;
    var listapago = 0;
    var nomina = 0;
    var listaempleado = 0;
    var crearempleado = 0;
    var editarempleado = 0;
    var eliminarempleado = 0;
    var listanomina = 0;
    var crearnomina = 0;
    var editarnomina = 0;
    var eliminarnomina = 0;
    var cartaporte = 0;
    var listaubicacion = 0;
    var crearubicacion = 0;
    var editarubicacion = 0;
    var eliminarubicacion = 0;
    var listatransporte = 0;
    var creartransporte = 0;
    var editartransporte = 0;
    var eliminartransporte = 0;
    var listaremolque = 0;
    var crearremolque = 0;
    var editarremolque = 0;
    var eliminarremolque = 0;
    var listaoperador = 0;
    var crearoperador = 0;
    var editaroperador = 0;
    var eliminaroperador = 0;
    var listacarta = 0;
    var crearcarta = 0;
    var editarcarta = 0;
    var eliminarcarta = 0;
    var cotizacion = 0;
    var crearcotizacion = 0;
    var editarcot = 0;
    var eliminarcot = 0;
    var listacotizacion = 0;
    var anticipo = 0;
    var cliente = 0;
    var crearcliente = 0;
    var editarcliente = 0;
    var eliminarcliente = 0;
    var listacliente = 0;
    var comunicado = 0;
    var crearcomunicado = 0;
    var editarcomunicado = 0;
    var eliminarcomunicado = 0;
    var listacomunicado = 0;
    var producto = 0;
    var crearproducto = 0;
    var editarproducto = 0;
    var eliminarproducto = 0;
    var listaproducto = 0;
    var proveedor = 0;
    var crearproveedor = 0;
    var editarproveedor = 0;
    var eliminarproveedor = 0;
    var listaproveedor = 0;
    var impuesto = 0;
    var crearimpuesto = 0;
    var editarimpuesto = 0;
    var eliminarimpuesto = 0;
    var listaimpuesto = 0;
    var datosfacturacion = 0;
    var creardatos = 0;
    var editardatos = 0;
    var listadatos = 0;
    var contrato = 0;
    var crearcontrato = 0;
    var editarcontrato = 0;
    var eliminarcontrato = 0;
    var listacontrato = 0;
    var usuarios = 0;
    var crearusuario = 0;
    var listausuario = 0;
    var eliminarusuario = 0;
    var asigpermiso = 0;
    var reporte = 0;
    var reportefactura = 0;
    var reportepago = 0;
    var reportegrafica = 0;
    var reporteiva = 0;
    var datosiva = 0;
    var reporteventas = 0;
    var configuracion = 0;
    var addfolio = 0;
    var listafolio = 0;
    var editfolio = 0;
    var eliminarfolio = 0;
    var addcomision = 0;
    var encabezados = 0;
    var confcorreo = 0;
    var importar = 0;

    if ($("#collapse1").hasClass('in')) {
        facturas = 1;
        if ($("#nuevafactura").prop('checked')) {
            crearfactura = 1;
        }
        if ($("#editarfactura").prop('checked')) {
            editarfactura = 1;
        }
        if ($("#eliminarfactura").prop('checked')) {
            eliminarfactura = 1;
        }
        if ($("#listafactura").prop('checked')) {
            listafactura = 1;
        }
    }

    if ($("#collapse2").hasClass('in')) {
        pago = 1;

        if ($("#crearpago").prop('checked')) {
            crearpago = 1;
        }
        if ($("#editarpago").prop('checked')) {
            editarpago = 1;
        }
        if ($("#eliminarpago").prop('checked')) {
            eliminarpago = 1;
        }
        if ($("#listapago").prop('checked')) {
            listapago = 1;
        }
    }
    
    if ($("#collapse14").hasClass('in')) {
        nomina = 1;

        if ($("#listaempleado").prop('checked')) {
            listaempleado = 1;
        }
        if ($("#nuevoempleado").prop('checked')) {
            crearempleado = 1;
        }
        if ($("#editarempleado").prop('checked')) {
            editarempleado = 1;
        }
        if ($("#eliminarempleado").prop('checked')) {
            eliminarempleado = 1;
        }
        
        if ($("#listanomina").prop('checked')) {
            listanomina = 1;
        }
        if ($("#nuevanomina").prop('checked')) {
            crearnomina = 1;
        }
        if ($("#editarnomina").prop('checked')) {
            editarnomina = 1;
        }
        if ($("#eliminarnomina").prop('checked')) {
            eliminarnomina = 1;
        }
    }
    
    if ($("#collapse15").hasClass('in')) {
        cartaporte = 1;

        if ($("#listaubicacion").prop('checked')) {
            listaubicacion = 1;
        }
        if ($("#nuevaubicacion").prop('checked')) {
            crearubicacion = 1;
        }
        if ($("#editarubicacion").prop('checked')) {
            editarubicacion = 1;
        }
        if ($("#eliminarubicacion").prop('checked')) {
            eliminarubicacion = 1;
        }
        
        if ($("#listatransporte").prop('checked')) {
            listatransporte = 1;
        }
        if ($("#nuevotransporte").prop('checked')) {
            creartransporte = 1;
        }
        if ($("#editartransporte").prop('checked')) {
            editartransporte = 1;
        }
        if ($("#eliminartransporte").prop('checked')) {
            eliminartransporte = 1;
        }
        
        if ($("#listaremolque").prop('checked')) {
            listaremolque = 1;
        }
        if ($("#nuevoremolque").prop('checked')) {
            crearremolque = 1;
        }
        if ($("#editarremolque").prop('checked')) {
            editarremolque = 1;
        }
        if ($("#eliminarremolque").prop('checked')) {
            eliminarremolque = 1;
        }
        
        if ($("#listaoperador").prop('checked')) {
            listaoperador = 1;
        }
        if ($("#nuevooperador").prop('checked')) {
            crearoperador = 1;
        }
        if ($("#editaroperador").prop('checked')) {
            editaroperador = 1;
        }
        if ($("#eliminaroperador").prop('checked')) {
            eliminaroperador = 1;
        }
        
        if ($("#listacarta").prop('checked')) {
            listacarta = 1;
        }
        if ($("#nuevacarta").prop('checked')) {
            crearcarta = 1;
        }
        if ($("#editarcarta").prop('checked')) {
            editarcarta = 1;
        }
        if ($("#eliminarcarta").prop('checked')) {
            eliminarcarta = 1;
        }
    }

    if ($("#collapse3").hasClass('in')) {
        cotizacion = 1;
        if ($("#crearcot").prop('checked')) {
            crearcotizacion = 1;
        }
        if ($("#editarcot").prop('checked')) {
            editarcot = 1;
        }
        if ($("#eliminarcot").prop('checked')) {
            eliminarcot = 1;
        }
        if ($("#listacot").prop('checked')) {
            listacotizacion = 1;
        }
        if ($("#anticipo").prop('checked')) {
            anticipo = 1;
        }
    }

    if ($("#collapse4").hasClass('in')) {
        cliente = 1;
        if ($("#crearcliente").prop('checked')) {
            crearcliente = 1;
        }
        if ($("#editarcliente").prop('checked')) {
            editarcliente = 1;
        }
        if ($("#eliminarcliente").prop('checked')) {
            eliminarcliente = 1;
        }
        if ($("#listacliente").prop('checked')) {
            listacliente = 1;
        }
    }

    if ($("#collapse5").hasClass('in')) {
        comunicado = 1;
        if ($("#crearcomunicado").prop('checked')) {
            crearcomunicado = 1;
        }
        if ($("#editarcomunicado").prop('checked')) {
            editarcomunicado = 1;
        }
        if ($("#eliminarcomunicado").prop('checked')) {
            eliminarcomunicado = 1;
        }
        if ($("#listacomunicado").prop('checked')) {
            listacomunicado = 1;
        }
    }

    if ($("#collapse6").hasClass('in')) {
        producto = 1;
        if ($("#crearproducto").prop('checked')) {
            crearproducto = 1;
        }
        if ($("#editarproducto").prop('checked')) {
            editarproducto = 1;
        }
        if ($("#eliminarproducto").prop('checked')) {
            eliminarproducto = 1;
        }
        if ($("#listaproducto").prop('checked')) {
            listaproducto = 1;
        }
    }

    if ($("#collapse7").hasClass('in')) {
        proveedor = 1;
        if ($("#crearproveedor").prop('checked')) {
            crearproveedor = 1;
        }
        if ($("#editarproveedor").prop('checked')) {
            editarproveedor = 1;
        }
        if ($("#eliminarproveedor").prop('checked')) {
            eliminarproveedor = 1;
        }
        if ($("#listaproveedor").prop('checked')) {
            listaproveedor = 1;
        }
    }

    if ($("#collapse13").hasClass('in')) {
        impuesto = 1;
        if ($("#crearimpuesto").prop('checked')) {
            crearimpuesto = 1;
        }
        if ($("#editarimpuesto").prop('checked')) {
            editarimpuesto = 1;
        }
        if ($("#eliminarimpuesto").prop('checked')) {
            eliminarimpuesto = 1;
        }
        if ($("#listaimpuesto").prop('checked')) {
            listaimpuesto = 1;
        }
    }

    if ($("#collapse8").hasClass('in')) {
        datosfacturacion = 1;
        if ($("#creardatos").prop('checked')) {
            creardatos = 1;
        }
        if ($("#editardatos").prop('checked')) {
            editardatos = 1;
        }
        if ($("#listadatos").prop('checked')) {
            listadatos = 1;
        }
    }

    if ($("#collapse9").hasClass('in')) {
        contrato = 1;
        if ($("#crearcontrato").prop('checked')) {
            crearcontrato = 1;
        }
        if ($("#editarcontrato").prop('checked')) {
            editarcontrato = 1;
        }
        if ($("#eliminarcontrato").prop('checked')) {
            eliminarcontrato = 1;
        }
        if ($("#listacontrato").prop('checked')) {
            listacontrato = 1;
        }
    }

    if ($("#collapse10").hasClass('in')) {
        usuarios = 1;
        if ($("#crearusuario").prop('checked')) {
            crearusuario = 1;
        }
        if ($("#listausuario").prop('checked')) {
            listausuario = 1;
        }
        if ($("#eliminarusuario").prop('checked')) {
            eliminarusuario = 1;
        }
        if ($("#asgpermiso").prop('checked')) {
            asigpermiso = 1;
        }
    }

    if ($("#collapse11").hasClass('in')) {
        reporte = 1;
        if ($("#repfactura").prop('checked')) {
            reportefactura = 1;
        }
        if ($("#reppago").prop('checked')) {
            reportepago = 1;
        }
        if ($("#repgrafica").prop('checked')) {
            reportegrafica = 1;
        }
        if ($("#repiva").prop('checked')) {
            reporteiva = 1;
        }
        if ($("#listaiva").prop('checked')) {
            datosiva = 1;
        }
        if ($("#repventas").prop('checked')) {
            reporteventas = 1;
        }
    }


    if ($("#collapse12").hasClass('in')) {
        configuracion = 1;
        if ($("#addfolio").prop('checked')) {
            addfolio = 1;
        }
        if ($("#listfolio").prop('checked')) {
            listafolio = 1;
        }
        if ($("#editfolio").prop('checked')) {
            editfolio = 1;
        }
        if ($("#delfolio").prop('checked')) {
            eliminarfolio = 1;
        }
        if ($("#addcomision").prop('checked')) {
            addcomision = 1;
        }
        if ($("#confencabezado").prop('checked')) {
            encabezados = 1;
        }
        if ($("#confcorreo").prop('checked')) {
            confcorreo = 1;
        }
        if ($("#imptablas").prop('checked')) {
            importar = 1;
        }
    }

    $.ajax({
        url: "com.sine.enlace/enlaceusuario.php",
        type: "POST",
        data: {transaccion: "actualizarpermisos", idusuario: idusuario, accion:accion, facturas: facturas, crearfactura: crearfactura, editarfactura: editarfactura, eliminarfactura: eliminarfactura, listafactura: listafactura, pago: pago, crearpago: crearpago, editarpago: editarpago, eliminarpago: eliminarpago, listapago: listapago, nomina:nomina, listaempleado:listaempleado, crearempleado:crearempleado, editarempleado: editarempleado, eliminarempleado:eliminarempleado, listanomina:listanomina, crearnomina:crearnomina, editarnomina:editarnomina, eliminarnomina:eliminarnomina, cartaporte:cartaporte, listaubicacion:listaubicacion, crearubicacion:crearubicacion, editarubicacion:editarubicacion, eliminarubicacion:eliminarubicacion, listatransporte:listatransporte, creartransporte:creartransporte, editartransporte:editartransporte, eliminartransporte:eliminartransporte, listaremolque:listaremolque, crearremolque:crearremolque, editarremolque:editarremolque, eliminarremolque:eliminarremolque, listaoperador:listaoperador, crearoperador:crearoperador, editaroperador:editaroperador, eliminaroperador:eliminaroperador, listacarta:listacarta, crearcarta:crearcarta, editarcarta:editarcarta, eliminarcarta:eliminarcarta, cotizacion: cotizacion, crearcotizacion: crearcotizacion, editarcot: editarcot, eliminarcot: eliminarcot, listacotizacion: listacotizacion, anticipo: anticipo, cliente: cliente, crearcliente: crearcliente, editarcliente: editarcliente, eliminarcliente: eliminarcliente, listacliente: listacliente, comunicado: comunicado, crearcomunicado: crearcomunicado, editarcomunicado: editarcomunicado, eliminarcomunicado: eliminarcomunicado, listacomunicado: listacomunicado, producto: producto, crearproducto: crearproducto, editarproducto: editarproducto, eliminarproducto: eliminarproducto, listaproducto: listaproducto, proveedor: proveedor, crearproveedor: crearproveedor, editarproveedor: editarproveedor, eliminarproveedor: eliminarproveedor, listaproveedor: listaproveedor, impuesto: impuesto, crearimpuesto: crearimpuesto, editarimpuesto: editarimpuesto, eliminarimpuesto: eliminarimpuesto, listaimpuesto: listaimpuesto, datosfacturacion: datosfacturacion, creardatos: creardatos, editardatos: editardatos, listadatos: listadatos, contrato: contrato, crearcontrato: crearcontrato, editarcontrato: editarcontrato, eliminarcontrato: eliminarcontrato, listacontrato: listacontrato, usuarios: usuarios, crearusuario: crearusuario, listausuario: listausuario, eliminarusuario:eliminarusuario, asigpermiso: asigpermiso, reporte: reporte, reportefactura: reportefactura, reportepago: reportepago, reportegrafica: reportegrafica, reporteiva: reporteiva, datosiva: datosiva, reporteventas: reporteventas, configuracion: configuracion, addfolio: addfolio, listafolio: listafolio, editfolio: editfolio, eliminarfolio: eliminarfolio, addcomision: addcomision, encabezados: encabezados, confcorreo: confcorreo, importar:importar},
        success: function (datos) {
            //alert(datos);
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
            } else {
                if(idlogin != idusuario){
                    loadView('listasuarioaltas');
                }else{
                    location.href = 'home.php';
                }
                alertify.success('Se guardaron los datos correctamente ');
            }
        }
    });
}

function editarUsuario(idusuario) {
    cargandoHide();
    cargandoShow();
    $.ajax({
        url: "com.sine.enlace/enlaceusuario.php",
        type: "POST",
        data: {transaccion: "editarusuario", idusuario: idusuario},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
            } else {
                cargandoHide();
                loadView('nuevousuario');
                window.setTimeout("setValoresEditarUsuario('" + datos + "')", 400);
            }
        }
    });
}

function checkUser() {
    var chuser = 0;
    if ($("#chuser").prop('checked')) {
        chuser = 1;
    }
    if (chuser == 0) {
        $("#usuario").prop('disabled', true);
    } else {
        alertify.confirm("Estas seguro que deseas cambiar el nombre de usuario?", function () {
            $("#usuario").removeAttr('disabled');
        }, function () {
            $("#chuser").removeAttr('checked');
        }).set({title: "Q-ik"});
    }
}

function checkContrasena() {
    var chpass = 0;
    if ($("#chpass").prop('checked')) {
        chpass = 1;
    }
    if (chpass == 0) {
        $("#contrasena").prop('disabled', true);
        $("#contrasena").val('');
    } else {
        alertify.confirm("Estas seguro que deseas cambiar la contraseña de este usuario?", function () {
            $("#contrasena").removeAttr('disabled');
        }, function () {
            $("#chpass").removeAttr('checked');
        }).set({title: "Q-ik"});
    }
}

function setValoresEditarUsuario(datos) {
    // alert(datos);
    $("#usuario").attr('disabled', true);
    $("#contrasena").attr('disabled', true);
    changeText("#contenedor-titulo-form-usuario", "Editar usuario");
    changeText("#btn-form-usuario", "Guardar cambios <span class='glyphicon glyphicon-floppy-disk'></span></a>");
    var array = datos.split("</tr>");
    var idusuario = array[0];
    var nombre = array[1];
    var apellidopaterno = array[2];
    var apellidomaterno = array[3];
    var telefono = array[7];
    var celular = array[6];
    var correo = array[5];
    var usuario = array[4];
    var estatus = array[8];
    var contraseña = array[9];
    var tipo = array[10];
    var idlogin = array[11];
    var tipologin = array[12];
    var imgnm = array[13];
    var img = array[14];

    $("#nombre").val(nombre);
    $("#apellido-paterno").val(apellidopaterno);
    $("#apellido-materno").val(apellidomaterno);
    $("#celular").val(celular);
    $("#telefono").val(telefono);
    $("#correo").val(correo);
    $("#usuario").val(usuario);
    $("#tipo-usuario").val(tipo);
    
    if(imgnm !== ''){
        $("#muestraimagen").html(img);
        $("#filename").val(imgnm);
    }

    if (tipologin == '2') {
        $("#tipo-usuario").attr('disabled', true);
    }
    if (idusuario == idlogin || tipologin == '1') {
        $("#div-user").addClass('col-md-10');
        $("#span-user").addClass('col-md-2');
        $("#span-user").append("<input class='input-check' type='checkbox' id='chuser' onclick='checkUser()' title='Cambiar nombre de usuario'/>");
        
        $("#div-pass").addClass('col-md-10');
        $("#span-pass").addClass('col-md-2');
        $("#span-pass").append("<input class='input-check' type='checkbox' id='chpass' onclick='checkContrasena()' title='Cambiar contraseña'/>");
    }
    $("#contrasena").val("");

    $("#form-usuario").append("<input type='hidden' id='id-usuario' name='id-usuario' value='" + idusuario + "'/><input type='hidden' id='imgactualizar' name='imgactualizar' value='" + img + "'/>")
    $("#btn-form-usuario").attr("onclick", "actualizarUsuario();");

}

function actualizarUsuario() {
    var idusuario = $("#id-usuario").val();
    var nombre = $("#nombre").val();
    var apellidopaterno = $("#apellido-paterno").val();
    var apellidomaterno = $("#apellido-materno").val();
    var telefono = $("#telefono").val();
    var celular = $("#celular").val();
    var correo = $("#correo").val();
    var usuario = $("#usuario").val();
    var contrasena = $("#contrasena").val();
    var tipo = $("#tipo-usuario").val();
    var img = $("#filename").val();
    var imgactualizar = $("#imgactualizar").val();
    var chpass = 0;
    if ($("#chpass").prop('checked')) {
        chpass = 1;
    }
    if (isnEmpty(nombre, "nombre") && isnEmpty(apellidopaterno, "apellido-paterno") && isnEmpty(apellidomaterno, "apellido-materno") && isPhoneNumber(telefono, "telefono") && isPhoneNumber(celular, "celular") && isnEmpty(usuario, "usuario") && isEmail(correo, "correo")) {
        $.ajax({
            url: "com.sine.enlace/enlaceusuario.php",
            type: "POST",
            data: {transaccion: "actualizarusuario", idusuario: idusuario, nombre: nombre, apellidopaterno: apellidopaterno, apellidomaterno: apellidomaterno, telefono: telefono, usuario: usuario, contrasena: contrasena, celular: celular, correo: correo, tipo: tipo, chpass: chpass, img: img, imgactualizar:imgactualizar},
            success: function (datos) {
                //alert(datos);
                var texto = datos.toString();
                var bandera = texto.substring(0, 1);
                var res = texto.substring(1, 1000);
                if (bandera == '0') {
                    alertify.error(res);
                } else {
                    alertify.success('Se guardaron los datos correctamente ');
                    if (img != "") {
                        location.href = 'home.php';
                    } else {
                        loadView('listasuarioaltas');
                    }
                }
            }
        });
    }
}

function eliminarUsuario(idusuario) {
    alertify.confirm("Estas seguro que quieres eliminar este usuario?", function () {
        cargandoHide();
        cargandoShow();
        $.ajax({
            url: "com.sine.enlace/enlaceusuario.php",
            type: "POST",
            data: {transaccion: "eliminarusuario", idusuario: idusuario},
            success: function (datos) {
                var texto = datos.toString();
                var bandera = texto.substring(0, 1);
                var res = texto.substring(1, 1000);
                if (bandera == '0') {
                    alertify.error(res);
                } else {
                    cargandoHide();
                    loadView('listasuarioaltas');
                }
            }
        });
    }).set({title: "Q-ik"});
}

function filtrarUsuario(pag = "") {
    cargandoHide();
    cargandoShow();
    var US = $("#buscar-usuario").val();
    var numreg = $("#num-reg").val();
    $.ajax({
        url: "com.sine.enlace/enlaceusuario.php",
        type: "POST",
        data: {transaccion: "filtrarusuario", US: US, numreg: numreg, pag: pag},
        success: function (datos) {
            //alert(datos);
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
                cargandoHide();
            } else {
                $("#body-lista-usuarios-altas").html(datos);
                cargandoHide();
            }
        }
    });
}

function buscarUsuario(pag = "") {
    var US = $("#buscar-usuario").val();
    var numreg = $("#num-reg").val();
    $.ajax({
        url: "com.sine.enlace/enlaceusuario.php",
        type: "POST",
        data: {transaccion: "filtrarusuario", US: US, numreg: numreg, pag: pag},
        success: function (datos) {
            //alert(datos);
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
            } else {
                $("#body-lista-usuarios-altas").html(datos);
            }
        }
    });
}