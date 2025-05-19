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
    header("Location: index.php");
    exit();
}

$order_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Get order details
$stmt = $pdo->prepare("
    SELECT o.*, u.username, u.email 
    FROM orders o
    JOIN users u ON o.user_id = u.id
    WHERE o.id = ? AND o.user_id = ?
");
$stmt->execute([$order_id, $user_id]);
$order = $stmt->fetch();

if (!$order) {
    header("Location: index.php");
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

<div class="confirmation-container">
    <div class="confirmation-card">
        <div class="confirmation-header">
            <h1>Order Confirmation</h1>
            <p>Thank you for your purchase!</p>
        </div>
        
        <div class="confirmation-details">
            <div class="detail-row">
                <span>Order Number</span>
                <span>#<?php echo $order['id']; ?></span>
            </div>
            <div class="detail-row">
                <span>Date</span>
                <span><?php echo date('F j, Y', strtotime($order['created_at'])); ?></span>
            </div>
            <div class="detail-row">
                <span>Total</span>
                <span>$<?php echo number_format($order['total_amount'], 2); ?></span>
            </div>
            <div class="detail-row">
                <span>Status</span>
                <span class="status-badge <?php echo strtolower($order['status']); ?>">
                    <?php echo ucfirst($order['status']); ?>
                </span>
            </div>
        </div>
        
        <div class="order-items">
            <h2>Order Items</h2>
            <?php foreach ($items as $item): ?>
            <div class="order-item">
                <div class="item-image">
                    <?php if ($item['product_image']): ?>
                        <img src="uploads/products/<?php echo htmlspecialchars(basename($item['product_image'])); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>" width="60">
                    <?php endif; ?>
                </div>
                <div class="item-details">
                    <h3><?php echo htmlspecialchars($item['product_name']); ?></h3>
                    <p>Quantity: <?php echo $item['quantity']; ?></p>
                </div>
                <div class="item-price">
                    $<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="confirmation-actions">
            <a href="index.php" class="continue-btn">Continue Shopping</a>
            <a href="orders.php" class="view-orders-btn">View All Orders</a>
        </div>
    </div>
</div>

<style>
.confirmation-container {
    max-width: 800px;
    margin: 2rem auto;
    padding: 1rem;
}

.confirmation-card {
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    overflow: hidden;
}

.confirmation-header {
    background-color: #6b21a8;
    color: white;
    padding: 2rem;
    text-align: center;
}

.confirmation-header h1 {
    margin: 0;
    font-size: 1.75rem;
}

.confirmation-header p {
    margin: 0.5rem 0 0;
    font-size: 1.125rem;
}

.confirmation-details {
    padding: 1.5rem;
    border-bottom: 1px solid #e5e7eb;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.75rem;
}

.detail-row:last-child {
    margin-bottom: 0;
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    border-radius: 9999px;
    font-size: 0.75rem;
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

.order-items {
    padding: 1.5rem;
}

.order-items h2 {
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

.order-item:last-child {
    border-bottom: none;
}

.item-image img {
    border-radius: 0.25rem;
}

.item-details {
    flex: 1;
}

.item-details h3 {
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

.confirmation-actions {
    display: flex;
    justify-content: center;
    gap: 1rem;
    padding: 1.5rem;
    border-top: 1px solid #e5e7eb;
}

.continue-btn, .view-orders-btn {
    display: inline-block;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    text-decoration: none;
    font-weight: 500;
    transition: background-color 0.3s;
}

.continue-btn {
    background-color: #6b21a8;
    color: white;
}

.continue-btn:hover {
    background-color: #5b21b6;
}

.view-orders-btn {
    background-color: #e5e7eb;
    color: #374151;
}

.view-orders-btn:hover {
    background-color: #d1d5db;
}
</style>

<?php include 'footer.php'; ?>