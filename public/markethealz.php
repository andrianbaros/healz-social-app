<!--markethealz.php -->
<?php
session_start();
require_once '../classes/Product.php'; // Controller
?>

<html>
  <head>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <title>MarketHealz</title>
    <link rel="icon" type="image/png" href="../assets/images/hkuning.png">
  </head>
  <body class="flex">
    <!-- Sidebar -->
        <div class="flex">
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
                                <a class="flex items-center ml-2 space-x-2 text-gray-800 hover:text-red-800" href="../actions/logout.php">
                    <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
                </a>

            </nav>
        </div>


      </nav>
    </div>
    <!-- Main Content -->
    <div class="flex-1 p-4">
      <!-- Search Bar -->
      <form method="GET" action="markethealz.php" class="flex items-center mb-8">
        <input class="w-full p-2 rounded-l-lg border border-gray-300" placeholder="Search" type="text" name="search_query" value="<?= isset($_GET['search_query']) ? $_GET['search_query'] : '' ?>" />
        <button class="bg-yellow-300 p-3 rounded-r-lg border border-gray-300">
          <i class="fas fa-search"></i>
        </button>
      </form>
      <div class="flex items-center mb-8" >
                <button onclick="window.location.href='addproduct.php'" class="bg-yellow-500 ml-2 w-30 h-10 text-gray-800 px-20 rounded-lg flex items-center space-x-2 hover:bg-yellow-800 transition">
          <i class="fas fa-plus"></i><b> Add Product Post</b>
        </button>
      </div>


      <!-- Projects Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php foreach ($products as $product) : ?>
          <div class='border border-gray-300 rounded-lg p-4'>
            <img src='<?= $product['image'] ?>' class='w-full h-40 object-cover rounded' alt='Product Image'>
            <h3 class='text-lg font-bold mt-2'><?= $product['title'] ?></h3>
            <p class='text-sm text-gray-600'><?= $product['category'] ?> - <?= $product['asset_type'] ?></p>
            <p class='text-sm text-gray-800'>License: <?= $product['license'] ?></p>
            <p class='text-sm font-bold mt-2'>Rp.  <?= $product['price'] ?></p>
            <table class="w-full">
  <tr>
    <td class="flex items-center gap-2">
      <!-- Tombol Detail -->
      <a href="product_detail.php?id=<?= $product['id'] ?>" 
         class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-700 transition">
        <i class="fas fa-info-circle"></i> Detail
      </a>

     <!-- Form untuk hapus produk -->
<?php if ($_SESSION['user_id'] == $product['user_id']) : ?>
  <form method="POST" action="../actions/delete_product.php" onsubmit="return confirmDelete();">
    <input type="hidden" name="id" value="<?= $product['id'] ?>">
    <button type="submit" 
            class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-700 transition">
      <i class="fas fa-trash"></i> Delete
    </button>
  </form>
<?php endif; ?>

    </td>
  </tr>
</table>

            
          </div>
        <?php endforeach; ?>
      </div>
    </div>

<!-- Filter Sidebar -->
<div class="bg-yellow-300 w-1/5 min-h-screen p-4">
  <div class="font-bold mb-4">Filter by</div>
  <form method="GET" action="markethealz.php">
    <div class="mb-4">
      <div class="font-bold">Category</div>
      <select name="category" class="w-full p-2 border border-gray-300 rounded">
        <option value="">All</option>
        <option value="Graphic Design" <?= $category_filter == 'Graphic Design' ? 'selected' : '' ?>>Graphic Design</option>
        <option value="Branding" <?= $category_filter == 'Branding' ? 'selected' : '' ?>>Branding</option>
        <option value="Illustration" <?= $category_filter == 'Illustration' ? 'selected' : '' ?>>Illustration</option>
        <option value="UI Design" <?= $category_filter == 'UI Design' ? 'selected' : '' ?>>UI Design</option>
        <option value="etc." <?= $category_filter == 'etc.' ? 'selected' : '' ?>>etc.</option>
      </select>
    </div>
    <div class="mb-4">
      <div class="font-bold">Asset Type</div>
      <select name="asset_type" class="w-full p-2 border border-gray-300 rounded">
        <option value="">All</option>
        <option value="Vectors" <?= $asset_type_filter == 'Vectors' ? 'selected' : '' ?>>Vectors</option>
        <option value="Photos" <?= $asset_type_filter == 'Photos' ? 'selected' : '' ?>>Photos</option>
        <option value="Icons" <?= $asset_type_filter == 'Icons' ? 'selected' : '' ?>>Icons</option>
        <option value="PSD" <?= $asset_type_filter == 'PSD' ? 'selected' : '' ?>>PSD</option>
        <option value="etc." <?= $asset_type_filter == 'etc.' ? 'selected' : '' ?>>etc.</option>
      </select>
    </div>
    <div class="mb-4">
      <div class="font-bold">License</div>
      <select name="license" class="w-full p-2 border border-gray-300 rounded">
        <option value="">All</option>
        <option value="Free" <?= $license_filter == 'Free' ? 'selected' : '' ?>>Free</option>
        <option value="Paid" <?= $license_filter == 'Paid' ? 'selected' : '' ?>>Paid</option>
      </select>
    </div>
    <button type="submit" class="bg-gray-900 text-yellow-500 p-3 rounded mt-4 w-full"><b>Apply Filters</b></button>
  </form>
</div>
<script>
  function confirmDelete() {
    return confirm("Apakah Anda yakin ingin menghapus produk ini?");
  }
</script>
<?php if (isset($_SESSION['message'])): ?>
  <div class="bg-green-500 text-white p-2 rounded mb-4">
    <?= $_SESSION['message']; unset($_SESSION['message']); ?>
  </div>
<?php endif; ?>
  </body>
</html>
<!---->