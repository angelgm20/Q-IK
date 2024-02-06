<?php
require_once '../com.sine.modelo/Producto.php';
require_once '../com.sine.controlador/ControladorProducto.php';

if(isset($_POST['transaccion'])){
    $transaccion=$_POST['transaccion'];    
    switch ($transaccion) {
        case 'insertarproducto':
            $p=new Producto();
            $cp=new ControladorProducto();
            
            $producto=$_POST['producto'];
            $precio=$_POST['precio'];
            $codigointerno=$_POST['codigointerno'];
            $codigobarras=$_POST['codigobarras'];
            $modelo=$_POST['modelo'];
            $fechacompra=$_POST['fechacompra'];
            $marca=$_POST['marca'];
            $descripcion=$_POST['descripcion'];
            $stock=$_POST['stock'];
            $costo=$_POST['costo'];
            $idcategoria=$_POST['idcategoria'];
            
            $p->setProducto($producto);
            $p->setPrecio($precio);
            $p->setCodigoInterno($codigointerno);
            $p->setCodigoBarras($codigobarras);
            $p->setModelo($modelo);
            $p->setFechaCompra($fechacompra);
            $p->setMarca($marca);
            $p->setDescripcion($descripcion);
            $p->setStock($stock);
            $p->setCosto($costo);
            $p->setIdCategoria($idcategoria);
            $p->setEstatus("activo");
            
            $insertado=$cp->nuevoProducto($p);
            if($insertado){
                echo "Registro guardado correctamente";
            }
            else{
                echo "0No se registro el producto";
            }            
            break;
        case 'listaproductosaltas':
            $datos="";
            $cp=new ControladorProducto();
            $datos=$cp->listaProductos();
            if($datos!=""){
                echo $datos;
            }
            else{
                echo "0Ah ocurrido un error";
            }
            break;
        case 'editarproducto':
            $datos="";
            $idproducto=$_POST['idproducto'];
            $cp=new ControladorProducto();
            $datos=$cp->datosProductos($idproducto);
            if($datos!=""){
                echo $datos;
            }
            else{
                echo "0Ah ocurrido un error";
            }
            break;
        case 'actualizarproducto':
            $p=new Producto();
            $cp=new ControladorProducto();
            $idproducto=$_POST['idproducto'];
            $producto=$_POST['producto'];
            $precio=$_POST['precio'];
            $codigointerno=$_POST['codigointerno'];
            $codigobarras=$_POST['codigobarras'];
            $modelo=$_POST['modelo'];
            $fechacompra=$_POST['fechacompra'];
            $marca=$_POST['marca'];
            $descripcion=$_POST['descripcion'];
            $stock=$_POST['stock'];
            $costo=$_POST['costo'];
            $idcategoria=$_POST['idcategoria'];
            
            $p->setIdProducto($idproducto);
            $p->setProducto($producto);
            $p->setPrecio($precio);
            $p->setCodigoInterno($codigointerno);
            $p->setCodigoBarras($codigobarras);
            $p->setModelo($modelo);
            $p->setFechaCompra($fechacompra);
            $p->setMarca($marca);
            $p->setDescripcion($descripcion);
            $p->setStock($stock);
            $p->setCosto($costo);
            $p->setIdCategoria($idcategoria);
            $p->setEstatus('activo');
            
            $actualizado=$cp->modificarProducto($p);
            if($actualizado){
                echo "Registro guardado correctamente";
            }
            else{
                echo "0Ah ocurrido un error";
            }
            break;
        case 'eliminarproducto':
            $datos="";
            $idproducto=$_POST['idproducto'];
            $cp=new ControladorProducto();
            $eliminado=$cp->quitarProducto($idproducto);
            if($eliminado){
                echo "Registro eliminado";
            }
            else{
                echo "0Ah ocurrido un error";
            }
            break;
        default:
            break;
    }
}


