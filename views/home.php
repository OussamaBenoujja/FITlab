<?php

class BaseCRUD {
    protected $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($table, $data) {
       
    }

    public function read($table, $conditions = '1=1') {
        $query = "SELECT * FROM $table WHERE $conditions";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($table, $data, $conditions) {
        
    }

    public function delete($table, $conditions) {
        
    }
}

class Activities extends BaseCRUD {
    private $table = 'activities';

    public function createActivity($data) {
        return $this->create($this->table, $data);
    }

    public function getActivity($id) {
        return $this->read($this->table, "id = $id");
    }

    public function updateActivity($id, $data) {
        return $this->update($this->table, $data, "id = $id");
    }

    public function deleteActivity($id) {
        return $this->delete($this->table, "id = $id");
    }

    public function getAllActivities() {
        return $this->read($this->table);
    }
}


$host = 'localhost';
$db = 'fitbase';
$user = 'osama'; 
$pass = 'maeil1310';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}


$activitiesClass = new Activities($conn);
$activities = $activitiesClass->getAllActivities();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <title>Activities</title>
</head>
<body>


    <main>
        <div class="container mx-auto p-10">
            <h2 class="text-4xl text-center mb-8">Available Activities</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php foreach ($activities as $activity): ?>
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <a href="activity.php?activity_ID=<?php echo $activity['id']; ?>">
                            <div class="relative">
                                <img class="w-full h-48 object-cover" src="https://via.placeholder.com/600x360" alt="<?php echo htmlspecialchars($activity['name']); ?>">
                                <div class="absolute bottom-0 right-0 bg-gray-800 text-white px-2 py-1 m-2 rounded-md text-xs">3 min read</div>
                            </div>
                            <div class="p-4">
                                <div class="text-lg font-medium text-gray-800 mb-2"><?php echo htmlspecialchars($activity['name']); ?></div>
                                <p class="text-gray-500 text-sm"><?php echo htmlspecialchars($activity['description']); ?></p>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <!-- Footer -->

    <script>
         function toggleNavbar(collapseID) {
            document.getElementById(collapseID).classList.toggle('hidden')
            document.getElementById(collapseID).classList.toggle('block')
        }

        AOS.init({
            delay: 200,
            duration: 1200,
            once: false,
        })
    </script>
</body>
</html>

<?php

$conn = null;
?>