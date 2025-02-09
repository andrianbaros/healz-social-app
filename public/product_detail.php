<?php
require_once '../classes/product.php'; // Pastikan file ini sudah benar
require_once '../classes/Database.php'; // Jika diperlukan

// Pastikan ID produk dikirim via GET
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID produk tidak valid atau tidak ada.");
}

$product_id = $_GET['id'];

// Ambil produk berdasarkan ID
$product = Product::getProductById($product_id);

if (!$product) {
    die("Produk tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
</head>
<body class="flex bg-gray-100">
    <!-- Sidebar -->
    <div class="bg-yellow-400 w-64 min-h-screen p-4 hidden md:block">
        <div class="text-2xl font-bold ml-2 mb-8">Healz.</div>
        <nav class="space-y-4">
            <a class="flex items-center ml-2 space-x-2 text-gray-800 hover:text-gray-600" href="dashboard.php">
                <i class="fas fa-home"></i> <span>Home</span>
            </a>
            <a class="flex items-center ml-2 space-x-2 hover:text-gray-600" href="markethealz.php">
                <i class="fas fa-store"></i> <span>MarketHealz</span>
            </a>
            <a class="flex items-center ml-2 space-x-2 text-gray-800 hover:text-gray-600" href="messages.php">
                <i class="fas fa-envelope"></i> <span>Messages</span>
            </a>
            <a class="flex items-center ml-2 space-x-2 text-gray-800 hover:text-gray-600" href="profile.php">
                <i class="fas fa-user"></i> <span>Profile</span>
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-6">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-4">Detail Produk</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Product Image -->
                <div>
                    <img src="<?php echo htmlspecialchars($product->getImage()); ?>" alt="Gambar Produk" class="w-full rounded-lg shadow-md">
                </div>
                <!-- Product Details -->
                <div>
                    <p class="text-lg font-semibold"><strong>Nama:</strong> <?php echo htmlspecialchars($product->getTitle()); ?></p>
                    <p class="text-gray-700"><strong>Kategori:</strong> <?php echo htmlspecialchars($product->getCategory()); ?></p>
                    <p class="text-gray-700"><strong>Jenis Aset:</strong> <?php echo htmlspecialchars($product->getAssetType()); ?></p>
                    <p class="text-gray-700"><strong>Lisensi:</strong> <?php echo htmlspecialchars($product->getLicense()); ?></p>
                    <p class="text-xl font-bold text-yellow-500 mt-2">Rp. <?php echo htmlspecialchars($product->getPrice()); ?></p>
                    <p class="text-gray-600 mt-4"><strong>Deskripsi:</strong> <?php echo htmlspecialchars($product->getDescription()); ?></p>
                    <p class="mt-4"><strong>Penjual:</strong> <a href="profilepost.php?id=<?php echo htmlspecialchars($product->getUserId()); ?>" class="text-blue-500 hover:underline"> <?php echo htmlspecialchars($product->getUsername()); ?></a></p>
                    <div class="mt-6">
                        <a href="markethealz.php" class="bg-yellow-500 text-white px-6 py-2 rounded-lg hover:bg-yellow-700 transition"><i class="fas fa-arrow-left"></i> Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
