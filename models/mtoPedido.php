<?php
require_once '../controllers/db_config.php';//Agregamos la conexion

class Pedido {
    private $conn;//Variable de conexion
    private $table_name = "pedido"; //variable que contiene el nombre de la tabla
    private $detalle_table_name = "pedidodetalle"; //variable que contiene el nombre de la tabla

    //Metodo constructor de la clase
    public function __construct() {
        $database = new Database();//Instanciamos la conexion
        $this->conn = $database->getConnection();//Asignamos la conexion a la variable previamente definida
    }

    //Funcion que muestra todos los registros de la tabla
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE estado = 'Nuevo' ORDER BY fecha_pedido DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function read($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE estado = 'Nuevo' AND id_pedido = ". $id ." ORDER BY fecha_pedido DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function detalle($id) {
        $query = "SELECT m.nombre AS nombre, pd.cantidad AS cantidad FROM pedido p, pedidodetalle pd, menuitem m WHERE p.id_pedido = pd.id_pedido AND pd.id_item = m.id_item AND p.id_pedido = " .$id;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Función para crear un nuevo pedido
    public function createPedido($estado, $num_mesa, $id_mesero) {
        $query = "INSERT INTO " . $this->table_name . " (estado, num_mesa, id_mesero) VALUES (:estado, :num_mesa, :id_mesero)";
        $stmt = $this->conn->prepare($query);

        // Bind de los parámetros
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':num_mesa', $num_mesa);
        $stmt->bindParam(':id_mesero', $id_mesero);

        // Ejecuta la consulta
        if ($stmt->execute()) {
            return $this->conn->lastInsertId(); // Retorna el ID del último pedido creado
        }
        return false; // Retorna falso en caso de error
    }

    // Función para agregar detalles al pedido
    public function addDetallePedido($id_pedido, $id_item, $cantidad, $precio_unitario) {
        $query = "INSERT INTO " . $this->detalle_table_name . " (id_pedido, id_item, cantidad, precio_unitario) VALUES (:id_pedido, :id_item, :cantidad, :precio_unitario)";
        $stmt = $this->conn->prepare($query);

        // Bind de los parámetros
        $stmt->bindParam(':id_pedido', $id_pedido);
        $stmt->bindParam(':id_item', $id_item);
        $stmt->bindParam(':cantidad', $cantidad);
        $stmt->bindParam(':precio_unitario', $precio_unitario);

        // Ejecuta la consulta
        if ($stmt->execute()) {
            return true; // Retorna verdadero si se agrega correctamente
        }
        return false; // Retorna falso en caso de error
    }

    public function obtenerPedidosDespachados() {
        $query = "SELECT * FROM pedido WHERE estado = 'Despachado' ORDER BY fecha_pedido DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    

    /*public function updateStatus($id, $estado)
    {
        $query = "UPDATE " . $this->table_name . " SET estado = :estado WHERE id_pedido = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':estado', $estado);
        return $stmt->execute();
    }*/
}
?>