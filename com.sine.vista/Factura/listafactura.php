<?php
include("modals.php");
?>
<div class="col-md-12"><div class="titulo-lista">Facturas </div> </div>
<form class="form-inline" onsubmit="return false;">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <input type="text" class="form-control input-search" id="buscar-factura" placeholder="Buscar facturas (Folio, emisor o cliente)" oninput="buscarFactura()">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <select class="form-control input-search" id="num-reg" name="num-reg" onchange="buscarFactura()">
                    <option value="10">--</option>
                    <option value="2">2</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>
        <div class="col-md-4 text-right">
            <div class="form-group">
                <button type="button" class="btn btn-custom" id="btn-crear">Crear Factura</button>
            </div>
        </div>
    </div>
</form>


<!--<button class="btn btn-sm btn-primary " onclick="tests()" id="btn-form-factura">Guardar <span class="glyphicon glyphicon-save"></span></button>-->
<div class="scrollX div-bordered">
    <table class="table table-hover  table-responsive table-factura table-head" id="body-lista-factura">
        <thead class="sin-paddding">
            <tr>
                <th></th>
                <th>N°Folio </th>
                <th>Fecha de Creacion </th>
                <th class="col-md-3">Emisor</th>
                <th>Cliente</th>
                <th>Estado </th>
                <th>Subtotal </th>
                <th>Traslados </th>
                <th>Retenciones </th>
                <th>Total </th>
                <th><span class="fas fa-ellipsis-v"></span></th>
            </tr>
        </thead>
    </table>
     
        
</div>
<br/>
<script type="text/javascript" src="js/scriptfactura.js"></script>
<script >
                    $(document).ready(function () {
                        var cleave = new Cleave('.cfdi', {
                            delimiter: '-',
                            blocks: [8, 4, 4, 4, 12]
                        });

                    });
</script>
