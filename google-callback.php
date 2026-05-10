<?php
session_start();
require_once 'db.php';
require_once 'app_helpers.php';

if (empty($_GET['code']) || empty($_GET['state'])) {
    $_SESSION['auth_flash_error'] = 'Google login was cancelled or did not complete.';
    header('Location: login.php');
    exit();
}

$expectedState = $_SESSION['google_oauth_state'] ?? '';
if ($expectedState === '' || !hash_equals($expectedState, (string) $_GET['state'])) {
    $_SESSION['auth_flash_error'] = 'Google login could not be verified. Please try again.';
    header('Location: login.php');
    exit();
}

unset($_SESSION['google_oauth_state']);
$flow = $_SESSION['google_oauth_flow'] ?? 'signin';
unset($_SESSION['google_oauth_flow']);
$requestedRole = $_SESSION['google_oauth_requested_role'] ?? 'customer';
unset($_SESSION['google_oauth_requested_role']);

try {
    $tokenData = google_oauth_exchange_code((string) $_GET['code']);
    $accessToken = $tokenData['access_token'] ?? '';

    if ($accessToken === '') {
        throw new RuntimeException('Google did not return an access token.');
    }

    $profile = google_oauth_fetch_userinfo($accessToken);
    $email = trim($profile['email'] ?? '');
    $emailVerified = !empty($profile['verified_email']);
    $displayName = trim($profile['name'] ?? '');

    if ($email === '' || !$emailVerified) {
        throw new RuntimeException('Google account email is missing or unverified.');
    }

    $name = $displayName !== '' ? $displayName : preg_replace('/@.+$/', '', $email);

    $stmt = mysqli_prepare($conn, "SELECT id, name, email, password, role, account_status FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if ($user && ($user['account_status'] ?? 'active') === 'suspended') {
        throw new RuntimeException('This account is suspended. Please contact the admin.');
    }

    if (!$user) {
        $generatedPassword = password_hash(bin2hex(random_bytes(24)), PASSWORD_DEFAULT);
        $role = in_array($flow, ['signin', 'signup'], true) ? $requestedRole : 'customer';
        if (!in_array($role, ['admin', 'seller', 'customer'], true)) {
            $role = 'customer';
        }
        $status = 'active';

        $stmt = mysqli_prepare($conn, "INSERT INTO users (name, email, password, role, account_status) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $generatedPassword, $role, $status);
        mysqli_stmt_execute($stmt);

        $user = [
            'id' => mysqli_insert_id($conn),
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'account_status' => $status,
        ];
    }

    session_regenerate_id(true);
    $_SESSION['user_id'] = (int) $user['id'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['role'] = $user['role'];

    if ($user['role'] === 'admin') {
        header('Location: admin/dashboard.php');
    } elseif ($user['role'] === 'seller') {
        header('Location: seller/dashboard.php');
    } else {
        header('Location: customer/dashboard.php');
    }
    exit();
} catch (Throwable $e) {
    $_SESSION['auth_flash_error'] = $e->getMessage();
    header('Location: login.php');
    exit();
}
