<?php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL);
require "../../models/casa_matriz.php";

if (isset($_GET['idcasa'])) {
    $casa_id = filter_var(base64_decode($_GET['idcasa']), FILTER_SANITIZE_NUMBER_INT);
    
    try {
        $datos = ['casa_id' => $casa_id];
        $Eliminar = new Casa($datos);
        $casaEliminar = $Eliminar->eliminar();

        if ($casaEliminar && $casaEliminar['resultado']) {
            echo json_encode([
                'codigo' => 1,
                'mensaje' => "CASA ELIMINADA EXITOSAMENTE"
            ]);
        } else {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => "ERROR AL ELIMINAR LA CASA"
            ]);
        }

    } catch (Exception $e) {
        error_log("Error en eliminar.php: " . $e->getMessage());
        echo json_encode([
            'codigo' => 0,
            'mensaje' => 'Ha ocurrido un error al eliminar la casa. Intente más tarde.'
        ]);
    }
} else {
    echo json_encode([
        'codigo' => 0,
        'mensaje' => "No se recibió un ID válido"
    ]);
}
exit;