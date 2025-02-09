<?php
require_once '../classes/Database.php';
session_start();

// Validasi ID pengguna dari URL
if (!isset($_GET['user_id']) || !is_numeric($_GET['user_id'])) {
    die("ID pengguna tidak valid atau tidak ada.");
}

$user_id = intval($_GET['user_id']);

$db = new Database();
$conn = $db->conn;

class Profile {
    private $conn;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function getUserProfile($user_id) {
        $sql = "SELECT username, profile_picture, latitude, longitude FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    public function getPosts($user_id) {
        $sql = "SELECT p.id, p.content, p.image, p.created_at, u.username, u.profile_picture 
                FROM posts p 
                INNER JOIN users u ON p.user_id = u.id 
                WHERE p.user_id = ? 
                ORDER BY p.created_at DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}

$profile = new Profile($conn);
$userData = $profile->getUserProfile($user_id);
$posts = $profile->getPosts($user_id);

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <header class="bg-yellow-500 p-4 text-white text-center text-2xl font-bold">
        Profil Pengguna
    </header>
    
    <div class="mx-auto bg-white shadow-md p-6 max-w-xl mt-6 rounded-lg">
        <div class="text-center">
            <?php if ($userData): ?>
                <img src="<?= htmlspecialchars($userData['profile_picture']) ?>" class="w-24 h-24 rounded-full object-cover mx-auto" alt="Foto Profil">
                <h2 class="mt-4 text-xl font-semibold"> <?= htmlspecialchars($userData['username']) ?> </h2>
            <?php else: ?>
                <p class="text-red-500 font-semibold">Pengguna tidak ditemukan.</p>
            <?php endif; ?>
        </div>
        
        <div class="mt-6">
            <h3 class="text-lg font-semibold">Postingan</h3>
            <?php if (!empty($posts)): ?>
                <?php foreach ($posts as $post): ?>
                    <div class="bg-gray-100 p-4 rounded-lg mt-4">
                        <p class="text-gray-700"> <?= nl2br(htmlspecialchars($post['content'])) ?> </p>
                        <?php if (!empty($post['image'])): ?>
                            <img src="<?= htmlspecialchars($post['image']) ?>" class="w-full h-64 rounded-lg object-cover mt-2" alt="Gambar Postingan">
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center text-gray-600">Belum ada postingan.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
