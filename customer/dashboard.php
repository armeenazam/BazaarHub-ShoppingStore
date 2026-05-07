<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Customer Home</title>
</head>
<body>
<h1>Welcome to BazaarHub</h1>
<p>Hello, <?php echo htmlspecialchars($_SESSION['name']); ?></p>

<hr>

<h3>Shop Features</h3>
<ul>
    <li><a href="products.php">Browse Products</a></li>
    <li><a href="cart.php">Add to Cart / View Cart</a></li>
    <li><a href="cart.php">Place Orders</a></li>
    <li><a href="my_orders.php">Track Orders</a></li>
    <li><a href="reviews.php">Write Reviews</a></li>
</ul>

<br>
<a href="../logout.php">Logout</a>
</body>
</html>
