<?php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL);

require '../../models/clientes.php';

try {
    $datos = [];
    
    if (!empty($_POST['cliente_nombre'])) {
        $datos['cliente_nombre'] = htmlspecialchars($_POST['cliente_nombre']);
    }
    
    if (!empty($_POST['cliente_edad'])) {
        $datos['cliente_edad'] = filter_var($_POST['cliente_edad'], FILTER_SANITIZE_NUMBER_INT);
    }
    
    if (!empty($_POST['cliente_dpi'])) {
        $datos['cliente_dpi'] = filter_var($_POST['cliente_dpi'], FILTER_SANITIZE_NUMBER_INT);
    }
    
    if (!empty($_POST['cliente_nit'])) {
        $datos['cliente_nit'] = filter_var($_POST['cliente_nit'], FILTER_SANITIZE_NUMBER_INT);
    }
    
    if (!empty($_POST['cliente_telefono'])) {
        $datos['cliente_telefono'] = filter_var($_POST['cliente_telefono'], FILTER_SANITIZE_NUMBER_INT);
    }
    
    if (!empty($_POST['cliente_correo'])) {
        $datos['cliente_correo'] = htmlspecialchars($_POST['cliente_correo']);
    }
    
    if (!empty($_POST['cliente_sexo'])) {
        $datos['cliente_sexo'] = htmlspecialchars($_POST['cliente_sexo']);
    }
    
    if (!empty($_POST['cliente_direccion'])) {
        $datos['cliente_direccion'] = htmlspecialchars($_POST['cliente_direccion']);
    }
    
    $Cliente_Consulta = new Cliente($datos);
    
    if (empty($datos)) {
        $clientes = $Cliente_Consulta->listarClientes();
    } else {
        $clientes = $Cliente_Consulta->buscar();
    }
    
    if (!empty($clientes)) {
        echo json_encode([
            'codigo' => 1,
            'mensaje' => 'Datos encontrados',
            'datos' => $clientes
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