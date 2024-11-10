<?php
session_start();// Inicia una nueva sesión o reanuda la sesión existente
require_once '../models/mtoPedido.php';// Incluye el archivo que contiene la definición de la clase 'Pedido'
require_once '../models/mtoMesa.php';// Incluye el archivo que contiene la definición de la clase 'Mesa'
require_once '../models/mtoProducto.php';// Incluye el archivo que contiene la definición de la clase 'Producto'

header('Content-Type: application/json');// Establece el tipo de contenido de la respuesta como JSON

$response = array();// Inicializa un array para almacenar la respuesta
$method = $_SERVER['REQUEST_METHOD'];// Obtiene el método HTTP de la solicitud actual (GET, POST, PUT, DELETE)

try {
    $pedidoModel = new Pedido();// Crea una instancia del modelo de 'Pedido'
    $mesaModel = new Mesa();// Crea una instancia del modelo de 'Mesa'
    $productoModel = new Producto();// Crea una instancia del modelo de 'Producto'

    switch ($method) {// Verifica el método HTTP de la solicitud y actúa en consecuencia
        case 'GET':
            if(isset($_GET['mesa'])){// Si la solicitud es GET y se pasa un parámetro 'mesa', obtendra las mesas disponibles del restaurante
                $response = $mesaModel->mesasDisponibles(); //Se asigna el condenido del metodo en la variable $response
            }
            else if (isset($_GET['productos'])) {// Si la solicitud es GET y se pasa un parámetro 'productos', obtendra todos los productos
                $response = $productoModel->obtenerProductos();
            }
            // Nueva funcionalidad: Obtener pedidos despachados o los detalles de un pedido específico
            else if (isset($_GET['pedido'])) {
                if (isset($_GET['id'])) {
                    // Si se pasa un ID, obtener detalles del pedido específico
                    $pedidoDetalles = $pedidoModel->detallef($_GET['id']);
                    if ($pedidoDetalles) {
                        $response = [
                            'status' => 'success',
                            'data' => [
                                'id' => $_GET['id'],
                                'lineas_pedido' => $pedidoDetalles
                            ]
                        ];
                    } else {
                        $response = [
                            'status' => 'error',
                            'message' => 'Detalles de pedido no encontrados.'
                        ];
                    }
                } else {
                    // Si no se pasa un ID, obtener todos los pedidos en estado 'Despachado'
                    $pedidosDespachados = $pedidoModel->obtenerPedidosDespachados();
                    $response = ['status' => 'success', 'data' => $pedidosDespachados];
                }
            }
            break;
        case 'POST':
            // Nueva funcionalidad para cambiar el estado del pedido a "Cancelado"
            $data = json_decode(file_get_contents("php://input"), true);
            if (isset($data['action']) && $data['action'] === 'cancel' && isset($data['id_pedido'])) {
                $pedidoId = $data['id_pedido'];
                $resultado = $pedidoModel->updateStatus($pedidoId, 'Cancelado');
                $response = $resultado ? ['status' => 'success'] : ['status' => 'error', 'message' => 'No se pudo cambiar el estado del pedido'];
                echo json_encode($response);
                exit; // Terminar aquí para evitar continuar con el resto del código en este caso
            } 
            try {
                // Decodifica el cuerpo de la solicitud JSON en un array asociativo
                $data = json_decode(file_get_contents("php://input"), true);
        
                // Verifica que se hayan pasado los campos necesarios
                if (!isset($data['estado']) || !isset($data['num_mesa']) || !isset($data['id_mesero']) || !isset($data['lineas_pedido'])) {
                    throw new Exception('Datos incompletos');
                }
        
                // Crear el pedido
                $id_pedido = $pedidoModel->createPedido($data['estado'], $data['num_mesa'], $data['id_mesero']);
                if (!$id_pedido) {
                    throw new Exception('Error al crear el pedido');
                }
        
                // Agregar líneas de pedido
                foreach ($data['lineas_pedido'] as $linea) {
                    $resultado = $pedidoModel->addDetallePedido($id_pedido, $linea['id_item'], $linea['cantidad'], $linea['precio_unitario']);
                    if (!$resultado) {
                        throw new Exception('Error al agregar la línea de pedido');
                    }
                }
        
                // Respuesta exitosa
                $response = ['status' => 'success', 'message' => 'Pedido creado con éxito', 'id_pedido' => $id_pedido];
            } catch (Exception $e) {
                // Manejo de errores: envía la respuesta de error al navegador
                $response = [
                    'status' => 'error',
                    'message' => $e->getMessage()
                ];
                header('Content-Type: application/json', true, 400); // Establecer el código de estado a 400 (Bad Request)
            }
            break;
        // case 'POST':
        //     // Respuesta fija para confirmar que la ruta funciona
        //     $response = ['message' => 'Hello, World!', 'status' => 'success',];
        //     break;
        case 'PUT':
            // Decodifica el cuerpo de la solicitud JSON en un array asociativo
            $data = json_decode(file_get_contents("php://input"), true);
            // Verifica que se haya pasado los campos
            if (!isset($data['num_mesa']) || !isset($data['estado'])) {
                // Si no, lanza una excepción indicando datos incompletos
                throw new Exception('Datos incompletos');
            }
            // Llama al método update del modelo para actualizar la mesa especificada con cantidad de personas y estado
            $mesaModel->update2($data['num_mesa'], $data['estado']);
            // Establece la respuesta indicando éxito
            $response = ['status' => 'success', 'message' => 'Mesa actualizada con éxito'];
            break;
        default: // Si se utiliza un método HTTP no soportado, lanza una excepción
            throw new Exception('Método no permitido');
    }
} catch (Exception $e) {// Captura cualquier excepción y establece la respuesta indicando error
    $response = ['status' => 'error', 'message' => $e->getMessage()];
}

echo json_encode($response);// Codifica el array de respuesta en formato JSON y lo imprime
?>