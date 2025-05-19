<?php
require_once 'config.php';
require_once 'header.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get cart items with product details
$stmt = $pdo->prepare("
    SELECT c.*, p.product_name, p.price, p.product_image 
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_id = ?
");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll();

// Calculate total
$total = 0;
foreach ($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<div class="cart-container">
    <h1>Your Shopping Cart</h1>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    
    <?php if (empty($cart_items)): ?>
        <div class="empty-cart">
            <p>Your cart is empty</p>
            <a href="shop.php" class="button">Continue Shopping</a>
        </div>
    <?php else: ?>
        <div class="cart-items">
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                    <tr>
                        <td class="product-info">
                            <?php if ($item['product_image']): ?>
                                <img src="uploads/products/<?php echo htmlspecialchars(basename($item['product_image'])); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>" width="80">
                            <?php endif; ?>
                            <span><?php echo htmlspecialchars($item['product_name']); ?></span>
                        </td>
                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                        <td>
                            <form action="update_cart.php" method="POST" class="quantity-form">
                                <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                                <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" class="quantity-input">
                                <button type="submit" class="update-btn">Update</button>
                            </form>
                        </td>
                        <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                        <td>
                            <a href="remove_from_cart.php?id=<?php echo $item['id']; ?>" class="remove-btn">Remove</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="cart-summary">
            <div class="summary-card">
                <h3>Order Summary</h3>
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span>$<?php echo number_format($total, 2); ?></span>
                </div>
                <div class="summary-row">
                    <span>Shipping</span>
                    <span>Free</span>
                </div>
                <div class="summary-row total">
                    <span>Total</span>
                    <span>$<?php echo number_format($total, 2); ?></span>
                </div>
                <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
.cart-container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 1rem;
}

.alert {
    padding: 1rem;
    margin-bottom: 1rem;
    border-radius: 0.5rem;
}

.alert.success {
    background-color: #d1fae5;
    color: #065f46;
}

.empty-cart {
    text-align: center;
    padding: 2rem;
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.empty-cart p {
    font-size: 1.2rem;
    margin-bottom: 1rem;
}

.button {
    display: inline-block;
    padding: 0.5rem 1rem;
    background-color: #6b21a8;
    color: white;
    text-decoration: none;
    border-radius: 0.5rem;
    transition: background-color 0.3s;
}

.button:hover {
    background-color: #5b21b6;
}

.cart-items table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    border-radius: 0.5rem;
    overflow: hidden;
}

.cart-items th, .cart-items td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #e5e7eb;
}

.cart-items th {
    background-color: #6b21a8;
    color: white;
}

.product-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.product-info img {
    border-radius: 0.25rem;
}

.quantity-form {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.quantity-input {
    width: 60px;
    padding: 0.25rem;
    border: 1px solid #d1d5db;
    border-radius: 0.25rem;
}

.update-btn {
    padding: 0.25rem 0.5rem;
    background-color: #e5e7eb;
    border: none;
    border-radius: 0.25rem;
    cursor: pointer;
    transition: background-color 0.3s;
}

.update-btn:hover {
    background-color: #d1d5db;
}

.remove-btn {
    color: #ef4444;
    text-decoration: none;
    transition: color 0.3s;
}

.remove-btn:hover {
    color: #dc2626;
}

.cart-summary {
    margin-top: 2rem;
    display: flex;
    justify-content: flex-end;
}

.summary-card {
    width: 300px;
    background: white;
    padding: 1.5rem;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.summary-card h3 {
    margin-top: 0;
    margin-bottom: 1.5rem;
    color: #6b21a8;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.75rem;
}

.summary-row.total {
    font-weight: bold;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #e5e7eb;
}

.checkout-btn {
    display: block;
    width: 100%;
    padding: 0.75rem;
    margin-top: 1.5rem;
    background-color: #6b21a8;
    color: white;
    text-align: center;
    text-decoration: none;
    border-radius: 0.5rem;
    transition: background-color 0.3s;
}

.checkout-btn:hover {
    background-color: #5b21b6;
}
</style>

<?php include 'footer.php'; ?>