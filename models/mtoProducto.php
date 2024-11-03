<?php
require_once '../controllers/db_config.php';//Agregamos la conexion

class Producto {
    private $conn;//Variable de conexion
    private $table_name = "menuitem"; //variable que contiene el nombre de la tabla

    //Metodo constructor de la clase
    public function __construct() {
        $database = new Database();//Iinstanciamos la conexion
        $this->conn = $database->getConnection();//Asignamos la conexion a la variable previamente definida
    }

    //Funcion que muestra todos los registros de la tabla
    public function readAll() {
        $query = "SELECT id_item, nombre_categoria, nombre, descripcion, precio, CASE WHEN estado = 0 THEN 'AGOTADO' WHEN estado = 1 THEN 'DISPONIBLE' END AS estado, foto FROM menuitem m, categoria c WHERE m.id_categoria = c.id_categoria ORDER BY m.id_categoria ORDER BY id_item";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Funcion que muestra todos los productos con existencia de la tabla
    public function obtenerProductos() {
        $query = "SELECT id_item, nombre, precio, CASE WHEN estado = 0 THEN 'AGOTADO' WHEN estado = 1 THEN 'DISPONIBLE' END AS estado FROM menuitem WHERE estado = 1 ORDER BY id_item";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function read($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_item = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($nombre, $descripcion, $precio, $id_categoria, $estado, $fotoPath) {
        $query = "INSERT INTO " . $this->table_name . " (nombre, descripcion, precio, id_categoria, estado, foto) VALUES (:nombre, :descripcion, :precio, :id_categoria, :estado, :foto)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':precio', $precio);
        $stmt->bindParam(':id_categoria', $id_categoria);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':foto', $fotoPath);
        return $stmt->execute();
    }

    public function update($id, $nombre, $descripcion, $precio, $id_categoria, $fotoPath) {
       $query = "UPDATE " . $this->table_name . " SET nombre = :nombre, descripcion = :descripcion, precio = :precio, id_categoria = :id_categoria, estado = :estado";
        if ($fotoPath) {
            $query .= ", foto = :foto";
        }
        $query .= " WHERE id_item = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':precio', $precio);
        $stmt->bindParam(':id_categoria', $id_categoria);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':id', $id);
        if ($fotoPath) {
            $stmt->bindParam(':foto', $fotoPath);
        }

        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_item = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function getCategoria() {
        $query = "SELECT * FROM categoria";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>