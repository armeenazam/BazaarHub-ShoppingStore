<?php
$conn = mysqli_connect("localhost", "root", "", "bazaarhub");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>