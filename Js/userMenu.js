document.addEventListener('DOMContentLoaded', function () {
    // --- User dropdown menu ---
    const userMenu = document.getElementById('userMenu');
    const toggleUserButton = document.querySelector('button[onclick="toggleUserMenu()"]');

    window.toggleUserMenu = function () {
        if (userMenu) {
            userMenu.classList.toggle('hidden');
        }
    }

    window.addEventListener('click', function (e) {
        if (!userMenu || !toggleUserButton) return;
        if (!userMenu.contains(e.target) && !toggleUserButton.contains(e.target)) {
            userMenu.classList.add('hidden');
        }
    });

    // --- Sidebars ---
    const sidebars = {
        
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
        if (sidebars[key]) {
            sidebars[key].classList.remove('translate-x-full');
        }

        if (key === 'notification') {
            const floatingNotificationBadge = document.getElementById('floatingNotificationBadge');
            if (floatingNotificationBadge) {
                floatingNotificationBadge.classList.add('hidden');
            }
            markNotificationsAsRead();
        }
    }

    function markNotificationsAsRead() {
        fetch('check_notifications.php?markAsRead=1')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    console.log('Notifications marked as read');
                    checkNewNotifications(); // Refresh notifications after marking as read
                } else {
                    console.warn('Server responded, but marking as read was not successful:', data.message);
                }
            })
            .catch(error => {
                console.error('Error marking notifications as read:', error);
            });
    }

    function checkNewNotifications() {
        fetch('check_notifications.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                console.log('Notification data received:', data); // Debug output
                
                const floatingNotificationBadge = document.getElementById('floatingNotificationBadge');
                const notificationList = document.getElementById('notificationList');
                
                // Handle floating notification badge
                if (floatingNotificationBadge) {
                    if (data.hasNew) {
                        floatingNotificationBadge.textContent = data.count > 0 ? data.count : '';
                        floatingNotificationBadge.classList.remove('hidden');
                    } else {
                        floatingNotificationBadge.classList.add('hidden');
                    }
                }

                // Update notification list if available
                if (notificationList && data.orders && Array.isArray(data.orders)) {
                    notificationList.innerHTML = '';
                    
                    if (data.orders.length > 0) {
                        data.orders.forEach(order => {
                            const listItem = document.createElement('div');
                            listItem.classList.add('bg-gray-50', 'p-3', 'rounded-lg', 'border', 'mb-2');
                            
                            // Add appropriate status color class
                            const statusColorClass = getStatusColorClass(order.status);
                            
                            listItem.innerHTML = `
                                <div class="flex justify-between">
                                    <span class="font-medium">ออเดอร์ #${order.id}</span>
                                    <span class="${statusColorClass} text-sm">${order.status_text}</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">${order.items_text}</p>
                                <span class="text-xs text-gray-500 block mt-1">${order.time_ago}</span>
                            `;
                            notificationList.appendChild(listItem);
                        });
                    } else {
                        notificationList.innerHTML = '<p class="text-sm text-gray-500">คุณยังไม่มีการแจ้งเตือน</p>';
                    }
                }
                
                // Update latest status if element exists
                const latestStatusElement = document.getElementById('latestOrderStatus');
                if (latestStatusElement && data.latestOrderStatus) {
                    latestStatusElement.textContent = data.latestOrderStatus;
                }
            })
            .catch(error => {
                console.error('Failed to check notifications:', error);
            });
    }

    function getStatusColorClass(status) {
        switch (status) {
            case 'pending': return 'text-yellow-600';
            case 'processing': return 'text-blue-600';
            case 'completed': return 'text-green-600';
            case 'cancelled': return 'text-red-600';
            default: return 'text-gray-600';
        }
    }

    // Set up sidebar toggle buttons
    const toggleSidebarButtons = {
        
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

    // Set up floating notification button
    const floatingNotificationButton = document.getElementById('floatingNotificationButton');
    const floatingNotificationButtonIcon = document.getElementById('floatingNotificationButtonIcon');

    if (floatingNotificationButton) {
        floatingNotificationButton.addEventListener('click', () => openSidebar('notification'));
    }

    // Set up close sidebar buttons
    document.querySelectorAll('.closeSidebar').forEach(button => {
        button.addEventListener('click', closeAllSidebars);
    });

    // Check for new notifications on page load and periodically
    checkNewNotifications();
    
    // Poll for new notifications every 60 seconds
    setInterval(checkNewNotifications, 60000);
});