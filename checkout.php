<?php
ob_start(); // Start output buffering at the very beginning
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

// Check if cart is empty
if (empty($cart_items)) {
    $_SESSION['error'] = "Your cart is empty";
    header("Location: cart.php");
    exit();
}

// Calculate total
$total = 0;
foreach ($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create order
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, 'pending')");
    $stmt->execute([$user_id, $total]);
    $order_id = $pdo->lastInsertId();
    
    // Add order items
    foreach ($cart_items as $item) {
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']]);
        
        // Update product stock (optional)
        $stmt = $pdo->prepare("UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?");
        $stmt->execute([$item['quantity'], $item['product_id']]);
    }
    
    // Clear cart
    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->execute([$user_id]);
    
    // Redirect to order confirmation
    header("Location: order_confirmation.php?id=$order_id");
    exit();
}
?>

<div class="checkout-container">
    <h1>Checkout</h1>
    
    <div class="checkout-grid">
        <div class="checkout-form">
            <h2>Shipping Information</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input type="text" id="full_name" name="full_name" required>
                </div>
                
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" required>
                </div>
                
                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="state">State</label>
                        <input type="text" id="state" name="state" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="zip_code">ZIP Code</label>
                        <input type="text" id="zip_code" name="zip_code" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone" required>
                </div>
                
                <h2>Payment Method</h2>
                <div class="payment-method">
                    <label>
                        <input type="radio" name="payment_method" value="credit_card" checked>
                        Credit Card
                    </label>
                    <label>
                        <input type="radio" name="payment_method" value="paypal">
                        PayPal
                    </label>
                    <label>
                        <input type="radio" name="payment_method" value="cash_on_delivery">
                        Cash on Delivery
                    </label>
                </div>
                
                <button type="submit" class="checkout-btn">Place Order</button>
            </form>
        </div>
        
        <div class="order-summary">
            <h2>Order Summary</h2>
            <div class="order-items">
                <?php foreach ($cart_items as $item): ?>
                <div class="order-item">
                    <div class="item-image">
                        <?php if ($item['product_image']): ?>
                            <img src="uploads/products/<?php echo htmlspecialchars(basename($item['product_image'])); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>" width="60">
                        <?php endif; ?>
                    </div>
                    <div class="item-details">
                        <h4><?php echo htmlspecialchars($item['product_name']); ?></h4>
                        <p>Qty: <?php echo $item['quantity']; ?></p>
                    </div>
                    <div class="item-price">
                        $<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="summary-totals">
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
            </div>
        </div>
    </div>
</div>

<style>
.checkout-container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 1rem;
}

.checkout-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 2rem;
}

@media (min-width: 992px) {
    .checkout-grid {
        grid-template-columns: 2fr 1fr;
    }
}

.checkout-form {
    background: white;
    padding: 1.5rem;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.checkout-form h2 {
    color: #6b21a8;
    margin-top: 0;
    margin-bottom: 1.5rem;
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
}

.form-group input {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #d1d5db;
    border-radius: 0.25rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.payment-method {
    margin: 1.5rem 0;
}

.payment-method label {
    display: block;
    margin-bottom: 0.75rem;
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.25rem;
    cursor: pointer;
}

.payment-method label:hover {
    background-color: #f5f3ff;
}

.checkout-btn {
    display: block;
    width: 100%;
    padding: 0.75rem;
    background-color: #6b21a8;
    color: white;
    border: none;
    border-radius: 0.5rem;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s;
}

.checkout-btn:hover {
    background-color: #5b21b6;
}

.order-summary {
    background: white;
    padding: 1.5rem;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.order-summary h2 {
    color: #6b21a8;
    margin-top: 0;
    margin-bottom: 1.5rem;
}

.order-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 0;
    border-bottom: 1px solid #e5e7eb;
}

.item-image img {
    border-radius: 0.25rem;
}

.item-details {
    flex: 1;
}

.item-details h4 {
    margin: 0;
    font-size: 1rem;
}

.item-details p {
    margin: 0.25rem 0 0;
    color: #6b7280;
    font-size: 0.875rem;
}

.item-price {
    font-weight: 500;
}

.summary-totals {
    margin-top: 1.5rem;
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
</style>

<?php include 'footer.php'; ?>