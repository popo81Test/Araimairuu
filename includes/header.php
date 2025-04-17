<?php
ob_start();
// Include functions and start session
require_once(__DIR__ . '/functions.php');
require_once(__DIR__ . '/../config/foodOrder.php');

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

                        

                        <!-- ปุ่มชื่อผู้ใช้ -->
                        <div class="relative group">
                            <button onclick="toggleUserMenu()" class="flex items-center space-x-1 text-gray-700 hover:text-primary focus:outline-none">
                                <i class="fas fa-user text-xs"></i>
                                <span><?php echo $_SESSION['username']; ?></span>
                                
                            </button>

                            <!-- เมนูดรอปดาวน์ -->
                            <!-- <div id="userMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded shadow-lg border z-50">
                             บัญชี 
                                <button id="toggleAccount" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user-circle mr-2"></i> บัญชี
                                </button>

                                 การแจ้งเตือน 
                                <button id="toggleNotification" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                        <i class="bx bxs-bell-ring mr-2"></i> การแจ้งเตือน
                                </button>

                                ติดต่อเรา 
                                <button id="toggleContact" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i class="bx bxs-chat mr-2"></i> ติดต่อเรา
                                </button>

                                 รายการโปรด
                                <button id="toggleFavorites" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-heart mr-2"></i> รายการโปรด
                                </button>
                            </div> -->
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

     <!-- Sidebar บัญชี 
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
        </div>
    </div>

    Sidebar การแจ้งเตือน
    <div id="notificationSidebar"
        class="fixed top-0 right-0 w-80 h-full bg-white shadow-lg transform translate-x-full transition-transform duration-300 z-50 overflow-y-auto">
        <div class="flex items-center justify-between px-4 py-3 border-b">
            <h2 class="text-lg font-semibold">การแจ้งเตือน</h2>
            <button class="closeSidebar text-gray-600 hover:text-red-500">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-4">
            <p>คุณยังไม่มีการแจ้งเตือน</p>
        </div>
    </div>

    Sidebar ติดต่อเรา 
    <div id="contactSidebar"
        class="fixed top-0 right-0 w-80 h-full bg-white shadow-lg transform translate-x-full transition-transform duration-300 z-50 overflow-y-auto">
        <div class="flex items-center justify-between px-4 py-3 border-b">
            <h2 class="text-lg font-semibold">ติดต่อเรา</h2>
            <button class="closeSidebar text-gray-600 hover:text-red-500">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-4">
            <p>คุณสามารถติดต่อเราผ่าน Line, โทรศัพท์ หรืออีเมล</p>
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
    </script>

    


<script src="Js/userMenu.js"></script>


    
