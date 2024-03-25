
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
    $.ajax({
        url: '../CATSAT/CATSAT/com.sine.enlace/enlaceComprobante.php',
        type: 'POST',
        dataType: 'JSON',
        data: {transaccion: 'opcionescomprobante', id:id},
        success: function (datos) {
                $(".contenedor-tipo-comprobante").html(datos.datos);
        }
    });
}

function loadOpcionesMetodoPago(selected = "") {
    $.ajax({
        url: '../CATSAT/CATSAT/com.sine.enlace/enlaceMetodosPago.php',
        type: 'POST',
        dataType: 'JSON',
        data: {transaccion: 'opcionesmpago', selected: selected},
        success: function (datos) {
                $(".contenedor-metodo-pago").html(datos.datos);
        }
    });
}

function loadOpcionesFormaPago(selected = "") {
    $.ajax({
        url: '../CATSAT/CATSAT/com.sine.enlace/enlaceFormaPago.php',
        type: 'POST',
        dataType: 'JSON',
        data: {transaccion: 'opcionesformapago', selected:selected},
        success: function (datos) {
                $(".contenedor-forma-pago").html(datos.datos);

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
        dataType: 'JSON',
        data: {transaccion: 'gettipocambio', idmoneda: idmoneda},
        success: function (datos) {
                if (idmoneda != "1") {
                    $("#tipo-cambio").removeAttr('disabled');
                } else {
                    $("#tipo-cambio").attr('disabled', true);
                }
                $("#tipo-cambio").val(datos.datos);
            
            cargandoHide();
        }
    });
}

function loadOpcionesMoneda(idmoneda = "") {
    $.ajax({
        url: '../CATSAT/CATSAT/com.sine.enlace/enlaceMonedas.php',
        type: 'POST',
        dataType: 'JSON',
        data: {transaccion: 'opcionesmoneda', idmoneda:idmoneda},
        success: function (datos) {
                $(".contenedor-moneda").html(datos.datos);
        }
    });
}

function loadOpcionesUsoCFDI(iduso = "") {
    $.ajax({
        url: '../CATSAT/CATSAT/com.sine.enlace/enlaceusoCFDI.php',
        type: 'POST',
        dataType: 'JSON',
        data: {transaccion: 'opcionesusocfdi', iduso:iduso},
        success: function (datos) {
                $(".contenedor-uso").html(datos.datos);
        }
    });
}

function loadOpcionesTipoRelacion() {
    $.ajax({
        url: '../CATSAT/CATSAT/com.sine.enlace/enlaceTipoRelacion.php',
        type: 'POST',
        dataType: 'JSON',
        data: {transaccion: 'opcionesrelacion'},
        success: function (datos) {
                $(".contenedor-relacion").html(datos.datos);
        }
    });
}

//---------------------------------------PROD_SERV
function aucompletarCatalogo() {
    $('#clave-fiscal').autocomplete({
        source: "../CATSAT/CATSAT/com.sine.enlace/enlaceProdServ.php?transaccion=autocompleta",
        appendTo: "#nuevo-producto",
        select: function (event, ui) {
            var a = ui.item.value;
            var id = ui.item.id;
        }
    });
}
/*
function autocompletarCFiscal() {
    $('#editar-cfiscal').autocomplete({
        source: "../CATSAT/CATSAT/com.sine.enlace/enlaceAutoCom.php?transaccion=catfiscal",
        appendTo: "#editar-producto",
        select: function (event, ui) {
            var a = ui.item.value;
            var id = ui.item.id;
        }
    });
}

function aucompletarUnidad() {
    $('#clave-unidad').autocomplete({
        source: "../CATSAT/CATSAT/com.sine.enlace/enlaceClaveUnidad.php?transaccion=getOptions",
        appendTo: "#nuevo-producto",
        select: function (event, ui) {
            var a = ui.item.value;
            var id = ui.item.id;
        }
    });
}
*/
//--------------------------------------UNIDAD DE MEDIDAS
function aucompletarUnidad() {
    $('#clave-unidad').autocomplete({
        source: "../CATSAT/CATSAT/com.sine.enlace/enlaceClaveUnidad.php?transaccion=autocompleta",
        appendTo: "#nuevo-producto",
        select: function (event, ui) {
            var a = ui.item.value;
            var id = ui.item.id;
        }
    });
}
function autocompletarCUnidad() {
    $('#editar-cunidad').autocomplete({
        source: "../CATSAT/CATSAT/com.sine.enlace/enlaceClaveUnidad.php?transaccion=getOptions",
        appendTo: "#editar-producto",
        select: function (event, ui) {
            var a = ui.item.value;
            var id = ui.item.id;
        }
    });
}


//AUTOCOMPLETAR REGIMEN

function aucompletarRegimen() {
    $('#regimen-empresa').autocomplete({
        source: "../CATSAT/CATSAT/com.sine.enlace/enlaceRegimenFiscal.php?transaccion=autocompleta",
        select: function (event, ui) {
            var a = ui.item.value;
        }
    });
}

function autoCompleteRegimenFiscal(){
    $('#regimenFiscal').autocomplete({
        source: "../CATSTAT/com.sine.enlace/enlaceRegimenFiscal.php?transaccion=autocompleta",
        select: function (event, ui) {
            var c_regimen = ui.item.c_regimenfiscal;
            var desc_regimen = ui.item.descripcion_regimen;
            $('#c_regimenFiscal').val(c_regimen);
            $('#desc_regimenFiscal').val(desc_regimen);
        }
    });
}


//GET SELECT
function getOptions(){
    $.ajax({
        data : {transaccion: 'getOptions'},
        url  : 'com.sine.enlace/enlaceRegimenFiscal.php',
        type : 'POST',
        dataType : 'JSON',
        success  : function(res){
            if(res.status > 0){
                $('#contenedor_select_regimen').html(res.datos);
            }
        }
    });
}