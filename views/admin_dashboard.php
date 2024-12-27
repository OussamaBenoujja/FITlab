<?php




require_once('../control/db_config.php');
require_once('../control/basecrud.php');
require_once('../control/control.php');

$database = new Database();
$conn = $database->conn;



if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_activity'])) {
    $name = $_POST['activity_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $stmt = $conn->prepare("INSERT INTO activities (name, description, price) VALUES (:name, :description, :price)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':price', $price);

    if ($stmt->execute()) {
        echo "<script>alert('Activity added successfully!');</script>";
    } else {
        echo "<script>alert('Failed to add activity.');</script>";
    }
}

function fetchReservations($conn) {
    $query = "
        SELECT r.id, u.firstName, u.lastName, u.email, r.reservation_date 
        FROM reservations r 
        JOIN users u ON r.user_id = u.id";

    $stmt = $conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$reservationList = fetchReservations($conn);
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

        <!-- Reservations Table -->
        <h2>Member Reservations</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Date of Reservation</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservationList as $reservation): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($reservation['reservation_date']); ?></td>
                        <td><?php echo htmlspecialchars($reservation['firstName']); ?></td>
                        <td><?php echo htmlspecialchars($reservation['lastName']); ?></td>
                        <td><?php echo htmlspecialchars($reservation['email']); ?></td>
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
</body>
</html>