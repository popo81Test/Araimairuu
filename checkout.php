<?php
$pageTitle = "ตะกร้าสินค้า - เตี๋ยวเรือเจ๊เต้ย";
include 'includes/header.php';



// Redirect if not logged in
if (!isLoggedIn()) {
    redirect('login.php');
}

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$error = '';
$success = '';

// Process checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = sanitize($_POST['address']);
    $phone = sanitize($_POST['phone']);
    
    // Validate input
    if (empty($address) || empty($phone)) {
        $error = 'กรุณากรอกที่อยู่และเบอร์โทรศัพท์';
    } elseif (empty($_SESSION['cart'])) {
        $error = 'ตะกร้าสินค้าว่างเปล่า กรุณาเลือกสินค้าก่อน';
    } else {
        try {
            // Create order
            $userId = $_SESSION['user_id'];
            $totalPrice = getCartTotal();
            $items = $_SESSION['cart'];
            
            $orderId = createOrder($userId, $items, $totalPrice, $address, $phone);
            
            if ($orderId) {
                // Clear cart after successful order
                $_SESSION['cart'] = [];
                
                // Set success message
                $success = 'สั่งซื้อสำเร็จ! รหัสคำสั่งซื้อของคุณคือ #' . $orderId;
                
                // Redirect to order detail page
                redirect('your_orders.php?success=1');
            } else {
                $error = 'เกิดข้อผิดพลาดในการสั่งซื้อ กรุณาลองใหม่อีกครั้ง';
            }
        } catch (Exception $e) {
            $error = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
        }
    }
}
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold mb-6">ตะกร้าสินค้า</h1>
    
    <?php if (!empty($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?php echo $error; ?></span>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($success)): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?php echo $success; ?></span>
        </div>
    <?php endif; ?>
    
    <?php if (empty($_SESSION['cart'])): ?>
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <div class="text-gray-500 mb-4">
                <i class="fas fa-shopping-cart text-5xl mb-4"></i>
                <h2 class="text-xl font-semibold">ตะกร้าสินค้าว่างเปล่า</h2>
                <p class="mb-4">เพิ่มสินค้าลงในตะกร้าเพื่อสั่งซื้อ</p>
                <a href="index.php#menu" class="bg-primary text-white px-4 py-2 rounded hover:bg-amber-600 transition">
                    เลือกซื้อสินค้า
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="lg:flex lg:space-x-8">
            <!-- Cart Items -->
            <div class="lg:w-2/3">
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">สินค้า</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">ราคา</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">จำนวน</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">รวม</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">ลบ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($_SESSION['cart'] as $index => $item): ?>
                                <?php
                                    $itemTotal = $item['price'] * $item['quantity'];
                                    
                                    // Add addon prices
                                    if (isset($item['addons']) && !empty($item['addons'])) {
                                        foreach ($item['addons'] as $addon) {
                                            $itemTotal += $addon['price'] * $item['quantity'];
                                        }
                                    }
                                ?>
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <?php if (!empty($item['image'])): ?>
                                                <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="w-16 h-16 object-cover rounded mr-4">
                                            <?php else: ?>
                                                <div class="w-16 h-16 bg-gray-200 rounded mr-4 flex items-center justify-center">
                                                    <i class="fas fa-utensils text-gray-400"></i>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div>
                                                <div class="font-medium text-gray-900"><?php echo $item['name']; ?></div>
                                                
                                                <?php if (!empty($item['addons'])): ?>
                                                    <ul class="text-xs text-gray-500 mt-1">
                                                        <?php foreach ($item['addons'] as $addon): ?>
                                                            <li>
                                                                <?php echo $addon['name']; ?>
                                                                <?php if ($addon['price'] > 0): ?>
                                                                    (+฿<?php echo number_format($addon['price'], 2); ?>)
                                                                <?php endif; ?>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                <?php endif; ?>
                                                
                                                <?php if (!empty($item['special_instructions'])): ?>
                                                    <div class="text-xs text-gray-500 mt-1 italic">
                                                        "<?php echo $item['special_instructions']; ?>"
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-500">
                                        ฿<?php echo number_format($item['price'], 2); ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-center items-center">
                                            <a href="product-action.php?action=update_quantity&index=<?php echo $index; ?>&quantity=<?php echo $item['quantity'] - 1; ?>" 
                                               class="text-gray-500 hover:text-gray-700 px-2">
                                                <i class="fas fa-minus"></i>
                                            </a>
                                            <span class="text-gray-700 mx-2"><?php echo $item['quantity']; ?></span>
                                            <a href="product-action.php?action=update_quantity&index=<?php echo $index; ?>&quantity=<?php echo $item['quantity'] + 1; ?>"
                                               class="text-gray-500 hover:text-gray-700 px-2">
                                                <i class="fas fa-plus"></i>
                                            </a>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-500">
                                        ฿<?php echo number_format($itemTotal, 2); ?>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="product-action.php?action=remove_from_cart&index=<?php echo $index; ?>" 
                                           class="text-red-500 hover:text-red-700">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="flex justify-between mb-8">
                    <a href="index.php#menu" class="text-primary hover:text-amber-600">
                        <i class="fas fa-arrow-left mr-2"></i> เลือกซื้อสินค้าต่อ
                    </a>
                    
                    <a href="product-action.php?action=clear_cart" class="text-red-500 hover:text-red-700">
                        <i class="fas fa-trash mr-2"></i> ล้างตะกร้า
                    </a>
                </div>
            </div>
            
            <!-- Checkout Form -->
            <div class="lg:w-1/3">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                    <h2 class="text-lg font-semibold mb-4">สรุปคำสั่งซื้อ</h2>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-gray-600">
                            <span>จำนวนสินค้า</span>
                            <span><?php echo getCartItemCount(); ?> </span>
                        </div>
                        
                        <div class="flex justify-between font-semibold text-gray-900 pt-2 border-t border-gray-200">
                            <span>ยอดรวมทั้งสิ้น</span>
                            <span>฿<?php echo number_format(getCartTotal(), 2); ?></span>
                        </div>
                    </div>
                    
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                        <div class="space-y-4">
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">ที่อยู่จัดส่ง <span class="text-red-500">*</span></label>
                                <textarea id="address" name="address" rows="3" required
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary"></textarea>
                            </div>
                            
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">เบอร์โทรศัพท์ <span class="text-red-500">*</span></label>
                                <input type="tel" id="phone" name="phone" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
                            </div>
                        </div>
                        
                        <button type="submit" class="w-full bg-primary text-white py-3 mt-6 rounded-lg font-semibold hover:bg-amber-600 transition">
                            <a href="your_orders.php">สั่งอาหาร - ฿<?php echo number_format(getCartTotal(), 2); ?></a>
                        </button>
                        
                        <p class="text-xs text-gray-500 mt-3 text-center">
                            การกดสั่งอาหารหมายถึงคุณยอมรับ<a href="#" class="text-primary">เงื่อนไขการใช้บริการ</a>ของเรา
                        </p>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<head>
<style>
    .max-w-7xl.mx-auto.px-4.sm\:px-6.lg\:px-8.py-8 {
        transform: scale(1.0); /* 1.7 คือ 170% ของขนาดเดิม */
        transform-origin: top left; /* ให้การปรับขนาดเริ่มต้นจากมุมบนซ้าย */
        width: calc(100% / 1.0); /* ปรับความกว้างเพื่อชดเชยการขยาย */
        height: calc(100% / 1.0); /* ปรับความสูงเพื่อชดเชยการขยาย (ถ้าจำเป็น) */
        margin-bottom:20%;
    }
</style>
</head>

<?php include 'includes/footer.php'; ?>