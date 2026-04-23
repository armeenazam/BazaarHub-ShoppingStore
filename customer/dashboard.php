<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
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

<h1>Welcome to BazaarHub 🛒</h1>

<p>Hello, <?php echo $_SESSION['name']; ?> 👋</p>

<hr>

<h3>Shop Features</h3>

<ul>
    <li>Browse Products</li>
    <li>Add to Cart</li>
    <li>Place Orders</li>
    <li>Track Orders</li>
    <li>Write Reviews</li>
</ul>

<br>

<a href="../logout.php">Logout</a>

</body>
</html>