<?php
session_start();
require_once 'config.php';
require_once 'functions.php';
$products = getProducts();
include 'header.php';
?>
<link rel="stylesheet" href="styles.css">
<style>
    /* === General === */
body {
    font-family: 'Montserrat', sans-serif;
    background-color: #f9fafb;
    color: #111827;
    margin: 0;
    padding: 0;
}

/* === Header === */
.site-header {
    background-color: white;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    position: sticky;
    top: 0;
    z-index: 50;
}

.header-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 1rem 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
}

.site-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #6b21a8;
    text-decoration: none;
    letter-spacing: 0.05em;
}

.nav-links {
    display: none;
}

.nav-links a {
    margin-left: 2rem;
    font-weight: 600;
    color: #374151;
    text-decoration: none;
    transition: color 0.3s;
}

.nav-links a:hover {
    color: #6b21a8;
}

.icon-links {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.icon-links a,
.icon-links button {
    font-size: 1.125rem;
    color: #4b5563;
    background: none;
    border: none;
    cursor: pointer;
    transition: color 0.3s;
}

.icon-links a:hover,
.icon-links button:hover {
    color: #6b21a8;
}

.cart-icon {
    position: relative;
}

.cart-count {
    position: absolute;
    top: -0.5rem;
    right: -0.5rem;
    background-color: #dc2626;
    color: white;
    font-size: 0.75rem;
    width: 1.25rem;
    height: 1.25rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 9999px;
}

.mobile-menu-button {
    display: block;
    font-size: 1.5rem;
    color: #4b5563;
    background: none;
    border: none;
    cursor: pointer;
}

.mobile-menu {
    display: none;
    background-color: #fff;
    border-top: 1px solid #e5e7eb;
    flex-direction: column;
}

.mobile-menu a {
    padding: 0.75rem 1.5rem;
    border-bottom: 1px solid #e5e7eb;
    font-weight: 600;
    color: #374151;
    text-decoration: none;
}

.mobile-menu a:hover {
    background-color: #f5f3ff;
    color: #6b21a8;
}

.mobile-menu.show {
    display: flex;
}

@media (min-width: 768px) {
    .nav-links {
        display: flex;
    }
    .mobile-menu-button {
        display: none;
    }
}

/* === Hero Section === */
.hero-section {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 3rem 1.5rem;
    gap: 2.5rem;
}

.hero-content {
    text-align: center;
    max-width: 600px;
}

.hero-title {
    font-size: 2.25rem;
    font-weight: 800;
    color: #6b21a8;
    line-height: 1.25;
}

.hero-text {
    margin-top: 1.5rem;
    color: #374151;
    font-size: 1.125rem;
}

.hero-button {
    margin-top: 2rem;
    padding: 0.75rem 2rem;
    background-color: #6b21a8;
    color: white;
    font-weight: 600;
    border-radius: 0.5rem;
    text-decoration: none;
    transition: background-color 0.3s;
}

.hero-button:hover {
    background-color: #5b21b6;
}

.hero-image-container {
    max-width: 384px;
}

.hero-image {
    width: 100%;
    height: auto;
    border-radius: 0.5rem;
    object-fit: cover;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

@media (min-width: 768px) {
    .hero-section {
        flex-direction: row;
        align-items: center;
        justify-content: center;
    }

    .hero-content {
        text-align: left;
    }
}

/* === Products Grid === */
.section-title {
    font-size: 1.875rem;
    font-weight: bold;
    color: #6b21a8;
    text-align: center;
    margin: 2rem 0 1rem;
}

.products-grid {
    display: grid;
    gap: 2rem;
    padding: 1rem 1.5rem;
}

@media (min-width: 640px) {
    .products-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 1024px) {
    .products-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (min-width: 1280px) {
    .products-grid {
        grid-template-columns: repeat(4, 1fr);
    }
}

.product-card {
    background-color: white;
    border-radius: 0.5rem;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: box-shadow 0.3s;
}

.product-card:hover {
    box-shadow: 0 20px 25px rgba(0,0,0,0.1);
}

.product-image {
    width: 100%;
    height: 16rem;
    object-fit: cover;
}

.product-info {
    padding: 1rem;
}

.product-name {
    font-weight: 600;
    font-size: 1.125rem;
    color: #111827;
}

.product-price {
    margin-top: 0.5rem;
    color: #6b21a8;
    font-weight: bold;
    font-size: 1.25rem;
}

.add-to-cart {
    margin-top: 1rem;
    width: 100%;
    padding: 0.5rem;
    background-color: #6b21a8;
    color: white;
    font-weight: 600;
    border-radius: 0.375rem;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s;
}

.add-to-cart:hover {
    background-color: #5b21b6;
}

/* === Newsletter === */
.newsletter {
    margin: 5rem 0 0;
    background-color: #6b21a8;
    color: white;
    padding: 2.5rem 1.5rem;
    text-align: center;
}

.newsletter-title {
    font-size: 1.875rem;
    font-weight: bold;
}

.newsletter-text {
    margin-top: 1rem;
    margin-bottom: 1.5rem;
    max-width: 32rem;
    margin-left: auto;
    margin-right: auto;
}

.newsletter-form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    max-width: 28rem;
    margin: 0 auto;
}

.newsletter-input {
    padding: 0.75rem 1rem;
    border-radius: 0.375rem;
    color: #111827;
    font-weight: 600;
    border: none;
}

.newsletter-button {
    background-color: #facc15;
    color: #6b21a8;
    font-weight: bold;
    padding: 0.75rem 1.5rem;
    border-radius: 0.375rem;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s;
}

.newsletter-button:hover {
    background-color: #eab308;
}

@media (min-width: 640px) {
    .newsletter-form {
        flex-direction: row;
    }
}

/* === Footer === */
.footer {
    background-color: white;
    border-top: 1px solid #e5e7eb;
    margin-top: 5rem;
}

.footer-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2.5rem 1.5rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    color: #4b5563;
    font-size: 0.875rem;
}

.social-links {
    display: flex;
    gap: 1.5rem;
    margin-top: 1rem;
}

.social-link {
    color: #4b5563;
    transition: color 0.3s;
    font-size: 1.2rem;
}

.social-link:hover {
    color: #6b21a8;
}

@media (min-width: 768px) {
    .footer-container {
        flex-direction: row;
        justify-content: space-between;
    }

    .social-links {
        margin-top: 0;
    }
}

</style>
<main class="main-container">
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <h1 class="hero-title">Step Up Your Game with Premium Shoes</h1>
            <p class="hero-text">
                Discover the latest in basketball and lifestyle shoes, inspired by legends like Kobe Bryant. Style and comfort in every step.
            </p>
            <a href="shop.php" class="hero-button">Shop Now</a>
        </div>
        <div class="hero-image-container">
            <img src="https://storage.googleapis.com/a1aa/image/5dc89bee-e22b-4c2c-1c70-55ba9cf55db9.jpg"
                 alt="Kobe Bryant" class="hero-image" loading="lazy">
        </div>
    </section>

    <!-- Product Grid -->
    <section id="shop">
        <h2 class="section-title">Featured Shoes</h2>
        <div class="products-grid">
            <?php foreach ($products as $product) : ?>
                <article class="product-card">
                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>"
                         alt="<?php echo htmlspecialchars($product['name']); ?>"
                         class="product-image">
                    <div class="product-info">
                        <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p class="product-price">$<?php echo number_format($product['price'], 2); ?></p>
                        <form action="add_to_cart.php" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <button type="submit" class="add-to-cart">Add to Cart</button>
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Newsletter -->
   
</main>

<?php include 'footer.php'; ?>
