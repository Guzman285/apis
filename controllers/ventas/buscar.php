<?php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL);

require '../../models/ventas.php';

try {
    $datos = [];
    
    if (!empty($_POST['ventas_medicamento'])) {
        $datos['ventas_medicamento'] = filter_var($_POST['ventas_medicamento'], FILTER_SANITIZE_NUMBER_INT);
    }
    
    if (!empty($_POST['ventas_cantidad'])) {
        $datos['ventas_cantidad'] = filter_var($_POST['ventas_cantidad'], FILTER_SANITIZE_NUMBER_INT);
    }
    
    if (!empty($_POST['ventas_cliente'])) {
        $datos['ventas_cliente'] = filter_var($_POST['ventas_cliente'], FILTER_SANITIZE_NUMBER_INT);
    }
    
    $Venta_Consulta = new Venta($datos);
    $ventas = $Venta_Consulta->buscar();
    
    if (!empty($ventas)) {
        echo json_encode([
            'codigo' => 1,
            'mensaje' => 'DATOS ENCONTRADOS',
            'datos' => $ventas
        ]);
    } else {
        echo json_encode([
            'codigo' => 0,
            'mensaje' => 'No se encontraron datos'
        ]);
    }
} catch (Exception $e) {
    error_log("Error en buscar.php: " . $e->getMessage());
    echo json_encode([
        'codigo' => 0,
        'mensaje' => 'Ha ocurrido un error al buscar los datos. Intente más tarde.'
    ]);
}
exit;