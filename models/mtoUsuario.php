<?php
require_once '../controllers/db_config.php';//Agregamos la conexion

class Usuario {
    private $conn;//Variable de conexion
    private $table_name = "usuario"; //variable que contiene el nombre de la tabla

    //Metodo constructor de la clase
    public function __construct() {
        $database = new Database();//Iinstanciamos la conexion
        $this->conn = $database->getConnection();//Asignamos la conexion a la variable previamente definida
    }

    public function readTable(){
        $query = "SELECT id_usuario,tipo_usuario,nombre_usuario,apellido_usuario,mail,fecha_creacion,estado_usuario FROM tipo_usuario tu, usuario u WHERE tu.id_tipo_usuario = u.id_tipo_usuario ORDER BY id_usuario";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Funcion que muestra todos los registros de la tabla
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function read($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_usuario = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($nombre_usuario, $apellido_usuario, $mail, $password, $id_tipo_usuario) {
        $query = "INSERT INTO " . $this->table_name . " (nombre_usuario, apellido_usuario, mail, password, id_tipo_usuario) VALUES (:nombre_usuario, :apellido_usuario, :mail, :password, :id_tipo_usuario)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre_usuario', $nombre_usuario);
        $stmt->bindParam(':apellido_usuario', $apellido_usuario);
        $stmt->bindParam(':mail', $mail);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':id_tipo_usuario', $id_tipo_usuario);
        $stmt->execute();
    }

    public function update($id, $nombre_usuario, $apellido_usuario, $mail, $password, $id_tipo_usuario) {
        $query = "UPDATE " . $this->table_name . " SET nombre_usuario = :nombre_usuario, apellido_usuario = :apellido_usuario, mail = :mail, password = :password, id_tipo_usuario = :id_tipo_usuario WHERE id_usuario = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nombre_usuario', $nombre_usuario);
        $stmt->bindParam(':apellido_usuario', $apellido_usuario);
        $stmt->bindParam(':mail', $mail);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':id_tipo_usuario', $id_tipo_usuario);
        $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_usuario = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function deshabilitar($id,$estado){
        $query = "UPDATE " . $this->table_name . " SET estado_usuario = :estado WHERE id_usuario = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':estado', $estado);
        $stmt->execute();
    }

    public function getTiposUsuario() {
        $query = "SELECT id_tipo_usuario, tipo_usuario FROM tipo_usuario";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>