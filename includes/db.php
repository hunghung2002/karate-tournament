<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "web_tran_dau";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>