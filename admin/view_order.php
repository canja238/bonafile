<?php
require_once '../config.php';
require_once 'header.php';

if (!isset($_GET['id'])) {
    header("Location: orders.php");
    exit();
}

$order_id = $_GET['id'];

// Get order details
$stmt = $pdo->prepare("
    SELECT o.*, u.username, u.email 
    FROM orders o
    JOIN users u ON o.user_id = u.id
    WHERE o.id = ?
");
$stmt->execute([$order_id]);
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

<h2>Order #<?php echo $order['id']; ?></h2>

<div class="order-details">
    <div class="order-info">
        <h3>Order Information</h3>
        <p><strong>Customer:</strong> <?php echo htmlspecialchars($order['username']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
        <p><strong>Order Date:</strong> <?php echo date('F j, Y, g:i a', strtotime($order['created_at'])); ?></p>
        <p><strong>Status:</strong> 
            <span class="status-badge <?php echo strtolower($order['status']); ?>">
                <?php echo ucfirst($order['status']); ?>
            </span>
        </p>
    </div>
    
    <div class="order-summary">
        <h3>Order Summary</h3>
        <p><strong>Subtotal:</strong> $<?php echo number_format($order['total_amount'], 2); ?></p>
        <p><strong>Total:</strong> $<?php echo number_format($order['total_amount'], 2); ?></p>
    </div>
</div>

<h3>Order Items</h3>
<table class="admin-table">
    <thead>
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $item): ?>
        <tr>
            <td>
                <?php if ($item['product_image']): ?>
                <img src="../<?php echo htmlspecialchars($item['product_image']); ?>" width="50" style="vertical-align: middle; margin-right: 10px;">
                <?php endif; ?>
                <?php echo htmlspecialchars($item['product_name']); ?>
            </td>
            <td>$<?php echo number_format($item['price'], 2); ?></td>
            <td><?php echo $item['quantity']; ?></td>
            <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<a href="orders.php" class="admin-button">Back to Orders</a>

<style>
.order-details {
    display: grid;
    grid-template-columns: 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

@media (min-width: 768px) {
    .order-details {
        grid-template-columns: 1fr 1fr;
    }
}

.order-info, .order-summary {
    background: white;
    padding: 1.5rem;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.order-info h3, .order-summary h3 {
    color: #6b21a8;
    margin-top: 0;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #e5e7eb;
}

.order-info p, .order-summary p {
    margin: 0.5rem 0;
}
</style>

<?php include 'footer.php'; ?>