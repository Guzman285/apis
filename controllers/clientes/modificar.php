<?php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL);
require '../../models/clientes.php';


if (isset($_GET['idcliente'])) {
    $cliente_id = filter_var(base64_decode($_GET['idcliente']), FILTER_SANITIZE_NUMBER_INT);
    
    try {
        $modificar = new Cliente();
        $ClienteModificar = $modificar->buscarID($cliente_id);

        if ($ClienteModificar) {
            echo json_encode([
                'codigo' => 1,
                'datos' => $ClienteModificar
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
        if (empty($_POST['cliente_id']) || empty($_POST['cliente_nombre']) || empty($_POST['cliente_edad']) || 
            empty($_POST['cliente_dpi']) || empty($_POST['cliente_nit']) ||
            empty($_POST['cliente_telefono']) || empty($_POST['cliente_correo']) ||
            empty($_POST['cliente_sexo']) || empty($_POST['cliente_direccion'])) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => "Todos los campos son requeridos"
            ]);
            exit;
        }
        
        $datos = [
            'cliente_id' => filter_var($_POST['cliente_id'], FILTER_VALIDATE_INT),
            'cliente_nombre' => ucwords(strtolower(htmlspecialchars($_POST['cliente_nombre']))),
            'cliente_edad' => filter_var($_POST['cliente_edad'], FILTER_SANITIZE_NUMBER_INT),
            'cliente_dpi' => filter_var($_POST['cliente_dpi'], FILTER_SANITIZE_NUMBER_INT),
            'cliente_nit' => filter_var($_POST['cliente_nit'], FILTER_SANITIZE_NUMBER_INT),
            'cliente_telefono' => filter_var($_POST['cliente_telefono'], FILTER_SANITIZE_NUMBER_INT),
            'cliente_correo' => strtolower(htmlspecialchars($_POST['cliente_correo'])),
            'cliente_sexo' => ucwords(strtolower(htmlspecialchars($_POST['cliente_sexo']))),
            'cliente_direccion' => ucwords(strtolower(htmlspecialchars($_POST['cliente_direccion'])))
        ];
        
        $clienteNuevo = new Cliente($datos);
        $modificar = $clienteNuevo->modificar();
        
        if ($modificar) {
            echo json_encode([
                'codigo' => 1,
                'mensaje' => "CLIENTE MODIFICADO CORRECTAMENTE"
            ]);
        } else {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => "NO SE PUDO MODIFICAR EL CLIENTE"
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