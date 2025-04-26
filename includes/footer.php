<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = isset($_SESSION['user_id']); // ตรวจสอบว่ามี user_id ใน Session หรือไม่
?>
</main>
    </div>
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex justify-center md:justify-start space-x-6">
                    <a href="#" class="text-gray-400 hover:text-gray-500">
                        <i class="fab fa-facebook text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-gray-500">
                        <i class="fab fa-instagram text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-gray-500">
                        <i class="fab fa-line text-xl"></i>
                    </a>
                </div>

                <div class="mt-8 md:mt-0">
                    <p class="text-center text-base text-gray-500">
                        &copy; <?php echo date('Y'); ?> เตี๋ยวเรือเจ๊เต้ย
                    </p>
                </div>

            </div>
        </div>
    </footer>

    <div id="floatingNotificationButton" class="fixed bottom-6 right-6 bg-primary text-white rounded-full shadow-lg p-3 cursor-pointer z-50" style="<?php echo !$isLoggedIn ? 'display: none;' : ''; ?>">
        <div class="relative">
            <i id="floatingNotificationButtonIcon" class="bx bxs-bell-ring text-xl"></i>
            <span id="floatingNotificationBadge"
            class="notification-badge absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full hidden">
            </span>
        </div>
    </div>
    

    <script>
        const isLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;
    </script>
    <script src="Js/userMenu.js"></script>

</body>
</html>