<?php
require_once 'Database.php';

class Like {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function hasLiked($postId, $userId) {
        $stmt = $this->conn->prepare("SELECT id FROM likes WHERE post_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $postId, $userId);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function toggleLike($postId, $userId) {
    // Check if the user has already liked this post
    if ($this->hasLiked($postId, $userId)) {
        // User already liked, so we remove the like
        $stmt = $this->conn->prepare("DELETE FROM likes WHERE post_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $postId, $userId);
        if ($stmt->execute()) {
            return "unliked";  // Return unliked action
        } else {
            return false;  // Return false on failure
        }
    } else {
        // User has not liked the post, so we add the like
        $stmt = $this->conn->prepare("INSERT INTO likes (post_id, user_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $postId, $userId);
        if ($stmt->execute()) {
            return "liked";  // Return liked action
        } else {
            return false;  // Return false on failure
        }
    }
}

public function getLikeCount($postId) {
    $stmt = $this->conn->prepare("SELECT COUNT(*) as like_count FROM likes WHERE post_id = ?");
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    return $data['like_count'];
 
    
}
}
?>
