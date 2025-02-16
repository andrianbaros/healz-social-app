<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM comments WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        header("Location: dashboard.php");
    } else {
        echo "Gagal menghapus komentar: " . mysqli_error($conn);
    }
}
?>
