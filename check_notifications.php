<?php
// --- เริ่มต้น Session และ Output Buffering ---
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
ob_start();

// --- Include ไฟล์ที่จำเป็น ---
require_once(__DIR__ . '/functions.php'); // ไฟล์รวมฟังก์ชันของคุณ
require_once(__DIR__ . '/../config/foodOrder.php'); // ไฟล์ Config หรือเชื่อมต่อ DB

// --- การตั้งค่า ---
$notificationLimit = 5; // จำนวนการแจ้งเตือนล่าสุดที่จะดึงมา
$defaultLastViewTime = 0; // ถ้าไม่เคยดู ถือว่าเป็น 0 เพื่อให้ notification ทั้งหมดเป็น new ในครั้งแรก

// --- ฟังก์ชันช่วยเหลือ (ถ้ายังไม่มีใน functions.php) ---
if (!function_exists('getStatusTextThai')) {
    function getStatusTextThai($status) {
        switch ($status) {
            case 'pending': return 'รอดำเนินการ';
            case 'processing': return 'กำลังเตรียมอาหาร';
            case 'completed': return 'เสร็จแล้ว';
            case 'cancelled': return 'ยกเลิก';
            default: return ucfirst($status);
        }
    }
}

if (!function_exists('timeAgoThai')) {
    function timeAgoThai($timestamp) {
        if ($timestamp <= 0) return "ไม่ระบุเวลา";
        $currentTime = time();
        $timeDiff = $currentTime - $timestamp;

        if ($timeDiff < 60) { return "เมื่อสักครู่"; }
        elseif ($timeDiff < 3600) { $minutes = round($timeDiff / 60); return $minutes . " นาทีที่แล้ว"; }
        elseif ($timeDiff < 86400) { $hours = round($timeDiff / 3600); return $hours . " ชั่วโมงที่แล้ว"; }
        elseif ($timeDiff < 604800) { $days = round($timeDiff / 86400); return $days . " วันที่แล้ว"; }
        else { return date('d/m/Y H:i', $timestamp); }
    }
}
// --- จบ Helper Functions ---

header('Content-Type: application/json'); // ตั้งค่า header เป็น JSON

$response = [
    'success' => false,
    'count' => 0,       // จำนวน notification ใหม่ (ก่อน mark read)
    'hasNew' => false,  // มีอันใหม่หรือไม่ (ก่อน mark read)
    'orders' => [],     // รายการ notification ล่าสุด
    'latestOrder' => null, // อันใหม่ล่าสุด (ก่อน mark read)
    'message' => ''
];

// 1. ตรวจสอบว่า User Login หรือยัง
if (!function_exists('isLoggedIn') || !isLoggedIn()) {
    $response['message'] = 'User not logged in.';
    ob_end_clean();
    echo json_encode($response);
    exit;
}

$userId = $_SESSION['user_id'];

// 2. ดึงเวลาที่ User ดู Notification ล่าสุดจาก Session
$lastViewTime = isset($_SESSION['last_notification_view_time']) ? (int)$_SESSION['last_notification_view_time'] : $defaultLastViewTime;

// 3. ตรวจสอบว่ามีการร้องขอ Mark as Read หรือไม่
$markAsRead = isset($_GET['markAsRead']) && $_GET['markAsRead'] == '1';

// 4. ดึงข้อมูล Order ล่าสุดจากฐานข้อมูล
$recentOrders = [];
// ใช้ฟังก์ชันที่คุณมี (ควรดึง items_text มาด้วยเพื่อ performance)
if (function_exists('getUserRecentOrdersWithDetails')) {
     $recentOrders = getUserRecentOrdersWithDetails($userId, $notificationLimit);
} elseif (function_exists('getUserRecentOrders')) {
    $recentOrders = getUserRecentOrders($userId, $notificationLimit);
} // เพิ่ม fallback อื่นๆ ถ้าจำเป็น

// 5. ประมวลผล Orders และนับ Notification ใหม่
$newNotificationsCount = 0;
$ordersForJson = [];
$latestNewOrder = null;
$latestTimestamp = 0; // เก็บ timestamp ล่าสุด

if (!empty($recentOrders)) {
    foreach ($recentOrders as $order) {
        $orderTimestamp = isset($order['updated_at']) ? strtotime($order['updated_at']) : (isset($order['created_at']) ? strtotime($order['created_at']) : 0);
        if ($orderTimestamp <= 0) continue;

        if ($orderTimestamp > $latestTimestamp) {
             $latestTimestamp = $orderTimestamp; // อัพเดท timestamp ล่าสุดที่เจอ
        }

        // **สำคัญ:** ตรวจสอบว่าเป็น "ใหม่" หรือไม่ โดยเทียบกับ *ก่อน* เวลาที่จะ mark read
        $isNew = ($orderTimestamp > $lastViewTime);

        if ($isNew) {
            $newNotificationsCount++;
            // เก็บ order ใหม่ล่าสุด (อันแรกที่เจอใน loop เพราะเรียงตามเวลาล่าสุดแล้ว)
            if ($latestNewOrder === null) {
                // เก็บข้อมูลดิบก่อน เพื่อ prepare ทีหลัง
                 $latestNewOrder = $order;
                 $latestNewOrder['calculated_timestamp'] = $orderTimestamp; // เก็บ timestamp ที่คำนวณแล้ว
            }
        }

        // เตรียมข้อมูลสำหรับ JSON response
        $orderData = [
            'id' => $order['id'],
            'status' => $order['status'],
            'is_new' => $isNew, // สถานะใหม่ *ก่อน* การ mark read ครั้งนี้
            'status_text' => getStatusTextThai($order['status']),
            'time_ago' => timeAgoThai($orderTimestamp),
            'items_text' => isset($order['items_text']) ? $order['items_text'] : 'รายการอาหาร...', // ใช้ที่ดึงมา หรือ default
             // ส่ง timestamp ไปด้วยเผื่อ JS อยากใช้เรียงลำดับเอง
            'timestamp' => $orderTimestamp
        ];

        // ถ้าไม่ได้ดึง items_text มาใน query แรก (ไม่แนะนำด้าน performance)
        if (!isset($order['items_text']) && function_exists('getOrderDetails')) {
             try {
                 $orderItems = getOrderDetails($order['id']);
                 if (!empty($orderItems)) {
                    $foodNames = array_column($orderItems, 'food_name');
                    $itemsSummary = implode(', ', array_slice($foodNames, 0, 2));
                    if (count($foodNames) > 2) { $itemsSummary .= ' และอื่นๆ'; }
                    $orderData['items_text'] = $itemsSummary;
                 }
             } catch (Exception $e) {
                 error_log("Error getting order details for order ID {$order['id']}: " . $e->getMessage());
                 $orderData['items_text'] = 'เกิดข้อผิดพลาดในการโหลดรายการ';
             }
        }

        $ordersForJson[] = $orderData;
    }
     // Prepare ข้อมูลสำหรับ latestNewOrder (ถ้ามี)
     if ($latestNewOrder !== null) {
         $latestNewOrder['status_text'] = getStatusTextThai($latestNewOrder['status']);
         // หา items_text สำหรับ latestNewOrder ด้วยวิธีเดียวกับใน loop
         if (!isset($latestNewOrder['items_text']) && function_exists('getOrderDetails')) {
             try {
                 $latestOrderItems = getOrderDetails($latestNewOrder['id']);
                  if (!empty($latestOrderItems)) {
                     $latestFoodNames = array_column($latestOrderItems, 'food_name');
                     $latestItemsSummary = implode(', ', array_slice($latestFoodNames, 0, 2));
                     if (count($latestFoodNames) > 2) { $latestItemsSummary .= ' และอื่นๆ'; }
                     $latestNewOrder['items_text'] = $latestItemsSummary;
                  } else { $latestNewOrder['items_text'] = 'รายการอาหาร...';}
             } catch (Exception $e) { $latestNewOrder['items_text'] = 'เกิดข้อผิดพลาด'; }
         } elseif (!isset($latestNewOrder['items_text'])) {
             $latestNewOrder['items_text'] = 'รายการอาหาร...';
         }
     }


}

// 6. อัปเดตเวลาที่ดู Notification ล่าสุดใน Session *หลังจาก* ประมวลผลเสร็จ
if ($markAsRead) {
    // อัปเดตเวลาที่ดู เป็น timestamp ล่าสุดของ order ที่เจอ หรือเวลาปัจจุบันถ้าไม่มี order
     $newLastViewTime = ($latestTimestamp > 0) ? $latestTimestamp : time();
    $_SESSION['last_notification_view_time'] = $newLastViewTime;

    $response['message'] = 'Notifications marked as read.';
    $newNotificationsCount = 0; // รีเซ็ต count สำหรับ response ครั้งนี้
    $latestNewOrder = null; // ไม่มีอันใหม่ล่าสุดแล้ว
    foreach ($ordersForJson as &$orderItem) {
        $orderItem['is_new'] = false; // ทุกอันไม่เป็น is_new แล้วใน response นี้
    }
    unset($orderItem);
}

// 7. สร้าง JSON Response สุดท้าย
$response['success'] = true;
$response['count'] = $newNotificationsCount; // จำนวนใหม่ *ก่อน* การ mark read (ถ้าไม่ได้ mark)
$response['hasNew'] = ($newNotificationsCount > 0 && !$markAsRead); // เป็น true ถ้ามีใหม่ และ *ไม่ได้* กำลัง mark read
$response['orders'] = $ordersForJson; // รายการ order ล่าสุด (พร้อมสถานะ is_new)
// ส่งเฉพาะข้อมูลที่จำเป็นสำหรับ latestOrder
$response['latestOrder'] = $latestNewOrder ? [
    'id' => $latestNewOrder['id'],
    'status' => $latestNewOrder['status'],
    'status_text' => $latestNewOrder['status_text'] ?? '',
    'items_text' => $latestNewOrder['items_text'] ?? '',
    'timestamp' => $latestNewOrder['calculated_timestamp'] ?? 0
] : null;


ob_end_clean(); // ล้าง output buffer ก่อนส่ง JSON
echo json_encode($response);
exit;

?>