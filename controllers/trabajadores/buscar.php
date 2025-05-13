<?php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL);

require '../../models/trabajadores.php';

try {
    $datos = [];
    
    if (!empty($_POST['trabajador_nombre'])) {
        $datos['trabajador_nombre'] = htmlspecialchars($_POST['trabajador_nombre']);
    }
    
    if (!empty($_POST['trabajador_edad'])) {
        $datos['trabajador_edad'] = filter_var($_POST['trabajador_edad'], FILTER_SANITIZE_NUMBER_INT);
    }
    
    if (!empty($_POST['trabajador_dpi'])) {
        $datos['trabajador_dpi'] = filter_var($_POST['trabajador_dpi'], FILTER_SANITIZE_NUMBER_INT);
    }
    
    if (!empty($_POST['trabajador_puesto'])) {
        $datos['trabajador_puesto'] = htmlspecialchars($_POST['trabajador_puesto']);
    }
    
    if (!empty($_POST['trabajador_telefono'])) {
        $datos['trabajador_telefono'] = filter_var($_POST['trabajador_telefono'], FILTER_SANITIZE_NUMBER_INT);
    }
    
    if (!empty($_POST['trabajador_correo'])) {
        $datos['trabajador_correo'] = htmlspecialchars($_POST['trabajador_correo']);
    }
    
    if (!empty($_POST['trabajador_sueldo'])) {
        $datos['trabajador_sueldo'] = filter_var($_POST['trabajador_sueldo'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }
    
    if (!empty($_POST['trabajador_sexo'])) {
        $datos['trabajador_sexo'] = htmlspecialchars($_POST['trabajador_sexo']);
    }
    
    if (!empty($_POST['trabajador_direccion'])) {
        $datos['trabajador_direccion'] = htmlspecialchars($_POST['trabajador_direccion']);
    }
    
    $Trabajador_Consulta = new Trabajador($datos);
    $trabajadores = $Trabajador_Consulta->buscar();
    
    if (!empty($trabajadores)) {
        echo json_encode([
            'codigo' => 1,
            'mensaje' => 'DATOS ENCONTRADOS',
            'datos' => $trabajadores
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