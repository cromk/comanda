<?php
require_once '../controllers/db_config.php';//Agregamos la conexion

class TipoUsuario {
    private $conn;//Variable de conexion
    private $table_name = "tipo_usuario"; //variable que contiene el nombre de la tabla

    //Metodo constructor de la clase
    public function __construct() {
        $database = new Database();//Iinstanciamos la conexion
        $this->conn = $database->getConnection();//Asignamos la conexion a la variable previamente definida
    }

    //Funcion que muestra todos los registros de la tabla
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id_tipo_usuario";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function read($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_tipo_usuario = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($tipo_usuario, $descripcion_tipo_usuario) {
        $query = "INSERT INTO " . $this->table_name . " (tipo_usuario, descripcion_tipo_usuario) VALUES (:tipo_usuario, :descripcion_tipo_usuario)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':tipo_usuario', $tipo_usuario);
        $stmt->bindParam(':descripcion_tipo_usuario', $descripcion_tipo_usuario);
        return $stmt->execute();
    }

    public function update($id, $tipo_usuario, $descripcion_tipo_usuario) {
        $query = "UPDATE " . $this->table_name . " SET tipo_usuario = :tipo_usuario, descripcion_tipo_usuario = :descripcion_tipo_usuario WHERE id_tipo_usuario = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':tipo_usuario', $tipo_usuario);
        $stmt->bindParam(':descripcion_tipo_usuario', $descripcion_tipo_usuario);
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_tipo_usuario = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>