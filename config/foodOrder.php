<?php
// Database connection details
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'food_ordering';

// Create connection
$conn = mysqli_connect($host, $user, $password, $database);



// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to utf8
mysqli_set_charset($conn, "utf8");
?>