<?php $page_title = "Reset Password | BazaarHub"; ?>
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
</head>
<body class="auth-page auth-page--utility">
    <div class="page-glow page-glow--top" aria-hidden="true"></div>
    <div class="page-glow page-glow--bottom" aria-hidden="true"></div>

    <header class="site-header">
        <div class="site-header__inner">
            <a class="wordmark" href="login.php" aria-label="BazaarHub home">
                <span>Bazaar</span><span class="wordmark__accent">Hub</span>
            </a>
            <nav class="site-nav" aria-label="Primary">
                <a href="#">Discover</a>
                <a href="#">Artisans</a>
                <a href="#">Stories</a>
            </nav>
        </div>
    </header>

    <main class="utility-main">
        <section class="utility-card utility-card--wide">
            <div class="utility-grid">
                <div>
                    <p class="eyebrow">Reset Password</p>
                    <h1>Choose a fresh password</h1>
                    <p class="muted-copy">Use something strong, memorable, and unique to your BazaarHub account.</p>

                    <form class="auth-form" data-mock-form="reset-password" novalidate>
                        <div class="field">
                            <label for="reset-password">New password</label>
                            <div class="field__control field__control--password">
                                <span class="field__icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24"><path d="M17 9h-1V7a4 4 0 1 0-8 0v2H7a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-7a2 2 0 0 0-2-2zm-6 6.7V17a1 1 0 1 0 2 0v-1.3a2 2 0 1 0-2 0zM10 9V7a2 2 0 1 1 4 0v2h-4z"/></svg>
                                </span>
                                <input id="reset-password" name="password" type="password" placeholder="Create a new password" data-strength-source required>
                                <button class="password-toggle" type="button" data-password-toggle aria-label="Show password">Show</button>
                            </div>
                        </div>

                        <div class="field">
                            <label for="confirm-reset-password">Confirm password</label>
                            <div class="field__control field__control--password">
                                <span class="field__icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24"><path d="M17 9h-1V7a4 4 0 1 0-8 0v2H7a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-7a2 2 0 0 0-2-2zm-6 6.7V17a1 1 0 1 0 2 0v-1.3a2 2 0 1 0-2 0zM10 9V7a2 2 0 1 1 4 0v2h-4z"/></svg>
                                </span>
                                <input id="confirm-reset-password" name="confirm_password" type="password" placeholder="Confirm your new password" required>
                                <button class="password-toggle" type="button" data-password-toggle aria-label="Show password">Show</button>
                            </div>
                        </div>

                        <button class="submit-button" type="submit" data-loading-text="Resetting Password">Reset Password</button>
                    </form>
                </div>

                <aside class="strength-card" aria-label="Password strength guidance">
                    <p class="eyebrow">Strength Checklist</p>
                    <h2>Make it strong</h2>
                    <ul class="strength-list">
                        <li data-strength-check="length">At least 8 characters</li>
                        <li data-strength-check="uppercase">One uppercase letter</li>
                        <li data-strength-check="number">One number</li>
                        <li data-strength-check="symbol">One special character</li>
                    </ul>
                </aside>
            </div>

            <div class="utility-actions">
                <a class="text-link" href="login.php">Back to sign in</a>
                <a class="text-link" href="forgot-password.php">Need another reset link?</a>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="site-footer__inner">
            <p>&copy; BazaarHub</p>
            <p>Marrakech | Istanbul | Jaipur | Oaxaca</p>
        </div>
    </footer>

    <div class="toast-host" aria-live="polite" aria-atomic="true"></div>
    <script src="assets/js/auth.js"></script>
</body>
</html>
