<?php
require_once '../controllers/db_config.php';//Agregamos la conexion

class Factura {
    private $conn;//Variable de conexion
    private $table_name = "factura"; //variable que contiene el nombre de la tabla
    private $detalle_table_name = "detallefactura"; //variable que contiene el nombre de la tabla
   
    //Metodo constructor de la clase
    public function __construct() {
        $database = new Database();//Instanciamos la conexion
        $this->conn = $database->getConnection();//Se asigna la conexión a la variable previamente definida
    }

    // Función para agregar factura
    public function addFactura($id_pedido, $id_mesero, $fecha_factura, $total) {
        $query = "INSERT INTO " . $this->table_name . " (id_pedido, id_mesero, fecha_factura, total) VALUES (:id_pedido, :id_mesero, :fecha_factura, :total)";
        $stmt = $this->conn->prepare($query);

        // Bind de los parámetros
        $stmt->bindParam(':id_pedido', $id_pedido);
        $stmt->bindParam(':id_mesero', $id_mesero);
        $stmt->bindParam(':fecha_factura', $fecha_factura);
        $stmt->bindParam(':total', $total);

        // Ejecuta la consulta
        if ($stmt->execute()) {
            return $this->conn->lastInsertId(); // Retorna el ID de la última factura generada. 
        }
        return false; // Retorna falso en caso de error
    }

    // Función para agregar detalles al pedido
    public function addDetalleFactura($id_factura, $id_item, $cantidad, $precio_unitario) {
        $query = "INSERT INTO " . $this->detalle_table_name . " (id_factura, id_item, cantidad, precio_unitario) VALUES (:id_factura, :id_item, :cantidad, :precio_unitario)";
        $stmt = $this->conn->prepare($query);

        // Bind de los parámetros
        $stmt->bindParam(':id_factura', $id_factura);
        $stmt->bindParam(':id_item', $id_item);
        $stmt->bindParam(':cantidad', $cantidad);
        $stmt->bindParam(':precio_unitario', $precio_unitario);

        // Ejecuta la consulta
        if ($stmt->execute()) {
            return true; // Retorna verdadero si se agrega correctamente
        }
        return false; // Retorna falso en caso de error
    }
}
?>