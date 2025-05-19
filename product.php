<?php
require_once 'config.php';
require_once 'header.php';

// Check if product ID is provided
if (!isset($_GET['id'])) {
    header("Location: shop.php");
    exit();
}

$product_id = $_GET['id'];

// Get product details
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    header("Location: shop.php");
    exit();
}

// Get related products (same category)
$related_products = [];
if (!empty($product['category'])) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE category = ? AND id != ? LIMIT 4");
    $stmt->execute([$product['category'], $product_id]);
    $related_products = $stmt->fetchAll();
}
?>

<div class="product-container">
    <div class="product-gallery">
        <?php if ($product['product_image']): ?>
            <div class="main-image">
                <img src="uploads/products/<?php echo htmlspecialchars(basename($product['product_image'])); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
            </div>
        <?php else: ?>
            <div class="main-image no-image">
                <span>No Image Available</span>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="product-details">
        <?php if ($product['is_featured']): ?>
            <div class="featured-badge">Featured</div>
        <?php endif; ?>
        
        <h1><?php echo htmlspecialchars($product['product_name']); ?></h1>
        
        <?php if (!empty($product['category'])): ?>
            <p class="product-category">Category: <?php echo htmlspecialchars($product['category']); ?></p>
        <?php endif; ?>
        
        <div class="product-price">
            $<?php echo number_format($product['price'], 2); ?>
        </div>
        
        <?php if ($product['stock_quantity'] > 0): ?>
            <p class="in-stock">In Stock (<?php echo $product['stock_quantity']; ?> available)</p>
        <?php else: ?>
            <p class="out-of-stock">Out of Stock</p>
        <?php endif; ?>
        
        <form action="add_to_cart.php" method="POST" class="add-to-cart-form">
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
            
            <div class="quantity-selector">
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo $product['stock_quantity']; ?>">
            </div>
            
            <button type="submit" class="add-to-cart-btn" <?php echo $product['stock_quantity'] <= 0 ? 'disabled' : ''; ?>>
                <i class="fas fa-shopping-cart"></i> Add to Cart
            </button>
        </form>
        
        <?php if (!empty($product['product_description'])): ?>
            <div class="product-description">
                <h3>Description</h3>
                <p><?php echo nl2br(htmlspecialchars($product['product_description'])); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if (!empty($related_products)): ?>
<div class="related-products">
    <h2>You May Also Like</h2>
    
    <div class="products-grid">
        <?php foreach ($related_products as $related): ?>
            <div class="product-card">
                <div class="product-image">
                    <a href="product.php?id=<?php echo $related['id']; ?>">
                        <?php if ($related['product_image']): ?>
                            <img src="uploads/products/<?php echo htmlspecialchars(basename($related['product_image'])); ?>" alt="<?php echo htmlspecialchars($related['product_name']); ?>">
                        <?php else: ?>
                            <div class="no-image">No Image</div>
                        <?php endif; ?>
                    </a>
                </div>
                
                <div class="product-info">
                    <h3>
                        <a href="product.php?id=<?php echo $related['id']; ?>">
                            <?php echo htmlspecialchars($related['product_name']); ?>
                        </a>
                    </h3>
                    
                    <p class="product-price">$<?php echo number_format($related['price'], 2); ?></p>
                    
                    <form action="add_to_cart.php" method="POST" class="add-to-cart-form">
                        <input type="hidden" name="product_id" value="<?php echo $related['id']; ?>">
                        <button type="submit" class="add-to-cart-btn">
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<style>
.product-container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 1rem;
    display: grid;
    grid-template-columns: 1fr;
    gap: 2rem;
}

@media (min-width: 992px) {
    .product-container {
        grid-template-columns: 1fr 1fr;
    }
}

.product-gallery {
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    overflow: hidden;
}

.main-image {
    height: 400px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f3f4f6;
}

.main-image img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.main-image.no-image {
    color: #6b7280;
    font-size: 1rem;
}

.product-details {
    background: white;
    padding: 1.5rem;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    position: relative;
}

.featured-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background-color: #f59e0b;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    font-weight: 500;
}

.product-details h1 {
    margin: 0 0 0.5rem;
    color: #111827;
    font-size: 1.75rem;
}

.product-category {
    margin: 0 0 1rem;
    color: #6b7280;
}

.product-price {
    font-size: 1.5rem;
    font-weight: bold;
    color: #6b21a8;
    margin: 1rem 0;
}

.in-stock {
    color: #065f46;
    margin: 0.5rem 0 1.5rem;
}

.out-of-stock {
    color: #b91c1c;
    margin: 0.5rem 0 1.5rem;
}

.add-to-cart-form {
    margin: 1.5rem 0;
}

.quantity-selector {
    margin-bottom: 1rem;
}

.quantity-selector label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
}

.quantity-selector input {
    padding: 0.5rem;
    border: 1px solid #d1d5db;
    border-radius: 0.25rem;
    width: 80px;
}

.add-to-cart-btn {
    width: 100%;
    padding: 0.75rem;
    background-color: #6b21a8;
    color: white;
    border: none;
    border-radius: 0.25rem;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.add-to-cart-btn:hover {
    background-color: #5b21b6;
}

.add-to-cart-btn:disabled {
    background-color: #9ca3af;
    cursor: not-allowed;
}

.product-description {
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid #e5e7eb;
}

.product-description h3 {
    margin: 0 0 1rem;
    font-size: 1.25rem;
}

.product-description p {
    line-height: 1.6;
    color: #4b5563;
}

.related-products {
    max-width: 1200px;
    margin: 3rem auto;
    padding: 0 1rem;
}

.related-products h2 {
    color: #6b21a8;
    margin-bottom: 1.5rem;
    font-size: 1.5rem;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 1.5rem;
}

@media (max-width: 768px) {
    .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    }
}

@media (max-width: 480px) {
    .products-grid {
        grid-template-columns: 1fr 1fr;
    }
}
</style>

<?php include 'footer.php'; ?>