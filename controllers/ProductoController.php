<?php
session_start(); // Inicia una nueva sesión o reanuda la sesión existente

// Incluye el archivo que contiene la definición de la clase 'Producto'
require_once '../models/mtoProducto.php';

// Establece el tipo de contenido de la respuesta como JSON
header('Content-Type: application/json');

// Inicializa un array para almacenar la respuesta
$response = array();

// Obtiene el método HTTP de la solicitud actual (GET, POST, PUT, DELETE)
$method = $_SERVER['REQUEST_METHOD'];

// Detectamos si se está simulando un método PUT
if ($method == 'POST' && isset($_POST['_method']) && $_POST['_method'] == 'PUT') {
    $method = 'PUT';
}

try {
    // Crea una instancia del modelo de 'Producto'
    $productoModel = new Producto();
    $fotoPath = null;

    // Verifica el método HTTP de la solicitud y actúa en consecuencia
    switch ($method) {
        case 'GET':
            // Si la solicitud es GET y se pasa un parámetro 'categoria', obtiene todas las categorías de los productos
            if (isset($_GET['categoria'])) {
                $response = $productoModel->getCategoria();
            } else if (isset($_GET['id'])) {
                // Si la solicitud es GET y se pasa un parámetro 'id', lee un producto en específico
                $response = $productoModel->read($_GET['id']);
            } else {
                // Si no se pasa ningún parámetro, lee todos los productos
                $response = $productoModel->readAll();
            }
            break;

        case 'POST':
            try {
                // Obtiene los datos del producto desde la solicitud POST
                $nombre = $_POST['nombre'];
                $descripcion = $_POST['descripcion'];
                $precio = $_POST['precio'];
                $id_categoria = $_POST['id_categoria'];
                $estado = $_POST['estado'];
                $foto = $_FILES['foto'];

                // Verifica que todos los datos requeridos estén presentes
                if (!isset($nombre) || !isset($descripcion) || !isset($precio) || !isset($id_categoria) || !isset($foto) || !isset($estado)) {
                    throw new Exception('Datos incompletos al guardar');
                }

                // Verifica si el archivo 'foto' fue cargado y si tiene un nombre temporal asignado
                if (isset($_FILES['foto']) && $_FILES['foto']['tmp_name']) {
                    // Asigna el archivo 'foto' a la variable $foto
                    $foto = $_FILES['foto'];
                    // Obtiene la extensión del archivo cargado
                    $extension = pathinfo($foto['name'], PATHINFO_EXTENSION);
                    // Construye la ruta completa donde se guardará la foto, usando el nombre del producto y su extensión
                    $fotoPath = '../img/products/' . $nombre . '.' . $extension;
                    // Mueve el archivo cargado desde su ubicación temporal a la ruta especificada
                    if (!move_uploaded_file($foto['tmp_name'], $fotoPath)) {
                        // Si no se puede mover el archivo, lanza una excepción indicando un error al subir la imagen
                        throw new Exception('Error al subir la imagen');
                    }
                }
                // Llama al método create del modelo de Producto
                $productoModel->create($nombre, $descripcion, $precio, $id_categoria, $estado, $fotoPath);
                $response = ['status' => 'success', 'message' => 'Producto creado con éxito'];
            } catch (Exception $e) {
                $response = ['status' => 'error', 'message' => $e->getMessage()];
            }
            break;

        case 'PUT':
            try {
                // Usamos $_POST para obtener los datos de la solicitud PUT
                $id = $_POST['id'];
                $nombre = $_POST['nombre'];
                $descripcion = $_POST['descripcion'];
                $precio = $_POST['precio'];
                $id_categoria = $_POST['id_categoria'];
                $foto_actual = $_POST['foto_actual'];
                $estado = $_POST['estado'];

                // Verifica que todos los datos requeridos estén presentes
                if (!isset($id) || !isset($nombre) || !isset($descripcion) || !isset($precio) || !isset($id_categoria) || !isset($estado)) {
                    throw new Exception('Datos incompletos del update');
                }

                // Maneja la carga de la nueva foto del producto, si se proporciona una nueva
                $fotoPath = $foto_actual;
                if (isset($_FILES['foto']) && $_FILES['foto']['tmp_name']) {
                    $foto = $_FILES['foto'];
                    $extension = pathinfo($foto['name'], PATHINFO_EXTENSION);
                    $fotoPath = '../img/products/' . $nombre . '.' . $extension;

                    // Elimina la imagen anterior si existe
                    if (file_exists($foto_actual)) {
                        unlink($foto_actual);
                    }

                    if (!move_uploaded_file($foto['tmp_name'], $fotoPath)) {
                        throw new Exception('Error al subir la imagen');
                    }
                }

                // Llama al método update del modelo de Producto
                $productoModel->update($id, $nombre, $descripcion, $precio, $id_categoria, $estado, $fotoPath);
                $response = ['status' => 'success', 'message' => 'Producto actualizado con éxito'];
            } catch (Exception $e) {
                $response = ['status' => 'error', 'message' => $e->getMessage()];
            }
            break;

        case 'DELETE':
            // Obtiene los datos de la solicitud DELETE, generalmente enviados en el cuerpo de la solicitud
            $data = json_decode(file_get_contents("php://input"), true);
            if (!isset($data['id']) && !isset($data['estado'])) {
                throw new Exception('Datos incompletos');
            }
            // Llama al método delete del modelo de Producto
            $productoModel->delete($data['id'],$data['estado']);
            $response = ['status' => 'success', 'message' => 'Producto eliminado con éxito'];
            break;

        default:
            // Si el método HTTP no es GET, POST, PUT o DELETE, lanza una excepción
            throw new Exception('Método no permitido');
    }
} catch (Exception $e) {
    // Captura cualquier excepción y la asigna a la respuesta
    $response = ['status' => 'error', 'message' => $e->getMessage()];
}

// Codifica la respuesta en formato JSON y la imprime
echo json_encode($response);
?>