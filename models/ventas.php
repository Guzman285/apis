<?php
include_once 'conexion.php';

class Venta extends conexion{
    public $ventas_id; 
    public $ventas_medicamento;
    public $ventas_cantidad;
    public $ventas_cliente;
    public $ventas_situacion; 

    public function __construct($args = []){
        $this->ventas_id = $args['ventas_id'] ?? null;
        $this->ventas_medicamento = $args['ventas_medicamento'] ?? '';
        $this->ventas_cantidad = $args['ventas_cantidad'] ?? '';
        $this->ventas_cliente = $args['ventas_cliente'] ?? '';
        $this->ventas_situacion = $args['ventas_situacion'] ?? 1; 
        
    }
    public function guardar(){
        $sql = "INSERT INTO ventas(ventas_medicamento, ventas_cantidad, ventas_cliente, ventas_situacion)
        VALUES (:medicamento, :cantidad, :cliente, :situacion)";

        $params =[
            ':medicamento' => $this->ventas_medicamento,
            ':cantidad' => $this->ventas_cantidad,
            ':cliente' => $this->ventas_cliente,
            ':situacion' => $this->ventas_situacion
        ];        
        return $this->ejecutar($sql, $params);
    }


    public function buscar(...$columnas)
{
    $sql = "SELECT *, ventas_medicamento, ventas_cantidad, cliente_nombre 
                FROM ventas  
                JOIN medicamentos ON ventas_medicamento = medicamento_id
                JOIN clientes ON ventas_cliente = cliente_id 
                WHERE medicamento_situacion = 1";
    $params = [];

    if (!empty($this->ventas_medicamento)) {
        $sql .= " AND ventas_medicamento = :medicamento";
        $params[':medicamento'] = $this->ventas_medicamento;
    }

    if (!empty($this->ventas_cantidad)) {
        $sql .= " AND ventas_cantidad = :cantidad";
        $params[':cantidad'] = $this->ventas_cantidad;
    }

    if (!empty($this->ventas_cliente)) {
        $sql .= " AND ventas_cliente = :cliente";
        $params[':cliente'] = $this->ventas_cliente;
    }

    return self::servir($sql, $params); 
}

public function buscarID($ID){
        
    $sql = "SELECT ventas.*, ventas_medicamento, ventas_cantidad, cliente_nombre 
            FROM ventas  
            JOIN medicamentos ON ventas.ventas_medicamento = medicamento_id
            JOIN clientes ON ventas.ventas_cliente = cliente_id  
            where ventas.ventas_situacion = 1 AND ventas.ventas_id = $ID ";

    $resultado =  array_shift(self::servir($sql));
    return $resultado;
}
}