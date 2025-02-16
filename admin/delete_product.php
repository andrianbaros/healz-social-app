<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM product_posts WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        header("Location: dashboard.php");
    } else {
        echo "Gagal menghapus produk: " . mysqli_error($conn);
    }
}
?>
