<!-- BUG -->


<?php
session_start();

if (!isset($_SESSION['favorites'])) {
    $_SESSION['favorites'] = [];
}

$id = $_POST['id'] ?? null;
$action = $_POST['action'] ?? null;

if ($id && $action) {
    if ($action === 'add') {
        if (!in_array($id, $_SESSION['favorites'])) {
            $_SESSION['favorites'][] = $id;
            echo "เพิ่ม $id แล้ว";
        } else {
            echo "$id มีอยู่แล้ว";
        }
    } elseif ($action === 'remove') {
        $_SESSION['favorites'] = array_filter($_SESSION['favorites'], fn($f) => $f != $id);
        echo "ลบ $id แล้ว";
    } else {
        echo "action ไม่ถูกต้อง";
    }
} else {
    echo "ข้อมูลไม่ครบ";
}


