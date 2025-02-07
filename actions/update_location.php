<?php
session_start();
require 'classes/database.php';

// Check if latitude and longitude are provided
if (isset($_POST['latitude'], $_POST['longitude'])) {
    $user_id = $_SESSION['user_id']; // Get user ID from session
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    $db = new Database();
    $conn = $db->conn;
    $profile = new Profile($conn);

    // Update the user's location
    if ($profile->updateUserLocation($user_id, $latitude, $longitude)) {
        echo "Location updated successfully!";
    } else {
        echo "Failed to update location.";
    }

    $conn->close();
} else {
    echo "Latitude or Longitude missing.";
}
?>
