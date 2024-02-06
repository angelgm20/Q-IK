<?php

require_once 'ControladorFacturaCron.php';

$controladorfactura = new ControladorFacturaCron();
$refacturar = $controladorfactura->timbrarFactura('98');
echo $refacturar;