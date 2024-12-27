


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

if(isset($_POST['login'])){

  $auth->login($_POST['email'], $_POST['password']);
    header('Location: home.php');
    exit;
  


}



?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Login</title>
</head>
<body>


<nav class="bg-black p-4 shadow-lg">
    <div class="container mx-auto flex items-center justify-between">
    
        <a href="/" class="text-gold font-bold text-xl hover:text-yellow-400 transition duration-300">
            FitLab
        </a>

        
        <div class="flex space-x-6">
            <a href="home.php" class="text-yellow-500 hover:text-yellow-400 transition duration-300">
                Home
            </a>

            <?php if (!isset($_SESSION['user_id'])) : ?>
                <a href="login.php" class="text-yellow-500 hover:text-yellow-400 transition duration-300">
                    Login
                </a>
                <a href="register.php" class="text-yellow-500 hover:text-yellow-400 transition duration-300">
                    Sign Up
                </a>
            <?php else : ?>
                <a href="profile.php" class="text-yellow-500 hover:text-yellow-400 transition duration-300">
                    Profile
                </a>
                <a href="logout.php" class="text-yellow-500 hover:text-yellow-400 transition duration-300">
                    LogOut
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>


<div class="sm:mx-auto sm:w-full sm:max-w-md mt-20">
        <img class="mx-auto h-10 w-auto" src="https://www.svgrepo.com/show/301692/login.svg" alt="Workflow">
        <h2 class="mt-6 text-center text-3xl leading-9 font-extrabold text-gray-900">
                            login to your account

        </h2>
        <p class="mt-2 text-center text-sm leading-5 text-gray-500 max-w">
            Or
            <a href="../views/register.php"
                class="font-medium text-blue-600 hover:text-blue-500 focus:outline-none focus:underline transition ease-in-out duration-150">
                Create a new account             </a>
        </p>
    </div>


<div class="flex justify-center items-center h-screen p-10 ">
  <div class="grid md:grid-cols-2 grid-cols-1  border rounded-3xl">
    <div class="flex justify-center items-center p-5">
      <form action="login.php" method='POST'>
        <h1 class="text-center mb-10 font-bold text-4xl">Login</h1>
        <input type="email" name='email' class=" bg-gray-100 border outline-none rounded-md py-3 w-full px-4 mb-3" placeholder="Email">
        <input type="password" name='password' class=" bg-gray-100 border outline-none rounded-md py-3 w-full px-4 mb-3" placeholder="Password">
        <button type="submit" name='login' class=" bg-yellow-400 hover:bg-yellow-500 border outline-none rounded-md py-3 w-full px-4 font-semibold text-white">submit</button>
      </form>
    </div>
    <div class="">
      <img src="../assest/image/fitnesse.webp" class="rounded-3xl"  alt="">
    </div>
  </div>
</div>
    
</body>
</html>