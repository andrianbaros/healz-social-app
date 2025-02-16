<?php
session_start();
include 'config.php';

function fetchData($conn, $table) {
    $query = mysqli_query($conn, "SELECT * FROM $table ORDER BY id ASC");
    if (!$query) {
        die("Error fetching $table: " . mysqli_error($conn));
    }
    return $query;
}

$users = fetchData($conn, "users");
$posts = fetchData($conn, "posts");
$comments = fetchData($conn, "comments");
$likes = fetchData($conn, "likes");
$messages = fetchData($conn, "messages");
$product_posts = fetchData($conn, "product_posts");

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Healz</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 p-6">
    <div class="container mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold mb-4">Dashboard Healz</h1>

        <!-- Manajemen User -->
        <h2 class="text-xl font-semibold mb-2">Manajemen User</h2>
        <table class="w-full border text-sm">
            <tr class="bg-gray-200">
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
            <?php while ($user = mysqli_fetch_assoc($users)) { ?>
            <tr>
                <td><?= $user['id']; ?></td>
                <td><?= $user['username']; ?></td>
                <td><?= $user['email']; ?></td>
                <td>
                    <a href="delete_user.php?id=<?= $user['id']; ?>" class="text-red-500" onclick="return confirm('Hapus user ini?');">Hapus</a>
                </td>
            </tr>
            <?php } ?>
        </table>

        <!-- Manajemen Postingan -->
        <h2 class="text-xl font-semibold mt-6 mb-2">Manajemen Postingan</h2>
        <table class="w-full border text-sm">
            <tr class="bg-gray-200">
                <th>ID</th>
                <th>User</th>
                <th>Konten</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
            <?php while ($post = mysqli_fetch_assoc($posts)) { ?>
            <tr>
                <td><?= $post['id']; ?></td>
                <td><?= $post['user_id']; ?></td>
                <td><?= substr($post['content'], 0, 50); ?>...</td>
                <td><?= date("d-m-Y H:i", strtotime($post['created_at'])); ?></td>
                <td>
                    <a href="delete_post.php?id=<?= $post['id']; ?>" class="text-red-500" onclick="return confirm('Hapus postingan ini?');">Hapus</a>
                </td>
            </tr>
            <?php } ?>
        </table>

        <!-- Manajemen Komentar -->
        <h2 class="text-xl font-semibold mt-6 mb-2">Manajemen Komentar</h2>
        <table class="w-full border text-sm">
            <tr class="bg-gray-200">
                <th>ID</th>
                <th>Post ID</th>
                <th>User ID</th>
                <th>Komentar</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
            <?php while ($comment = mysqli_fetch_assoc($comments)) { ?>
            <tr>
                <td><?= $comment['id']; ?></td>
                <td><?= $comment['post_id']; ?></td>
                <td><?= $comment['user_id']; ?></td>
                <td><?= substr($comment['content'], 0, 50); ?>...</td>
                <td><?= date("d-m-Y H:i", strtotime($comment['created_at'])); ?></td>
                <td>
                    <a href="delete_comment.php?id=<?= $comment['id']; ?>" class="text-red-500" onclick="return confirm('Hapus komentar ini?');">Hapus</a>
                </td>
            </tr>
            <?php } ?>
        </table>

        <!-- Manajemen Likes -->
        <h2 class="text-xl font-semibold mt-6 mb-2">Manajemen Likes</h2>
        <table class="w-full border text-sm">
            <tr class="bg-gray-200">
                <th>ID</th>
                <th>User ID</th>
                <th>Post ID</th>
                <th>Tanggal</th>
            </tr>
            <?php while ($like = mysqli_fetch_assoc($likes)) { ?>
            <tr>
                <td><?= $like['id']; ?></td>
                <td><?= $like['user_id']; ?></td>
                <td><?= $like['post_id']; ?></td>
                <td><?= date("d-m-Y H:i", strtotime($like['created_at'])); ?></td>
            </tr>
            <?php } ?>
        </table>

        <!-- Manajemen Pesan -->
        <h2 class="text-xl font-semibold mt-6 mb-2">Manajemen Pesan</h2>
        <table class="w-full border text-sm">
            <tr class="bg-gray-200">
                <th>Pengirim</th>
                <th>Penerima</th>
                <th>Pesan</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
            <?php while ($message = mysqli_fetch_assoc($messages)) { ?>
            <tr>
                <td><?= $message['sender']; ?></td>
                <td><?= $message['receiver']; ?></td>
                <td><?= substr($message['message'], 0, 50); ?>...</td>
                <td><?= date("d-m-Y H:i", strtotime($message['timestamp'])); ?></td>
                <td>
                    <a href="delete_message.php?id=<?= $message['id']; ?>" class="text-red-500" onclick="return confirm('Hapus pesan ini?');">Hapus</a>
                </td>
            </tr>
            <?php } ?>
        </table>

        <!-- Manajemen Produk -->
        <h2 class="text-xl font-semibold mt-6 mb-2">Manajemen Produk</h2>
        <table class="w-full border text-sm">
            <tr class="bg-gray-200">
                <th>ID</th>
                <th>User ID</th>
                <th>Judul</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
            <?php while ($product = mysqli_fetch_assoc($product_posts)) { ?>
            <tr>
                <td><?= $product['id']; ?></td>
                <td><?= $product['user_id']; ?></td>
                <td><?= $product['title']; ?></td>
                <td>Rp<?= number_format($product['price'], 0, ',', '.'); ?></td>
                <td>
                    <a href="delete_product.php?id=<?= $product['id']; ?>" class="text-red-500" onclick="return confirm('Hapus produk ini?');">Hapus</a>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
