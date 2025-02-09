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
public function getCommentsByPostId($postId) {
    $database = new Database();
    $this->conn = $database->getConnection(); // Pastikan koneksi menggunakan mysqli

    $query = "SELECT comments.id, comments.content, comments.user_id, users.username 
          FROM comments 
          LEFT JOIN users ON comments.user_id = users.id 
          WHERE comments.post_id = ? 
          ORDER BY comments.created_at DESC";


    $stmt = $this->conn->prepare($query);
    if (!$stmt) {
        die("Query Error: " . $this->conn->error); // Debugging jika query gagal
    }

    $stmt->bind_param("i", $postId); // "i" untuk integer
    $stmt->execute();
    
    $result = $stmt->get_result();
    $comments = [];

    while ($row = $result->fetch_assoc()) {
        // Tambahkan print_r untuk melihat data yang dikembalikan
        print_r($row);  // Debugging data yang dikembalikan
        $comments[] = $row;
    }

    $stmt->close();
    return $comments;
}

    public function getComments($postId) {
    $query = "SELECT comments.id, comments.content, comments.user_id, users.username 
              FROM comments 
              LEFT JOIN users ON comments.user_id = users.id 
              WHERE comments.post_id = ? 
              ORDER BY comments.created_at DESC";

    $stmt = $this->conn->prepare($query);
    if (!$stmt) {
        die("Query Error: " . $this->conn->error); // Debugging jika query gagal
    }

    $stmt->bind_param("i", $postId);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $comments = [];

    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }

    $stmt->close();
    return $comments;
}

public function getLatestComment($postId, $userId) {
    $query = "SELECT comments.id, comments.content, comments.user_id, users.username, comments.created_at 
              FROM comments 
              LEFT JOIN users ON comments.user_id = users.id 
              WHERE comments.post_id = ? AND comments.user_id = ? 
              ORDER BY comments.created_at DESC 
              LIMIT 1";

    $stmt = $this->conn->prepare($query);
    if (!$stmt) {
        die("Query Error: " . $this->conn->error);
    }

    $stmt->bind_param("ii", $postId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $comment = $result->fetch_assoc();
    
    $stmt->close();
    return $comment;
}




}
?>
