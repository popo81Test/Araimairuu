<?php
require_once '../config/foodOrder.php';
header('Content-Type: application/json');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$stmt = $conn->prepare("SELECT * FROM foods WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$food = $result->fetch_assoc();

if (!$food) {
    echo json_encode(['error' => 'ไม่พบเมนู']);
    exit;
}

// ดึงรีวิว
$reviewStmt = $conn->prepare("SELECT name, rating, comment FROM reviews WHERE food_id = ? ORDER BY created_at DESC");
$reviewStmt->bind_param("i", $id);
$reviewStmt->execute();
$reviewResult = $reviewStmt->get_result();

$reviews = [];
while ($row = $reviewResult->fetch_assoc()) {
    $reviews[] = $row;
}

// ใส่รีวิวในข้อมูลอาหาร
$food['reviews'] = $reviews;

echo json_encode($food);
