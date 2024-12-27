<?php
session_start();


if(isset($_SESSION['user_id']){
      if($_SESSION['role'] == 'admin'){
        header('Location: admin_dashboard.php');
      }else if($_SESSION['role'] == 'member'){
        header('Location: home.php');
      }
}


require_once('../control/db_config.php');
require_once('../control/basecrud.php');
require_once('../control/control.php');

$database = new Database();
$conn = $database->conn;
$users = new Users($conn);


if(isset($_SESSION['error'])){
  echo $_SESSION['error'];
}

if(isset($_SESSION['success'])){
  echo $_SESSION['success'];
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = trim($_POST['firstname']);
    $lastname  = trim($_POST['lastname']);
    $email     = trim($_POST['email']);
    $bday      = $_POST['bday'];
    $password  = $_POST['password'];
    $passwordConfirmation = $_POST['password_confirmation'];

    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Invalid email format.';
        header('Location: ../views/register.php');
        exit;
    }
    
    if ($password !== $passwordConfirmation) {
        $_SESSION['error'] = 'Passwords do not match.';
        header('Location: ../views/register.php');
        exit;
    }
    
    $emCheck = "SELECT * FROM users WHERE email = :email";
    $stmt = $conn->prepare($emCheck);
    $stmt->bindParam(':email', $email,PDO::PARAM_STR);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $_SESSION['error'] = 'Email or username already exists.';
        header('Location: ../views/register.php');
        exit;
    }
    
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    $userData = [
        'firstname' => $firstname,
        'lastname' => $lastname,
        'email' => $email,
        'password' => $hashedPassword,
        'date_joined' => date('Y-m-d'),
        'role' => 'member',
        'bday' => $bday
    ];
    
    if ($users->createUser($userData)) {
        $_SESSION['success'] = 'Account created successfully. You can now login.';
        header('Location: ../views/login.php');
        exit;
    } else {
        $_SESSION['error'] = 'An error occurred. Please try again.';
        header('Location: ../views/register.php');
        exit;
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Register</title>
</head>
<body>
  

<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <img class="mx-auto h-10 w-auto" src="https://www.svgrepo.com/show/301692/login.svg" alt="Workflow">
        <h2 class="mt-6 text-center text-3xl leading-9 font-extrabold text-gray-900">
            Create a new account
        </h2>
        <p class="mt-2 text-center text-sm leading-5 text-gray-500 max-w">
            Or
            <a href="../views/login.php"
                class="font-medium text-blue-600 hover:text-blue-500 focus:outline-none focus:underline transition ease-in-out duration-150">
                login to your account
            </a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <form method="POST" action="register.php" id="validation" name='signup'>
                <div>
                    <label for="email" class="block text-sm font-medium leading-5  text-gray-700">FIRSTName</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input id="firstname" name="firstname" placeholder="your first name"  type="text" required=""
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                        <div class="hidden absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="lastname" class="block text-sm font-medium leading-5  text-gray-700 mt-4">LastName</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input id="lastName" name="lastname" placeholder="your last name"  type="text" required=""
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                        <div class="hidden absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="bday" class="block text-sm font-medium leading-5  text-gray-700 mt-4">BIRTHDAY</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input id="bday" name="bday" placeholder="your birthday"  type="date" required=""
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                        <div class="hidden absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <label for="email" class="block text-sm font-medium leading-5 text-gray-700">
                        Email address
                    </label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input id="email" name="email" placeholder="user@example.com" type="email"
                            required=""
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                        <div class="hidden absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <label for="password" class="block text-sm font-medium leading-5 text-gray-700">
                        Password
                    </label>
                    <div class="mt-1 rounded-md shadow-sm">
                        <input id="password" name="password" type="password" required=""
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                    </div>
                </div>

                <div class="mt-6">
                    <label for="password_confirmation" class="block text-sm font-medium leading-5 text-gray-700">
                        Confirm Password
                    </label>
                    <div class="mt-1 rounded-md shadow-sm">
                        <input id="password_confirmation" name="password_confirmation" type="password" required=""
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                    </div>
                </div>

                <div class="mt-6">
                    <span class="block w-full rounded-md shadow-sm">
                        <button type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition duration-150 ease-in-out">
                            Create account
                        </button>
                    </span>
                </div>
            </form>

        </div>
    </div>
</div>

        <script>
        
        const namePattern = /^[a-zA-Z\s]+$/;
                const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/; 
                const passwordPattern = /^(?=.*[a-zA-Z0-9]).{4,}$/;
        
        
                document.getElementById('validation').addEventListener('submit', function(event) {
                        const nome = document.getElementById('firstName').value;
                        const prenome = document.getElementById('lastName').value;
                        const email = document.getElementById('email').value;
                        const password = document.getElementById('password').value;
        
                        if (!namePattern.test(nome) || !namePattern.test(prenome)) {
                            alert("name should only contain letters and spaces.");
                            event.preventDefault();
                            return;
                        }
        
                        if (!emailPattern.test(email)) {
                            alert("please enter a valid email address.");
                            event.preventDefault();
                            return;
                        }
        
                        if (!passwordPattern.test(password)) {
                            alert("password must be at least 4 characters long");
                            event.preventDefault();
                            return;
                        }
        
                });
        
        </script>

</body>
</html>