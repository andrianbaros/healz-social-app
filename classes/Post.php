<?php
require_once 'Database.php';
require_once 'Like.php';

class Post {
    private $conn;
    private $like;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        $this->like = new Like();
    }

    public function createPost($userId, $content, $image = null) {
        $imagePath = null;

        if ($image && isset($image['tmp_name']) && !empty($image['tmp_name'])) {
            $targetDir = "../uploads/"; // Simpan di dalam folder uploads (relatif)
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $imageName = time() . "_" . basename($image["name"]);
            $imagePath = $targetDir . $imageName;
            $imageFileType = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));

            // Validasi tipe file gambar
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($imageFileType, $allowedTypes)) {
                return ["success" => false, "error" => "Invalid image format. Only JPG, JPEG, PNG & GIF allowed."];
            }

            // Validasi ukuran file (maksimal 2MB)
            if ($image["size"] > 2 * 1024 * 1024) {
                return ["success" => false, "error" => "Image size must be less than 2MB."];
            }

            if (!move_uploaded_file($image["tmp_name"], $imagePath)) {
                return ["success" => false, "error" => "Failed to upload image."];
            }
        }

        $stmt = $this->conn->prepare("INSERT INTO posts (user_id, content, image) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $userId, $content, $imagePath);
        $success = $stmt->execute();

        return [
            "success" => $success,
            "post_id" => $this->conn->insert_id,
            "image_path" => $imagePath ? $imagePath : null
        ];
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
