$(function () {
    $(".button-config").click(function () {
        $('.button-config').removeClass("conf-active");
        $(this).addClass("conf-active");
    });
});

function firmaCanvas() {
    var canvas = document.getElementById('firma-canvas');
    var signaturePad = new SignaturePad(canvas);
    signaturePad.on();

    document.getElementById('clear').addEventListener('click', function () {
        signaturePad.clear();
    });

    document.getElementById('undo').addEventListener('click', function () {
        var data = signaturePad.toData();
        if (data) {
            data.pop(); // remove the last dot or line
            signaturePad.fromData(data);
        }
    });

    document.getElementById('save').addEventListener('click', function () {
        if (signaturePad.isEmpty()) {
            return alertify.error("Dibuje una firma valida");
        }

        var firma = canvas.toDataURL();
        var firmaanterior = $("#firma-actual").val();
        alertify.confirm("Esta reemplazara la firma actual, ¿continuar?", function () {
            cargandoHide();
            cargandoShow();
            $.ajax({
                url: "com.sine.enlace/enlaceconfig.php",
                type: "POST",
                data: {transaccion: "guardarfirma", firma: firma, firmaanterior: firmaanterior},
                success: function (datos) {
                    var texto = datos.toString();
                    var bandera = texto.substring(0, 1);
                    var res = texto.substring(1, 1000);
                    if (bandera == '0') {
                        alertify.error(res);
                        cargandoHide();
                    } else {
                        cargandoHide();
                        var arr = datos.split("<corte>");
                        var img = arr[1];
                        $("#div-firma").html("<img src='img/logo/" + img + "' width='200px' id='imgfirma'>");
                        $("#firma-actual").val(img);
                    }
                }
            });
        }).set({title: "Q-ik"});

    });
}

function loadBtnCrearConfig(view) {
    $.ajax({
        url: "com.sine.enlace/enlacepermiso.php",
        type: "POST",
        data: {transaccion: "loadbtn", view: view},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            $("#btn-crear-folio").html(datos);
            cargandoHide();
        }
    });
}

function getViewConfig(view) {
    //cargandoShow();
    $.ajax({
        url: 'com.sine.enlace/enlaceenrutador.php',
        type: 'POST',
        data: {transaccion: "hola", view: view},
        success: function (datos) {
//            alertify.alert(datos+"    "+view);
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var resto = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(resto);
                //cargandoHide();
            } else {
                $("#view-config").html('');
                $("#view-config").html(datos);
                //cargandoHide();
            }
        }
    });
}

function loadViewConfig(vista) {
    switch (vista) {
        case 'encabezado':
            getViewConfig(vista);
            break;
        case 'correo':
            getViewConfig(vista);
            window.setTimeout("opcionesCorreoList()", 300);
            break;
        case 'folio':
            getViewConfig(vista);
            window.setTimeout("loadopcionesFolioDatos()", 300);
            break;
        case 'listafolio':
            getViewConfig(vista);
            window.setTimeout("loadListaFolio()", 400);
            window.setTimeout("loadBtnCrearConfig('folio')", 450);
            break;
        case 'comision':
            getViewConfig(vista);
            window.setTimeout("loadOpcionesUsuario()", 400);
            break;
        case 'tablas':
            getViewConfig(vista);
            //window.setTimeout("loadOpcionesUsuario()", 400);
            break;
        default :
            break;
    }
}

function guardarLogo() {
    var logo = $("#imagen").val();
    var logoactual = $("#logo-actual").val();
    alertify.confirm("Esta reemplazara la firma actual, ¿continuar?", function () {
        $.ajax({
            url: "com.sine.enlace/enlaceconfig.php",
            type: "POST",
            data: {transaccion: "guardarlogo", logo: logo, logoactual: logoactual},
            success: function (datos) {
                var texto = datos.toString();
                var bandera = texto.substring(0, 1);
                var res = texto.substring(1, 1000);
                if (bandera == '0') {
                    alertify.error(res);
                    cargandoHide();
                } else {
                    //alert(datos);
                    var arr = datos.split("<corte>");
                    var img = arr[1];
                    var arr2 = img.split("/");
                    var logo = arr2[0];
                    $("#div-logo").html("<img src='img/logo/" + logo + "' width='200px' id='imgfirma'>");
                    $("#logo-actual").val(logo);
                }
            }
        });
    }).set({title: "Q-ik"});
}

function loadEncabezado() {
    var encabezado = $("#id-encabezado").val();
    if (isnEmpty(encabezado, "id-encabezado")) {
        cargandoHide();
        cargandoShow();
        $.ajax({
            url: "com.sine.enlace/enlaceconfig.php",
            type: "POST",
            data: {transaccion: "editarencabezado", encabezado: encabezado},
            success: function (datos) {
                var texto = datos.toString();
                var bandera = texto.substring(0, 1);
                var res = texto.substring(1, 1000);
                if (bandera == '0') {
                    alertify.error(res);
                    cargandoHide();
                } else {
                    //alert(datos);
                    hideCommon();
                    setValoresEncabezado(datos);
                }
            }
        });
    }
}

function setValoresEncabezado(datos) {
    var idencabezado = $("#id-encabezado").val();
    var array = datos.split("</tr>");
    var tituloenc = array[0];
    var titulocarta = array[1];
    var colortitulo = array[2];
    var colorceltitulo = array[3];
    var colorcuadro = array[4];
    var colorsub = array[5];
    var colorfdatos = array[6];
    var colorbold = array[7];
    var colortexto = array[8];
    var colorhtabla = array[9];
    var colortittabla = array[10];
    var pagina = array[11];
    var correo = array[12];
    var tel1 = array[13];
    var tel2 = array[14];
    var numpag = array[15];
    var colorpie = array[16];
    var imglogo = array[17];
    var imgarr = imglogo.split("/");
    var observaciones = array[18];

    $("#titulo").val(tituloenc);
    $("#color-titulo").val(colortitulo);
    $("#color-celda").val(colorceltitulo);
    $("#color-cuadro").val(colorcuadro);
    $("#color-subtitulo").val(colorsub);
    $("#fondo-datos").val(colorfdatos);
    $("#texto-bold").val(colorbold);
    $("#color-texto").val(colortexto);
    $("#color-tabla").val(colorhtabla);
    $("#titulos-tabla").val(colortittabla);
    $("#pagina").val(pagina);
    $("#correo").val(correo);
    $("#telefono1").val(tel1);
    $("#telefono2").val(tel2);
    $("#color-pie").val(colorpie);
    $("#filename").val(imgarr[0]);
    $("#imgactualizar").val(imglogo);

    
    if( idencabezado == '12' ){

        $('#width-ticket').val(numpag);
        var array_datos = titulocarta.split('</>');

        if( array_datos[0] == 1){
            $('#chk-data').prop('checked', true);
            $('#datos-empresa').show('slow');
            $('#header-logo').show('slow');

        } else if( array_datos[0] == 2){
            $('#chk-logo').prop('checked', true);
            $('#data-empresa').hide('slow');
            $('#header-logo').show('slow');

        }else if( array_datos[0] == 3){
            $('#chk-only-data').prop('checked', true);
            $('#datos-empresa').show('slow');
            $('#header-logo').hide('slow');
        }
        
        $('#nombre-empresa').val(array_datos[1]);
        $('#razon-social').val(array_datos[2]);
        $('#direccion').val(array_datos[3]);
        $('#rfc-empresa').val(array_datos[4]);
    }

    if (numpag == '1') {
        $("#chnum").prop('checked', true);
    } else {
        $("#chnum").removeAttr('checked');
    }

    if (imglogo !== '') {
        $("#muestraimagen").html("<img src='img/logo/" + imglogo + "' height='150px'>");
    }

    if (idencabezado == '3' || idencabezado == '12') {
        var txtbd = observaciones.replace(new RegExp("<ent>", 'g'), '\n');
        $("#add-observaciones").removeAttr('hidden');
        $("#observaciones-cot").val(txtbd);
    } else {
        $("#add-observaciones").attr('hidden', true);
        $("#observaciones-cot").val('');
    }
    
    if (idencabezado == '11') {
        $("#carta-titulo").removeAttr('hidden');
        $("#titulo2").val(titulocarta);
    } else {
        $("#carta-titulo").attr('hidden', true);
        $("#titulo2").val('');
    }
    
    cargandoHide();
    visualizarPDF();
}

function cargarLogo() {
    var formData = new FormData(document.getElementById("form-encabezado"));
    var idencabezado = $("#id-encabezado").val();
    var img = $("#imagen").val();
    if (isnEmpty(idencabezado, "id-encabezado") && isnEmpty(img, "imagen")) {
        $.ajax({
            url: 'com.sine.enlace/cargarimg.php',
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function (datos) {
                //alert(datos);
                var array = datos.split("<corte>");
                $("#muestraimagen").html(array[0]);
                $("#filename").val(array[1]);
                visualizarPDF();
            }
        });
    }
}

function visualizarPDF() {
    var widthticket = $('#width-ticket').val();
    if (widthticket < 58) {
        widthticket = 58;
        $('#width-ticket').val(58); 
    }
    var idencabezado = $("#id-encabezado").val();
    var titulo = $("#titulo").val();
    var titulo2 = $("#titulo2").val();
    var clrtitulo = $("#color-titulo").val();
    var colorcelda = $("#color-celda").val();
    var clrcuadro = $("#color-cuadro").val();
    var clrsub = $("#color-subtitulo").val();
    var clrfdatos = $("#fondo-datos").val();
    var txtbold = $("#texto-bold").val();
    var clrtxt = $("#color-texto").val();
    var colorhtabla = $("#color-tabla").val();
    var tittabla = $("#titulos-tabla").val();
    var pagina = $("#pagina").val();
    var correo = $("#correo").val();
    var tel1 = $("#telefono1").val();
    var tel2 = $("#telefono2").val();
    var clrpie = $("#color-pie").val();
    var imagen = $('#filename').val();
    var widthticket = $('#width-ticket').val();
    var rfcempresa = $('#rfc-empresa').val();
    var direccion = $('#direccion').val();
    var razonsocial = $('#razon-social').val();
    var nombreempresa = $('#nombre-empresa').val(); 

    var observaciones = "";
    if (idencabezado == '3' || idencabezado == '12') {
        observaciones = $("#observaciones-cot").val();
    }

    var chnum = 0;
    if ($("#chnum").prop('checked')) {
        chnum = 1;
    }
    var chlogo = 0;
    if ($("#chlogo").prop('checked')) {
        chlogo = 1;
    }

    var chkdata = 0;
    if( $('#chk-data').is(':checked') ){//datos y logo
        chkdata = 1;
    } else if( $('#chk-logo').is(':checked') ){//solo logo
        chkdata = 2;
    }else if( $('#chk-only-data').is(':checked') ){//solo datos
        chkdata = 3;
    }
    if (isnEmpty(idencabezado, "id-encabezado")) {
        console.log(widthticket);
        $.ajax({
            url: "com.sine.imprimir/configpdf.php",
            type: "POST",
            data: {transaccion: "pdf", idencabezado: idencabezado, titulo: titulo, clrtitulo: clrtitulo, colorcelda: colorcelda, clrcuadro: clrcuadro, clrsub: clrsub, clrfdatos: clrfdatos, txtbold: txtbold, clrtxt: clrtxt, colorhtabla: colorhtabla, tittabla: tittabla, pagina: pagina, correo: correo, tel1: tel1, tel2: tel2, clrpie: clrpie, imagen: imagen, chnum: chnum, chlogo: chlogo, observaciones: observaciones, widthticket: widthticket, titulo2: titulo2, rfcempresa: rfcempresa, direccion: direccion, razonsocial: razonsocial, nombreempresa: nombreempresa, chkdata: chkdata},
            success: function (datos) {
                var texto = datos.toString();
                var bandera = texto.substring(0, 1);
                var res = texto.substring(0, 1000);
                if (bandera == '0') {
                    alertify.error(res);
                } else {
                    //alert(datos);
                    document.getElementById('myIframe').contentDocument.location.reload()
                }
                cargandoHide();
            }
        });
    }
}

function actualizarEncabezado() {
    var idencabezado = $("#id-encabezado").val();
    var titulo = $("#titulo").val();
    var titulocarta = $("#titulo2").val();
    var clrtitulo = $("#color-titulo").val();
    var colorcelda = $("#color-celda").val();
    var clrcuadro = $("#color-cuadro").val();
    var clrsub = $("#color-subtitulo").val();
    var clrfdatos = $("#fondo-datos").val();
    var txtbold = $("#texto-bold").val();
    var clrtxt = $("#color-texto").val();
    var colorhtabla = $("#color-tabla").val();
    var tittabla = $("#titulos-tabla").val();
    var pagina = $("#pagina").val();
    var correo = $("#correo").val();
    var tel1 = $("#telefono1").val();
    var tel2 = $("#telefono2").val();
    var clrpie = $("#color-pie").val();
    var imagen = $('#filename').val();
    var imgactualizar = $("#imgactualizar").val();
    var txtbd = "";
    if (idencabezado == '3' || idencabezado == '12') {
        var observaciones = $("#observaciones-cot").val();
        txtbd = observaciones.replace(new RegExp("\n", 'g'), '<ent>');
    }

    var chnum = 0;
    if ($("#chnum").prop('checked')) {
        chnum = 1;
    }

    if( idencabezado == '12' ){
        var chkdata = 0;
        var rfcempresa = $('#rfc-empresa').val();
        var direccion = $('#direccion').val();
        var razonsocial = $('#razon-social').val();
        var nombreempresa = $('#nombre-empresa').val();

        chnum = $('#width-ticket').val();

        if( $('#chk-data').is(':checked') ){//datos y logo
        chkdata = 1;
        } else if( $('#chk-logo').is(':checked') ){//solo logo
            chkdata = 2;
        }else if( $('#chk-only-data').is(':checked') ){//solo datos
            chkdata = 3;
        }
        titulocarta =  chkdata+'</>'+nombreempresa+'</>'+razonsocial+'</>'+direccion+'</>'+rfcempresa;
    }

    var chlogo = 0;
    if ($("#chlogo").prop('checked')) {
        chlogo = 1;
    }

    if (isnEmpty(idencabezado, "id-encabezado") && isnEmpty(titulo, "titulo") && isnEmpty(clrtitulo, "color-titulo") && isnEmpty(tel1, "telefono1")) {
        cargandoHide();
        cargandoShow();
        $.ajax({
            url: "com.sine.enlace/enlaceconfig.php",
            type: "POST",
            data: {transaccion: "actualizarencabezado", idencabezado: idencabezado, titulo: titulo, titulocarta:titulocarta, clrtitulo: clrtitulo, colorcelda: colorcelda, clrcuadro: clrcuadro, clrsub: clrsub, clrfdatos: clrfdatos, txtbold: txtbold, clrtxt: clrtxt, colorhtabla: colorhtabla, tittabla: tittabla, pagina: pagina, correo: correo, tel1: tel1, tel2: tel2, clrpie: clrpie, imagen: imagen, chnum: chnum, imgactualizar: imgactualizar, chlogo: chlogo, observaciones: txtbd},
            success: function (datos) {
                var texto = datos.toString();
                var bandera = texto.substring(0, 1);
                var res = texto.substring(1, 1000);
                if (bandera == '0') {
                    cargandoHide();
                    alertify.error(res);
                } else {
                    cargandoHide();
                    //alert(datos);
                    alertify.success('Datos Guardados');
                    loadViewConfig('encabezado');
                }
            }
        });
    }
}

function typeText() {
    var asunto = $("#asunto").val();
    var saludo = $("#saludo").val();
    var nombre = "(Razon Social del cliente)";
    var mensaje = $("#texto-correo").val();
    var txtformat = mensaje.replace(new RegExp("\n", 'g'), "</p> <p style='font-size:18px; text-align: justify;'>");

    $("#asunto-lab").html(asunto);
    $("#saludo-lab").html(saludo + "" + nombre);
    $("#txt-lab").html(txtformat);
}

function loaddatosUsuario() {
    var idusuario = $("#id-usuario").val();
    $.ajax({
        url: 'com.sine.enlace/enlaceconfig.php',
        type: 'POST',
        data: {transaccion: 'datosusuario', idusuario: idusuario},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            if (bandera == 0) {
                alertify.error(res);
            } else {
                //alert(datos);
                setValoresUsuarioComision(datos);

            }
            //cargandoHide();
        }
    });
}

function setValoresUsuarioComision(datos) {
    var array = datos.split("</tr>");
    var tipo = array[0];
    var t = "";
    var check = array[1];

    if (tipo === '1') {
        t = "Administrador";
    } else {
        t = "Vendedor";
    }

    $("#tipo-usuario").val(t);

    if (check != '0') {
        var idcomision = array[2];
        var idusu = array[3];
        var porcentaje = array[4];
        var calculo = array[5];



        changeText("#btn-form-quicom", "Eliminar comisión <span class='fas fa-times'></span>");
        $("#btn-form-quicom").attr('onclick', 'quitarComision(' + idcomision + ')');
        $("#btn-form-quicom").removeClass('visually-hidden');


        $("#porcentaje-comision").val(porcentaje);
        $("#calculo" + calculo).prop('checked', true);
        changeText("#btn-form-comision", "Actualizar Comision <span class='fas fa-save'></span>");
        $("#btn-form-comision").attr('onclick', 'actualizarComision(' + idcomision + ')');
    } else {
        $("#porcentaje-comision").val('');
        $("#calculo1").prop('checked', true);
        changeText("#btn-form-comision", "Guardar <span class='fas fa-save'></span>");
        $("#btn-form-comision").attr('onclick', 'insertarComision()');
    }
}

function quitarComision(idcomision){
    alertify.confirm("Estas seguro que quieres eliminar la comision de este usuario?", function () {
        cargandoHide();
        cargandoShow();
        $.ajax({
            url: "com.sine.enlace/enlaceconfig.php",
            type: "POST",
            data: {transaccion: "quitarcomision", idcomision: idcomision},
            success: function (datos) {
                var texto = datos.toString();
                var bandera = texto.substring(0, 1);
                var res = texto.substring(1, 1000);
                
                if (bandera == '0') {
                    alertify.error(res);
                }
                else {
                    cargandoHide();
                    alertify.success("comision eliminada");
                    loadView('config');
                }
            }
        });
    }).set({title: "Q-ik"});
}

function insertarComision() {
    var idusuario = $("#id-usuario").val();
    var porcentaje = $("#porcentaje-comision").val();
    var chcalculo = $("input[name=calculo]:checked").val();
    var chcom = 0;
    if ($("#chcom").prop('checked')) {
        chcom = 1;
    }

    if (isnEmpty(idusuario, "id-usuario") && isValidPercentage(porcentaje, "porcentaje-comision")) {
        cargandoHide();
        cargandoShow();
        $.ajax({
            url: "com.sine.enlace/enlaceconfig.php",
            type: "POST",
            data: {transaccion: "insertarcomision", idusuario: idusuario, porcentaje: porcentaje, chcalculo: chcalculo, chcom: chcom},
            success: function (datos) {
                var texto = datos.toString();
                var bandera = texto.substring(0, 1);
                var res = texto.substring(1, 1000);
                if (bandera == '0') {
                    cargandoHide();
                    alertify.error(res);
                } else {
                    cargandoHide();
                    //alert(datos);
                    alertify.success('comision registrada');
                    loadViewConfig('comision');
                }
            }
        });
    }
}

function actualizarComision(idcomision) {
    var idusuario = $("#id-usuario").val();
    var porcentaje = $("#porcentaje-comision").val();
    var chcalculo = $("input[name=calculo]:checked").val();
    var chcom = 0;
    if ($("#chcom").prop('checked')) {
        chcom = 1;
    }

    if (isnEmpty(idusuario, "id-usuario") && isValidPercentage(porcentaje, "porcentaje-comision")) {
        cargandoHide();
        cargandoShow();
        $.ajax({
            url: "com.sine.enlace/enlaceconfig.php",
            type: "POST",
            data: {transaccion: "actualizarcomision", idcomision: idcomision, idusuario: idusuario, porcentaje: porcentaje, chcalculo: chcalculo, chcom: chcom},
            success: function (datos) {
                var texto = datos.toString();
                var bandera = texto.substring(0, 1);
                var res = texto.substring(1, 1000);
                if (bandera == '0') {
                    cargandoHide();
                    alertify.error(res);
                } else {
                    cargandoHide();
                    //alert(datos);
                    alertify.success('comision actualizada');
                    loadViewConfig('comision');
                }
            }
        });
    }
}

function loadMailConfig() {
    var idcorreo = $("#id-correo").val();
    cargandoHide();
    cargandoShow();
    $.ajax({
        url: "com.sine.enlace/enlaceconfig.php",
        type: "POST",
        data: {transaccion: "loadmail", idcorreo: idcorreo},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
                changeText("#btn-form-correo", "Guardar <span class='glyphicon glyphicon-save-file'></span>");
                $("#btn-form-correo").attr('onclick', 'insertarCorreo()');
                cargandoHide();
            } else {
                setValoresCorreo(datos);
                cargandoHide();
            }
        }
    });
}

function setValoresCorreo(datos) {
    changeText("#btn-form-correo", "Guardar cambios <span class='fas fa-save'></span>");
    var array = datos.split("</tr>");

    var correo = array[0];
    var pass = array[1];
    var remitente = array[2];
    var host = array[4];
    var puerto = array[5];
    var seguridad = array[6];
    var chuso1 = array[7];
    var chuso2 = array[8];
    var chuso3 = array[9];
    var chuso4 = array[10];
    var chuso5 = array[11];
    var chuso6 = array[12];

    $("#correo-uso").val(correo);
    $("#pass").val(pass);
    $("#remitente").val(remitente);
    $("#correo-remitente").val(correo);
    $("#host-correo").val(host);
    $("#puerto-acceso").val(puerto);
    $("#seguridad").val(seguridad);

    if (chuso1 == 1) {
        $("#chuso1").prop('checked', true);
    } else {
        $("#chuso1").prop('checked', false);
    }

    if (chuso2 == 1) {
        $("#chuso2").prop('checked', true);
    } else {
        $("#chuso2").prop('checked', false);
    }

    if (chuso3 == 1) {
        $("#chuso3").prop('checked', true);
    } else {
        $("#chuso3").prop('checked', false);
    }

    if (chuso4 == 1) {
        $("#chuso4").prop('checked', true);
    } else {
        $("#chuso4").prop('checked', false);
    }

    if (chuso5 == 1) {
        $("#chuso5").prop('checked', true);
    } else {
        $("#chuso5").prop('checked', false);
    }
    
    if (chuso6 == 1) {
        $("#chuso6").prop('checked', true);
    } else {
        $("#chuso6").prop('checked', false);
    }

    $("#btn-form-correo").attr('onclick', 'actualizarCorreo()');
}

function actualizarCorreo() {
    var idcorreo = $("#id-correo").val();
    var correo = $("#correo-uso").val();
    var pass = $("#pass").val();
    var remitente = $("#remitente").val();
    var mailremitente = $("#correo-remitente").val();
    var host = $("#host-correo").val();
    var puerto = $("#puerto-acceso").val();
    var seguridad = $("#seguridad").val();
    var chuso1 = 0;
    var chuso2 = 0;
    var chuso3 = 0;
    var chuso4 = 0;
    var chuso5 = 0;

    if ($("#chuso1").prop('checked')) {
        chuso1 = 1;
    }

    if ($("#chuso2").prop('checked')) {
        chuso2 = 1;
    }

    if ($("#chuso3").prop('checked')) {
        chuso3 = 1;
    }

    if ($("#chuso4").prop('checked')) {
        chuso4 = 1;
    }

    if ($("#chuso5").prop('checked')) {
        chuso5 = 1;
    }

    if (isnEmpty(idcorreo, "id-correo") && isEmail(correo, "correo-uso") && isnEmpty(pass, "pass") && isnEmpty(remitente, "remitente") && isnEmpty(host, "host-correo") && isnEmpty(puerto, "puerto-acceso") && isnEmpty(seguridad, "seguridad")) {
        cargandoHide();
        cargandoShow();
        $.ajax({
            url: "com.sine.enlace/enlaceconfig.php",
            type: "POST",
            data: {transaccion: "actualizarcorreo", idcorreo: idcorreo, correo: correo, pass: pass, remitente: remitente, mailremitente: mailremitente, host: host, puerto: puerto, seguridad: seguridad, chuso1: chuso1, chuso2: chuso2, chuso3: chuso3, chuso4: chuso4, chuso5: chuso5},
            success: function (datos) {
                var texto = datos.toString();
                var bandera = texto.substring(0, 1);
                var res = texto.substring(1, 1000);
                if (bandera == '0') {
                    alertify.error(res);
                } else {
                    alertify.success('Datos Guardados');
                    loadViewConfig('correo');
                }
                cargandoHide();
            }
        });
    }
}

function actualizarRemitente() {
    var correoUso = $("#correo-uso").val();
    $("#correo-remitente").val(correoUso);
}
$("#correo-uso").on("keyup", function () {
    actualizarRemitente();
});




function cargarLogoMail() {
    var formData = new FormData(document.getElementById("form-correo"));
    var idbody = $("#id-body").val();
    var img = $("#imagen").val();
    if (isnEmptyImg(idbody, "id-body", 'imagen') && isnEmptyImg(img, "imagen", 'imagen')) {
        cargandoHide();
        cargandoShow();
        $.ajax({
            url: 'com.sine.enlace/cargarimg.php',
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function (datos) {
                var array = datos.split("<corte>");
                $("#muestraimagen").html(array[0]);
                $("#filename").val(array[1]);
                $("#imagen").val('');
                cargandoHide();
            }
        });
    }
}

function getMailBody() {
    var body = $("#id-body").val();
    if (isnEmpty(body, "id-body")) {
        cargandoHide();
        cargandoShow();
        $.ajax({
            url: "com.sine.enlace/enlaceconfig.php",
            type: "POST",
            data: {transaccion: "editarbody", idbody: body},
            success: function (datos) {
                var texto = datos.toString();
                var bandera = texto.substring(0, 1);
                var res = texto.substring(1, 1000);
                if (bandera == '0') {
                    alertify.error(res);
                    cargandoHide();
                } else {
                    setValoresEditarBody(datos);
                    cargandoHide();

                }
            }
        });
    }
}

function setValoresEditarBody(datos) {
    var array = datos.split("</tr>");
    var idmailbody = array[0];
    var asunto = array[1];
    var saludo = array[2];
    var mensaje = array[3];
    var filenm = array[4];
    var logo = array[5];
    var txt = mensaje.replace(new RegExp("<corte>", 'g'), '\n');

    $("#asunto").val(asunto);
    $("#saludo").val(saludo);
    $("#texto-correo").val(txt);
    $("#muestraimagen").html(logo);
    $("#filename").val(filenm);
    $("#imgactualizar").val(filenm);

    typeText();
}

function actualizarBody() {
    var idbody = $("#id-body").val();
    var asunto = $("#asunto").val();
    var saludo = $("#saludo").val();
    var mensaje = $("#texto-correo").val();
    var filenm = $("#filename").val();
    var imgactualizar = $("#imgactualizar").val();
    var chlogo = 0;
    if ($("#chlogo").prop('checked')) {
        chlogo = 1;
    }
    var txtbd = mensaje.replace(new RegExp("\n", 'g'), '<corte>');

    if (isnEmpty(idbody, "id-body") && isnEmpty(asunto, "asunto") && isnEmpty(mensaje, "mensaje")) {
        cargandoHide();
        cargandoShow();
        $.ajax({
            url: "com.sine.enlace/enlaceconfig.php",
            type: "POST",
            data: {transaccion: "actualizarbody", idbody: idbody, asunto: asunto, saludo: saludo, txtbd: txtbd, filenm: filenm, imgactualizar: imgactualizar, chlogo: chlogo},
            success: function (datos) {
                var texto = datos.toString();
                var bandera = texto.substring(0, 1);
                var res = texto.substring(1, 1000);
                if (bandera == '0') {
                    cargandoHide();
                    alertify.error(res);
                } else {
                    cargandoHide();
                    //alert(datos);
                    alertify.success("Datos actualizados");
                    loadViewConfig('correo');
                }
            }
        });
    }
}

function insertarFolio() {
    var serie = $("#serie").val();
    var letra = $("#folio-letra").val();
    var folio = $("#folio-inicio").val();
    var usos = [];
    $.each($("input[name='chusofolio']:checked"), function () {
        usos.push($(this).val());
    });
    var usofolio = usos.join("-");

    if (isnEmpty(serie, "serie") && isnEmpty(folio, "folio-inicio") && isnEmpty(usofolio, "btn-uso")) {
        cargandoHide();
        cargandoShow();
        $.ajax({
            url: "com.sine.enlace/enlaceconfig.php",
            type: "POST",
            data: {transaccion: "insertarfolio", serie: serie, letra: letra, folio: folio, usofolio: usofolio},
            success: function (datos) {
                var texto = datos.toString();
                var bandera = texto.substring(0, 1);
                var res = texto.substring(1, 1000);
                if (bandera == '0') {
                    cargandoHide();
                    alertify.error(res);
                } else {
                    alertify.success('Datos Guardados');
                    loadViewConfig('listafolio');
                    cargandoHide();
                }
            }
        });
    }
}

function buscarFolio(pag = "") {
    var REF = $("#buscar-folio").val();
    var numreg = $("#num-reg").val();

    $.ajax({
        url: "com.sine.enlace/enlaceconfig.php",
        type: "POST",
        data: {transaccion: "listafolios", pag: pag, REF: REF, numreg: numreg},
        success: function (datos) {
            //alert(datos);
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
            } else {
                $("#body-lista-folios").html(datos);
            }
        }
    });
}

function loadListaFolio(pag = "") {
    var REF = $("#buscar-folio").val();
    var numreg = $("#num-reg").val();
    cargandoHide();
    cargandoShow();
    $.ajax({
        url: "com.sine.enlace/enlaceconfig.php",
        type: "POST",
        data: {transaccion: "listafolios", pag: pag, REF: REF, numreg: numreg},
        success: function (datos) {
            //alert(datos);
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
            } else {
                $("#body-lista-folios").html(datos);
            }
            cargandoHide();
        }
    });
}

function editarFolio(idfolio) {
    cargandoHide();
    cargandoShow();
    $.ajax({
        url: "com.sine.enlace/enlaceconfig.php",
        type: "POST",
        data: {transaccion: "editarfolio", idfolio: idfolio},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
                cargandoHide();
            } else {
                //alert(datos);
                loadViewConfig('folio');
                window.setTimeout("setValoresEditarFolio('" + datos + "')", 500);
            }
        }
    });
}

function setValoresEditarFolio(datos) {
    changeText("#contenedor-titulo-form-folio", "Editar Folio");
    changeText("#btn-form-folio", "Guardar cambios <span class='glyphicon glyphicon-floppy-disk'></span></a>");

    var array = datos.split("</tr>");
    var idfolio = array[0];
    var serie = array[1];
    var letra = array[2];
    var numinicio = array[3];
    var uso = array[4];
    var aruso = uso.split("-");
    for (var i = 0, max = aruso.length; i < max; i++) {
        $("#chusofolio" + aruso[i]).prop('checked', true);
        $("#chspan" + aruso[i]).removeClass('glyphicon-unchecked').addClass('glyphicon-check');
    }

    $("#serie").val(serie);
    $("#folio-letra").val(letra);
    $("#folio-inicio").val(numinicio);
    $("#uso-folio").val(uso);

    $("#form-folio").append("<input type='hidden' id='numinicio' name='numinicio' value='" + numinicio + "'/>");
    $("#btn-form-folio").attr("onclick", "actualizarFolio(" + idfolio + ");");
    cargandoHide();
}

function actualizarFolio(idfolio) {
    var serie = $("#serie").val();
    var letra = $("#folio-letra").val();
    var folio = $("#folio-inicio").val();
    var inicio = $("#numinicio").val();
    var usos = [];
    $.each($("input[name='chusofolio']:checked"), function () {
        usos.push($(this).val());
    });
    var usofolio = usos.join("-");

    if (isnEmpty(serie, "serie") && isnEmpty(folio, "folio-inicio")) {
        cargandoHide();
        cargandoShow();
        $.ajax({
            url: "com.sine.enlace/enlaceconfig.php",
            type: "POST",
            data: {transaccion: "actualizarfolio", idfolio: idfolio, serie: serie, letra: letra, folio: folio, usofolio: usofolio, inicio: inicio},
            success: function (datos) {
                var texto = datos.toString();
                var bandera = texto.substring(0, 1);
                var res = texto.substring(1, 1000);
                if (bandera == '0') {
                    cargandoHide();
                    alertify.error(res);
                } else {
                    //alert(datos);
                    alertify.success(datos);
                    loadViewConfig('listafolio');
                    cargandoHide();
                }
            }
        });
    }
}

function eliminarFolio(idfolio) {
    alertify.confirm("¿Esta seguro que desea eliminar este folio?", function () {
        cargandoHide();
        cargandoShow();
        $.ajax({
            url: "com.sine.enlace/enlaceconfig.php",
            type: "POST",
            data: {transaccion: "eliminarfolio", idfolio: idfolio},
            success: function (datos) {
                var texto = datos.toString();
                var bandera = texto.substring(0, 1);
                var res = texto.substring(1, 1000);
                if (bandera == '0') {
                    cargandoHide();
                    alertify.error(res);
                } else {
                    cargandoHide();
                    //alert(datos);
                    alertify.success(datos);
                    loadViewConfig('listafolio');
                }
            }
        });
    }).set({title: "Q-ik"});
}

function insertarCorreo() {
    var correo = $("#correo-uso").val();
    var pass = $("#pass").val();
    var remitente = $("#remitente").val();
    var mailremitente = $("#correo-remitente").val();  // Agregado
    var host = $("#host-correo").val();
    var puerto = $("#puerto-acceso").val();
    var seguridad = $("#seguridad").val();
    var chuso1 = 0;
    var chuso2 = 0;
    var chuso3 = 0;
    var chuso4 = 0;
    var chuso5 = 0;
    var chuso6 = 0;

    if ($("#chuso1").prop('checked')) {
        chuso1 = 1;
    }

    if ($("#chuso2").prop('checked')) {
        chuso2 = 1;
    }

    if ($("#chuso3").prop('checked')) {
        chuso3 = 1;
    }

    if ($("#chuso4").prop('checked')) {
        chuso4 = 1;
    }

    if ($("#chuso5").prop('checked')) {
        chuso5 = 1;
    }
    
    if ($("#chuso6").prop('checked')) {
        chuso6 = 1;
    }

    if (isEmail(correo, "correo-uso") && isnEmpty(pass, "pass") && isnEmpty(remitente, "remitente") && isnEmpty(host, "host-correo") && isnEmpty(puerto, "puerto-acceso") && isnEmpty(seguridad, "seguridad")) {
        cargandoHide();
        cargandoShow();
        $.ajax({
            url: "com.sine.enlace/enlaceconfig.php",
            type: "POST",
            data: {transaccion: "insertarcorreo", correo: correo, pass: pass, remitente: remitente, mailremitente: mailremitente, host: host, puerto: puerto, seguridad: seguridad, chuso1: chuso1, chuso2: chuso2, chuso3: chuso3, chuso4: chuso4, chuso5: chuso5, chuso6:chuso6},
            success: function (datos) {
                var texto = datos.toString();
                var bandera = texto.substring(0, 1);
                var res = texto.substring(1, 1000);
                if (bandera == '0') {
                    cargandoHide();
                    alertify.error(res);
                } else {
                    cargandoHide();
                    //alert(datos);
                    alertify.success('correo insertado');
                    loadViewConfig('correo');
                }
            }
        });
    }
}

function testCorreo() {
    var correo = $("#correo-uso").val();
    var pass = $("#pass").val();
    var remitente = $("#remitente").val();
    var mailremitente = $("#correo-remitente").val();
    var host = $("#host-correo").val();
    var puerto = $("#puerto-acceso").val();
    var seguridad = $("#seguridad").val();

    if (isEmail(correo, "correo-uso") && isnEmpty(pass, "pass") && isnEmpty(remitente, "remitente") && isnEmpty(host, "host-correo") && isnEmpty(puerto, "puerto-acceso") && isnEmpty(seguridad, "seguridad")) {
        cargandoHide();
        cargandoShow();
        $.ajax({
            url: "com.sine.enlace/enlaceconfig.php",
            type: "POST",
            data: {transaccion: "testcorreo", correo: correo, pass: pass, remitente: remitente, mailremitente: mailremitente, host: host, puerto: puerto, seguridad: seguridad},
            success: function (datos) {
                var texto = datos.toString();
                var bandera = texto.substring(0, 1);
                var res = texto.substring(1, 1000);
                if (bandera == '0') {
                    cargandoHide();
                    alertify.error(res);
                } else {
                    cargandoHide();
                    //alert(datos);
                    alertify.success(res);
                }
            }
        });
    }
}

function cargarArchivoTabla() {
    var formData = new FormData(document.getElementById("form-tabla"));
    cargandoHide();
    cargandoShow();
    $.ajax({
        url: 'com.sine.enlace/cargarimg.php',
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (datos) {
            var array = datos.split("<corte>");
            $("#muestraimagen").html(array[0]);
            $("#filename").val(array[1]);
            $("#imagen").val('');
            cargandoHide();
        }
    });
}

function loadFormato() {
    var tabla = $("#tabla-datos").val();
    var data = "";
    if (tabla == '1') {
        data = "Formato del archivo:\n\
                <br/>21 Campos, sin titulos de tabla\n\
                <li>Nombre</li>\n\
                <li>Apellido Paterno</li>\n\
                <li>Apellido Materno</li>\n\
                <li>Empresa</li>\n\
                <li>Email Informacion (Opcional)</li>\n\
                <li>Email Facturacion</li>\n\
                <li>Email Gerencia (Opcional)</li>\n\
                <li>Telefono</li>\n\
                <li>Banco (Opcional)</li>\n\
                <li>Cuenta (Opcional)</li>\n\
                <li>Clabe (Opcional)</li>\n\
                <li>RFC</li>\n\
                <li>Razon Social</li>\n\
                <li>Regimen Cliente (Formato: (Clv regimen)-(Descripcion Fiscal))</li>\n\
                <li>Calle (Opcional)</li>\n\
                <li>Numero Interior (Opcional)</li>\n\
                <li>Numero Exterior</li>\n\
                <li>Localidad o Colonia (Opcional)</li>\n\
                <li>Estado</li>\n\
                <li>Municipio</li>\n\
                <li>Codigo Postal</li>\n\
                <br/>\n\
                <button class='button-form btn-success' onclick='descargarEjemplo(1)'>Descargar ejemplo <span class='glyphicon glyphicon-download'></span></button>";
    } else if (tabla == '2') {
        data = "Formato del archivo:\n\
                <br/>14 Campos, sin titulos de tabla\n\
                <li>Cod Producto</li>\n\
                <li>Nombre de Producto</li>\n\
                <li>Clave Fiscal de Unidad (Catalogo Sat)</li>\n\
                <li>Descripcion Fiscal de Unidad (Catalogo Sat)</li>\n\
                <li>Descripcion de Producto</li>\n\
                <li>Precio de compra</li>\n\
                <li>Porcentaje de Ganancia</li>\n\
                <li>Importe de Ganancia</li>\n\
                <li>Precio de Venta</li>\n\
                <li>Tipo de Producto (Producto = '1' / Servicio='2')</li>\n\
                <li>Clave Fiscal del Producto (Catalogo del SAT)</li>\n\
                <li>Descripcion Fiscal del Producto (Catalogo del SAT)</li>\n\
                <li>Activar Inventario? (Si = '1' / No = '0')</li>\n\
                <li>Cantidad Inventario</li>\n\
                <br/>\n\
                <button class='button-form btn-success' onclick='descargarEjemplo(2)'>Descargar ejemplo <span class='glyphicon glyphicon-download'></span></button>";
    }

    $("#data-tabla").html(data);
}

function loadArchivo() {
    var fnm = $("#filename").val();
    var tabla = $("#tabla-datos").val();
    cargandoHide();
    cargandoShow();
    $.ajax({
        url: "com.sine.enlace/enlaceconfig.php",
        type: "POST",
        data: {transaccion: "loadexcel", fnm: fnm, tabla: tabla},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                cargandoHide();
                alertify.error(res);
            } else {
                cargandoHide();
                alertify.success(res);
            }
        }
    });
}

function descargarEjemplo(id) {
    cargandoHide();
    cargandoShow();
    if (id == '1') {
        window.open('./temporal/Ejemplo_cliente.xlsx');
    } else if (id == '2') {
        window.open('./temporal/Ejemplo_producto.xlsx');
    }
    cargandoHide();
}

function hideCommon(){
    
    if( $('#id-encabezado').val() == 12 ){
        $('#common-ticket').show();
        $('#datos-empresa').show();
        $('#common-cuerpo').hide();
        $('#common-pie').hide();
        $('#common-subtitulos').hide();
        $('#common-table').hide();
        $('#config-gral').hide();
        $('#lbl-despedida').html('Mensaje Pie Ticket');
        $('#lbl-pagina').html('Inferior Izquierda');
        $('#lbl-correo').html('Inferior Derecha');
    }
    else {
        $('#common-ticket').hide();
        $('#datos-empresa').hide();
        $('#common-cuerpo').show();
        $('#common-pie').show();
        $('#common-subtitulos').show();
        $('#common-table').show();
        $('#config-gral').show();
        $('#lbl-despedida').html('Mensaje de Observaciones');
        $('#lbl-pagina').html('Pagina');
        $('#lbl-correo').html('Correo');
    }
}

function habilitarDatos() {
    if( $('#chk-data').is(':checked') ){//datos y logo

        $('#data-empressa').show('slow');
        $('#header-logo').show('slow');

    } else if( $('#chk-logo').is(':checked') ){//solo logo
        
        $('#data-empressa').hide('slow');
        $('#header-logo').show('slow');
        
    }else if ($('#chk-only-data').is(':checked')) {//solo datos
       
        $('#data-empressa').show('slow');
        $('#header-logo').toggle('slow');
    }

    visualizarPDF();
}

/*function verificaAnchoticket(){
    var width = $('#width-ticket').val();
    if( width >= 58){
        visualizarPDF();
    }else{
        alertify.error("El tamaño minimo del anchoh del ticket es de 58mm.");
        $('#width-ticket').val(58);
        visualizarPDF();
    }
}*/
function verificaAnchoticket() {
    var width = $('#width-ticket').val();
    if (width >= 58) {
        visualizarPDF();
    } else {
        alertify.error("El tamaño mínimo del ancho del ticket es de 58mm.");
        $('#width-ticket').val(58);
        visualizarPDF(); // Agregado: Llama a visualizarPDF incluso si hay un error
    }
}

   




