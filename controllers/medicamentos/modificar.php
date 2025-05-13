<?php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL);
require '../../models/medicamentos.php';

if (isset($_GET['idmedicamento'])) {
    $medicamento_id = filter_var(base64_decode($_GET['idmedicamento']), FILTER_SANITIZE_NUMBER_INT);
    
    try {
        $modificar = new Medicamento();
        $MedicamentoModificar = $modificar->buscarID($medicamento_id);

        if ($MedicamentoModificar) {
            echo json_encode([
                'codigo' => 1,
                'datos' => $MedicamentoModificar
            ]);
        } else {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => "No se encontró registro"
            ]);
        }
    } catch (Exception $e) {
        error_log("Error en modificar.php (GET): " . $e->getMessage());
        echo json_encode([
            'codigo' => 0,
            'mensaje' => 'Error al buscar el registro'
        ]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (empty($_POST['medicamento_id']) || empty($_POST['medicamento_nombre']) || empty($_POST['medicamento_vencimiento']) || 
            empty($_POST['medicamento_descripcion']) || empty($_POST['medicamento_presentacion']) ||
            empty($_POST['medicamento_casa_matriz']) || empty($_POST['medicamento_cantidad']) ||
            empty($_POST['medicamento_precio'])) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => "Todos los campos son requeridos"
            ]);
            exit;
        }
        
        $datos = [
            'medicamento_id' => filter_var($_POST['medicamento_id'], FILTER_VALIDATE_INT),
            'medicamento_nombre' => ucwords(strtolower(htmlspecialchars($_POST['medicamento_nombre']))),
            'medicamento_vencimiento' => ucwords(strtolower(htmlspecialchars($_POST['medicamento_vencimiento']))),
            'medicamento_descripcion' => ucwords(strtolower(htmlspecialchars($_POST['medicamento_descripcion']))),
            'medicamento_presentacion' => ucwords(strtolower(htmlspecialchars($_POST['medicamento_presentacion']))),
            'medicamento_casa_matriz' => htmlspecialchars($_POST['medicamento_casa_matriz']),
            'medicamento_cantidad' => filter_var($_POST['medicamento_cantidad'], FILTER_SANITIZE_NUMBER_INT),
            'medicamento_precio' => filter_var($_POST['medicamento_precio'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION)
        ];
        
        $medicamentoNuevo = new Medicamento($datos);
        $modificar = $medicamentoNuevo->modificar();
        
        if ($modificar) {
            echo json_encode([
                'codigo' => 1,
                'mensaje' => "MEDICAMENTO MODIFICADO CORRECTAMENTE"
            ]);
        } else {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => "NO SE PUDO MODIFICAR EL MEDICAMENTO"
            ]);
        }
   
    } catch (Exception $e) {
        error_log("Error en modificar.php (POST): " . $e->getMessage());
        echo json_encode([
            'codigo' => 0,
            'mensaje' => 'Ha ocurrido un error al modificar los datos. Intente más tarde.'
        ]);
    }
    exit;
}

echo json_encode([
    'codigo' => 0,
    'mensaje' => "Solicitud no válida"
]);
exit;