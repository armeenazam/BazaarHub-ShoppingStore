<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../login.php");
    exit();
}

$user_id = (int) $_SESSION['user_id'];
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_cart'])) {
        $cart_id = (int) $_POST['cart_id'];
        $quantity = max(1, (int) $_POST['quantity']);
        $stmt = mysqli_prepare($conn, "UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
        mysqli_stmt_bind_param($stmt, "iii", $quantity, $cart_id, $user_id);
        mysqli_stmt_execute($stmt);
        $message = "Cart updated.";
    }

    if (isset($_POST['remove_cart'])) {
        $cart_id = (int) $_POST['cart_id'];
        $stmt = mysqli_prepare($conn, "DELETE FROM cart WHERE id = ? AND user_id = ?");
        mysqli_stmt_bind_param($stmt, "ii", $cart_id, $user_id);
        mysqli_stmt_execute($stmt);
        $message = "Item removed.";
    }

    if (isset($_POST['place_order'])) {
        mysqli_begin_transaction($conn);

        try {
            $stmt = mysqli_prepare($conn, "
                SELECT c.product_id, c.quantity, p.price, p.stock
                FROM cart c
                JOIN products p ON p.id = c.product_id
                WHERE c.user_id = ?
                FOR UPDATE
            ");
            mysqli_stmt_bind_param($stmt, "i", $user_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            $items = [];
            $total = 0;

            while ($item = mysqli_fetch_assoc($result)) {
                if ((int) $item['quantity'] > (int) $item['stock']) {
                    throw new Exception("Not enough stock for one or more products.");
                }
                $items[] = $item;
                $total += $item['quantity'] * $item['price'];
            }

            if (!$items) {
                throw new Exception("Your cart is empty.");
            }

            $address_id = null;
            $stmt = mysqli_prepare($conn, "SELECT id FROM user_addresses WHERE user_id = ? ORDER BY is_default DESC, id ASC LIMIT 1");
            mysqli_stmt_bind_param($stmt, "i", $user_id);
            mysqli_stmt_execute($stmt);
            $address = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
            if ($address) {
                $address_id = (int) $address['id'];
            }

            $stmt = mysqli_prepare($conn, "INSERT INTO orders (user_id, delivery_address_id, total_amount, status) VALUES (?, ?, ?, 'completed')");
            mysqli_stmt_bind_param($stmt, "iid", $user_id, $address_id, $total);
            mysqli_stmt_execute($stmt);
            $order_id = mysqli_insert_id($conn);

            foreach ($items as $item) {
                $stmt = mysqli_prepare($conn, "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt, "iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
                mysqli_stmt_execute($stmt);

                $stmt = mysqli_prepare($conn, "UPDATE products SET stock = stock - ? WHERE id = ?");
                mysqli_stmt_bind_param($stmt, "ii", $item['quantity'], $item['product_id']);
                mysqli_stmt_execute($stmt);
            }

            $stmt = mysqli_prepare($conn, "INSERT INTO payments (order_id, amount, payment_status) VALUES (?, ?, 'paid')");
            mysqli_stmt_bind_param($stmt, "id", $order_id, $total);
            mysqli_stmt_execute($stmt);

            $invoice_number = 'INV-' . str_pad((string) $order_id, 4, '0', STR_PAD_LEFT);
            $stmt = mysqli_prepare($conn, "INSERT INTO invoices (order_id, invoice_number, subtotal, tax_amount, total_amount) VALUES (?, ?, ?, 0, ?)");
            mysqli_stmt_bind_param($stmt, "isdd", $order_id, $invoice_number, $total, $total);
            mysqli_stmt_execute($stmt);

            $stmt = mysqli_prepare($conn, "DELETE FROM cart WHERE user_id = ?");
            mysqli_stmt_bind_param($stmt, "i", $user_id);
            mysqli_stmt_execute($stmt);

            mysqli_commit($conn);
            header("Location: my_orders.php?placed=1");
            exit();
        } catch (Exception $e) {
            mysqli_rollback($conn);
            $message = $e->getMessage();
        }
    }
}

$stmt = mysqli_prepare($conn, "
    SELECT c.id, c.quantity, p.name, p.price, p.stock
    FROM cart c
    JOIN products p ON p.id = c.product_id
    WHERE c.user_id = ?
    ORDER BY c.id DESC
");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$cart_items = mysqli_stmt_get_result($stmt);
$total = 0;
?>
<!DOCTYPE html>
<html>
<head><title>Cart - BazaarHub</title></head>
<body>
<h1>Your Cart</h1>
<p>
    <a href="dashboard.php">Dashboard</a> |
    <a href="products.php">Products</a> |
    <a href="my_orders.php">Orders</a> |
    <a href="../logout.php">Logout</a>
</p>
<hr>

<?php if ($message): ?><p><strong><?= htmlspecialchars($message) ?></strong></p><?php endif; ?>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Product</th>
        <th>Price (PKR)</th>
        <th>Quantity</th>
        <th>Subtotal</th>
        <th>Action</th>
    </tr>
    <?php while ($item = mysqli_fetch_assoc($cart_items)): ?>
        <?php $subtotal = $item['price'] * $item['quantity']; $total += $subtotal; ?>
        <tr>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td><?= number_format($item['price'], 2) ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">
                    <input type="number" name="quantity" value="<?= (int) $item['quantity'] ?>" min="1" max="<?= (int) $item['stock'] ?>">
                    <button type="submit" name="update_cart">Update</button>
                </form>
            </td>
            <td><?= number_format($subtotal, 2) ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">
                    <button type="submit" name="remove_cart">Remove</button>
                </form>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<h3>Total: PKR <?= number_format($total, 2) ?></h3>

<?php if ($total > 0): ?>
    <form method="POST">
        <button type="submit" name="place_order">Place Order</button>
    </form>
<?php else: ?>
    <p>Your cart is empty. <a href="products.php">Browse products</a>.</p>
<?php endif; ?>
</body>
</html>
