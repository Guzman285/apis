<?php
include_once 'conexion.php';

class Cliente extends conexion
{
    public $cliente_id;
    public $cliente_nombre;
    public $cliente_edad;
    public $cliente_dpi;
    public $cliente_nit;
    public $cliente_telefono;
    public $cliente_correo;
    public $cliente_sexo;
    public $cliente_direccion;
    public $cliente_situacion;


    public function __construct($args = [])
    {
        $this->cliente_id = $args['cliente_id'] ?? null;
        $this->cliente_nombre = $args['cliente_nombre'] ?? '';
        $this->cliente_edad = $args['cliente_edad'] ?? '';
        $this->cliente_dpi = $args['cliente_dpi'] ?? '';
        $this->cliente_nit = $args['cliente_nit'] ?? '';
        $this->cliente_telefono = $args['cliente_telefono'] ?? '';
        $this->cliente_correo = $args['cliente_correo'] ?? '';
        $this->cliente_sexo = $args['cliente_sexo'] ?? '';
        $this->cliente_direccion = $args['cliente_direccion'] ?? '';
        $this->cliente_situacion = $args['cliente_situacion'] ?? 1;
    }

    public function guardar(){
        $this->cliente_nombre = ucwords(strtolower($this->cliente_nombre));
        $this->cliente_sexo = ucwords(strtolower($this->cliente_sexo));
        $this->cliente_direccion = ucwords(strtolower($this->cliente_direccion));

        $sql = "INSERT INTO clientes(cliente_nombre, cliente_edad, cliente_dpi, cliente_nit, cliente_telefono, cliente_correo, cliente_sexo, cliente_direccion, cliente_situacion)
        VALUES (:nombre, :edad, :dpi, :nit, :telefono, :correo, :sexo, :direccion, :situacion)";

        $params = [
            ':nombre' => $this->cliente_nombre,
            ':edad' => $this->cliente_edad,
            ':dpi' => $this->cliente_dpi,
            ':nit' => $this->cliente_nit,
            ':telefono' => $this->cliente_telefono,
            ':correo' => $this->cliente_correo,
            ':sexo' => $this->cliente_sexo,
            ':direccion' => $this->cliente_direccion,
            ':situacion' => $this->cliente_situacion
        ];
        return $this->ejecutar($sql, $params);
    }


    public function buscar(...$columnas)
    {
        $cols = count($columnas) > 0 ? implode(',', $columnas) : '*';
        $sql = "SELECT $cols FROM clientes WHERE cliente_situacion = 1";
        $params = [];

        if (!empty($this->cliente_nombre)) {
            $sql .= " AND cliente_nombre LIKE :nombre";
            $params[':nombre'] = "%{$this->cliente_nombre}%";
        }

        if (!empty($this->cliente_edad)) {
            $sql .= " AND cliente_edad = :edad";
            $params[':edad'] = "%{$this->cliente_edad}%";
        }

        if (!empty($this->cliente_dpi)) {
            $sql .= " AND cliente_dpi = :dpi";
            $params[':dpi'] = "%{$this->cliente_dpi}%";
        }

        if (!empty($this->cliente_nit)) {
            $sql .= " AND cliente_nit = :nit";
            $params[':nit'] = $this->cliente_nit;
        }

        if (!empty($this->cliente_telefono)) {
            $sql .= " AND cliente_telefono = :telefono";
            $params[':telefono'] = $this->cliente_telefono;
        }

        if (!empty($this->cliente_correo)) {
            $sql .= " AND cliente_correo LIKE :correo";
            $params[':correo'] = "%{$this->cliente_correo}%";
        }

        if (!empty($this->cliente_sexo)) {
            $sql .= " AND cliente_sexo LIKE :sexo";
            $params[':sexo'] = "%{$this->cliente_sexo}%";
        }

        if (!empty($this->cliente_direccion)) {
            $sql .= " AND cliente_direccion LIKE :direccion";
            $params[':direccion'] = "%{$this->cliente_direccion}%";
        }

        return self::servir($sql, $params);
    }

    public function buscarID($ID)
    {

        $sql = "SELECT * FROM clientes where cliente_situacion = 1 AND cliente_id = $ID ";

        $resultado =  array_shift(self::servir($sql));
        return $resultado;
    }

    public function modificar(){
        $this->cliente_nombre = ucwords(strtolower($this->cliente_nombre));
        $this->cliente_sexo = ucwords(strtolower($this->cliente_sexo));
        $this->cliente_direccion = ucwords(strtolower($this->cliente_direccion));
        
        $sql = "UPDATE clientes 
            SET cliente_nombre = :nombre, 
                cliente_edad = :edad,
                cliente_dpi = :dpi,
                cliente_nit = :nit, 
                cliente_telefono = :telefono,
                cliente_correo = :correo,
                cliente_sexo = :sexo,
                cliente_direccion = :direccion  
            WHERE cliente_situacion = 1 AND cliente_id = :id";

        $params = [
            ':nombre'   => $this->cliente_nombre,
            ':edad' => $this->cliente_edad,
            ':dpi' => $this->cliente_dpi,
            ':nit'      => $this->cliente_nit,
            ':telefono' => $this->cliente_telefono,
            ':correo' => $this->cliente_correo,
            ':sexo' => $this->cliente_sexo,
            ':direccion' => $this->cliente_direccion,
            ':id'   => $this->cliente_id
        ];

        return $this->ejecutar($sql, $params);
    }


    public function eliminar()
    {
        $sql = "UPDATE clientes SET cliente_situacion = 0 WHERE cliente_id = :id";

        $params = [
            ':id' => $this->cliente_id
        ];

        return $this->ejecutar($sql, $params);
    }

    public function listarClientes()
    {
        $sql = "SELECT * FROM clientes WHERE cliente_situacion = 1";
        return self::servir($sql);
    }
}
