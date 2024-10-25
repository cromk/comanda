<?php
require_once '../controllers/db_config.php';//Agregamos la conexion
require_once '../libs/fpdf/fpdf.php'; // Asegúrate de tener instalada la librería FPDF

class Factura {
    private $conn;//Variable de conexion
    private $table_name = "pedido"; //variable que contiene el nombre de la tabla
    private $detalle_table_name = "pedidodetalle"; //variable que contiene el nombre de la tabla

    //Metodo constructor de la clase
    public function __construct() {
        $database = new Database();//Instanciamos la conexion
        $this->conn = $database->getConnection();//Asignamos la conexion a la variable previamente definida
    }

     // Función para obtener todos los pedidos que están en estado 'Despachado'
     public function obtenerPedidosDespachados() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE estado = 'Despachado' ORDER BY fecha_pedido DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Función para obtener los detalles de un pedido específico
    public function obtenerDetallesPedido($id) {
        $query = "SELECT m.id_item AS id_item, m.nombre AS nombre, pd.cantidad AS cantidad, pd.precio_unitario AS precio_unitario 
                  FROM " . $this->table_name . " p
                  INNER JOIN " . $this->detalle_table_name . " pd ON p.id_pedido = pd.id_pedido
                  INNER JOIN menuitem m ON pd.id_item = m.id_item 
                  WHERE p.id_pedido = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
 // Función para generar la factura en PDF
 public function generarFactura($id_pedido) {
    
}
}
?>