<?php
require_once '../com.sine.dao/Consultas.php';

class Controladorcliente {

    function __construct() {
    }

    private function insertarCliente($p) {
        //$registrado = false;
        $consulta = "INSERT INTO cliente (id_cliente, nombre, apaterno, amaterno, nombre_empresa, email_informacion, email_facturacion, email_gerencia, telefono, idbanco, cuenta, clabe, rfc)  VALUES (:id, :nombre, :apaterno, :amaterno, :nombre_empresa, :email_informacion, :email_facturacion, :email_gerencia, :telefono, :idbanco, :cuenta, :clabe, :rfc);";
        $valores = array("id" => null,
            "nombre" => $p->getNombre(),    
            "apaterno" => $p->getApellidoPaterno(),
            "amaterno" => $p->getApellidoMaterno(),
            "rfc"=> $p->getRfc(),
            "nombre_empresa" => $p->getNombre_empresa(),
            "email_informacion" => $p->getCorreoinfo(),
            "email_facturacion" => $p->getCorreo_fact(),
            "email_gerencia" => $p->getCorreo_gerencia(),
            "telefono" => $p->getTelefono(),
            "idbanco" => $p->getIdbanco(),
            "cuenta" => $p->getCuenta(),
            "clabe" => $p->getClabe());
            
        $consultas = new Consultas();
        $registrado = $consultas->execute($consulta, $valores);
        return $registrado;
    }
    public function getFiltrado($condicion) {
        $consultado = false;
        $consulta = "SELECT * FROM cliente c $condicion;";
        $consultas = new Consultas();
        $consultado = $consultas->getResults($consulta, null);
        return $consultado;
    }
    public function getClienteById($idcliente) {
        $consultado = false;
        $consulta = "SELECT * FROM cliente AS c where c.id_cliente=:idcliente;";
        $c = new Consultas();
        $valores = array("idcliente" => $idcliente);
        $consultado = $c->getResults($consulta, $valores);
        return $consultado;
    }
    public function editarCliente($idcliente) {
        $clientes = $this->getClienteById($idcliente);
        $datos = "";
        foreach ($clientes as $clienteactual) {
            $idCliente = $clienteactual['id_cliente'];
            $nombre = $clienteactual['nombre'];
            $apellidopaterno = $clienteactual['apaterno'];
            $apellidomaterno = $clienteactual['amaterno'];
            $rfc = $clienteactual['rfc'];
            $nombreEmpresa = $clienteactual['nombre_empresa']; 
            $setcorreoInfo = $clienteactual['email_informacion'];
            $emailfacturacion = $clienteactual['email_facturacion'];
            $emailGerencia = $clienteactual['email_gerencia'];
            //$emailadicional1 = $clienteactual['emailadicional1'];
            //$emailadicional2 = $clienteactual['emailadicional2'];
            $telefono = $clienteactual['telefono'];
            $idbanco = $clienteactual['idbanco'];
            $cuenta = $clienteactual['cuenta'];
            $clabe = $clienteactual['clabe'];
            $datos = "$idCliente</tr>$nombre</tr>$apellidopaterno</tr>$apellidomaterno</tr>$rfc</tr>$nombreEmpresa</tr>$setcorreoInfo</tr>$emailfacturacion</tr>$emailGerencia</tr>$telefono</tr>$idbanco</tr>$cuenta</tr>$clabe";
            
        }
        return $datos;
    }
    
        
    

    public function nuevoCliente($p) {
        //$existe = $this->validarExistenciaCliente($p->getRfc(), 0);
        $existe = FALSE;
        //$insertado = false;
        if (!$existe) {
            $insertado = $this->insertarCliente($p);
        }
        return $insertado;
    }
    public function modificarCliente($p) {
        //$existe = $this->validarExistenciaCliente($p->getRfc(), 0);
        $existe = FALSE;
        //$insertado = false;
        if (!$existe) {
            $actualizado = $this->actualizarCliente($p);
        }
        return $actualizado;
    }
   
    public function getClienteByNombre($nombre) {
        $consultado = false;
        $consulta = "SELECT * FROM cliente WHERE nombre=:nombre;";
        $consultas = new Consultas();
        $valores = array("nombre" => $nombre);
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    public function getClienteByCuenta($cuenta) {
        $consultado = false;
        $consulta = "SELECT * FROM clientes WHERE cuenta=:cuenta;";
        $consultas = new Consultas();
        $valores = array("cuenta" => $cuenta);
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    public function getClienteByClabe($clabe) {
        $consultado = false;
        $consulta = "SELECT * FROM cliente WHERE clabe=:clabe;";
        $consultas = new Consultas();
        $valores = array("clabe" => $clabe);
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    private function actualizarCliente($p) {
        //$actualizado = false;
        $consulta = "UPDATE `cliente` SET  nombre=:nombre, apaterno=:apaterno, amaterno=:amaterno, nombre_empresa=:nombre_empresa, email_informacion=:email_informacion, email_facturacion=:email_facturacion, email_gerencia=:email_gerencia, telefono=:telefono, idbanco=:idbanco, cuenta=:cuenta, clabe=:clabe, rfc=:rfc WHERE id_cliente=:id;";
        $valores = array(
            "nombre" => $p->getNombre(),
            "apaterno" => $p->getApellidoPaterno(),
            "amaterno" => $p->getApellidoMaterno(),
            "rfc" => $p->getRfc(),
            "nombre_empresa" => $p->getNombre_empresa(),
            "email_informacion" => $p->getCorreoinfo(),
            "email_facturacion" => $p->getCorreo_fact(),
            "email_gerencia" => $p->getcorreo_gerencia(),
            "telefono" => $p->getTelefono(),
            "idbanco" => $p->getIdbanco(),
            "cuenta" => $p->getCuenta(),
            "clabe" => $p->getClabe(),
            "id" => $p->getIdCliente()
        );
    
        $consultas = new Consultas();
        $actualizado = $consultas->execute($consulta, $valores);
        return $actualizado;
    }
    




     function eliminarCliente($idcliente) {
        $eliminado = false;
        $consulta = "DELETE FROM `cliente` WHERE id_cliente=:id;";
        $valores = array("id" => $idcliente);
        $consultas = new Consultas();
        $eliminado = $consultas->execute($consulta, $valores);
        return $eliminado;
    }
    private function getNumrowsAux($condicion) {
        $consultado = false;
        $consulta = "SELECT count(*) numrows FROM cliente AS c $condicion;";
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
            $editar = $actual['editarcliente'];
            $eliminar = $actual['eliminarcliente'];
            $datos .= "$editar</tr>$eliminar";
        }
        return $datos;
    }
    
    private function getNombancoaux($idbanco) {
        $consultado = false;
        $consulta = "select nombre_banco from catalogo_banco where idcatalogo_banco=$idbanco;";
        $consultas = new Consultas();
        $consultado = $consultas->getResults($consulta, null);
        return $consultado;
    }

    public function getNomBanco($idbanco) {
        $banco = $this->getNombancoaux($idbanco);
        $nombre = "";
        foreach ($banco as $bactual) {
            $nombre = $bactual['nombre_banco'];
        }
        return $nombre;
    }

    public function listaServiciosHistorialCliente($REF, $pag, $numreg) {
        include '../com.sine.common/pagination.php';
        session_start();
        $idlogin = $_SESSION[sha1("idusuario")];
        $datos = "<thead>
            <tr>
                <th>Nombre</th>
                <th>RFC</th>
                <th>EMPRESA</th>

                <th>correo facturacion</th>
                <th>Telefono</th>
                <th>cuenta</th>
               
            </tr>
        </thead>
        <tbody>";
        $condicion = "";
        if ($REF == "") {
            $condicion = " ORDER BY c.nombre";
        } else {
            $condicion = "where (c.nombre LIKE '%$REF%' OR c.amaterno LIKE '%$REF%') ORDER BY c.nombre";
        }

        $numrows = $this->getNumrows($condicion);
        $page = (isset($pag) && !empty($pag)) ? $pag : 1;
        $per_page = $numreg;
        $adjacents = 4;
        $offset = ($page - 1) * $per_page;
        $total_pages = ceil($numrows / $per_page);
        $con = $condicion . " LIMIT $offset,$per_page ";
        $clientes = $this->getFiltrado($con);
        $finales = 0;
        $permisos = $this->getPermisos($idlogin);
        $div = explode("</tr>", $permisos);
        foreach ($clientes as $clienteactual) {
            $id_cliente = $clienteactual['id_cliente'];
            $nombre = $clienteactual['nombre'];
            $apellidopaterno = $clienteactual['apaterno'];
            $apellidomaterno = $clienteactual['amaterno'];
            $rfc = $clienteactual['rfc'];
            $nombreEmpresa = $clienteactual['nombre_empresa'];
            $correoinfo = $clienteactual['email_informacion'];
            $emailfacturacion = $clienteactual['email_facturacion'];
            $emailGerencia = $clienteactual['email_gerencia'];
            $telefono = $clienteactual['telefono'];
            $idbanco = $clienteactual['idbanco'];
            $cuenta = $clienteactual['cuenta'];
            $clabe = $clienteactual['clabe'];
            if($clienteactual['idbanco'] != '0'){
                $banco = $this->getNomBanco($clienteactual['idbanco']);
            }
            
            if ($div[0] == '1') {
                $editar = "<button class='button-list' onclick='editarCliente($id_cliente)' title='Editar este cliente'><span class='glyphicon glyphicon-edit'></span></button>";
            } else {
                $editar = "";
            }
            if ($div[1] == '1') {
                $eliminar = "<button class='button-list' onclick='eliminarCliente($id_cliente)' title='Eliminar este cliente'><span class='glyphicon glyphicon-remove'></span></button>";
            } else {
                $eliminar = "";
            }

            $datos .= "<tr>
                         <td> $nombre $apellidopaterno<p></p> $apellidomaterno </td> 
                         <td>$rfc</td>
                         <td>$nombreEmpresa</td>
                         <td style='word-break: break-all;'>$emailfacturacion</td>
                         <td>$telefono</td>
                         <td>$cuenta</td>
                         <td>$editar</td>
                         <td>$eliminar</td>
                    </tr>";
            $finales++;
        }
        $inicios = $offset + 1;
        $finales += $inicios - 1;
        $function = "buscarCliente";
        if ($finales == 0) {
            $datos .= "<tr><td class='text-center' colspan='10'>No se encontraron registros</td></tr>";
        }
        $datos .= "</tbody><tfoot><tr><th colspan='10'>Mostrando $inicios al $finales de $numrows registros " . paginate($page, $total_pages, $adjacents, $function) . "</th></tr></tfoot>";

        return $datos;
    }

}

?>