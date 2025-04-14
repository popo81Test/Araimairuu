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
    header("Location: $location");
    exit;
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
function createOrder($userId, $items, $total, $address, $phone) {
    global $conn;
    
    // Begin transaction
    mysqli_begin_transaction($conn);
    
    try {
        // Insert order
        $userId = sanitize($userId);
        $total = sanitize($total);
        $address = sanitize($address);
        $phone = sanitize($phone);
        $status = "pending";
        
        $query = "INSERT INTO orders (user_id, total_price, address, phone, status, created_at) 
                  VALUES ('$userId', '$total', '$address', '$phone', '$status', NOW())";
        
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

// Function to get user orders
function getUserOrders($userId) {
    global $conn;
    $userId = sanitize($userId);
    
    $query = "SELECT * FROM orders WHERE user_id = '$userId' ORDER BY created_at DESC";
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
        // Get addons for this order item
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
?>