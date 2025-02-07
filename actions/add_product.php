<?php
session_start();
require_once '../classes/Database.php'; // Sesuaikan dengan lokasi file Database.php

// Buat instance dari Database
$db = new Database();
$conn = $db->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['user_id'])) {
        die("Error: User not logged in.");
    }

    $user_id = $_SESSION['user_id']; // Ambil user_id dari sesi login
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $asset_type = $_POST['asset_type'];
    $category = $_POST['category'];
    $license = $_POST['license'];
    $image = $_FILES['image']['name'];

    // Upload file
    $target_dir = "../assets/images/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
         $image_path = "../assets/images/" . basename($_FILES["image"]["name"]);
        $stmt = $conn->prepare("INSERT INTO product_posts (user_id, title, description, price, asset_type, category, license, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issdssss", $user_id, $title, $description, $price, $asset_type, $category, $license, $image_path);

        if ($stmt->execute()) {
            header("Location: ../public/markethealz.php?success=Product added successfully");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error uploading file.";
    }
}

$conn->close();