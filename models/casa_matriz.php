<?php
include_once 'conexion.php';

class Casa extends conexion
{
    public $casa_id;
    public $casa_nombre;
    public $casa_direccion;
    public $casa_telefono;
    public $casa_jefe;
    public $casa_situacion;

    public function __construct($args = [])
    {
        $this->casa_id = $args['casa_id'] ?? null;
        $this->casa_nombre = $args['casa_nombre'] ?? '';
        $this->casa_direccion = $args['casa_direccion'] ?? '';
        $this->casa_telefono = $args['casa_telefono'] ?? '';
        $this->casa_jefe = $args['casa_jefe'] ?? '';
        $this->casa_situacion = $args['casa_situacion'] ?? 1;
    }
    
    public function guardar()
    {
        try {
            $this->casa_nombre = ucwords(strtolower($this->casa_nombre));
            $this->casa_direccion = ucwords(strtolower($this->casa_direccion));
            $this->casa_jefe = ucwords(strtolower($this->casa_jefe));

            $sql = "INSERT INTO casa_matriz(casa_nombre, casa_direccion, casa_telefono, casa_jefe, casa_situacion)
            VALUES (:nombre, :direccion, :telefono, :jefe, :situacion)";

            $params = [
                ':nombre' => $this->casa_nombre,
                ':direccion' => $this->casa_direccion,
                ':telefono' => $this->casa_telefono,
                ':jefe' => $this->casa_jefe,
                ':situacion' => $this->casa_situacion
            ];

            return $this->ejecutar($sql, $params);
        } catch (Exception $e) {
            error_log("Error en guardar (modelo): " . $e->getMessage());
            throw new Exception("Error al guardar: " . $e->getMessage());
        }
    }

    public function buscar(...$columnas)
    {
        try {
            $cols = count($columnas) > 0 ? implode(',', $columnas) : '*';
            $sql = "SELECT $cols FROM casa_matriz WHERE casa_situacion = 1";
            $params = [];

            if (!empty($this->casa_nombre)) {
                $sql .= " AND casa_nombre LIKE :nombre";
                $params[':nombre'] = "%{$this->casa_nombre}%";
            }

            if (!empty($this->casa_direccion)) {
                $sql .= " AND casa_direccion LIKE :direccion";
                $params[':direccion'] = "%{$this->casa_direccion}%";
            }

            if (!empty($this->casa_telefono)) {
                $sql .= " AND casa_telefono = :telefono";
                $params[':telefono'] = $this->casa_telefono;
            }

            if (!empty($this->casa_jefe)) {
                $sql .= " AND casa_jefe LIKE :jefe";
                $params[':jefe'] = "%{$this->casa_jefe}%";
            }

            return self::servir($sql, $params);
        } catch (Exception $e) {
            error_log("Error en buscar (modelo): " . $e->getMessage());
            throw new Exception("Error al buscar: " . $e->getMessage());
        }
    }

    public function buscarID($ID)
    {
        try {
            if (empty($ID)) {
                throw new Exception("El ID no puede estar vacío");
            }
            
            $sql = "SELECT * FROM casa_matriz WHERE casa_situacion = 1 AND casa_id = :id";
            $params = [':id' => $ID];
            
            $resultado = self::servir($sql, $params);
            
            if (empty($resultado)) {
                return null;
            }
            
            return array_shift($resultado);
        } catch (Exception $e) {
            error_log("Error en buscarID (modelo): " . $e->getMessage());
            throw new Exception("Error al buscar por ID: " . $e->getMessage());
        }
    }

    public function modificar()
    {
        try {
            if (empty($this->casa_id)) {
                throw new Exception("El ID no puede estar vacío");
            }
            
            $this->casa_nombre = ucwords(strtolower($this->casa_nombre));
            $this->casa_direccion = ucwords(strtolower($this->casa_direccion));
            $this->casa_jefe = ucwords(strtolower($this->casa_jefe));
            
            $sql = "UPDATE casa_matriz
                SET casa_nombre = :nombre,
                    casa_direccion = :direccion,
                    casa_telefono = :telefono,
                    casa_jefe = :jefe   
                WHERE casa_situacion = 1 AND casa_id = :id";

            $params = [
                ':nombre'   => $this->casa_nombre,
                ':direccion'   => $this->casa_direccion,
                ':telefono'   => $this->casa_telefono,
                ':jefe'   => $this->casa_jefe,
                ':id'   => $this->casa_id
            ];

            return $this->ejecutar($sql, $params);
        } catch (Exception $e) {
            error_log("Error en modificar (modelo): " . $e->getMessage());
            throw new Exception("Error al modificar: " . $e->getMessage());
        }
    }

    public function eliminar()
    {
        try {
            if (empty($this->casa_id)) {
                throw new Exception("El ID no puede estar vacío");
            }
            
            $sql = "UPDATE casa_matriz SET casa_situacion = 0 WHERE casa_id = :id";

            $params = [
                ':id' => $this->casa_id
            ];

            return $this->ejecutar($sql, $params);
        } catch (Exception $e) {
            error_log("Error en eliminar (modelo): " . $e->getMessage());
            throw new Exception("Error al eliminar: " . $e->getMessage());
        }
    }

    public function listarMarcas()
    {
        try {
            $sql = "SELECT * FROM casa_matriz WHERE casa_situacion = 1";
            return self::servir($sql);
        } catch (Exception $e) {
            error_log("Error en listarMarcas (modelo): " . $e->getMessage());
            throw new Exception("Error al listar marcas: " . $e->getMessage());
        }
    }
}