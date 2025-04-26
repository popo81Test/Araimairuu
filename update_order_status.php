<?php
ob_clean(); // ล้าง buffer เผื่อมี whitespace หรือ warning ก่อนหน้านี้
header('Content-Type: application/json');

require_once 'includes/functions.php';
require_once 'config/foodOrder.php';

// ฟังก์ชันสำหรับ log ข้อความ 
function log_message($message) {
    $logFile = 'error.log'; 
    $timestamp = date('Y-m-d H:i:s');
    $debugTrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
    $caller = isset($debugTrace[0]['file']) ? basename($debugTrace[0]['file']) : 'unknown';
    $line = isset($debugTrace[0]['line']) ? $debugTrace[0]['line'] : 'unknown';
    error_log("[{$timestamp}] [{$caller}:{$line}] {$message}\n", 3, $logFile);
}

if (!isAdmin()) {
    log_message('Unauthorized access attempt to update_order_status.php');
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    log_message('Invalid request method: ' . $_SERVER['REQUEST_METHOD']);
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$orderId = isset($data['order_id']) ? (int)$data['order_id'] : 0;
$status = isset($data['status']) ? sanitize($data['status']) : '';

log_message("Received request to update order ID: {$orderId} to status: {$status}");

if (!$orderId || empty($status)) {
    log_message("Invalid parameters: order_id={$orderId}, status={$status}");
    echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
    exit;
}


$stmt = $conn->prepare("UPDATE orders SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
if ($stmt) {
    $stmt->bind_param("si", $status, $orderId);
    if ($stmt->execute()) {
        log_message("Order ID: {$orderId} updated successfully to status: {$status}");
        echo json_encode(['success' => true]);
    } else {
        $errorMessage = 'Execute failed: ' . $stmt->error;
        log_message("Error updating order ID: {$orderId}. {$errorMessage}");
        echo json_encode(['success' => false, 'message' => $errorMessage]);
    }
    $stmt->close();
} else {
    $errorMessage = 'Prepare failed: ' . $conn->error;
    log_message("Error preparing statement for order ID: {$orderId}. {$errorMessage}");
    echo json_encode(['success' => false, 'message' => $errorMessage]);
}


?>