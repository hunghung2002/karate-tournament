<?php
// includes/db.php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'karate_tournament';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Kết nối CSDL thất bại: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
