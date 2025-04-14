<!--remember me -->

<?php
include 'config/foodOrder.php';

session_start();

if (isset($_POST['username']) && isset($_POST['token'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $token = $_POST['token'];
    $expire = time() + (86400 * 30); // Expected expire time (should match the one set during login)
    $expected_token = md5($username . $expire . 'your_secret_key'); // สร้าง token ที่คาดหวัง

    if ($token === $expected_token) {
        $query = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            echo json_encode(['success' => true]);
            exit();
        }
    }
}

echo json_encode(['success' => false]);
mysqli_close($conn);
?>