<?php
require_once 'config/foodOrder.php';

$food_id = $_POST['food_id'];
$name = $_POST['name'];
$rating = $_POST['rating'];
$comment = $_POST['comment'];

$sql = "INSERT INTO reviews (food_id, name, rating, comment, created_at) VALUES (?, ?, ?, ?, NOW())";
$stmt = $pdo->prepare($sql);
$stmt->execute([$food_id, $name, $rating, $comment]);

header("Location: details/food-detail-api.php?id=" . $food_id);
exit;
