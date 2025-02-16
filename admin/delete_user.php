<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM users WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        header("Location: dashboard.php");
    } else {
        echo "Gagal menghapus user: " . mysqli_error($conn);
    }
}
?>
