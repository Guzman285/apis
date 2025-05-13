<?php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL);

require '../../models/casa_matriz.php';

try {
    if (empty($_POST['casa_nombre']) || empty($_POST['casa_direccion']) || 
        empty($_POST['casa_telefono']) || empty($_POST['casa_jefe'])) {
        echo json_encode([
            'codigo' => 0,
            'mensaje' => "Todos los campos son requeridos",
        ]);
        exit;
    }

    $datos = [
        'casa_nombre' => $_POST['casa_nombre'],
        'casa_direccion' => $_POST['casa_direccion'],
        'casa_telefono' => $_POST['casa_telefono'],
        'casa_jefe' => $_POST['casa_jefe']
    ];

    $nueva_casa = new Casa($datos);
    $resultado = $nueva_casa->guardar();
    
    if ($resultado && $resultado['resultado']) {
        echo json_encode([
            'codigo' => 1,
            'mensaje' => "CASA INGRESADA SATISFACTORIAMENTE",
        ]);
    } else {
        echo json_encode([
            'codigo' => 0,
            'mensaje' => "No se pudo guardar la casa",
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