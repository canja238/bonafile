<?php
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if ID is provided
if (!isset($_GET['id'])) {
    header("Location: cart.php");
    exit();
}

$cart_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Check if cart item belongs to user
$stmt = $pdo->prepare("SELECT * FROM cart WHERE id = ? AND user_id = ?");
$stmt->execute([$cart_id, $user_id]);
$cart_item = $stmt->fetch();

if (!$cart_item) {
    $_SESSION['error'] = "Cart item not found";
    header("Location: cart.php");
    exit();
}

// Remove item from cart
$stmt = $pdo->prepare("DELETE FROM cart WHERE id = ?");
$stmt->execute([$cart_id]);

$_SESSION['success'] = "Item removed from cart";
header("Location: cart.php");
exit();
?>