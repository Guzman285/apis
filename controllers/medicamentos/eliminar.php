<?php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL);
require "../../models/medicamentos.php";

if (isset($_GET['idmedicamento'])) {
    $medicamento_id = filter_var(base64_decode($_GET['idmedicamento']), FILTER_SANITIZE_NUMBER_INT);
    
    try {
        $datos = ['medicamento_id' => $medicamento_id];
        $Eliminar = new Medicamento($datos);
        $medicamentoEliminar = $Eliminar->eliminar();

        if ($medicamentoEliminar) {
            echo json_encode([
                'codigo' => 1,
                'mensaje' => "MEDICAMENTO ELIMINADO EXITOSAMENTE"
            ]);
        } else {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => "ERROR AL ELIMINAR EL MEDICAMENTO"
            ]);
        }

    } catch (Exception $e) {
        error_log("Error en eliminar.php: " . $e->getMessage());
        echo json_encode([
            'codigo' => 0,
            'mensaje' => 'Ha ocurrido un error al eliminar el medicamento. Intente más tarde.'
        ]);
    }
} else {
    echo json_encode([
        'codigo' => 0,
        'mensaje' => "No se recibió un ID válido"
    ]);
}
exit;