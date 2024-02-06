<?php
include("modals.php");
?>
<div class="col-md-12"><div class="titulo-lista">Facturas </div> </div>
<form class="form-horizontal" onsubmit="return false;">
    <div class="form-group">
        <div class="col-md-6 text-left">
            <input type="text" class="form-control input-search" id="buscar-factura" placeholder="Buscar facturas (Folio, emisor o cliente)" oninput="buscarFactura()">
        </div>
        <div class="col-md-2 text-center">
            <select class="form-control input-search" id="num-reg" name="num-reg" onchange="buscarFactura()">
                <option value="10" >10</option>
                <option value="15" >15</option>
                <option value="20" >20</option>
                <option value="30" >30</option>
                <option value="50" >50</option>
                <option value="100" >100</option>
            </select>
        </div>
        <div class="col-md-4 text-right" id="btn-crear">
            
        </div>
    </div>
</form>
<!--<button class="btn btn-sm btn-primary " onclick="tests()" id="btn-form-factura">Guardar <span class="glyphicon glyphicon-save"></span></button>-->
<div class="scrollX div-bordered">
    <table class="table table-hover table-condensed table-responsive table-factura table-head" id="body-lista-factura">
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
                <th><span class="glyphicon glyphicon-option-vertical"></span></th>
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
