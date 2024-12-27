<?php
session_start();




if(isset($_SESSION['user_id'])){
      if($_SESSION['role'] == 'admin'){
        header('Location: admin_dashboard.php');
      }else if($_SESSION['role'] == 'member'){
        header('Location: home.php');
      }
}

require_once('../control/db_config.php');
require_once('../control/basecrud.php');
require_once('../control/control.php');
require_once('../control/auth.php');

$database = new Database();
$conn = $database->conn;
$auth = new Authentication($conn);



  $auth->logout();
  header('Location: login.php');
  exit;
  






?>