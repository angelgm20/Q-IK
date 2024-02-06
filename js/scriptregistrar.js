$(document).ready(function (){
    loadformRegistrar();
    
});

//Formulario para cargar el formulario para ingresar al sistema
function loadformRegistrar(){
    $("#body-index").load("com.sine.vista/login/formreg.html");
}

function validarSession() {
    cargandoShow();
    $.ajax({
        url: 'com.sine.enlace/enlacesession.php',
        type: 'POST',
        data: {transaccion: 'validarsession'},
        success: function (datos) {
            if (datos.toString() == '0') {
                loadFormLogin();
            } else {
                location.href = 'home.php';
            }
            cargandoHide();
        }
    });
    cargandoHide();
}

function Registrar(){
    
    var nombre = $("#nombre").val();
    var apellidopaterno = $("#apellido-paterno").val();
    var apellidomaterno = $("#apellido-materno").val();
    var telefono = $("#telefono").val();
    var celular = $("#celular").val();
    var correo = $("#correo").val();
    var usuario = $("#nomusuario").val();
    var contrasena = $("#pass").val();
    var estatus = $("#estatus").val();
    var tipo = '1';

    if (isnEmpty(nombre, "nombre") && isnEmpty(apellidopaterno, "apellido-paterno") && isnEmpty(apellidomaterno, "apellido-materno") && isPhoneNumber(telefono, "telefono") && isPhoneNumber(celular, "celular") && isnEmpty(usuario, "nomusuario") && isnEmpty(contrasena, "pass") && isEmail(correo, "correo") && isnEmpty(tipo, "tipo-usuario")) {
        $.ajax({
            url: "com.sine.enlace/enlaceregistro.php",
            type: "POST",
            data: {transaccion: "insertarusuario", nombre: nombre, apellidopaterno: apellidopaterno, apellidomaterno: apellidomaterno, telefono: telefono, celular: celular, usuario: usuario, contrasena: contrasena, correo: correo, estatus: estatus, tipo: tipo},
            success: function (datos) {
                var texto = datos.toString();
                var bandera = texto.substring(0, 1);
                var res = texto.substring(1, 1000);
                if (bandera == '0') {
                    alertify.error(res);
                } else {
                    alertify.success(datos);
                    location.href='index.php';
                }
            }
        });
    }
}