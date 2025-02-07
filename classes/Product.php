<!---product.php -->
<?php
require_once 'Database.php';
require_once '../actions/display_products.php'; // Model

class ProductController {
    private $productModel;

    public function __construct() {
        $this->productModel = new ProductModel();
    }

    // Fetch all products with optional search query and filters
    public function getAllProducts($search_query = '', $category = '', $asset_type = '', $license = '') {
        return $this->productModel->fetchAllProducts($search_query, $category, $asset_type, $license);
    }

    // Add a new product
    public function addProduct($name, $category, $asset_type, $license, $price, $image) {
        return $this->productModel->insertProduct($name, $category, $asset_type, $license, $price, $image);
    }

   // Delete a product
    public function deleteProduct($id) {
        return $this->productModel->removeProduct($id);
    }

}
class Product {
    private $id;
    private $title;
    private $category;
    private $asset_type;
    private $license;
    private $price;
    private $image;
    private $description;
    private $username;

    // Constructor
    public function __construct($id, $title, $category, $asset_type, $license, $price, $image, $description, $username) {
        $this->id = $id;
        $this->title = $title;
        $this->category = $category;
        $this->asset_type = $asset_type;
        $this->license = $license;
        $this->price = $price;
        $this->image = $image;
        $this->description = $description;
        $this->username = $username;
    }

    // Get all products
    public static function getAllProducts($searchQuery = '') {
        // Replace with actual database connection
        $db = new mysqli('localhost', 'root', '', 'market'); // Example connection

        if ($searchQuery) {
            $stmt = $db->prepare("SELECT * FROM products WHERE title LIKE ?");
            $searchQuery = "%" . $searchQuery . "%";
            $stmt->bind_param('s', $searchQuery);
        } else {
            $stmt = $db->prepare("SELECT * FROM products");
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $product = new Product(
                $row['id'],
                $row['title'],
                $row['category'],
                $row['asset_type'],
                $row['license'],
                $row['price'],
                $row['image'],
                $row['description'],
                $row['username']
            );
            $products[] = $product;
        }

        $stmt->close();
        $db->close();

        return $products;
    }

    // Get a specific product by its ID
    public static function getProductById($id) {
        // Replace with actual database connection
        $db = new mysqli('localhost', 'root', '', 'market'); // Example connection

        $stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $product = new Product(
            $row['id'],
            $row['title'],
            $row['category'],
            $row['asset_type'],
            $row['license'],
            $row['price'],
            $row['image'],
            $row['description'],
            $row['username']
        );

        $stmt->close();
        $db->close();

        return $product;
    }
}

// Initialize Controller
$productController = new ProductController();
$search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$asset_type_filter = isset($_GET['asset_type']) ? $_GET['asset_type'] : '';
$license_filter = isset($_GET['license']) ? $_GET['license'] : '';

$products = $productController->getAllProducts($search_query, $category_filter, $asset_type_filter, $license_filter);
?>
