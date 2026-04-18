<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'seller') {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Seller Dashboard</title>
</head>
<body>

<h1>Seller Panel</h1>

<p>Welcome, <?php echo $_SESSION['name']; ?> 👋</p>

<hr>

<h3>Your Options</h3>

<ul>
    <li>Add Product</li>
    <li>Update Product</li>
    <li>Delete Product</li>
    <li>View Orders</li>
    <li>Manage Inventory</li>
</ul>

<br>

<a href="../logout.php">Logout</a>

</body>
</html>