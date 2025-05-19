<?php
require_once '../config.php';
require_once 'header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['product_name'] ?? '';
    $description = $_POST['product_description'] ?? '';
    $price = $_POST['price'] ?? 0;
    $category = $_POST['category'] ?? '';
    $stock = $_POST['stock_quantity'] ?? 0;
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    
    // Handle file upload
    $image_path = '';
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/products/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        $file_name = uniqid() . '_' . basename($_FILES['product_image']['name']);
        $target_path = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['product_image']['tmp_name'], $target_path)) {
            $image_path = 'uploads/products/' . $file_name;
        }
    }
    
    try {
        $stmt = $pdo->prepare("INSERT INTO products (product_name, product_description, price, category, product_image, stock_quantity, is_featured) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $description, $price, $category, $image_path, $stock, $is_featured]);
        
        $success = 'Product added successfully!';
    } catch (PDOException $e) {
        $error = 'Error adding product: ' . $e->getMessage();
    }
}
?>

<h2>Add New Product</h2>

<?php if ($error): ?>
<div class="error-message"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<?php if ($success): ?>
<div class="success-message"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" class="admin-form">
    <div class="form-group">
        <label for="product_name">Product Name</label>
        <input type="text" id="product_name" name="product_name" required>
    </div>
    
    <div class="form-group">
        <label for="product_description">Description</label>
        <textarea id="product_description" name="product_description" rows="4"></textarea>
    </div>
    
    <div class="form-group">
        <label for="price">Price</label>
        <input type="number" id="price" name="price" step="0.01" min="0" required>
    </div>
    
    <div class="form-group">
        <label for="category">Category</label>
        <input type="text" id="category" name="category">
    </div>
    
    <div class="form-group">
        <label for="stock_quantity">Stock Quantity</label>
        <input type="number" id="stock_quantity" name="stock_quantity" min="0" required>
    </div>
    
    <div class="form-group">
        <label for="product_image">Product Image</label>
        <input type="file" id="product_image" name="product_image">
    </div>
    
    <div class="form-group checkbox">
        <input type="checkbox" id="is_featured" name="is_featured">
        <label for="is_featured">Featured Product</label>
    </div>
    
    <button type="submit" class="admin-button">Add Product</button>
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