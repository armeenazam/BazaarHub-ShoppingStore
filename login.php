<?php
session_start();
include "db.php";

if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {

        $user = mysqli_fetch_assoc($result);

        // store session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];

        // role-based redirect
        if ($user['role'] == 'admin') {
            header("Location: admin/dashboard.php");
        }
        elseif ($user['role'] == 'seller') {
            header("Location: seller/dashboard.php");
        }
        else {
            header("Location: customer/home.php");
        }

    } else {
        echo "Invalid email or password!";
    }
}
?>

<!-- HTML FORM -->
<form method="POST">
    <h2>Login</h2>

    <input type="email" name="email" placeholder="Email" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>

    <button type="submit" name="login">Login</button>
</form>