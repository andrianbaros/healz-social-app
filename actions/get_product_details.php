<!-- get_product_details.php -->
<?php
require_once 'classes/Product.php';

$productId = $_GET['id'];

// Fetch the product details
$product = Product::getProductById($productId);

echo json_encode($product);
?>
