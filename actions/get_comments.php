<?php
session_start();
require_once '../classes/Database.php';
require_once '../classes/User.php';
require_once '../classes/Comment.php';

header('Content-Type: application/json'); // Pastikan response selalu JSON

$user = new User();
$comment = new Comment();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!$user->isLoggedIn()) {
        echo json_encode(["error" => "User not logged in"]);
        exit();
    }

    if (!isset($_GET['post_id']) || empty($_GET['post_id'])) {
        echo json_encode(["error" => "Invalid post ID"]);
        exit();
    }

    $postId = intval($_GET['post_id']); // Pastikan ini angka
    $comments = $comment->getComments($postId);

    if ($comments === false) {
        echo json_encode(["error" => "Failed to fetch comments"]);
    } else {
        echo json_encode(["comments" => $comments]);
    }
} else {
    echo json_encode(["error" => "Invalid request method"]);
}
?>
