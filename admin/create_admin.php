<?php
include 'config.php';

$username = "admin";
$email = "admin@example.com";
$password = "admin123";  // Ganti dengan password yang aman
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$query = "INSERT INTO admin (username, password, email) VALUES (?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("sss", $username, $hashed_password, $email);

if ($stmt->execute()) {
    echo "Akun admin berhasil dibuat!";
} else {
    echo "Gagal membuat akun admin: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
