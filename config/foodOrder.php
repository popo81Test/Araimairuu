<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'food_ordering';

$conn = mysqli_connect($host, $user, $password, $database);


if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


mysqli_set_charset($conn, "utf8");
?>