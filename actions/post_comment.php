<?php
session_start();
require_once '../classes/Database.php';
require_once '../classes/User.php';
require_once '../classes/Comment.php';

$user = new User();
$comment = new Comment();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$user->isLoggedIn()) {
        echo json_encode(["error" => "User not logged in"]);
        exit();
    }

    $postId = isset($_POST['post_id']) ? $_POST['post_id'] : null;
    $commentText = isset($_POST['comment_text']) ? $_POST['comment_text'] : '';

    if ($postId === null || empty($commentText)) {
        echo json_encode(["error" => "Post ID or comment text missing"]);
        exit();
    }

    $userId = $user->getUserId();
    
    // Debug: Cek apakah data diterima dengan benar
    error_log("User ID: $userId, Post ID: $postId, Comment: $commentText");

    if ($comment->addComment($postId, $userId, $commentText)) {
        // Fetch the newly added comment
        $newComment = $comment->getLatestComment($postId, $userId);
        echo json_encode(["success" => "Comment posted successfully", "comment" => $newComment]);
    } else {
        echo json_encode(["error" => "Failed to post comment"]);
    }
}
?>
