<?php
session_start();

// ล้าง session ทั้งหมด
session_unset();
session_destroy();

// Redirect กลับไปยังหน้า logSign.php
header("Location: logSign.php");
exit();
?>