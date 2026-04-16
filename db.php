<?php
$conn = mysqli_connect("localhost", "root", "", "bazaarhub");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>