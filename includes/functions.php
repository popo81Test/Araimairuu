<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Function to check if user is admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Function to redirect to a page
function redirect($location) {
    if (!headers_sent()) {
        header("Location: $location");
        exit;
    } else {
        echo "<script>window.location.href='$location';</script>";
        exit;
    }
}

// Function to sanitize input data
function sanitize($data) {
    global $conn;
    return mysqli_real_escape_string($conn, trim($data));
}

// Function to encrypt password using MD5
function encryptPassword($password) {
    return md5($password);
}

// Function to display error message
function showError($message) {
    return '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
              <span class="block sm:inline">' . $message . '</span>
            </div>';
}

// Function to display success message
function showSuccess($message) {
    return '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
              <span class="block sm:inline">' . $message . '</span>
            </div>';
}

// Function to display food items as cards
function displayFoodCard($food) {
    $id = $food['id'];
    $name = $food['name'];
    $description = $food['description'] ?? '';
    $price = $food['price'];
    $image = $food['image'] ?? 'https://via.placeholder.com/150?text=อาหาร';

    $output = '<div class="bg-white rounded-lg shadow-md overflow-hidden">';
    $output .= '<img src="' . $image . '" alt="' . $name . '" class="w-full h-48 object-cover">';
    $output .= '<div class="p-4">';
    $output .= '<h3 class="text-lg font-semibold">' . $name . '</h3>';
    $output .= '<p class="text-gray-600 text-sm mb-2">' . $description . '</p>';
    $output .= '<div class="flex justify-between items-center">';
    $output .= '<span class="text-lg font-bold text-primary">฿' . number_format($price, 2) . '</span>';
    $output .= '<a href="product-action.php?action=view&id=' . $id . '" class="bg-primary text-white px-4 py-2 rounded hover:bg-amber-600 transition">สั่งเลย</a>';
    $output .= '</div></div></div>';

    return $output;
}

// Function to get cart total
function getCartTotal() {
    $total = 0;
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $subtotal = $item['price'] * $item['quantity'];

            // Add addon prices
            if (isset($item['addons']) && !empty($item['addons'])) {
                foreach ($item['addons'] as $addon) {
                    $subtotal += $addon['price'];
                }
            }

            $total += $subtotal;
        }
    }
    return $total;
}

// Function to get cart item count
function getCartItemCount() {
    $count = 0;
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $count += $item['quantity'];
        }
    }
    return $count;
}

// Function to get food by ID
function getFoodById($id) {
    global $conn;
    $id = sanitize($id);
    $query = "SELECT * FROM foods WHERE id = $id";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }

    return null;
}

// Function to get all food categories
function getAllCategories() {
    global $conn;
    $query = "SELECT * FROM categories ORDER BY name";
    $result = mysqli_query($conn, $query);

    $categories = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
    }

    return $categories;
}

// Function to get all foods
function getAllFoods() {
    global $conn;
    $query = "SELECT * FROM foods ORDER BY name";
    $result = mysqli_query($conn, $query);

    $foods = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $foods[] = $row;
    }

    return $foods;
}

// Function to get foods by category
function getFoodsByCategory($categoryId) {
    global $conn;
    $categoryId = sanitize($categoryId);
    $query = "SELECT * FROM foods WHERE category_id = $categoryId ORDER BY name";
    $result = mysqli_query($conn, $query);

    $foods = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $foods[] = $row;
    }

    return $foods;
}

// Function to get addon options by type
function getAddonsByType($type) {
    global $conn;
    $type = sanitize($type);
    $query = "SELECT * FROM addon_options WHERE type = '$type'";
    $result = mysqli_query($conn, $query);

    $addons = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $addons[] = $row;
    }

    return $addons;
}

// Function to create a new order
function createOrder($userId, $items, $totalPrice) {
    global $conn;

    // Start transaction
    mysqli_begin_transaction($conn);

    try {
        // Create order
        $userId = sanitize($userId);
        $totalPrice = sanitize($totalPrice);

        $query = "INSERT INTO orders (user_id, total_price, status) 
                  VALUES ('$userId', '$totalPrice', 'pending')";

        if (!mysqli_query($conn, $query)) {
            throw new Exception("Error creating order: " . mysqli_error($conn));
        }

        $orderId = mysqli_insert_id($conn);

        // Insert order items
        foreach ($items as $item) {
            $foodId = sanitize($item['id']);
            $quantity = sanitize($item['quantity']);
            $price = sanitize($item['price']);
            $specialInstructions = isset($item['special_instructions']) ? sanitize($item['special_instructions']) : '';

            $query = "INSERT INTO order_items (order_id, food_id, quantity, price, special_instructions) 
                      VALUES ('$orderId', '$foodId', '$quantity', '$price', '$specialInstructions')";

            if (!mysqli_query($conn, $query)) {
                throw new Exception("Error creating order item: " . mysqli_error($conn));
            }

            $orderItemId = mysqli_insert_id($conn);

            // Insert order item addons
            if (isset($item['addons']) && !empty($item['addons'])) {
                foreach ($item['addons'] as $addon) {
                    $addonId = sanitize($addon['id']);
                    $addonPrice = sanitize($addon['price']);

                    $query = "INSERT INTO order_item_addons (order_item_id, addon_id, price) 
                              VALUES ('$orderItemId', '$addonId', '$addonPrice')";

                    if (!mysqli_query($conn, $query)) {
                        throw new Exception("Error creating order item addon: " . mysqli_error($conn));
                    }
                }
            }
        }

        // Commit transaction
        mysqli_commit($conn);

        return $orderId;
    } catch (Exception $e) {
        // Rollback transaction on error
        mysqli_rollback($conn);
        throw $e;
    }
}


function getUserOrders($userId) {
    global $conn;
    $userId = sanitize($userId);

    if (isAdmin()) {
        $query = "SELECT o.*, u.username FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC";
    } else {
        $query = "SELECT * FROM orders WHERE user_id = '$userId' ORDER BY created_at DESC";
    }
    
    $result = mysqli_query($conn, $query);

    $orders = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $orders[] = $row;
    }

    return $orders;
}

// Function to get order details
function getOrderDetails($orderId) {
    global $conn;
    $orderId = sanitize($orderId);

    $query = "SELECT oi.*, f.name as food_name, f.image as food_image 
              FROM order_items oi 
              JOIN foods f ON oi.food_id = f.id 
              WHERE oi.order_id = '$orderId'";

    $result = mysqli_query($conn, $query);

    $items = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $orderItemId = $row['id'];
        $addonQuery = "SELECT oia.*, ao.name as addon_name, ao.type as addon_type 
                      FROM order_item_addons oia 
                      JOIN addon_options ao ON oia.addon_id = ao.id 
                      WHERE oia.order_item_id = '$orderItemId'";

        $addonResult = mysqli_query($conn, $addonQuery);

        $addons = [];
        while ($addonRow = mysqli_fetch_assoc($addonResult)) {
            $addons[] = $addonRow;
        }

        $row['addons'] = $addons;
        $items[] = $row;
    }

    return $items;
}

function updateUserLastNotificationViewTime($userId) {
    global $conn;
    $currentTime = time();
    
    // เก็บเวลาลงในฐานข้อมูล
    $sql = "UPDATE users SET last_notification_view_time = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $currentTime, $userId);
    $result = $stmt->execute();
    
    // อัพเดต session ด้วย
    if ($result) {
        $_SESSION['last_notification_view_time'] = $currentTime;
    }
    
    return $result;
}

/**
 * ดึงเวลาที่ผู้ใช้ดูการแจ้งเตือนล่าสุด
 * @param int $userId รหัสผู้ใช้
 * @return int เวลาล่าสุดที่ดูการแจ้งเตือน (timestamp)
 */
function getUserLastNotificationViewTime($userId) {
    global $conn;
    
    // ถ้ามีค่าใน session ให้ใช้ค่านั้น
    if (isset($_SESSION['last_notification_view_time'])) {
        return $_SESSION['last_notification_view_time'];
    }
    
    // ถ้าไม่มีให้ดึงจากฐานข้อมูล
    $sql = "SELECT last_notification_view_time FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $time = (int)$row['last_notification_view_time'];
        $_SESSION['last_notification_view_time'] = $time; // เก็บลง session
        return $time;
    }
    
    // ถ้าไม่มีค่าให้ return 0
    return 0;
}

/* เพิ่มฟังก์ชันสำหรับนับแจ้งเตือนใหม่ */
function countNewNotifications($userId) {
    $newCount = 0;
    
    // ดึงเวลาที่ดูการแจ้งเตือนล่าสุด
    $lastViewTime = getUserLastNotificationViewTime($userId);
    
    // ดึงรายการแจ้งเตือน/ออเดอร์ล่าสุด
    $recentOrders = [];
    if (function_exists('getUserRecentOrdersWithDetails')) {
        $recentOrders = getUserRecentOrdersWithDetails($userId, 5);
    } elseif (function_exists('getUserRecentOrders')) {
        $recentOrders = getUserRecentOrders($userId, 5);
    } else {
        if (function_exists('getUserOrders')) {
            $allOrders = getUserOrders($userId);
            $recentOrders = array_slice($allOrders, 0, 5);
        }
    }
    
    // นับแจ้งเตือนใหม่
    foreach ($recentOrders as $order) {
        $updateTime = isset($order['updated_at']) ? strtotime($order['updated_at']) : (
            isset($order['created_at']) ? strtotime($order['created_at']) : 0
        );
        
        if ($updateTime > $lastViewTime) {
            $newCount++;
        }
    }
    
    return $newCount;
}

function getUserRecentOrdersWithDetails($userId, $limit = 5) {
    global $conn;

    $stmt = $conn->prepare("
        SELECT id, user_id, status, created_at, updated_at
        FROM orders
        WHERE user_id = ?
        ORDER BY updated_at DESC
        LIMIT ?
    ");
    $stmt->bind_param("ii", $userId, $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }

    return $orders;
}


?>