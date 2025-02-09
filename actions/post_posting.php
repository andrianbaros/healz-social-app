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
    $uploadDir = 'uploads/'; // Folder penyimpanan gambar
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true); // Buat folder jika belum ada
    }

    $imageName = time() . '_' . basename($_FILES['image']['name']);
    $targetFile = $uploadDir . $imageName;

    // Validasi jenis file
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($_FILES['image']['type'], $allowedTypes)) {
        echo json_encode(["error" => "Invalid image format. Allowed formats: JPG, PNG, GIF"]);
        exit();
    }

    // Pindahkan file ke folder uploads
    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
        $imagePath = 'uploads/' . $imageName; // Simpan path relatif
    } else {
        echo json_encode(["error" => "Failed to upload image"]);
        exit();
    }
}

$post = new Post();
$newPostId = $post->createPost($user_id, $content, $imagePath);

if ($newPostId) {
    echo json_encode([
        "success" => true,
        "new_post_id" => $newPostId,
        "new_post_content" => $content,
        "username" => $_SESSION['username'],
        "image" => $imagePath
    ]);
} else {
    echo json_encode(["error" => "Failed to create post"]);
}
?>
