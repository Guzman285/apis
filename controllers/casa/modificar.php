<?php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL);
require '../../models/casa_matriz.php';

if (isset($_GET['idcasa'])) {
    $casa_id = filter_var(base64_decode($_GET['idcasa']), FILTER_SANITIZE_NUMBER_INT);
    
    try {
        $modificar = new Casa();
        $CasaModificar = $modificar->buscarID($casa_id);

        if ($CasaModificar) {
            echo json_encode([
                'codigo' => 1,
                'datos' => $CasaModificar
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
        if (empty($_POST['casa_id']) || empty($_POST['casa_nombre']) || empty($_POST['casa_direccion']) || 
            empty($_POST['casa_telefono']) || empty($_POST['casa_jefe'])) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => "Todos los campos son requeridos"
            ]);
            exit;
        }
        
        $datos = [
            'casa_id' => $_POST['casa_id'],
            'casa_nombre' => $_POST['casa_nombre'],
            'casa_direccion' => $_POST['casa_direccion'],
            'casa_telefono' => $_POST['casa_telefono'],
            'casa_jefe' => $_POST['casa_jefe']
        ];
        
        $casaNueva = new Casa($datos);
        $modificar = $casaNueva->modificar();
        
        if ($modificar && $modificar['resultado']) {
            echo json_encode([
                'codigo' => 1,
                'mensaje' => "CASA MODIFICADA CORRECTAMENTE"
            ]);
        } else {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => "NO SE PUDO MODIFICAR LA CASA"
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