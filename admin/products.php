<?php
require_once '../config.php';
require_once 'header.php';

// Get all products
$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Product Management</h2>
<a href="add_product.php" class="admin-button">Add New Product</a>

<table class="admin-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Name</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Featured</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $product): ?>
        <tr>
            <td><?php echo $product['id']; ?></td>
            <td>
                <?php if ($product['product_image']): ?>
                <img src="../<?php echo htmlspecialchars($product['product_image']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" width="50">
                <?php endif; ?>
            </td>
            <td><?php echo htmlspecialchars($product['product_name']); ?></td>
            <td>$<?php echo number_format($product['price'], 2); ?></td>
            <td><?php echo $product['stock_quantity']; ?></td>
            <td><?php echo $product['is_featured'] ? 'Yes' : 'No'; ?></td>
            <td>
                <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="admin-button small">Edit</a>
                <a href="delete_product.php?id=<?php echo $product['id']; ?>" class="admin-button small delete-btn">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<style>
.admin-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin-top: 1.5rem;
    background: white;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border-radius: 0.75rem;
    overflow: hidden;
    border: 1px solid var(--gray-200);
}

.admin-table th {
    background-color: var(--primary);
    color: white;
    font-weight: 500;
    padding: 1rem 1.25rem;
    text-align: left;
}

.admin-table td {
    padding: 0.75rem 1.25rem;
    border-bottom: 1px solid var(--gray-200);
    vertical-align: middle;
}

.admin-table tr:last-child td {
    border-bottom: none;
}

.admin-table tr:hover {
    background-color: var(--gray-100);
}

.admin-button.small {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    margin-right: 0.5rem;
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: capitalize;
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

.status-badge.cancelled {
    background-color: #fee2e2;
    color: #991b1b;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.table-header h2 {
    margin: 0;
    color: var(--primary);
}

.admin-button.secondary {
    background-color: var(--gray-200);
    color: var(--gray-700);
}

.admin-button.secondary:hover {
    background-color: var(--gray-300);
}

img.product-thumb {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 0.25rem;
    border: 1px solid var(--gray-200);
}
</style>

<?php include 'footer.php'; ?>