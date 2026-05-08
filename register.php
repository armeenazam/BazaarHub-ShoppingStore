<?php
session_start();
include 'db.php';

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $role     = in_array($_POST['role'], ['admin', 'seller', 'customer'], true) ? $_POST['role'] : 'customer';

    // Check if email already exists
    $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $check = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($check) > 0) {
        $error = "Email already registered.";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($conn, "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $hashed, $role);
        $insert = mysqli_stmt_execute($stmt);
        if ($insert) {
            $success = "Account created! <a href='login.php'>Login here</a>";
        } else {
            $error = "Something went wrong. Try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - BazaarHub</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 400px; margin: 60px auto; }
        input, select { width: 100%; padding: 8px; margin: 8px 0 16px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #e44d26; color: white; border: none; cursor: pointer; }
        .error { color: red; } .success { color: green; }
    </style>
</head>
<body>
    <h2>Register - BazaarHub</h2>

    <?php if($error): ?><p class="error"><?= $error ?></p><?php endif; ?>
    <?php if($success): ?><p class="success"><?= $success ?></p><?php endif; ?>

    <form method="POST">
        <label>Full Name</label>
        <input type="text" name="name" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <label>Register As</label>
        <select name="role">
            <option value="customer">Customer</option>
            <option value="seller">Seller</option>
            <option value="admin">Admin</option>
        </select>

        <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login</a></p>
</body>
</html>
