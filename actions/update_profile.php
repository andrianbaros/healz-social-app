<?php
require_once __DIR__ . '/../classes/Database.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die("User belum login!");
}

$db = new Database();
$conn = $db->getConnection();
$user_id = $_SESSION['user_id'];

// **Ambil Data Lama dari Database**
$query_old = "SELECT profile_picture FROM users WHERE id = ?";
$stmt_old = $conn->prepare($query_old);
$stmt_old->bind_param("i", $user_id);
$stmt_old->execute();
$result_old = $stmt_old->get_result();
$old_data = $result_old->fetch_assoc();
$profile_image = $old_data['profile_picture']; // Ambil gambar lama

// **Pastikan semua input tidak kosong**
$username = isset($_POST['username']) ? trim($_POST['username']) : null;
$email = isset($_POST['email']) ? trim($_POST['email']) : null;
$bio = isset($_POST['bio']) ? trim($_POST['bio']) : null;
$password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

if (!$username || !$email) {
    die("Data tidak lengkap!");
}

// **Proses Upload Profile Image**
$upload_dir = "../assets/images/";
if (!empty($_FILES["profile_image"]["name"])) {
    $new_image = $upload_dir . basename($_FILES["profile_image"]["name"]);

    // **Cek apakah file berhasil diupload**
    if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $new_image)) {
        $profile_image = $new_image; // Hanya update jika upload berhasil
    }
}

// **Query Update**
if ($password) {
    $sql = "UPDATE users SET username = ?, email = ?, password = ?, profile_picture = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $username, $email, $password, $profile_image, $user_id);
} else {
    $sql = "UPDATE users SET username = ?, email = ?, profile_picture = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $username, $email, $profile_image, $user_id);
}

// **Cek apakah query berhasil dipersiapkan**
if (!$stmt) {
    die("Error dalam query: " . $conn->error);
}

// **Eksekusi Query**
if ($stmt->execute()) {
    header("Location: ../public/profile.php");
    exit();
} else {
    die("Gagal memperbarui profil: " . $stmt->error);
}
?>
