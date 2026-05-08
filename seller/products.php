<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
    header("Location: ../login.php");
    exit();
}

$seller_id = (int) $_SESSION['user_id'];
$message = "";
$upload_dir = dirname(__DIR__) . '/assets/images/products/';

if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

function product_image_path(array $file, string $upload_dir): ?string
{
    if (!isset($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'jfif'], true)) {
        return null;
    }

    $base = preg_replace('/[^a-z0-9]+/i', '_', pathinfo($file['name'], PATHINFO_FILENAME));
    $filename = strtolower(trim($base, '_')) . '_' . time() . '.' . $extension;

    if (move_uploaded_file($file['tmp_name'], $upload_dir . $filename)) {
        return 'assets/images/products/' . $filename;
    }

    return null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save_product'])) {
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = (float) ($_POST['price'] ?? 0);
        $stock = max(0, (int) ($_POST['stock'] ?? 0));
        $category_id = (int) ($_POST['category_id'] ?? 0);
        $product_id = (int) ($_POST['product_id'] ?? 0);
        $image_url = product_image_path($_FILES['image'] ?? [], $upload_dir);

        if ($name === '' || $price <= 0 || $category_id <= 0) {
            $message = "Name, price, and category are required.";
        } elseif ($product_id > 0) {
            if ($image_url) {
                $stmt = mysqli_prepare($conn, "UPDATE products SET name = ?, description = ?, image_url = ?, price = ?, stock = ?, category_id = ? WHERE id = ? AND seller_id = ?");
                mysqli_stmt_bind_param($stmt, "sssdiiii", $name, $description, $image_url, $price, $stock, $category_id, $product_id, $seller_id);
            } else {
                $stmt = mysqli_prepare($conn, "UPDATE products SET name = ?, description = ?, price = ?, stock = ?, category_id = ? WHERE id = ? AND seller_id = ?");
                mysqli_stmt_bind_param($stmt, "ssdiiii", $name, $description, $price, $stock, $category_id, $product_id, $seller_id);
            }
            mysqli_stmt_execute($stmt);
            $message = "Product updated.";
        } else {
            $image_url = $image_url ?: 'assets/images/products/blue_bow_blouse.jfif';
            $stmt = mysqli_prepare($conn, "INSERT INTO products (name, description, image_url, price, stock, category_id, seller_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sssdiii", $name, $description, $image_url, $price, $stock, $category_id, $seller_id);
            mysqli_stmt_execute($stmt);
            $message = "Product added.";
        }
    }

    if (isset($_POST['delete_product'])) {
        $product_id = (int) $_POST['product_id'];
        $stmt = mysqli_prepare($conn, "DELETE FROM products WHERE id = ? AND seller_id = ?");
        mysqli_stmt_bind_param($stmt, "ii", $product_id, $seller_id);
        mysqli_stmt_execute($stmt);
        $message = "Product deleted.";
    }
}

$categories = mysqli_query($conn, "SELECT id, name FROM categories ORDER BY name");
$edit_product = null;
if (isset($_GET['edit'])) {
    $edit_id = (int) $_GET['edit'];
    $stmt = mysqli_prepare($conn, "SELECT * FROM products WHERE id = ? AND seller_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $edit_id, $seller_id);
    mysqli_stmt_execute($stmt);
    $edit_product = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}

$stmt = mysqli_prepare($conn, "
    SELECT p.*, c.name AS category_name, COALESCE(AVG(r.rating), 0) AS avg_rating
    FROM products p
    JOIN categories c ON c.id = p.category_id
    LEFT JOIN reviews r ON r.product_id = p.id
    WHERE p.seller_id = ?
    GROUP BY p.id, c.name
    ORDER BY p.created_at DESC
");
mysqli_stmt_bind_param($stmt, "i", $seller_id);
mysqli_stmt_execute($stmt);
$products = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Products - BazaarHub</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/customer.css">
</head>
<body class="customer-page">
<main class="shop-shell">
    <nav class="shop-nav">
        <a class="shop-brand" href="dashboard.php"><strong>BazaarHub</strong><span>Seller studio</span></a>
        <div class="shop-links">
            <a class="shop-link" href="dashboard.php">Dashboard</a>
            <a class="shop-link" href="../logout.php">Logout</a>
        </div>
    </nav>

    <section class="checkout-layout">
        <form class="checkout-form shop-panel" method="POST" enctype="multipart/form-data">
            <p class="shop-kicker"><?= $edit_product ? 'Edit product' : 'Add product' ?></p>
            <h1 class="checkout-title">Seller catalog</h1>
            <?php if ($message): ?><div class="notice"><?= htmlspecialchars($message) ?></div><?php endif; ?>
            <input type="hidden" name="product_id" value="<?= (int) ($edit_product['id'] ?? 0) ?>">
            <label>Name <input class="shop-input" name="name" value="<?= htmlspecialchars($edit_product['name'] ?? '') ?>" required></label>
            <label>Description <textarea class="shop-textarea" name="description" required><?= htmlspecialchars($edit_product['description'] ?? '') ?></textarea></label>
            <label>Image <input class="shop-input" type="file" name="image" accept=".jpg,.jpeg,.png,.webp,.jfif"></label>
            <label>Price in dollars <input class="shop-input" type="number" step="0.01" min="0.01" name="price" value="<?= htmlspecialchars($edit_product['price'] ?? '') ?>" required></label>
            <label>Stock <input class="shop-input" type="number" min="0" name="stock" value="<?= htmlspecialchars($edit_product['stock'] ?? '10') ?>" required></label>
            <label>Category
                <select class="shop-select" name="category_id" required>
                    <?php while ($category = mysqli_fetch_assoc($categories)): ?>
                        <option value="<?= $category['id'] ?>" <?= (int) ($edit_product['category_id'] ?? 0) === (int) $category['id'] ? 'selected' : '' ?>><?= htmlspecialchars($category['name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </label>
            <button class="shop-button shop-button--primary" name="save_product" type="submit"><?= $edit_product ? 'Update Product' : 'Add Product' ?></button>
        </form>

        <section class="checkout-summary">
            <p class="shop-kicker">Product ownership</p>
            <p class="muted-copy">Images are stored on the product rows you create. Each product also stores your seller id, so the catalog stays tied to the individual seller account.</p>
        </section>
    </section>

    <section class="product-grid">
        <?php while ($product = mysqli_fetch_assoc($products)): ?>
            <article class="product-card">
                <div class="product-card__image">
                    <img src="../<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                    <span class="product-chip"><?= htmlspecialchars($product['category_name']) ?></span>
                </div>
                <div class="product-card__body">
                    <div class="product-card__meta"><span><?= (int) $product['stock'] ?> in stock</span><span><?= number_format($product['avg_rating'], 1) ?>/5</span></div>
                    <h2><?= htmlspecialchars($product['name']) ?></h2>
                    <p class="product-desc"><?= htmlspecialchars($product['description']) ?></p>
                    <div class="product-card__footer">
                        <div class="product-price"><strong>$<?= number_format($product['price'], 2) ?></strong></div>
                        <div class="shop-links">
                            <a class="shop-link" href="products.php?edit=<?= $product['id'] ?>">Edit</a>
                            <form method="POST">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <button class="shop-button" name="delete_product" type="submit">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </article>
        <?php endwhile; ?>
    </section>
</main>
</body>
</html>
