<?php
session_start();
require_once '../classes/Database.php';
require_once '../classes/User.php';
require_once '../classes/Post.php';

$user = new User();
$post = new Post();

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$user->isLoggedIn()) {
        $response['error'] = "User not logged in";
    } else {
        $content = $_POST['content'];
        $userId = $_SESSION['user_id'];
        $username = $_SESSION['username'];  // Ensure username is available
        
        // Handle file upload
        $image = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $imageTmp = $_FILES['image']['tmp_name'];
            $imageName = time() . "_" . basename($_FILES['image']['name']);
            $imagePath = "../uploads/" . $imageName;

            if (move_uploaded_file($imageTmp, $imagePath)) {
                $image = $imagePath;
            } else {
                $response['error'] = "Failed to upload image";
            }
        }

        // Insert the new post, including the image if available
        $postId = $post->createPost($userId, $content, $image);
        
        if ($postId) {
            $response['success'] = "Post created successfully";
            $response['new_post_id'] = $postId;
            $response['new_post_content'] = $content;
            $response['image'] = $image ? $image : null;
            $response['username'] = $username;  // Include username in the response
        } else {
            $response['error'] = "Failed to create post";
        }
    }
}

// Send JSON response back to the frontend
echo json_encode($response);
?>
