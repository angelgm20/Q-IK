function tests() {
    $.ajax({
        url: "com.sine.enlace/enlacecron.php",
        type: "POST",
        data: {transaccion: "refactura"},
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

function tablaProductos(uuid = "") {
    $.ajax({
        url: "com.sine.enlace/enlacefactura.php",
        type: "POST",
        data: {transaccion: "tablatmp", uuid: uuid},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
            } else {
                $("#resultados").html(datos);
               
            }
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
        url: 'com.sine.enlace/enlacepago.php',
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

function addCFDI() {
    var rel = $("#tipo-relacion").val();
    var cfdi = $("#cfdi-rel").val();
    if (isnEmpty(rel, "tipo-relacion") && isnEmpty(cfdi, "cfdi-rel")) {
        $.ajax({
            url: "com.sine.enlace/enlacefactura.php",
            type: "POST",
            data: {transaccion: "addcfdi", rel: rel, cfdi: cfdi},
            success: function (datos) {
                var texto = datos.toString();
                var bandera = texto.substring(0, 1);
                var res = texto.substring(1, 1000);
                if (bandera == '0') {
                    alertify.error(res);
                } else {
                    $("#tipo-relacion").val('');
                    $("#cfdi-rel").val('');
                    cargandoHide();
                    var array = datos.split("<corte>");
                    var p2 = array[1];
                    $("#body-lista-cfdi").html(p2);
                   
                }
            }
        });
    }
}

function eliminarCFDI(idtmp) {
    alertify.confirm("Esta seguro que desea eliminar este CFDI?", function () {
        $.ajax({
            url: "com.sine.enlace/enlacefactura.php",
            type: "POST",
            data: {transaccion: "eliminarcfdi", idtmp: idtmp},
            success: function (datos) {
                var texto = datos.toString();
                var bandera = texto.substring(0, 1);
                var res = texto.substring(1, 1000);
                if (bandera == '0') {
                    alertify.error(res);
                } else {
                    cargandoHide();
                    var array = datos.split("<corte>");
                    var p1 = array[0];
                    var p2 = array[1];
                    $("#body-lista-cfdi").html(p2);
                }
            }
        });
    }).set({title: "Q-ik"});
}

function checkIVA(idtmp) {
    var traslados = [];
    $.each($("input[name='chtrastabla" + idtmp + "']:checked"), function () {
        traslados.push(0 + "-" + $(this).val() + "-" + $(this).attr("data-impuesto"));
    });
    var idtraslados = traslados.join("<impuesto>");

    var retenciones = [];
    $.each($("input[name='chrettabla" + idtmp + "']:checked"), function () {
        retenciones.push(0 + "-" + $(this).val() + "-" + $(this).attr("data-impuesto"));
    });
    var idretenciones = retenciones.join("<impuesto>");
    $.ajax({
        url: "com.sine.enlace/enlacefactura.php",
        type: "POST",
        data: {transaccion: "chivatmp", idtmp: idtmp, traslados: idtraslados, retenciones: idretenciones},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
            } else {
                tablaProductos();
                cargandoHide();
            }
        }
    });
}

function setCamposProducto() {
   
    $("#codigo-producto").val('');
    $("#producto").val('');
    $("#tipo").val('');
    $("#inventario").attr('hidden', true);
    $("#clave-unidad").val('');
    $("#descripcion").val('');
    $("#pcompra").val(0);
    $("#porganancia").val(0);
    $("#ganancia").val(0);
    $("#pventa").val(0);
    $("#clave-fiscal").val('');
    $('#id-proveedor').val('');
    $("#imagen").val('');
    $('#muestraimagen').html("");
    $("#btn-form-producto-factura").attr("onclick", "insertarProductoFactura();");
}

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

function insertarProductoFactura() {
    var codproducto = $("#codigo-producto").val();
    var producto = $("#producto").val();
    var descripcion = $("#descripcion").val();
    var clavefiscal = $("#clave-fiscal").val();
    var tipo = $("#tipo").val();
    var unidad = $("#clave-unidad").val();
    var pcompra = $("#pcompra").val();
    var porcentaje = $("#porganancia").val();
    var ganancia = $("#ganancia").val();
    var pventa = $("#pventa").val();
    var idproveedor = $("#id-proveedor").val() || '0';
    var imagen = $('#filename').val();
    var chinventario = 0;
    var cantidad = $("#cantidad").val();
    if ($("#chinventario").prop('checked')) {
        chinventario = 1;
    }
    
    if (
    validarCodigoProducto (codproducto, "codigo-producto") && 
    isnEmpty(producto, "producto") &&
    isList(clavefiscal, "clave-fiscal") &&
    isnEmpty(tipo, "tipo") && 
    isListUnit(unidad, "clave-unidad") &&
    isPositive(porcentaje, "porganancia") && 
    isPositive(ganancia, "ganancia") && 
    isPositive(pventa, "pventa")) {
        //cargandoHide();
        //cargandoShow();
        $.ajax({
            url: "com.sine.enlace/enlaceproducto.php",
            type: "POST",
            data: {transaccion: "insertarproducto", codproducto: codproducto, producto: producto, tipo: tipo, unidad: unidad, descripcion: descripcion, pcompra: pcompra, porcentaje: porcentaje, ganancia: ganancia, pventa: pventa, clavefiscal: clavefiscal, idproveedor: idproveedor, imagen: imagen, chinventario: chinventario, cantidad: cantidad, insert: 'f'},
            success: function (datos) {
                var texto = datos.toString();
                var bandera = texto.substring(0, 1);
                var res = texto.substring(1, 1000);
                if (bandera == '0') {
                    alertify.error(res);
                } else {
                    //loadView('listafactura');
                    //alertify.success('cliente registrado')
                    tablaProductos();
                   
                    $("#nuevo-producto").modal('hide');
                    
                }
                cargandoHide();
            }
        });
    }
}

function aucompletarRegimen() {
    $('#regfiscal-cliente').autocomplete({
        source: "com.sine.enlace/enlaceautocompletar.php?transaccion=regimenfiscal",
        select: function (event, ui) {
            var a = ui.item.value;
        }
    });
}

function aucompletarCatalogo() {
    $('#clave-fiscal').autocomplete({
        source: "com.sine.enlace/enlaceautocompletar.php?transaccion=catfiscal",
        appendTo: "#nuevo-producto",
        select: function (event, ui) {
            var a = ui.item.value;
            var id = ui.item.id;
        }
    });
}

function aucompletarUnidad() {
    $('#clave-unidad').autocomplete({
        source: "com.sine.enlace/enlaceautocompletar.php?transaccion=catunidad",
        appendTo: "#nuevo-producto",
        select: function (event, ui) {

            var a = ui.item.value;
            var id = ui.item.id;
        }
    });
}

function autocompletarCliente() {
    
    if ($("#nombre-cliente").val() == '') {
        $("#id-cliente").val('0');
    }
    $('#nombre-cliente').autocomplete({
        source: "com.sine.enlace/enlaceautocompletar.php?transaccion=nombrecliente",
        select: function (event, ui) {
            var a = ui.item.value;
            var id = ui.item.id;
            var rfc = ui.item.rfc;
            var razon = ui.item.razon;
            var regfiscal = ui.item.regfiscal;
            var codpostal = ui.item.codpostal;
            var direccion = ui.item.direccion;

            $("#id-cliente").val(id);
            $("#rfc-cliente").val(rfc);
            $("#razon-cliente").val(razon);
            $("#regfiscal-cliente").val(regfiscal);
            $("#cp-cliente").val(codpostal);
            $("#direccion-cliente").val(direccion);
        }
    });
}

function calcularImporteEditar() {
    var cantidad = $("#editar-cantidad").val() || '';
    var precio = $("#editar-precio").val() || '';
    
    var importe = parseFloat(cantidad) * parseFloat(precio);
    $("#editar-totuni").val(importe);
    calcularDescuentoEditar();
}

function calcularDescuentoEditar() {
    var pordesc = $("#editar-descuento").val() || '0';
    var importe = $("#editar-totuni").val() || '0';

    var descuento = parseFloat(importe) * (parseFloat(pordesc) / 100);
    var subtotal = (parseFloat(importe) - parseFloat(descuento));
    var traslados = 0;
    var retencion = 0;

    $.each($("input[name='chtrasedit']:checked"), function () {
        var tasa = $(this).val();
        traslados += parseFloat(subtotal) * parseFloat(tasa);
    });

    $.each($("input[name='chretedit']:checked"), function () {
        var tasa = $(this).val();
        retencion += parseFloat(subtotal) * parseFloat(tasa);
    });
    var total = (parseFloat(subtotal) + parseFloat(traslados)) - parseFloat(retencion);
    $("#editar-impdesc").val(descuento);
    $("#editar-total").val(total);
}

function autocompletarCFiscal() {
    $('#editar-cfiscal').autocomplete({
        source: "com.sine.enlace/enlaceautocompletar.php?transaccion=catfiscal",
        appendTo: "#editar-producto",
        select: function (event, ui) {
            var a = ui.item.value;
            var id = ui.item.id;
        }
    });
}

function autocompletarCUnidad() {
    $('#editar-cunidad').autocomplete({
        source: "com.sine.enlace/enlaceautocompletar.php?transaccion=catunidad",
        appendTo: "#editar-producto",
        select: function (event, ui) {

            var a = ui.item.value;
            var id = ui.item.id;
        }
    });
}

function editarConcepto(idtmp) {
    $.ajax({
        url: "com.sine.enlace/enlacefactura.php",
        type: "POST",
        data: {transaccion: "editarconcepto", idtmp: idtmp},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
            } else {
                setValoresEditarConcepto(datos);
            }
            cargandoHide();
        }
    });
}

function setValoresEditarConcepto(datos) {
    var array = datos.split("</tr>");
    var idtmp = array[0];
    var nombre = array[1];
    var cantidad = array[2];
    var precio = array[3];
    var totunitario = array[4];
    var descuento = array[5];
    var impdescuento = array[6];
    var total = array[7];
    var observaciones = array[8];
    var clvfiscal = array[9];
    var clvunidad = array[10]
    var traslados = array[11];
    var retencion = array[12];

    $("#editar-idtmp").val(idtmp);
    $("#editar-descripcion").val(nombre);
    $("#editar-cfiscal").val(clvfiscal);
    $("#editar-cunidad").val(clvunidad);
    $("#editar-cantidad").val(cantidad);
    $("#editar-precio").val(precio);
    $("#editar-totuni").val(totunitario);
    $("#editar-descuento").val(descuento);
    $("#editar-impdesc").val(impdescuento);
    $("#editar-traslados").html(traslados);
    $("#editar-retencion").html(retencion);
    $("#editar-total").val(Math.floor(total * 100) / 100);
    $("#editar-observaciones").val(observaciones);
}

function actualizarConceptoFactura() {
    var idtmp = $("#editar-idtmp").val();
    var descripcion = $("#editar-descripcion").val();
    var clvfiscal = $("#editar-cfiscal").val();
    var clvunidad = $("#editar-cunidad").val();
    var cantidad = $("#editar-cantidad").val();
    var precio = $("#editar-precio").val();
    var totalunitario = $("#editar-totuni").val();
    var descuento = $("#editar-descuento").val();
    var impdescuento = $("#editar-impdesc").val();
    var total = $("#editar-total").val();
    var observaciones = $("#editar-observaciones").val();

    var traslados = [];
    $.each($("input[name='chtrasedit']:checked"), function () {
        traslados.push(0 + "-" + $(this).val() + "-" + $(this).attr("data-impuesto"));
    });

    var retenciones = [];
    $.each($("input[name='chretedit']:checked"), function () {
        retenciones.push(0 + "-" + $(this).val() + "-" + $(this).attr("data-impuesto"));
    });
    var idtraslados = traslados.join("<impuesto>");
    var idretencion = retenciones.join("<impuesto>");

    if (isnEmpty(idtmp, "editar-idtmp") && isnEmpty(descripcion, "editar-descripcion") && isnEmpty(clvfiscal, "editar-cfiscal") && isnEmpty(clvunidad, "editar-cunidad") && isPositive(cantidad, "editar-cantidad") && isPositive(precio, "editar-precio") && isPositive(descuento, "editar-descuento")) {
        $.ajax({
            url: "com.sine.enlace/enlacefactura.php",
            type: "POST",
            data: {transaccion: "actualizarconcepto", idtmp: idtmp, descripcion: descripcion, clvfiscal: clvfiscal, clvunidad: clvunidad, cantidad: cantidad, precio: precio, totalunitario: totalunitario, descuento: descuento, impdescuento: impdescuento, total: total, observaciones: observaciones, idtraslados: idtraslados, idretencion: idretencion},
            success: function (datos) {
                var texto = datos.toString();
                var bandera = texto.substring(0, 1);
                var res = texto.substring(1, 1000);
                if (bandera == '0') {
                    alertify.error(res);
                } else {
                    tablaProductos();
                    $("#editar-producto").modal('hide');
                }
            }
        });

    }
}

function editarProductoFactura(idprod, idtmp) {
    $.ajax({
        url: "com.sine.enlace/enlaceproducto.php",
        type: "POST",
        data: {transaccion: "editarproducto", idproducto: idprod},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
            } else {
                $("#codigo-producto").val('');
                $("#producto").val('');
                $("#tipo").val('');
                $("#cantidad").val('');
                $("#clave-unidad").val('');
                $("#descripcion").val('');
                $("#pcompra").val('');
                $("#porganancia").val('')
                $("#ganancia").val('');
                $("#pventa").val('');
                $("#clave-fiscal").val('');
                $("#id-proveedor").val('');
                $("#filename").val('');
                $("#imgactualizar").val('');
                setValoresEditarProducto(datos, idtmp);
            }
            cargandoHide();
        }
    });
}

function setValoresEditarProducto(datos, idtmp) {
    $("#muestraimagen").html('');
    var array = datos.split("</tr>");
    var idproducto = array[0];
    var codigo = array[1];
    var nombre = array[2];
    var clvunidad = array[3];
    var descripcion_unidad = array[4];
    var unidad = clvunidad + "-" + descripcion_unidad;
    var descripcion_producto = array[5];
    var pcompra = array[6];
    var porcentaje = array[7];
    var ganancia = array[8];
    var pventa = array[9];
    var tipo = array[10];
    var clvfiscal = array[11];
    var descripcionfiscal = array[12];
    var clavefiscal = clvfiscal + "-" + descripcionfiscal;
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
    if (idproveedor != '0') {
        $("#id-proveedor").val(idproveedor);
    }
    $("#filename").val(imagen);
    $("#imgactualizar").val(imagen);

    if (imagen !== '') {
        $("#muestraimagen").html(img);
    }

    $("#btn-form-producto-factura").attr("onclick", "actualizarProductoFactura(" + idproducto + "," + idtmp + ");");
}

function actualizarProductoFactura(idproducto, idtmp) {
    var codproducto = $("#codigo-producto").val();
    var producto = $("#producto").val();
    var descripcion = $("#descripcion").val();
    var clavefiscal = $("#clave-fiscal").val();
    var tipo = $("#tipo").val();
    var unidad = $("#clave-unidad").val();
    var pcompra = $("#pcompra").val();
    var porcentaje = $("#porganancia").val();
    var ganancia = $("#ganancia").val();
    var pventa = $("#pventa").val();
    var idproveedor = $("#id-proveedor").val();
    var imagen = $('#filename').val();
    var imgactualizar = $("#imgactualizar").val();
    var chinventario = 0;
    var cantidad = $("#cantidad").val();
    if ($("#chinventario").prop('checked')) {
        chinventario = 1;
    }

    if (isnEmpty(codproducto, "codigo-producto") && isnEmpty(producto, "producto") && isList(clavefiscal, "clave-fiscal") && isnEmpty(tipo, "tipo") && isListUnit(unidad, "clave-unidad") && isPositive(porcentaje, "porganancia") && isPositive(ganancia, "ganancia") && isPositive(pventa, "pventa")) {
        cargandoHide();
        cargandoShow();
        $.ajax({
            url: "com.sine.enlace/enlaceproducto.php",
            type: "POST",
            data: {transaccion: "actualizarproducto", idproducto: idproducto, idtmp: idtmp, codproducto: codproducto, producto: producto, tipo: tipo, unidad: unidad, descripcion: descripcion, pcompra: pcompra, porcentaje: porcentaje, ganancia: ganancia, pventa: pventa, clavefiscal: clavefiscal, idproveedor: idproveedor, imagen: imagen, imgactualizar: imgactualizar, chinventario: chinventario, cantidad: cantidad, insert: 'f'},
            success: function (datos) {
                var texto = datos.toString();
                var bandera = texto.substring(0, 1);
                var res = texto.substring(1, 1000);
                if (bandera == '0') {
                    alertify.error(res);
                } else {
                    tablaProductos();
                    $("#nuevo-producto").modal('hide');
                }
                cargandoHide();
            }
        });
    }
}

function getcheckFactura() {
    var val = 0;
    if ($("#chfirma").prop('checked')) {
        val = 1;
    }
    alert(val);
}

function setIDTMP(id, observaciones) {
    var txtbd = observaciones.replace(new RegExp("<ent>", 'g'), '\n');
    $("#idtmp").val(id);
    $("#observaciones-producto").val(txtbd);
}

function agregarObservaciones() {
    var idtmp = $("#idtmp").val();
    var observaciones = $("#observaciones-producto").val();
    var txtbd = observaciones.replace(new RegExp("\n", 'g'), '<ent>');
    var uuid = $("#uuidfactura").val();
    if (!uuid) {
        uuid = "";
    }
    $.ajax({
        url: "com.sine.enlace/enlacefactura.php",
        type: "POST",
        data: {transaccion: "agregarobservaciones", idtmp: idtmp, observaciones: txtbd, uuid: uuid},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
            } else {
                tablaProductos(uuid);
            }
            cargandoHide();
        }
    });
}

function checkMetodopago() {
    var idmetodopago = $("#id-metodo-pago").val();
    if (idmetodopago == '2') {
        $('#formapago6').prop('selected', true);
        $("#id-forma-pago").prop('disabled', true);
    } else {
        $('#formapago6').removeAttr('selected');
        $("#id-forma-pago").removeAttr('disabled');
    }
}

function getComprobante() {
    var tcomprobante = $("#tipo-comprobante").val();
    if (tcomprobante == '1') {
        $("#clientediv").attr('hidden', false);
        $("#btnprod").attr('hidden', false);
        $("#proveedordiv").attr('hidden', true);
        $("#añadirproducto").attr('hidden', true);
    } else if (tcomprobante == '2') {
        $("#proveedordiv").attr('hidden', false);
        $("#añadirproducto").attr('hidden', false);
        $("#clientediv").attr('hidden', true);
        $("#btnprod").attr('hidden', true);
    } else {
        $("#proveedordiv").attr('hidden', true);
        $("#añadirproducto").attr('hidden', true);
        $("#clientediv").attr('hidden', true);
        $("#btnprod").attr('hidden', true);
    }
}

function calcularImporte(idproducto) {
    var cantidad = $("#cantidad_" + idproducto).val() || '0';
    var precio = $("#pventa_" + idproducto).val() || '0';
    var importe = parseFloat(cantidad) * parseFloat(precio);
    $("#importe_" + idproducto).val(importe);
    calcularDescuento(idproducto);
}

function calcularDescuento(idproducto) {
    var pordesc = $("#pordescuento_" + idproducto).val() || '0';
    var importe = $("#importe_" + idproducto).val() || '0';
    
    var descuento = parseFloat(importe) * (parseFloat(pordesc) / 100);
    var subtotal = (parseFloat(importe) - parseFloat(descuento));
    var traslados = 0;
    var retencion = 0;

    $.each($("input[name='chtraslado" + idproducto + "']:checked"), function () {
        var tasa = $(this).val();
        traslados += parseFloat(subtotal) * parseFloat(tasa);
    });

    $.each($("input[name='chretencion" + idproducto + "']:checked"), function () {
        var tasa = $(this).val();
        retencion += parseFloat(subtotal) * parseFloat(tasa);
    });

    var total = (parseFloat(subtotal) + parseFloat(traslados)) - parseFloat(retencion);

    $("#descuento_" + idproducto).val(descuento);
    $("#total_" + idproducto).val(total);
}

function showTelefono(idfactura) {
    cargandoHide();
    cargandoShow();
    $("#idfacturawp").val(idfactura);
    $.ajax({
        url: "com.sine.enlace/enlacefactura.php",
        type: "POST",
        data: {transaccion: "gettelefono", idfactura: idfactura},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
            } else {
                $("#wpnumber").val(datos);
            }
        }
    });
    cargandoHide();
}

function getCorreos(idfactura) {
    $.ajax({
        url: "com.sine.enlace/enlacefactura.php",
        type: "POST",
        data: {transaccion: "getcorreos", idfactura: idfactura},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
            } else {
                var array = datos.split("<corte>");
                var correo1 = array[0];
                var correo2 = array[1];
                var correo3 = array[2];
                var correo4 = array[3];
                var correo5 = array[4];
                var correo6 = array[5];

                $("#correo1").val(correo1);
                $("#correo2").val(correo2);
                $("#correo3").val(correo3);
                $("#correo4").val(correo4);
                $("#correo5").val(correo5);
                $("#correo6").val(correo6);
            }
        }
    });
}

function showCorreos(idfactura) {
    cargandoHide();
    cargandoShow();
    $("#idfacturaenvio").val(idfactura);
    $("#correo1").val('');
    $("#correo2").val('');
    $("#correo3").val('');
    $("#correo4").val('');
    $("#correo5").val('');
    $("#correo6").val('');
    getCorreos(idfactura);
    $("#chcorreo1").prop('checked', false);
    $("#chcorreo2").prop('checked', true);
    $("#chcorreo3").prop('checked', false);
    $("#chcorreo4").prop('checked', false);
    $("#chcorreo5").prop('checked', false);
    $("#chcorreo6").prop('checked', false);
    cargandoHide();
}

function registrarPago(idfactura) {
    cargandoHide();
    cargandoShow();
    $.ajax({
        url: "com.sine.enlace/enlacefactura.php",
        type: "POST",
        data: {transaccion: "getdatospago", idfactura: idfactura},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
                cargandoHide();
            } else {
                $('.list-element').removeClass("menu-active");
                $('.marker').removeClass("marker-active");
                $('#pago-menu').addClass("menu-active");
                $('#pago-menu').children('div.marker').addClass("marker-active");
                window.setTimeout("loadView('pago')", 300);
                window.setTimeout("setvaloresRegistrarPago('" + datos + "')", 800);
            }
        }
    });
}

function setvaloresRegistrarPago(datos) {
    var array = datos.split("</tr>");
    var idfactura = array[0];
    var foliofactura = array[1];
    var idcliente = array[2];
    var nombrecliente = array[3];
    var rfccliente = array[4];
    var rzcliente = array[5];
    var cpreceptor = array[6];
    var regfiscal = array[7];
    var iddatosfacturacion = array[8];
    var nombrecontribuyente = array[9];
    var idformapago = array[10];
    var idmoneda = array[11];
    var tcambio = array[12];

    $("#id-cliente").val(idcliente);
    $("#nombre-cliente").val(nombrecliente);
    $("#rfc-cliente").val(rfccliente);
    $("#razon-cliente").val(rzcliente);
    $("#regfiscal-cliente").val(regfiscal);
    $("#cp-cliente").val(cpreceptor);
    $("#option-default-datos").val(iddatosfacturacion);
    $("#option-default-datos").text(nombrecontribuyente);
    
    $.ajax({
        url: "com.sine.enlace/enlacepago.php",
        type: "POST",
        data: {transaccion: "expcomplementos", idformapago:idformapago, idmoneda:idmoneda, tcambio:tcambio, idfactura:idfactura, foliofactura:foliofactura},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
            } else {
                var array = datos.split("<comp>");
                comp = array.length;
                for (i = 0; i < array.length; i++) {
                    var comps = array[i].split("<cut>");
                    $("#tabs").append(comps[0]);
                    $("#complementos").append(comps[1]);
                    var tag1 = comps[2];

                    $(".sub-div").hide();
                    $(".tab-pago").removeClass("sub-tab-active");

                    var first = $("#tabs").find('.tab-pago:first').attr("data-tab");
                    if (first) {
                        $("#complemento-" + first).show();
                        $("#tab-" + first).addClass("sub-tab-active");
                    }
                    tablaRowCFDI(tag1);
                    loadFactura(idfactura, 'f', tag1);
                }
            }
        }
    });
    loadFolioPago();
    cargandoHide();
}

function tablaPagos(idfactura, estado) {
    $.ajax({
        url: "com.sine.enlace/enlacefactura.php",
        type: "POST",
        data: {transaccion: "pagosfactura", idfactura: idfactura, estado: estado},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
            } else {

                var array = datos.split("<corte>");
                var p2 = array[1];
                $("#pagostabla").html(p2);
            }
        }
    });
}

function imprimirpago(idpago) {
    cargandoHide();
    cargandoShow();
    VentanaCentrada('./com.sine.imprimir/imprimirpago.php?pago=' + idpago, 'Pago', '', '1024', '768', 'true');
    cargandoHide();
}

function insertarFactura() {
    var folio = $("#folio").val();
    var fecha_creacion = $("#fecha-creacion").val();
    var idcliente = $("#id-cliente").val() || '0';
    var cliente = $("#nombre-cliente").val();
    var rfccliente = $("#rfc-cliente").val();
    var razoncliente = $("#razon-cliente").val();
    var regfiscal = $("#regfiscal-cliente").val();
    var dircliente = $("#direccion-cliente").val();
    var codpostal = $("#cp-cliente").val();
    var tipoComprobante = $("#tipo-comprobante").val();
    var idformapago = $("#id-forma-pago").val();
    var idmetodopago = $("#id-metodo-pago").val();
    var idmoneda = $("#id-moneda").val();
    var tcambio = $("#tipo-cambio").val();
    var iduso = $("#id-uso").val();
    var iddatosF = $("#datos-facturacion").val();
    var periodicidad = $("#periodicidad-factura").val();
    var mesperiodo = $("#mes-periodo").val();
    var anhoperiodo = $("#anho-periodo").val();
    var idcotizacion = $("#idcotizacion").val() || '0';
    var cfdis = 0;
    var chfirma = 0;
    if ($("#chfirma").prop('checked')) {
        chfirma = 1;
    }
    if ($("#cfdirel").hasClass('in')) {
        alert(cfdis);
        cfdis = 1;
    }

    if (isnEmpty(iddatosF, "datos-facturacion") && isnEmpty(rfccliente, "rfc-cliente") && isnEmpty(razoncliente, "razon-cliente") && isnEmpty(regfiscal, "regfiscal-cliente") && isnEmpty(codpostal, "cp-cliente") && isnEmpty(tipoComprobante, "tipo-comprobante") && isnEmpty(idformapago, "id-forma-pago") && isnEmpty(idmetodopago, "id-metodo-pago") && isnEmpty(idmoneda, "id-moneda") && isnEmpty(tcambio, "tipo-cambio") && isnEmpty(iduso, "id-uso")) {
        $.ajax({
            url: "com.sine.enlace/enlacefactura.php",
            type: "POST",
            data: {transaccion: "insertarfactura", folio: folio, fecha_creacion: fecha_creacion, idcliente: idcliente, cliente:cliente, rfccliente: rfccliente, razoncliente: razoncliente, regfiscal: regfiscal, dircliente: dircliente, codpostal: codpostal, idformapago: idformapago, idmetodopago: idmetodopago, idmoneda: idmoneda, tcambio: tcambio, iduso: iduso, tipocomprobante: tipoComprobante, iddatosF: iddatosF, chfirma: chfirma, cfdis: cfdis, idcotizacion: idcotizacion, periodicidad: periodicidad, mesperiodo: mesperiodo, anhoperiodo: anhoperiodo},
            success: function (datos) {
                var texto = datos.toString();
                var bandera = texto.substring(0, 1);
                var res = texto.substring(1, 1000);
                if (bandera == '0') {
                    alertify.error(res);
                } else {
                    alertify.success('Factura creada');
                    loadView('listafactura');
                }
                cargandoHide();
            }
        });
    }

}

function buscarProducto(pag = "") {
    if (pag != "") {
        cargandoHide();
        cargandoShow();
    }
    var NOM = $("#buscar-producto").val();
    var numreg = $("#num-reg").val();
    $.ajax({
        url: "com.sine.enlace/enlacefactura.php",
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
                var array = datos.split("<pag>");
                var table = array[0];
                var pag = array[1];
                $("#body-lista-productos-factura").html(table);
                $("#pagination").html(pag);
            }
            cargandoHide();
        }
    });
}

function filtrarProducto(pag = "") {
    cargandoHide();
    cargandoShow();
    var NOM = $("#buscar-producto").val();
    var numreg = $("#num-reg").val();
    $("#body-lista-productos-factura").append('');
    $.ajax({
        url: "com.sine.enlace/enlacefactura.php",
        type: "POST",
        data: {transaccion: "filtrarproducto", NOM: NOM, pag: pag, numreg: numreg},
        success: function (datos) {
            //alert(datos);
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
                cargandoHide();
            } else {
                var array = datos.split("<pag>");
                var table = array[0];
                var pag = array[1];
                $("#body-lista-productos-factura").append(table);
                $("#pagination").append(pag);
                cargandoHide();
                //$("#tabla-agregar-prod").DataTable({language: DATA_TABLE_ES});
            }
        }
    });
}

function editarFactura(idFactura) {
    cargandoHide();
    cargandoShow();
    $.ajax({
        url: "com.sine.enlace/enlacefactura.php",
        type: "POST",
        data: {transaccion: "editarfactura", idFactura: idFactura},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
            } else {
                loadView('factura');
                window.setTimeout("setValoresEditarFactura('" + datos + "')", 900);
            }
        }
    });
}

function setValoresEditarFactura(datos) {
    changeText("#contenedor-titulo-form-factura", "Editar Factura");
    changeText("#btn-form-factura", "Guardar cambios <span class='fas fa-save'></span>");

    var array = datos.split("</tr>");
    var idfactura = array[0];
    var serie = array[1];
    var letra = array[2];
    var folio = array[3];
    var fechacreacion = array[4];
    var idcliente = array[5];
    var cliente = array[6];
    var rfccliente = array[7];
    var rzreceptor = array[8];
    var cpreceptor = array[9];
    var regfiscalrec = array[10];
    var idmetodo_pago = array[11];
    var idmoneda = array[12];
    var iduso_cfdi = array[13];
    var idforma_pago = array[14];
    var idtipo_comprobante = array[15];
    var status = array[16];
    var uuid = array[17];
    var iddatos = array[18];
    var chfirmar = array[19];
    var cfdisrel = array[20];
    var tcambio = array[21];
    var rfcemisor = array[22];
    var rzsocial = array[23];
    var clvreg = array[24];
    var regimen = array[25];
    var tag = array[26];
    var dirreceptor = array[27];
    var periodoG = array[28];
    var mesperiodo = array[29];
    var anhoperiodo = array[30];
	var cpemisor = array[31];

    var arfecha = fechacreacion.split("-");
    var fechacreacion = arfecha[2] + "/" + arfecha[1] + "/" + arfecha[0];

    if (uuid != "") {
        $("#not-timbre").html("<div class='col-md-12'><label class='mark-required text-justify'>*</label> <label class='label-required text-justify'> Esta factura ya ha sido timbrada, por lo que solo puedes editar la direccion del cliente, las observaciones de productos y modificar la firma del contribuyente.</label></div>");
        $("#folio").attr("disabled", true);
        $("#btn-agregar-cfdi").attr("disabled", true);
        $("#nombre-cliente").attr("disabled", true);
        $("#rfc-cliente").attr("disabled", true);
        $("#razon-cliente").attr("disabled", true);
        $("#regfiscal-cliente").attr("disabled", true);
        $("#cp-cliente").attr("disabled", true);
        $("#tipo-comprobante").attr("disabled", true);
        $("#id-forma-pago").attr("disabled", true);
        $("#id-metodo-pago").attr("disabled", true);
        $("#id-moneda").attr("disabled", true);
        $("#id-uso").attr("disabled", true);
        $("#datos-facturacion").attr("disabled", true);
        $("#btn-nuevo-producto").attr("disabled", true);
        $("#btn-agregar-productos").attr("disabled", true);
        $("#periodicidad-factura").attr("disabled", true);
        $("#mes-periodo").attr('disabled', true);
        $("#anho-periodo").attr('disabled', true);
    	$("#rfc-emisor").val(rfcemisor);
        $("#razon-emisor").val(rzsocial);
        $("#regimen-emisor").val(clvreg + "-" + regimen);
        $("#cp-emisor").val(cpemisor);
    } else {
    	loadDatosFactura(iddatos);
        if (idmoneda != "1") {
            $("#tipo-cambio").removeAttr('disabled');
        } else {
            $("#tipo-cambio").attr('disabled', true);
        }
    }
	
    if (cfdisrel == '1') {
    	
        $.ajax({
            url: "com.sine.enlace/enlacefactura.php",
            type: "POST",
            data: {transaccion: "cfdisrelacionados", tag: tag, uuid: uuid},
            success: function (datos) {
                var texto = datos.toString();
                var bandera = texto.substring(0, 1);
                var res = texto.substring(1, 1000);
                if (bandera == '0') {
                    alertify.error(res);
                } else {
                    var array = datos.split("<corte>");
                    var p1 = array[0];
                    var p2 = array[1];
                    $("#body-lista-cfdi").html(p2);
                    $("#cfdirel").addClass('in');
                }
            }
        });
    }

    $.ajax({
        url: "com.sine.enlace/enlacefactura.php",
        type: "POST",
        data: {transaccion: "prodfactura", tag: tag},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
            } else {
                //alert(datos);
                tablaProductos(uuid);
            }
        }
    });

    loadOpcionesFolios('0', serie, letra+folio);
    loadOpcionesFacturacion(iddatos);
    $("#rfc-emisor").val(rfcemisor);
    $("#razon-emisor").val(rzsocial);
    $("#regimen-emisor").val(clvreg + "-" + regimen);
    $("#fecha-creacion").val(fechacreacion);
    $("#id-cliente").val(idcliente);
    $("#nombre-cliente").val(cliente);
    $("#rfc-cliente").val(rfccliente);
    $("#razon-cliente").val(rzreceptor);
    $("#regfiscal-cliente").val(regfiscalrec);
    $("#direccion-cliente").val(dirreceptor);
    $("#cp-cliente").val(cpreceptor);
    loadOpcionesFormaPago(idforma_pago);
    loadOpcionesMetodoPago(idmetodo_pago);
    loadOpcionesMoneda(idmoneda);
    $("#tipo-cambio").val(tcambio);
    loadOpcionesUsoCFDI(iduso_cfdi);
    loadOpcionesComprobante(idtipo_comprobante);
    opcionesPeriodoGlobal(periodoG);
    opcionesMeses(mesperiodo);
    if (anhoperiodo != "") {
        $("#option-default-anho-periodo").val(anhoperiodo);
        $("#option-default-anho-periodo").text(anhoperiodo);
    }

    if (chfirmar == '1') {
        $("#chfirma").attr('checked', true);
    }

    $("#form-factura").append("<input type='hidden' id='uuidfactura' name='uuidfactura' value='" + uuid + "'/>");
    $("#form-factura").append("<input type='hidden' id='tagfactura' name='tagfactura' value='" + tag + "'/>");
    $("#btn-form-factura").attr("onclick", "actualizarFactura(" + idfactura + ");");
    cargandoHide();

}

function actualizarFactura(idfactura) {
    var folio = $("#folio").val();
    var iddatosF = $("#datos-facturacion").val();
    var idcliente = $("#id-cliente").val();
    var cliente = $("#nombre-cliente").val();
    var rfccliente = $("#rfc-cliente").val();
    var razoncliente = $("#razon-cliente").val();
    var regfiscal = $("#regfiscal-cliente").val();
    var dircliente = $("#direccion-cliente").val();
    var codpostal = $("#cp-cliente").val();
    var tipoComprobante = $("#tipo-comprobante").val();
    var idmetodopago = $("#id-metodo-pago").val();
    var idformapago = $("#id-forma-pago").val();
    var idmoneda = $("#id-moneda").val();
    var tcambio = $("#tipo-cambio").val();
    var iduso = $("#id-uso").val();
    var periodicidad = $("#periodicidad-factura").val();
    var mesperiodo = $("#mes-periodo").val();
    var anhoperiodo = $("#anho-periodo").val();
    var tag = $("#tagfactura").val();
    var chfirmar = 0;
    var cfdis = 0;
    if ($("#chfirma").prop('checked')) {
        chfirmar = 1;
    }

    if ($("#cfdirel").hasClass('in')) {
        cfdis = 1;
    }

    if (isnEmpty(iddatosF, "datos-facturacion") && isnEmpty(rfccliente, "rfc-cliente") && isnEmpty(razoncliente, "razon-cliente") && isnEmpty(regfiscal, "regfiscal-cliente") && isnEmpty(codpostal, "cp-cliente") && isnEmpty(tipoComprobante, "tipo-comprobante") && isnEmpty(idformapago, "id-forma-pago") && isnEmpty(idmetodopago, "id-metodo-pago") && isnEmpty(idmoneda, "id-moneda") && isnEmpty(tcambio, "tipo-cambio") && isnEmpty(iduso, "id-uso")) {
        $.ajax({
            url: "com.sine.enlace/enlacefactura.php",
            type: "POST",
            data: {transaccion: "actualizarFactura", folio: folio, idcliente: idcliente, cliente:cliente, rfccliente: rfccliente, razoncliente: razoncliente, regfiscal: regfiscal, dircliente: dircliente, codpostal: codpostal, idformapago: idformapago, idmetodopago: idmetodopago, idmoneda: idmoneda, tcambio: tcambio, iduso: iduso, tipocomprobante: tipoComprobante, idfactura: idfactura, iddatosF: iddatosF, chfirmar: chfirmar, cfdis: cfdis, periodicidad: periodicidad, mesperiodo: mesperiodo, anhoperiodo: anhoperiodo, tag: tag},
            success: function (datos) {
                var texto = datos.toString();
                var bandera = texto.substring(0, 1);
                var res = texto.substring(1, 1000);
                if (bandera == '0') {
                    alertify.error(res);
                } else {
                    //alert(datos);
                    alertify.success('Factura Actualizada');
                    loadView('listafactura');
                }
                cargandoHide();
            }
        });
    }
}

function eliminarFactura(idFactura) {
    alertify.confirm("Esta seguro que desea eliminar esta factura?", function () {
        cargandoHide();
        cargandoShow();
        $.ajax({
            url: "com.sine.enlace/enlacefactura.php",
            type: "POST",
            data: {transaccion: "eliminarfactura", idfactura: idFactura},
            success: function (datos) {
                var texto = datos.toString();
                var bandera = texto.substring(0, 1);
                var res = texto.substring(1, 1000);
                if (bandera == '0') {
                    alertify.error(res);
                } else {
                    alertify.success('Se elimino correctamente una factura')
                    filtrarFolio();
                }
                cargandoHide();
            }
        });
    }).set({title: "Q-ik"});
}

function copiarFactura(idFactura) {
    cargandoHide();
    cargandoShow();
    $.ajax({
        url: "com.sine.enlace/enlacefactura.php",
        type: "POST",
        data: {transaccion: "editarfactura", idFactura: idFactura},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
            } else {
                loadView('factura');
                window.setTimeout("setValoresCopiarFactura('" + datos + "')", 600);
                window.setTimeout("cargandoHide()", 750);
            }
        }
    });
}

function setValoresCopiarFactura(datos) {
    var array = datos.split("</tr>");
    var idfactura = array[0];
    var serie = array[1];
    var letra = array[2];
    var folio = array[3];
    var fechacreacion = array[4];
    var idcliente = array[5];
    var cliente = array[6];
    var rfccliente = array[7];
    var rzreceptor = array[8];
    var cpreceptor = array[9];
    var regfiscalrec = array[10];
    var idmetodo_pago = array[11];
    var idmoneda = array[12];
    var iduso_cfdi = array[13];
    var idforma_pago = array[14];
    var idtipo_comprobante = array[15];
    var status = array[16];
    var uuid = "";
    var iddatos = array[18];
    var chfirmar = array[19];
    var cfdisrel = array[20];
    var tcambio = array[21];
    var rfcemisor = array[22];
    var rzsocial = array[23];
    var clvreg = array[24];
    var regimen = array[25];
    var tag = array[26];
    var dirreceptor = array[27];
    var periodoG = array[28];
    var mesperiodo = array[29];
    var anhoperiodo = array[30];

    if (cfdisrel == '1') {
        $.ajax({
            url: "com.sine.enlace/enlacefactura.php",
            type: "POST",
            data: {transaccion: "cfdisrelacionados", tag: tag, uuid: uuid},
            success: function (datos) {
                var texto = datos.toString();
                var bandera = texto.substring(0, 1);
                var res = texto.substring(1, 1000);
                if (bandera == '0') {
                    alertify.error(res);
                } else {
                    var array = datos.split("<corte>");
                    var p1 = array[0];
                    var p2 = array[1];
                    $("#body-lista-cfdi").html(p2);
                    $("#cfdirel").addClass('in');
                }
            }
        });
    }

    $.ajax({
        url: "com.sine.enlace/enlacefactura.php",
        type: "POST",
        data: {transaccion: "prodfactura", tag: tag},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
            } else {
                //alert(datos);
                tablaProductos();
            }
        }
    });

    loadOpcionesFacturacion(iddatos);
    $("#rfc-emisor").val(rfcemisor);
    $("#razon-emisor").val(rzsocial);
    $("#regimen-emisor").val(clvreg + "-" + regimen);
    $("#fecha-creacion").val(fechacreacion);
    $("#id-cliente").val(idcliente);
    $("#nombre-cliente").val(cliente);
    $("#rfc-cliente").val(rfccliente);
    $("#razon-cliente").val(rzreceptor);
    $("#regfiscal-cliente").val(regfiscalrec);
    $("#direccion-cliente").val(dirreceptor);
    $("#cp-cliente").val(cpreceptor);
    loadOpcionesFormaPago(idforma_pago);
    loadOpcionesMetodoPago(idmetodo_pago);
    loadOpcionesMoneda(idmoneda);
    $("#tipo-cambio").val(tcambio);
    loadOpcionesUsoCFDI(iduso_cfdi);
    loadOpcionesComprobante(idtipo_comprobante);
    opcionesPeriodoGlobal(periodoG);
    opcionesMeses(mesperiodo);
    if (anhoperiodo != "") {
        $("#option-default-anho-periodo").val(anhoperiodo);
        $("#option-default-anho-periodo").text(anhoperiodo);
    }

    if (chfirmar == '1') {
        $("#chfirma").attr('checked', true);
    }

    loadDatosFactura();
    getTipoCambio();
}

function checkFolios() {
    var comprobante = $("#tipo-comprobante").val();
    if (comprobante == '1' || comprobante == '2') {
        $.ajax({
            url: 'com.sine.enlace/enlaceopcion.php',
            type: 'POST',
            data: {transaccion: 'opcionesfolio', id: comprobante},
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
}

function loadDatosFactura(iddatos = "") {
    cargandoShow();
    if(iddatos == ""){
        iddatos = $("#datos-facturacion").val();
    }
    $.ajax({
        url: 'com.sine.enlace/enlacefactura.php',
        type: 'POST',
        data: {transaccion: 'emisor', iddatos: iddatos},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            if (bandera == 0) {
                alertify.error(res);
            } else {
                //alert(datos);
                var array = datos.split("</tr>");
                var rfc = array[0];
                var razon = array[1];
                var clvreg = array[2];
                var regimen = array[3];
                var codpos = array[4];

                $("#rfc-emisor").val(rfc);
                $("#razon-emisor").val(razon);
                $("#regimen-emisor").val(clvreg + "-" + regimen);
                $("#cp-emisor").val(codpos);
            }

            cargandoHide();
        }
    });
}

function loadFecha() {
    ////cargandoShow();
    $.ajax({
        url: 'com.sine.enlace/enlacefactura.php',
        type: 'POST',
        data: {transaccion: 'fecha'},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            if (bandera == '') {
                alertify.error(res);
            } else {
                //alert(datos);
                $("#fecha-creacion").val(datos);
            }
            ////cargandoHide();
        }
    });
}

function loadDocumento() {
    ////cargandoShow();
    $.ajax({
        url: 'com.sine.enlace/enlacecarta.php',
        type: 'POST',
        data: {transaccion: 'documento'},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            if (bandera == 0) {
                alertify.error(res);
            } else {
                //alert(datos);
                $("#documento").val(datos);
            }
            ////cargandoHide();
        }
    });
}
function buscarFactura(pag = "") {
    var REF = $("#buscar-factura").val();
    var numreg = $("#num-reg").val();

    $.ajax({
        url: "com.sine.enlace/enlacefactura.php",
        type: "POST",
        data: {transaccion: "filtrarfolio", pag: pag, REF: REF, numreg: numreg},
        success: function (datos) {
            //alert(datos);
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
            } else {
                $("#body-lista-factura").html(datos);
            }
        }
    });
}

function filtrarFolio(pag = "") {
    cargandoHide();
    cargandoShow();
    var REF = $("#buscar-factura").val();
    var numreg = $("#num-reg").val();

    $.ajax({
        url: "com.sine.enlace/enlacefactura.php",
        type: "POST",
        data: {transaccion: "filtrarfolio", pag: pag, REF: REF, numreg: numreg},
        success: function (datos) {
            //alert(datos);
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
                cargandoHide();
            } else {
                $("#body-lista-factura").html(datos);
                cargandoHide();
            }
        }
    });
}

function agregarProducto(idproducto) {
    var descripcion = $("#prodserv" + idproducto).val();
    var cantidad = $("#cantidad_" + idproducto).val();
    var pventa = $("#pventa_" + idproducto).val();
    var importe = $("#importe_" + idproducto).val();
    var descuento = $("#pordescuento_" + idproducto).val();
    var impdescuento = $("#descuento_" + idproducto).val();
    var total = $("#total_" + idproducto).val();

    var traslados = [];
    $.each($("input[name='chtraslado" + idproducto + "']:checked"), function () {
        traslados.push(0 + "-" + $(this).val() + "-" + $(this).attr("data-impuesto"));
    });

    var retenciones = [];
    $.each($("input[name='chretencion" + idproducto + "']:checked"), function () {
        retenciones.push(0 + "-" + $(this).val() + "-" + $(this).attr("data-impuesto"));
    });

    var idtraslados = traslados.join("<impuesto>");
    var idretencion = retenciones.join("<impuesto>");

    if (isNumber(cantidad, "cantidad_" + idproducto) && isNumber(pventa, "pventa_" + idproducto)) {
        $.ajax({
            url: "com.sine.enlace/enlacefactura.php",
            type: "POST",
            data: {transaccion: "agregarProducto", idproducto: idproducto, descripcion: descripcion, cantidad: cantidad, pventa: pventa, importe: importe, descuento: descuento, impdescuento: impdescuento, total: total, idtraslados: idtraslados, idretencion: idretencion},
            success: function (datos) {
                var texto = datos.toString();
                var bandera = texto.substring(0, 1);
                var res = texto.substring(1, 1000);
                if (bandera == '0') {
                    alertify.error(res);
                } else {
                    tablaProductos();
                }
                cargandoHide();
            }
        });
    }
}

function incrementarCantidad(idtmp) {
    $.ajax({
        url: "com.sine.enlace/enlacefactura.php",
        type: "POST",
        data: {transaccion: "incrementar", idtmp: idtmp},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
            } else {
                tablaProductos();
                cargandoHide();
            }
        }
    });
}

function reducirCantidad(idtmp) {
    $.ajax({
        url: "com.sine.enlace/enlacefactura.php",
        type: "POST",
        data: {transaccion: "reducir", idtmp: idtmp},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
            } else {
                tablaProductos();
                cargandoHide();
            }
        }
    });
}

function setCantidad(idtmp, cant) {
    $("#idcant").val(idtmp);
    $("#cantidad-producto").val(cant);
}

function modificarCantidad() {
    var idtmp = $("#idcant").val();
    var cant = $("#cantidad-producto").val();
    $.ajax({
        url: "com.sine.enlace/enlacefactura.php",
        type: "POST",
        data: {transaccion: "modificartmp", idtmp: idtmp, cant: cant},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
            } else {
                tablaProductos();
                cargandoHide();
            }
        }
    });
}

function eliminar(idtemp, cantidad, idproducto) {
    alertify.confirm("Esta seguro que desea eliminar este producto?", function () {
        cargandoHide();
        cargandoShow();
        $.ajax({
            url: "com.sine.enlace/enlacefactura.php",
            type: "POST",
            data: {transaccion: "eliminar", idtemp: idtemp, cantidad: cantidad, idproducto: idproducto},
            success: function (datos) {
                var texto = datos.toString();
                var bandera = texto.substring(0, 1);
                var res = texto.substring(1, 1000);
                if (bandera == '0') {
                    alertify.error(res);
                    cargandoHide();
                } else {
                    tablaProductos();
                    cargandoHide();
                }
            }
        });
    }).set({title: "Q-ik"});
}

function cancelarFactura() {
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
                loadView('listafactura');
                
            }
        }
    });
    
}

function imprimir_factura(id) {
    cargandoHide();
    cargandoShow();
    VentanaCentrada('./com.sine.imprimir/imprimirfactura.php?factura=' + id, 'Factura', '', '1024', '768', 'true');
    cargandoHide();
}

function timbrarFactura(fid) {
    alertify.confirm("Esta seguro que desea timbrar esta factura?", function () {
        cargandoHide();
        cargandoShow();
        $.ajax({
            url: "com.sine.enlace/enlacefactura.php",
            type: "POST",
            data: {transaccion: "xml", idfactura: fid},
            success: function (datos) {
                var texto = datos.toString();
                var bandera = texto.substring(0, 1);
                var res = texto.substring(0, 1000);
                if (bandera == '0') {
                    alertify.error(res);
                } else {
                    alertify.success('Factura Timbrada');
                    loadView('listafactura');
                }
                cargandoHide();
            }
        });
    }).set({title: "Q-ik"});
}

function setCancelacion(fid) {
    $("#btn-cancelar").attr('onclick', 'cancelarTimbre(' + fid + ')')
}

function checkCancelacion() {
    var motivo = $("#motivo-cancelacion").val();
    if (motivo === '01') {
        $("#div-reemplazo").show('slow');
    } else {
        $("#div-reemplazo").hide('slow');
    }
}

function cancelarTimbre(idfactura) {
    var motivo = $("#motivo-cancelacion").val();
    var reemplazo = "0";
    if (motivo === '01') {
        reemplazo = $("#uuid-reemplazo").val();
    }
    if (isnEmpty(motivo, "motivo-cancelacion") && isnEmpty(reemplazo, "uuid-reemplazo")) {
        alertify.confirm("Esta seguro que desea cancelar esta factura?", function () {
            cargandoHide();
            cargandoShow();
            $.ajax({
                url: "com.sine.enlace/enlacefactura.php",
                type: "POST",
                data: {transaccion: "cancelartimbre", idfactura: idfactura, motivo: motivo, reemplazo: reemplazo},
                success: function (datos) {
                    var texto = datos.toString();
                    var bandera = texto.substring(0, 1);
                    var res = texto.substring(1, 1000);
                    if (bandera == '0') {
                        alertify.error(res);
                    } else {
                        $("#modalcancelar").modal('hide');
                        alertify.success('Factura Cancelada');
                        filtrarFolio();
                    }
                    cargandoHide();
                }
            });
        }).set({title: "Q-ik"});
    }
}

function enviarfactura() {
    var idfactura = $("#idfacturaenvio").val();
    var mailalt1 = "ejemplo@ejemplo.com";
    var mailalt2 = "ejemplo@ejemplo.com";
    var mailalt3 = "ejemplo@ejemplo.com";
    var mailalt4 = "ejemplo@ejemplo.com";
    var mailalt5 = "ejemplo@ejemplo.com";
    var mailalt6 = "ejemplo@ejemplo.com";
    var chcorreo1 = 0;
    var chcorreo2 = 0;
    var chcorreo3 = 0;
    var chcorreo4 = 0;
    var chcorreo5 = 0;
    var chcorreo6 = 0;

    if ($("#chcorreo1").prop('checked')) {
        chcorreo1 = 1;
        mailalt1 = $("#correo1").val();
    }
    if ($("#chcorreo2").prop('checked')) {
        chcorreo2 = 1;
        mailalt2 = $("#correo2").val();
    }
    if ($("#chcorreo3").prop('checked')) {
        chcorreo3 = 1;
        mailalt3 = $("#correo3").val();
    }
    if ($("#chcorreo4").prop('checked')) {
        chcorreo4 = 1;
        mailalt4 = $("#correo4").val();
    }
    if ($("#chcorreo5").prop('checked')) {
        chcorreo5 = 1;
        mailalt5 = $("#correo5").val();
    }
    if ($("#chcorreo6").prop('checked')) {
        chcorreo6 = 1;
        mailalt6 = $("#correo6").val();
    }

    if (isEmailtoSend(mailalt1, "correo1") && isEmailtoSend(mailalt2, "correo2") && isEmailtoSend(mailalt3, "correo3") && isEmailtoSend(mailalt4, "correo4") && isEmailtoSend(mailalt5, "correo5") && isEmailtoSend(mailalt6, "correo6") && isCheckedMailSend(chcorreo1, chcorreo2, chcorreo3, chcorreo4, chcorreo5, chcorreo6)) {
        cargandoHide();
        cargandoShow();
        $.ajax({
            url: "com.sine.imprimir/imprimirfactura.php",
            type: "POST",
            data: {transaccion: "pdf", id: idfactura, ch1: chcorreo1, ch2: chcorreo2, ch3: chcorreo3, ch4: chcorreo4, ch5: chcorreo5, ch6: chcorreo6, mailalt1: mailalt1, mailalt2: mailalt2, mailalt3: mailalt3, mailalt4: mailalt4, mailalt5: mailalt5, mailalt6: mailalt6},
            success: function (datos) {
                var texto = datos.toString();
                var bandera = texto.substring(0, 1);
                var res = texto.substring(0, 1000);
                if (bandera == '0') {
                    alertify.error(res);
                } else {
                    $("#enviarmail").modal('hide');
                    alertify.success('res');
                }
                cargandoHide();
            }
        });
    }
}

function opcionesCorreo() {
    $.ajax({
        url: 'com.sine.enlace/enlaceconfig.php',
        type: 'POST',
        data: {transaccion: 'opcionescorreo'},
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

function sendWhatsapp() {
    cargandoHide();
    cargandoShow();
    var idfactura = $("#idfacturawp").val();
    var cod = $("#option-cod").val();
    var wpnumber = $("#wpnumber").val();

    $.ajax({
        url: "com.sine.imprimir/imprimirfactura.php",
        type: "POST",
        data: {transaccion: "pdf", idwp: idfactura, cod: cod, wpnumber: wpnumber},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(0, 1000);
            if (bandera == '0') {
                alertify.error(res);
            } else {
                alert(datos);
            }
            cargandoHide();
        }
    });

}

function loadCliente(idcliente) {
    $.ajax({
        url: "com.sine.enlace/enlacefactura.php",
        type: "POST",
        data: {transaccion: "loadcliente", idcliente: idcliente},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
            } else {
                var array = datos.split("</tr>");
                var nombre = array[0];
                var rfc = array[1];
                var razon = array[2];
                var regfiscal = array[3];
                var codpostal = array[4];

                $("#id-cliente").val(idcliente);
                $("#nombre-cliente").val(nombre);
                $("#rfc-cliente").val(rfc);
                $("#razon-cliente").val(razon);
                $("#regfiscal-cliente").val(regfiscal);
                $("#cp-cliente").val(codpostal);
            }
        }
    });
}

function checkStatusCancelacion(fid) {
    $("#cod-status").html('');
    $("#estado-cfdi").html('');
    $("#cfdi-cancelable").html('');
    $("#estado-cancelacion").html('');
    $("#div-reset").html('');
    cargandoHide();
    cargandoShow();
    $.ajax({
        url: "com.sine.enlace/enlacefactura.php",
        type: "POST",
        data: {transaccion: "statuscfdi", fid: fid},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
            } else {
                var array = datos.split("</tr>");
                var codstatus = array[0];
                var estado = array[1];
                var cancelable = array[2];
                var stcancelacion = array[3];
                var reset = array[4];

                $("#cod-status").html(codstatus);
                $("#estado-cfdi").html(estado);
                $("#cfdi-cancelable").html(cancelable);
                $("#estado-cancelacion").html(stcancelacion);
                $("#div-reset").html(reset);
            }
            cargandoHide();
        }
    });
}

function resetCfdi(idfactura) {
    alertify.confirm("Este proceso devolvera la factura al estado de 'Pendiente' y borrara el acuse de cancelacion generado, ¿Desea continuar?", function () {
        cargandoHide();
        cargandoShow();
        $.ajax({
            url: "com.sine.enlace/enlacefactura.php",
            type: "POST",
            data: {transaccion: "editarestado", idfactura: idfactura},
            success: function (datos) {
                var texto = datos.toString();
                var bandera = texto.substring(0, 1);
                var res = texto.substring(0, 1000);
                if (bandera == '0') {
                    alertify.error(res);
                } else {
                    alertify.success('Factura Restaurada');
                    $("#modal-stcfdi").modal('hide');
                    filtrarFolio();
                }
                cargandoHide();
            }
        });
    }).set({title: "Q-ik"});
}

function getClientebyRFC() {
    var rfc = $("#rfc-cliente").val();
    if (rfc != "") {
        cargandoHide();
        cargandoShow();
        $.ajax({
            url: "com.sine.enlace/enlacecarta.php",
            type: "POST",
            data: {transaccion: "getcliente", rfc: rfc},
            success: function (datos) {
                //alert("hola"+datos);
                var texto = datos.toString();
                var bandera = texto.substring(0, 1);
                var res = texto.substring(1, 1000);
                if (bandera == '0') {
                    alertify.error(res);
                } else {
                    var array = res.split("</tr>");
                    
                    $("#id-cliente").val(array[0]);
                    $("#razon-cliente").val(array[1]);
                    $("#regfiscal-cliente").val(array[2]);
                    $("#cp-cliente").val(array[3]);
                }
                cargandoHide();
            }
        });
    }
}