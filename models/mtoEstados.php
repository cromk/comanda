<?php
require_once '../controllers/db_config.php';//Agregamos la conexion

class Estado {
	private $conn;//Variable de conexion
    private $table_primary = "pedido"; //variable que contiene el nombre de la tabla
    private $table_secondary = "pedidodetalle";

    //Metodo constructor de la clase
    public function __construct() {
        $database = new Database();//Iinstanciamos la conexion
        $this->conn = $database->getConnection();//Asignamos la conexion a la variable previamente definida
    }

    //Funcion que muestra todos los registros de la tabla
    public function getPedidos() {
        $query = "SELECT id_pedido,fecha_pedido,estado,num_mesa,CONCAT(u.nombre_usuario,' ',u.apellido_usuario) AS mesero FROM " . $this->table_primary . " p, usuario u WHERE p.id_mesero = u.id_usuario ORDER BY id_pedido";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPedido($id) {
        $query = "SELECT m.nombre AS nombre, pd.cantidad AS cantidad FROM pedido p, pedidodetalle pd, menuitem m WHERE p.id_pedido = pd.id_pedido AND pd.id_item = m.id_item AND p.id_pedido = " .$id;
        error_log('EL QUERI ES: ' . $query);
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>