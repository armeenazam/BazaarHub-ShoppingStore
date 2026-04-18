<?php
session_start();
include '../db.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// Submit review
if(isset($_POST['submit_review'])) {
    $product_id = intval($_POST['product_id']);
    $rating     = intval($_POST['rating']);
    $comment    = trim($_POST['comment']);

    // Check if already reviewed
    $check = mysqli_query($conn, "
        SELECT id FROM reviews 
        WHERE user_id=$user_id AND product_id=$product_id
    ");

    if(mysqli_num_rows($check) > 0) {
        $message = "❌ You already reviewed this product.";
    } else {
        mysqli_query($conn, "
            INSERT INTO reviews (user_id, product_id, rating, comment)
            VALUES ($user_id, $product_id, $rating, '$comment')
        ");
        $message = "✅ Review submitted!";
    }
}

// Show products the customer has ordered (can only review those)
$products = mysqli_query($conn, "
    SELECT DISTINCT p.id, p.name
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    JOIN products p ON oi.product_id = p.id
    WHERE o.user_id = $user_id
");
?>
<!DOCTYPE html>
<html>
<head><title>Reviews - BazaarHub</title></head>
<body>
<h2>Write a Review</h2>
<a href="products.php">← Back</a>
<hr>
<?php if($message): ?><p><?= $message ?></p><?php endif; ?>

<form method="POST">
    <select name="product_id" required>
        <option value="">Select a product you ordered</option>
        <?php while($p = mysqli_fetch_assoc($products)): ?>
            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?></option>
        <?php endwhile; ?>
    </select><br><br>
    <label>Rating (1-5):</label>
    <select name="rating">
        <option value="5">⭐⭐⭐⭐⭐ 5</option>
        <option value="4">⭐⭐⭐⭐ 4</option>
        <option value="3">⭐⭐⭐ 3</option>
        <option value="2">⭐⭐ 2</option>
        <option value="1">⭐ 1</option>
    </select><br><br>
    <textarea name="comment" placeholder="Write your review..."></textarea><br><br>
    <button type="submit" name="submit_review">Submit Review</button>
</form>
</body>
</html>