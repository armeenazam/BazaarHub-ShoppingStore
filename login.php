<<<<<<< Updated upstream
=======
<?php
session_start();
include "db.php";

$message = "";
$message_type = "";
$auth_mode = "signin";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['full_name'])) {
        $auth_mode = "signup";
        $full_name = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'customer';
        $allowed_roles = ['customer', 'seller'];

        if (empty($full_name) || empty($email) || empty($password)) {
            $message = "All fields are required.";
            $message_type = "error";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = "Please enter a valid email address.";
            $message_type = "error";
        } elseif (strlen($password) < 6) {
            $message = "Password must be at least 6 characters.";
            $message_type = "error";
        } elseif (!in_array($role, $allowed_roles, true)) {
            $message = "Please choose a valid account role.";
            $message_type = "error";
        } else {
            $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $check_stmt->bind_param("s", $email);
            $check_stmt->execute();

            if ($check_stmt->get_result()->num_rows > 0) {
                $message = "Email already registered.";
                $message_type = "error";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $insert_stmt = $conn->prepare("INSERT INTO users (name, email, password, role, created_at) VALUES (?, ?, ?, ?, NOW())");
                $insert_stmt->bind_param("ssss", $full_name, $email, $hashed_password, $role);

                if ($insert_stmt->execute()) {
                    if ($insert_stmt->affected_rows > 0) {
                        $message = "Account created successfully. Please sign in.";
                    } else {
                        $message = "Insert failed (no rows affected).";
                    }
                }
                else {
                    $message = "Error: " . $insert_stmt->error;
                }

                $insert_stmt->close();
            }

            $check_stmt->close();
        }
    } else {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $stmt = $conn->prepare("SELECT id, name, role, password FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user['password']) || $password === $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['email'] = $email;
                session_regenerate_id(true);

                if ($user['role'] === 'admin') {
                    header("Location: admin/dashboard.php");
                } elseif ($user['role'] === 'seller') {
                    header("Location: seller/dashboard.php");
                } else {
                    header("Location: customer/dashboard.php");
                }
                exit();
            }

            $message = "Invalid password.";
            $message_type = "error";
        } else {
            $message = "No user found with that email.";
            $message_type = "error";
        }

        $stmt->close();
    }
}

$page_title = "BazaarHub Auth";
$signin_email = $auth_mode === 'signin' ? ($_POST['email'] ?? '') : '';
$signup_name = $auth_mode === 'signup' ? ($_POST['full_name'] ?? '') : '';
$signup_email = $auth_mode === 'signup' ? ($_POST['email'] ?? '') : '';
$signup_role = $auth_mode === 'signup' ? ($_POST['role'] ?? 'customer') : 'customer';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/auth.css">
    <?php if ($message): ?>
        <script>
            window.authMessage = {
                text: <?php echo json_encode($message); ?>,
                type: <?php echo json_encode($message_type ?: 'info'); ?>
            };
        </script>
    <?php endif; ?>
</head>

<body class="auth-page">
    <div class="page-glow page-glow--top" aria-hidden="true"></div>
    <div class="page-glow page-glow--bottom" aria-hidden="true"></div>

    <header class="site-header">
        <div class="site-header__inner">
            <a class="wordmark" href="login.php" aria-label="BazaarHub home">
                <span>Bazaar</span><span class="wordmark__accent">Hub</span>
            </a>
        </div>
    </header>

    <main class="auth-main">
        <section class="auth-card" data-auth-shell data-mode="<?php echo htmlspecialchars($auth_mode, ENT_QUOTES, 'UTF-8'); ?>" aria-label="Sign in or create account">
            <div class="auth-card__mobile-head">
                <p class="eyebrow">Marketplace Access</p>
                <p class="muted-copy">Join BazaarHub to discover artisans, manage your shop, and keep every order in motion.</p>
            </div>

            <div class="form-window" aria-live="polite">
                <div class="form-track">
                    <section class="form-pane form-pane--signin" data-form-pane="signin" aria-labelledby="signin-heading">
                        <div class="form-pane__header">
                            <h2 id="signin-heading">Sign in</h2>
                        </div>

                        <div class="social-row" aria-label="Sign in with social providers">
                            <button class="icon-button" type="button" data-social="Facebook" aria-label="Continue with Facebook">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M13.5 21v-7h2.4l.4-3h-2.8V9.1c0-.9.2-1.6 1.5-1.6h1.4V4.8c-.2 0-1-.1-2-.1-2 0-3.4 1.2-3.4 3.6V11H8v3h3.1v7h2.4z" />
                                </svg>
                            </button>
                            <button class="icon-button" type="button" data-social="Google" aria-label="Continue with Google">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M21.8 12.2c0-.6-.1-1.1-.2-1.6H12v3h5.5c-.2 1.3-.9 2.4-2 3.1v2.5h3.2c1.9-1.8 3.1-4.4 3.1-7.5z" />
                                    <path d="M12 22c2.7 0 5-.9 6.7-2.5l-3.2-2.5c-.9.6-2.1 1-3.5 1-2.7 0-5-1.8-5.8-4.2H2.9v2.6C4.6 19.8 8 22 12 22z" />
                                    <path d="M6.2 13.8c-.2-.6-.4-1.2-.4-1.8s.1-1.2.4-1.8V7.6H2.9C2.3 8.9 2 10.4 2 12s.3 3.1.9 4.4l3.3-2.6z" />
                                    <path d="M12 5.8c1.5 0 2.8.5 3.9 1.5l2.9-2.9C17 2.8 14.7 2 12 2 8 2 4.6 4.2 2.9 7.6l3.3 2.6C7 7.6 9.3 5.8 12 5.8z" />
                                </svg>
                            </button>
                            <button class="icon-button" type="button" data-social="Apple" aria-label="Continue with Apple">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M16.8 12.7c0-2.2 1.8-3.2 1.9-3.3-1-1.5-2.7-1.7-3.3-1.7-1.4-.1-2.8.8-3.5.8-.7 0-1.8-.8-3-.8-1.6 0-3 .9-3.8 2.2-1.6 2.7-.4 6.8 1.1 8.9.8 1 1.7 2 2.9 1.9 1.2-.1 1.6-.7 3-.7 1.4 0 1.8.7 3 .7 1.3 0 2.1-1 2.8-2 .9-1.2 1.3-2.4 1.3-2.5-.1 0-2.4-.9-2.4-3.5zm-2.3-6.5c.6-.7 1-1.7.9-2.7-.9 0-1.9.6-2.5 1.3-.6.7-1.1 1.7-1 2.6 1 .1 2-.5 2.6-1.2z" />
                                </svg>
                            </button>
                        </div>

                        <div class="divider"><span>or use your email for registration</span></div>

                        <form class="auth-form" method="POST" action="login.php" novalidate>
                            <div class="field">
                                <div class="field__control">
                                    <span class="field__icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24">
                                            <path d="M4 6h16a2 2 0 0 1 2 2v.4l-10 5.6L2 8.4V8a2 2 0 0 1 2-2zm18 4.7V16a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-5.3l9.5 5.3a1 1 0 0 0 1 0L22 10.7z" />
                                        </svg>
                                    </span>
                                    <input id="signin-email" name="email" type="email" placeholder="Email Address" value="<?php echo htmlspecialchars($signin_email, ENT_QUOTES, 'UTF-8'); ?>" required>
                                </div>
                            </div>

                            <div class="field">
                                <div class="field__control field__control--password">
                                    <span class="field__icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24">
                                            <path d="M17 9h-1V7a4 4 0 1 0-8 0v2H7a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-7a2 2 0 0 0-2-2zm-6 6.7V17a1 1 0 1 0 2 0v-1.3a2 2 0 1 0-2 0zM10 9V7a2 2 0 1 1 4 0v2h-4z" />
                                        </svg>
                                    </span>
                                    <input id="signin-password" name="password" type="password" placeholder="Password" required>
                                    <button class="password-toggle" type="button" data-password-toggle aria-label="Show password">Show</button>
                                </div>
                            </div>

                            <div class="form-row">
                                <label class="checkbox">
                                    <input type="checkbox" name="remember_me">
                                    <span>Remember me</span>
                                </label>
                                <a class="text-link" href="forgot-password.php">Forgot your password?</a>
                            </div>

                            <button class="submit-button" type="submit" data-loading-text="Signing In">Sign In</button>
                        </form>
                    </section>

                    <section class="form-pane form-pane--signup" data-form-pane="signup" aria-labelledby="signup-heading">
                        <div class="form-pane__header">
                            <h2 id="signup-heading">Create Account</h2>
                        </div>

                        <div class="social-row" aria-label="Create account with social providers">
                            <button class="icon-button" type="button" data-social="Facebook" aria-label="Continue with Facebook">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M13.5 21v-7h2.4l.4-3h-2.8V9.1c0-.9.2-1.6 1.5-1.6h1.4V4.8c-.2 0-1-.1-2-.1-2 0-3.4 1.2-3.4 3.6V11H8v3h3.1v7h2.4z" />
                                </svg>
                            </button>
                            <button class="icon-button" type="button" data-social="Google" aria-label="Continue with Google">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M21.8 12.2c0-.6-.1-1.1-.2-1.6H12v3h5.5c-.2 1.3-.9 2.4-2 3.1v2.5h3.2c1.9-1.8 3.1-4.4 3.1-7.5z" />
                                    <path d="M12 22c2.7 0 5-.9 6.7-2.5l-3.2-2.5c-.9.6-2.1 1-3.5 1-2.7 0-5-1.8-5.8-4.2H2.9v2.6C4.6 19.8 8 22 12 22z" />
                                    <path d="M6.2 13.8c-.2-.6-.4-1.2-.4-1.8s.1-1.2.4-1.8V7.6H2.9C2.3 8.9 2 10.4 2 12s.3 3.1.9 4.4l3.3-2.6z" />
                                    <path d="M12 5.8c1.5 0 2.8.5 3.9 1.5l2.9-2.9C17 2.8 14.7 2 12 2 8 2 4.6 4.2 2.9 7.6l3.3 2.6C7 7.6 9.3 5.8 12 5.8z" />
                                </svg>
                            </button>
                            <button class="icon-button" type="button" data-social="Apple" aria-label="Continue with Apple">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M16.8 12.7c0-2.2 1.8-3.2 1.9-3.3-1-1.5-2.7-1.7-3.3-1.7-1.4-.1-2.8.8-3.5.8-.7 0-1.8-.8-3-.8-1.6 0-3 .9-3.8 2.2-1.6 2.7-.4 6.8 1.1 8.9.8 1 1.7 2 2.9 1.9 1.2-.1 1.6-.7 3-.7 1.4 0 1.8.7 3 .7 1.3 0 2.1-1 2.8-2 .9-1.2 1.3-2.4 1.3-2.5-.1 0-2.4-.9-2.4-3.5zm-2.3-6.5c.6-.7 1-1.7.9-2.7-.9 0-1.9.6-2.5 1.3-.6.7-1.1 1.7-1 2.6 1 .1 2-.5 2.6-1.2z" />
                                </svg>
                            </button>
                        </div>

                        <div class="divider"><span>or create with email</span></div>

                        <form class="auth-form" method="POST" action="login.php" novalidate>
                            <div class="field">
                                <div class="field__control">
                                    <span class="field__icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24">
                                            <path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4zm0 2c-3.3 0-6 1.8-6 4v1h12v-1c0-2.2-2.7-4-6-4z" />
                                        </svg>
                                    </span>
                                    <input id="signup-name" name="full_name" type="text" placeholder="Full Name" value="<?php echo htmlspecialchars($signup_name, ENT_QUOTES, 'UTF-8'); ?>" required>
                                </div>
                            </div>

                            <div class="field">
                                <div class="field__control">
                                    <span class="field__icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24">
                                            <path d="M4 6h16a2 2 0 0 1 2 2v.4l-10 5.6L2 8.4V8a2 2 0 0 1 2-2zm18 4.7V16a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-5.3l9.5 5.3a1 1 0 0 0 1 0L22 10.7z" />
                                        </svg>
                                    </span>
                                    <input id="signup-email" name="email" type="email" placeholder="Email Address" value="<?php echo htmlspecialchars($signup_email, ENT_QUOTES, 'UTF-8'); ?>" required>
                                </div>
                            </div>

                            <div class="field">
                                <div class="field__control">
                                    <span class="field__icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24">
                                            <path d="M12 2 3 6v6c0 5 3.8 9.7 9 11 5.2-1.3 9-6 9-11V6l-9-4zm0 2.2 6.8 3v4.8c0 3.8-2.7 7.6-6.8 8.8-4.1-1.2-6.8-5-6.8-8.8V7.2L12 4.2z" />
                                        </svg>
                                    </span>
                                    <select id="signup-role" name="role" required>
                                        <option value="customer" <?php echo $signup_role === 'customer' ? 'selected' : ''; ?>>Customer</option>
                                        <option value="seller" <?php echo $signup_role === 'seller' ? 'selected' : ''; ?>>Seller</option>
                                    </select>
                                </div>
                            </div>

                            <div class="field">
                                <div class="field__control field__control--password">
                                    <span class="field__icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24">
                                            <path d="M17 9h-1V7a4 4 0 1 0-8 0v2H7a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-7a2 2 0 0 0-2-2zm-6 6.7V17a1 1 0 1 0 2 0v-1.3a2 2 0 1 0-2 0zM10 9V7a2 2 0 1 1 4 0v2h-4z" />
                                        </svg>
                                    </span>
                                    <input id="signup-password" name="password" type="password" placeholder="Password" required>
                                    <button class="password-toggle" type="button" data-password-toggle aria-label="Show password">Show</button>
                                </div>
                            </div>

                            <button class="submit-button" type="submit" data-loading-text="Creating Account">Sign Up</button>
                        </form>
                    </section>
                </div>
            </div>

            <aside class="overlay-panel" aria-hidden="true">
                <div class="overlay-copy overlay-copy--signin is-visible">
                    <h2 id="signin-heading">Hey There!</h2>
                    <p>Begin your shopping journey by creating an account with us today.</p>
                    <button class="overlay-button" type="button" data-switch-mode="signup">Sign Up</button>
                </div>
                <div class="overlay-copy overlay-copy--signup">
                    <h2 id="signin-heading">Welcome Back!</h2>
                    <p>Stay connected by logging in with your credentials and continue your journey at BazaarHub.</p>
                    <button class="overlay-button" type="button" data-switch-mode="signin">Sign In</button>
                </div>
            </aside>

            <div class="mobile-toggle">
                <button class="mobile-toggle__button" type="button" data-mobile-switch>
                    <span data-mobile-switch-label>Need an account? Sign Up</span>
                </button>
            </div>
        </section>
    </main>

    <div class="toast-host" aria-live="polite" aria-atomic="true"></div>
    <script src="assets/js/auth.js"></script>
</body>

</html>
>>>>>>> Stashed changes
