<?php
$host = "localhost";
$user = "root";  // Sesuaikan dengan user database
$pass = "";  // Sesuaikan dengan password database
$db = "healz_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
