<?php

ob_start();
// Include functions and start session
require_once(__DIR__ . '/functions.php');
require_once(__DIR__ . '/../config/foodOrder.php');

// Get order notifications for logged-in users
$orderNotifications = [];
if (isLoggedIn()) {
    $userId = $_SESSION['user_id'];
    if (function_exists('getUserRecentOrders')) {
        $orderNotifications = getUserRecentOrders($userId, 5);
    } else {
        if (function_exists('getUserOrders')) {
            $allOrders = getUserOrders($userId);
            $orderNotifications = array_slice($allOrders, 0, 5);
        }
    }
}
if (!isset($_SESSION['last_notification_view_time'])) {
    $_SESSION['last_notification_view_time'] = 0;
}

$newNotificationsCount = 0;
if (!empty($orderNotifications)) {
    foreach ($orderNotifications as $notification) {
        if (isset($notification['updated_at']) &&
            (strtotime($notification['updated_at']) > $_SESSION['last_notification_view_time'])) {
            $newNotificationsCount++;
        }
    }
}

// เพิ่มการตรวจสอบ session variable
if (!isset($_SESSION['last_notification_view_time'])) {
    $_SESSION['last_notification_view_time'] = 0;
}

$newNotificationsCount = 0;
if (!empty($orderNotifications)) {
    foreach ($orderNotifications as $notification) {
        // เปรียบเทียบเวลาอัพเดตกับเวลาที่ดูล่าสุด
        if (isset($notification['updated_at']) && 
            (strtotime($notification['updated_at']) > $_SESSION['last_notification_view_time'])) {
            $newNotificationsCount++;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'เตี๋ยวเรือเจ๊เต้ย'; ?></title>

    <!-- CSS เฉพาะหน้า -->
    <?php if (isset($customCss)): ?>
        <link rel="stylesheet" href="css/<?php echo $customCss; ?>">
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/tailwindcss@3.0.0/dist/tailwind.min.css"></script>

    
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#F59E0B',
                        },
                        secondary: {
                            DEFAULT: '#1F2937',
                        }
                    },
                    fontFamily: {
                        sans: ['Kanit', 'sans-serif'],
                        heading: ['Kanit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    
    <!-- Google Fonts - Kanit (Thai) -->
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>

    body {
        font-family: 'Kanit', sans-serif;
    }
    .notification-badge {
        transition: opacity 0.3s ease;
    }
    .notification-badge {
transition: opacity 0.3s ease;
}

#floatingNotificationButton {
    width: 50px;
    height: 50px;
    display: flex;
    justify-content: center;
    align-items: center;
    position: fixed;
    bottom: 24px;
    right: 24px;
    background-color: #F59E0B; /* หรือสี primary ของคุณ */
    color: white;
    border-radius: 50%;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    cursor: pointer;
    z-index: 50;
    transition: background-color 0.3s ease; /* เพิ่ม Transition ให้สีพื้นหลัง */
}

@keyframes bell-shake-constant {
    0% { transform: rotate(0deg); }
    25% { transform: rotate(-8deg); }
    50% { transform: rotate(8deg); }
    75% { transform: rotate(-6deg); }
    100% { transform: rotate(0deg); }
}

@keyframes bell-shake-hover {
    0% { transform: rotate(0deg); }
    25% { transform: rotate(-10deg); }
    50% { transform: rotate(10deg); }
    75% { transform: rotate(-5deg); }
    100% { transform: rotate(0deg); }
}

#floatingNotificationButtonIcon {
    animation: bell-shake-constant 1.5s ease-in-out infinite;
    transition: color 0.3s ease, transform 0.3s ease;
    color: white;
}

#floatingNotificationButton:hover {
    background-color: rgb(253, 69, 69); /* เปลี่ยนสีพื้นหลังเมื่อ Hover */
}

#floatingNotificationButton:hover #floatingNotificationButtonIcon {
    color: white; /* เปลี่ยนสีไอคอนเมื่อ Hover (เป็นสีขาวเพื่อให้ตัดกับพื้นหลังใหม่) */
    animation: bell-shake-hover 0.3s ease-in-out infinite;
}

</style>

</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
<div class="min-h-screen flex flex-col">
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- โลโก้ -->
                <div class="flex items-center">
                    <a href="index.php" class="flex-shrink-0 flex items-center">
                        <img src="images/เตี๋ยวเรือเจ๊เต้ย.png" alt="เตี๋ยวเรือเจ๊เต้ย" class="h-8 w-auto">
                    </a>
                </div>

                <!-- แถบค้นหา (แสดงเฉพาะ index.php) -->
                <?php if (basename($_SERVER['PHP_SELF']) === 'index.php'|| basename($_SERVER['PHP_SELF']) === 'search.php'): ?>
                    <form action="search.php" method="GET" class="flex-1 mx-4">
                        <input type="text" name="query" placeholder="ค้นหาเมนู..." 
                            class="w-full px-4 py-2 border rounded-full shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" />
                    </form>
                <?php endif; ?>

                <!-- เมนูขวา -->
                <div class="flex items-center gap-4">
                    <?php if (isLoggedIn()): ?>
                        <a href="checkout.php" class="relative inline-flex items-center p-2 text-gray-700 hover:text-primary">
                            <i class="fas fa-shopping-cart text-xl"></i>
                            <?php if (getCartItemCount() > 0): ?>
                                <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                                    <?php echo getCartItemCount(); ?>
                                </span>
                            <?php endif; ?>
                        </a>

                        <a href="your_orders.php" class="text-gray-700 hover:text-primary px-3 py-2">
                            <i class="fas fa-list-ul mr-1"></i> ออเดอร์ของฉัน
                        </a>

                        <?php if (isAdmin()): ?>
                            <a href="dishes.php" class="text-gray-700 hover:text-primary px-3 py-2">
                                <i class="fas fa-utensils mr-1"></i> จัดการอาหาร
                            </a>
                        <?php else: ?>
                        <?php endif; ?>
                        <!-- ปุ่มชื่อผู้ใช้ -->
                        <div class="relative group">
                            <button onclick="toggleUserMenu()" class="flex items-center space-x-1 text-gray-700 hover:text-primary focus:outline-none">
                                <i class="fas fa-user text-xs"></i>
                                <span><?php echo $_SESSION['username']; ?></span>
                                <i class="fas fa-chevron-down text-xs ml-1"></i>
                            </button>

                            <!-- เมนูดรอปดาวน์ -->
                            <div id="userMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded shadow-lg border z-50">
                                <!-- บัญชี -->
                                <button id="toggleAccount" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user-circle mr-2"></i> บัญชี
                                </button>

                                <!-- ติดต่อเรา -->
                                <button id="toggleContact" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i class="bx bxs-chat mr-2"></i> ติดต่อเรา
                                </button>
                            </div>
                        </div>
                        <a href="logout.php" class="bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600 transition">
                            <i class="fas fa-sign-out-alt mr-1"></i> ออกจากระบบ
                        </a>
                    <?php else: ?>
                        <a href="logSign.php" class="text-gray-700 hover:text-primary px-3 py-2">เข้าสู่ระบบ</a>
                        <a href="logSign.php" class="bg-primary text-white px-4 py-2 rounded hover:bg-amber-600 transition">สมัครสมาชิก</a>
                    <?php endif; ?>
                </div>
                
            </div>
        </div>
    </header>
    
    <!-- Sidebar บัญชี -->
    <div id="accountSidebar"
        class="fixed top-0 right-0 w-80 h-full bg-white shadow-lg transform translate-x-full transition-transform duration-300 z-50 overflow-y-auto">
        <div class="flex items-center justify-between px-4 py-3 border-b">
            <h2 class="text-lg font-semibold">บัญชี</h2>
            <button class="closeSidebar text-gray-600 hover:text-red-500">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-4">
            <p>รายละเอียดบัญชีของคุณ</p>
            <p><strong>ชื่อผู้ใช้:</strong> <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'ไม่ระบุ'; ?></p>

        </div>
    </div>

    <!-- Sidebar การแจ้งเตือน -->
    <!-- Add this to your header.php file, replacing the existing notification sidebar -->
<div id="notificationSidebar"
    class="fixed top-0 right-0 w-80 h-full bg-white shadow-lg transform translate-x-full transition-transform duration-300 z-50 overflow-y-auto">
    <div class="flex items-center justify-between px-4 py-3 border-b">
        <h2 class="text-lg font-semibold">การแจ้งเตือน</h2>
        <button class="closeSidebar text-gray-600 hover:text-red-500">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="p-4">
        <div class="mb-4">
            <h3 class="font-medium mb-2">สถานะล่าสุด</h3>
            <p id="latestOrderStatus" class="text-sm text-gray-600">-</p>
        </div>
        
        <div class="border-t pt-4">
            <h3 class="font-medium mb-2">รายการอาหารล่าสุด</h3>
            <div class="space-y-3" id="notificationList">
                <!-- Notification items will be added here dynamically -->
                <p class="text-sm text-gray-500">กำลังโหลดข้อมูล...</p>
            </div>
            
            <div class="mt-4 text-center">
                <a href="your_orders.php" class="text-primary hover:text-amber-600 text-sm font-medium">
                    ดูออเดอร์ทั้งหมด <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

    <!-- Sidebar ติดต่อเรา -->
    <div id="contactSidebar"
        class="fixed top-0 right-0 w-80 h-full bg-white shadow-lg transform translate-x-full transition-transform duration-300 z-50 overflow-y-auto">
        <div class="flex items-center justify-between px-4 py-3 border-b">
            <h2 class="text-lg font-semibold">ติดต่อเรา</h2>
            <button class="closeSidebar text-gray-600 hover:text-red-500">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-4">
            <div class="mb-4">
                <div class="flex items-center mb-2">
                    <i class="bx bxl-line text-green-500 text-xl mr-2"></i>
                    <h3 class="font-medium">Line</h3>
                </div>
                <p class="text-sm text-gray-600">@jeitey</p>
            </div>
            
            <div class="mb-4">
                <div class="flex items-center mb-2">
                    <i class="fas fa-phone text-blue-500 mr-2"></i>
                    <h3 class="font-medium">โทรศัพท์</h3>
                </div>
                <p class="text-sm text-gray-600">099-999-9999</p>
            </div>
            
            <div class="mb-4">
                <div class="flex items-center mb-2">
                    <i class="fas fa-envelope text-red-500 mr-2"></i>
                    <h3 class="font-medium">อีเมล</h3>
                </div>
                <p class="text-sm text-gray-600">contact@jeitey.com</p>
            </div>
        </div>
    </div>

    <main class="flex-grow">
    </main>

<script src="Js/userMenu.js"></script>
