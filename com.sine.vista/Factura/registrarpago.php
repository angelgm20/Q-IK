
<form action="com.sine.enlace/enlacefactura.php" onsubmit="return false;" id="form-pago">
    <hr/>
    <div class="panel">
        <div class="panel-heading">
            <div class="h4 text-info text-center" id="contenedor-titulo-form-pago"> Registrar Pago de Factura <span class="glyphicon glyphicon-list-alt"></span>
            </div>
        </div>

        <div class="panel-body">
            <input type="hidden" class="form-control" id="idfactura" placeholder="Buscar productos">
            <input type="hidden" class="form-control" id="idcliente" placeholder="Buscar productos">
            <input type="hidden" class="form-control" id="idmetodopago" placeholder="Buscar productos">
            <div class="row">
                <label class="control-label col-md-2 text-right" for="folio-factura">Folio de factura</label>
                <div class="col-md-4 form-group">
                    <div class="input-group">
                        <input class="form-control text-center input-sm" disabled id="folio-factura" name="folio-factura" placeholder="Folio de Factura" type="text"/>
                        <div class="input-group-addon"><span class="glyphicon glyphicon-barcode"></span></div>
                    </div>
                    <div class="folio-factura-errors">
                    </div>
                </div>
                <label class="control-label col-md-2 text-right" for="folio">Folio de Pago</label>
                <div class="col-md-4 form-group">
                    <div class="input-group">
                        <input class="form-control text-center input-sm" disabled id="folio-pago" name="folio-pago" placeholder="Folio de Factura" type="text"/>
                        <div class="input-group-addon"><span class="glyphicon glyphicon-barcode"></span></div>
                    </div>
                    <div class="folio-pago-errors">
                    </div>
                </div>
            </div>
            <div class="row">
                <label class="control-label col-md-6 text-right">
                    
                </label>
                <label class="control-label col-md-2 text-right" for="fecha-creacion">Fecha de creacion</label>
                <div class="col-md-4 form-group">
                    <div class="input-group">
                        <input class="form-control text-center input-sm" disabled id="fecha-creacion" name="fecha-creacion" placeholder="Fecha Actual" type="text"/>
                        <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
                    </div>
                    <div class="fecha-creacion-errors">
                    </div>
                </div>
            </div>
            <hr/>
            <div class="row">
                <label class="control-label col-md-2 text-right" for="folio">Monto Pagado</label>
                <div class="col-md-4 form-group">
                    <div class="input-group">
                        <input class="form-control text-center input-sm" id="monto-pago" name="monto-pago" placeholder="Monto Pagado" type="number" oninput="calcularRestante()"/>
                        <div class="input-group-addon"><span class="glyphicon glyphicon-usd"></span></div>
                    </div>
                    <div class="monto-pago-errors">
                    </div>
                </div>
                <label class="control-label col-md-2 text-right" for="folio">Total Anterior</label>
                <div class="col-md-4 form-group">
                    <div class="input-group">
                        <input class="form-control text-center input-sm" id="totalfactura" name="monto-pago" placeholder="Total Anterior" type="number" disabled />
                        <div class="input-group-addon"><span class="glyphicon glyphicon-usd"></span></div>
                    </div>
                    <div class="totalfactura-errors">
                    </div>
                </div>
            </div>
            <div class="row">
                <label class="control-label col-md-2 text-right" for="folio">Total Restante</label>
                <div class="col-md-4 form-group">
                    <div class="input-group">
                        <input class="form-control text-center input-sm" id="tot-restante" name="num-transaccion" placeholder="Total Restante" value="0" type="number" disabled/>
                        <div class="input-group-addon"><span class="glyphicon glyphicon-barcode"></span></div>
                    </div>
                    <div class="tot-restante-errors">
                    </div>
                </div>
                <label class="control-label col-md-2 text-right" for="id-forma-pago">Forma de Pago</label>
                <div class="col-md-4 form-group">
                    <div class="input-group">
                        <select class="form-control text-center input-sm" id="id-forma-pago" name="id-forma-pago">
                            <option value="" id="option-default-forma-pago">- - - -</option>
                            <optgroup id="forma-pago" class="contenedor-forma-pago"> </optgroup>
                        </select>
                        <div class="input-group-addon"><span class="glyphicon glyphicon-credit-card"></span></div>
                    </div>
                    <div id="id-forma-pago-errors"></div>
                </div>
                
            </div>
            <div class="row">
                <label class="control-label col-md-2 text-right" for="cliente">Cuenta Banco</label>
                <div class="col-md-4 form-group">
                    <div class="input-group">
                        <select class="form-control text-center input-sm" id="id-bancocuenta" name="id-bancocuenta" >
                            <option value="" id="option-default-id-bancocuenta">- - - -</option>
                            <option value="31<corte>00" id="oxxobancuenta">Intermediario (OXXO,SevenEleven, etc.)</option>
                            <optgroup id="cliente" class="contenedor-id-bancocuenta"> </optgroup>
                        </select>
                        <div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
                    </div>
                    <div id="id-bancocuenta-errors"></div>
                </div>
                <label class="control-label col-md-2 text-right" for="folio">N° de Transaccion</label>
                <div class="col-md-4 form-group">
                    <div class="input-group">
                        <input class="form-control text-center input-sm" id="num-transaccion" name="num-transaccion" placeholder="N° de Transaccion" type="number"/>
                        <div class="input-group-addon"><span class="glyphicon glyphicon-barcode"></span></div>
                    </div>
                    <div class="num-transaccion-errors">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-right" id="btn">
                    <button class="btn btn-sm btn-danger " onclick="loadView('listafactura')" >Cancelar <span class="glyphicon glyphicon-remove"></span></button> &nbsp;
                    <button class="btn btn-sm btn-primary " onclick="insertarPago()" id="btn-pago">Guardar<span class="glyphicon glyphicon-save"></span></button>
                </div>	
            </div>
        </div>	
    </div>
</form>
<script src="js/scriptfactura.js"></script>
<script src="js/pdf.js"></script>
