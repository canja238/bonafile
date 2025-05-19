<?php
require_once 'config.php';
require_once 'header.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get user's orders
$stmt = $pdo->prepare("
    SELECT o.id, o.total_amount, o.status, o.created_at 
    FROM orders o
    WHERE o.user_id = ?
    ORDER BY o.created_at DESC
");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();
?>

<div class="orders-container">
    <h1>Your Orders</h1>
    
    <?php if (empty($orders)): ?>
        <div class="empty-orders">
            <p>You haven't placed any orders yet.</p>
            <a href="index.php" class="button">Start Shopping</a>
        </div>
    <?php else: ?>
        <table class="orders-table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td>#<?php echo $order['id']; ?></td>
                    <td><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
                    <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                    <td>
                        <span class="status-badge <?php echo strtolower($order['status']); ?>">
                            <?php echo ucfirst($order['status']); ?>
                        </span>
                    </td>
                    <td>
                        <a href="view_order.php?id=<?php echo $order['id']; ?>" class="view-btn">View</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<style>
.orders-container {
    max-width: 1000px;
    margin: 2rem auto;
    padding: 1rem;
}

.empty-orders {
    text-align: center;
    padding: 2rem;
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.empty-orders p {
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

.orders-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    border-radius: 0.5rem;
    overflow: hidden;
}

.orders-table th, .orders-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #e5e7eb;
}

.orders-table th {
    background-color: #6b21a8;
    color: white;
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

.view-btn {
    color: #6b21a8;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s;
}

.view-btn:hover {
    color: #5b21b6;
    text-decoration: underline;
}
</style>

<?php include 'footer.php'; ?>