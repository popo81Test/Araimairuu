<?php
ob_start();
// Include functions and start session
require_once(__DIR__ . '/functions.php');
require_once(__DIR__ . '/../config/foodOrder.php');

// Get order notifications for logged-in users
$orderNotifications = [];
if (isLoggedIn()) {
    $userId = $_SESSION['user_id'];
    // Try to get recent orders with status updates
    if (function_exists('getUserRecentOrders')) {
        $orderNotifications = getUserRecentOrders($userId, 5); // Get 5 most recent orders
    } else {
        // Fallback if the function doesn't exist - we'll need to define it
        if (function_exists('getUserOrders')) {
            $allOrders = getUserOrders($userId);
            // Only keep the 5 most recent orders
            $orderNotifications = array_slice($allOrders, 0, 5);
        }
    }
}

// Count new notifications (orders with recent status changes)
$newNotificationsCount = 0;
if (!empty($orderNotifications)) {
    foreach ($orderNotifications as $notification) {
        // Consider orders updated in the last 30 minutes as "new"
        if (isset($notification['updated_at']) && 
            (strtotime($notification['updated_at']) > (time() - 1800))) {
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

    

    
    <style>
        body {
            font-family: 'Kanit', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- โลโก้ -->
                <div class="flex items-center">
                    <a href="index.php" class="flex-shrink-0 flex items-center">
                        <img src="images/01.png" alt="เตี๋ยวเรือเจ๊เต้ย" class="h-8 w-auto">
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

                        <!-- ปุ่มแจ้งเตือน -->
                        <div class="relative">
                            <button id="toggleNotification" class="relative inline-flex items-center p-2 text-gray-700 hover:text-primary">
                                <i class="bx bxs-bell-ring text-xl"></i>
                                <?php if ($newNotificationsCount > 0): ?>
                                    <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                                        <?php echo $newNotificationsCount; ?>
                                    </span>
                                <?php endif; ?>
                            </button>
                        </div>

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

                                <!-- รายการโปรด -->
                                <button id="toggleFavorites" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-heart mr-2"></i> รายการโปรด
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
    
    <!-- Sidebar รายการโปรด -->
    <div id="favoritesSidebar"
        class="fixed top-0 right-0 w-80 h-full bg-white shadow-lg transform translate-x-full transition-transform duration-300 z-50 overflow-y-auto">
        <div class="flex items-center justify-between px-4 py-3 border-b">
            <h2 class="text-lg font-semibold">เมนูโปรด</h2>
            <button class="closeSidebar text-gray-600 hover:text-red-500">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-4">
            <p>ยังไม่มีรายการโปรด</p>
        </div>
    </div>

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
            <p><strong>ชื่อผู้ใช้:</strong> <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'ไม่ระบุ'; ?></p>
            <p><strong>อีเมล:</strong> <?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : 'ไม่ระบุ'; ?></p>
            <p>รายละเอียดบัญชีของคุณ</p>
        </div>
    </div>

    <!-- Sidebar การแจ้งเตือน -->
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
                <div class="flex items-center justify-between">
                    <h3 class="font-medium">การแจ้งเตือนสถานะอาหาร</h3>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" value="" class="sr-only peer" id="notificationToggle" checked>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/50 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                    </label>
                </div>
                <p class="text-sm text-gray-500 mt-1">รับการแจ้งเตือนเมื่อสถานะอาหารของคุณมีการอัพเดท</p>
            </div>
            
            <div class="border-t pt-4">
                <h3 class="font-medium mb-2">สถานะอาหารล่าสุด</h3>
                <div class="space-y-3" id="notificationList">
                    <?php if (!empty($orderNotifications)): ?>
                        <?php foreach ($orderNotifications as $order): ?>
                            <?php
                                $statusClass = '';
                                $statusText = '';
                                $isNew = isset($order['updated_at']) && (strtotime($order['updated_at']) > (time() - 1800));
                                
                                switch ($order['status']) {
                                    case 'pending':
                                        $statusClass = 'bg-yellow-50 border-yellow-100';
                                        $statusText = 'รอดำเนินการ';
                                        $statusColor = 'text-yellow-600';
                                        break;
                                    case 'processing':
                                        $statusClass = 'bg-blue-50 border-blue-100';
                                        $statusText = 'กำลังเตรียมอาหาร';
                                        $statusColor = 'text-blue-600';
                                        break;
                                    case 'completed':
                                        $statusClass = 'bg-green-50 border-green-100';
                                        $statusText = 'เสร็จแล้ว';
                                        $statusColor = 'text-green-600';
                                        break;
                                    case 'cancelled':
                                        $statusClass = 'bg-red-50 border-red-100';
                                        $statusText = 'ยกเลิก';
                                        $statusColor = 'text-red-600';
                                        break;
                                    default:
                                        $statusClass = 'bg-gray-50 border-gray-100';
                                        $statusText = $order['status'];
                                        $statusColor = 'text-gray-600';
                                }
                                
                                // Get items from the order if available
                                $itemsText = "รายการอาหารของคุณ";
                                if (function_exists('getOrderDetails') && isset($order['id'])) {
                                    $orderItems = getOrderDetails($order['id']);
                                    if (!empty($orderItems)) {
                                        $foodNames = array_map(function($item) {
                                            return $item['food_name'];
                                        }, $orderItems);
                                        $itemsText = implode(', ', array_slice($foodNames, 0, 2));
                                        if (count($foodNames) > 2) {
                                            $itemsText .= ' และอื่นๆ';
                                        }
                                    }
                                }
                            ?>
                            <div class="<?php echo $statusClass; ?> p-3 rounded-lg border <?php echo $isNew ? 'relative' : ''; ?>">
                                <?php if ($isNew): ?>
                                    <span class="absolute top-2 right-2 h-2 w-2 bg-red-500 rounded-full"></span>
                                <?php endif; ?>
                                <div class="flex justify-between">
                                    <span class="font-medium">ออเดอร์ #<?php echo $order['id']; ?></span>
                                    <span class="<?php echo $statusColor; ?> text-sm"><?php echo $statusText; ?></span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1"><?php echo $itemsText; ?></p>
                                <span class="text-xs text-gray-500 block mt-1">
                                    <?php 
                                        $timestamp = isset($order['updated_at']) ? strtotime($order['updated_at']) : strtotime($order['created_at']);
                                        $timeAgo = round((time() - $timestamp) / 60);
                                        
                                        if ($timeAgo < 1) {
                                            echo "เมื่อสักครู่";
                                        } elseif ($timeAgo < 60) {
                                            echo $timeAgo . " นาทีที่แล้ว";
                                        } elseif ($timeAgo < 1440) {
                                            echo round($timeAgo / 60) . " ชั่วโมงที่แล้ว";
                                        } else {
                                            echo date('d/m/Y H:i', $timestamp);
                                        }
                                    ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-sm text-gray-500">คุณยังไม่มีการแจ้งเตือน</p>
                    <?php endif; ?>
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
    <!-- Page content goes here -->


    </main>

    <script>
        function switchToSignup(event) {
            event.preventDefault(); // ป้องกันการเปลี่ยนหน้าแบบปกติ
            window.location.href = 'logSign.php'; // เปลี่ยนไปยังหน้า logSign.php

            // รอโหลดหน้า logSign.php เสร็จ
            setTimeout(function() {
                const overlayBtn = window.opener ? window.opener.document.getElementById('overlayBtn') : document.getElementById('overlayBtn');
                if (overlayBtn) {
                    overlayBtn.click(); // คลิกปุ่ม overlayBtn
                }
            }, 500); // รอ 500 milliseconds
        }

        // Add event listener for notification toggle
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle notification sidebar
            const toggleBtn = document.getElementById('toggleNotification');
            const sidebar = document.getElementById('notificationSidebar');
            
            if (toggleBtn && sidebar) {
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('translate-x-full');
                });
            }
            
            // Close notification sidebar
            const closeButtons = document.querySelectorAll('.closeSidebar');
            closeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const sidebar = this.closest('div[id$="Sidebar"]');
                    if (sidebar) {
                        sidebar.classList.add('translate-x-full');
                    }
                });
            });
            
            // Save notification preferences
            const notificationToggle = document.getElementById('notificationToggle');
            if (notificationToggle) {
                notificationToggle.addEventListener('change', function() {
                    localStorage.setItem('notificationsEnabled', this.checked ? 'true' : 'false');
                });
                
                // Load saved preference
                const savedPref = localStorage.getItem('notificationsEnabled');
                if (savedPref !== null) {
                    notificationToggle.checked = savedPref === 'true';
                }
            }
            
            // Check for notifications periodically
            if (isLoggedIn()) {
                checkForNewNotifications();
                setInterval(checkForNewNotifications, 60000); // Check every minute
            }
        });
        
        // Function to check for new notifications
        function checkForNewNotifications() {
            const notificationsEnabled = localStorage.getItem('notificationsEnabled') !== 'false';
            if (!notificationsEnabled) return;
            
            fetch('check_notifications.php')
                .then(response => response.json())
                .then(data => {
                    if (data.hasNew && data.hasNew === true) {
                        // Update notification badge
                        updateNotificationBadge(data.count || 0);
                        
                        // Show browser notification if supported
                        if (Notification.permission === "granted" && data.latestOrder) {
                            new Notification("สถานะอาหารอัพเดท", {
                                body: `ออเดอร์ #${data.latestOrder.id}: ${getStatusText(data.latestOrder.status)}`,
                                icon: "images/01.png"
                            });
                        }
                        
                        // Update notification list if sidebar is open
                        if (!document.getElementById('notificationSidebar').classList.contains('translate-x-full')) {
                            updateNotificationList(data.orders || []);
                        }
                    }
                })
                .catch(error => console.error('Error checking notifications:', error));
        }
        
        // Function to update notification badge
        function updateNotificationBadge(count) {
            const badge = document.querySelector('#toggleNotification span');
            if (count > 0) {
                if (badge) {
                    badge.textContent = count;
                } else {
                    const newBadge = document.createElement('span');
                    newBadge.className = 'absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full';
                    newBadge.textContent = count;
                    document.getElementById('toggleNotification').appendChild(newBadge);
                }
            } else if (badge) {
                badge.remove();
            }
        }
        
        // Function to get Thai status text
        function getStatusText(status) {
            switch (status) {
                case 'pending': return 'รอดำเนินการ';
                case 'processing': return 'กำลังเตรียมอาหาร';
                case 'completed': return 'เสร็จแล้ว';
                case 'cancelled': return 'ยกเลิก';
                default: return status;
            }
        }
        
        // Request notification permission
        if (Notification && Notification.permission !== "granted" && Notification.permission !== "denied") {
            document.getElementById('notificationToggle').addEventListener('change', function(e) {
                if (e.target.checked) {
                    Notification.requestPermission();
                }
            });
        }
    </script>

<script src="Js/userMenu.js"></script>