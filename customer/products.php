<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../login.php");
    exit();
}

$user_id = (int) $_SESSION['user_id'];
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = (int) $_POST['product_id'];
    $quantity = max(1, (int) ($_POST['quantity'] ?? 1));

    $stmt = mysqli_prepare($conn, "SELECT stock FROM products WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    mysqli_stmt_execute($stmt);
    $product = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if (!$product) {
        $message = "Product not found.";
    } elseif ($quantity > (int) $product['stock']) {
        $message = "Requested quantity is more than available stock.";
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id FROM cart WHERE user_id = ? AND product_id = ?");
        mysqli_stmt_bind_param($stmt, "ii", $user_id, $product_id);
        mysqli_stmt_execute($stmt);
        $existing = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

        if ($existing) {
            $stmt = mysqli_prepare($conn, "UPDATE cart SET quantity = quantity + ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "ii", $quantity, $existing['id']);
        } else {
            $stmt = mysqli_prepare($conn, "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "iii", $user_id, $product_id, $quantity);
        }

        mysqli_stmt_execute($stmt);
        $message = "Product added to cart.";
    }
}

$search = trim($_GET['search'] ?? '');
$category_id = (int) ($_GET['category_id'] ?? 0);
$categories = mysqli_query($conn, "SELECT id, name FROM categories ORDER BY name");

$sql = "
    SELECT p.id, p.name, p.description, p.price, p.stock,
           c.name AS category_name, u.name AS seller_name,
           COALESCE(AVG(r.rating), 0) AS avg_rating
    FROM products p
    JOIN categories c ON c.id = p.category_id
    JOIN users u ON u.id = p.seller_id
    LEFT JOIN reviews r ON r.product_id = p.id
    WHERE (? = '' OR p.name LIKE CONCAT('%', ?, '%'))
      AND (? = 0 OR p.category_id = ?)
    GROUP BY p.id, p.name, p.description, p.price, p.stock, c.name, u.name
    ORDER BY p.created_at DESC
";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ssii", $search, $search, $category_id, $category_id);
mysqli_stmt_execute($stmt);
$products = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html>
<head><title>Products - BazaarHub</title></head>
<body>
<h1>Browse Products</h1>
<p>
    <a href="dashboard.php">Dashboard</a> |
    <a href="cart.php">Cart</a> |
    <a href="my_orders.php">Orders</a> |
    <a href="../logout.php">Logout</a>
</p>
<hr>

<?php if ($message): ?><p><strong><?= htmlspecialchars($message) ?></strong></p><?php endif; ?>

<form method="GET">
    <input type="text" name="search" placeholder="Search products" value="<?= htmlspecialchars($search) ?>">
    <select name="category_id">
        <option value="0">All categories</option>
        <?php while ($category = mysqli_fetch_assoc($categories)): ?>
            <option value="<?= $category['id'] ?>" <?= $category_id === (int) $category['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($category['name']) ?>
            </option>
        <?php endwhile; ?>
    </select>
    <button type="submit">Search</button>
</form>

<br>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Product</th>
        <th>Category</th>
        <th>Seller</th>
        <th>Price (PKR)</th>
        <th>Stock</th>
        <th>Rating</th>
        <th>Cart</th>
    </tr>
    <?php while ($product = mysqli_fetch_assoc($products)): ?>
        <tr>
            <td>
                <strong><?= htmlspecialchars($product['name']) ?></strong><br>
                <?= htmlspecialchars($product['description'] ?? '') ?>
            </td>
            <td><?= htmlspecialchars($product['category_name']) ?></td>
            <td><?= htmlspecialchars($product['seller_name']) ?></td>
            <td><?= number_format($product['price'], 2) ?></td>
            <td><?= (int) $product['stock'] ?></td>
            <td><?= number_format($product['avg_rating'], 1) ?>/5</td>
            <td>
                <?php if ((int) $product['stock'] > 0): ?>
                    <form method="POST">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <input type="number" name="quantity" value="1" min="1" max="<?= (int) $product['stock'] ?>">
                        <button type="submit">Add</button>
                    </form>
                <?php else: ?>
                    Out of stock
                <?php endif; ?>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
</body>
</html>
