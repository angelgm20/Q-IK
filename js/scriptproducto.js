function calcularGanancia() {
    var preciocompra = $("#pcompra").val() || '0';
    var porcentaje = $("#porganancia").val() || '0';
    var importeganancia = (parseFloat(preciocompra) * parseFloat(porcentaje)) / 100;
    $("#ganancia").val(importeganancia);
    var precioventa = parseFloat(preciocompra) + parseFloat(importeganancia);
    $("#pventa").val(precioventa);
}

function addinventario() {
    var tipo = $("#tipo").val();
    if (tipo == '1') {
        $("#inventario").show('slow');
        if ($("#chinventario").prop('checked')) {
            $("#cantidad").removeAttr('disabled');
        } else {
            $("#cantidad").attr('disabled', true);
            $("#cantidad").val('0');
        }
        $("#clave-unidad").val('');
    } else {
        $("#chinventario").removeAttr('checked');
        $("#inventario").hide('slow');
        $("#cantidad").attr('disabled', true);
        $("#cantidad").val('0');
        $("#clave-unidad").val('E48-Unidad de servicio');
    }
}

function insertarProducto() {
    var codproducto = $("#codigo-producto").val();
    var producto = $("#producto").val();
    var tipo = $("#tipo").val();
    var unidad = $("#clave-unidad").val();
    var descripcion = $("#descripcion").val();
    var pcompra = $("#pcompra").val() || '0';
    var porcentaje = $("#porganancia").val() || '0';
    var ganancia = $("#ganancia").val() || '0';
    var pventa = $("#pventa").val();
    var clavefiscal = $("#clave-fiscal").val();
    var idproveedor = $("#id-proveedor").val() || '0';
    var imagen = $('#filename').val();
    var chinventario = 0;
    var cantidad = $("#cantidad").val() || '0';
    if ($("#chinventario").prop('checked')) {
        chinventario = 1;
    }
    if (isnEmpty(codproducto, "codigo-producto") && isnEmpty(producto, "producto") && isList(clavefiscal, "clave-fiscal") && isnEmpty(tipo, "tipo") && isListUnit(unidad, "clave-unidad") && isPositive(porcentaje, "porganancia") && isPositive(ganancia, "ganancia") && isPositive(pventa, "pventa")) {
        $.ajax({
            url: "com.sine.enlace/enlaceproducto.php",
            type: "POST",
            data: {transaccion: "insertarproducto", codproducto: codproducto, producto: producto, tipo: tipo, unidad: unidad, descripcion: descripcion, pcompra: pcompra, porcentaje: porcentaje, ganancia: ganancia, pventa: pventa, clavefiscal: clavefiscal, idproveedor: idproveedor, imagen: imagen, chinventario: chinventario, cantidad: cantidad},
            success: function (datos) {
                var texto = datos.toString();
                var bandera = texto.substring(0, 1);
                var res = texto.substring(1, 1000);
                if (bandera == '0') {
                    alertify.error(res);
                } else {
                    alertify.success('Producto Registrado');
                    loadView('listaproductoaltas');
                }
                cargandoHide();
            }
        });
    }
}

function aucompletarCatalogo() {
    $('#clave-fiscal').autocomplete({
        source: "com.sine.enlace/enlaceautocompletar.php?transaccion=catfiscal",
        select: function (event, ui) {
            var a = ui.item.value;
            var id = ui.item.id;
        }
    });
}

function aucompletarUnidad() {

    $('#clave-unidad').autocomplete({
        source: "com.sine.enlace/enlaceautocompletar.php?transaccion=catunidad",
        select: function (event, ui) {

            var a = ui.item.value;
            var id = ui.item.id;
        }
    });
}

function setProducto(codprod) {
    var codigo = "#prod" + codprod;
    $('' + codigo + '').attr('selected', true);
}

function setCant(idproducto) {
    $("#idproducto").val(idproducto);
    $("#cantidad-inventario").val('0');
}

function setCantInventario(idproducto, cantidad) {
    $("#idproducto-inventario").val(idproducto);
    $("#cantidad-nueva").val(cantidad);
}

function estadoInventario() {
    var idproducto = $("#idproducto").val();
    var cantidad = $("#cantidad-inventario").val();
    if (isnEmpty(cantidad, "cantidad-inventario")) {
        alertify.confirm("Esta seguro que desea activar el inventario de este producto?", function () {
            //cargandoHide();
            //cargandoShow();
            $.ajax({
                url: "com.sine.enlace/enlaceproducto.php",
                type: "POST",
                data: {transaccion: "activarinventario", idproducto: idproducto, cantidad: cantidad},
                success: function (datos) {
                    var texto = datos.toString();
                    var bandera = texto.substring(0, 1);
                    var res = texto.substring(1, 1000);
                    if (bandera == '0') {
                        alertify.error(res);
                    } else {
                        //cargandoHide();
                        $("#cambiarestado").modal('hide');
                        window.setTimeout("loadView('listaproductoaltas')", 200);
                    }
                }
            });
        }).set({title: "Q-ik"});
    }
}

function desactivarInventario(idproducto) {
    alertify.confirm("Esta seguro que desea desactivar el inventario de este producto? De desactivarlo la cantidad registrada se establecera en 0", function () {
        cargandoHide();
        cargandoShow();
        $.ajax({
            url: "com.sine.enlace/enlaceproducto.php",
            type: "POST",
            data: {transaccion: "desactivarinventario", idproducto: idproducto},
            success: function (datos) {
                var texto = datos.toString();
                var bandera = texto.substring(0, 1);
                var res = texto.substring(1, 1000);
                if (bandera == '0') {
                    alertify.error(res);
                } else {
                    loadView('listaproductoaltas');
                }
                cargandoHide();
            }
        });
    }).set({title: "Q-ik"});
}

function cambiarCantidad() {
    var idproducto = $("#idproducto-inventario").val();
    var cantidad = $("#cantidad-nueva").val();
    $.ajax({
        url: "com.sine.enlace/enlaceproducto.php",
        type: "POST",
        data: {transaccion: "cambiarcantidad", idproducto: idproducto, cantidad: cantidad},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
            } else {
                //cargandoHide();
                $("#cambiarcantidad").modal('hide');
                window.setTimeout("loadView('listaproductoaltas')", 200);
            }
        }
    });
}

function buscarProducto(pag = "") {
    if (pag != "") {
        cargandoHide();
        cargandoShow();
    }
    var NOM = $("#buscar-producto").val();
    var numreg = $("#num-reg").val();
    $.ajax({
        url: "com.sine.enlace/enlaceproducto.php",
        type: "POST",
        data: {transaccion: "filtrarproducto", NOM: NOM, pag: pag, numreg: numreg},
        success: function (datos) {
            //alert(datos);
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
            } else {
                $("#body-lista-productos-altas").html(datos);
            }
            cargandoHide();
        }
    });
}

function loadListaProductosaltas(pag = "") {
    cargandoHide();
    cargandoShow();
    var NOM = "";
    var numreg = $("#num-reg").val();
    $.ajax({
        url: "com.sine.enlace/enlaceproducto.php",
        type: "POST",
        data: {transaccion: "filtrarproducto", NOM: NOM, pag: pag, numreg: numreg},
        success: function (datos) {
            //alert(datos);
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
            } else {
                $("#body-lista-productos-altas").html(datos);
            }
            cargandoHide();
        }
    });
}

function editarProducto(idproducto) {
    cargandoHide();
    cargandoShow();
    $.ajax({
        url: "com.sine.enlace/enlaceproducto.php",
        type: "POST",
        data: {transaccion: "editarproducto", idproducto: idproducto},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
                cargandoHide();
            } else {
                loadView('nuevoproducto');
                window.setTimeout("setValoresEditarProducto('" + datos + "')", 600);
            }
        }
    });
}

function setValoresEditarProducto(datos) {
    changeText("#contenedor-titulo-form-producto", "Editar producto");
    changeText("#btn-form-producto-guardar", "Guardar cambios <span class='glyphicon glyphicon-floppy-disk'></span></a>");

    var array = datos.split("</tr>");
    var idproducto = array[0];
    var codigo = array[1];
    var nombre = array[2];
    var unidad = array[3] + "-" + array[4];
    var descripcion_producto = array[5];
    var pcompra = array[6];
    var porcentaje = array[7];
    var ganancia = array[8];
    var pventa = array[9];
    var tipo = array[10];
    var clavefiscal = array[11] + "-" + array[12];
    var idproveedor = array[13];
    var empresa = array[14];
    var imagen = array[15];
    var chinventario = array[16];
    var cantidad = array[17];
    var img = array[18];

    if (tipo == "1") {
        $("#inventario").removeAttr('hidden');
    } else if (tipo == "2") {
        $("#inventario").attr('hidden', true);
    }

    if (chinventario == '1') {
        $("#chinventario").prop('checked', true);
        $("#cantidad").removeAttr('disabled');
    }

    $("#codigo-producto").val(codigo);
    $("#producto").val(nombre);
    $("#tipo").val(tipo);
    $("#cantidad").val(cantidad);
    $("#clave-unidad").val(unidad);
    $("#descripcion").val(descripcion_producto);
    $("#pcompra").val(pcompra);
    $("#porganancia").val(porcentaje)
    $("#ganancia").val(ganancia);
    $("#pventa").val(pventa);
    $("#clave-fiscal").val(clavefiscal);
    loadOpcionesProveedor(idproveedor);

    if (imagen !== '') {
        $("#muestraimagen").html(img);
        $("#filename").val(imagen);
    }

    $("#form-producto").append("<input type='hidden' id='imgactualizar' name='imgactualizar' value='" + imagen + "'/>")
    $("#btn-form-producto-guardar").attr("onclick", "actualizarProducto(" + idproducto + ");");
    cargandoHide();
}

function actualizarProducto(idproducto) {
    var codproducto = $("#codigo-producto").val();
    var producto = $("#producto").val();
    var tipo = $("#tipo").val();
    var unidad = $("#clave-unidad").val();
    var descripcion = $("#descripcion").val();
    var pcompra = $("#pcompra").val() || '0';
    var porcentaje = $("#porganancia").val() || '0';
    var ganancia = $("#ganancia").val() || '0';
    var pventa = $("#pventa").val();
    var clavefiscal = $("#clave-fiscal").val();
    var idproveedor = $("#id-proveedor").val() || '0';
    var imagen = $('#filename').val();
    var imgactualizar = $("#imgactualizar").val();
    var chinventario = 0;
    var cantidad = $("#cantidad").val() || '0';
    if ($("#chinventario").prop('checked')) {
        chinventario = 1;
    }
    if (isnEmpty(codproducto, "codigo-producto") && isnEmpty(producto, "producto") && isList(clavefiscal, "clave-fiscal") && isnEmpty(tipo, "tipo") && isListUnit(unidad, "clave-unidad") && isPositive(porcentaje, "porganancia") && isPositive(ganancia, "ganancia") && isPositive(pventa, "pventa")) {
        $.ajax({
            url: "com.sine.enlace/enlaceproducto.php",
            type: "POST",
            data: {transaccion: "actualizarproducto", idproducto: idproducto, codproducto: codproducto, producto: producto, tipo: tipo, unidad: unidad, descripcion: descripcion, pcompra: pcompra, porcentaje: porcentaje, ganancia: ganancia, pventa: pventa, clavefiscal: clavefiscal, idproveedor: idproveedor, imagen: imagen, chinventario: chinventario, cantidad: cantidad, imgactualizar: imgactualizar},
            success: function (datos) {
                var texto = datos.toString();
                var bandera = texto.substring(0, 1);
                var res = texto.substring(1, 1000);
                if (bandera == '0') {
                    alertify.error(res);
                } else {
                    alertify.success('Registro Actualizado');
                    loadView('listaproductoaltas');
                }
                cargandoHide();
            }
        });
    }
}

function eliminarProducto(idproducto) {
    alertify.confirm("Estas seguro que quieres eliminar este producto?", function () {
        cargandoHide();
        cargandoShow();
        $.ajax({
            url: "com.sine.enlace/enlaceproducto.php",
            type: "POST",
            data: {transaccion: "eliminarproducto", idproducto: idproducto},
            success: function (datos) {
                var texto = datos.toString();
                var bandera = texto.substring(0, 1);
                var res = texto.substring(1, 1000);
                if (bandera == '0') {
                    alertify.error(res);
                } else {
                    loadView('listaproductoaltas');
                }
                cargandoHide();
            }
        });
    }).set('oncancel', function (closeEvent) {
        loadView('listaproductoaltas');
    }).set({title: "Q-ik"});
}



function copiarProducto(idproducto) {
    cargandoHide();
    cargandoShow();
    $.ajax({
        url: "com.sine.enlace/enlaceproducto.php",
        type: "POST",
        data: {transaccion: "editarproducto", idproducto: idproducto},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
                cargandoHide();
            } else {
                loadView('nuevoproducto');
                window.setTimeout("setValoresCopiarProducto('" + datos + "')", 600);
            }
        }
    });
}

function setValoresCopiarProducto(datos) {
    var array = datos.split("</tr>");
    var idproducto = array[0];
    var codigo = array[1];
    var nombre = array[2];
    var unidad = array[3] + "-" + array[4];
    var descripcion_producto = array[5];
    var pcompra = array[6];
    var porcentaje = array[7];
    var ganancia = array[8];
    var pventa = array[9];
    var tipo = array[10];
    var clavefiscal = array[11] + "-" + array[12];
    var idproveedor = array[13];
    var imagen = array[14];
    var chinventario = array[15];
    var cantidad = array[16];
    var img = array[17];

    if (tipo == "1") {
        $("#inventario").removeAttr('hidden');
    } else if (tipo == "2") {
        $("#inventario").attr('hidden', true);
    }

    if (chinventario == '1') {
        $("#chinventario").prop('checked', true);
        $("#cantidad").removeAttr('disabled');
    }

    $("#codigo-producto").val(codigo);
    $("#producto").val(nombre);
    $("#tipo").val(tipo);
    $("#cantidad").val(cantidad);
    $("#clave-unidad").val(unidad);
    $("#descripcion").val(descripcion_producto);
    $("#pcompra").val(pcompra);
    $("#porganancia").val(porcentaje)
    $("#ganancia").val(ganancia);
    $("#pventa").val(pventa);
    $("#clave-fiscal").val(clavefiscal);
    loadOpcionesProveedor(idproveedor);

    if (imagen !== '') {
        $("#muestraimagen").html(img);
        $("#filename").val(imagen);
    }
    cargandoHide();
}