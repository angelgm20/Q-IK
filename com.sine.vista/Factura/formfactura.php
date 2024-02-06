<?php
include("buscarProductos.php");
?>
<form action="com.sine.enlace/enlacefactura.php" onsubmit="return false;" id="form-factura" style="height: 100%;">
    <div class="col-md-12"><div class="titulo-lista" id="contenedor-titulo-form-factura">Nueva Factura </div> </div>
    <div id="div-space">
    </div>
    <div class="div-form">
        <div class="row" id="not-timbre">

        </div>

        <div class="row">
            <div class="col-md-8">
                <label class="label-sub">Datos del Emisor</label>
            </div>
            <div class="col-md-4">
                <label class="label-form text-right" for="fecha-creacion">Fecha de creacion</label>
                <div class=" form-group">
                    <input class="input-form text-center form-control" disabled id="fecha-creacion" name="fecha-creacion" placeholder="Fecha Actual" type="text"/>
                    <div id="fecha-creacion-errors">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <label class="label-form text-right" for="folio">Folio</label> <label class="mark-required text-right">*</label>
                <div class="form-group">
                    <select class="form-control text-center input-form" id="folio" name="folio">
                        <option value="" id="option-default-folio">- - - -</option>
                        <optgroup id="foliofactura" class="contenedor-folios text-left"> </optgroup>
                    </select>
                    <div id="folio-errors"></div>
                </div>
            </div>
            
            <div class="col-md-4">
                <label class="label-form text-right" for="datos-facturacion">Datos de Facturacion</label> <label class="mark-required text-right">*</label>
                <div class="form-group">
                    <select class="form-control text-center input-form" id="datos-facturacion" name="datos-facturacion" onchange="loadDatosFactura();">
                        <option value="" id="option-default-datos">- - - -</option>
                        <optgroup id="datosfacturar" class="contenedor-datos text-left"> </optgroup>
                    </select>
                    <div id="datos-facturacion-errors"></div>
                </div>
            </div>

            <div class="col-md-4">
                <label class="label-form text-right" for="rfc-emisor">RFC</label>
                <div class="form-group">
                    <input class="input-form text-center form-control" disabled id="rfc-emisor" name="rfc-emisor" placeholder="RFC Emisor" type="text"/>
                    <div id="rfc-emisor-errors"></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <label class="label-form text-right" for="razon-emisor">Razon Social</label>
                <div class="form-group">
                    <input class="input-form text-center form-control" disabled id="razon-emisor" name="razon-emisor" placeholder="Razon Social Emisor" type="text"/>
                    <div id="razon-emisor-errors"></div>
                </div>
            </div>
            
            <div class="col-md-4">
                <label class="label-form text-right" for="regimen-emisor">Regimen Fiscal</label>
                <div class="form-group">
                    <input class="input-form text-center form-control" disabled id="regimen-emisor" name="regimen-emisor" placeholder="Regimen fiscal" type="text"/>
                    <div id="regimen-emisor-errors"></div>
                </div>
            </div>
            
            <div class="col-md-4">
                <label class="label-form text-right" for="cp-emisor">Codigo Postal</label>
                <div class="form-group">
                    <input class="input-form text-center form-control" disabled id="cp-emisor" name="cp-emisor" placeholder="Codigo Postal" type="text"/>
                    <div id="cp-emisor-errors"></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <label class="label-sub">Datos del Receptor</label>
            </div>
            <div class="col-md-6 text-right">
                <label class="mark-required text-right">*</label> <label class="label-required text-right"> Campo Obligatorio</label>
            </div>
        </div>        

        <div class="row">
            <div class="col-md-4">
                <div class="new-tooltip icon tip">
                    <label class="label-form text-right" for="nombre-cliente">Cliente</label> <span class="glyphicon glyphicon-question-sign"></span>
                    <span class="tiptext">Puede realizar la busqueda por Nombre, Apellidos, Empresa o RFC de un cliente que haya registrado previamente y el sistema cargara los datos de forma automatica, si no realizo registro puede dejar este campo en blanco e ingresar los datos necesarios.</span>
                </div>
                <div class="form-group">
                    <input type="hidden" id="id-cliente"/>
                    <input type="text" class="form-control input-form" id="nombre-cliente" placeholder="Buscar cliente (Nombre, Empresa o RFC cliente)" oninput="autocompletarCliente()"/>
                    <div id="nombre-cliente-errors"></div>
                </div>
            </div>

            <div class="col-md-4">
                <label class="label-form text-right" for="rfc-cliente">RFC Cliente</label> <label class="mark-required text-right">*</label>
                <div class="form-group">
                    <input type="text" class="form-control input-form" id="rfc-cliente" placeholder="RFC del cliente" onblur="getClientebyRFC();"/>
                    <div id="rfc-cliente-errors"></div>
                </div>
            </div>

            <div class="col-md-4">
                <label class="label-form text-right" for="razon-cliente">Razon Social del Cliente</label> <label class="mark-required text-right">*</label>
                <div class="form-group">
                    <input type="text" class="form-control input-form" id="razon-cliente" placeholder="Razon social del cliente"/>
                    <div id="razon-cliente-errors"></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <label class="label-form text-right" for="regfiscal-cliente">Regimen Fiscal del cliente</label> <label class="mark-required text-right">*</label>
                <div class="form-group">
                    <input type="text" class="form-control input-form" id="regfiscal-cliente" placeholder="Regimen fiscal del cliente" oninput="aucompletarRegimen();"/>
                    <div id="regfiscal-cliente-errors"></div>
                </div>
            </div>

            <div class="col-md-4">
                <label class="label-form text-right" for="direccion-cliente">Direccion del Cliente</label>
                <div class="form-group">
                    <input type="text" class="form-control input-form" id="direccion-cliente" placeholder="Direccion del cliente"/>
                    <div id="direccion-cliente-errors"></div>
                </div>
            </div>
            
            <div class="col-md-4">
                <label class="label-form text-right" for="cp-cliente">Codigo Postal del Cliente</label> <label class="mark-required text-right">*</label>
                <div class="form-group">
                    <input type="text" class="form-control input-form" id="cp-cliente" placeholder="Codigo Postal del cliente"/>
                    <div id="cp-cliente-errors"></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <label class="label-sub">Datos de Factura</label>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <label class="label-form text-right" for="tipo-comprobante">Tipo Comprobante</label> <label class="mark-required text-right">*</label>
                <div class="form-group">
                    <select class="form-control text-center input-form" id="tipo-comprobante" name="tipo-comprobante" onchange="checkFolios();">
                        <option value="" id="option-default-tipo-comprobante">- - - -</option>
                        <optgroup id="tipo-comprobante" class="contenedor-tipo-comprobante text-left"> </optgroup>
                    </select>
                    <div id="tipo-comprobante-errors"></div>
                </div>
            </div>

            <div class="col-md-4">
                <label class="label-form text-right" for="id-metodo-pago">Metodo de Pago</label> <label class="mark-required text-right">*</label>
                <div class="form-group">
                    <select class="form-control text-center input-form" id="id-metodo-pago" name="id-metodo-pago" onchange="checkMetodopago();" >
                        <option value="" id="option-default-metodo-pago">- - - -</option>
                        <optgroup id="metodo-pago" class="contenedor-metodo-pago text-left"> </optgroup>
                    </select>
                    <div id="id-metodo-pago-errors"></div>
                </div>
            </div>

            <div class="col-md-4">
                <label class="label-form text-right" for="id-forma-pago">Forma de Pago</label> <label class="mark-required text-right">*</label>
                <div class="form-group">
                    <select class="form-control text-center input-form" id="id-forma-pago" name="id-forma-pago">
                        <option value="" id="option-default-forma-pago">- - - -</option>
                        <optgroup id="forma-pago" class="contenedor-forma-pago text-left"> </optgroup>
                    </select>
                    <div id="id-forma-pago-errors"></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-2">
                <label class="label-form text-right" for="id-moneda">Moneda</label> <label class="mark-required text-right">*</label>
                <div class="form-group">
                    <select class="form-control text-center input-form" id="id-moneda" name="id-moneda" onchange="getTipoCambio()">
                        <option value="" id="option-default-moneda">- - - -</option>
                        <optgroup id="metodo-pago" class="contenedor-moneda text-left"> </optgroup>
                    </select>
                    <div id="id-moneda-errors"></div>
                </div>
            </div>

            <div class="col-md-2">
                <label class="label-form text-right" for="tipo-cambio">Tipo de Cambio</label>
                <div class="form-group">
                    <input type="text" class="form-control input-form" id="tipo-cambio" placeholder="Tipo de cambio de Moneda" disabled="">
                    <div id="tipo-cambio-errors"></div>
                </div>
            </div>

            <div class="col-md-4">
                <label class="label-form text-right" for="id-uso">Uso CFDI</label> <label class="mark-required text-right">*</label>
                <div class="form-group">
                    <select class="form-control text-center input-form" id="id-uso" name="id-uso" >
                        <option value="" id="option-default-uso">- - - -</option>
                        <optgroup id="metodo-pago" class="contenedor-uso text-left"> </optgroup>
                    </select>
                    <div id="id-uso-errors"></div>
                </div>
            </div>

            <div class="col-md-4">
                <label class="label-form text-right" for="chfirma">Firmar?</label>
                <div class="form-group">
                    <input class="input-check" id="chfirma" name="chfirma" type="checkbox"/>
                    <div id="chfirma-errors"></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="new-tooltip icon tip">
                    <label class="label-form text-right" for="periodicidad-factura">Periodicidad </label> <span class="glyphicon glyphicon-question-sign"></span>
                    <span class="tiptext">Los datos de periodicidad, Meses y A&ntilde;o pertenecen a los datos de informacion global, estos datos solo son necesarios al crear una factura para el publico en general.</span>
                </div>
                <div class="form-group">
                    <select class="form-control text-center input-form" id="periodicidad-factura" name="periodicidad-factura">
                        <option value="" id="option-default-periodicidad-factura">- - - -</option>
                        <optgroup id="tipo-comprobante" class="contenedor-pglobal text-left"> </optgroup>
                    </select>
                    <div id="periodicidad-factura-errors"></div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="new-tooltip icon tip">
                    <label class="label-form text-right" for="mes-periodo">Mes Periodicidad</label> <span class="glyphicon glyphicon-question-sign"></span>
                    <span class="tiptext">Se debe registrar la clave del mes o los meses al que corresponde la información de las operaciones celebradas con el público en general</span>
                </div>
                <div class="form-group">
                    <select class="form-control text-center input-form" id="mes-periodo" name="mes-periodo">
                        <option value="" id="option-default-mes-periodo">- - - -</option>
                        <optgroup id="periodo-mes" class="contenedor-meses text-left"> </optgroup>
                    </select>
                    <div id="mes-periodo-errors"></div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="new-tooltip icon tip">
                    <label class="label-form text-right" for="anho-periodo">A&ntilde;o Periodicidad</label> <span class="glyphicon glyphicon-question-sign"></span>
                    <span class="tiptext">Se debe registrar el año al que corresponde la información del comprobante global. El valor registrado debe ser igual al año en curso o al año inmediato anterior considerando el registrado en la Fecha de emisión del comprobante.</span>
                </div>
                
                <div class="form-group">
                    <select class="form-control text-center input-form" id="anho-periodo" name="anho-periodo">
                        <option value="" id="option-default-anho-periodo">- - - -</option>
                        <optgroup id="periodo-anho" class="contenedor-ano text-left"> </optgroup>
                    </select>
                    <div id="anho-periodo-errors"></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <a href="#cfdirel" data-toggle='collapse' class="label-sub">Agregar CFDIS Relacionados <span class="glyphicon glyphicon-collapse-down"></span></a>
                <div id="cfdirel" class="panel-collapse collapse">
                    <table class="table tab-hover table-condensed table-responsive table-row table-head">
                        <thead>
                            <tr>
                                <th>Tipo de Relacion <span class="glyphicon glyphicon-sort-by-alphabet"></span></th>
                                <th>CFDI <span class="glyphicon glyphicon-sort-by-alphabet"></span></th>
                                <th>Agregar <span class="glyphicon glyphicon-plus"></span></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="col-md-5">
                                    <select class="form-control text-center input-form" id="tipo-relacion" name="tipo-relacion" >
                                        <option value="" id="option-default-tipo-relacion">- - - -</option>
                                        <optgroup id="relacion" class="contenedor-relacion text-left"> </optgroup>
                                        
                                    </select>
                                </td>
                                <td><input type="text" class="form-control cfdi input-form" id="cfdi-rel" placeholder="00000000-0000-0000-0000-000000000000"></td>
                                <td class="text-center"><button id="btn-agregar-cfdi" class='btn button-list' onclick='addCFDI();'><span class='glyphicon glyphicon-plus'></span> </button></td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table tab-hover table-condensed table-responsive table-row table-head" id="body-lista-cfdi">

                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <label class="label-sub">Conceptos</label>
            </div>
            <div class="col-md-8 text-right" id="btnprod">
                <button id="btn-nuevo-producto" type="button" class="button-agregar" data-toggle="modal" data-target="#nuevo-producto" onclick="setCamposProducto();">
                    <span class="glyphicon glyphicon-plus"></span> Nuevo Producto
                </button>
                <button id="btn-agregar-productos" type="button" class="button-agregar" data-toggle="modal" data-target="#myModal">
                    Agregar Conceptos <span class="glyphicon glyphicon-search "></span>
                </button>
            </div>
        </div>        

        <div class="row scrollX" style="max-width: 100%;">
            <table id="resultados" class="table tab-hover table-condensed table-responsive table-row table-head">

            </table>

        </div>

        <div class="row">
            <div class="col-md-12 text-right" id="btns"> 
                <button class="button-form btn-danger " onclick="cancelarFactura()" >Cancelar <span class="glyphicon glyphicon-remove"></span></button> &nbsp;
                <button class="button-form btn-primary " onclick="insertarFactura()" id="btn-form-factura">Guardar <span class="glyphicon glyphicon-floppy-disk"></span></button>
            </div>	
        </div>
    </div>
</form>
<br/>
<script src="js/scriptfactura.js"></script>
<script >
                    $(document).ready(function () {
                        var cleave = new Cleave('.cfdi', {
                            delimiter: '-',
                            blocks: [8, 4, 4, 4, 12]
                        });

                    });
                    window.addEventListener('resize', resetDiv);
                    resetDiv();
</script>
