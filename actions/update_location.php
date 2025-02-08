<?php
require '../classes/database.php';
session_start();

// Initialize database and Profile class
$db = new Database();
$conn = $db->conn;
$profile = new Profile($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['latitude']) && isset($_POST['longitude'])) {
    $user_id = $_SESSION['user_id'] ?? null;
    if ($user_id) {
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];

        // Update user location in the database
        $profile->updateUserLocation($user_id, $latitude, $longitude);

        // Return a JSON response
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'User not logged in']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}

$conn->close();
?>
