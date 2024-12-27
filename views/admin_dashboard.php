<?php






if(isset($_SESSION['user_id']) && $_SESSION['role'] != 'admin'){
        header('Location: home.php');
}



require_once('../control/db_config.php');
require_once('../control/basecrud.php');
require_once('../control/control.php');

$database = new Database();
$conn = $database->conn;

$users = new Users($conn);
$activities = new Activities($conn);


$memberList = $users->read('users', "role = 'member'");
$activityList = $activities->read('activities');


$reservationQuery = "
    SELECT 
        r.id,
        r.reservation_date,
        r.status,
        u.firstName,
        u.lastName,
        u.email,
        a.name as activity_name
    FROM reservations r
    JOIN users u ON r.user_id = u.id
    JOIN activities a ON r.activity_id = a.id
    ORDER BY r.reservation_date DESC";

$stmt = $conn->prepare($reservationQuery);
$stmt->execute();
$reservationList = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete_user'])) {
        $userId = $_POST['user_id'];
        $users->deleteUser($userId);
        header("Location: " . $_SERVER['PHP_SELF']);
    }

    if (isset($_POST['update_reservation_status'])) {
        $reservationId = $_POST['reservation_id'];
        $status = $_POST['status'];
        
        $updateQuery = "UPDATE reservations SET status = :status WHERE id = :id";
        $stmt = $conn->prepare($updateQuery);
        $stmt->execute([
            ':status' => $status,
            ':id' => $reservationId
        ]);
        
        header("Location: " . $_SERVER['PHP_SELF']);
    }

    if (isset($_POST['add_activity'])) {
        $activityData = [
            'name' => $_POST['activity_name'],
            'description' => $_POST['description'],
            'price' => $_POST['price']
        ];
        
        if ($activities->createActivity($activityData)) {
            echo "<script>alert('Activity added successfully!');</script>";
            header("Location: " . $_SERVER['PHP_SELF']);
        } else {
            echo "<script>alert('Failed to add activity.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Fitbase Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" />
</head>
<body class="bg-light">


<nav class="bg-black p-4 shadow-lg">
    <div class="container mx-auto flex items-center justify-between">
    
        <a href="/" class="text-gold font-bold text-xl hover:text-yellow-400 transition duration-300">
            FitLab
        </a>

        
        <div class="flex space-x-6">


            <?php if (!isset($_SESSION['user_id'])) : ?>

            <?php else : ?>

                <a href="logout.php" class="text-yellow-500 hover:text-yellow-400 transition duration-300">
                    LogOut
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>



<div class="container mt-5">
    <h1 class="text-center">Fitbase Admin Dashboard</h1>

    <!-- Button to Add Activity -->
    <div class="mb-3 text-center">
        <button id="toggleActivityForm" class="btn btn-primary">Add Activity</button>
    </div>

    <!-- Add Activity Form -->
    <div id="activityForm" class="mb-4" style="display: none;">
        <h2>Add Activity</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="activity_name" class="form-label">Activity Name</label>
                <input type="text" name="activity_name" id="activity_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" name="price" id="price" class="form-control" required step="0.01">
            </div>
            <button type="submit" name="add_activity" class="btn btn-success">Add Activity</button>
        </form>
    </div>

    <!-- Members Table -->
    <h2>Members</h2>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($memberList as $member): ?>
            <tr>
                <td><?php echo htmlspecialchars($member['firstName']); ?></td>
                <td><?php echo htmlspecialchars($member['lastName']); ?></td>
                <td><?php echo htmlspecialchars($member['email']); ?></td>
                <td>
                    <form method="POST" style="display: inline-block;">
                        <input type="hidden" name="user_id" value="<?php echo $member['id']; ?>">
                        <button type="submit" name="delete_user" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Reservations Table -->
    <h2>Reservations</h2>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Date</th>
            <th>Member Name</th>
            <th>Email</th>
            <th>Activity</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($reservationList as $reservation): ?>
            <tr>
                <td><?php echo htmlspecialchars($reservation['reservation_date']); ?></td>
                <td><?php echo htmlspecialchars($reservation['firstName'] . ' ' . $reservation['lastName']); ?></td>
                <td><?php echo htmlspecialchars($reservation['email']); ?></td>
                <td><?php echo htmlspecialchars($reservation['activity_name']); ?></td>
                <td><?php echo htmlspecialchars($reservation['status']); ?></td>
                <td>
                    <form method="POST" style="display: inline-block;">
                        <input type="hidden" name="reservation_id" value="<?php echo $reservation['id']; ?>">
                        <select name="status" class="form-select form-select-sm d-inline w-auto">
                            <option value="pending" <?php if ($reservation['status'] === 'pending') echo 'selected'; ?>>Pending</option>
                            <option value="confirmed" <?php if ($reservation['status'] === 'confirmed') echo 'selected'; ?>>Confirmed</option>
                            <option value="canceled" <?php if ($reservation['status'] === 'canceled') echo 'selected'; ?>>Canceled</option>
                        </select>
                        <button type="submit" name="update_reservation_status" class="btn btn-primary btn-sm">Update</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Activities Table -->
    <h2>Activities</h2>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($activityList as $activity): ?>
            <tr>
                <td><?php echo htmlspecialchars($activity['name']); ?></td>
                <td><?php echo htmlspecialchars($activity['description']); ?></td>
                <td><?php echo htmlspecialchars($activity['price']); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    document.getElementById('toggleActivityForm').onclick = function() {
        var activityForm = document.getElementById('activityForm');
        activityForm.style.display = activityForm.style.display === "none" ? "block" : "none";
    };
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>