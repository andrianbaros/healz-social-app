<?php
session_start();
require_once '../classes/Database.php';
require_once '../classes/User.php';
require_once '../classes/Comment.php';

$user = new User();
$comment = new Comment();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!$user->isLoggedIn()) {
        echo json_encode(["error" => "User not logged in"]);
        exit();
    }

    $postId = $_GET['post_id'];
    $comments = $comment->getComments($postId);
    echo json_encode(["comments" => $comments]);
}
?>
