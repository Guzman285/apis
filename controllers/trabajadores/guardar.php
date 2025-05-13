<?php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL);

require '../../models/trabajadores.php';

try {
    if (empty($_POST['trabajador_nombre']) || empty($_POST['trabajador_edad']) || 
        empty($_POST['trabajador_dpi']) || empty($_POST['trabajador_puesto']) ||
        empty($_POST['trabajador_telefono']) || empty($_POST['trabajador_correo']) ||
        empty($_POST['trabajador_sueldo']) || empty($_POST['trabajador_sexo']) ||
        empty($_POST['trabajador_direccion'])) {
        echo json_encode([
            'codigo' => 0,
            'mensaje' => "Todos los campos son requeridos",
        ]);
        exit;
    }
    
    $datos = [
        'trabajador_nombre' => ucwords(strtolower(htmlspecialchars($_POST['trabajador_nombre']))),
        'trabajador_edad' => filter_var($_POST['trabajador_edad'], FILTER_SANITIZE_NUMBER_INT),
        'trabajador_dpi' => filter_var($_POST['trabajador_dpi'], FILTER_SANITIZE_NUMBER_INT),
        'trabajador_puesto' => ucwords(strtolower(htmlspecialchars($_POST['trabajador_puesto']))),
        'trabajador_telefono' => filter_var($_POST['trabajador_telefono'], FILTER_SANITIZE_NUMBER_INT),
        'trabajador_correo' => ucwords(strtolower(htmlspecialchars($_POST['trabajador_correo']))),
        'trabajador_sueldo' => filter_var($_POST['trabajador_sueldo'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
        'trabajador_sexo' => ucwords(strtolower(htmlspecialchars($_POST['trabajador_sexo']))),
        'trabajador_direccion' => ucwords(strtolower(htmlspecialchars($_POST['trabajador_direccion'])))
    ];
    
    $nuevo_trabajador = new Trabajador($datos);
    $resultado = $nuevo_trabajador->guardar();
    
    if ($resultado) {
        echo json_encode([
            'codigo' => 1,
            'mensaje' => "TRABAJADOR INGRESADO SATISFACTORIAMENTE",
        ]);
    } else {
        echo json_encode([
            'codigo' => 0,
            'mensaje' => "No se pudo guardar el trabajador",
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