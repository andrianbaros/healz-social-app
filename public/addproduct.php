<?php
require_once '../classes/Product.php'; // Controller
?>

<html>
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
</head>
<body class="flex">
    <!-- Sidebar -->
    <div class="bg-yellow-400 w-64 min-h-screen p-4 hidden md:block">
        <div class="text-2xl font-bold ml-2 mb-8">Healz.</div>
        <nav class="space-y-4">
            <a class="flex items-center ml-2 space-x-2 text-gray-800 hover:text-gray-600" href="dashboard.php">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>
            <a class="flex items-center ml-2 space-x-2 hover:text-gray-600" href="markethealz.php">
                <i class="fas fa-store"></i>
                <span>MarketHealz</span>
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-4">
        <h2 class="text-2xl font-bold mb-4">Add Product</h2>
        <form method="POST" action="../actions/add_product.php" enctype="multipart/form-data" class="space-y-4">
            <input type="text" name="title" placeholder="Product Title" class="w-full p-2 border border-gray-300 rounded" required>
            <textarea name="description" placeholder="Product Description" class="w-full p-2 border border-gray-300 rounded" required></textarea>
            <input type="number" name="price" step="0.01" placeholder="Price" class="w-full p-2 border border-gray-300 rounded" required>
            <input type="file" name="image" class="w-full p-2 border border-gray-300 rounded">
            <select name="category" class="w-full p-2 border border-gray-300 rounded" required>
                <option value="">Select Category</option>
                <option value="Graphic Design">Graphic Design</option>
                <option value="Branding">Branding</option>
                <option value="Illustration">Illustration</option>
                <option value="UI Design">UI Design</option>
            </select>
            <select name="asset_type" class="w-full p-2 border border-gray-300 rounded" required>
                <option value="">Select Asset Type</option>
                <option value="Vectors">Vectors</option>
                <option value="Photos">Photos</option>
                <option value="Icons">Icons</option>
                <option value="PSD">PSD</option>
            </select>
            <select name="license" class="w-full p-2 border border-gray-300 rounded" required>
                <option value="">Select License</option>
                <option value="Free">Free</option>
                <option value="Paid">Paid</option>
            </select>
            <button type="submit" class="bg-yellow-300 text-gray-800 p-3 rounded hover:bg-yellow-500 transition">Add Product</button>
        </form>
    </div>
</body>
</html>
