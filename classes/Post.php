<?php
require_once 'Database.php';
require_once 'Like.php'; // Tambahkan ini agar bisa pakai fungsi Like

class Post {
    private $conn;
    private $like;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        $this->like = new Like();
    }

    public function createPost($userId, $content, $image = null) {
        $stmt = $this->conn->prepare("INSERT INTO posts (user_id, content, image) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $userId, $content, $image);
        return $stmt->execute();
    }

    public function getAllPosts() {
        $sql = "SELECT posts.id, posts.content, posts.image, posts.user_id, users.username 
                FROM posts 
                JOIN users ON posts.user_id = users.id 
                ORDER BY posts.id DESC";

        $result = $this->conn->query($sql);
        $posts = [];

        while ($row = $result->fetch_assoc()) {
            $row['like_count'] = $this->like->getLikeCount($row['id']);
            $posts[] = $row;
        }

        return $posts;
    }
}


?>
