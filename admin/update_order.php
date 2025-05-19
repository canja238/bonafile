<?php
require_once '../config.php';

// Check for order ID before any output
if (!isset($_GET['id'])) {
    header("Location: orders.php");
    exit();
}

$order_id = $_GET['id'];

// Handle POST before any output
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'] ?? '';
    
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$status, $order_id]);
    
    header("Location: view_order.php?id=$order_id");
    exit();
}

// Get order details
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

// Check order exists before output
if (!$order) {
    header("Location: orders.php");
    exit();
}

// Now include header.php after all potential redirects
require_once 'header.php';
?>

<!-- Rest of your HTML/PHP code -->

<h2>Update Order #<?php echo $order['id']; ?></h2>

<form method="POST" class="admin-form">
    <div class="form-group">
        <label for="status">Order Status</label>
        <select id="status" name="status" required>
            <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
            <option value="processing" <?php echo $order['status'] == 'processing' ? 'selected' : ''; ?>>Processing</option>
            <option value="shipped" <?php echo $order['status'] == 'shipped' ? 'selected' : ''; ?>>Shipped</option>
            <option value="delivered" <?php echo $order['status'] == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
            <option value="cancelled" <?php echo $order['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
        </select>
    </div>
    
    <button type="submit" class="admin-button">Update Order</button>
    <a href="view_order.php?id=<?php echo $order['id']; ?>" class="admin-button secondary">Cancel</a>
</form>

<style>
.admin-form {
    max-width: 800px;
    margin-top: 1.5rem;
    background: white;
    padding: 2rem;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid var(--gray-200);
}

.form-group {
    margin-bottom: 1.25rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: var(--gray-700);
}

.form-group input[type="text"],
.form-group input[type="number"],
.form-group input[type="email"],
.form-group input[type="password"],
.form-group input[type="file"],
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid var(--gray-300);
    border-radius: 0.5rem;
    font-family: inherit;
    transition: all 0.2s;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
}

.form-group textarea {
    min-height: 120px;
    resize: vertical;
}

.checkbox {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.checkbox input {
    width: auto;
}

.error-message {
    background-color: #fee2e2;
    color: #b91c1c;
    padding: 1rem;
    border-radius: 0.5rem;
    margin-bottom: 1.5rem;
    border-left: 4px solid #ef4444;
}

.success-message {
    background-color: #dcfce7;
    color: #166534;
    padding: 1rem;
    border-radius: 0.5rem;
    margin-bottom: 1.5rem;
    border-left: 4px solid #10b981;
}

.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 1.5rem;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.file-upload {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.file-upload-preview {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border-radius: 0.5rem;
    border: 1px dashed var(--gray-300);
    display: none;
}

.file-upload-preview.visible {
    display: block;
}
</style>
<?php include 'footer.php'; ?>