<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/../config/foodOrder.php';

if (!empty($_SESSION['favorites'])) {
    $ids = implode(",", array_map('intval', $_SESSION['favorites']));
    $query = "SELECT * FROM foods WHERE food_id IN ($ids)";
    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        echo '<div class="mb-4 border p-2 rounded shadow">
                <h3 class="font-bold text-gray-800">' . htmlspecialchars($row['food_name']) . '</h3>
                <p class="text-sm text-gray-600">' . htmlspecialchars($row['description']) . '</p>
            </div>';
    }
} else {
    echo '<p class="text-gray-500">ยังไม่มีเมนูโปรด</p>';
}
