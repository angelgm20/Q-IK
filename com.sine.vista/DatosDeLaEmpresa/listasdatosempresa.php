<div class="col-md-12"><div class="titulo-lista">Datos de facturacion </div> </div>
<form class="form-inline" onsubmit="return false;">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <input type="text" class="form-control input-search" id="buscar-empresa" placeholder="Buscar datos (Contribuyente o RFC)" oninput="buscarEmpresa()">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <select class="form-select input-search w-100 p-2" id="num-reg" name="num-reg" onchange="buscarEmpresa()">
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
                <button type="button" class="btn btn-custom" id="btn-crear"></button>
            </div>
        </div>
    </div>
</form>

<div class="scrollX div-bordered">
    <table class="table table-hover table-condensed table-responsive table-row table-head" id="body-lista-empresa">
        <thead class="sin-paddding">
            <tr>
            <th></th>
                <th class="col-md-2">Contribuyente</th>
                <th>RFC </th>
                <th class="col-md-2">Razon Social</th>
                <th>Direccion</th>
                <th>Regimen Fiscal</th>
                <th><span class=" fas fa-ellipsis-v"></span></th>
            </tr>
        </thead>
    </table> 
</div>
<br/>
<script type="text/javascript" src="js/scriptempresa.js"></script>

