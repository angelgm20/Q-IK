<?php

require_once 'ControladorFacturaCron.php';

$controladorfactura = new ControladorFacturaCron();
$refacturar = $controladorfactura->Contratosfacturar();
echo $refacturar;