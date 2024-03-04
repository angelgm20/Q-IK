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
                    console.log(datos);
                    var array = datos.split("<tr>");
                    var estados = array[0];
                    var municipios = array[1];
                    console.log(datos);
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
    //cargandoShow();
    $.ajax({
        url: '../CATSAT/CATSAT/com.sine.enlace/enlaceCodigopostal.php',
        type: 'POST',
        dataType : 'JSON',
        data: {transaccion: 'opcionesestado', idestado:idestado},
        success: function (datos) {
           
            $(".contenedor-estado").html(datos.datos);
          
            
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

