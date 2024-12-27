<?php


if(isset($_SESSION['user_id']) || $_SESSION['role'] == 'admin'){
        header('Location: admin_dashboard.php');
}



require_once('../control/db_config.php');
require_once('../control/basecrud.php');
require_once('../control/control.php');

$database = new Database();
$conn = $database->conn;


$users = new Users($conn);
$activities = new Activities($conn);
$reservations = new Reservations($conn);

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


$userData = $users->getUser($user_id);
$user = $userData[0]; 


function getUserReservationsWithDetails($conn, $user_id) {
    $query = "
        SELECT 
            r.id,
            r.reservation_date,
            r.status,
            a.name as activity_name,
            a.price
        FROM reservations r
        JOIN activities a ON r.activity_id = a.id
        WHERE r.user_id = :user_id
        ORDER BY r.reservation_date DESC";

    $stmt = $conn->prepare($query);
    $stmt->execute(['user_id' => $user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$reservations = getUserReservationsWithDetails($conn, $user_id);


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $updateData = [
        'firstName' => $_POST['firstName'],
        'lastName' => $_POST['lastName'],
        'email' => $_POST['email'],
        'location' => $_POST['location'],
        'bday' => $_POST['bday']
    ];
    
    
    if (!empty($_POST['new_password'])) {
        $updateData['password'] = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    }
    
    
    $updateData = array_filter($updateData, function($value) {
        return $value !== '';
    });
    
    if ($users->updateUser($user_id, $updateData)) {
        $success_message = "Profile updated successfully!";
        
        $userData = $users->getUser($user_id);
        $user = $userData[0];
    } else {
        $error_message = "Failed to update profile.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>User Profile - Fitbase</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" />
</head>
<body class="bg-light">
<div class="container mt-5">
    <h1 class="text-center mb-4">My Profile</h1>

    <?php if (isset($success_message)): ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php endif; ?>
    
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <div class="row">
        <!-- Profile Information -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Profile Information</h3>
                </div>
                <div class="card-body">
                    <form method="POST" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="firstName" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="firstName" name="firstName" 
                                   value="<?php echo htmlspecialchars($user['firstName']); ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="lastName" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="lastName" name="lastName" 
                                   value="<?php echo htmlspecialchars($user['lastName']); ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" class="form-control" id="location" name="location" 
                                   value="<?php echo htmlspecialchars($user['location']); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="bday" class="form-label">Birthday</label>
                            <input type="date" class="form-control" id="bday" name="bday" 
                                   value="<?php echo htmlspecialchars($user['bday']); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password (leave blank to keep current)</label>
                            <input type="password" class="form-control" id="new_password" name="new_password">
                        </div>
                        
                        <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Reservations -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">My Reservations</h3>
                </div>
                <div class="card-body">
                    <?php if (empty($reservations)): ?>
                        <p class="text-muted">You haven't made any reservations yet.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Activity</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($reservations as $reservation): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($reservation['reservation_date']); ?></td>
                                            <td><?php echo htmlspecialchars($reservation['activity_name']); ?></td>
                                            <td>$<?php echo htmlspecialchars(number_format($reservation['price'], 2)); ?></td>
                                            <td>
                                                <span class="badge <?php 
                                                    echo match($reservation['status']) {
                                                        'confirmed' => 'bg-success',
                                                        'canceled' => 'bg-danger',
                                                        default => 'bg-warning'
                                                    };
                                                ?>">
                                                    <?php echo ucfirst(htmlspecialchars($reservation['status'])); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script>
// Form validation
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
})()
</script>
</body>
</html>