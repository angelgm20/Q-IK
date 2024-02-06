

function  insertarCliente() {
    var nombre = $("#nombre-cliente").val();
    var apellidopaterno= $("#apellidopaterno").val();
    var apellidomaterno = $("#apellidomaterno").val();
    var rfc = $("#rfc").val();
    var nombreEmpresa= $("#nombreEmpresa").val();
    var correoinfo = $("#email").val();
    var emailfacturacion = $("#emailfacturacion").val();
    var emailGerencia = $("#emaildegerencia").val();
    var  telefono= $("#telefono").val();
    var banco = $("#id-banco").val();
    var cuenta = $("#cuenta").val();
    var clabe = $("#clabe").val();
  
    if (
    isnEmpty(nombre, "nombre-cliente"),
    isnEmpty(apellidopaterno, "apellidopaterno") &&
    isnEmpty(apellidomaterno, "apellidomaterno")&&
    validarRFC(rfc, "rfc") &&
    isnEmpty(nombreEmpresa, "nombreEmpresa")&&
    validEmail(emailfacturacion, "emailfacturacion") &&
    valTel(telefono, "telefono") &&
    isnEmpty(banco, "id-banco") &&
    valCue(cuenta, "cuenta") &&
     valClab(clabe, "clabe")) {
        $.ajax({
            url: "com.sine.enlace/enlacecliente.php",
            type: "POST",
            data: {transaccion: "insertarcliente",
             nombre: nombre,
              apellidopaterno: apellidopaterno,
               apellidomaterno: apellidomaterno,
               rfc: rfc,
                nombreEmpresa: nombreEmpresa,
                correoinfo: correoinfo,
             emailfacturacion: emailfacturacion,
             emailGerencia: emailGerencia,
            telefono: telefono, banco: banco,
            cuenta: cuenta, clabe: clabe},
            success: function (datos) {
                var texto = datos.toString();
                var bandera = texto.substring(0, 1);
                var res = texto.substring(1, 1000);
                if (bandera == '0') {
                    alertify.error(res);
                } else { 
                    loadView('listaclientealtas');
                    alertify.success('cliente registrado')
                }
            }
        });
    }
}   


 

function buscarCliente(pag = ""){
    var REF = $("#buscar-cliente").val();
    var numreg = $("#num-reg").val();
    
    $.ajax({
        url: "com.sine.enlace/enlacecliente.php",   
        type: "POST",
        data: {transaccion: "filtrarcliente", REF: REF, numreg: numreg, pag:pag},
        success: function (datos) {
            //alert(datos);
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
            } else {
                $("#body-lista-clientes").html(datos);
            }
        }
    });
}

function loadListaClientes(pag = ""){
    cargandoHide();
    cargandoShow();
    var REF = $("#buscar-cliente").val();
    var numreg = $("#num-reg").val();
    
    $.ajax({
        url: "com.sine.enlace/enlacecliente.php",
        type: "POST",
        data: {transaccion: "filtrarcliente", REF: REF, numreg: numreg, pag:pag},
        success: function (datos) {
            //alert(datos);
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
                cargandoHide();
            } else {
                $("#body-lista-clientes").html(datos);
                cargandoHide();
            }
        }
    });
}

function editarCliente(idcliente) {
    cargandoHide();
    cargandoShow();
    $.ajax({
        url: "com.sine.enlace/enlacecliente.php",
        type: "POST",
        data: {transaccion: "editarcliente", idcliente: idcliente},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
            }
            else {
                cargandoHide();
                loadView('nuevocliente');
                window.setTimeout("setValoresEditarCliente('" + datos + "')", 500);
                
                
            }
        }
    });
}




function setValoresEditarCliente(datos) {
    changeText("#contenedor-titulo-form-cliente", "Editar Cliente");
    changeText("#btn-form-cliente", "Guardar cambios </a>");
    var array = datos.split("</tr>", 20);
    var idcliente = array[0];
    var nombre = array [1];
    var apellidopaterno = array[2];
    var apellidomaterno = array[3];
    var rfc = array[4]
    var nombreEmpresa = array [5];
    var correoInfo = array [6];
    var emailfacturacion = array[7];
    var emailGerencia = array [8];
    var telefono = array [9];
    var idbanco =  array[10];
    var cuenta = array[11];
    var clabe = array [12];
   
    $("#id-cliente").val(idcliente);
    $("#nombre-cliente").val(nombre);
    $("#apellidopaterno").val(apellidopaterno);
    $("#apellidomaterno").val(apellidomaterno);
    $("#rfc").val(rfc);
    $("#nombreEmpresa").val(nombreEmpresa);
    $("#email").val(correoInfo);
    $("#emailfacturacion").val(emailfacturacion);
    $("#emaildegerencia").val(emailGerencia);
    $("#telefono").val(telefono);
    $("#id-banco").val(idbanco);
    $("#cuenta").val(cuenta);
    $("#clabe").val(clabe);
    $("#btn-form-cliente").attr("onclick", "actualizarCliente();");
    
}

function actualizarCliente() {
    var idcliente =$("#id-cliente").val();
    var nombre = $("#nombre-cliente").val();
    var apellidopaterno= $("#apellidopaterno").val();
    var apellidomaterno = $("#apellidomaterno").val();
    var  rfc= $("#rfc").val();
    var  nombreEmpresa= $("#nombreEmpresa").val();
    var correoinfo = $("#email").val();
    var emailfacturacion = $("#emailfacturacion").val();
    var emailGerencia = $("#emaildegerencia").val();
    var  telefono= $("#telefono").val();
    var banco = $("#id-banco").val(); 
    var cuenta = $("#cuenta").val();
    var clabe = $("#clabe").val();
    
    if (
    isnEmpty(nombre, "nombre-cliente"),
    isnEmpty(apellidopaterno, "apellidopaterno") &&
    isnEmpty(apellidomaterno, "apellidomaterno")&&
    validarRFC(rfc, "rfc") &&
    isnEmpty(nombreEmpresa, "nombreEmpresa")&&
    validEmail(emailfacturacion, "emailfacturacion") &&
    valTel(telefono, "telefono") &&
    isnEmpty(banco, "id-banco") &&
      valCue(cuenta, "cuenta") &&
        valClab(clabe, "clabe")) {
            $.ajax({
                url: "com.sine.enlace/enlacecliente.php",
                type: "POST",
                data: {transaccion: "actualizarcliente",
                idcliente: idcliente,
                 nombre: nombre,
                 apellidopaterno: apellidopaterno,
                  apellidomaterno: apellidomaterno,
                  rfc: rfc,
                    nombreEmpresa: nombreEmpresa,
                    correoinfo: correoinfo,
                 emailfacturacion: emailfacturacion,
                 emailGerencia: emailGerencia,
                telefono: telefono, banco: banco,
                cuenta: cuenta, clabe: clabe },
                success: function (datos) {
                    var texto = datos.toString();
                    var bandera = texto.substring(0, 1);
                    var res = texto.substring(1, 1000);
                    if (bandera == '0') {
                        alertify.error(res);
                    } else { 
                        loadView('listaclientealtas');
                        alertify.success('los cambios han sido guardados')
                    }
                }
            });
        }
    }

function eliminarCliente(idcliente) {
    alertify.confirm("Estas seguro que quieres eliminar este cliente?", function () {
        cargandoHide();
        cargandoShow();
        $.ajax({
            url: "com.sine.enlace/enlacecliente.php",
            type: "POST",
            data: {transaccion: "eliminarcliente", idcliente: idcliente},
            success: function (datos) {
                var texto = datos.toString();
                var bandera = texto.substring(0, 1);
                var res = texto.substring(1, 1000);
                if (bandera == '0') {
                    alertify.error(res);
                }
                else {
                    cargandoHide();
                    loadView('listaclientealtas');
                }
            }
        });
    }).set({title: "Q-ik"});
}