<?php
session_start();
require_once '../classes/Database.php';
require_once '../classes/User.php';
require_once '../classes/Like.php'; // Pastikan file ini ada dan benar

$user = new User();
$like = new Like();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$user->isLoggedIn()) {
        echo json_encode(["error" => "User not logged in"]);
        exit();
    }

    $postId = isset($_POST['post_id']) ? $_POST['post_id'] : null;
    if ($postId === null) {
        echo json_encode(["error" => "Post ID is missing"]);
        exit();
    }

    $userId = $user->getUserId();
    
    // Debug: Cek apakah userId dan postId diterima dengan benar
    error_log("User ID: $userId, Post ID: $postId");

    // Toggle like status
    $action = $like->toggleLike($postId, $userId);
    if ($action === false) {
        echo json_encode(["error" => "Failed to toggle like"]);
        exit();
    }

    // Debug: Periksa aksi yang dilakukan
    error_log("Action performed: $action");

    // Get the updated like count
    $likeCount = $like->getLikeCount($postId);

    echo json_encode([
        "action" => $action,  // "liked" atau "unliked"
        "like_count" => $likeCount
    ]);
}
?>
