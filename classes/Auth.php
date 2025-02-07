<?php
session_start();
require_once 'database.php';

class Auth {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    public function register($username, $email, $password) {
        // Periksa apakah email sudah terdaftar
        $checkQuery = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->db->prepare($checkQuery);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return "Email sudah digunakan. Gunakan email lain.";
        }

        // Hash password sebelum disimpan
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Query untuk insert data user
        $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        if ($stmt->execute()) {
            return null; // Registrasi berhasil
        } else {
            return "Terjadi kesalahan saat mendaftar. Coba lagi.";
        }
    }
    public function login($username, $password) {
        $query = "SELECT id, username, password FROM users WHERE username = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: dashboard.php");
                exit();
            } else {
                return "Password salah.";
            }
        } else {
            return "Pengguna tidak ditemukan.";
        }
    }
}
?>
