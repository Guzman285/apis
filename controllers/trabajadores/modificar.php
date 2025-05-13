<?php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL);
require '../../models/trabajadores.php';

if (isset($_GET['idtrabajador'])) {
    $trabajador_id = filter_var(base64_decode($_GET['idtrabajador']), FILTER_SANITIZE_NUMBER_INT);
    
    try {
        $modificar = new Trabajador();
        $TrabajadorModificar = $modificar->buscarID($trabajador_id);

        if ($TrabajadorModificar) {
            echo json_encode([
                'codigo' => 1,
                'datos' => $TrabajadorModificar
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
        if (empty($_POST['trabajador_id']) || empty($_POST['trabajador_nombre']) || 
            empty($_POST['trabajador_edad']) || empty($_POST['trabajador_dpi']) || 
            empty($_POST['trabajador_puesto']) || empty($_POST['trabajador_telefono']) || 
            empty($_POST['trabajador_correo']) || empty($_POST['trabajador_sueldo']) || 
            empty($_POST['trabajador_sexo']) || empty($_POST['trabajador_direccion'])) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => "Todos los campos son requeridos"
            ]);
            exit;
        }
        
        $datos = [
            'trabajador_id' => filter_var($_POST['trabajador_id'], FILTER_VALIDATE_INT),
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
        
        $trabajadorNuevo = new Trabajador($datos);
        $modificar = $trabajadorNuevo->modificar();
        
        if ($modificar) {
            echo json_encode([
                'codigo' => 1,
                'mensaje' => "TRABAJADOR MODIFICADO CORRECTAMENTE"
            ]);
        } else {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => "NO SE PUDO MODIFICAR EL TRABAJADOR"
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