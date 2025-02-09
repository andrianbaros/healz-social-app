<?php
session_start();
require_once '../classes/Database.php';
require_once '../classes/Post.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit();
}

$user_id = $_SESSION['user_id'];
$content = trim($_POST['content'] ?? '');
$imagePath = null; // Default tidak ada gambar

// Cek apakah ada file yang diunggah
if (!empty($_FILES['image']['name'])) {
    $uploadDir = '../uploads/'; // Folder penyimpanan gambar
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true); // Buat folder jika belum ada
    }

    $imageName = time() . '_' . basename($_FILES['image']['name']);
    $targetFile = $uploadDir . $imageName;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Validasi jenis file
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowedTypes)) {
        echo json_encode(["error" => "Invalid image format. Allowed formats: JPG, PNG, GIF"]);
        exit();
    }

    // Validasi ukuran file (maksimal 2MB)
    if ($_FILES['image']['size'] > 2 * 1024 * 1024) {
        echo json_encode(["error" => "Image size must be less than 2MB."]);
        exit();
    }

    // Pindahkan file ke folder uploads
    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
        $imagePath = $targetFile; // Simpan path relatif
    } else {
        echo json_encode(["error" => "Failed to upload image"]);
        exit();
    }
}

$post = new Post();
$response = $post->createPost($user_id, $content, $imagePath);

if ($response["success"]) {
    echo json_encode([
        "success" => true,
        "new_post_id" => $response["post_id"],
        "new_post_content" => $content,
        "username" => $_SESSION['username'],
        "image" => $response["image_path"]
    ]);
} else {
    echo json_encode(["error" => "Failed to create post"]);
}
?>
