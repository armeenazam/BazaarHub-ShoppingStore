<?php
session_start();
require_once 'app_helpers.php';

$flow = in_array($_GET['flow'] ?? 'signin', ['signin', 'signup'], true) ? $_GET['flow'] : 'signin';
$requestedRole = in_array($_GET['role'] ?? 'customer', ['admin', 'seller', 'customer'], true) ? $_GET['role'] : 'customer';

if (!google_oauth_ready()) {
    $_SESSION['auth_flash_error'] = 'Google login is not configured yet. Add GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET, and GOOGLE_REDIRECT_URI.';
    header('Location: login.php');
    exit();
}

$_SESSION['google_oauth_flow'] = $flow;
$_SESSION['google_oauth_requested_role'] = $requestedRole;
$_SESSION['google_oauth_state'] = bin2hex(random_bytes(16));

header('Location: ' . google_oauth_authorize_url($_SESSION['google_oauth_state']));
exit();
