<?php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL);

require '../../models/medicamentos.php';

try {
    $datos = [];
    
    if (!empty($_POST['medicamento_nombre'])) {
        $datos['medicamento_nombre'] = htmlspecialchars($_POST['medicamento_nombre']);
    }
    
    if (!empty($_POST['medicamento_vencimiento'])) {
        $datos['medicamento_vencimiento'] = htmlspecialchars($_POST['medicamento_vencimiento']);
    }
    
    if (!empty($_POST['medicamento_descripcion'])) {
        $datos['medicamento_descripcion'] = htmlspecialchars($_POST['medicamento_descripcion']);
    }
    
    if (!empty($_POST['medicamento_presentacion'])) {
        $datos['medicamento_presentacion'] = htmlspecialchars($_POST['medicamento_presentacion']);
    }
    
    if (!empty($_POST['medicamento_casa_matriz'])) {
        $datos['medicamento_casa_matriz'] = htmlspecialchars($_POST['medicamento_casa_matriz']);
    }
    
    if (!empty($_POST['medicamento_cantidad'])) {
        $datos['medicamento_cantidad'] = filter_var($_POST['medicamento_cantidad'], FILTER_SANITIZE_NUMBER_INT);
    }
    
    if (!empty($_POST['medicamento_precio'])) {
        $datos['medicamento_precio'] = filter_var($_POST['medicamento_precio'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }
    
    $Medicamento_Consulta = new Medicamento($datos);
    
    if (empty($datos)) {
        $medicamentos = $Medicamento_Consulta->listarUtiles();
    } else {
        $medicamentos = $Medicamento_Consulta->buscar();
    }
    
    if (!empty($medicamentos)) {
        echo json_encode([
            'codigo' => 1,
            'mensaje' => 'Datos encontrados',
            'datos' => $medicamentos
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