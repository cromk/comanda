<?php
session_start(); // Inicia o reanuda la sesión existente
require_once '../models/FacturaModel.php'; // Incluye el modelo que maneja las facturas

header('Content-Type: application/json'); // Establece el tipo de contenido de la respuesta como JSON

$response = array(); // Inicializa un array para almacenar la respuesta
$method = $_SERVER['REQUEST_METHOD']; // Obtiene el método HTTP de la solicitud (GET, POST, PUT, DELETE)

try {
    $facturaModel = new Factura(); // Instancia del modelo Factura

    switch ($method) {
        case 'GET':
            // Si la solicitud es GET y se pasa un parámetro 'pedido', obtendrá los pedidos despachados o detalles del pedido
            if (isset($_GET['pedido'])) {
                if (isset($_GET['id'])) {
                    // Obtener los detalles de un pedido específico
                    $response = $facturaModel->obtenerDetallesPedido($_GET['id']);
                } else {
                    // Obtener todos los pedidos en estado 'Despachado'
                    $response = $facturaModel->obtenerPedidosDespachados();
                }
            }
            break;

        case 'POST':
            try {
                // Decodifica el cuerpo de la solicitud JSON en un array asociativo
                $data = json_decode(file_get_contents("php://input"), true);

                // Verifica que se haya pasado el campo necesario 'id_pedido' para generar la factura
                if (!isset($data['id_pedido'])) {
                    throw new Exception('Datos incompletos: falta id_pedido');
                }

                // Generar la factura con el id del pedido
                $rutaFactura = $facturaModel->generarFactura($data['id_pedido']);
                if (!$rutaFactura) {
                    throw new Exception('Error al generar la factura');
                }

                // Respuesta exitosa con la ruta del archivo PDF generado
                $response = ['status' => 'success', 'message' => 'Factura generada con éxito', 'ruta_factura' => $rutaFactura];
            } catch (Exception $e) {
                // Manejo de errores: envía la respuesta de error al navegador
                $response = [
                    'status' => 'error',
                    'message' => $e->getMessage()
                ];
                header('Content-Type: application/json', true, 400); // Establecer el código de estado a 400 (Bad Request)
            }
            break;

        
    }
} catch (Exception $e) {
    // Captura cualquier excepción y establece la respuesta indicando error
    $response = ['status' => 'error', 'message' => $e->getMessage()];
}

// Codifica el array de respuesta en formato JSON y lo imprime
echo json_encode($response);
?>
