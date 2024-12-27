<?php

session_start();



if(isset($_SESSION['user_id']) && $_SESSION['role'] == 'admin'){
        header('Location: admin_dashboard.php');
}



require_once('../control/db_config.php');
require_once('../control/basecrud.php');
require_once('../control/control.php');

$database = new Database();
$db = $database->conn;

$activities = new Activities($db);
$users = new Users($db);

$activityId = isset($_GET['activity_ID']) ? intval($_GET['activity_ID']) : 0;

$currentUserId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

$activityDetails = $activities->getActivity($activityId);

if (empty($activityDetails)) {
    die("Activity not found.");
}

$activity = $activityDetails[0]; 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $currentUserId !== null) {
    $reservationDate = isset($_POST['reservation_date']) ? $_POST['reservation_date'] : date('Y-m-d');

    $reservationData = [
        'user_id' => $currentUserId,
        'activity_id' => $activityId,
        'reservation_date' => $reservationDate
    ];
    
    $reservations = new Reservations($db);
    $reservations->createReservation($reservationData);

    echo "Reservation successful!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title><?php echo htmlspecialchars($activity['name']); ?></title>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-2xl bg-white shadow-lg rounded-lg p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-4"><?php echo htmlspecialchars($activity['name']); ?></h1>
        <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($activity['description']); ?></p>
        <p class="text-lg font-semibold text-gray-700 mb-6">Price: $<?php echo htmlspecialchars($activity['price']); ?></p>

        <?php if ($currentUserId !== null): ?>
            <form method="post" class="space-y-4">
                <div>
                    <label for="reservation_date" class="block text-sm font-medium text-gray-700 mb-2">Select a Date:</label>
                    <input type="date" id="reservation_date" name="reservation_date" class="bg-gray-100 border outline-none rounded-md py-2 px-4 w-full">
                </div>
                <div class="flex justify-center">
                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded-md">
                        Reserve This Activity
                    </button>
                </div>
            </form>
        <?php else: ?>
            <p class="text-red-500 font-semibold">Please log in to reserve this activity.</p>
        <?php endif; ?>
    </div>
</body>
</html>
