<?php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL);
require "../../models/clientes.php";

if (isset($_GET['idcliente'])) {
    $cliente_id = filter_var(base64_decode($_GET['idcliente']), FILTER_SANITIZE_NUMBER_INT);
    
    try {
        $datos = ['cliente_id' => $cliente_id];
        $Eliminar = new Cliente($datos);
        $clienteEliminar = $Eliminar->eliminar();

        if ($clienteEliminar) {
            echo json_encode([
                'codigo' => 1,
                'mensaje' => "CLIENTE ELIMINADO EXITOSAMENTE"
            ]);
        } else {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => "ERROR AL ELIMINAR EL CLIENTE"
            ]);
        }

    } catch (Exception $e) {
        error_log("Error en eliminar.php: " . $e->getMessage());
        echo json_encode([
            'codigo' => 0,
            'mensaje' => 'Ha ocurrido un error al eliminar el cliente. Intente más tarde.'
        ]);
    }
} else {
    echo json_encode([
        'codigo' => 0,
        'mensaje' => "No se recibió un ID válido"
    ]);
}
exit;