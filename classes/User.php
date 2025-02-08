<?php
require_once 'Database.php';

class User {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public function getUserId() {
        return $_SESSION['user_id'] ?? null;
    }

    public function getUserById($user_id) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
    
        if (!$user) {
            error_log("User with ID $user_id not found in database");
        }
    
        return $user;
    }

    public function updateProfile($user_id, $username, $bio, $email, $password = null) {
        // Ambil data lama dari database
        $stmt = $this->conn->prepare("SELECT username, bio, email, password FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $oldData = $result->fetch_assoc();
    
        if (!$oldData) {
            return false; // Jika user tidak ditemukan
        }
    
        // Periksa apakah ada perubahan data
        $updateFields = [];
        $params = [];
        $types = "";
    
        if ($username !== $oldData['username']) {
            $updateFields[] = "username = ?";
            $params[] = $username;
            $types .= "s";
        }
    
        if ($bio !== $oldData['bio']) {
            $updateFields[] = "bio = ?";
            $params[] = $bio;
            $types .= "s";
        }
    
        if ($email !== $oldData['email']) {
            $updateFields[] = "email = ?";
            $params[] = $email;
            $types .= "s";
        }
    
        if (!empty($password)) { // Jika user menginput password baru
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $updateFields[] = "password = ?";
            $params[] = $hashed_password;
            $types .= "s";
        }
    
        if (empty($updateFields)) {
            return true; // Tidak ada perubahan yang perlu dilakukan
        }
    
        $sql = "UPDATE users SET " . implode(", ", $updateFields) . " WHERE id = ?";
        $params[] = $user_id;
        $types .= "i";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
    
        return $stmt->execute();
    }
    
    
}
?>

