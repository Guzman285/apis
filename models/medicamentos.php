<?php
include_once 'conexion.php';

class Medicamento extends conexion{
    public $medicamento_id; 
    public $medicamento_nombre; 
    public $medicamento_vencimiento;
    public $medicamento_descripcion;
    public $medicamento_presentacion;
    public $medicamento_casa_matriz; 
    public $medicamento_cantidad;
    public $medicamento_precio;
    public $medicamento_situacion; 


    public function __construct($args = []){
        $this->medicamento_id = $args['medicamento_id'] ?? null;
        $this->medicamento_nombre = $args['medicamento_nombre'] ?? '';
        $this->medicamento_vencimiento = $args['medicamento_vencimiento'] ?? '';
        $this->medicamento_descripcion = $args['medicamento_descripcion'] ?? '';
        $this->medicamento_presentacion = $args['medicamento_presentacion'] ?? '';
        $this->medicamento_casa_matriz = $args['medicamento_casa_matriz'] ?? '';
        $this->medicamento_cantidad = $args['medicamento_cantidad'] ?? '';
        $this->medicamento_precio = $args['medicamento_precio'] ?? '';
        $this->medicamento_situacion = $args['medicamento_situacion'] ?? 1; 
        
    }
    public function guardar(){
        $this->medicamento_nombre = ucwords(strtolower($this->medicamento_nombre));
        $this->medicamento_vencimiento = strtolower($this->medicamento_vencimiento);
        $this->medicamento_descripcion = ucwords(strtolower($this->medicamento_descripcion));
        $this->medicamento_presentacion = ucwords(strtolower($this->medicamento_presentacion));
        
        $sql = "INSERT INTO medicamentos(medicamento_nombre, medicamento_vencimiento, medicamento_descripcion, medicamento_presentacion, medicamento_casa_matriz, medicamento_cantidad, medicamento_precio, medicamento_situacion)
        VALUES (:nombre, :vencimiento, :descripcion, :presentacion, :casa_matriz, :cantidad, :precio, :situacion)";

        $params =[
            ':nombre' => $this->medicamento_nombre,
            ':vencimiento' => $this->medicamento_vencimiento,
            ':descripcion' => $this->medicamento_descripcion,
            ':presentacion' => $this->medicamento_presentacion,
            ':casa_matriz' => $this->medicamento_casa_matriz,
            ':cantidad' => $this->medicamento_cantidad,
            ':precio' => $this->medicamento_precio,
            ':situacion' => $this->medicamento_situacion
        ];
        
        return $this->ejecutar($sql, $params);
    }


    public function buscar(...$columnas)
{
    $sql = "SELECT *, casa_nombre 
                FROM medicamentos  
                JOIN casa_matriz ON medicamento_casa_matriz = casa_id 
                WHERE medicamento_situacion = 1";
    $params = [];

    if (!empty($this->medicamento_nombre)) {
        $sql .= " AND medicamento_nombre LIKE :nombre";
        $params[':nombre'] = "%{$this->medicamento_nombre}%";
    }

    if (!empty($this->medicamento_vencimiento)) {
        $sql .= " AND medicamento_vencimiento LIKE :vencimiento";
        $params[':vencimiento'] = "%{$this->medicamento_vencimiento}%";
    }

    if (!empty($this->medicamento_descripcion)) {
        $sql .= " AND medicamento_descripcion LIKE :descripcion";
        $params[':descripcion'] = "%{$this->medicamento_descripcion}%";
    }

    if (!empty($this->medicamento_presentacion)) {
        $sql .= " AND medicamento_presentacion LIKE :presentacion";
        $params[':presentacion'] = "%{$this->medicamento_presentacion}%";
    }

    if (!empty($this->medicamento_casa_matriz)) {
        $sql .= " AND medicamento_casa_matriz = :precio";
        $params[':precio'] = $this->medicamento_casa_matriz;
    }

    if (!empty($this->medicamento_cantidad)) {
        $sql .= " AND medicamento_cantidad = :descripcion";
        $params[':descripcion'] = $this->medicamento_cantidad;
    }

    if (!empty($this->medicamento_precio)) {
        $sql .= " AND medicamento_precio = :precio";
        $params[':precio'] = $this->medicamento_precio;
    }


    return self::servir($sql, $params); 
}

public function buscarID($ID){
        
    $sql = "SELECT medicamentos.*, casa_nombre 
            FROM medicamentos 
            JOIN casa_matriz ON medicamentos.medicamento_casa_matriz = casa_id 
            where medicamentos.medicamento_situacion = 1 AND medicamentos.medicamento_id = $ID ";

    $resultado =  array_shift(self::servir($sql));
    return $resultado;
}

public function modificar(){
    $this->medicamento_nombre = ucwords(strtolower($this->medicamento_nombre));
    $this->medicamento_vencimiento = strtolower($this->medicamento_vencimiento);
    $this->medicamento_descripcion = ucwords(strtolower($this->medicamento_descripcion));
    $this->medicamento_presentacion = ucwords(strtolower($this->medicamento_presentacion));
    
    $sql = "UPDATE medicamentos 
            SET medicamento_nombre = :nombre,
                medicamento_vencimiento = :vencimiento, 
                medicamento_descripcion = :descripcion,
                medicamento_presentacion = :presentacion,
                medicamento_casa_matriz = :casa_matriz, 
                medicamento_cantidad = :cantidad,
                medicamento_precio = :precio  
            WHERE medicamento_situacion = 1 AND medicamento_id = :id";

    $params = [
        ':nombre' => $this->medicamento_nombre,
        ':vencimiento' => $this->medicamento_vencimiento,
        ':descripcion' => $this->medicamento_descripcion,
        ':presentacion' => $this->medicamento_presentacion,
        ':casa_matriz' => $this->medicamento_casa_matriz,
        ':cantidad' => $this->medicamento_cantidad, 
        ':precio' => $this->medicamento_precio,
        ':id' => $this->medicamento_id
    ];

    return $this->ejecutar($sql,$params);
}

public function eliminar() {
    $sql = "UPDATE medicamentos SET medicamento_situacion = 0 WHERE medicamento_id = :id";

    $params = [
        ':id' => $this->medicamento_id
    ];

    return $this->ejecutar($sql,$params);
}

public function listarUtiles()
{
    $sql = "SELECT * FROM medicamentos WHERE medicamento_situacion = 1";
    return self::servir($sql);
}

}