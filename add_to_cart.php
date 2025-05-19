<?php
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if product ID is provided
if (!isset($_POST['product_id'])) {
    header("Location: index.php");
    exit();
}

$product_id = $_POST['product_id'];
$user_id = $_SESSION['user_id'];

// Check if product exists
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    $_SESSION['error'] = "Product not found";
    header("Location: index.php");
    exit();
}

// Check if product is already in cart
$stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
$stmt->execute([$user_id, $product_id]);
$existing_item = $stmt->fetch();

if ($existing_item) {
    // Update quantity
    $new_quantity = $existing_item['quantity'] + 1;
    $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
    $stmt->execute([$new_quantity, $existing_item['id']]);
} else {
    // Add new item to cart
    $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");
    $stmt->execute([$user_id, $product_id]);
}

$_SESSION['success'] = "Product added to cart";
header("Location: cart.php");
exit();
?>