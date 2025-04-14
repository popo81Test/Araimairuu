<?php
    $pageTitle = "เข้าสู่ระบบ - เตี๋ยวเรือเจ๊เต้ย";
    session_start(); // Start the session at the very beginning

    // Include database connection
    include 'config/foodOrder.php';

    // Include helper functions
    include 'includes/functions.php';

    // Check if user is already logged in, redirect to index if so
    if (isLoggedIn()) {
        header("Location: index.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/logSign.css">
</head>
<body>
    

    <div class="container" id="container">
        <div class="form-container sign-up-container">
            <form action="process_login_signup.php" method="post">
                <h1>Create Account</h1>
                <?php if (isset($_SESSION['signup_error'])): ?>
                    <p style="color: red;"><?php echo $_SESSION['signup_error']; ?></p>
                    <?php unset($_SESSION['signup_error']); ?>
                <?php endif; ?>
                <span>or use your email for registration</span>
                <div class="infield">
                    <input type="text" placeholder="Name" name="name" required />
                    <label></label>
                </div>
                <div class="infield">
                    <input type="email" placeholder="Email" name="email" required />
                    <label></label>
                </div>
                <div class="infield">
                    <input type="tel" placeholder="Phone" name="phone" required />
                    <label></label>
                </div>
                <div class="infield">
                    <input type="password" placeholder="Password" name="password" required />
                    <label></label>
                </div>
                <input type="hidden" name="action" value="signup">
                <button type="submit">Sign Up</button>
            </form>
        </div>

        <div class="form-container sign-in-container">
            <form action="process_login_signup.php" method="post">
                <h1>Sign in</h1>
                <?php if (isset($_SESSION['signup_error'])): ?>
                    <p style="color: red;"><?php echo $_SESSION['signup_error']; ?></p>
                    <?php unset($_SESSION['signup_error']); ?>
                <?php endif; ?>
                <span>or use your account</span>
                <div class="infield">
                    <input type="text" placeholder="Username" name="username" value="<?php echo isset($_COOKIE['remember_username']) ? $_COOKIE['remember_username'] : ''; ?>" required />
                    <label class="underline-label"></label>
                </div>
                <div class="infield">
                    <input type="password" placeholder="Password" name="password" required />
                    <label class="underline-label"></label>
                </div>

                <div class="remember-me-container">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember" value="1" <?php echo isset($_COOKIE['remember_username']) ? 'checked' : ''; ?>>
                        <label for="remember">จำชื่อผู้ใช้</label>

                    </div>
                </div>

                <input type="hidden" name="action" value="login">
                <button type="submit">Sign In</button>
            </form>
        </div>

        <div class="overlay-container" id="overlayCon">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <button type="button" id="helloFriendButton" style="font-size:13.5px;"><h1>สวัสดีจ้าเพิ่งเคยมาเหรอ?</h1></button>
                    <p>สมัครก่อน เดี๋ยวเจ้เต้ยไม่รู้จัก 😆</p>
                    <button id="signInOverlay" style="margin-bottom:32px;">Sign In</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <button type="button" id="welcomeBackButton" style="font-size:13.5px;"><h1>ยินดีต้อนรับกลับมา!</h1></button>
                    <p>เตี๋ยวกำลังเดือดเลย 🍜</p>
                    <button id="signUpOverlay" style="margin-bottom:32px; width: 143.67px; height: 40px;">Sign Up</button>
                </div>
            </div>
            <button id="overlayBtn"></button>
        </div>
    </div>

    
    <!--Left Right -->
    <script src="Js/logSignLR.js"></script>
    <!--Cookie-->
    <script src="Js/cookie.js"></script>

</body>
</html>

