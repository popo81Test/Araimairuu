<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


require_once(__DIR__ . '/includes/functions.php'); 
require_once(__DIR__ . '/config/foodOrder.php'); 

function log_notification_message($message) {
    $logFile = __DIR__ . '/notification.log';
    $timestamp = date('Y-m-d H:i:s');
    error_log("[{$timestamp}] {$message}\n", 3, $logFile);
}


$response = [
    'success' => false,
    'message' => '',
    'count' => 0,
    'hasNew' => false,
    'orders' => [],
    'latestOrderStatus' => '' 
];

// Check if user is logged in
if (!function_exists('isLoggedIn') || !isLoggedIn()) {
    $response['message'] = 'User not logged in.';
    log_notification_message("Attempt to check notifications without login.");
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Check for user_id in session
if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'User not authenticated.';
    log_notification_message("User ID not found in session.");
    header('Content-Type: application/json');
    http_response_code(401); 
    echo json_encode($response);
    exit;
}

$userId = $_SESSION['user_id'];
$notificationLimit = 5; 


$lastViewTime = 0; 

if (isset($_SESSION['last_notification_view_time']) && $_SESSION['last_notification_view_time'] > 0) {
    $lastViewTime = (int)$_SESSION['last_notification_view_time'];
    
} else {
    
    $fetchSql = "SELECT last_notification_view_time FROM users WHERE id = ?";
    $fetchStmt = $conn->prepare($fetchSql);
    if ($fetchStmt) {
        $fetchStmt->bind_param("i", $userId);
        if ($fetchStmt->execute()) {
            $result = $fetchStmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $dbTime = (int)$row['last_notification_view_time'];
                if ($dbTime > 0) {
                    $lastViewTime = $dbTime;
                    $_SESSION['last_notification_view_time'] = $lastViewTime; 
                    
                } else {
                     
                     if (!isset($_SESSION['last_notification_view_time'])) { $_SESSION['last_notification_view_time'] = 0; }
                }
            } else {
                
                if (!isset($_SESSION['last_notification_view_time'])) { $_SESSION['last_notification_view_time'] = 0; }
            }
        } else {
            log_notification_message("User $userId: Failed to execute fetch lastViewTime query: " . $fetchStmt->error);
            if (!isset($_SESSION['last_notification_view_time'])) { $_SESSION['last_notification_view_time'] = 0; }
        }
        $fetchStmt->close();
    } else {
         log_notification_message("User $userId: Failed to prepare statement for fetching user notification time: " . $conn->error);
         if (!isset($_SESSION['last_notification_view_time'])) { $_SESSION['last_notification_view_time'] = 0; }
    }
}


if (isset($_GET['markAsRead']) && $_GET['markAsRead'] == '1') {
    log_notification_message("User $userId: Marking notifications as read.");

    $latestOrderTime = $lastViewTime; 
    $ordersToConsiderForMarking = [];

    if (function_exists('isAdmin') && isAdmin()) {
        // Admin:
        $adminMarkStmt = $conn->prepare("SELECT MAX(created_at) as max_created, MAX(updated_at) as max_updated FROM (SELECT created_at, updated_at FROM orders ORDER BY created_at DESC LIMIT ?) as recent");
        if($adminMarkStmt) {
            $adminMarkStmt->bind_param("i", $notificationLimit);
             if($adminMarkStmt->execute()) {
                 $markResult = $adminMarkStmt->get_result();
                 if ($row = $markResult->fetch_assoc()) {
                     $maxCreated = isset($row['max_created']) ? strtotime($row['max_created']) : 0;
                     $maxUpdated = isset($row['max_updated']) ? strtotime($row['max_updated']) : 0;
                     $latestOrderTime = max($latestOrderTime, $maxCreated, $maxUpdated);
                 }
             } else {
                log_notification_message("Admin $userId: Failed execute query to find latest order time: " . $adminMarkStmt->error);
             }
            $adminMarkStmt->close();
        } else {
            log_notification_message("Admin $userId: Failed prepare query to find latest order time: " . $conn->error);
        }

    } else {
        // Non-Admin:
        if (function_exists('getUserRecentOrdersWithDetails')) {
             $ordersToConsiderForMarking = getUserRecentOrdersWithDetails($userId, $notificationLimit);
             foreach ($ordersToConsiderForMarking as $order) {
                 $timestamp = isset($order['updated_at']) ? strtotime($order['updated_at']) : (isset($order['created_at']) ? strtotime($order['created_at']) : 0);
                 if ($timestamp > $latestOrderTime) {
                     $latestOrderTime = $timestamp;
                 }
             }
        }
    }


    log_notification_message("User $userId: Determined latestOrderTime for marking as read: " . ($latestOrderTime > 0 ? date('Y-m-d H:i:s', $latestOrderTime) : '0'));

    // Update session and database
    $_SESSION['last_notification_view_time'] = $latestOrderTime;

    $updateSql = "UPDATE users SET last_notification_view_time = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateSql);
    if ($updateStmt) {
        $updateStmt->bind_param("ii", $latestOrderTime, $userId);
        if($updateStmt->execute()){
            log_notification_message("User $userId: Successfully updated last_notification_view_time in DB.");
        } else {
            log_notification_message("User $userId: Failed to execute DB update for last_notification_view_time: " . $updateStmt->error);
        }
        $updateStmt->close();
    } else {
        log_notification_message("User $userId: Failed to prepare statement for updating user notification time: " . $conn->error);
    }

    $response['success'] = true;
    $response['message'] = 'Notifications marked as read.';
    $response['hasNew'] = false; // After marking, nothing is new relative to the new time

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}


// === Fetch Recent Orders for Display ===
$recentOrders = [];
$newNotificationsCount = 0;
$ordersForJson = [];

if (function_exists('isAdmin') && isAdmin()) {
    log_notification_message("User $userId (Admin): Fetching all recent orders.");
    // Admin: ดึงออเดอร์ทั้งหมด
    $adminStmt = $conn->prepare("
        SELECT o.id, o.user_id, o.status, o.created_at, o.updated_at, u.username
        FROM orders o
        JOIN users u ON o.user_id = u.id
        ORDER BY o.created_at DESC
        LIMIT ?
    ");
     if ($adminStmt) {
        $adminStmt->bind_param("i", $notificationLimit);
        if ($adminStmt->execute()) {
            $adminResult = $adminStmt->get_result();
            while ($row = $adminResult->fetch_assoc()) {
                $recentOrders[] = $row;
            }
        } else {
             log_notification_message("Admin $userId: Failed to execute query for all recent orders: " . $adminStmt->error);
        }
        $adminStmt->close();
    } else {
        log_notification_message("Admin $userId: Failed to prepare query for all recent orders: " . $conn->error);
    }

} else {
    log_notification_message("User $userId (Non-Admin): Fetching user-specific recent orders.");
    // Non-Admin: just his own order fetch by username (id)
    if (function_exists('getUserRecentOrdersWithDetails')) {
         $recentOrders = getUserRecentOrdersWithDetails($userId, $notificationLimit);
    } else {
         log_notification_message("User $userId: Function getUserRecentOrdersWithDetails does not exist.");
    }
}


log_notification_message("User $userId: Processing notifications. Comparing against lastViewTime: " . ($lastViewTime > 0 ? date('Y-m-d H:i:s', $lastViewTime) : '0'));

// Process fetched orders
foreach ($recentOrders as $order) {
    // Use created_at primarily for "new order" notification, updated_at for status changes
    $orderTimestamp = isset($order['created_at']) ? strtotime($order['created_at']) : 0; // Timestamp for comparison
     $updateTimestamp = isset($order['updated_at']) ? strtotime($order['updated_at']) : 0; // Timestamp for display 'time ago'

     // Determine the most relevant timestamp for display and comparison
     $relevantTimestamp = max($orderTimestamp, $updateTimestamp);

    if ($relevantTimestamp <= 0) continue; // Skip if no valid timestamp

    $isNew = ($relevantTimestamp > $lastViewTime);
    if ($isNew) {
        $newNotificationsCount++;
        log_notification_message("User $userId: Order ID {$order['id']} (Time: " . date('Y-m-d H:i:s', $relevantTimestamp) . ") is NEW relative to last view.");
    }

    // Get order item details (assuming getOrderDetails exists and works)
    $itemsText = "รายการอาหารของคุณ"; // Default text
    if (function_exists('getOrderDetails') && isset($order['id'])) {
        $orderItems = getOrderDetails($order['id']); // This needs order ID
        if (!empty($orderItems)) {
            $foodNames = array_map(function($item) {
                return isset($item['food_name']) ? htmlspecialchars($item['food_name']) : 'สินค้า';
            }, $orderItems);
            $itemsText = implode(', ', array_slice($foodNames, 0, 2)); // Show first 2 items
            if (count($foodNames) > 2) {
                $itemsText .= ' และอื่นๆ';
            }
        }
    }

    // Calculate time ago based on the most recent activity (update or creation)
    $timeAgo = '';
    $currentTime = time();
    $timeDiffMinutes = round(($currentTime - $relevantTimestamp) / 60);

    if ($timeDiffMinutes < 1) { $timeAgo = "เมื่อสักครู่"; }
    elseif ($timeDiffMinutes < 60) { $timeAgo = $timeDiffMinutes . " นาทีที่แล้ว"; }
    elseif ($timeDiffMinutes < 1440) { $timeAgo = round($timeDiffMinutes / 60) . " ชั่วโมงที่แล้ว"; }
    else { $timeAgo = round($timeDiffMinutes / 1440) . " วันที่แล้ว"; }
    // Alternatively show full date: $timeAgo = date('d/m/Y H:i', $relevantTimestamp);

    $orderData = [
        'id' => $order['id'],
        'status' => $order['status'],
        'status_text' => function_exists('getOrderStatusText') ? getOrderStatusText($order['status']) : ucfirst($order['status']),
        'items_text' => $itemsText,
        'time_ago' => $timeAgo,
        'timestamp' => $relevantTimestamp, // Send the relevant timestamp
        'is_new_flag' => $isNew,
        // *** MODIFICATION FOR ADMIN START ***
        'username' => (isset($order['username']) && isAdmin()) ? htmlspecialchars($order['username']) : null // Add username only if admin and available
       
    ];

    $ordersForJson[] = $orderData;
}


if (!isAdmin() && function_exists('getUserLatestOrderStatus')) {
     $response['latestOrderStatus'] = getUserLatestOrderStatus($userId);
} else if (isAdmin()) {
     
     if (!empty($recentOrders)) {
        $latestAdminOrderStatus = $recentOrders[0]['status'];
        $response['latestOrderStatus'] = "ออเดอร์ล่าสุด (#".$recentOrders[0]['id']."): " . (function_exists('getOrderStatusText') ? getOrderStatusText($latestAdminOrderStatus) : ucfirst($latestAdminOrderStatus));
     } else {
        $response['latestOrderStatus'] = "ยังไม่มีออเดอร์ล่าสุด";
     }
}



$response['success'] = true;
$response['count'] = $newNotificationsCount;
$response['hasNew'] = ($newNotificationsCount > 0);
$response['orders'] = $ordersForJson;


log_notification_message("User $userId: Sending response. New count: $newNotificationsCount, Has new: " . ($response['hasNew'] ? 'true' : 'false'));

header('Content-Type: application/json; charset=utf-8'); 
echo json_encode($response, JSON_UNESCAPED_UNICODE); 
exit;
?>
