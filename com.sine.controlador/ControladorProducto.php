<?php

require_once '../com.sine.dao/Consultas.php';

class ControladorProducto {

    function __construct() {
        
    }

    private function getImpuestos($tipo) {
        $consultado = false;
        $consulta = "SELECT * FROM impuesto where tipoimpuesto=:tipo";
        $consultas = new Consultas();
        $valores = array("tipo" => $tipo);
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    private function buildArray($idimpuesto, $precio) {
        $imptraslados = $this->getImpuestos($idimpuesto);
        $row = array();
        foreach ($imptraslados as $tactual) {
            $impuesto = $tactual['impuesto'];
            $porcentaje = $tactual['porcentaje'];
            $chuso = $tactual['chuso'];
            if ($chuso == '1') {
                $imp = $precio * $porcentaje;
                if ($imp > 0) {
                    $row[] = bcdiv($imp, '1', 2) . '-' . $porcentaje . '-' . $impuesto;
                }
            }
        }

        $trasarray = implode("<impuesto>", $row);
        return $trasarray;
    }

    private function estadoInventario($e) {
        $insertado = false;
        $consulta = "UPDATE `productos_servicios` SET cantinv=:cantidad, chinventario=:chinventario WHERE idproser=:idproducto;";
        $valores = array("idproducto" => $e->getIdProducto(),
            "cantidad" => $e->getCantidad(),
            "chinventario" => $e->getChinventario());
        $con = new Consultas();
        $insertado = $con->execute($consulta, $valores);
        return $insertado;
    }
    
    private function getCodigobyCod($cod){
        $consultado = false;
        $consultas = new Consultas();
        $consulta = "SELECT codproducto FROM productos_servicios WHERE codproducto=:cod;";
        $val = array("cod" => $cod);
        $consultado = $consultas->getResults($consulta, $val);
        return $consultado;
    }

    private function validarCodigoAux($cod) {
        $existe = false;
        $validar = $this->getCodigobyCod($cod);
        foreach ($validar as $actual) {
            $existe = true;
        }
        return $existe;
    }

    public function validarCodigo($p) {
        $datos = "";
        $check = $this->validarCodigoAux($p->getCodproducto());
        if ($check) {
            $datos = "0Ya existe un producto con este codigo.";
        } else {
            $datos = $this->insertarProducto($p);
        }
        return $datos;
    }

    private function insertarProducto($p) {
        $insertado = false;
        $con = new Consultas();
        $consulta = "INSERT INTO `productos_servicios` VALUES (:id, :codproducto, :producto, :clvunidad, :unidad, :descripcion, :pcompra, :porcentaje, :ganancia, :pventa, :tipo, :clvfiscal, :descfiscal, :idproveedor,:imagen,:chinventario,:cantidad);";
        $valores = array("id" => null,
            "codproducto" => $p->getCodproducto(),
            "producto" => $p->getProducto(),
            "clvunidad" => $p->getClvunidad(),
            "unidad" => $p->getDescripcionunidad(),
            "descripcion" => $p->getDescripcion(),
            "pcompra" => $p->getPrecio_compra(),
            "porcentaje" => $p->getPorcentaje(),
            "ganancia" => $p->getGanancia(),
            "pventa" => $p->getPrecio_venta(),
            "tipo" => $p->getTipo(),
            "clvfiscal" => $p->getClavefiscal(),
            "descfiscal" => $p->getDescripcionfiscal(),
            "idproveedor" => $p->getIdproveedor(),
            "imagen" => $p->getImagen(),
            "chinventario" => $p->getChinventario(),
            "cantidad" => $p->getCantidad());
        $insertado = $con->execute($consulta, $valores);
        if ($p->getImagen() != "") {
            rename('../temporal/tmp/' . $p->getImagen(), '../img/productos/' . $p->getImagen());
        }
        if ($p->getInsert() != "") {
            session_start();
            $sid = session_id();
            $idproducto = $this->getIDProducto($p->getCodproducto());
            if ($p->getInsert() == 'f') {
                $agregar = $this->agregarProductoFactura($idproducto, $sid, $p);
            }if ($p->getInsert() == 'c' || $p->getInsert() == 'ct') {
                $agregar = $this->agregarProductoCotizacion($idproducto, $sid, $p);
            }
        }
        return $insertado;
    }

    private function agregarProductoFactura($idproducto, $sessionid, $t) {
        $insertado = false;
        $traslados = $this->buildArray('1', $t->getPrecio_venta());
        $retenciones = $this->buildArray('2', $t->getPrecio_venta());
        $consulta = "INSERT INTO `tmp` VALUES (:id, :idproducto, :tmpnombre, :cantidad, :precio, :importe, :descuento, :impdescuento, :imptotal, :traslados, :retenciones, :observaciones, :chinv, :clvfiscal, :clvunidad, :session);";
        $valores = array("id" => null,
            "idproducto" => $idproducto,
            "tmpnombre" => $t->getProducto(),
            "cantidad" => '1',
            "precio" => $t->getPrecio_venta(),
            "importe" => $t->getPrecio_venta(),
            "descuento" => '0',
            "impdescuento" => '0',
            "imptotal" => $t->getPrecio_venta(),
            "traslados" => $traslados,
            "retenciones" => $retenciones,
            "observaciones" => '',
            "chinv" => $t->getChinventario(),
            "clvfiscal" => $t->getClavefiscal()."-".$t->getDescripcionfiscal(),
            "clvunidad" => $t->getClvunidad()."-".$t->getDescripcionunidad(),
            "session" => $sessionid);
        $con = new Consultas();
        $insertado = $con->execute($consulta, $valores);
        if ($t->getChinventario() == '1') {
            $remover = $this->removerInventario($idproducto, '1');
        }
        return $insertado;
    }

    private function agregarProductoCotizacion($idproducto, $sessionid, $t) {
        $insertado = false;
        $traslados = $this->buildArray('1', $t->getPrecio_venta());
        $retenciones = $this->buildArray('2', $t->getPrecio_venta());
        $consulta = "INSERT INTO `tmpcotizacion` VALUES (:id, :idproducto, :tmpnombre, :cantidad, :precio, :importe, :descuento, :impdescuento, :imptotal, :traslados, :retenciones, :observaciones, :chinv, :clvfiscal, :clvunidad, :session);";
        $valores = array("id" => null,
            "idproducto" => $idproducto,
            "tmpnombre" => $t->getProducto(),
            "cantidad" => '1',
            "precio" => $t->getPrecio_venta(),
            "importe" => $t->getPrecio_venta(),
            "descuento" => '0',
            "impdescuento" => '0',
            "imptotal" => $t->getPrecio_venta(),
            "traslados" => $traslados,
            "retenciones" => $retenciones,
            "observaciones" => '',
            "chinv" => $t->getChinventario(),
            "clvfiscal" => $t->getClavefiscal()."-".$t->getDescripcionfiscal(),
            "clvunidad" => $t->getClvunidad()."-".$t->getDescripcionunidad(),
            "session" => $sessionid);
        $con = new Consultas();
        $insertado = $con->execute($consulta, $valores);
        return $insertado;
    }

    private function removerInventario($idproducto, $cantidad) {
        $consultado = false;
        $consulta = "UPDATE `productos_servicios` set cantinv=cantinv-:cantidad where idproser=:idproducto;";
        $consultas = new Consultas();
        $valores = array("idproducto" => $idproducto,
            "cantidad" => $cantidad);
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    private function getIDProductoAux($datos) {
        $consultado = false;
        $consulta = "SELECT * FROM productos_servicios WHERE codproducto=:datos;";
        $consultas = new Consultas();
        $valores = array("datos" => $datos);
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    private function getIDProducto($datos) {
        $idproducto = "";
        $producto = $this->getIDProductoAux($datos);
        foreach ($producto as $actual) {
            $idproducto = $actual['idproser'];
        }
        return $idproducto;
    }

    private function insertarInventario($p) {
        $insertado = false;
        $consulta = "UPDATE `productos_servicios` SET cantinv=:cantidad WHERE idproser=:id_producto;";
        $valores = array("id_producto" => $p->getIdProducto(), "cantidad" => $p->getCantidad());
        $con = new Consultas();
        $insertado = $con->execute($consulta, $valores);
        return $insertado;
    }

    public function nuevoProducto($p) {
        $insertado = false;
        $existe = $this->validarExistenciaProducto($p->getProducto(), $p->getIdproveedor(), 0);
        if (!$existe) {
            $insertado = $this->insertarProducto($p);
        }
        return $insertado;
    }

    private function validarExistenciaProducto($producto, $idclave, $idproducto) {
        $existe = false;
        $productos = $this->getProductoByNombre($producto);
        foreach ($productos as $productoactual) {
            $idproductoactual = $productoactual['idproser'];
            if ($idproductoactual != $idproducto) {
                $existe = true;
                echo "0Este producto ya existe";
                break;
            }
        }
        if (!$existe) {
            $productos = $this->getProductoByClave($idclave);
            foreach ($productos as $productoactual) {
                $idproductoactual = $productoactual['idproser'];
                if ($idproductoactual != $idproducto) {
                    $existe = true;
                    echo "0Este codigo de barras ya existe";
                    break;
                }
            }
        }
        return $existe;
    }

    private function getProductoByClave($idclave) {
        $consultado = false;
        $consulta = "SELECT * FROM productos_servicios WHERE idcatalogo_fis=:idclave;";
        $consultas = new Consultas();
        $valores = array("idclave" => $idclave);
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    private function getProductoByNombre($producto) {
        $consultado = false;
        $consulta = "SELECT * FROM productos_servicios WHERE nombre_producto=:producto;";
        $consultas = new Consultas();
        $valores = array("producto" => $producto);
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    private function getInventario() {
        $consultado = false;
        $consulta = "SELECT ps.*, ip.*, pr.empresa FROM productos_servicios as ps inner join inventario_productos as ip on (ps.codprod=ip.idpro_ser) inner join proveedor as pr on (ps.idproveedor=pr.idproveedor);";
        $consultas = new Consultas();
        $consultado = $consultas->getResults($consulta, null);
        return $consultado;
    }

    private function getProductos() {
        $consultado = false;
        $consulta = "SELECT ps.*, p.empresa FROM productos_servicios ps inner join proveedor p on (ps.idproveedor=p.idproveedor) order by ps.nombre_producto;";
        $consultas = new Consultas();
        $consultado = $consultas->getResults($consulta, null);
        return $consultado;
    }

    private function getProductoById($idproducto) {
        $consultado = false;
        $consultas = new Consultas();
        $consulta = "SELECT p.* FROM productos_servicios p WHERE p.idproser=:pid;";
        $valores = array("pid" => $idproducto);
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    public function datosProductos($idproducto) {
        $datos = "";
        $productos = $this->getProductoById($idproducto);
        foreach ($productos as $productoactual) {
            $id_producto = $productoactual['idproser'];
            $codigo = $productoactual['codproducto'];
            $nombre = addslashes($productoactual['nombre_producto']);
            $clvunidad = $productoactual['clv_unidad'];
            $descripcion_unidad = $productoactual['desc_unidad'];
            $descripcion_producto = $productoactual['descripcion_producto'];
            $pcompra = $productoactual['precio_compra'];
            $porcentaje = $productoactual['porcentaje'];
            $ganancia = $productoactual['ganancia'];
            $pventa = $productoactual['precio_venta'];
            $tipo = $productoactual['tipo'];
            $clavefiscal = $productoactual['clave_fiscal'];
            $descripcion = $productoactual['desc_fiscal'];
            $idproveedor = $productoactual['idproveedor'];
            $imagen = $productoactual['imagenprod'];
            $chinventario = $productoactual['chinventario'];
            $cantidad = $productoactual['cantinv'];
            $proveedor = "";
            if ($idproveedor != '0') {
                $proveedor = $this->getProveedor($idproveedor);
            }

            $img = "";
            if ($imagen != "") {
                $imgfile = "../img/productos/" . $imagen;
                $type = pathinfo($imgfile, PATHINFO_EXTENSION);
                $data = file_get_contents($imgfile);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                $img = "<img src=\"$base64\" width=\"200px\">";
            }

            $datos .= "$id_producto</tr>$codigo</tr>$nombre</tr>$clvunidad</tr>$descripcion_unidad</tr>$descripcion_producto</tr>$pcompra</tr>$porcentaje</tr>$ganancia</tr>$pventa</tr>$tipo</tr>$clavefiscal</tr>$descripcion</tr>$idproveedor</tr>$proveedor</tr>$imagen</tr>$chinventario</tr>$cantidad</tr>$img";
        }
        return $datos;
    }

    public function getInventarioById($idinventario) {
        $consultado = false;
        $consulta = "SELECT ps.*, ip.*, pr.empresa FROM productos_servicios as ps inner join inventario_productos as ip on (ps.codprod=ip.idpro_ser) inner join proveedor as pr on (ps.idproveedor=pr.idproveedor) where ip.id_inventario=:idinventario;";
        $valores = array("idinventario" => $idinventario);
        $consultas = new Consultas();
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    public function datosInventario($idinventario) {
        $datos = "";
        $productos = $this->getInventarioById($idinventario);
        foreach ($productos as $productoactual) {
            $id_producto = $productoactual['codprod'];
            $nombre = $productoactual['nombre_producto'];
            $empresa = $productoactual['empresa'];
            $id_inv = $productoactual['id_inventario'];
            $cantidad = $productoactual['cantinv'];
            $datos .= "$id_producto</tr>$nombre</tr>$empresa</tr>$id_inv</tr>$cantidad";
        }
        return $datos;
    }

    private function valCodigoActualizarAux($codigo, $idproducto) {
        $consultado = false;
        $consulta = "SELECT * FROM productos_servicios where codproducto = '$codigo' and idproser !='$idproducto';";
        $consultas = new Consultas();
        $consultado = $consultas->getResults($consulta, null);
        return $consultado;
    }

    public function valCodigoActualizar($p) {
        $cod = "";
        $datos = "";
        $validar = $this->valCodigoActualizarAux($p->getCodproducto(), $p->getIdProducto());
        foreach ($validar as $valactual) {
            $cod = $valactual['codproducto'];
        }
        if ($cod != "") {
            $datos = "0Ya existe un producto con este codigo.";
        } else {
            $datos = $this->actualizarProducto($p);
        }
        return $datos;
    }

    private function actualizarProducto($p) {
        $idproducto = $p->getIdProducto();
        $img = $p->getImagen();
        if ($img == '') {
            $img = $p->getImgactualizar();
        }
        $actualizado = false;
        $consulta = "UPDATE `productos_servicios` SET codproducto=:codproducto, nombre_producto=:producto, clv_unidad=:clvunidad, desc_unidad=:unidad, descripcion_producto=:descripcion, precio_compra=:pcompra, porcentaje=:porcentaje, ganancia=:ganancia, precio_venta=:pventa, tipo=:tipo, clave_fiscal=:clvfiscal, desc_fiscal=:descfiscal, idproveedor=:idproveedor, imagenprod=:imagen, chinventario=:chinventario, cantinv=:cantidad WHERE idproser=:id_producto;";
        $valores = array("codproducto" => $p->getCodproducto(),
            "producto" => $p->getProducto(),
            "clvunidad" => $p->getClvunidad(),
            "unidad" => $p->getDescripcionunidad(),
            "descripcion" => $p->getDescripcion(),
            "pcompra" => $p->getPrecio_compra(),
            "porcentaje" => $p->getPorcentaje(),
            "ganancia" => $p->getGanancia(),
            "pventa" => $p->getPrecio_venta(),
            "tipo" => $p->getTipo(),
            "clvfiscal" => $p->getClavefiscal(),
            "descfiscal" => $p->getDescripcionfiscal(),
            "idproveedor" => $p->getIdproveedor(),
            "imagen" => $img,
            "chinventario" => $p->getChinventario(),
            "cantidad" => $p->getCantidad(),
            "id_producto" => $p->getIdProducto());
        $con = new Consultas();
        $actualizado = $con->execute($consulta, $valores);
        if ($p->getImagen() != $p->getImgactualizar()) {
            if ($p->getImagen() != "") {
                rename('../temporal/tmp/' . $p->getImagen(), '../img/productos/' . $p->getImagen());
                unlink("../img/productos/" . $p->getImgactualizar());
            }
        }

        if ($p->getInsert() == 'f') {
            $concepto = $this->actualizarConcepto2($p);
        } else if ($p->getInsert() == 'c' || $p->getInsert() == 'ct') {
            $concepto = $this->actualizarConceptoCot($p);
        }
        return $actualizado;
    }

    private function actualizarConcepto2($t) {
        $check = $this->checkProductoAux($t->getIdtmp());
        foreach ($check as $actual) {
            $canttmp = $actual['cantidad_tmp'];
            $descuento_tmp = $actual['descuento_tmp'];
            $traslados = $actual['trasladotmp'];
            $retenciones = $actual['retenciontmp'];
            $idproducto = $actual['id_productotmp'];
        }
        $totuni = $canttmp * $t->getPrecio_venta();
        $impdescuento = $t->getPrecio_venta() * ($descuento_tmp / 100);
        $importe = bcdiv($totuni, '1', 2) - bcdiv($impdescuento, '1', 2);

        $rebuildT = $this->reBuildArray($importe, $traslados);
        $divT = explode("<cut>", $rebuildT);
        $traslados = $divT[0];
        $Timp = $divT[1];

        $rebuildR = $this->reBuildArray($importe, $retenciones);
        $divR = explode("<cut>", $rebuildR);
        $retenciones = $divR[0];
        $Tret = $divR[1];

        $total = (( bcdiv($importe, '1', 2) + bcdiv($Timp, '1', 2)) - bcdiv($Tret, '1', 2));

        $actualizado = false;
        $con = new Consultas();
        $consulta = "UPDATE `tmp` SET tmpnombre=:nombre, precio_tmp=:precio, totunitario_tmp=:totunitario, impdescuento_tmp=:impdesc, imptotal_tmp=:total, trasladotmp=:traslado, retenciontmp=:retencion, clvfiscaltmp=:cfiscal, clvunidadtmp=:cunidad  WHERE idtmp=:id;";
        $valores = array("id" => $t->getIdtmp(),
            "nombre" => $t->getProducto(),
            "precio" => $t->getPrecio_venta(),
            "totunitario" => $totuni,
            "impdesc" => $impdescuento,
            "total" => $total,
            "traslado" => $traslados,
            "retencion" => $retenciones,
            "cfiscal" => $t->getClavefiscal()."-".$t->getDescripcionfiscal(),
            "cunidad" => $t->getClvunidad()."-".$t->getDescripcionunidad());
        $insertado = $con->execute($consulta, $valores);
        return $insertado;
    }

    private function actualizarConceptoCot($t) {
        $check = $this->checkProductoCotAux($t->getIdtmp());
        foreach ($check as $actual) {
            $canttmp = $actual['cantidad_tmp'];
            $descuento_tmp = $actual['descuento_tmp'];
            $traslados = $actual['trasladotmp'];
            $retenciones = $actual['retenciontmp'];
            $idproducto = $actual['id_productotmp'];
        }
        $totuni = $canttmp * $t->getPrecio_venta();
        $impdescuento = $t->getPrecio_venta() * ($descuento_tmp / 100);
        $importe = bcdiv($totuni, '1', 2) - bcdiv($impdescuento, '1', 2);

        $rebuildT = $this->reBuildArray($importe, $traslados);
        $divT = explode("<cut>", $rebuildT);
        $traslados = $divT[0];
        $Timp = $divT[1];

        $rebuildR = $this->reBuildArray($importe, $retenciones);
        $divR = explode("<cut>", $rebuildR);
        $retenciones = $divR[0];
        $Tret = $divR[1];

        $total = (bcdiv($importe, '1', 2) + bcdiv($Timp, '1', 2)) - bcdiv($Tret, '1', 2);

        $actualizado = false;
        $consulta = "UPDATE `tmpcotizacion` SET tmpnombre=:nombre, precio_tmp=:precio, totunitario_tmp=:totunitario, impdescuento_tmp=:impdesc, imptotal_tmp=:total, trasladotmp=:traslado, retenciontmp=:retencion, clvfiscaltmp=:cfiscal, clvunidadtmp=:cunidad WHERE idtmpcotizacion=:id;";
        $valores = array("id" => $t->getIdtmp(),
            "nombre" => $t->getProducto(),
            "precio" => $t->getPrecio_venta(),
            "totunitario" => $totuni,
            "impdesc" => $impdescuento,
            "total" => $total,
            "traslado" => $traslados,
            "retencion" => $retenciones,
            "cfiscal" => $t->getClavefiscal()."-".$t->getDescripcionfiscal(),
            "cunidad" => $t->getClvunidad()."-".$t->getDescripcionunidad());
        $con = new Consultas();
        $insertado = $con->execute($consulta, $valores);
        return $insertado;
    }

    private function checkProductoAux($idtmp) {
        $consultado = false;
        $consulta = "SELECT t.*,p.cantinv,p.chinventario FROM tmp t inner join productos_servicios p on (t.id_productotmp=p.idproser) where t.idtmp=:id;";
        $val = array("id" => $idtmp);
        $consultas = new Consultas();
        $consultado = $consultas->getResults($consulta, $val);
        return $consultado;
    }

    private function checkProductoCotAux($idtmp) {
        $consultado = false;
        $consulta = "SELECT t.*,p.cantinv,p.chinventario FROM tmpcotizacion t inner join productos_servicios p on (t.id_productotmp=p.idproser) where t.idtmpcotizacion=:id;";
        $val = array("id" => $idtmp);
        $consultas = new Consultas();
        $consultado = $consultas->getResults($consulta, $val);
        return $consultado;
    }

    private function reBuildArray($importe, $array) {
        $div = explode("<impuesto>", $array);
        $row = array();
        $Timp = 0;
        foreach ($div as $d) {
            $div2 = explode("-", $d);
            $imp = $importe * $div2[1];
            $Timp += $imp;
            if ($Timp > 0) {
                $row[] = bcdiv($imp, '1', 2) . '-' . $div2[1] . '-' . $div2[2];
            }
        }
        $rearray = implode("<impuesto>", $row);
        return $rearray . "<cut>" . $Timp;
    }

    public function actualizarInventario($p) {
        $actualizado = false;
        $consulta = "UPDATE `inventario_productos` SET cantinv=:cantidad WHERE id_inventario=:id and idpro_ser=:idproser;";
        $valores = array("id" => $p->getIdinventario(), "cantidad" => $p->getCantidad(), "idproser" => $p->getIdProducto());
        $con = new Consultas();
        $actualizado = $con->execute($consulta, $valores);
        return $actualizado;
    }

    public function quitarProducto($idproducto) {
        $eliminado = false;
        $eliminado = $this->eliminarProducto($idproducto);
        return $eliminado;
    }

    public function eliminarProducto($idproducto) {
        $eliminado = false;
        $consulta = "DELETE FROM `productos_servicios` WHERE idproser=:id_producto;";
        $valores = array("id_producto" => $idproducto);
        $con = new Consultas();
        $eliminado = $con->execute($consulta, $valores);
        return $eliminado;
    }

    public function actualizarEstatusProducto($idproducto, $estatus) {
        $eliminado = false;
        $consulta = "UPDATE `producto` SET estatus=:estatus WHERE id_producto=:id_producto;";
        $valores = array("estatus" => $estatus, "id_producto" => $idproducto);
        $con = new Consultas();
        $eliminado = $con->execute($consulta, $valores);
        return $eliminado;
    }

    private function getNumrowsAux($condicion) {
        $consultado = false;
        $consulta = "SELECT COUNT(*) numrows FROM productos_servicios p $condicion;";
        $consultas = new Consultas();
        $consultado = $consultas->getResults($consulta, null);
        return $consultado;
    }

    private function getNumrows($condicion) {
        $numrows = 0;
        $rows = $this->getNumrowsAux($condicion);
        foreach ($rows as $actual) {
            $numrows = $actual['numrows'];
        }
        return $numrows;
    }

    private function getSevicios($condicion) {
        $consultado = false;
        $consulta = "SELECT p.* FROM productos_servicios p $condicion;";
        $consultas = new Consultas();
        $consultado = $consultas->getResults($consulta, null);
        return $consultado;
    }

    private function getPermisoById($idusuario) {
        $consultado = false;
        $consulta = "SELECT * FROM usuariopermiso p where permiso_idusuario=:idusuario;";
        $c = new Consultas();
        $valores = array("idusuario" => $idusuario);
        $consultado = $c->getResults($consulta, $valores);
        return $consultado;
    }

    private function getPermisos($idusuario) {
        $datos = "";
        $permisos = $this->getPermisoById($idusuario);
        foreach ($permisos as $actual) {
            $crear = $actual['crearproducto'];
            $editar = $actual['editarproducto'];
            $eliminar = $actual['eliminarproducto'];
            $datos .= "$editar</tr>$eliminar</tr>$crear";
        }
        return $datos;
    }

    private function getProveedorAux($pid) {
        $datos = false;
        $con = new Consultas();
        $consulta = "SELECT * FROM proveedor WHERE idproveedor=:pid";
        $val = array("pid" => $pid);
        $datos = $con->getResults($consulta, $val);
        return $datos;
    }

    private function getProveedor($pid) {
        $nombre = false;
        $datos = $this->getProveedorAux($pid);
        foreach ($datos as $actual) {
            $nombre = $actual['empresa'];
        }
        return $nombre;
    }

    public function listaProductosHistorial($NOM, $pag, $numreg) {
        include '../com.sine.common/pagination.php';
        session_start();
        $idlogin = $_SESSION[sha1("idusuario")];
        $datos = "<thead>
            <tr>
                <th>Codigo</th>
                <th>Producto/Servicio </th>
                <th>Unidad </th>
                <th class='col-md-2'>P. Compra </th>
                <th class='col-md-2'>P. Venta</th>
                <th class='col-md-3'>Clave Fiscal </th>
                <th>Proveedor</th>
                <th>Inventario</th>
                <th>Cantidad</th>
                <th>Opcion</th>
            </tr>
        </thead>
        <tbody>";
        $condicion = "";
        if ($NOM == "") {
            $condicion = "order by p.codproducto";
        } else {
            //$condicionfolio="AND  nombre LIKE '%$Nombr%' AND (d.RazonSocial LIKE '%$Razon%' OR d.Rfc LIKE '%$Razon%')  ORDER BY nombre DESC";
            $condicion = "where (p.nombre_producto LIKE '%$NOM%' or p.codproducto LIKE '%$NOM%') order by p.codproducto";
        }
        $numrows = $this->getNumrows($condicion);
        $page = (isset($pag) && !empty($pag)) ? $pag : 1;
        $per_page = $numreg;
        $adjacents = 4;
        $offset = ($page - 1) * $per_page;
        $total_pages = ceil($numrows / $per_page);
        $con = $condicion . " LIMIT $offset,$per_page ";
        $productos = $this->getSevicios($con);
        $finales = 0;
        $permisos = $this->getPermisos($idlogin);
        $div = explode("</tr>", $permisos);
        foreach ($productos as $productoactual) {
            $id_producto = $productoactual['idproser'];
            $codigo = $productoactual['codproducto'];
            $nombre = $productoactual['nombre_producto'];
            $unidad = $productoactual['desc_unidad'];
            $descripcion_producto = $productoactual['descripcion_producto'];
            $pcompra = $productoactual['precio_compra'];
            $pventa = $productoactual['precio_venta'];
            $tipo = $productoactual['tipo'];
            $clavefiscal = $productoactual['clave_fiscal'];
            $descripcion = $productoactual['desc_fiscal'];
            $chinventario = $productoactual['chinventario'];
            $cantidad = $productoactual['cantinv'];
            $idproveedor = $productoactual['idproveedor'];

            $estadoinv = "Inactivo";
            $color = "#ED495C";
            $title = "Activar Inventario";
            $function = "onclick='setCant($id_producto)'";
            $modal = "data-toggle='modal' data-target='#cambiarestado'";

            $class2 = "btn-default";
            $title2 = "Activar Inventario";
            $function2 = "onclick='setCant($id_producto)'";
            $modal2 = "data-toggle='modal' data-target='#cambiarestado'";

            if ($chinventario == '1') {
                $estadoinv = "Activo";
                $color = "#34A853";
                $title = "Desactivar Inventario";
                $function = "onclick='desactivarInventario($id_producto)'";
                $modal = "";

                $class2 = "btn-primary";
                $title2 = "Cambiar Cantidad";
                $function2 = "onclick='setCantInventario($id_producto,$cantidad)'";
                $modal2 = "data-toggle='modal' data-target='#cambiarcantidad'";
            }

            if ($tipo == '2') {
                $estadoinv = "Inactivo";
                $color = "#ED495C";
                $title = "Un servicio no puede tener inventario";
                $function = "";
                $modal = "";

                $class2 = "btn-default";
                $title2 = "No Disponible";
                $function2 = "";
                $modal2 = "";
            }

            if ($div[0] == '1') {
                $editar = "<li><a onclick='editarProducto($id_producto);'>Editar Producto <span class='glyphicon glyphicon-edit'></span></a></li>";
            } else {
                $editar = "";
            }
            if ($div[1] == '1') {
                $eliminar = "<li><a onclick='eliminarProducto($id_producto);'>Eliminar Producto <span class='glyphicon glyphicon-remove'></span></a></li>";
            } else {
                $eliminar = "";
            }
            if ($div[2] == '1') {
                $copiar = "<li><a onclick='copiarProducto($id_producto);'>Copiar Producto <span class='glyphicon glyphicon-copy'></span></a></li>";
            } else {
                $copiar = "";
            }

            $proveedor = "No Disponible";
            if ($idproveedor != '0') {
                $proveedor = $this->getProveedor($idproveedor);
            }

            $datos .= "
                    <tr>
                        <td>$codigo</td>
                        <td>$nombre - $descripcion_producto</td>
                        <td>$unidad</td>
                        <td>$ " . number_format($pcompra, 2, '.', ',') . "</td>
                        <td>$ " . number_format($pventa, 2, '.', ',') . "</td>
                        <td>$clavefiscal - $descripcion</td>
                        <td>$proveedor</td>
                        <td><a class='state-link' style='color: $color;' $modal $function title='$title'><span>$estadoinv</span></a></td>
                        <td><a class='state-link' $modal2 $function2 title='$title2'><span>$cantidad</span></a></td>
                        <td align='center'><div class='dropdown'>
                        <button class='button-list dropdown-toggle' title='Opciones'  type='button' data-toggle='dropdown'><span class='glyphicon glyphicon-option-vertical'></span>
                        <span class='caret'></span></button>
                        <ul class='dropdown-menu dropdown-menu-right'>
                        $editar
                        $eliminar
                        $copiar
                        </ul>
                        </div></td>
                    </tr>
                     ";
            $finales++;
        }
        $inicios = $offset + 1;
        $finales += $inicios - 1;
        $function = "buscarProducto";
        $datos .= "</tbody><tfoot><tr><th colspan='12'>Mostrando $inicios al $finales de $numrows registros " . paginate($page, $total_pages, $adjacents, $function) . "</th></tr></tfoot>";
        if ($finales == 0) {
            $datos .= "<tr><td class='text-center' colspan='12'>No se encontraron registros</td></tr>";
        }
        return $datos;
    }

}