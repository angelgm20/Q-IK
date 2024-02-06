<?php

require_once '../com.sine.dao/Consultas.php';

class ControladorProveedor {

    function __construct() {
        
    }

    private function insertarProveedor($p) {
        $registrado = false;
        $consulta = "INSERT INTO `proveedor` VALUES (:id, :empresa, :representante, :telefono, :email, :cuenta, :clabe, :idbanco, :sucursal, :rfc, :razon);";
        $valores = array("id" => null,
            "empresa" => $p->getEmpresa(),
            "representante" => $p->getRepresentante(),
            "telefono" => $p->getTelefono(),
            "email" => $p->getEmail(),
            "cuenta" => $p->getNum_cuenta(),
            "clabe" => $p->getClave_interbancaria(),
            "idbanco" => $p->getId_banco(),
            "sucursal" => $p->getSucursal(),
            "rfc" => $p->getRfc(),
            "razon" => $p->getRazon());
        $consultas = new Consultas();
        $registrado = $consultas->execute($consulta, $valores);
        return $registrado;
    }

    public function getFiltrado($condicion) {
        $consultado = false;
        $consulta = "SELECT * FROM proveedor p $condicion;";
        $consultas = new Consultas();
        $consultado = $consultas->getResults($consulta, null);
        return $consultado;
    }

    public function getProveedorById($idproveedor) {
        $consultado = false;
        $consulta = "SELECT * FROM proveedor AS p where p.idproveedor=:idproveedor;";
        $c = new Consultas();
        $valores = array("idproveedor" => $idproveedor);
        $consultado = $c->getResults($consulta, $valores);
        return $consultado;
    }

    public function getDatosProveedor($idproveedor) {
        $proveedor = $this->getProveedorById($idproveedor);
        $datos = "";
        foreach ($proveedor as $proveedoractual) {
            $idproveedor = $proveedoractual['idproveedor'];
            $empresa = $proveedoractual['empresa'];
            $representante = $proveedoractual['representante'];
            $telefono = $proveedoractual['telefono'];
            $correo = $proveedoractual['email'];
            $cuenta = $proveedoractual['num_cuenta'];
            $clabe = $proveedoractual['clabe_interban'];
            $idbanco = $proveedoractual['idbanco'];
            $sucursal = $proveedoractual['nsucursal'];
            $rfc = $proveedoractual['rfc'];
            $razon = $proveedoractual['razon_social'];
            $datos = "$idproveedor</tr>$empresa</tr>$representante</tr>$telefono</tr>$correo</tr>$cuenta</tr>$clabe</tr>$idbanco</tr>$sucursal</tr>$rfc</tr>$razon";
            break;
        }
        return $datos;
    }

    public function nuevoProveedor($p) {
        $existe = $this->validarExistenciaProveedor($p->getEmpresa(), $p->getNum_cuenta(), $p->getClave_interbancaria(), 0);
        $insertado = false;
        if (!$existe) {
            $insertado = $this->insertarProveedor($p);
        }
        return $insertado;
    }

    public function modificarProveedor($p) {
        $existe = $this->validarExistenciaProveedor($p->getEmpresa(), $p->getNum_cuenta(), $p->getClave_interbancaria(), $p->getId_proveedor());
        $actualizado = false;
        if (!$existe) {
            $actualizado = $this->actualizarProveedor($p);
        }
        return $actualizado;
    }

    public function validarExistenciaProveedor($empresa, $cuenta, $clabe, $idproveedor) {
        $existe = false;
        $proveedores = $this->getProveedorByEmpresa($empresa);
        foreach ($proveedores as $proveedoractual) {
            $idproveedoractual = $proveedoractual['idproveedor'];
            if ($idproveedoractual != $idproveedor) {
                echo "0Esta empresa ya esta registrada como proveedor";
                $existe = true;
                break;
            }
        }
        if (!$existe) {
            $proveedores = $this->getProveedorByCuenta($cuenta);
            foreach ($proveedores as $proveedoractual) {
                $$idproveedoractual = $proveedoractual['idproveedor'];
                if ($idproveedoractual != $idproveedor) {
                    echo "0Ya existe un proveedor con este numero de cuenta";
                    $existe = true;
                    break;
                }
            }
        }
        if (!$existe) {
            $proveedores = $this->getProveedorByClabe($clabe);
            foreach ($proveedores as $proveedoractual) {
                $idproveedoractual = $proveedoractual['idproveedor'];
                if ($idproveedoractual != $idproveedor) {
                    echo "0Ya existe un proveedor con esta clabe interbancaria";
                    $existe = true;
                    break;
                }
            }
        }
        return $existe;
    }

    public function getProveedorByEmpresa($empresa) {
        $consultado = false;
        $consulta = "SELECT * FROM proveedor WHERE empresa=:empresa;";
        $consultas = new Consultas();
        $valores = array("empresa" => $empresa);
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    public function getProveedorByCuenta($cuenta) {
        $consultado = false;
        $consulta = "SELECT * FROM proveedor WHERE num_cuenta=:cuenta;";
        $consultas = new Consultas();
        $valores = array("cuenta" => $cuenta);
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    public function getProveedorByClabe($clabe) {
        $consultado = false;
        $consulta = "SELECT * FROM proveedor WHERE rfc=:clabe;";
        $consultas = new Consultas();
        $valores = array("clabe" => $clabe);
        $consultado = $consultas->getResults($consulta, $valores);
        return $consultado;
    }

    private function actualizarProveedor($p) {
        $actualizado = false;
        $consulta = "UPDATE `proveedor` SET  empresa=:empresa, representante=:representante, telefono=:telefono, email=:email, num_cuenta=:cuenta, clabe_interban=:clabe, idbanco=:idbanco, nsucursal=:sucursal WHERE idproveedor=:id;";
        $valores = array("empresa" => $p->getEmpresa(),
            "representante" => $p->getRepresentante(),
            "telefono" => $p->getTelefono(),
            "email" => $p->getEmail(),
            "cuenta" => $p->getNum_cuenta(),
            "clabe" => $p->getClave_interbancaria(),
            "idbanco" => $p->getId_banco(),
            "sucursal" => $p->getSucursal(),
            "id" => $p->getId_proveedor());
        $consultas = new Consultas();
        $actualizado = $consultas->execute($consulta, $valores);
        return $actualizado;
    }

    public function getEstatusUsuario($estatus, $id) {
        $b = "";
        if ($estatus == "activo") {
            $b = "<button class='btn btn-xs btn-success' onclick='bajaUsuario($id)' title='Desactivar este usuario'><span class='glyphicon glyphicon-ok'></span></button>";
        } else {
            $b = "<button class='btn btn-xs btn-warning' onclick='altaUsuario($id)' title='Reactivar este usuario'><span class='glyphicon glyphicon-remove'></span></button>";
        }
        return $b;
    }

    public function quitarProveedor($idproveedor) {
        $eliminado = $this->eliminarProveedor($idproveedor);

        return $eliminado;
    }

    private function eliminarProveedor($idproveedor) {
        $eliminado = false;
        $consulta = "DELETE FROM `proveedor` WHERE idproveedor=:id;";
        $valores = array("id" => $idproveedor);
        $consultas = new Consultas();
        $eliminado = $consultas->execute($consulta, $valores);
        return $eliminado;
    }

    private function getNumrowsAux($condicion) {
        $consultado = false;
        $consulta = "SELECT count(*) numrows FROM proveedor AS p $condicion;";
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
            $editar = $actual['editarproveedor'];
            $eliminar = $actual['eliminarproveedor'];
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

    public function listaServiciosHistorial($REF, $pag, $numreg) {
        include '../com.sine.common/pagination.php';
        session_start();
        $idlogin = $_SESSION[sha1("idusuario")];
        $datos = "<thead>
            <tr>
                <th>Empresa</th>
                <th>Representante</th>
                <th>Telefono</th>
                <th>Correo</th>
                <th>Banco</th>
                <th>Sucursal</th>
                <th>N° de Cuenta</th>
                <th>Clabe Interbancaria</th>
                <th><span class='glyphicon glyphicon-edit'></span></th>
                <th><span class='glyphicon glyphicon-remove'></span></th>
            </tr>
        </thead>
        <tbody>";
        $condicion = "";
        if ($REF == "") {
            $condicion = " ORDER BY p.empresa";
        } else {
            $condicion = "where (p.empresa LIKE '%$REF%' OR p.representante LIKE '%$REF%') ORDER BY p.empresa";
        }

        $numrows = $this->getNumrows($condicion);
        $page = (isset($pag) && !empty($pag)) ? $pag : 1;
        $per_page = $numreg;
        $adjacents = 4;
        $offset = ($page - 1) * $per_page;
        $total_pages = ceil($numrows / $per_page);
        $con = $condicion . " LIMIT $offset,$per_page ";
        $proveedores = $this->getFiltrado($con);
        $finales = 0;
        $permisos = $this->getPermisos($idlogin);
        $div = explode("</tr>", $permisos);
        foreach ($proveedores as $proveedoractual) {
            $id_proveedor = $proveedoractual['idproveedor'];
            $empresa = $proveedoractual['empresa'];
            $representante = $proveedoractual['representante'];
            $telefono = $proveedoractual['telefono'];
            $email = $proveedoractual['email'];
            $num_cuenta = $proveedoractual['num_cuenta'];
            $clabe = $proveedoractual['clabe_interban'];
            $banco = "No Disponible";
            if($proveedoractual['idbanco'] != '0'){
                $banco = $this->getNomBanco($proveedoractual['idbanco']);
            }
            $sucursal = $proveedoractual['nsucursal'];

            if ($div[0] == '1') {
                $editar = "<button class='button-list' onclick='editarProveedor($id_proveedor)' title='Editar este proveedor'><span class='glyphicon glyphicon-edit'></span></button>";
            } else {
                $editar = "";
            }
            if ($div[1] == '1') {
                $eliminar = "<button class='button-list' onclick='eliminarProveedor($id_proveedor)' title='Editar este proveedor'><span class='glyphicon glyphicon-remove'></span></button>";
            } else {
                $eliminar = "";
            }

            $datos .= "<tr>
                         <td>$empresa</td>
                         <td>$representante</td>
                         <td>$telefono</td>
                         <td style='word-break: break-all;'>$email</td>
                         <td>$banco</td>
                         <td>$sucursal</td>
                         <td>$num_cuenta</td>
                         <td>$clabe</td>
                         <td>$editar</td>
                         <td>$eliminar</td>
                    </tr>";
            $finales++;
        }
        $inicios = $offset + 1;
        $finales += $inicios - 1;
        $function = "buscarProveedor";
        if ($finales == 0) {
            $datos .= "<tr><td class='text-center' colspan='10'>No se encontraron registros</td></tr>";
        }
        $datos .= "</tbody><tfoot><tr><th colspan='10'>Mostrando $inicios al $finales de $numrows registros " . paginate($page, $total_pages, $adjacents, $function) . "</th></tr></tfoot>";

        return $datos;
    }

}
