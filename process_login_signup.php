<?php
session_start();

include 'config/foodOrder.php';
mysqli_set_charset($conn, "utf8");

// ฟังก์ชัน login
function loginUser($conn, $username, $password) {
    $username = mysqli_real_escape_string($conn, $username);
    $password_hashed = md5($password); // แนะนำใช้ bcrypt แทน

    $query = "SELECT id, username, password, role FROM users WHERE username = '$username' AND password = '$password_hashed'";
    $result = mysqli_query($conn, $query);

    return (mysqli_num_rows($result) > 0) ? mysqli_fetch_assoc($result) : false;
}

// ✅ ฟังก์ชัน signup รองรับ phone ด้วย
function registerUser($conn, $name, $email, $password, $phone) {
    $name = mysqli_real_escape_string($conn, $name);
    $email = mysqli_real_escape_string($conn, $email);
    $phone = mysqli_real_escape_string($conn, $phone);
    $password_hashed = md5($password); // แนะนำใช้ bcrypt แทน

    // ตรวจสอบชื่อผู้ใช้ซ้ำ
    $checkUsernameQuery = "SELECT * FROM users WHERE username = '$name'";
    $checkUsernameResult = mysqli_query($conn, $checkUsernameQuery);
    if (mysqli_num_rows($checkUsernameResult) > 0) {
        return "Username already exists.";
    }

    // ตรวจสอบ email ซ้ำ
    $checkEmailQuery = "SELECT * FROM users WHERE email = '$email'";
    $checkEmailResult = mysqli_query($conn, $checkEmailQuery);
    if (mysqli_num_rows($checkEmailResult) > 0) {
        return "Email already exists.";
    }

    // ตรวจสอบเบอร์โทรศัพท์ซ้ำ
    $checkPhoneQuery = "SELECT * FROM users WHERE phone = '$phone'";
    $checkPhoneResult = mysqli_query($conn, $checkPhoneQuery);
    if (mysqli_num_rows($checkPhoneResult) > 0) {
        return "Phone number already exists.";
    }

    // ตรวจสอบเบอร์โทรศัพท์ซ้ำ
    $checkPasswordQuery = "SELECT * FROM users WHERE password = '$password'";
    $checkPasswordResult = mysqli_query($conn, $checkPasswordQuery);
    if (mysqli_num_rows($checkPasswordResult) > 0) {
        return "Password already exists.";
    }

    // ✅ เพิ่ม phone เข้าไปใน query
    $query = "INSERT INTO users (username, email, password, phone, firstname, lastname, created_at, role)
              VALUES ('$name', '$email', '$password_hashed', '$phone', '$name', '$name', NOW(), 'user')";

    return mysqli_query($conn, $query) ? true : false;
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['action'] == 'login') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $user = loginUser($conn, $username, $password);
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if (isset($_POST['remember'])) {
                $expire = time() + (86400 * 30);
                setcookie('remember_username', $username, $expire, '/');
                setcookie('remember_token', md5($username . $expire . 'your_secret_key'), $expire, '/');
            } else {
                setcookie('remember_username', '', time() - 3600, '/');
                setcookie('remember_token', '', time() - 3600, '/');
            }

            header("Location: index.php");
            exit();
        } else {
            $_SESSION['login_error'] = "Invalid username or password.";
            header("Location: logSign.php");
            exit();
        }

    } elseif ($_POST['action'] == 'signup') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone']; // ✅ รับ phone จาก form
        $password = $_POST['password'];

        // ✅ ส่ง phone ไปที่ฟังก์ชัน registerUser
        $registerResult = registerUser($conn, $name, $email, $password, $phone);
        if ($registerResult === true) {
            $_SESSION['signup_success'] = "Registration successful.";
            header("Location: logSign.php");
            exit();
        } else {
            $_SESSION['signup_error'] = $registerResult;
            $errorMsg = addslashes($registerResult);
            echo "
                <html><head>
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                <style>
                    body {
                        background-color: #fff7f0;
                        font-family: 'Prompt', sans-serif;
                    }
                </style>
                <link href='https://fonts.googleapis.com/css2?family=Prompt&display=swap' rel='stylesheet'>
    
                </head><body>
                <script>
                    Swal.fire({
                    icon: 'error',
                    title: 'สมัครไม่สำเร็จ',
                    text: '$errorMsg',
                    confirmButtonText: 'กลับไปแก้ไข',
                    background: '#fff0e6',
                    color: '#b90000',
                    iconColor: '#ff5722',
                    confirmButtonColor: '#ff9800',
                    customClass: {
                    popup: 'rounded-xl shadow-lg'
                    }
                }).then(() => {
                window.location.href = 'logSign.php';
                });
            </script>
            </body></html>
            ";
            exit();
        }
    }
}


mysqli_close($conn);
?>
