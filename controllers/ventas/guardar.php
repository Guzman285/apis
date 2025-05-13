<?php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL);

require '../../models/ventas.php';
require '../../models/medicamentos.php';

try {
    if (empty($_POST['ventas_medicamento']) || empty($_POST['ventas_cantidad']) || empty($_POST['ventas_cliente'])) {
        echo json_encode([
            'codigo' => 0,
            'mensaje' => "Todos los campos son requeridos",
        ]);
        exit;
    }
    
    $datos = [
        'ventas_medicamento' => filter_var($_POST['ventas_medicamento'], FILTER_SANITIZE_NUMBER_INT),
        'ventas_cantidad' => filter_var($_POST['ventas_cantidad'], FILTER_SANITIZE_NUMBER_INT),
        'ventas_cliente' => filter_var($_POST['ventas_cliente'], FILTER_SANITIZE_NUMBER_INT)
    ];
    
    $conexion = Conexion::conectar();
    $sqlDisponibilidad = "SELECT medicamento_cantidad FROM medicamentos WHERE medicamento_id = :id AND medicamento_situacion = 1";
    $Disponibilidad = $conexion->prepare($sqlDisponibilidad);
    $Disponibilidad->bindParam(':id', $datos['ventas_medicamento'], PDO::PARAM_INT);
    $Disponibilidad->execute();
    
    if($Disponibilidad->rowCount() == 0) {
        echo json_encode([
            'codigo' => 0,
            'mensaje' => "El producto seleccionado no existe o está inactivo",
        ]);
        exit;
    }
    
    $disponible = $Disponibilidad->fetchColumn();
    $disponible = (int)$disponible;
    $cantidad = (int)$datos['ventas_cantidad'];

    if ($disponible >= $cantidad && $cantidad > 0) {
        $nueva_venta = new Venta($datos);
        $venta = $nueva_venta->guardar();
    
        $sqlUpdate = "UPDATE medicamentos SET medicamento_cantidad = medicamento_cantidad - :cantidad WHERE medicamento_id = :id";
        $Update = $conexion->prepare($sqlUpdate);
        $Update->bindParam(':id', $datos['ventas_medicamento'], PDO::PARAM_INT);
        $Update->bindParam(':cantidad', $datos['ventas_cantidad'], PDO::PARAM_INT);
        $Update->execute();
        
        echo json_encode([
            'codigo' => 1,
            'mensaje' => "VENTA INGRESADA SATISFACTORIAMENTE",
        ]);
    } else {
        echo json_encode([
            'codigo' => 0,
            'mensaje' => "NO HAY SUFICIENTE PRODUCTO. Disponible: " . $disponible . ", Solicitado: " . $cantidad,
        ]);
    }
} catch (Exception $e) {
    error_log("Error en guardar.php: " . $e->getMessage());
    echo json_encode([
        'codigo' => 0,
        'mensaje' => 'Ha ocurrido un error al procesar la solicitud. Intente más tarde.'
    ]);
}
exit;