<?php
require_once '../config.php';

// Check admin status before any output
if (!isset($_SESSION['user_id']) || !isAdmin()) {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - ShoeStore</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #7c3aed;
            --primary-light: #8b5cf6;
            --primary-dark: #6d28d9;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-700: #374151;
            --gray-900: #111827;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--gray-100);
            color: var(--gray-900);
            line-height: 1.5;
        }
        
        .admin-header {
            background-color: var(--primary);
            color: white;
            padding: 1rem 2rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 50;
        }
        
        .admin-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .admin-nav-links {
            display: flex;
            gap: 1.5rem;
        }
        
        .admin-nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .admin-nav-links a:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }
        
        .admin-nav-links a i {
            font-size: 1rem;
        }
        
        .admin-main {
            display: flex;
            min-height: calc(100vh - 72px);
        }
        
        .admin-sidebar {
            width: 280px;
            background-color: white;
            border-right: 1px solid var(--gray-200);
            padding: 1.5rem 0;
            position: sticky;
            top: 72px;
            height: calc(100vh - 72px);
            overflow-y: auto;
        }
        
        .admin-content {
            flex: 1;
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-menu li {
            margin-bottom: 0.25rem;
        }
        
        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.5rem;
            color: var(--gray-700);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .sidebar-menu a:hover {
            background-color: var(--primary-light);
            color: white;
        }
        
        .sidebar-menu a.active {
            background-color: var(--primary);
            color: white;
            position: relative;
        }
        
        .sidebar-menu a.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background-color: var(--primary-dark);
            border-radius: 0 4px 4px 0;
        }
        
        .sidebar-menu a i {
            width: 20px;
            text-align: center;
        }
        
        .logo {
            font-weight: 700;
            font-size: 1.25rem;
            letter-spacing: -0.5px;
        }
        
        .admin-nav-user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .admin-nav-user img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <header class="admin-header">
        <nav class="admin-nav">
            <div class="logo">Shoepply V2.0</div>
            <div class="admin-nav-user">

                <a href="../index.php" title="Logout"><i class="fas fa-sign-out-alt"></i></a>
            </div>
        </nav>
    </header>
    
    <main class="admin-main">
        <aside class="admin-sidebar">
            <ul class="sidebar-menu">
            <li><a href="reports.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active' : ''; ?>"><i class="fas fa-chart-line"></i> Reports</a></li>
                <li><a href="products.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : ''; ?>"><i class="fas fa-shoe-prints"></i> Products</a></li>
                <li><a href="add_product.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'add_product.php' ? 'active' : ''; ?>"><i class="fas fa-plus-circle"></i> Add Product</a></li>
                <li><a href="users.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'users.php' ? 'active' : ''; ?>"><i class="fas fa-users"></i> Users</a></li>
                <li><a href="orders.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'active' : ''; ?>"><i class="fas fa-shopping-bag"></i> Orders</a></li>


            </ul>
        </aside>
        
        <div class="admin-content">