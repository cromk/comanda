<?php
require_once '../controllers/db_config.php';
require_once '../models/mtoFactura.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $dbConnection = $database->getConnection();
    $factura = new Factura($dbConnection);

    $success = $factura->addFactura(
        $_POST['id_pedido'],
        $_POST['id_cajero'],
        $_POST['fecha_factura'],
        $_POST['total']
    );

    if ($success) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al guardar la factura.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de solicitud no permitido.']);
}
?>
