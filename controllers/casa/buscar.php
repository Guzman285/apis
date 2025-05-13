<?php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL);

require '../../models/casa_matriz.php';

try {
    $datos = [];
    
    if (!empty($_POST['casa_nombre'])) {
        $datos['casa_nombre'] = $_POST['casa_nombre'];
    }
    
    if (!empty($_POST['casa_direccion'])) {
        $datos['casa_direccion'] = $_POST['casa_direccion'];
    }
    
    if (!empty($_POST['casa_telefono'])) {
        $datos['casa_telefono'] = $_POST['casa_telefono'];
    }
    
    if (!empty($_POST['casa_jefe'])) {
        $datos['casa_jefe'] = $_POST['casa_jefe'];
    }

    $Casa_Consulta = new Casa($datos);
    if (empty($datos)) {
        $casas = $Casa_Consulta->listarMarcas();
    } else {
        $casas = $Casa_Consulta->buscar();
    }
    
    if (!empty($casas)) {
        echo json_encode([
            'codigo' => 1,
            'mensaje' => 'Datos encontrados',
            'datos' => $casas
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