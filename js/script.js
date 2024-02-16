$(document).ready(function () {
    loadView('paginicio');
    tipoCambio();
    valPeriodoPrueba();
    getDiasHorario();
});




function getUserFirstSession() {
    $.ajax({
        url: "com.sine.enlace/enlaceinicio.php",
        type: "POST",
        data: {transaccion: "firstsession"},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);

            if (datos == '0') {
                $("#modal-alert").modal('show');
                $("#alert-body").html("<div class='text-justify'><p class='alert-title text-center'>Bienvenido a Q-ik</p> <p class='alert-title text-center'>Tu sistema de facturaci&oacute;n en la nube.</p> <p class='alert-text'>Para dar tus primeros pasos en el sistema recuerda dar de alta la siguiente informaci&oacute;n: </p> <ul class='alert-text' style='padding-left:50px;'><li>Datos de facturaci&oacute;n</li> <li>Datos de impuestos</li> <li>Folios de facturaci&oacute;n</li></ul> <p class='alert-text'>Si deseas saber como realizar estos pasos te invitamos a visitar nuestro canal de Youtube, donde encontraras tutoriales para los distintos m&oacute;dulos del sistema <a href='https://www.youtube.com/playlist?list=PL3Iwrxm9g7E0cq3fhRdshFEUcpwx44u1d' target='_blank'>Iniciar en Q-ik</a></p> <p class='alert-text'>Para recibir soporte t&eacute;cnico o resolver dudas puedes usar el m&oacute;dulo de soporte t&eacute;cnico en el men&uacute; del sistema.</p> <p class='alert-title text-center'>Gracias por tu preferencia</p></div>");
            }
        }
    });
}

function getDiasHorario() {
    var date = new Date();
    var sundayP = new Date(date.getFullYear(), 3, 1);
    while (sundayP.getDay() != 0) {
        var primer = formatDate(sundayP.setDate(sundayP.getDate() + 1));
    }
    
    var sundayU = new Date(date.getFullYear(), 9, 25);
    while (sundayU.getDay() != 0) {
        var ultimo = formatDate(sundayU.setDate(sundayU.getDate() + 1));
    }
    
    $.ajax({
        url: "com.sine.enlace/enlacefactura.php",
        type: "POST",
        data: {transaccion: "diashorario", primer:primer, ultimo:ultimo},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
            } else {
                
            }
        }
    });
}

function validarRFC() {
    cargandoHide();
    cargandoShow();
    VentanaCentrada('https://www.sat.gob.mx/aplicacion/operacion/79615/valida-en-linea-rfc%C2%B4s-uno-a-uno-o-de-manera-masiva-hasta-5-mil-registros', 'SAT', '', '1024', '768', 'true');
    cargandoHide();
}

function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) 
        month = '0' + month;
    if (day.length < 2) 
        day = '0' + day;

    return [year, month, day].join('-');
}

function translateMonth(m) {
    var mes = "";
    switch (m) {
        case '01':
            mes = "Ene";
            break;
        case "02":
            mes = "Feb";
            break;
        case "03":
            mes = "Mar";
            break;
        case "04":
            mes = "Abr";
            break;
        case "05":
            mes = "May";
            break;
        case "06":
            mes = "Jun";
            break;
        case "07":
            mes = 'Jul';
            break;
        case "08":
            mes = 'Ago';
            break;
        case "09":
            mes = 'Sep';
            break;
        case "10":
            mes = 'Oct';
            break;
        case "11":
            mes = 'Nov';
        case "12":
            mes = 'Dic';
        default:
            mes = '';
            break;
    }
    return mes;
}

function resetIndex() {
    if (window.innerWidth >= 700) {
        $('#body-index').removeClass('body-index2');
        $('#body-right').removeClass('body-right2');
    } else if (window.innerWidth < 700) {
        $('#body-index').addClass('body-index2');
        $('#body-right').addClass('body-right2');
    }
}

function resetMenu() {
    if (window.innerWidth >= 700) {
        $('#main-menu').removeClass('content-menu2');
        $('.elipse').removeClass('elipse2');
        document.getElementById('menu-icon').style.display = 'none';
        document.getElementById('user-name').style.display = '';
        $('.user-info').removeClass('user-info2');
        $('#contenedor-vista-right').addClass('left-pad');
    } else if (window.innerWidth < 700) {
        $('#main-menu').addClass('content-menu2');
        $('.elipse').addClass('elipse2');
        document.getElementById('menu-icon').style.display = 'block';
        document.getElementById('user-name').style.display = 'none';
        $('.user-info').addClass('user-info2');
        $('#contenedor-vista-right').removeClass('left-pad');
    }
}

function resetDiv() {
    if (window.innerWidth >= 700) {
        $("#div-space").addClass('div-space');
        $('.div-form').removeClass('div-form2');
    } else if (window.innerWidth < 700) {
        $("#div-space").removeClass('div-space');
        $('.div-form').addClass('div-form2');
    }
}

function resetFrame() {
    if (window.innerWidth >= 700) {
        $("#rfc-iframe").addClass('frame-rfc');
        $('#rfc-iframe').removeClass('rfc-phone');
    } else if (window.innerWidth < 700) {
        $("#rfc-iframe").removeClass('frame-rfc');
        $('#rfc-iframe').addClass('rfc-phone');
    }
}

function valPeriodoPrueba() {
    $.ajax({
        url: 'com.sine.enlace/enlaceinicio.php',
        type: 'POST',
        data: {transaccion: 'valperiodo'},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            if (bandera == 0) {
                $("#modal-alert").modal('show');
                $("#alert-body").html(res);
            } else {
                //alert(datos);
            }
            //cargandoHide();
        }
    });
}

function displayIMG(id) {
    $.ajax({
        url: "com.sine.imprimir/img.php",
        type: "POST",
        data: {img: id},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
                cargandoHide();
            } else {
                var array = datos.split("<type>");
                var t = array[0];
                var data = array[1];
                if (t == 'd') {
                    var newTab = window.open('com.sine.imprimir/img.php?doc=' + id);
                    newTab.document.body.innerHTML = data;
                } else {
                    var newTab = window.open();
                    newTab.document.body.innerHTML = data;
                }
            }
        }
    });
}

function cargarImgProducto() {
    var formData = new FormData(document.getElementById("form-producto"));
    var img = $("#imagen").val();
    if (isnEmpty(img, 'imagen')) {
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
                var view = array[0];
                var fn = array[1];
                $("#muestraimagen").html(view);
                $("#filename").val(fn);
                $("#imagen").val('');
                cargandoHide();
            }
        });
    }
}

function VentanaCentrada(theURL, winName, features, myWidth, myHeight, isCenter) { //v3.0
    if (window.screen)
        if (isCenter)
            if (isCenter == "true") {
                var myLeft = (screen.width - myWidth) / 2;
                var myTop = (screen.height - myHeight) / 2;
                features += (features != '') ? ',' : '';
                features += ',left=' + myLeft + ',top=' + myTop;
            }
    window.open(theURL, winName, features + ((features != '') ? ',' : '') + 'width=' + myWidth + ',height=' + myHeight);
}

function getNotification(id) {
    $.ajax({
        url: 'com.sine.enlace/enlaceinicio.php',
        type: 'POST',
        data: {transaccion: 'getnotification', id: id},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            if (bandera == 0) {
                alertify.error("Error al cargar la notificacion")
            } else {
                setValoresNotification(datos);
            }
            //cargandoHide();
        }
    });
}

function setValoresNotification(datos) {
    var array = datos.split("</tr>");
    var idnot = array[0];
    var fecha = array[1];
    var hora = array[2];
    var notification = array[3];
    var readed = array[4];
    var arrayF = fecha.split("-");
    var mes = "";
    switch (arrayF[1]) {
        case "01":
            mes = "Ene";
            break;
        case "02":
            mes = "Feb";
            break;
        case "03":
            mes = "Mar";
            break;
        case "04":
            mes = "Abr";
            break;
        case "05":
            mes = "May";
            break;
        case "06":
            mes = "Jun";
            break;
        case "07":
            mes = "Jul";
            break;
        case "08":
            mes = "Ago";
            break;
        case "09":
            mes = "Sep";
            break;
        case "10":
            mes = "Oct";
            break;
        case "11":
            mes = "Nov";
        case "12":
            mes = "Dic";
        default:
            break;
    }

    var fechanot = arrayF[2] + "/" + mes + "/" + arrayF[0];

    $("#notification-date").html(fechanot + " " + hora);
    $("#notification-body").html(notification);
    if (readed == '0') {
        updateNotificacion(idnot);
    }
}

function updateNotificacion(id) {
    $.ajax({
        url: 'com.sine.enlace/enlaceinicio.php',
        type: 'POST',
        data: {transaccion: 'updatenotification', id: id},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            if (bandera == 0) {
                alertify.error("Error al cargar la notificacion")
            } else {
                var array = datos.split("<corte>");
                var list = array[1];
                var count = array[2];
                $("#list-notificaciones").html(list);
                if (count > 0) {
                    $("#notification-alert").addClass("notification-marker-active");
                } else {
                    $("#notification-alert").removeClass("notification-marker-active");
                }
            }
            //cargandoHide();
        }
    });
}

function tipoCambio() {
    $.ajax({
        url: 'com.sine.enlace/enlacepago.php',
        type: 'POST',
        data: {transaccion: 'tipocambio'},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            if (bandera == 0) {
                alertify.error(res);
            } else {
                //alert(datos);
            }
            //cargandoHide();
        }
    });
}

$(function () {
    $(document).on('click', '.dropdown-menu li', function (event) {
        var $checkbox = $(this).find('.checkbox');
        var id = $(this).attr("data-id");
        var location = $(this).attr("data-location");

        if (!$checkbox.length) {
            return;
        }

        var $input = $checkbox.find('input');
        var $icon = $checkbox.find('span.glyphicon');
        if ($input.is(':checked')) {
            $input.prop('checked', false);
            $icon.removeClass('glyphicon-check').addClass('glyphicon-unchecked');
            if (location == 'lista') {
                calcularDescuento(id);
            } else if (location == 'tabla') {
                checkIVA(id);
            } else if (location == 'edit') {
                calcularDescuentoEditar();
            } else if (location == 'percepcion') {
                selectedPercepciones();
            } else if (location == 'deduccion') {
                selectedDeduccion();
            } else if (location == 'otrospagos') {
                selectedOtrosPagos();
            } else if (location == 'form') {
                calcularDescuentoObra();
            }
        } else {
            $input.prop('checked', true);
            $icon.removeClass('glyphicon-unchecked').addClass('glyphicon-check');
            if (location == 'lista') {
                calcularDescuento(id);
            } else if (location == 'tabla') {
                checkIVA(id);
            } else if (location == 'edit') {
                calcularDescuentoEditar();
            } else if (location == 'percepcion') {
                selectedPercepciones();
            } else if (location == 'deduccion') {
                selectedDeduccion();
            } else if (location == 'otrospagos') {
                selectedOtrosPagos();
            }else if (location == 'form') {
                calcularDescuentoObra();
            }
        }

        return false;
    });
});

function truncateTmpIMG() {
    $.ajax({
        url: "com.sine.enlace/enlaceimgs.php",
        type: "POST",
        data: {transaccion: "cancelar"},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
            } else {

            }
        }
    });
}

function truncateTmp() {
    $.ajax({
        url: "com.sine.enlace/enlacefactura.php",
        type: "POST",
        data: {transaccion: "cancelar"},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
            } else {

            }
        }
    });
}

function truncateTmpCot() {
    $.ajax({
        url: "com.sine.enlace/enlacecotizacion.php",
        type: "POST",
        data: {transaccion: "cancelar"},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
            } else {

            }
        }
    });
}

function truncateTmpCarta() {
    $.ajax({
        url: "com.sine.enlace/enlacecarta.php",
        type: "POST",
        data: {transaccion: "cancelar"},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
            } else {

            }
        }
    });
}

function loadOpcionesFolios(id = "" , serie = "", folio = "") {
    //cargandoShow();
    $.ajax({
        url: 'com.sine.enlace/enlaceopcion.php',
        type: 'POST',
        data: {transaccion: 'opcionesfolio', id:id, serie:serie, folio:folio},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            if (bandera == 0) {
                alertify.error(res);
            } else {
                $(".contenedor-folios").html(datos);
            }
            //cargandoHide();
        }
    });
}

function loadOpcionesImpuestos(t) {
    //cargandoShow();
    $.ajax({
        url: 'com.sine.enlace/enlaceopcion.php',
        type: 'POST',
        data: {transaccion: 'opcionesimpuestos', t: t},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            if (bandera == 0) {
                alertify.error(res);
            } else {
                if (t == '1') {
                    $("#traslados-option").html(datos);
                } else if (t == '2') {
                    $("#retencion-option").html(datos);
                }

            }
            //cargandoHide();
        }
    });
}

function loadOpcionesEstado(idestado = "") {
    //cargandoShow();
    $.ajax({
        url: 'com.sine.enlace/enlaceopcion.php',
        type: 'POST',
        data: {transaccion: 'opcionesestado', idestado:idestado},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            if (bandera == 0) {
                alertify.error(res);
            } else {
                $(".contenedor-estado").html(datos);
            }
            //cargandoHide();
        }
    });
}

function loadOpcionesMunicipio(idmun = "", idestado = "") {
    cargandoHide();
    cargandoShow();
    if(idestado == ''){
        idestado = $("#id-estado").val();
    }
    
    $.ajax({
        url: 'com.sine.enlace/enlaceopcion.php',
        type: 'POST',
        data: {transaccion: 'opcionesmunicipio', idestado: idestado, idmunicipio:idmun},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            if (bandera == 0) {
                alertify.error(res);
            } else {
                
                $(".contenedor-municipio").html(datos);
            }
            cargandoHide();
        }
    });
}

function loadOpcionesProveedor(idprov = "") {
    //cargandoShow();
    $.ajax({
        url: 'com.sine.enlace/enlaceopcion.php',
        type: 'POST',
        data: {transaccion: 'opcionesproveedor', idprov:idprov},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            if (bandera == 0) {
                alertify.error(res);
            } else {
                $(".contenedor-proveedores").html(datos);
            }
            //cargandoHide();
        }
    });
}

function loadOpcionesBancoCliente(idcliente) {
    //cargandoShow();
    $.ajax({
        url: 'com.sine.enlace/enlaceopcion.php',
        type: 'POST',
        data: {transaccion: 'opcionesbancocliente', idcliente: idcliente},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            if (bandera == 0) {
                alertify.error(res);
            } else {
                $(".contenedor-id-bancocuenta").html(datos);
            }
            //cargandoHide();
        }
    });
}

function loadOpcionesCBeneficiario(iddatos) {
    //cargandoShow();
    $.ajax({
        url: 'com.sine.enlace/enlaceopcion.php',
        type: 'POST',
        data: {transaccion: 'opcionesbeneficiario', iddatos: iddatos},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            if (bandera == 0) {
                alertify.error(res);
            } else {
                $(".contenedor-id-bancobeneficiario").html(datos);
            }
            //cargandoHide();
        }
    });
}

function loadOpcionesCliente() {
    //cargandoShow();
    $.ajax({
        url: 'com.sine.enlace/enlaceopcion.php',
        type: 'POST',
        data: {transaccion: 'opcionescliente'},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            if (bandera == 0) {
                alertify.error(res);

            } else {
                $(".contenedor-cliente").html(datos);
            }
            //cargandoHide();
        }
    });
}

function loadOpcionesFacturacion(id = "") {
    //cargandoShow();
    $.ajax({
        url: 'com.sine.enlace/enlaceopcion.php',
        type: 'POST',
        data: {transaccion: 'opcionesfacturacion', id:id},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            if (bandera == 0) {
                alertify.error(res);
            } else {
                $(".contenedor-datos").html(datos);
            }
            //cargandoHide();
        }
    });
}

function loadOpcionesMetodoPago(selected = "") {
    //cargandoShow();
    $.ajax({
        url: 'com.sine.enlace/enlaceopcion.php',
        type: 'POST',
        data: {transaccion: 'opcionesmpago', selected: selected},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            if (bandera == 0) {
                alertify.error(res);
            } else {
                $(".contenedor-metodo-pago").html(datos);
            }
            //cargandoHide();
        }
    });
}


function loadOpcionesFormaPago(selected = "") {
    //cargandoShow();
    $.ajax({
        url: 'com.sine.enlace/enlaceopcion.php',
        type: 'POST',
        data: {transaccion: 'opcionesformapago', selected:selected},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            if (bandera == 0) {
                alertify.error(res);
            } else {
                $(".contenedor-forma-pago").html(datos);
            }
            //cargandoHide();
        }
    });
}

function loadOpcionesFormaPago2(selected = "") {
    //cargandoShow();
    $.ajax({
        url: 'com.sine.enlace/enlaceopcion.php',
        type: 'POST',
        data: {transaccion: 'opcionesformapago2', selected:selected},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            if (bandera == 0) {
                alertify.error(res);
            } else {
                $(".contenedor-forma-pago").html(datos);
            }
            //cargandoHide();
        }
    });
}

function loadOpcionesMoneda(idmoneda = "") {
    //cargandoShow();
    $.ajax({
        url: 'com.sine.enlace/enlaceopcion.php',
        type: 'POST',
        data: {transaccion: 'opcionesmoneda', idmoneda:idmoneda},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            if (bandera == 0) {
                alertify.error(res);
            } else {
                $(".contenedor-moneda").html(datos);
            }
            //cargandoHide();
        }
    });
}

function loadOpcionesUsoCFDI(iduso = "") {
    //cargandoShow();
    $.ajax({
        url: 'com.sine.enlace/enlaceopcion.php',
        type: 'POST',
        data: {transaccion: 'opcionesusocfdi', iduso:iduso},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            if (bandera == 0) {
                alertify.error(res);
            } else {
                $(".contenedor-uso").html(datos);
            }
            //cargandoHide();
        }
    });
}

function loadOpcionesComprobante(id = "") {
    //cargandoShow();
    $.ajax({
        url: 'com.sine.enlace/enlaceopcion.php',
        type: 'POST',
        data: {transaccion: 'opcionescomprobante', id:id},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            if (bandera == 0) {
                alertify.error(res);
            } else {
                $(".contenedor-tipo-comprobante").html(datos);
            }
            //cargandoHide();
        }
    });
}

function loadOpcionesRegimen(idregimen = "") {
    //cargandoShow();
    $.ajax({
        url: 'com.sine.enlace/enlaceopcion.php',
        type: 'POST',
        data: {transaccion: 'opcionesregimen', idregimen:idregimen},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            if (bandera == 0) {
                alertify.error(res);
            } else {
                $(".contenedor-regimen").html(datos);
            }
            //cargandoHide();
        }
    });
}

function loadOpcionesPeriodicidad(idper = "") {
    //cargandoShow();
    $.ajax({
        url: 'com.sine.enlace/enlaceopcion.php',
        type: 'POST',
        data: {transaccion: 'opcionesperiodicidad', idper:idper},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            if (bandera == 0) {
                alertify.error(res);
            } else {
                $(".contenedor-periodo").html(datos);
            }
            //cargandoHide();
        }
    });
}

function loadOpcionesJornada(idjor) {
    //cargandoShow();
    $.ajax({
        url: 'com.sine.enlace/enlaceopcion.php',
        type: 'POST',
        data: {transaccion: 'opcionesjornada', idjor:idjor},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            if (bandera == 0) {
                alertify.error(res);
            } else {
                $(".contenedor-jornada").html(datos);
            }
            //cargandoHide();
        }
    });
}

function loadOpcionesContrato(idcontrato = "") {
    //cargandoShow();
    $.ajax({
        url: 'com.sine.enlace/enlaceopcion.php',
        type: 'POST',
        data: {transaccion: 'opcionescontrato', idcontrato:idcontrato},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            if (bandera == 0) {
                alertify.error(res);
            } else {
                $(".contenedor-contrato").html(datos);
            }
            //cargandoHide();
        }
    });
}

function loadOpcionesBanco(idbanco = "") {
    //cargandoShow();
    $.ajax({
        url: 'com.sine.enlace/enlaceopcion.php',
        type: 'POST',
        data: {transaccion: 'opcionesbanco', idbanco: idbanco},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            if (bandera == 0) {
                alertify.error(res);
            } else {
                $(".contenedor-banco").html(datos);
            }
            //cargandoHide();
        }
    });
}

function loadOpcionesTipoRelacion() {
    //cargandoShow();
    $.ajax({
        url: 'com.sine.enlace/enlaceopcion.php',
        type: 'POST',
        data: {transaccion: 'opcionesrelacion'},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            if (bandera == 0) {
                alertify.error(res);
            } else {
                $(".contenedor-relacion").html(datos);
            }
            //cargandoHide();
        }
    });
}

function addloadOpcionesBanco(a, idbanco = "") {
    //cargandoShow();
    $.ajax({
        url: 'com.sine.enlace/enlaceopcion.php',
        type: 'POST',
        data: {transaccion: 'addopcionesbanco', a: a, idbanco:idbanco},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            if (bandera == 0) {
                alertify.error(res);
            } else {
                $(".contenedor-banco" + a).html(datos);
            }
            //cargandoHide();
        }
    });
}

function loadOpcionesRiesgo(idriesgo = "") {
    //cargandoShow();
    $.ajax({
        url: 'com.sine.enlace/enlaceopcion.php',
        type: 'POST',
        data: {transaccion: 'opcionesriesgo', idriesgo:idriesgo},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            if (bandera == 0) {
                alertify.error(res);
            } else {
                $(".contenedor-riesgo").html(datos);
            }
            //cargandoHide();
        }
    });
}

function loadOpcionesVendedor() {
    $.ajax({
        url: 'com.sine.enlace/enlaceopcion.php',
        type: 'POST',
        data: {transaccion: 'opcionesvendedor'},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            if (bandera == 0) {
                alertify.error(res);
            } else {
                $(".contenedor-vendedor").html(datos);
            }
            //cargandoHide();
        }
    });
}

function loadopcionesAno() {
    //cargandoShow();
    $.ajax({
        url: 'com.sine.enlace/enlaceopcion.php',
        type: 'POST',
        data: {transaccion: 'opcionesano'},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            if (bandera == 0) {
                alertify.error(res);
            } else {
                $(".contenedor-ano").html(datos);
            }
            //cargandoHide();
        }
    });
}

function loadOpcionesUsuario() {
    $.ajax({
        url: 'com.sine.enlace/enlaceopcion.php',
        type: 'POST',
        data: {transaccion: 'opcionesusuario'},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            if (bandera == 0) {
                alertify.error(res);
            } else {
                $(".contenedor-usuarios").html(datos);
            }
            //cargandoHide();
        }
    });
}

function opcionesCorreoList() {
    $.ajax({
        url: 'com.sine.enlace/enlaceopcion.php',
        type: 'POST',
        data: {transaccion: 'correolist'},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            if (bandera == 0) {
                alertify.error(res);
            } else {
                $(".contenedor-correos").html(datos);
            }
            //cargandoHide();
        }
    });
}

function opcionesMotivoCancelar() {
    $.ajax({
        url: 'com.sine.enlace/enlaceopcion.php',
        type: 'POST',
        data: {transaccion: 'opcionesmotivo'},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            if (bandera == 0) {
                alertify.error(res);
            } else {
                $(".contenedor-motivos").html(datos);
            }
            //cargandoHide();
        }
    });
}

function opcionesPeriodoGlobal(id = "") {
    $.ajax({
        url: 'com.sine.enlace/enlaceopcion.php',
        type: 'POST',
        data: {transaccion: 'periodoglobal', id:id},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            if (bandera == 0) {
                alertify.error(datos);
            } else {
                $(".contenedor-pglobal").html(datos);
            }
            //cargandoHide();
        }
    });
}

function opcionesMeses(id = "") {
    $.ajax({
        url: 'com.sine.enlace/enlaceopcion.php',
        type: 'POST',
        data: {transaccion: 'opcionesmeses', id:id},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            if (bandera == 0) {
                alertify.error(res);
            } else {
                $(".contenedor-meses").html(datos);
            }
            //cargandoHide();
        }
    });
}

function opcionesAnoGlobal() {
    //cargandoShow();
    $.ajax({
        url: 'com.sine.enlace/enlaceopcion.php',
        type: 'POST',
        data: {transaccion: 'anoglobal'},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            if (bandera == 0) {
                alertify.error(res);
            } else {
                $(".contenedor-ano").html(datos);
            }
            //cargandoHide();
        }
    });
}

function btnCargando(id) {
    $("#" + id).attr("disabled", "true");
    $("#" + id).text("Guardando...");
}

function btnGuardar(id) {
    $("#" + id).removeAttr("disabled");
    $("#" + id).text("Guardar");
}

function menorFecha(Fecha, id) {
    var date = new Date();
    var array_fecha = date.toString().split(" ", 10);
    var array_fecha2 = Fecha.toString().split("-", 10);
    var mes = array_fecha2[1], ano = array_fecha2[0], dia = array_fecha2[2];
    var mesh = 0, anoh = array_fecha[3], diah = array_fecha[2];
    switch (array_fecha[1]) {
        case "Jan":
            mesh = 01;
            break;
        case "Feb":
            mesh = 02;
            break;
        case "Mar":
            mesh = 03;
            break;
        case "Apr":
            mesh = 04;
            break;
        case "May":
            mesh = 05;
            break;
        case "Jun":
            mesh = 06;
            break;
        case "Jul":
            mesh = 07;
            break;
        case "Aug":
            mesh = 08;
            break;
        case "Sep":
            mesh = 09;
            break;
        case "Oct":
            mesh = 10;
            break;
        case "Nov":
            mesh = 11;
        case "Dec":
            mesh = 12;
        default:
            break;
    }
    if (ano >= anoh) {
        if (ano > anoh) {
            $("#" + id).css("border-color", "green");
            $("#" + id + "-errors").text("");
            return true;
        } else {
            if (mes >= mesh) {
                if (mes == mesh) {
                    if (dia > diah) {
                        $("#" + id).css("border-color", "green");
                        $("#" + id + "-errors").text("");
                        return true;
                    } else {
                        $("#" + id).css("border-color", "red");
                        $("#" + id + "-errors").text("La fecha de caducidad no puede ser anterior a hoy");
                        $("#" + id + "-errors").css("color", "red");
                        $("#" + id).focus();
                        return false;
                    }
                } else {
                    $("#" + id).css("border-color", "green");
                    $("#" + id + "-errors").text("");
                    return true;
                }
            } else {
                $("#" + id).css("border-color", "red");
                $("#" + id + "-errors").text("La fecha de caducidad no puede ser anterior a hoy");
                $("#" + id + "-errors").css("color", "red");
                $("#" + id).focus();
                return false;
            }
        }
    } else {
        $("#" + id).css("border-color", "red");
        $("#" + id + "-errors").text("La fecha de caducidad no puede ser anterior a hoy");
        $("#" + id + "-errors").css("color", "red");
        $("#" + id).focus();
        return false;
    }
}

function mayorFecha(Fecha, id) {
    var date = new Date();
    var array_fecha = date.toString().split(" ", 10);
    var array_fecha1 = Fecha.toString().split("-", 10);
    var mes = array_fecha1[1], ano = array_fecha1[0], dia = array_fecha1[2];
    var mesh = 0, anoh = array_fecha[3], diah = array_fecha[2];
    switch (array_fecha[1]) {
        case "Jan":
            mesh = 01;
            break;
        case "Feb":
            mesh = 02;
            break;
        case "Mar":
            mesh = 03;
            break;
        case "Apr":
            mesh = 04;
            break;
        case "May":
            mesh = 05;
            break;
        case "Jun":
            mesh = 06;
            break;
        case "Jul":
            mesh = 07;
            break;
        case "Aug":
            mesh = 08;
            break;
        case "Sep":
            mesh = 09;
            break;
        case "Oct":
            mesh = 10;
            break;
        case "Nov":
            mesh = 11;
        case "Dec":
            mesh = 12;
        default:
            break;
    }
    if (ano <= anoh) {
        if (ano < anoh) {
            $("#" + id).css("border-color", "green");
            $("#" + id + "-errors").text("");
            return true;
        } else {
            if (mes <= mesh) {
                if (mes == mesh) {
                    if (dia <= diah) {
                        $("#" + id).css("border-color", "green");
                        $("#" + id + "-errors").text("");
                        return true;
                    } else {
                        $("#" + id).css("border-color", "red");
                        $("#" + id + "-errors").text("La fecha de compra no puede ser mayor a hoy");
                        $("#" + id + "-errors").css("color", "red");
                        $("#" + id).focus();
                        return false;
                    }

                } else {
                    $("#" + id).css("border-color", "green");
                    $("#" + id + "-errors").text("");
                    return true;
                }
            } else {
                $("#" + id).css("border-color", "red");
                $("#" + id + "-errors").text("La fecha de compra no puede ser mayor a hoy");
                $("#" + id + "-errors").css("color", "red");
                $("#" + id).focus();
                return false;
            }
        }

    } else {
        $("#" + id).css("border-color", "red");
        $("#" + id + "-errors").text("La fecha de compra no puede ser mayor a hoy");
        $("#" + id + "-errors").css("color", "red");
        $("#" + id).focus();
        return false;
    }

}

function playCancelar(id) {
    $("#" + id).append("<audio src='sounds/RyuKO.mp3' autoplay='true'/>");
}

function cargandoShow() {
    $("body").append("<div id='contenedor-loader'></div>");
}

function cargandoHide() {
    $("#contenedor-loader").remove();
}

function dateEmpty(val, id) {
    if (val == "") {
        $("." + id).css("border-color", "red");
        $("#" + id + "-errors").text("Este campo no puede estar vacio");
        $("#" + id + "-errors").css("color", "red");
        return false;
    } else {
        $("#" + id + "-errors").text("");
        $("." + id).css("border-color", "green");
        return true;
    }
}

function sumarDias(fecha, dias) {
    fecha.setDate(fecha.getDate() + dias);
    return fecha;
}

function isListUnit(val, id) {
    expr = /^([a-zA-Z0-9 :+-.,#"$%&/()\[\]=;áéíóúÁÉÍÓÚñÑ])+\-(([a-zA-Z0-9 :+-.,#"$%&/()\[\]=;áéíóúÁÉÍÓÚñÑ]))/;
    if (!expr.test(val)) {
        $("#" + id).css("border-color", "red");
        $("#" + id + "-errors").text("Debes Seleccionar un elemento de la lista");
        $("#" + id + "-errors").css("color", "red");
        $("#" + id).focus();
        return false;
    } else {
        $("#" + id + "-errors").text("");
        $("#" + id).css("border-color", "green");
        return true;
    }
}

function isList(val, id) {
    expr = /^([a-zA-Z0-9 :+-.,#"$%&/()\[\]=;áéíóúÁÉÍÓÚñÑ])+\-(([a-zA-Z0-9 :+-.,#"$%&/()\[\]=;áéíóúÁÉÍÓÚñÑ]))/;
    if (!expr.test(val)) {
        $("#" + id).css("border-color", "red");
        $("#" + id + "-errors").text("Debes Seleccionar un elemento de la lista");
        $("#" + id + "-errors").css("color", "red");
        $("#" + id).focus();
        return false;
    } else {
        $("#" + id + "-errors").text("");
        $("#" + id).css("border-color", "green");
        return true;
    }
}

function isnEmpty(val, id) {
    if (val == "") {
        $("#" + id).css("border-color", "red");
        $("#" + id + "-errors").text("Este campo no puede estar vacio");
        $("#" + id + "-errors").css("color", "red");
        $("#" + id).focus();
        return false;
    } else {
        $("#" + id + "-errors").text("");
        $("#" + id).css("border-color", "green");
        return true;
    }
}

//VALIDACIONES ESPECIFICAS+++++++++++++++++++++++++++++++++

    function validarCodigoProducto(codigo, id) {
        // Expresión regular que acepta letras (mayúsculas y minúsculas), números y espacios
        var regex = /^[a-zA-Z0-9\s]*$/;
        // Verificar si el campo está vacío
        if (codigo.trim() === "") {
            $("#" + id).css("border-color", "red");
            $("#" + id + "-errors").text("Este campo no puede estar vacío");
            $("#" + id + "-errors").css("color", "red");
            $("#" + id).focus();
            return false;
        } else if (!regex.test(codigo)) { // Verificar si el campo contiene caracteres no permitidos
            // Filtrar caracteres no permitidos y actualizar el valor del campo
            $("#" + id).val(codigo.replace(/[^a-zA-Z0-9\s]/g, ''));
            return false;
        } else {
            // Limpiar mensajes de error y establecer el borde del campo en verde
            $("#" + id + "-errors").text("");
            $("#" + id).css("border-color", "green");
            return true;
        }
    }

 








function validarRFC(rfc, id) {
    var regexRFC = /^[a-zA-Z]{3,4}\d{6}[a-zA-Z\d]{3}$/;
    rfc = rfc.toUpperCase();
    var rfcInput = $("#rfc");
rfcInput.val(rfcInput.val().toUpperCase());
    if (!regexRFC.test(rfc)) {
        $("#" + id).css("border-color", "red");
        $("#" + id + "-errors").text("RFC no válido");
        $("#" + id + "-errors").css("color", "red");
        $("#" + id).focus();
        return false;
    } else {
        $("#" + id + "-errors").text("");
        $("#" + id).css("border-color", "green");
        return true;
    }
         
}

function validEmail(correo, id) {
    var regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]{1,2}$/;

    if (!regexCorreo.test(correo)) {
        $("#" + id).css("border-color", "red");
        $("#" + id + "-errors").text("Correo electrónico no válido");
        $("#" + id + "-errors").css("color", "red");
        $("#" + id).focus();
        return false;
    } else {
        $("#" + id + "-errors").text("");
        $("#" + id).css("border-color", "green");
        return true;
    }
}

function valTel(telefono, id) {
    var regexTelefono = /^\d{10}$/;
    if (!regexTelefono.test(telefono)) {
        $("#" + id).css("border-color", "red");
        $("#" + id + "-errors").text("Número de teléfono no válido");
        $("#" + id + "-errors").css("color", "red");
        $("#" + id).focus();
        return false;
    } else {
        $("#" + id + "-errors").text("");
        $("#" + id).css("border-color", "green");
        return true;
    }
}

function valCue(cuenta, id) {
    var regexCuentaBancaria = /^\d{8,12}$/;

    if (!regexCuentaBancaria.test(cuenta)) {
        $("#" + id).css("border-color", "red");
        $("#" + id + "-errors").text("Cuenta bancaria no válida");
        $("#" + id + "-errors").css("color", "red");
        $("#" + id).focus();
        return false;
    } else {
        $("#" + id + "-errors").text("");
        $("#" + id).css("border-color", "green");
        return true;
    }
}

function valClab(clabe, id) {
    var regexClabe = /^\d{18}$/;
    if (!regexClabe.test(clabe)) {
        $("#" + id).css("border-color", "red");
        $("#" + id + "-errors").text("CLABE no válida");
        $("#" + id + "-errors").css("color", "red");
        $("#" + id).focus();
        return false;
    } else {
        $("#" + id + "-errors").text("");
        $("#" + id).css("border-color", "green");
        return true;
    }
}

function isValidPercentage(val, id) {
    // Verificar si el valor no está vacío
    if (val.trim() === "") {
        showValidationError(id, "Este campo no puede estar vacío");
        return false;
    }

    // Verificar si el valor es un número
    var parsedValue = parseFloat(val);
    if (isNaN(parsedValue)) {
        showValidationError(id, "Porcentaje inválido. Ingrese un número.");
        return false;
    }

    // Verificar si el número está en el rango de 0 a 100
    if (parsedValue < 0 || parsedValue > 100) {
        showValidationError(id, "Porcentaje inválido. Debe estar entre 0 y 100.");
        return false;
    }

    // Si todas las verificaciones pasan, el porcentaje es válido
    clearValidationError(id);
    return true;
}

function showValidationError(id, message) {
    $("#" + id).css("border-color", "red");
    $("#" + id + "-errors").text(message);
    $("#" + id + "-errors").css("color", "red");
    $("#" + id).focus();
}

function clearValidationError(id) {
    $("#" + id).css("border-color", "green");
    $("#" + id + "-errors").text("");
}


//++++++++++++++++++++++++++++++++++++++++++++++++++++


function isnEmptyImg(val, id, img) {
    if (val == "") {
        $("#" + id).css("border-color", "red");
        $("#" + id + "-errors").text("Este campo no puede estar vacio");
        $("#" + id + "-errors").css("color", "red");
        $("#" + id).focus();
        $("#"+img).val('');
        return false;
    } else {
        $("#" + id + "-errors").text("");
        $("#" + id).css("border-color", "green");
        return true;
    }
}

function isnRango(val, min, max, id) {
    if (val < min || val > max) {
        $("#" + id).css("border-color", "red");
        $("#" + id + "-errors").text("El valor esta fuera del rango permitido");
        $("#" + id + "-errors").css("color", "red");
        $("#" + id).focus();
        return false;
    } else {
        $("#" + id + "-errors").text("");
        $("#" + id).css("border-color", "green");
        return true;
    }
}

function isnList(val, id) {
    if (val == "" || val == "Ninguno") {
        $("#" + id).css("border-color", "red");
        $("#" + id + "-errors").text("Selecciona un elemento de la lista");
        $("#" + id + "-errors").css("color", "red");
        $("#" + id).focus();
        return false;
    } else {
        $("#" + id + "-errors").text("");
        $("#" + id).css("border-color", "green");
        return true;
    }
}

function isValDay(val, id) {
    if (val != "") {
        if (!isNaN(val)) {
            if (val >= 32 || val <= 0) {
                $("#" + id).css("border-color", "red");
                $("#" + id + "-errors").text("El dia seleccionado esta fuera de rango");
                $("#" + id + "-errors").css("color", "red");
                $("#" + id).focus();
                return false;
            } else {
                $("#" + id + "-errors").text("");
                $("#" + id).css("border-color", "green");
                return true;
            }
        } else {
            $("#" + id).css("border-color", "red");
            $("#" + id + "-errors").text("Este campo debe contener solo numeros");
            $("#" + id + "-errors").css("color", "red");
            $("#" + id).focus();
            return false;
        }
    } else {
        $("#" + id).css("border-color", "red");
        $("#" + id + "-errors").text("Este campo no puede estar vacio");
        $("#" + id + "-errors").css("color", "red");
        $("#" + id).focus();
        return false;
    }
}

function isnZero(val, id) {
    if (val != "") {
        if (!isNaN(val)) {
            if (val > 0) {
                $("#" + id + "-errors").text("");
                $("#" + id).css("border-color", "green");
                return true;
            } else {
                $("#" + id).css("border-color", "red");
                $("#" + id + "-errors").text("El numero debe ser mayor a cero");
                $("#" + id + "-errors").css("color", "red");
                $("#" + id).focus();
                return false;
            }
        } else {
            $("#" + id).css("border-color", "red");
            $("#" + id + "-errors").text("Este campo debe contener solo numeros");
            $("#" + id + "-errors").css("color", "red");
            $("#" + id).focus();
            return false;
        }
    } else {
        $("#" + id).css("border-color", "red");
        $("#" + id + "-errors").text("Este campo no puede estar vacio");
        $("#" + id + "-errors").css("color", "red");
        $("#" + id).focus();
        return false;
    }
}

function isCheckedMailSend(ch1, ch2, ch3, ch4, ch5, ch6) {
    if (ch1 == 0 && ch2 == 0 && ch3 == 0 && ch4 == 0 && ch5 == 0 && ch6 == 0) {
        alertify.error('Debes seleccionar por lo menos un correo eletronico para el envio');
        return false;
    } else {
        return true;
    }
}

function isCheckedMail(ch1, ch2, ch3, ch4, ch5, ch6, id) {
    if (ch1 == 0 && ch2 == 0 && ch3 == 0 && ch4 == 0 && ch5 == 0 && ch6 == 0) {
        $("#" + id).css("border-color", "red");
        $("#" + id + "-errors").text("Debes seleccionar al menos un correo de destino");
        $("#" + id + "-errors").css("color", "red");
        $("#" + id).focus();
        return false;
    } else {
        $("#" + id + "-errors").text("");
        $("#" + id).css("border-color", "green");
        return true;
    }
}

function isPositive(val, id) {
    if (val != "") {
        if (!isNaN(val)) {
            if (val >= 0) {
                $("#" + id + "-errors").text("");
                $("#" + id).css("border-color", "green");
                return true;
            } else {
                $("#" + id).css("border-color", "red");
                $("#" + id + "-errors").text("El numero debe ser positivo");
                $("#" + id + "-errors").css("color", "red");
                $("#" + id).focus();
                return false;
            }
        } else {
            $("#" + id).css("border-color", "red");
            $("#" + id + "-errors").text("Este campo debe contener solo numeros");
            $("#" + id + "-errors").css("color", "red");
            $("#" + id).focus();
            return false;
        }
    } else {
        $("#" + id).css("border-color", "red");
        $("#" + id + "-errors").text("Este campo no puede estar vacio");
        $("#" + id + "-errors").css("color", "red");
        $("#" + id).focus();
        return false;
    }
}

function isUM(val, id) {
    if (val != " u/m ") {
        $("#" + "-errors").text("");
        $("#" + id).css("border-color", "green");
        $("#unidadmedida").css("border-color", "green");
        return true;
    } else {
        $("#" + id).css("border-color", "red");
        $("#" + id + "-errors").text("Elige una unidad de medida");
        $("#" + id + "-errors").css("color", "red");
        $("#" + id).focus();
        $("#unidadmedida").css("border-color", "red");
        return false;
    }
}

function isNumber(val, id) {
    if (!isNaN(val)) {
        if (val >= 0) {
            $("#" + id + "-errors").text("");
            $("#" + id).css("border-color", "green");
            return true;
        } else {
            $("#" + id).css("border-color", "red");
            $("#" + id + "-errors").text("El numero no puede ser menor a 0");
            $("#" + id + "-errors").css("color", "red");
            $("#" + id).focus();
            return false;
        }
    } else {
        $("#" + id).css("border-color", "red");
        $("#" + id + "-errors").text("Este campo debe contener solo numeros");
        $("#" + id + "-errors").css("color", "red");
        $("#" + id).focus();
        return false;
    }
}




function isEmail(val, id) {
    expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (!expr.test(val)) {
        $("#" + id).css("border-color", "red");
        $("#" + id + "-errors").text("La direccion de correo (Email) no es valida");
        $("#" + id + "-errors").css("color", "red");
        $("#" + id).focus();
        return false;
    } else {
        $("#" + id + "-errors").text("");
        $("#" + id).css("border-color", "green");
        return true;
    }
}

function isEmailtoSend(val, id) {
    expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (!expr.test(val)) {
        $("#" + id).css("border-color", "red");
        $("#" + id + "-errors").text("La direccion de correo (Email) no es valida");
        $("#" + id + "-errors").css("color", "red");
        $("#" + id).focus();
        return false;
    } else {
        $("#" + id + "-errors").text("");
        $("#" + id).css("border-color", "#D6D6DF");
        return true;
    }
}

function isPhoneNumber(val, id) {
    if (!isNaN(val)) {
        var n = val.toString();
        if (n.length > 6 && n.length < 11) {
            $("#" + id + "-errors").text("");
            $("#" + id).css("border-color", "green");
            return true;
        } else {
            $("#" + id).css("border-color", "red");
            $("#" + id + "-errors").text("Los numeros de telefono deben tener entre 7 y 10 digitos");
            $("#" + id + "-errors").css("color", "red");
            $("#" + id).focus();
            return false;
        }
    } else {
        $("#" + id).css("border-color", "red");
        $("#" + id + "-errors").text("Debes escribir solo numeros");
        $("#" + id + "-errors").css("color", "red");
        $("#" + id).focus();
        return false;
    }
}

function changeActiveClass(elementoActual, elementoNuevo) {
    $("#" + elementoActual).removeClass("active");
    $("#" + elementoNuevo).addClass("active");
    $("#contenedor-enlace-" + elementoActual).removeClass("active");
    $("#contenedor-enlace-" + elementoNuevo).addClass("active");
}

function changeText(elemento, texto) {
    $(elemento).html(texto);
}

function isNoError(datos) {
    var texto = datos.toString();
    var bandera = texto.substring(0, 1);
    var res = texto.substring(1, 5000);
    if (bandera == 0) {
        alertify.error(res);
        return false;
    } else {
        var tipo = texto.substring(10, 16);
        if (tipo == 'Notice') {
            alertify.error("Notice: ah ocurrido  un error");
            return false;
        } else {
            if (tipo == 'Warnin') {
                alertify.error("Warning: ah ocurrido  un error");
                return false;
            } else {
                return true;
            }
        }
    }
}

function changeActiveAcordion(id) {
    $('.social #accordion .panel a .panel-heading').removeClass("sub-menu-active");
    $('.social #accordion .panel a .panel-heading').removeClass("in");
    $("#" + id).addClass("sub-menu-active");
}

$(function () {
    $("body").click(function (e) {
        if (window.innerWidth < 700) {
            if (e.target.id == "contenedor-vista-right" || $(e.target).parents("#contenedor-vista-right").length) {
                $('#main-menu').addClass('content-menu2');
                $('.elipse').addClass('elipse2');
            }
        }

    });
});

$(function () {
    $(".list-conf").click(function () {
        var submenu = $(this).attr("data-submenu");
        $('.list-element').removeClass("menu-active");
        $('.marker').removeClass("marker-active");
        $('.panel-collapse').removeClass("in");
        $('.lista-submenu-elemento').removeClass("sub-active");
        if (submenu != "") {
            loadView(submenu);
        }
    });
});

$(function () {
    $(".list-menu").click(function () {
        var submenu = $(this).attr("data-submenu");
        if (submenu != "") {
            loadView(submenu);
        }
    });
});

$(function () {
    $(".show-menu").click(function () {
        if ($('#main-menu').hasClass('content-menu2')) {
            $('#main-menu').removeClass('content-menu2');
            $('.elipse').removeClass('elipse2');
        } else {
            $('#main-menu').addClass('content-menu2');
            $('.elipse').addClass('elipse2');
        }
    });
});

$(function () {
    $(".list-element").click(function () {
        $('.list-element').removeClass("menu-active");
        $('.marker').removeClass("marker-active");
        if ($(this).hasClass("list-menu")) {
            $('.panel-collapse').removeClass("in");
            $('.lista-submenu-elemento').removeClass("sub-active");
        }
        $(this).addClass("menu-active");
        $(this).children('div.marker').addClass("marker-active");
    });
});

$(function () {
    $(".lista-submenu-elemento").click(function () {
        $('.lista-submenu-elemento').removeClass("sub-active");
        $(this).addClass("sub-active");
    });
});

function getView(view) {
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
                $("#contenedor-vista-right").html('');
                $("#contenedor-vista-right").html(datos);
                //cargandoHide();
            }
        }
    });
}

$(function () {
    $("#id-estado").on("change", function () {
        loadOpcionesMunicipio();
    });
});

function loadImgPerfil(id) {
    cargandoHide();
    cargandoShow();
    $.ajax({
        url: "com.sine.enlace/enlaceusuario.php",
        type: "POST",
        data: {transaccion: "editarusuario", idusuario: id},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
                cargandoHide();
            } else {
                setImgUsuario(datos);
            }
        }
    });
}

function setImgUsuario(datos) {
    var array = datos.split("</tr>");
    var idusuario = array[0];
    var idlogin = array[11];
    var tipologin = array[12];
    var imgnm = array[13];
    var img = array[14];

    if (imgnm !== '') {
        $("#profimg").html(img);
        $("#fileuser").val(imgnm);
    }

    $("#form-profile").append("<input type='hidden' id='profactualizar' name='profactualizar' value='" + imgnm + "'/>")
    $("#btn-edit-profile").attr("onclick", "editarPerfil(" + idusuario + ");");
    $("#btn-form-profile").attr("onclick", "actualizarImgPerfil(" + idusuario + ");");
    cargandoHide();
}

function actualizarImgPerfil(idusuario) {
    var img = $("#fileuser").val();
    var imgactualizar = $("#profactualizar").val();
    $.ajax({
        url: "com.sine.enlace/enlaceusuario.php",
        type: "POST",
        data: {transaccion: "actualizarimg", idusuario: idusuario, img: img, imgactualizar: imgactualizar},
        success: function (datos) {
            //alert(datos);
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
            } else {
                alertify.success('Se guardaron los datos correctamente ');
                location.href = 'home.php';
            }
        }
    });
}

function cargarImgPerfil() {
    var formData = new FormData(document.getElementById("form-profile"));
    var img = $("#imgprof").val();
    if (isnEmpty(img, 'imgprof')) {
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
                $("#profimg").html(view);
                $("#fileuser").val(fn);
                $("#imgprof").val('');
            }
        });
    }
}

function editarPerfil(idusuario) {
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
                window.setTimeout("setValoresEditarPerfil('" + datos + "')", 400);
            }
        }
    });
}

function setValoresEditarPerfil(datos) {
    // alert(datos);
    $("#modal-profile-img").modal('hide');
    $("#usuario").attr('disabled', true);
    $("#contrasena").attr('disabled', true);
    changeText("#contenedor-titulo-form-usuario", "Editar usuario");
    changeText("#btn-form-usuario", "Guardar cambios <span class='glyphicon glyphicon-save-file'></span></a>");
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

    if (imgnm !== '') {
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

function loadBtnCrear(view) {
    $.ajax({
        url: "com.sine.enlace/enlacepermiso.php",
        type: "POST",
        data: {transaccion: "loadbtn", view: view},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            $("#btn-crear").html(datos);
            cargandoHide();
        }
    });
}

function loadBtnConfig(view) {
    $.ajax({
        url: "com.sine.enlace/enlacepermiso.php",
        type: "POST",
        data: {transaccion: "loadbtn", view: view},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);

            var array = datos.split("</tr>");
            var folio = array[0];
            var comision = array[1];
            var encabezado = array[2];
            var correo = array[3];
            var importar = array[4];

            if (folio == '1') {
                $("#div-folio-conf").removeAttr('hidden');
                $("#btn-folio-conf").attr('onclick', "loadViewConfig('listafolio');");
            }
            if (comision == '1') {
                $("#div-comision-conf").removeAttr('hidden');
                $("#btn-comision-conf").attr('onclick', "loadViewConfig('comision');");
            }
            if (encabezado == '1') {
                $("#div-encabezado-conf").removeAttr('hidden');
                $("#btn-encabezado-conf").attr('onclick', "loadViewConfig('encabezado');");
            }
            if (correo == '1') {
                $("#div-correo-conf").removeAttr('hidden');
                $("#btn-correo-conf").attr('onclick', "loadViewConfig('correo');");
            }
            if(importar == '1'){
                $("#div-tablas").removeAttr('hidden');
                $("#btn-tablas").attr('onclick', "loadViewConfig('tablas');");
            }

            cargandoHide();
        }
    });
}

function loadView(vista) {
    switch (vista) {
        case 'paginicio':
            getView(vista);
    		window.setTimeout("getUserFirstSession()", 300);
            window.setTimeout("getSaldo()", 350);
            window.setTimeout("datosGrafica()", 400);
            window.setTimeout("loadopcionesAno()", 450);
            break;
        case 'notificacion':
            getView(vista);
            window.setTimeout("filtrarNotificaciones()", 350);
            break;
        case 'comprar':
            getView(vista);
            break;
        case 'nuevousuario':
            getView(vista);
            window.setTimeout("checkUsuario()", 350);
            window.setTimeout("truncateTmp()", 400);
            window.setTimeout("truncateTmpCot()", 400);
            window.setTimeout("loadOpcionesEstado()", 450);
            break;
        case 'listasuarioaltas':
            getView(vista);
            window.setTimeout("truncateTmp()", 300);
            window.setTimeout("truncateTmpCot()", 350);
            window.setTimeout("loadBtnCrear('usuario')", 370);
            window.setTimeout("filtrarUsuario()", 400);
            break;
        case 'asignarpermisos':
            getView(vista);
            window.setTimeout("truncateTmp()", 300);
            window.setTimeout("truncateTmpCot()", 350);
            break;
        case 'categoria':
            getView(vista);

            break;
        case 'listacategoria':
            getView(vista);
            window.setTimeout("loadBtnCrear('categoria')", 360);
            window.setTimeout("loadListaCategorias()", 500);
            break;
        case 'nuevoproducto':
            getView(vista);
            window.setTimeout("truncateTmp()", 300);
            window.setTimeout("truncateTmpCot()", 350);
            window.setTimeout("loadOpcionesProveedor()", 350);
            break;
        case 'listaproductoaltas':
            getView(vista);
            window.setTimeout("truncateTmp()", 300);
            window.setTimeout("truncateTmpCot()", 350);
            window.setTimeout("loadBtnCrear('producto')", 370);
            window.setTimeout("loadListaProductosaltas()", 400);
            break;
        case 'valrfc':
            getView(vista);
            break;
        case 'nuevocliente':
            getView(vista);
            //window.setTimeout("truncateTmpCot()", 350);
            //window.setTimeout("truncateTmp()", 400);
            //window.setTimeout("loadOpcionesEstado()", 420);
            //window.setTimeout("loadOpcionesBanco()", 450);
            break;
        case 'listaclientealtas':
            getView(vista);
            //window.setTimeout("truncateTmp()", 300);
            //window.setTimeout("truncateTmpCot()", 350);
            window.setTimeout("loadBtnCrear('cliente')", 370);
            window.setTimeout("loadListaClientes()", 400);
            break;
        case 'comunicado':
            getView(vista);
            window.setTimeout("truncateTmpIMG()", 300);
            window.setTimeout("loadFecha()", 350);
            window.setTimeout("loadOpcionesFacturacion()", 400);
            window.setTimeout("loadContactos()", 420);
            break;
        case 'listacomunicado':
            getView(vista);
            window.setTimeout("truncateTmpIMG()", 300);
            window.setTimeout("loadBtnCrear('comunicado')", 350);
            window.setTimeout("listaComunicados()", 400);
            break;
        case 'insertar':
            getView(vista);
            window.setTimeout("truncateTmp()", 350);
            window.setTimeout("truncateTmpCot()", 400);
            break;
        case 'cfdi':
            getView(vista);
            window.setTimeout("truncateTmp()", 400);

            break;
        case 'impuesto':
            getView(vista);

            break;
        case 'listaimpuesto':
            getView(vista);
            window.setTimeout("loadBtnCrear('impuesto')", 350);
            window.setTimeout("loadListaImpuesto()", 400);
            break;
        case 'datosempresa':
            getView(vista);
            window.setTimeout("firmaCanvas()", 400);
            window.setTimeout("loadOpcionesBanco()", 400);
            window.setTimeout("loadOpcionesEstado()", 500);
            break;
        case 'nuevocontrato':
            getView(vista);
            window.setTimeout("truncateTmpCot()", 300);
            window.setTimeout("loadOpcionesFolios()", 320);
            window.setTimeout("filtrarProductos()", 350);
            window.setTimeout("loadFecha()", 370);
            window.setTimeout("loadOpcionesFormaPago()", 400);
            window.setTimeout("loadOpcionesMetodoPago()", 420);
            window.setTimeout("loadOpcionesMoneda()", 450);
            window.setTimeout("loadOpcionesUsoCFDI()", 470);
            window.setTimeout("loadOpcionesFacturacion()", 500);
            window.setTimeout("loadOpcionesProveedor()", 520);
            break;
        case 'precio':
            getView(vista);
            window.setTimeout("truncateTmp()", 400);
            window.setTimeout("truncateTmpCot()", 450);
            break;
        case 'pago':
            getView(vista);
            window.setTimeout("loadFecha()", 300);
            window.setTimeout("cancelarPago2()", 320);
            window.setTimeout("loadOpcionesFolios('3')", 350);
            window.setTimeout("loadOpcionesMoneda()", 400);
            window.setTimeout("loadOpcionesFormaPago2()", 420);
            window.setTimeout("loadOpcionesFacturacion()", 500);
            break;
        case 'listapago':
            getView(vista);
            window.setTimeout("loadBtnCrear('pago')", 350);
            window.setTimeout("opcionesMotivoCancelar()", 380);
            window.setTimeout("loadListaPago()", 400);
            break;
        case 'factura':
            getView(vista);
            window.setTimeout("truncateTmp()", 300);
            window.setTimeout("loadOpcionesFacturacion()", 320);
            window.setTimeout("loadFecha()", 350);
            window.setTimeout("loadOpcionesFolios('1')", 370);
            window.setTimeout("filtrarProducto()", 400);
            window.setTimeout("loadOpcionesFormaPago()", 420);
            window.setTimeout("loadOpcionesMetodoPago()", 450);
            window.setTimeout("loadOpcionesMoneda()", 470);
            window.setTimeout("loadOpcionesUsoCFDI()", 500);
            window.setTimeout("loadOpcionesComprobante()", 520);
            window.setTimeout("loadOpcionesProveedor()", 550);
            window.setTimeout("loadOpcionesTipoRelacion()", 570);
            window.setTimeout("opcionesPeriodoGlobal()", 600);
            window.setTimeout("opcionesMeses()", 620);
            window.setTimeout("opcionesAnoGlobal()", 650);
            break;
        case 'listafactura':
            getView(vista);
            window.setTimeout("truncateTmp()", 300);
            window.setTimeout("truncateTmpCot()", 350);
            window.setTimeout("loadBtnCrear('factura')", 400);
            window.setTimeout("opcionesMotivoCancelar()", 420);
            window.setTimeout("filtrarFolio()", 450);
            break;
        case 'cotizacion':
            window.setTimeout("truncateTmpCot()", 300);
            window.setTimeout("loadOpcionesImpuestos('1')", 320);
            window.setTimeout("loadOpcionesImpuestos('2')", 340);
            window.setTimeout("loadOpcionesFolios('5')", 350);
            window.setTimeout("loadFecha()", 370);
            window.setTimeout("loadOpcionesFacturacion()", 400);
            window.setTimeout("loadOpcionesComprobante()", 420);
            window.setTimeout("loadOpcionesFormaPago()", 450);
            window.setTimeout("loadOpcionesMetodoPago()", 470);
            window.setTimeout("loadOpcionesMoneda()", 500);
            window.setTimeout("loadOpcionesUsoCFDI()", 520);
            window.setTimeout("filtrarProducto() ", 550);
            window.setTimeout("loadOpcionesProveedor()", 600);
            getView(vista);
            break;
        case 'listacotizacion':
            window.setTimeout("truncateTmp()", 300);
            window.setTimeout("truncateTmpCot()", 350);
            window.setTimeout("loadBtnCrear('cotizacion')", 360);
            window.setTimeout("filtrarCotizacion()", 400);
            getView(vista);
            break;
        case 'instalacion':
            window.setTimeout("truncateTmp()", 350);
            window.setTimeout("truncateTmpCot()", 400);
            window.setTimeout("loadFolio()", 430);
            window.setTimeout("loadDocumento()", 450);
            window.setTimeout("loadFecha()", 500);
            getView(vista);
            break;
        case 'listainstalacion':
            window.setTimeout("truncateTmp()", 350);
            window.setTimeout("truncateTmpCot()", 400);
            window.setTimeout("filtrarInstalacion() ", 500);
            getView(vista);
            break;
        case 'listacontratos':
            getView(vista);
            window.setTimeout("truncateTmp()", 300);
            window.setTimeout("truncateTmpCot()", 350);
            window.setTimeout("loadBtnCrear('contrato')", 370);
            window.setTimeout("filtrarContratos()", 400);
            break;
        case 'listaempresa':
            getView(vista);
            window.setTimeout("truncateTmp()", 300);
            window.setTimeout("truncateTmpCot()", 350);
            window.setTimeout("loadBtnCrear('datos')", 370);
            window.setTimeout("loadListaEmpresa()", 400);

            break;
        case 'listacfdi':
            getView(vista);

            window.setTimeout("truncateTmp()", 300);
            window.setTimeout("truncateTmpCot()", 350);
            window.setTimeout("loadListaCFDI()", 400);
            break;
        case 'nuevoproveedor':
            getView(vista);
            window.setTimeout("truncateTmp()", 300);
            window.setTimeout("truncateTmpCot()", 350);
            window.setTimeout("loadOpcionesBanco()", 400);
            break;
        case 'listaproveedor':
            getView(vista);
            window.setTimeout("truncateTmp()", 300);
            window.setTimeout("truncateTmpCot()", 350);
            window.setTimeout("loadBtnCrear('proveedor')", 370);
            window.setTimeout("loadListaProveedor()", 400);
            break;
        case 'forminventario':
            getView(vista);
            window.setTimeout("truncateTmp()", 300);
            window.setTimeout("truncateTmpCot()", 350);
            window.setTimeout("loadOpcionesProducto()", 400);
            break;
        case 'listainventario':
            getView(vista);
            window.setTimeout("truncateTmp()", 300);
            window.setTimeout("truncateTmpCot()", 350);
            window.setTimeout("loadListaInventario()", 400);
            break;
        case 'reportefactura':
            getView(vista);
            window.setTimeout("truncateTmp()", 300);
            window.setTimeout("truncateTmpCot()", 350);
            window.setTimeout("loadOpcionesCliente()", 400);
            window.setTimeout("loadOpcionesFacturacion()", 450);
            window.setTimeout("loadOpcionesMoneda()", 470);
            break;
        case 'reportepago':
            getView(vista);
            window.setTimeout("loadOpcionesCliente()", 400);
            window.setTimeout("loadOpcionesFacturacion()", 450);
            window.setTimeout("loadOpcionesMoneda()", 470);
            break;
        case 'reportegrafica':
            getView(vista);
            window.setTimeout("loadOpcionesFacturacion()", 350);
            window.setTimeout("loadopcionesAno()", 400);
            window.setTimeout("reporteGraficaActual()", 450);
            window.setTimeout("reporteGraficaAnterior()", 500);
            break;
        case 'reportesat':
            getView(vista);
            window.setTimeout("loadopcionesAno()", 400);
            window.setTimeout("loadOpcionesFacturacion()", 450);
            window.setTimeout("reporteBimestralActual()", 500);
            window.setTimeout("reporteBimestralAnterior()", 550);
            window.setTimeout("reporteBimestralAnterior2()", 600);
            break;
        case 'datosiva':
            getView(vista);
            window.setTimeout("loadopcionesAno()", 350);
            window.setTimeout("loadlistaIVA()", 400);
            window.setTimeout("loadOpcionesFacturacion()", 450);
            break;
        case 'reporteventas':
            getView(vista);
            window.setTimeout("truncateTmp()", 400);
            window.setTimeout("truncateTmpCot()", 450);
            window.setTimeout("loadOpcionesCliente()", 500);
            window.setTimeout("loadOpcionesFacturacion()", 500);
            window.setTimeout("loadOpcionesVendedor()", 500);
            break;
        case 'config':
            getView(vista);
            window.setTimeout("loadBtnConfig('config')", 350);
            break;
        case 'encabezado':
            getView(vista);
            break;
        case 'correo':
            getView(vista);
            window.setTimeout("opcionesCorreo()", 300);
            //window.setTimeout("loadMailConfig()", 400);
            break;
        case 'folio':
            getView(vista);
            break;
        case 'listafolio':
            getView(vista);
            window.setTimeout("loadListaFolio()", 400);
            break;
        case 'comision':
            getView(vista);
            window.setTimeout("loadOpcionesUsuario()", 400);
            break;
        case 'listafiel':
            getView(vista);
            window.setTimeout("loadListaFiel()", 300);
            //window.setTimeout("loadBtnCrear('factura')", 410);
            break;
        case 'nuevafiel':
            getView(vista);
            //window.setTimeout("loadListaSolicitud()", 400);
            break;
        case 'listadescsolicitud':
            getView(vista);
            window.setTimeout("loadListaSolicitud()", 400);
            //window.setTimeout("loadBtnCrear('factura')", 410);
            break;
        case 'descsolicitud':
            getView(vista);
            //window.setTimeout("loadListaSolicitud()", 400);
            break;
        case 'empleado':
            getView(vista);
            window.setTimeout("loadOpcionesRegimen()", 300);
            window.setTimeout("loadOpcionesPeriodicidad()", 310);
            window.setTimeout("loadOpcionesJornada()", 320);
            window.setTimeout("loadOpcionesContrato()", 330);
            window.setTimeout("loadOpcionesEstado()", 330);
            window.setTimeout("loadOpcionesBanco()", 340);
            window.setTimeout("loadOpcionesRiesgo()", 350);
            break;
        case 'listaempleado':
            getView(vista);
            window.setTimeout("loadBtnCrear('empleado')", 300);
            window.setTimeout("loadListaEmpleado()", 310);
            break;
        case 'nomina':
            getView(vista);
            window.setTimeout("loadFecha()", 300);
            window.setTimeout("loadOpcionesFacturacion()", 310);
            window.setTimeout("loadOpcionesRegimen()", 320);
            window.setTimeout("listaPercepciones()", 330);
            window.setTimeout("listaDeducciones()", 340);
            window.setTimeout("listaOtrosPagos()", 350);
            window.setTimeout("optionListPercepciones()", 360);
            window.setTimeout("optionListDeducciones()", 370);
            window.setTimeout("optionListOtrosPagos()", 380);
            break;
        case 'listanomina':
            getView(vista);
            window.setTimeout("loadBtnCrear('nomina')", 300);
            window.setTimeout("filtrarFolio()", 320);
            break;
        case 'direccion':
            getView(vista);
            window.setTimeout("loadOpcionesEstado()", 320);
            break;
        case 'listadireccion':
            getView(vista);
            window.setTimeout("loadBtnCrear('destino')", 300);
            window.setTimeout("filtrarUbicacion()", 320);
            break;
        case 'transporte':
            getView(vista);
            //window.setTimeout("loadOpcionesEstado()", 320);
            break;
        case 'listatransporte':
            getView(vista);
            window.setTimeout("loadBtnCrear('transporte')", 300);
            window.setTimeout("filtrarTransporte()", 320);
            break;
        case 'remolque':
            getView(vista);
            //window.setTimeout("loadOpcionesEstado()", 320);
            break;
        case 'listaremolque':
            getView(vista);
            window.setTimeout("loadBtnCrear('remolque')", 300);
            window.setTimeout("filtrarRemolque()", 320);
            break;
        case 'operador':
            getView(vista);
            window.setTimeout("loadOpcionesEstado()", 320);
            break;
        case 'listaoperador':
            getView(vista);
            window.setTimeout("loadBtnCrear('operador')", 300);
            window.setTimeout("filtrarOperador()", 320);
            break;
        case 'carta':
            getView(vista);
            window.setTimeout("truncateTmpCarta()", 300);
            window.setTimeout("truncateTmpIMG()", 320);
            window.setTimeout("loadOpcionesFolios('4')", 350);
            window.setTimeout("loadFecha()", 370);
            window.setTimeout("loadOpcionesEstado()", 400);
            window.setTimeout("filtrarProducto()", 420);
            window.setTimeout("loadOpcionesFormaPago()", 450);
            window.setTimeout("loadOpcionesMetodoPago()", 470);
            window.setTimeout("loadOpcionesMoneda()", 500);
            window.setTimeout("loadOpcionesUsoCFDI()", 520);
            window.setTimeout("loadOpcionesComprobante()", 550);
            window.setTimeout("loadOpcionesFacturacion()", 570);
            window.setTimeout("loadOpcionesProveedor()", 600);
            window.setTimeout("opcionesPeriodoGlobal()", 620);
            window.setTimeout("opcionesMeses()", 650);
            window.setTimeout("opcionesAnoGlobal()", 670);
            break;
        case 'listacarta':
            getView(vista);
            window.setTimeout("truncateTmpCarta()", 300);
            window.setTimeout("truncateTmpIMG()", 300);
            window.setTimeout("loadBtnCrear('carta')", 300);
            window.setTimeout("filtrarCarta()", 320);
            window.setTimeout("opcionesMotivoCancelar()", 350);
            break;
        default :
            break;
    }
}

function deabilitarElemento(elemento) {
    $(elemento).attr("disabled", "false");
}


function logout() {
    cargandoShow();
    $.ajax({
        url: 'com.sine.enlace/enlacesession.php',
        type: 'POST',
        data: {transaccion: 'logout'},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            if (bandera == 0) {
                alertify.error(res);
            } else {
                if (datos == 'salir') {
                    location.href = 'index.php';
                } else {
                    alertify.error(res);
                }
            }
            cargandoHide();

        }
    });
}

function getNombreUsuario() {
    $("#nombre-soporte").val('');
    $("#telefono-soporte").val('');
    $("#check-soporte").removeAttr('checked');
    $("#correo-soporte").val('');
    $("#mensaje-soporte").val('');

    $.ajax({
        url: 'com.sine.enlace/enlaceinicio.php',
        type: 'POST',
        data: {transaccion: 'getnombre'},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            if (bandera == 0) {
                alertify.error(res);
            } else {
                var array = datos.split("</tr>");
                var nombre = array[0];
                var telefono = array[1];
                var mail = array[2];

                $("#nombre-soporte").val(nombre);
                $("#telefono-soporte").val(telefono);
                $("#correo-soporte").val(mail);
            }
        }
    })
}

function enviarSoporte() {
    var nombre = $("#nombre-soporte").val();
    var telefono = $("#telefono-soporte").val();
    var correo = $("#correo-soporte").val();
    var msg = $("#mensaje-soporte").val();
    var txtbd = msg.replace(new RegExp("\n", 'g'), '<ntr>');
    var chwhats = 0;
    if ($("#check-soporte").prop('checked')) {
        chwhats = 1;
    }
    if (isnEmpty(nombre, "nombre-soporte") && isnEmpty(telefono, "telefono-soporte") && isnEmpty(correo, "correo-soporte") && isnEmpty(msg, "mensaje-soporte")) {
        cargandoHide();
        cargandoShow();
        $.ajax({
            url: 'com.sine.enlace/enlaceinicio.php',
            type: 'POST',
            data: {transaccion: 'sendsoporte', nombre: nombre, telefono: telefono, chwhats: chwhats, correo: correo, msg: txtbd},
            success: function (datos) {
                var texto = datos.toString();
                var bandera = texto.substring(0, 1);
                var res = texto.substring(1, 5000);
                if (bandera == '0') {
                    alertify.error(res);
                } else {
                    alertify.success(res);
                }
                cargandoHide();
            }
        })
    }
}