<?php
include_once 'conexion.php';

class Trabajador extends conexion
{
    public $trabajador_id;
    public $trabajador_nombre;
    public $trabajador_edad;
    public $trabajador_dpi;
    public $trabajador_puesto;
    public $trabajador_telefono;
    public $trabajador_correo;
    public $trabajador_sueldo;
    public $trabajador_sexo;
    public $trabajador_direccion;
    public $trabajador_situacion;


    public function __construct($args = [])
    {
        $this->trabajador_id = $args['trabajador_id'] ?? null;
        $this->trabajador_nombre = $args['trabajador_nombre'] ?? '';
        $this->trabajador_edad = $args['trabajador_edad'] ?? '';
        $this->trabajador_dpi = $args['trabajador_dpi'] ?? '';
        $this->trabajador_puesto = $args['trabajador_puesto'] ?? '';
        $this->trabajador_telefono = $args['trabajador_telefono'] ?? '';
        $this->trabajador_correo = $args['trabajador_correo'] ?? '';
        $this->trabajador_sueldo = $args['trabajador_sueldo'] ?? '';
        $this->trabajador_sexo = $args['trabajador_sexo'] ?? '';
        $this->trabajador_direccion = $args['trabajador_direccion'] ?? '';
        $this->trabajador_situacion = $args['trabajador_situacion'] ?? 1;
    }

    public function guardar()
    {

        $this->trabajador_nombre = ucwords(strtolower($this->trabajador_nombre));
        $this->trabajador_sexo = ucwords(strtolower($this->trabajador_sexo));
        $this->trabajador_direccion = ucwords(strtolower($this->trabajador_direccion));

        $sql = "INSERT INTO trabajadores(trabajador_nombre, trabajador_edad, trabajador_dpi, trabajador_puesto, trabajador_telefono, trabajador_correo, trabajador_sueldo, trabajador_sexo, trabajador_direccion, trabajador_situacion)
        VALUES (:nombre, :edad, :dpi, :puesto, :telefono, :correo, :sueldo, :sexo, :direccion, :situacion)";

        $params = [
            ':nombre' => $this->trabajador_nombre,
            ':edad' => $this->trabajador_edad,
            ':dpi' => $this->trabajador_dpi,
            ':puesto' => $this->trabajador_puesto,
            ':telefono' => $this->trabajador_telefono,
            ':correo' => $this->trabajador_correo,
            ':sueldo' => $this->trabajador_sueldo,
            ':sexo' => $this->trabajador_sexo,
            ':direccion' => $this->trabajador_direccion,
            ':situacion' => $this->trabajador_situacion
        ];
        return $this->ejecutar($sql, $params);
    }


    public function buscar(...$columnas)
    {
        $sql = "SELECT * 
                FROM trabajadores   
                WHERE trabajador_situacion = 1";

        $params = [];

        if (!empty($this->trabajador_nombre)) {
            $sql .= " AND trabajador_nombre LIKE :nombre";
            $params[':nombre'] = "%{$this->trabajador_nombre}%";
        }

        if (!empty($this->trabajador_edad)) {
            $sql .= " AND trabajador_edad = :edad";
            $params[':edad'] = $this->trabajador_edad;
        }

        if (!empty($this->trabajador_dpi)) {
            $sql .= " AND trabajador_dpi = :dpi";
            $params[':dpi'] = $this->trabajador_dpi;
        }

        if (!empty($this->trabajador_puesto)) {
            $sql .= " AND trabajador_puesto LIKE :puesto";
            $params[':puesto'] = "%{$this->trabajador_puesto}%";
        }

        if (!empty($this->trabajador_telefono)) {
            $sql .= " AND trabajador_telefono = :telefono";
            $params[':telefono'] = $this->trabajador_telefono;
        }

        if (!empty($this->trabajador_correo)) {
            $sql .= " AND trabajador_correo LIKE :correo";
            $params[':correo'] = "%{$this->trabajador_correo}%";
        }

        if (!empty($this->trabajador_sueldo)) {
            $sql .= " AND trabajador_sueldo = :sueldo";
            $params[':sueldo'] = $this->trabajador_sueldo;
        }

        if (!empty($this->trabajador_sexo)) {
            $sql .= " AND trabajador_sexo LIKE :sexo";
            $params[':sexo'] = "%{$this->trabajador_sexo}%";
        }

        if (!empty($this->trabajador_direccion)) {
            $sql .= " AND trabajador_direccion LIKE :direccion";
            $params[':direccion'] = "%{$this->trabajador_direccion}%";
        }

        return self::servir($sql, $params);
    }

    public function buscarID($ID)
    {

        $sql = "SELECT * 
            FROM trabajadores  
            where trabajador_situacion = 1 AND trabajador_id = $ID ";

        $resultado =  array_shift(self::servir($sql));
        return $resultado;
    }

    public function modificar()
    {
        $this->trabajador_nombre = ucwords(strtolower($this->trabajador_nombre));
        $this->trabajador_sexo = ucwords(strtolower($this->trabajador_sexo));
        $this->trabajador_direccion = ucwords(strtolower($this->trabajador_direccion));
        $sql = "UPDATE trabajadores 
            SET trabajador_nombre = :nombre,
                trabajador_edad = :edad,
                trabajador_dpi = :dpi,
                trabajador_puesto = :puesto,
                trabajador_telefono = :telefono, 
                trabajador_correo = :correo,
                trabajador_sueldo = :sueldo,
                trabajador_sexo = :sexo,
                trabajador_direccion = :direccion 
            WHERE trabajador_situacion = 1 AND trabajador_id = :id";

        $params = [
            ':nombre' => $this->trabajador_nombre,
            ':edad' => $this->trabajador_edad,
            ':dpi' => $this->trabajador_dpi,
            ':puesto' => $this->trabajador_puesto,
            ':telefono' => $this->trabajador_telefono,
            ':correo' => $this->trabajador_correo,
            ':sueldo' => $this->trabajador_sueldo,
            ':sexo' => $this->trabajador_sexo,
            ':direccion' => $this->trabajador_direccion,
            ':id'   => $this->trabajador_id
        ];

        return $this->ejecutar($sql, $params);
    }


    public function eliminar()
    {
        $sql = "UPDATE trabajadores SET trabajador_situacion = 0 WHERE trabajador_id = :id";

        $params = [
            ':id' => $this->trabajador_id
        ];

        return $this->ejecutar($sql, $params);
    }
}
