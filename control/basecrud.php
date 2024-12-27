
<?php


class BaseCRUD {
    protected $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    
    public function create($table, $data) {
        $columns = implode(", ", array_keys($data));
        $values = ":" . implode(", :", array_keys($data)); 
        $query = "INSERT INTO $table ($columns) VALUES ($values)";

        $stmt = $this->conn->prepare($query);

        
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }


    public function read($table, $conditions = '1=1') {
        $query = "SELECT * FROM $table WHERE $conditions";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }


    public function update($table, $data, $conditions) {
        $updates = [];
        foreach ($data as $key => $value) {
            $updates[] = "$key = :$key";
        }
        $updateStr = implode(", ", $updates);
        $query = "UPDATE $table SET $updateStr WHERE $conditions";

        $stmt = $this->conn->prepare($query);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }

    public function delete($table, $conditions) {
        $query = "DELETE FROM $table WHERE $conditions";
        $stmt = $this->conn->prepare($query);

        return $stmt->execute();
    }
}

?>
