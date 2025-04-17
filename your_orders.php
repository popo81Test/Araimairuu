<?php
$pageTitle = "ประวัติการสั่งซื้อ - เตี๋ยวเรือเจ๊เต้ย";
include 'includes/header.php';


// Redirect if not logged in
if (!isLoggedIn()) {
    redirect('login.php');
}

$userId = $_SESSION['user_id'];

// Get user orders
$orders = getUserOrders($userId);
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold mb-6">ประวัติการสั่งซื้อของคุณ</h1>

    <?php if (empty($orders)): ?>
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <div class="text-gray-500 mb-4">
                <i class="fas fa-shopping-bag text-5xl mb-4"></i>
                <h2 class="text-xl font-semibold">คุณยังไม่มีประวัติการสั่งซื้อ</h2>
                <p class="mt-2">เริ่มสั่งก๋วยเตี๋ยวอร่อยๆ ของเราได้เลย</p>
            </div>
            <a href="index.php" class="inline-block bg-primary text-white py-2 px-4 rounded-md hover:bg-amber-600 transition">
                ไปยังเมนูอาหาร
            </a>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">รหัสคำสั่งซื้อ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">วันที่</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ยอดรวม</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">สถานะ</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">รายละเอียด</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($orders as $order): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                #<?php echo $order['id']; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo number_format($order['total_price'], 2); ?>฿
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php
                                    $statusClass = '';
                                    $statusText = '';

                                    switch ($order['status']) {
                                        case 'pending':
                                            $statusClass = 'bg-yellow-100 text-yellow-800';
                                            $statusText = 'รอดำเนินการ';
                                            break;
                                        case 'processing':
                                            $statusClass = 'bg-blue-100 text-blue-800';
                                            $statusText = 'กำลังเตรียมอาหาร';
                                            break;
                                        case 'out_for_delivery':
                                            $statusClass = 'bg-purple-100 text-purple-800';
                                            $statusText = 'กำลังจัดส่ง';
                                            break;
                                        case 'delivered':
                                            $statusClass = 'bg-indigo-100 text-indigo-800';
                                            $statusText = 'จัดส่งแล้ว';
                                            break;
                                        case 'completed':
                                            $statusClass = 'bg-green-100 text-green-800';
                                            $statusText = 'เสร็จสิ้น';
                                            break;
                                        case 'cancelled':
                                            $statusClass = 'bg-red-100 text-red-800';
                                            $statusText = 'ยกเลิก';
                                            break;
                                        default:
                                            $statusClass = 'bg-gray-100 text-gray-800';
                                            $statusText = $order['status'];
                                    }
                                ?>
                                <?php if (isAdmin()): ?>
                                    <select onchange="updateOrderStatus(<?php echo $order['id']; ?>, this.value)" class="px-2 py-1 text-xs font-semibold rounded <?php echo $statusClass; ?>">
                                        <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>รอดำเนินการ</option>
                                        <option value="processing" <?php echo $order['status'] === 'processing' ? 'selected' : ''; ?>>กำลังเตรียมอาหาร</option>
                                        <option value="completed" <?php echo $order['status'] === 'completed' ? 'selected' : ''; ?>>เสร็จสิ้น</option>
                                        <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>ยกเลิก</option>
                                    </select>
                                <?php else: ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $statusClass; ?>">
                                        <?php echo $statusText; ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button class="text-blue-600 hover:text-blue-900" onclick="toggleOrderDetails(<?php echo $order['id']; ?>)">
                                    <i class="fas fa-eye mr-1"></i> ดูรายละเอียด
                                </button>
                            </td>
                        </tr>

                        <!-- Order Details (Hidden by default) -->
                        <tr id="order-details-<?php echo $order['id']; ?>" class="hidden bg-gray-50">
                            <td colspan="5" class="px-6 py-4">
                                <div class="border-t border-gray-200 pt-4">
                                    <h4 class="text-sm font-semibold mb-2">รายละเอียดคำสั่งซื้อ #<?php echo $order['id']; ?></h4>

                                    <?php
                                        // Get order items
                                        $orderItems = getOrderDetails($order['id']);
                                    ?>

                                    <?php if (!empty($orderItems)): ?>
                                        <ul class="space-y-3">
                                            <?php foreach ($orderItems as $item): ?>
                                                <li class="flex items-start">
                                                    <?php if (!empty($item['food_image'])): ?>
                                                        <img src="<?php echo $item['food_image']; ?>" alt="<?php echo $item['food_name']; ?>" class="w-12 h-12 object-cover rounded mr-3">
                                                    <?php else: ?>
                                                        <div class="w-12 h-12 bg-gray-200 rounded mr-3 flex items-center justify-center">
                                                            <i class="fas fa-utensils text-gray-400"></i>
                                                        </div>
                                                    <?php endif; ?>

                                                    <div class="flex-1">
                                                        <div class="flex justify-between">
                                                            <div class="text-sm font-medium text-gray-900">
                                                                <?php echo $item['food_name']; ?> x<?php echo $item['quantity']; ?>
                                                            </div>
                                                            <div class="text-sm text-gray-500">
                                                                <?php echo number_format($item['price'] * $item['quantity'], 2); ?>฿
                                                            </div>
                                                        </div>

                                                        <?php if (!empty($item['addons'])): ?>
                                                            <ul class="text-xs text-gray-500 mt-1">
                                                                <?php foreach ($item['addons'] as $addon): ?>
                                                                    <li>
                                                                        <?php echo $addon['addon_name']; ?>
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
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>

                                        <div class="border-t border-gray-200 mt-4 pt-4">
                                            <div class="flex justify-between text-sm">
                                                <?php if (isAdmin()): ?>
                                                <div class="text-left">
                                                    <p class="font-medium">ผู้สั่งซื้อ: <?php echo htmlspecialchars($order['username']); ?></p>
                                                </div>
                                                <?php endif; ?>
                                                <div class="text-right">
                                                    <p class="font-semibold">ยอดรวมทั้งสิ้น: <?php echo number_format($order['total_price'] , 2); ?> ฿</p>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-sm text-gray-500">ไม่พบรายละเอียดรายการสินค้า</p>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <div class="mt-4">
        <a href="index.php" class="text-primary hover:text-amber-600">
            <i class="fas fa-arrow-left mr-2"></i> กลับหน้าหลัก
        </a>
    </div>
</div>

<script>
function updateOrderStatus(orderId, newStatus) {
    const select = event.target;
    const originalValue = select.value;
    
    // ส่งคำสั่งซื้อทันทีเมื่อเลือกสถานะ
    if (confirm('ยืนยันการอัพเดทสถานะคำสั่งซื้อ?')) {
        fetch('update_order_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                order_id: orderId,
                status: newStatus
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update select element class based on new status
                const statusClasses = {
                    'pending': 'bg-yellow-100 text-yellow-800',
                    'processing': 'bg-blue-100 text-blue-800',
                    'out_for_delivery': 'bg-purple-100 text-purple-800',
                    'delivered': 'bg-indigo-100 text-indigo-800',
                    'completed': 'bg-green-100 text-green-800',
                    'cancelled': 'bg-red-100 text-red-800'
                };

                // Remove all possible status classes
                select.classList.remove(...Object.values(statusClasses));
                // Add new status class
                select.classList.add(statusClasses[newStatus]);
                alert('อัพเดทสถานะคำสั่งซื้อเรียบร้อย');
            } else {
                alert('เกิดข้อผิดพลาดในการอัปเดตสถานะ');
                select.value = originalValue;
            }
        })
        .catch(error => {
            alert('เกิดข้อผิดพลาดในการอัปเดตสถานะ');
            select.value = originalValue;
        });
    }
}

function toggleOrderDetails(orderId) {
    const detailsRow = document.getElementById('order-details-' + orderId);
    const button = document.querySelector(`button[onclick="toggleOrderDetails(${orderId})"]`);
    const eyeIcon = button.querySelector('i');

    if (detailsRow) {
        if (detailsRow.classList.contains('hidden')) {
            detailsRow.classList.remove('hidden');
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            detailsRow.classList.add('hidden');
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }
}
</script>
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