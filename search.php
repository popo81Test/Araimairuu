<?php
include 'includes/header.php';
mysqli_set_charset($conn, "utf8");

$query = isset($_GET['query']) ? trim($_GET['query']) : '';

echo '<main class="flex-grow bg-blue-100 p-4">';
echo '<div class="max-w-6xl mx-auto">';

echo '<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">';
echo'<div class="mb-6">';
echo'<a href="index.php" class="text-primary hover:text-amber-600">';
echo'<i class="fas fa-arrow-left mr-2"></i> กลับหน้าหลัก';
echo'</a>';
echo'</div>';

echo '
<!-- Modal -->
<div id="foodModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg max-w-lg w-full relative">
        <button id="closeModalBtn" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
            <i class="fas fa-times"></i>
        </button>
        <div id="modalContent" class="text-sm text-gray-700">
            กำลังโหลด...
        </div>
    </div>
</div>';


if ($query) {
    echo '<h1 class="text-2xl font-bold text-gray-800 mb-4">ผลการค้นหา: "' . htmlspecialchars($query) . '"</h1>';

    $searchQuery = "SELECT * FROM foods WHERE name LIKE '%$query%' OR description LIKE '%$query%'";
    $result = mysqli_query($conn, $searchQuery);

    if (mysqli_num_rows($result) > 0) {
        echo '<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6" id="menu-items">';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="menu-item bg-white rounded-lg shadow-md overflow-hidden">';
            echo '<a href="#" class="open-modal block" data-id="' . htmlspecialchars($row['id']) . '">';
            echo '<img src="' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['name']) . '" class="w-full h-48 object-cover rounded mb-3">';
            echo '</a>';
            echo '<div class="p-4">';
            echo '<h3 class="text-lg font-semibold">';
            echo '<a href="#" class="open-modal hover:text-primary transition" data-id="' . htmlspecialchars($row['id']) . '">';
            echo htmlspecialchars($row['name']);
            echo '</a>';
            echo '</h3>';
            echo '<p class="text-gray-600 text-sm mb-2">' . htmlspecialchars($row['description']) . '</p>';
            echo '<div class="flex justify-between items-center">';
            echo '<span class="text-lg font-bold text-primary">฿' . number_format($row['price'], 2) . '</span>';
            echo '<a href="product-action.php?action=view&id=' . htmlspecialchars($row['id']) . '" class="bg-primary text-white px-4 py-2 rounded hover:bg-amber-600 transition">สั่งเลย</a>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
    } else {
        echo '<p class="text-gray-600">ไม่พบเมนูที่ค้นหา</p>';
    }
} else {
    echo '<p class="text-red-500">กรุณาระบุคำค้นหา</p>';
}


echo '</div>';
echo '</main>';
echo '<script src="Js/comm.js"></script>';

include 'includes/footer.php'; 
?>
