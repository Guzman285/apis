<?php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL);
require "../../models/trabajadores.php";

if (isset($_GET['idtrabajador'])) {
    $trabajador_id = filter_var(base64_decode($_GET['idtrabajador']), FILTER_SANITIZE_NUMBER_INT);
    
    try {
        $datos = ['trabajador_id' => $trabajador_id];
        $Eliminar = new Trabajador($datos);
        $trabajadorEliminar = $Eliminar->eliminar();

        if ($trabajadorEliminar) {
            echo json_encode([
                'codigo' => 1,
                'mensaje' => "TRABAJADOR ELIMINADO EXITOSAMENTE"
            ]);
        } else {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => "ERROR AL ELIMINAR EL TRABAJADOR"
            ]);
        }

    } catch (Exception $e) {
        error_log("Error en eliminar.php: " . $e->getMessage());
        echo json_encode([
            'codigo' => 0,
            'mensaje' => 'Ha ocurrido un error al eliminar el trabajador. Intente más tarde.'
        ]);
    }
} else {
    echo json_encode([
        'codigo' => 0,
        'mensaje' => "No se recibió un ID válido"
    ]);
}
exit;