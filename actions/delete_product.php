<!--delete_product.php-->

<?php
session_start();
require_once '../classes/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    $db = new Database();
    $conn = $db->getConnection();

    $query = "DELETE FROM product_posts WHERE id = ?";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        die('Query prepare failed: ' . $conn->error);
    }

    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Produk berhasil dihapus!";
    } else {
        $_SESSION['message'] = "Gagal menghapus produk!";
    }

    header("Location: ../public/markethealz.php");
    exit();
} else {
    header("Location: ../public/markethealz.php");
    exit();
}
?>