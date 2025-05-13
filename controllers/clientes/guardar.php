<?php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL);

require '../../models/clientes.php';

try {
    if (empty($_POST['cliente_nombre']) || empty($_POST['cliente_edad']) || 
        empty($_POST['cliente_dpi']) || empty($_POST['cliente_nit']) ||
        empty($_POST['cliente_telefono']) || empty($_POST['cliente_correo']) ||
        empty($_POST['cliente_sexo']) || empty($_POST['cliente_direccion'])) {
        echo json_encode([
            'codigo' => 0,
            'mensaje' => "Todos los campos son requeridos",
        ]);
        exit;
    }
    
    $datos = [
        'cliente_nombre' => ucwords(strtolower(htmlspecialchars($_POST['cliente_nombre']))),
        'cliente_edad' => filter_var($_POST['cliente_edad'], FILTER_SANITIZE_NUMBER_INT),
        'cliente_dpi' => filter_var($_POST['cliente_dpi'], FILTER_SANITIZE_NUMBER_INT),
        'cliente_nit' => filter_var($_POST['cliente_nit'], FILTER_SANITIZE_NUMBER_INT),
        'cliente_telefono' => filter_var($_POST['cliente_telefono'], FILTER_SANITIZE_NUMBER_INT),
        'cliente_correo' => strtolower(htmlspecialchars($_POST['cliente_correo'])),
        'cliente_sexo' => ucwords(strtolower(htmlspecialchars($_POST['cliente_sexo']))),
        'cliente_direccion' => ucwords(strtolower(htmlspecialchars($_POST['cliente_direccion'])))
    ];
    
    $nuevo_cliente = new Cliente($datos);
    $resultado = $nuevo_cliente->guardar();
    
    if ($resultado) {
        echo json_encode([
            'codigo' => 1,
            'mensaje' => "CLIENTE INGRESADO SATISFACTORIAMENTE",
        ]);
    } else {
        echo json_encode([
            'codigo' => 0,
            'mensaje' => "No se pudo guardar el cliente",
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