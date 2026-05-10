<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$dbHost = '127.0.0.1';
$dbUser = 'root';
$dbPass = '';
$dbName = 'bazaarhub';

try {
    // Connect to the MySQL server first so we can create/select the database safely.
    $conn = mysqli_connect($dbHost, $dbUser, $dbPass);
    mysqli_set_charset($conn, 'utf8mb4');

    mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    mysqli_select_db($conn, $dbName);

    // If the schema has not been imported yet, stop with a clear setup message instead of a fatal error.
    $tableCheck = mysqli_query($conn, "SHOW TABLES LIKE 'users'");
    if (mysqli_num_rows($tableCheck) === 0) {
        die("BazaarHub database is present, but the schema is not initialized yet. Import database.sql into the bazaarhub database, then reload the app.");
    }

    mysqli_query($conn, "
        CREATE TABLE IF NOT EXISTS password_reset_tokens (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            token_hash VARCHAR(255) NOT NULL,
            expires_at DATETIME NOT NULL,
            used_at DATETIME NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_password_reset_user (user_id),
            INDEX idx_password_reset_hash (token_hash),
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )
    ");
} catch (mysqli_sql_exception $e) {
    die("Database connection failed: " . $e->getMessage());
}
