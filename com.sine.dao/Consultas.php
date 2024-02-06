<?php
require 'Conexion.php';

class Consultas extends Conexion {

    private $conexion;

    function __construct() {
        $this->conexion = parent::conectar();
    }

    public function execute($consulta, $valores) {
        $resultado = false;
        $statement = $this->conexion->prepare($consulta);
        if ($statement) {
            if (preg_match_all("/(:\w+)/", $consulta, $campo, PREG_PATTERN_ORDER)) {
                $campo = array_pop($campo);
                foreach ($campo as $parametro) {
                    $statement->bindValue($parametro, $valores[substr($parametro, 1)]);
                }
            }
            try {
                if (!$statement->execute()) {
                    throw new Exception('No puede ejecutar la consulta. ' . implode(', ', $statement->errorInfo()));
                }
                $resultado = $statement->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $evento) {
                throw new Exception('Error en la ejecución de la consulta. ' . $evento->getMessage());
            } finally {
                $statement->closeCursor();
                $this->conexion = null; // Cerrar la conexión
            }
        }
        return $resultado;
    }

    public function getResults($consulta, $valores) {
        $resultado = "0";
        $statement = $this->conexion->prepare($consulta);
        if ($statement) {
            if (preg_match_all("/(:\w+)/", $consulta, $campo, PREG_PATTERN_ORDER)) {
                $campo = array_pop($campo);
                foreach ($campo as $parametro) {
                    $statement->bindValue($parametro, $valores[substr($parametro, 1)]);
                }
            }
            try {
                if (!$statement->execute()) {
                    throw new Exception('No puede ejecutar la consulta. ' . implode(', ', $statement->errorInfo()));
                }
                $resultado = $statement->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $evento) {
                throw new Exception('Error en la ejecución de la consulta. ' . $evento->getMessage());
            } finally {
                $statement->closeCursor();
                $this->conexion = null; // Cerrar la conexión
            }
        }
        return $resultado;
    }
}
?>
