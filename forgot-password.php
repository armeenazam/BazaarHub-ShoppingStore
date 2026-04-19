<?php $page_title = "Forgot Password | BazaarHub"; ?>
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
        <section class="utility-card">
            <p class="eyebrow">Password Recovery</p>
            <h1>Forgot your password?</h1>
            <p class="muted-copy">Enter the email tied to your BazaarHub account and we will send a reset link right away.</p>

            <form class="auth-form" data-mock-form="forgot-password" novalidate>
                <div class="field">
                    <label for="recovery-email">Email address</label>
                    <div class="field__control">
                        <span class="field__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24"><path d="M4 6h16a2 2 0 0 1 2 2v.4l-10 5.6L2 8.4V8a2 2 0 0 1 2-2zm18 4.7V16a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-5.3l9.5 5.3a1 1 0 0 0 1 0L22 10.7z"/></svg>
                        </span>
                        <input id="recovery-email" name="email" type="email" placeholder="you@example.com" required>
                    </div>
                    <p class="field__message">We will only use this to send your password reset instructions.</p>
                </div>

                <button class="submit-button" type="submit" data-loading-text="Sending Link">Send Reset Link</button>
            </form>

            <div class="utility-actions">
                <a class="text-link" href="login.php">Back to sign in</a>
                <a class="text-link" href="reset-password.php">Preview reset page</a>
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
