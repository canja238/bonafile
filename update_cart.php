<?php
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if required data is provided
if (!isset($_POST['cart_id']) || !isset($_POST['quantity'])) {
    header("Location: cart.php");
    exit();
}

$cart_id = $_POST['cart_id'];
$quantity = (int)$_POST['quantity'];
$user_id = $_SESSION['user_id'];

// Validate quantity
if ($quantity < 1) {
    $_SESSION['error'] = "Quantity must be at least 1";
    header("Location: cart.php");
    exit();
}

// Check if cart item belongs to user
$stmt = $pdo->prepare("SELECT * FROM cart WHERE id = ? AND user_id = ?");
$stmt->execute([$cart_id, $user_id]);
$cart_item = $stmt->fetch();

if (!$cart_item) {
    $_SESSION['error'] = "Cart item not found";
    header("Location: cart.php");
    exit();
}

// Update quantity
$stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
$stmt->execute([$quantity, $cart_id]);

$_SESSION['success'] = "Cart updated";
header("Location: cart.php");
exit();
?>