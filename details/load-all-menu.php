<?php
include '../includes/functions.php'; // ตรวจสอบ path ให้ถูกต้อง
include '../config/foodOrder.php'; // ตรวจสอบ path ให้ถูกต้อง
mysqli_set_charset($conn, "utf8");

$sql = "SELECT * FROM menu";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0):
    while ($food = mysqli_fetch_assoc($result)): ?>
        <div class="menu-item category-<?php echo $food['category_id']; ?> bg-white rounded-lg shadow-md overflow-hidden">
            <a href="#" class="open-modal block" data-id="<?php echo $food['id']; ?>">
                <img
                    src="<?php echo !empty($food['image']) ? $food['image'] : '../<?php echo $food['image']; ?>'; ?>"
                    alt="<?php echo htmlspecialchars($food['name']); ?>"
                    class="w-full h-48 object-cover"
                >
            </a>
            <div class="p-4">
                <h3 class="text-lg font-semibold">
                    <a href="#" class="open-modal hover:text-primary transition" data-id="<?php echo $food['id']; ?>">
                        <?php echo htmlspecialchars($food['name']); ?>
                    </a>
                </h3>
                <p class="text-gray-600 text-sm mb-2"><?php echo htmlspecialchars($food['description']); ?></p>
                <div class="flex justify-between items-center">
                    <span class="text-lg font-bold text-primary">฿<?php echo number_format($food['price'], 2); ?></span>
                    <a href="product-action.php?action=view&id=<?php echo $food['id']; ?>" class="bg-primary text-white px-4 py-2 rounded hover:bg-amber-600 transition">สั่งเลย</a>
                </div>
            </div>
        </div>
    <?php endwhile;
else:
    echo "<p class='text-gray-500'>ไม่มีเมนู</p>";
endif;

mysqli_close($conn);
?>