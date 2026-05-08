<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard - BazaarHub</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/customer.css">
</head>
<body class="customer-page">
<main class="shop-shell">
    <nav class="shop-nav">
        <a class="shop-brand" href="dashboard.php"><strong>BazaarHub</strong><span>Seller studio</span></a>
        <div class="shop-links">
            <a class="shop-link" href="products.php">Products</a>
            <a class="shop-link" href="../logout.php">Logout</a>
        </div>
    </nav>
    <section class="shop-hero">
        <div class="shop-hero__copy">
            <p class="shop-kicker">Seller dashboard</p>
            <h1 class="shop-title">Welcome, <?= htmlspecialchars($_SESSION['name']); ?>.</h1>
            <p class="shop-copy">Add clothing products with images, manage stock, update prices, and keep your catalog ready for customers.</p>
        </div>
        <div class="shop-hero__media">
            <img src="../assets/images/products/blue_denim_mom_jeans.jpg" alt="Seller catalog">
        </div>
    </section>
    <section class="dashboard-grid">
        <a class="dashboard-card" href="products.php"><span>01</span><h2>Manage Products</h2><p>Add, update, delete, price, describe, and upload product images.</p></a>
        <a class="dashboard-card" href="../customer/products.php"><span>02</span><h2>View Storefront</h2><p>Preview the customer catalog with seller products inside it.</p></a>
    </section>
</main>
</body>
</html>
