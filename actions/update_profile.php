<?php
require_once __DIR__ . '/../classes/Database.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die("User belum login!");
}

$db = new Database();
$conn = $db->getConnection();
$user_id = $_SESSION['user_id'];

// Pastikan semua input tidak kosong
$username = isset($_POST['username']) ? trim($_POST['username']) : null;
$email = isset($_POST['email']) ? trim($_POST['email']) : null;
$bio = isset($_POST['bio']) ? trim($_POST['bio']) : null;
$password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

// Jika input wajib kosong, hentikan proses
if (!$username || !$email) {
    die("Data tidak lengkap!");
}

// **Folder tujuan penyimpanan gambar**
$upload_dir = "../uploads/";

// **Proses Upload Profile Image**
$profile_image = null;
if (!empty($_FILES["profile_image"]["name"])) {
    $profile_image = $upload_dir . basename($_FILES["profile_image"]["name"]);
    move_uploaded_file($_FILES["profile_image"]["tmp_name"], $profile_image);
}

// **Proses Upload Header Image**
$header_image = null;
if (!empty($_FILES["header_image"]["name"])) {
    $header_image = $upload_dir . basename($_FILES["header_image"]["name"]);
    move_uploaded_file($_FILES["header_image"]["tmp_name"], $header_image);
}

// **Ambil gambar lama jika tidak ada gambar baru**
$query_old = "SELECT profile_image, header_image FROM users WHERE id = ?";
$stmt_old = $conn->prepare($query_old);
$stmt_old->bind_param("i", $user_id);
$stmt_old->execute();
$result_old = $stmt_old->get_result();
$old_data = $result_old->fetch_assoc();

if (!$profile_image) {
    $profile_image = $old_data['profile_image'];
}
if (!$header_image) {
    $header_image = $old_data['header_image'];
}

// **Query Update**
if ($password) {
    $sql = "UPDATE users SET username = ?, email = ?, bio = ?, password = ?, profile_image = ?, header_image = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $username, $email, $bio, $password, $profile_image, $header_image, $user_id);
} else {
    $sql = "UPDATE users SET username = ?, email = ?, bio = ?, profile_image = ?, header_image = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $username, $email, $bio, $profile_image, $header_image, $user_id);
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
