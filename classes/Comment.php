<?php
require_once 'Database.php';

class Comment {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function addComment($postId, $userId, $commentText) {
        $stmt = $this->conn->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $postId, $userId, $commentText);
        return $stmt->execute();
    }

    public function getComments($postId) {
        $stmt = $this->conn->prepare("SELECT * FROM comments WHERE post_id = ? ORDER BY created_at DESC");
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getLatestComment($postId, $userId) {
    $stmt = $this->conn->prepare("SELECT * FROM comments WHERE post_id = ? AND user_id = ? ORDER BY created_at DESC LIMIT 1");
    $stmt->bind_param("ii", $postId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

}
?>
