<?php

// Inicia una nueva sesión o reanuda la sesión existente
session_start();
// Incluye el archivo que contiene la definición de la clase 'Mesa'
require_once '../models/mtoMesa.php';
// Establece el tipo de contenido de la respuesta como JSON
header('Content-Type: application/json');
// Inicializa un array para almacenar la respuesta
$response = array();
// Obtiene el método HTTP de la solicitud actual (GET, POST, PUT, DELETE)
$method = $_SERVER['REQUEST_METHOD'];

try {
    // Crea una instancia del modelo de 'Mesa'
    $mesaModel = new Mesa();
    // Verifica el método HTTP de la solicitud y actúa en consecuencia
    switch ($method) {
        case 'GET':
            if (isset($_GET['mesa'])) { // Si la solicitud es GET y se pasa un parámetro 'mesa', lobtendra el ultimo id +1 de los ingresados
                $response = $mesaModel->getMesa();
            } else if (isset($_GET['id'])) { // Si la solicitud es GET y se pasa un parámetro 'id', lee una mesa específica
                $response = $mesaModel->read($_GET['id']);
            } else if (isset($_GET['cant'])) {
                header('Content-Type: text/plain'); // Asegurarse de que el contenido sea texto plano
                $response = $mesaModel->cantMesas();
            } else {
                $response = $mesaModel->readAll(); // Si no se pasa 'id', lee todas las categorías
            }
            break;

        case 'POST':
            // Decodifica el cuerpo de la solicitud JSON en un array asociativo
            $data = json_decode(file_get_contents("php://input"), true);
            // Verifica que se haya pasado los campos
            if (!isset($data['num_mesa']) || !isset($data['cant_personas']) || !isset($data['estado'])) {
                throw new Exception('Datos incompletos'); // Si no, lanza una excepción indicando datos incompletos
            }
            // Llama al método create del modelo para crear una nueva categoría
            $mesaModel->create($data['num_mesa'], $data['cant_personas'], $data['estado']);
            // Establece la respuesta indicando éxito
            $response = ['status' => 'success', 'message' => 'Mesa creada con éxito'];
            break;

        case 'PUT':
            // Decodifica el cuerpo de la solicitud JSON en un array asociativo
            $data = json_decode(file_get_contents("php://input"), true);
            // Verifica que se haya pasado el campo 'id'
            if (!isset($data['id'])) {
                throw new Exception('Datos incompletos');
            }

            // Si se recibe el diferenciador 'updateEstado'
            if (isset($data['updateEstado']) && $data['updateEstado'] === true) {
                // Verifica que se haya pasado el campo 'estado'
                if (!isset($data['estado'])) {
                    throw new Exception('Datos incompletos');
                }
                // Llama al método update2 del modelo para actualizar solo el estado
                $mesaModel->update2($data['id'], $data['estado']);
                // Establece la respuesta indicando éxito
                $response = ['status' => 'success', 'message' => 'Estado de la mesa actualizado con éxito'];
            } else {
                // Verifica que se hayan pasado los campos necesarios para la actualización completa
                if (!isset($data['cant_personas']) || !isset($data['estado'])) {
                    throw new Exception('Datos incompletos');
                }
                // Llama al método update del modelo para actualizar la mesa especificada con cantidad de personas y estado
                $mesaModel->update($data['id'], $data['cant_personas'], $data['estado']);
                // Establece la respuesta indicando éxito
                $response = ['status' => 'success', 'message' => 'Mesa actualizada con éxito'];
            }
            break;

        case 'DELETE':
            // Decodifica el cuerpo de la solicitud JSON en un array asociativo
            $data = json_decode(file_get_contents("php://input"), true);
            if (!isset($data['id'])) { // Verifica que se haya pasado el campo 'id'
                throw new Exception('Datos incompletos'); // Si no, lanza una excepción indicando datos incompletos
            }
            // Llama al método delete del modelo para eliminar la mesa especificada
            $mesaModel->delete($data['id']);
            // Establece la respuesta indicando éxito
            $response = ['status' => 'success', 'message' => 'Mesa eliminada con éxito'];
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
