<?php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL);

require '../../models/medicamentos.php';

try {

    if (empty($_POST['medicamento_nombre']) || empty($_POST['medicamento_vencimiento']) || 
        empty($_POST['medicamento_descripcion']) || empty($_POST['medicamento_presentacion']) ||
        empty($_POST['medicamento_casa_matriz']) || empty($_POST['medicamento_cantidad']) ||
        empty($_POST['medicamento_precio'])) {
        echo json_encode([
            'codigo' => 0,
            'mensaje' => "Todos los campos son requeridos",
        ]);
        exit;
    }
 
    $datos = [
        'medicamento_nombre' => ucwords(strtolower(htmlspecialchars($_POST['medicamento_nombre']))),
        'medicamento_vencimiento' => ucwords(strtolower(htmlspecialchars($_POST['medicamento_vencimiento']))),
        'medicamento_descripcion' => ucwords(strtolower(htmlspecialchars($_POST['medicamento_descripcion']))),
        'medicamento_presentacion' => ucwords(strtolower(htmlspecialchars($_POST['medicamento_presentacion']))),
        'medicamento_casa_matriz' => htmlspecialchars($_POST['medicamento_casa_matriz']),
        'medicamento_cantidad' => filter_var($_POST['medicamento_cantidad'], FILTER_SANITIZE_NUMBER_INT),
        'medicamento_precio' => filter_var($_POST['medicamento_precio'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION)
    ];
    
    $nuevo_medicamento = new Medicamento($datos);
    $resultado = $nuevo_medicamento->guardar();
    
    if ($resultado) {
        echo json_encode([
            'codigo' => 1,
            'mensaje' => "MEDICAMENTO INGRESADO SATISFACTORIAMENTE",
        ]);
    } else {
        echo json_encode([
            'codigo' => 0,
            'mensaje' => "No se pudo guardar el medicamento",
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