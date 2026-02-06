<?php
require_once 'config/Database.php';

class BaseModel
{
    protected $conn;
    protected $table;

    public function __construct($table)
    {
        $database = new Database();
        $this->conn = $database->getConnection();
        $this->table = $table;
    }

    // Métodos comunes para todos los modelos
    public function getAll()
    {
        $query = "SELECT * FROM " . $this->table . " WHERE activo = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getById($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id_" . $this->table . " = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt;
    }

    public function create($data)
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));

        $query = "INSERT INTO " . $this->table . " ($columns) VALUES ($placeholders)";
        $stmt = $this->conn->prepare($query);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }

    public function update($id, $data)
    {
        $setClause = "";
        foreach ($data as $key => $value) {
            $setClause .= "$key = :$key, ";
        }
        $setClause = rtrim($setClause, ", ");

        $query = "UPDATE " . $this->table . " SET $setClause WHERE id_" . $this->table . " = :id";
        $stmt = $this->conn->prepare($query);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->bindParam(":id", $id);

        return $stmt->execute();
    }

    public function delete($id)
    {
        $query = "UPDATE " . $this->table . " SET activo = 0 WHERE id_" . $this->table . " = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
}
?>