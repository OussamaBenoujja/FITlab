<?php
require_once('../control/db_config.php'); // Adjust the path as necessary for your project structure
require_once('../control/basecrud.php');
require_once('../control/control.php');

// Connect to the database
$database = new Database();
$conn = $database->conn;

// Check if activity_ID is provided in the URL
if (!isset($_GET['activity_ID'])) {
    die("Activity ID is missing.");
}

// Fetch the activity ID from the URL
$activity_id = $_GET['activity_ID'];

// Fetch activity details from the database
$activitiesClass = new Activities($conn);
$activity = $activitiesClass->getActivity($activity_id);

// If no activity found, show an error
if (!$activity) {
    die("Activity not found.");
}

// Handle the form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $date = $_POST['date'];

    // Create a new reservation
    $reservationClass = new Reservations($conn);
    $result = $reservationClass->createReservation([
        'user_id' => $user_id, // Use your actual user ID retrieval method
        'activity_id' => $activity_id,
        'email' => $email,
        'date' => $date
    ]);

    if ($result) {
        $message = "Reservation successful!";
    } else {
        $message = "Failed to make a reservation.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Reserve Activity</title>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Gym Activity Reservations</a>
            <a class="btn btn-outline-success mx-2" href="home.php">Home Page</a>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="text-center">Reserve Activity: <?php echo htmlspecialchars($activity['name'] ?? ''); ?></h1>

        <?php if ($message): ?>
            <div class="alert alert-info text-center"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <div class="card mb-4">
            <img src="https://via.placeholder.com/600x360" class="card-img-top" alt="<?php echo htmlspecialchars($activity['name'] ?? ''); ?>">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($activity['name'] ?? ''); ?></h5>
                <p class="card-text"><?php echo htmlspecialchars($activity['description'] ?? ''); ?></p>
            </div>
        </div>

        <form method="POST" class="bg-light p-4 rounded">
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" name="email" placeholder="Enter email" required>
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Select Date</label>
                <input type="date" class="form-control" name="date" required>
            </div>

            <button type="submit" class="btn btn-primary">Reserve Activity</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
// Close the database connection
$conn = null;
?>