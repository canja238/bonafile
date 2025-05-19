<?php
require_once 'config.php';
require_once 'header.php';

// Get all categories for filter
$categories = $pdo->query("SELECT DISTINCT category FROM products WHERE category IS NOT NULL AND category != ''")->fetchAll(PDO::FETCH_COLUMN);

// Handle filters
$category_filter = $_GET['category'] ?? '';
$search_query = $_GET['search'] ?? '';

// Build query
$sql = "SELECT * FROM products WHERE 1=1";
$params = [];

if (!empty($category_filter)) {
    $sql .= " AND category = ?";
    $params[] = $category_filter;
}

if (!empty($search_query)) {
    $sql .= " AND (product_name LIKE ? OR product_description LIKE ?)";
    $params[] = "%$search_query%";
    $params[] = "%$search_query%";
}

// Add sorting
$sort = $_GET['sort'] ?? 'newest';
switch ($sort) {
    case 'price_asc':
        $sql .= " ORDER BY price ASC";
        break;
    case 'price_desc':
        $sql .= " ORDER BY price DESC";
        break;
    case 'name':
        $sql .= " ORDER BY product_name ASC";
        break;
    case 'featured':
        $sql .= " ORDER BY is_featured DESC, product_name ASC";
        break;
    default: // newest
        $sql .= " ORDER BY created_at DESC";
        break;
}

// Get products
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="shop-container">
    <div class="shop-header">
        <h1>Our Products</h1>
        
        <div class="shop-controls">
            <form method="GET" class="search-form">
                <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
            
            <div class="filter-sort">
                <div class="filter">
                    <label for="category">Filter:</label>
                    <select id="category" name="category" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo htmlspecialchars($category); ?>" <?php echo $category_filter == $category ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="sort">
                    <label for="sort">Sort by:</label>
                    <select id="sort" name="sort" onchange="this.form.submit()">
                        <option value="newest" <?php echo $sort == 'newest' ? 'selected' : ''; ?>>Newest</option>
                        <option value="price_asc" <?php echo $sort == 'price_asc' ? 'selected' : ''; ?>>Price: Low to High</option>
                        <option value="price_desc" <?php echo $sort == 'price_desc' ? 'selected' : ''; ?>>Price: High to Low</option>
                        <option value="name" <?php echo $sort == 'name' ? 'selected' : ''; ?>>Name</option>
                        <option value="featured" <?php echo $sort == 'featured' ? 'selected' : ''; ?>>Featured</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    
    <?php if (empty($products)): ?>
        <div class="no-products">
            <p>No products found matching your criteria.</p>
            <a href="shop.php" class="button">Reset Filters</a>
        </div>
    <?php else: ?>
        <div class="products-grid">
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <?php if ($product['is_featured']): ?>
                        <div class="featured-badge">Featured</div>
                    <?php endif; ?>
                    
                    <div class="product-image">
                        <a href="product.php?id=<?php echo $product['id']; ?>">
                            <?php if ($product['product_image']): ?>
                                <img src="uploads/products/<?php echo htmlspecialchars(basename($product['product_image'])); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                            <?php else: ?>
                                <div class="no-image">No Image</div>
                            <?php endif; ?>
                        </a>
                    </div>
                    
                    <div class="product-info">
                        <h3>
                            <a href="product.php?id=<?php echo $product['id']; ?>">
                                <?php echo htmlspecialchars($product['product_name']); ?>
                            </a>
                        </h3>
                        
                        <?php if (!empty($product['category'])): ?>
                            <p class="product-category"><?php echo htmlspecialchars($product['category']); ?></p>
                        <?php endif; ?>
                        
                        <p class="product-price">$<?php echo number_format($product['price'], 2); ?></p>
                        
                        <form action="add_to_cart.php" method="POST" class="add-to-cart-form">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <button type="submit" class="add-to-cart-btn">
                                <i class="fas fa-shopping-cart"></i> Add to Cart
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.shop-container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 1rem;
}

.shop-header {
    margin-bottom: 2rem;
}

.shop-header h1 {
    color: #6b21a8;
    margin-bottom: 1.5rem;
}

.shop-controls {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.search-form {
    display: flex;
    max-width: 400px;
}

.search-form input {
    flex: 1;
    padding: 0.75rem 1rem;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem 0 0 0.5rem;
    font-size: 1rem;
}

.search-form button {
    padding: 0 1rem;
    background-color: #6b21a8;
    color: white;
    border: none;
    border-radius: 0 0.5rem 0.5rem 0;
    cursor: pointer;
}

.filter-sort {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
}

.filter, .sort {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.filter label, .sort label {
    font-weight: 500;
}

.filter select, .sort select {
    padding: 0.5rem;
    border: 1px solid #d1d5db;
    border-radius: 0.25rem;
    background-color: white;
}

.no-products {
    text-align: center;
    padding: 3rem;
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.no-products p {
    font-size: 1.125rem;
    margin-bottom: 1.5rem;
}

.button {
    display: inline-block;
    padding: 0.75rem 1.5rem;
    background-color: #6b21a8;
    color: white;
    text-decoration: none;
    border-radius: 0.5rem;
    transition: background-color 0.3s;
}

.button:hover {
    background-color: #5b21b6;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 1.5rem;
}

.product-card {
    background: white;
    border-radius: 0.5rem;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
    position: relative;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 15px rgba(0,0,0,0.1);
}

.featured-badge {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    background-color: #f59e0b;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    font-weight: 500;
    z-index: 1;
}

.product-image {
    height: 200px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f3f4f6;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.product-card:hover .product-image img {
    transform: scale(1.05);
}

.no-image {
    color: #6b7280;
    font-size: 0.875rem;
}

.product-info {
    padding: 1.25rem;
}

.product-info h3 {
    margin: 0 0 0.5rem;
    font-size: 1.125rem;
}

.product-info h3 a {
    color: #111827;
    text-decoration: none;
}

.product-info h3 a:hover {
    color: #6b21a8;
}

.product-category {
    margin: 0 0 0.5rem;
    color: #6b7280;
    font-size: 0.875rem;
}

.product-price {
    margin: 0 0 1rem;
    font-weight: bold;
    font-size: 1.125rem;
    color: #6b21a8;
}

.add-to-cart-form {
    margin-top: 1rem;
}

.add-to-cart-btn {
    width: 100%;
    padding: 0.5rem;
    background-color: #6b21a8;
    color: white;
    border: none;
    border-radius: 0.25rem;
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

@media (max-width: 768px) {
    .filter-sort {
        flex-direction: column;
        gap: 1rem;
    }
    
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

<script>
// Submit form when filters change
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.querySelector('.search-form');
    const filterForm = document.querySelector('.filter-sort').parentElement;
    
    // Create a form element for the filter/sort controls
    const form = document.createElement('form');
    form.method = 'GET';
    form.style.display = 'none';
    
    // Add existing query parameters
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.forEach((value, key) => {
        if (key !== 'category' && key !== 'sort') {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = value;
            form.appendChild(input);
        }
    });
    
    // Move filter/sort controls into the form
    while (filterForm.firstChild) {
        form.appendChild(filterForm.firstChild);
    }
    
    // Insert the form into the DOM
    filterForm.appendChild(form);
});
</script>

<?php include 'footer.php'; ?>