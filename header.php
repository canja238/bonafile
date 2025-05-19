<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <title>ShoeStore - Premium Shoes</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet"/>
    <link href="styles.css" rel="stylesheet"/>
</head>
<body>
<header class="site-header">
    <div class="header-container">
        <a href="index.php" class="site-title">Shoepply V2.0</a>
        
        <nav class="nav-links">
            <a href="index.php">Home</a>
            <a href="shop.php">Shop</a>
            <?php if (isset($_SESSION['user_id'])) : ?>
                <a href="orders.php">My Orders</a>
                <?php if (isAdmin()) : ?>
                    <a href="admin/reports.php">Admin</a>
                <?php endif; ?>
            <?php endif; ?>
            <a href="contact.php">Contact</a>
        </nav>
        
        <div class="icon-links">
            <button aria-label="Search"><i class="fas fa-search"></i></button>
            
            <?php if (isset($_SESSION['user_id'])) : ?>
                <a href="profile.php" aria-label="User Account"><i class="fas fa-user"></i></a>
                <a href="cart.php" aria-label="Shopping Cart" class="cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                    <?php $cart_count = getCartCount($_SESSION['user_id']); ?>
                    <?php if ($cart_count > 0) : ?>
                        <span class="cart-count"><?php echo $cart_count; ?></span>
                    <?php endif; ?>
                </a>
                <a href="logout.php">Logout</a>
            <?php else : ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php endif; ?>
        </div>
        
        <button aria-label="Open menu" class="mobile-menu-button" id="mobile-menu-button">
            <i class="fas fa-bars"></i>
        </button>
    </div>
    
    <!-- Mobile menu -->
    <nav aria-label="Mobile menu" class="mobile-menu" id="mobile-menu">
        <a href="index.php">Home</a>
        <a href="shop.php">Shop</a>
        <?php if (isset($_SESSION['user_id'])) : ?>
            <a href="orders.php">My Orders</a>
            <?php if (isAdmin()) : ?>
                <a href="admin/">Admin</a>
            <?php endif; ?>
        <?php endif; ?>
        <a href="contact.php">Contact</a>
    </nav>
</header>

<style>
    body {
        font-family: 'Montserrat', sans-serif;
        margin: 0;
    }

    .site-header {
        background-color: #ffffff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        position: sticky;
        top: 0;
        z-index: 50;
    }

    .header-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
    }

    .site-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #6b21a8; /* Tailwind's purple-700 */
        text-decoration: none;
        letter-spacing: 0.05em;
    }

    .nav-links {
        display: none;
    }

    .nav-links a {
        margin-left: 2rem;
        font-weight: 600;
        color: #374151; /* gray-700 */
        text-decoration: none;
        transition: color 0.3s ease;
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
        color: #4b5563; /* gray-600 */
        background: none;
        border: none;
        cursor: pointer;
        transition: color 0.3s ease;
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
        background-color: #dc2626; /* red-600 */
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
        background: none;
        border: none;
        font-size: 1.5rem;
        color: #4b5563;
        cursor: pointer;
    }

    .mobile-menu {
        display: none;
        background-color: #ffffff;
        border-top: 1px solid #e5e7eb;
        flex-direction: column;
    }

    .mobile-menu a {
        padding: 0.75rem 1.5rem;
        border-bottom: 1px solid #e5e7eb;
        font-weight: 600;
        color: #374151;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .mobile-menu a:hover {
        background-color: #f5f3ff;
        color: #6b21a8;
    }

    @media (min-width: 768px) {
        .nav-links {
            display: flex;
        }
        .mobile-menu-button {
            display: none;
        }
    }

    .mobile-menu.show {
        display: flex;
    }
</style>

<script>
    // Mobile menu toggle
    document.getElementById('mobile-menu-button').addEventListener('click', function () {
        document.getElementById('mobile-menu').classList.toggle('show');
    });
</script>