<?php
require_once 'config.php';
require_once 'header.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if order ID is provided
if (!isset($_GET['id'])) {
    header("Location: orders.php");
    exit();
}

$order_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Get order details
$stmt = $pdo->prepare("
    SELECT o.* 
    FROM orders o
    WHERE o.id = ? AND o.user_id = ?
");
$stmt->execute([$order_id, $user_id]);
$order = $stmt->fetch();

if (!$order) {
    header("Location: orders.php");
    exit();
}

// Get order items
$stmt = $pdo->prepare("
    SELECT oi.*, p.product_name, p.product_image 
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?
");
$stmt->execute([$order_id]);
$items = $stmt->fetchAll();
?>

<div class="order-details-container">
    <h1>Order #<?php echo $order['id']; ?></h1>
    
    <div class="order-status">
        <span class="status-badge <?php echo strtolower($order['status']); ?>">
            <?php echo ucfirst($order['status']); ?>
        </span>
        <p>Order placed on <?php echo date('F j, Y', strtotime($order['created_at'])); ?></p>
    </div>
    
    <div class="order-summary">
        <h2>Order Summary</h2>
        <div class="summary-row">
            <span>Subtotal</span>
            <span>$<?php echo number_format($order['total_amount'], 2); ?></span>
        </div>
        <div class="summary-row">
            <span>Shipping</span>
            <span>Free</span>
        </div>
        <div class="summary-row total">
            <span>Total</span>
            <span>$<?php echo number_format($order['total_amount'], 2); ?></span>
        </div>
    </div>
    
    <div class="order-items">
        <h2>Order Items</h2>
        <?php foreach ($items as $item): ?>
        <div class="order-item">
            <div class="item-image">
                <?php if ($item['product_image']): ?>
                    <img src="uploads/products/<?php echo htmlspecialchars(basename($item['product_image'])); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>" width="80">
                <?php endif; ?>
            </div>
            <div class="item-info">
                <h3><?php echo htmlspecialchars($item['product_name']); ?></h3>
                <p>Quantity: <?php echo $item['quantity']; ?></p>
                <p>Price: $<?php echo number_format($item['price'], 2); ?></p>
            </div>
            <div class="item-total">
                $<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <a href="orders.php" class="back-btn">Back to Orders</a>
</div>

<style>
.order-details-container {
    max-width: 800px;
    margin: 2rem auto;
    padding: 1rem;
}

.order-status {
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e5e7eb;
}

.order-status p {
    margin: 0.5rem 0 0;
    color: #6b7280;
}

.status-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 9999px;
    font-weight: 500;
}

.status-badge.pending {
    background-color: #fef3c7;
    color: #92400e;
}

.status-badge.processing {
    background-color: #dbeafe;
    color: #1e40af;
}

.status-badge.shipped {
    background-color: #d1fae5;
    color: #065f46;
}

.status-badge.delivered {
    background-color: #dcfce7;
    color: #166534;
}

.order-summary {
    background: white;
    padding: 1.5rem;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
}

.order-summary h2 {
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

.order-items h2 {
    color: #6b21a8;
    margin-bottom: 1.5rem;
}

.order-item {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding: 1.5rem;
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    margin-bottom: 1rem;
}

.item-image img {
    border-radius: 0.25rem;
}

.item-info {
    flex: 1;
}

.item-info h3 {
    margin: 0;
    font-size: 1.125rem;
}

.item-info p {
    margin: 0.5rem 0 0;
    color: #6b7280;
}

.item-total {
    font-weight: bold;
    font-size: 1.125rem;
}

.back-btn {
    display: inline-block;
    margin-top: 2rem;
    padding: 0.75rem 1.5rem;
    background-color: #6b21a8;
    color: white;
    text-decoration: none;
    border-radius: 0.5rem;
    transition: background-color 0.3s;
}

.back-btn:hover {
    background-color: #5b21b6;
}
</style>

<?php include 'footer.php'; ?>