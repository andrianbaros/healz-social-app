<!--display_products.php-->
<?php
require_once '../classes/Database.php'; // Make sure the database connection is here

class ProductModel {
    private $db;

    public function __construct() {
        // Ensure the connection is successful
        $this->db = new Database();

        if ($this->db->getConnection() === null) {
            die("Database connection failed");
        }
    }

    // Fetch all products from the database with optional search query and filters
    public function fetchAllProducts($search_query = '', $category = '', $asset_type = '', $license = '') {
        $query = "SELECT * FROM product_posts WHERE (title LIKE ? OR description LIKE ?)";

        // Add filters if provided
        if ($category) {
            $query .= " AND category = ?";
        }
        if ($asset_type) {
            $query .= " AND asset_type = ?";
        }
        if ($license) {
            $query .= " AND license = ?";
        }

        // Prepare the query
        $stmt = $this->db->getConnection()->prepare($query);

        // Check if prepare failed
        if ($stmt === false) {
            die('Query prepare failed: ' . $this->db->getConnection()->error);
        }

        // Bind parameters for search query and filters
        $params = [];
        $types = "ss";  // Initial types for search query

        $params[] = "%$search_query%";
        $params[] = "%$search_query%";

        if ($category) {
            $params[] = $category;
            $types .= "s";
        }
        if ($asset_type) {
            $params[] = $asset_type;
            $types .= "s";
        }
        if ($license) {
            $params[] = $license;
            $types .= "s";
        }

        // Bind the parameters
        $stmt->bind_param($types, ...$params);

        // Execute the query
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch the products
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }

        return $products;
    }

    // Insert a new product
    public function insertProduct($name, $category, $asset_type, $license, $price, $image) {
        $query = "INSERT INTO products (name, category, asset_type, license, price, image) VALUES (?, ?, ?, ?, ?, ?)";
        
        // Prepare the query
        $stmt = $this->db->getConnection()->prepare($query);

        // Check if prepare failed
        if ($stmt === false) {
            die('Query prepare failed: ' . $this->db->getConnection()->error);
        }

        // Bind the parameters and execute the query
        $stmt->bind_param("ssssds", $name, $category, $asset_type, $license, $price, $image);
        return $stmt->execute();
    }

    // Delete a product
    public function removeProduct($id) {
        $query = "DELETE FROM product_posts WHERE id = ?";
        $stmt = $this->db->getConnection()->prepare($query);

        if ($stmt === false) {
            die('Query prepare failed: ' . $this->db->getConnection()->error);
        }

        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}



?>
