
    // ตรวจสอบ cookie เมื่อโหลดหน้า
    window.onload = function() {
        const rememberUsername = getCookie('remember_username');
        const rememberToken = getCookie('remember_token');

        if (rememberUsername && rememberToken) {
            // ส่งคำขอไปยังเซิร์ฟเวอร์เพื่อตรวจสอบ token และเข้าสู่ระบบ
            fetch('auto_login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `username=${encodeURIComponent(rememberUsername)}&token=${encodeURIComponent(rememberToken)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'index.php'; // หรือหน้า dashboard ของคุณ
                } else {
                    // Token ไม่ถูกต้อง หรือหมดอายุ ให้ลบ cookie
                    deleteCookie('remember_username');
                    deleteCookie('remember_token');
                }
            })
            .catch(error => {
                console.error('Error during auto login:', error);
                // เกิดข้อผิดพลาด ให้ลบ cookie
                deleteCookie('remember_username');
                deleteCookie('remember_token');
            });
        }
    };

    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
    }

    function deleteCookie(name) {
        document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
    }
