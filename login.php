<?php $page_title = "BazaarHub - Sign In"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/auth.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
</head>

<body class="auth-page">
    <div class="bg-pattern" aria-hidden="true"></div>
    <div class="page-glow page-glow--top" aria-hidden="true"></div>
    <div class="page-glow page-glow--bottom" aria-hidden="true"></div>

    <main class="auth-main">
        <section class="auth-card" data-auth-shell data-mode="signin" aria-label="BazaarHub authentication">
            <aside class="brand-panel">
                <div class="brand-content">
                    <div class="brand-header">
                        <h1 class="brand-logo">bazaarhub</h1>
                        <p class="brand-tagline">Style. Choice. Connection.</p>
                        <div class="tagline-icon">
                            <div class="line"></div>
                            <span class="material-symbols-outlined">spa</span>
                            <div class="line"></div>
                        </div>
                    </div>

                    <div class="brand-description">
                        <div class="features">
                            <div class="feature">
                                <span class="material-symbols-outlined">apparel</span>
                                <p>Discover unique styles</p>
                            </div>
                            <div class="feature">
                                <span class="material-symbols-outlined">shopping_bag</span>
                                <p>Shop from multiple sellers</p>
                            </div>
                            <div class="feature">
                                <span class="material-symbols-outlined">shield_lock</span>
                                <p>Safe & secure shopping</p>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            <section class="auth-card-shell">
                <div class="auth-card-inner">
                    <div class="auth-tabs" role="tablist" aria-label="Authentication tabs">
                        <button class="auth-tab is-active" id="signin-tab" type="button" role="tab" aria-selected="true" data-tab-target="signin">Sign In</button>
                        <button class="auth-tab" id="signup-tab" type="button" role="tab" aria-selected="false" data-tab-target="signup">Sign Up</button>
                    </div>

                    <div class="auth-panel is-active" data-panel="signin" role="tabpanel" aria-labelledby="signin-tab">
                        <form class="auth-form" data-mock-form="signin" novalidate>
                            <div class="field">
                                <div class="field__control">
                                    <span class="material-symbols-outlined field__icon">mail</span>
                                    <input id="signin-email" name="email" type="email" placeholder="Email address" required>
                                </div>
                            </div>

                            <div class="field">
                                <div class="field__control field__control--password">
                                    <span class="material-symbols-outlined field__icon">lock</span>
                                    <input id="signin-password" name="password" type="password" placeholder="Password" required>
                                    <button class="password-toggle" type="button" data-password-toggle aria-label="Show password">
                                        <svg class="eye-open" viewBox="0 0 24 24">
                                            <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" />
                                        </svg>
                                        <svg class="eye-closed" viewBox="0 0 24 24">
                                            <path d="M12 7c2.76 0 5 2.24 5 5 0 .65-.13 1.26-.36 1.83l2.92 2.92c1.51-1.26 2.7-2.89 3.43-4.75-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-3.98.7l2.16 2.16C10.74 7.13 11.35 7 12 7zM2 4.27l2.28 2.28.46.46C3.08 8.3 1.78 10.02 1 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.42.42L19.73 22 21 20.73 3.27 3 2 4.27zM7.53 9.8l1.55 1.55c-.05.21-.08.43-.08.65 0 1.66 1.34 3 3 3 .22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53-2.76 0-5-2.24-5-5 0-.79.2-1.53.53-2.2zm4.31-.78l3.15 3.15.02-.16c0-1.66-1.34-3-3-3l-.17.01z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="form-row">
                                <label class="checkbox">
                                    <input type="checkbox" name="remember_me">
                                    <span class="checkbox-mark"></span>
                                    <span>Remember me</span>
                                </label>
                                <a class="text-link" href="forgot-password.php">Forgot password?</a>
                            </div>

                            <button class="submit-button" type="submit" data-loading-text="Signing In">Sign In</button>
                            <div class="divider-row">
                                <div class="divider"></div>
                                <span>or</span>
                                <div class="divider"></div>
                            </div>
                            <button class="google-button" type="button" data-social="Google" aria-label="Continue with Google">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path fill="#EA4335" d="M12 10.2v3.8h5.4c-.2 1.2-.9 2.3-1.9 3l3 2.4c1.7-1.6 2.7-4 2.7-6.8 0-.7-.1-1.3-.2-1.9H12z" />
                                    <path fill="#34A853" d="M6.5 14.3l-.7.5-2.6 2c1.7 3.4 5.2 5.7 9.1 5.7 2.7 0 5-.9 6.7-2.4l-3.2-2.5c-.9.6-2.1 1-3.5 1-2.7 0-5-1.8-5.8-4.3z" />
                                    <path fill="#FBBC05" d="M3.2 8.1c-.7 1.4-1.1 3-1.1 4.7s.4 3.3 1.1 4.7l3.3-2.6c-.2-.7-.3-1.3-.3-2.1s.1-1.5.3-2.1L3.2 8.1z" />
                                    <path fill="#4285F4" d="M12 5.3c1.5 0 2.8.5 3.8 1.4l2.9-2.9C17.1 2.2 14.8 1.3 12 1.3 8.1 1.3 4.6 3.6 3 7l3.3 2.6c.8-2.5 3.1-4.3 5.7-4.3z" />
                                </svg>
                                <span>Continue with Google</span>
                            </button>
                        </form>
                    </div>

                    <div class="auth-panel" data-panel="signup" role="tabpanel" aria-labelledby="signup-tab" hidden>
                        <form class="auth-form" data-mock-form="signup" novalidate>
                            <div class="field-row">
                                <div class="field">
                                    <div class="field__control">
                                        <span class="material-symbols-outlined field__icon">person</span>
                                        <input id="signup-firstname" name="first_name" type="text" placeholder="First name" required>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="field__control">
                                        <span class="material-symbols-outlined field__icon">person</span>
                                        <input id="signup-lastname" name="last_name" type="text" placeholder="Last name" required>
                                    </div>
                                </div>
                            </div>

                            <div class="field">
                                <div class="field__control">
                                    <span class="material-symbols-outlined field__icon">mail</span>
                                    <input id="signup-email" name="email" type="email" placeholder="Email address" required>
                                </div>
                            </div>

                            <div class="field">
                                <div class="field__control field__control--password">
                                    <span class="material-symbols-outlined field__icon">lock</span>
                                    <input id="signup-password" name="password" type="password" placeholder="Create password" required>
                                    <button class="password-toggle" type="button" data-password-toggle aria-label="Show password">
                                        <svg class="eye-open" viewBox="0 0 24 24">
                                            <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" />
                                        </svg>
                                        <svg class="eye-closed" viewBox="0 0 24 24">
                                            <path d="M12 7c2.76 0 5 2.24 5 5 0 .65-.13 1.26-.36 1.83l2.92 2.92c1.51-1.26 2.7-2.89 3.43-4.75-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-3.98.7l2.16 2.16C10.74 7.13 11.35 7 12 7zM2 4.27l2.28 2.28.46.46C3.08 8.3 1.78 10.02 1 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.42.42L19.73 22 21 20.73 3.27 3 2 4.27zM7.53 9.8l1.55 1.55c-.05.21-.08.43-.08.65 0 1.66 1.34 3 3 3 .22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53-2.76 0-5-2.24-5-5 0-.79.2-1.53.53-2.2zm4.31-.78l3.15 3.15.02-.16c0-1.66-1.34-3-3-3l-.17.01z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="field">
                                <div class="field__control field__control--password">
                                    <span class="material-symbols-outlined field__icon">lock</span>
                                    <input id="signup-confirm-password" name="confirm_password" type="password" placeholder="Confirm password" required>
                                    <button class="password-toggle" type="button" data-password-toggle aria-label="Show password">
                                        <svg class="eye-open" viewBox="0 0 24 24">
                                            <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" />
                                        </svg>
                                        <svg class="eye-closed" viewBox="0 0 24 24">
                                            <path d="M12 7c2.76 0 5 2.24 5 5 0 .65-.13 1.26-.36 1.83l2.92 2.92c1.51-1.26 2.7-2.89 3.43-4.75-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-3.98.7l2.16 2.16C10.74 7.13 11.35 7 12 7zM2 4.27l2.28 2.28.46.46C3.08 8.3 1.78 10.02 1 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.42.42L19.73 22 21 20.73 3.27 3 2 4.27zM7.53 9.8l1.55 1.55c-.05.21-.08.43-.08.65 0 1.66 1.34 3 3 3 .22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53-2.76 0-5-2.24-5-5 0-.79.2-1.53.53-2.2zm4.31-.78l3.15 3.15.02-.16c0-1.66-1.34-3-3-3l-.17.01z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="field">
                                <div class="field__control">
                                    <span class="material-symbols-outlined field__icon">phone_enabled</span>
                                    <input id="signup-phone" name="phone" type="tel" placeholder="Phone number">
                                </div>
                            </div>

                            <div class="field">
                                <div class="field__control">
                                    <span class="material-symbols-outlined field__icon">location_on</span>
                                    <input id="signup-city" name="city" type="text" placeholder="City">
                                </div>
                            </div>

                            <div class="field">
                                <div class="field__control">
                                    <span class="material-symbols-outlined field__icon">add_home</span>
                                    <input id="signup-address" name="address" type="text" placeholder="Address">
                                </div>
                            </div>

                            <div class="role-selection">
                                <p class="role-label">I want to:</p>
                                <div class="role-options">
                                    <label class="role-option">
                                        <input type="radio" name="role" value="buyer" checked>
                                        <span class="role-card">
                                            <span class="material-symbols-outlined role-icon">shopping_bag</span>
                                            <span class="role-text">Register as Buyer</span>
                                            <span class="role-desc">Shop from multiple trusted sellers</span>
                                        </span>
                                    </label>
                                    <label class="role-option">
                                        <input type="radio" name="role" value="seller">
                                        <span class="role-card">
                                            <span class="material-symbols-outlined role-icon">storefront</span>
                                            <span class="role-text">Register as Seller</span>
                                            <span class="role-desc">Sell products to thousands of buyers</span>
                                        </span>
                                    </label>
                                </div>
                            </div>

                            <button class="submit-button" type="submit" data-loading-text="Creating Account">Sign Up</button>
                            <div class="divider-row">
                                <div class="divider"></div>
                                <span>or</span>
                                <div class="divider"></div>
                            </div>
                            <button class="google-button" type="button" data-social="Google" aria-label="Continue with Google">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path fill="#EA4335" d="M12 10.2v3.8h5.4c-.2 1.2-.9 2.3-1.9 3l3 2.4c1.7-1.6 2.7-4 2.7-6.8 0-.7-.1-1.3-.2-1.9H12z" />
                                    <path fill="#34A853" d="M6.5 14.3l-.7.5-2.6 2c1.7 3.4 5.2 5.7 9.1 5.7 2.7 0 5-.9 6.7-2.4l-3.2-2.5c-.9.6-2.1 1-3.5 1-2.7 0-5-1.8-5.8-4.3z" />
                                    <path fill="#FBBC05" d="M3.2 8.1c-.7 1.4-1.1 3-1.1 4.7s.4 3.3 1.1 4.7l3.3-2.6c-.2-.7-.3-1.3-.3-2.1s.1-1.5.3-2.1L3.2 8.1z" />
                                    <path fill="#4285F4" d="M12 5.3c1.5 0 2.8.5 3.8 1.4l2.9-2.9C17.1 2.2 14.8 1.3 12 1.3 8.1 1.3 4.6 3.6 3 7l3.3 2.6c.8-2.5 3.1-4.3 5.7-4.3z" />
                                </svg>
                                <span>Continue with Google</span>
                            </button>
                        </form>
                    </div>
                </div>
            </section>
        </section>
    </main>

    <div class="toast-host" aria-live="polite" aria-atomic="true"></div>
    <script src="assets/js/auth.js"></script>
</body>

</html>
