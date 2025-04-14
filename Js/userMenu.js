document.addEventListener('DOMContentLoaded', function () {
    // --- เมนูดรอปดาวน์ ---
    const userMenu = document.getElementById('userMenu');
    const toggleUserButton = document.querySelector('button[onclick="toggleUserMenu()"]');

    // ฟังก์ชัน toggle เมนูดรอปดาวน์
    window.toggleUserMenu = function () {
        if (userMenu) {
            userMenu.classList.toggle('hidden');
        }
    }

    // ปิดเมนูถ้าคลิกนอก
    window.addEventListener('click', function (e) {
        if (!userMenu || !toggleUserButton) return;
        if (!userMenu.contains(e.target) && !toggleUserButton.contains(e.target)) {
            userMenu.classList.add('hidden');
        }
    });

    // --- Sidebar ---
    const sidebars = {
        favorites: document.getElementById('favoritesSidebar'),
        account: document.getElementById('accountSidebar'),
        notification: document.getElementById('notificationSidebar'),
        contact: document.getElementById('contactSidebar'),
    };

    function closeAllSidebars() {
        for (let key in sidebars) {
            if (sidebars[key]) {
                sidebars[key].classList.add('translate-x-full');
            }
        }
    }

    function openSidebar(key) {
        closeAllSidebars();
        if (sidebars[key]) {
            sidebars[key].classList.remove('translate-x-full');
        }
    }

    // ปุ่มเปิด sidebar
    const toggleSidebarButtons = {
        toggleFavorites: 'favorites',
        toggleAccount: 'account',
        toggleNotification: 'notification',
        toggleContact: 'contact',
    };

    for (let buttonId in toggleSidebarButtons) {
        const btn = document.getElementById(buttonId);
        const key = toggleSidebarButtons[buttonId];
        if (btn && key) {
            btn.addEventListener('click', () => openSidebar(key));
        }
    }

    // ปุ่มปิด sidebar
    document.querySelectorAll('.closeSidebar').forEach(button => {
        button.addEventListener('click', closeAllSidebars);
    });
});

