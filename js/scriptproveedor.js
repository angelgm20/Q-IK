    function checkfiscales(){
    if (!$("#datosficales").prop('checked')){
        $("#fiscales").hide(400);
    } else if ($("#datosficales").prop('checked')) {
        $("#fiscales").show(400);
    }
}

function insertarProveedor() {
    var empresa = $("#empresa").val();
    var representante = $("#representante").val();
    var telefono = $("#telefono").val();
    var correo = $("#correo").val();
    var cuenta = $("#cuenta").val();
    var clabe = $("#clabe").val();
    var banco = $("#id-banco").val() || '0';
    var sucursal = $("#sucursal").val();
    var rfc = $("#rfc-proveedor").val();
    var razon = $("#razon-proveedor").val();
    
    if (isnEmpty(empresa, "empresa") && isnEmpty(representante, "representante") && isPhoneNumber(telefono, "telefono") && isEmail(correo, "correo")) {
        $.ajax({
            url: "com.sine.enlace/enlaceproveedor.php",
            type: "POST",
            data: {transaccion: "insertarproveedor", empresa: empresa, representante: representante, telefono: telefono, correo: correo, cuenta:cuenta, clabe:clabe, idbanco:banco, sucursal:sucursal,rfc:rfc,razon:razon},
            success: function (datos) {
                var texto = datos.toString();
                var bandera = texto.substring(0, 1);
                var res = texto.substring(1, 1000);
                if (bandera == '0') {
                    alertify.error(res);
                } else { 
                    loadView('listaproveedor');
                    alertify.success('Proveedor registrado')
                }
            }
        });
    }
}

function buscarProveedor(pag = ""){
    var REF = $("#buscar-proveedor").val();
    var numreg = $("#num-reg").val();
    
    $.ajax({
        url: "com.sine.enlace/enlaceproveedor.php",
        type: "POST",
        data: {transaccion: "filtrarproveedor", REF: REF, numreg: numreg, pag:pag},
        success: function (datos) {
            //alert(datos);
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
            } else {
                $("#body-lista-proveedores").html(datos);
            }
        }
    });
}

function loadListaProveedor(pag = ""){
    cargandoHide();
    cargandoShow();
    var REF = $("#buscar-proveedor").val();
    var numreg = $("#num-reg").val();
    
    $.ajax({
        url: "com.sine.enlace/enlaceproveedor.php",
        type: "POST",
        data: {transaccion: "filtrarproveedor", REF: REF, numreg: numreg, pag:pag},
        success: function (datos) {
            //alert(datos);
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
                cargandoHide();
            } else {
                $("#body-lista-proveedores").html(datos);
                cargandoHide();
            }
        }
    });
}

function editarProveedor(idproveedor) {
    cargandoHide();
    cargandoShow();
    $.ajax({
        url: "com.sine.enlace/enlaceproveedor.php",
        type: "POST",
        data: {transaccion: "editarproveedor", idproveedor: idproveedor},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
            }
            else {
                cargandoHide();
                loadView('nuevoproveedor');
                window.setTimeout("setValoresEditarProveedor('" + datos + "')", 600);
            }
        }
    });
}

function setValoresEditarProveedor(datos) {
   // alert(datos);
    changeText("#contenedor-titulo-form-proveedor", "Editar Proveedor");
    changeText("#btn-form-proveedor", "Guardar cambios <span class='glyphicon glyphicon-floppy-disk'></span></a>");
    var array = datos.split("</tr>", 20);
    var idproveedor = array[0];
    var empresa = array[1];
    var representante = array[2];
    var telefono = array[3];
    var correo = array[4];
    var cuenta = array[5];
    var clabe = array[6];
    var idbanco = array[7];
    var sucursal = array[8];
    var rfc = array[9];
    var razon = array[10];
    
    $("#empresa").val(empresa);
    $("#representante").val(representante);
    $("#telefono").val(telefono);
    $("#correo").val(correo);
    $("#cuenta").val(cuenta);
    $("#clabe").val(clabe);
    loadOpcionesBanco(idbanco);
    $("#sucursal").val(sucursal);
    $("#rfc-proveedor").val(rfc);
    $("#razon-proveedor").val(razon);
    $("#form-proveedor").append("<input type='hidden' id='id-proveedor' name='id-proveedor' value='" + idproveedor + "'/>");
    $("#btn-form-proveedor").attr("onclick", "actualizarProveedor();");
}

function actualizarProveedor() {
    var idproveedor=$("#id-proveedor").val();
    var empresa = $("#empresa").val();
    var representante = $("#representante").val();
    var telefono = $("#telefono").val();
    var correo = $("#correo").val();
    var cuenta = $("#cuenta").val();
    var clabe = $("#clabe").val();
    var banco = $("#id-banco").val() || '0';
    var sucursal = $("#sucursal").val();
    
    if (isnEmpty(empresa, "empresa") && isnEmpty(representante, "representante") && isPhoneNumber(telefono, "telefono") && isEmail(correo, "correo")) {
        $.ajax({
            url: "com.sine.enlace/enlaceproveedor.php",
            type: "POST",
            data: {transaccion: "actualizarproveedor", idproveedor:idproveedor, empresa: empresa, representante: representante, telefono: telefono, correo: correo, cuenta:cuenta, clabe:clabe, idbanco:banco, sucursal:sucursal},
            success: function (datos) {
                //alert(datos);
                var texto = datos.toString();
                var bandera = texto.substring(0, 1);
                var res = texto.substring(1, 1000);
                if (bandera == '0') {
                    alertify.error(res);
                }else {
                    loadView('listaproveedor');
                    alertify.success('Los cambios han sido guardados')
                }
            }
        });
    }
}

function eliminarProveedor(idproveedor) {
    alertify.confirm("Estas seguro que quieres eliminar este proveedor?", function () {
        cargandoHide();
        cargandoShow();
        $.ajax({
            url: "com.sine.enlace/enlaceproveedor.php",
            type: "POST",
            data: {transaccion: "eliminarproveedor", idproveedor: idproveedor},
            success: function (datos) {
                var texto = datos.toString();
                var bandera = texto.substring(0, 1);
                var res = texto.substring(1, 1000);
                if (bandera == '0') {
                    alertify.error(res);
                }
                else {
                    cargandoHide();
                    loadView('listaproveedor');
                }
            }
        });
    }).set({title: "Q-ik"});
}
