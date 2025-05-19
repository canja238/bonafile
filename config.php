<?php
// Add session_start() at the very beginning
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'shoestore');
define('DB_USER', 'root');
define('DB_PASS', '');

// Create database connection
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Function to check if user is admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// ... rest of your config.php ...

// Function to get cart count
function getCartCount($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT SUM(quantity) as count FROM cart WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $result = $stmt->fetch();
    return $result['count'] ?? 0;
}

// Function to get products
// In config.php, update the getProducts function:
function getProducts() {
    global $pdo;
    $stmt = $pdo->query("SELECT id, product_name as name, price, product_image as image_url FROM products");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}