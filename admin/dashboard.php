<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>

<h1>Admin Panel</h1>

<p>Welcome, <?php echo $_SESSION['name']; ?> 👋</p>

<hr>

<h3>Management Options</h3>

<ul>
    <li>Manage Users</li>
    <li>Manage Products</li>
    <li>Manage Categories</li>
    <li>View Orders</li>
    <li>View Reports</li>
</ul>

<br>

<a href="../logout.php">Logout</a>

</body>
</html>