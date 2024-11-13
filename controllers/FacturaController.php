<?php
header('Content-Type: application/json');
require_once '../controllers/db_config.php';
require_once '../models/mtoFactura.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $database = new Database();
    $dbConnection = $database->getConnection();
    $factura = new Factura($dbConnection);

    //Guarda en la tabla factura
    $id_factura = $factura->addFactura(
        $data['id_pedido'],
        $data['id_mesero'],
        $data['fecha_factura'],
        $data['total']
    );

    if ($id_factura) {
        //Guarda cada detalle en la tabla detallefactura
        $success = true;
        
        // Recorre cada detalle en el array 'detalles' y guarda en la tabla detallefactura
        foreach($data['detalles'] as $detalle){
            $success = $success && $factura->addDetalleFactura(
                $id_factura,
                $detalle['id_item'],
                $detalle['cantidad'],
                $detalle['precio_unitario']
            );
        } 

        if ($success) {
            echo json_encode(['status' => 'success', 'id_factura' => $id_factura]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al guardar la factura.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Método de solicitud no permitido.']);
    }
}
?>
