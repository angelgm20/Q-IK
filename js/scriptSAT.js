/*function getEstadoByCodP() {
    var cp = $("#cp-empresa").val();
    if (cp !== "") {
        if (isNumber(cp, "cp-empresa")) {
            cargandoHide();
            cargandoShow();
            $.ajax({
                url: "../CATSAT/CATSAT/com.sine.enlace/enlaceCodigopostal.php",
                type: 'POST',
                dataType: 'JSON',
                data: {transaccion: 'buscarcp', cp: cp},
                success: function (datos) {
        
                        $(".contenedor-estado").html(datos.datos);
                        //$(".contenedor-municipio").html(datos.datosm);
                        loadOpcionesMunicipio();
                        cargandoHide();
                    }
                   
            });
        }
    }
}*/

function getEstadoByCodP(){
    var cp = $("#cp-empresa").val();
    if (cp !== "") {
        if (isNumber(cp, "cp-empresa")) {
            cargandoHide();
            cargandoShow();
            $.ajax({
                url: "../CATSAT/CATSAT/com.sine.enlace/enlaceCodigopostal.php",
                type: 'POST',
                dataType: 'html',
                data: {transaccion: 'buscarcp', cp: cp},
                success: function (datos) {
                    var array = datos.split("<tr>");
                    var estados = array[0];
                    var municipios = array[1];
                    var texto = datos.toString();
                    var bandera = texto.substring(0, 1);
                    var res = texto.substring(1, 5000);
                    if (bandera == 0) {
                        alertify.error(res);
                    } else {
                        cargandoHide();
                        $(".contenedor-estado").html(estados);
                        $(".contenedor-municipio").html(municipios);
                       
                        

                        
                        
                    }
                    cargandoHide();
                    
                }
            });
        }
    }
}


function loadOpcionesEstado(idestado = "") {
    cargandoShow();
    $.ajax({
        url: '../CATSAT/CATSAT/com.sine.enlace/enlaceCodigopostal.php',
        type: 'POST',
        dataType : 'JSON',
        data: {transaccion: 'opcionesestado', idestado:idestado},
        success: function (datos) {
           
            $(".contenedor-estado").html(datos.datos);
          
            
            cargandoHide();
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
        url: "../CATSAT/CATSAT/com.sine.enlace/enlaceCodigopostal.php",
        type: 'POST',
        dataType: 'JSON',
        data: {transaccion: 'opcionesmunicipio', idestado: idestado, idmunicipio:idmun},
        success: function (datos) {
           
            $(".contenedor-municipio").html(datos.datos);
            cargandoHide();
        }
       
    });
}

$(function () {
    $("#id-estado").on("change", function () {
        loadOpcionesMunicipio();
    });
});

function loadOpcionesBanco(idbanco = "") {
    //cargandoShow();
    $.ajax({
        url: "../CATSAT/CATSAT/com.sine.enlace/enlaceCodigopostal.php",
        type: 'POST',
        dataType: 'JSON',
        data: {transaccion: 'opcionesbanco', idbanco: idbanco},
        success: function (datos) {
           
                $(".contenedor-banco").html(datos.datos);
                 //cargandoHide();
            }
        
    });
}

function addloadOpcionesBanco(a, idbanco = "") {
    //cargandoShow();
    $.ajax({
        url: "../CATSAT/CATSAT/com.sine.enlace/enlaceCodigopostal.php",
        type: 'POST',
        data: {transaccion: 'addopcionesbanco', a: a, idbanco:idbanco},
        success: function (datos) {
                $(".contenedor-banco" + a).html(datos.datos);
                //cargandoHide();
            }
    });
}

//FACTURACION*******************************************

function loadOpcionesComprobante(id = "") {
    //cargandoShow();
    $.ajax({
        url: '../CATSAT/CATSAT/com.sine.enlace/enlaceComprobante.php',
        type: 'POST',
        dataType: 'JSON',
        data: {transaccion: 'opcionescomprobante', id:id},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            if (bandera == 0) {
                alertify.error(res);
            } else {
                $(".contenedor-tipo-comprobante").html(datos.datos);
            }
            //cargandoHide();
        }
    });
}

function loadOpcionesMetodoPago(selected = "") {
    //cargandoShow();
    $.ajax({
        url: '../CATSAT/CATSAT/com.sine.enlace/enlaceMetodosPago.php',
        type: 'POST',
        dataType: 'JSON',
        data: {transaccion: 'getOptions', selected: selected},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            if (bandera == 0) {
                alertify.error(res);
            } else {
                $(".contenedor-metodo-pago").html(datos.datos);
            }
            //cargandoHide();
        }
    });
}

function getTipoCambio(idmoneda = "") {
    cargandoHide();
    cargandoShow();
    if (idmoneda == "") {
        idmoneda = $("#id-moneda").val();
    }
    $.ajax({
        url: '../CATSAT/CATSAT/com.sine.enlace/enlaceMonedas.php',
        type: 'POST',
        data: {transaccion: 'gettipocambio', idmoneda: idmoneda},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            if (bandera == 0) {
                alertify.error(res);
            } else {
                if (idmoneda != "1") {
                    $("#tipo-cambio").removeAttr('disabled');
                } else {
                    $("#tipo-cambio").attr('disabled', true);
                }
                $("#tipo-cambio").val(datos);
            }
            cargandoHide();
        }
    });
}