

<?php 

class Database {

    private $host = 'localhost';
    private $user = 'osama';
    private $password = 'maeil1310';
    private $dbname = 'fitbase';
    public $conn;
    
    public function __construct() {
    
        $this->conn = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->user, $this->password);
       
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}
?>