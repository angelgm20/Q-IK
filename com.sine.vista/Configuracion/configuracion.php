<div class="col-md-12"><div class="titulo-lista">Configuracion </div> </div>
<form class="form-horizontal" onsubmit="return false;">
    <div class="form-group" id="menu-button">
        <div class='col-md-3' id="div-folio-conf" hidden="">
            <button id="btn-folio-conf" class='button-config'>Folios <span class='lnr lnr-book icon-size'></span></button>
        </div>
        
        <div class='col-md-3' id="div-comision-conf" hidden="">
            <button id="btn-comision-conf" class='button-config'>Comisiones <span class='glyphicon glyphicon-usd icon-size'></span></button>
        </div>
        
        <div class='col-md-3' id="div-encabezado-conf" hidden="">
            <button id="btn-encabezado-conf" class='button-config'>Encabezados <span class='lnr lnr-file-empty icon-size'></span></button>
        </div>
        
        <div class='col-md-3' id="div-correo-conf" hidden="">
            <button id="btn-correo-conf" class='button-config'>Correo <span class='lnr lnr-envelope icon-size'></span></button>
        </div>
        
        <div class='col-md-3' id="div-tablas" hidden="">
            <button id="btn-tablas" class='button-config'>Importar tablas <span class='lnr lnr-database icon-size'></span></button>
        </div>
    </div>
</form>
<div class="div-form" id="view-config">
</div>
<br/>
<script type="text/javascript" src="js/scriptconfig.js"></script>
<script>
    window.addEventListener('resize', resetDiv);
    resetDiv();
</script>