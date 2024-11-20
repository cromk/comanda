<?php
session_start();// Inicia una nueva sesión o reanuda la sesión existente
require_once '../models/mtoEstados.php';// Incluye el archivo que contiene la definición de la clase 'Usuario'

header('Content-Type: application/json');// Establece el tipo de contenido de la respuesta como JSON

$response = array();// Inicializa un array para almacenar la respuesta
$method = $_SERVER['REQUEST_METHOD'];// Obtiene el método HTTP de la solicitud actual (GET, POST, PUT, DELETE)

try {
    $estadoModel = new Estado();

    switch ($method) {// Verifica el método HTTP de la solicitud y actúa en consecuencia
        case 'GET':
            if (isset($_GET['pedidos'])) $response = $estadoModel->getPedidos();
            else if(isset($_GET['id_pedido'])) $response = $estadoModel->getPedido($_GET['id_pedido']);
            break;

        case 'PUT':
            $data = json_decode(file_get_contents("php://input"), true);
            // Verifica que se hayan pasado los campos 'id' y 'nombre_categoria'
            if (!isset($data['id'])) {
                // Si no, lanza una excepción indicando datos incompletos
                throw new Exception('Datos incompletos');
            }
            // Llama al método update del modelo para actualizar la categoría especificada
            $estadoModel->update($data['id']);
            // Establece la respuesta indicando éxito
            $response = ['status' => 'success', 'message' => 'Categoria de menú actualizada con éxito'];
            break;
            break;

        default:
            throw new Exception('Método no permitido');
    }
} catch (Exception $e) {
    $response = ['status' => 'error', 'message' => $e->getMessage()];
}

echo json_encode($response);
?>