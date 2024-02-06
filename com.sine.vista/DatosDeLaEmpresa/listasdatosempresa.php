<div class="col-md-12"><div class="titulo-lista">Datos de facturacion </div> </div>
<form class="form-horizontal" onsubmit="return false;">
    <div class="form-group">
        <div class="col-sm-6">
            <input type="text" class="form-control input-search" id="buscar-empresa" placeholder="Buscar datos (Contribuyente o RFC)" oninput="buscarEmpresa()">
        </div>
        <div class="col-sm-2">
            <select class="form-control input-search" id="num-reg" name="num-reg" onchange="buscarEmpresa()">
                <option value="10" >10</option>
                <option value="15" >15</option>
                <option value="20" >20</option>
                <option value="30" >30</option>
                <option value="50" >50</option>
                <option value="100" >100</option>
            </select>
        </div>
        <div class="col-sm-4 text-right" id="btn-crear">
            
        </div>
    </div>
</form>
<div class="scrollX div-bordered">
    <table class="table table-hover table-condensed table-responsive table-row table-head" id="body-lista-empresa">
        <thead>
            <tr>
                <th></th>
                <th class="col-md-2">Contribuyente</th>
                <th>RFC </th>
                <th class="col-md-2">Razon Social</th>
                <th>Direccion</th>
                <th>Regimen Fiscal</th>
                <th><span class="glyphicon glyphicon-edit"></span></th>
            </tr>
        </thead>
    </table>
</div>
<br/>

<script type="text/javascript" src="js/scriptempresa.js"></script>


