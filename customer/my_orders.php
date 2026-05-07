<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../login.php");
    exit();
}

$user_id = (int) $_SESSION['user_id'];
$stmt = mysqli_prepare($conn, "
    SELECT o.id, o.total_amount, o.status, o.created_at,
           p.payment_status, i.invoice_number
    FROM orders o
    LEFT JOIN payments p ON p.order_id = o.id
    LEFT JOIN invoices i ON i.order_id = o.id
    WHERE o.user_id = ?
    ORDER BY o.created_at DESC
");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$orders = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html>
<head><title>My Orders - BazaarHub</title></head>
<body>
<h1>My Orders</h1>
<p>
    <a href="dashboard.php">Dashboard</a> |
    <a href="products.php">Products</a> |
    <a href="cart.php">Cart</a> |
    <a href="reviews.php">Reviews</a> |
    <a href="../logout.php">Logout</a>
</p>
<hr>

<?php if (isset($_GET['placed'])): ?>
    <p><strong>Order placed successfully. Payment and invoice were created.</strong></p>
<?php endif; ?>

<?php while ($order = mysqli_fetch_assoc($orders)): ?>
    <h3>
        Order #<?= (int) $order['id'] ?>
        <?php if ($order['invoice_number']): ?>
            - Invoice <?= htmlspecialchars($order['invoice_number']) ?>
        <?php endif; ?>
    </h3>
    <p>
        Status: <?= htmlspecialchars($order['status']) ?> |
        Payment: <?= htmlspecialchars($order['payment_status'] ?? 'pending') ?> |
        Total: PKR <?= number_format($order['total_amount'], 2) ?> |
        Date: <?= htmlspecialchars($order['created_at']) ?>
    </p>

    <?php
    $order_id = (int) $order['id'];
    $items = mysqli_query($conn, "
        SELECT p.name, oi.quantity, oi.price
        FROM order_items oi
        JOIN products p ON p.id = oi.product_id
        WHERE oi.order_id = $order_id
    ");
    ?>
    <ul>
        <?php while ($item = mysqli_fetch_assoc($items)): ?>
            <li>
                <?= htmlspecialchars($item['name']) ?> -
                Qty <?= (int) $item['quantity'] ?> -
                PKR <?= number_format($item['price'], 2) ?>
            </li>
        <?php endwhile; ?>
    </ul>
<?php endwhile; ?>
</body>
</html>
