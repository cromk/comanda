<?php

// Inicia una nueva sesión o reanuda la sesión existente
session_start();


require_once '../models/mtoPedido.php';

// Establece el tipo de contenido de la respuesta como JSON
header('Content-Type: application/json');

// Inicializa un array para almacenar la respuesta
$response = array();
// Obtiene el método HTTP de la solicitud actual (GET, POST, PUT, DELETE)
$method = $_SERVER['REQUEST_METHOD'];

try {

    $pedidoModel = new Pedido();
    $data = null;

    switch ($method) {
        case 'GET':
            // Si la solicitud es GET y se pasa un parámetro 'id_pedido', obtiene un pedido específico
            if (isset($_GET['id_pedido'])) {
                $response = $pedidoModel->detalle($_GET['id_pedido']);
            }else if(isset($_GET['estado'])){
                $response = $pedidoModel->readStatus();
            }else {
                $response = $pedidoModel->readAll();
            }
            break;

        case 'PUT':
            $data = json_decode(file_get_contents("php://input"), true);
            if (!isset($data['id']) || !isset($data['estado'])) {
                throw new Exception('Datos incompletos');
            }
            $pedidoModel->updateStatus($data['id'], $data['estado']);
            $response = ['status' => 'success', 'message' => 'Estado actualizado'];
            break;

        default:
            // Si se utiliza un método HTTP no soportado, lanza una excepción
            throw new Exception('Método no permitido');
    }
    
} catch (Exception $e) {
    // Captura cualquier excepción y establece la respuesta indicando error
    $response = ['status' => 'error', 'message' => $e->getMessage()];
}

// Codifica el array de respuesta en formato JSON y lo imprime
echo json_encode($response);
?>
