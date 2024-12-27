<?php



class Authentication {
    private $db;
    private $usersTable = 'users';

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }
    

    public function login($email, $password) {
        $user = $this->getUserByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['pfp'] = $user['pfp'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['firstname'] = $user['firstName'];
            $_SESSION['lastname'] = $user['lastName'];
            $_SESSION['bday'] = $user['bday'];
            $_SESSION['date_joined'] = $user['date_joined'];
            return true;
        }
        return false;
    }

    private function getUserByEmail($email) {
        $emCheck = "SELECT * FROM users WHERE email = :email";
        
        $stmt = $this->db->prepare($emCheck); 
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ? $result[0] : null;
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public function logout() {
        session_unset();
        session_destroy();
    }
}




?>
