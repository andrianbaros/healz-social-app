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
    private $user_id;

    
    // Constructor
    public function __construct($id, $title, $category, $asset_type, $license, $price, $image, $description, $username, $user_id) {
        $this->id = $id;
        $this->title = $title;
        $this->category = $category;
        $this->asset_type = $asset_type;
        $this->license = $license;
        $this->price = $price;
        $this->image = $image;
        $this->description = $description;
        $this->username = $username;
        $this->user_id = $user_id;
    }
     // Getter methods
    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getCategory() {
        return $this->category;
    }

    public function getAssetType() {
        return $this->asset_type;
    }

    public function getLicense() {
        return $this->license;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getImage() {
        return $this->image;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getUsername() {
        return $this->username;
    }
    public function getUserId() {
    return $this->user_id; // Pastikan atribut ini ada di class Product
}


    // Get all products
    public static function getAllProducts($searchQuery = '') {
        $db = new mysqli('localhost', 'root', '', 'healz_db');

        // Cek koneksi database
        if ($db->connect_error) {
            die("Koneksi gagal: " . $db->connect_error);
        }

        if ($searchQuery) {
            $stmt = $db->prepare("SELECT * FROM product_posts WHERE title LIKE ?");
            if (!$stmt) {
                die("Query error: " . $db->error);
            }
            $searchQuery = "%" . $searchQuery . "%";
            $stmt->bind_param('s', $searchQuery);
        } else {
            $stmt = $db->prepare("SELECT * FROM product_posts");
            if (!$stmt) {
                die("Query error: " . $db->error);
            }
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
                isset($row['username']) ? $row['username'] : null ,// Menghindari error Undefined Index
                $row['user_id']
            );
            $products[] = $product;
        }

        $stmt->close();
        $db->close();

        return $products;
    }

    // Get a specific product by its ID
    public static function getProductById($id) {
    if (!is_numeric($id)) {
        die("ID produk tidak valid.");
    }

    $db = new mysqli('localhost', 'root', '', 'healz_db');

    if ($db->connect_error) {
        die("Koneksi gagal: " . $db->connect_error);
    }

    // Ambil produk beserta username penjualnya
    $stmt = $db->prepare("
        SELECT p.*, u.username 
        FROM product_posts p
        LEFT JOIN users u ON p.user_id = u.id
        WHERE p.id = ?
    ");

    if (!$stmt) {
        die("Query error: " . $db->error);
    }

    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        die("Produk dengan ID $id tidak ditemukan.");   
    }

    $product = new Product(
        $row['id'],
        $row['title'],
        $row['category'],
        $row['asset_type'],
        $row['license'],
        $row['price'],
        $row['image'],
        $row['description'],
        isset($row['username']) ? $row['username'] : 'Tidak Diketahui' ,// Menghindari error Undefined Index
                $row['user_id']
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


