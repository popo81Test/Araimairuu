const container = document.getElementById('container');
        const overlayCon = document.getElementById('overlayCon');
        const overlayBtn = document.getElementById('overlayBtn');
        const signUpOverlay = document.getElementById('signUpOverlay');
        const signInOverlay = document.getElementById('signInOverlay');
        const welcomeBackButton = document.getElementById('welcomeBackButton');
        const helloFriendButton = document.getElementById('helloFriendButton');

        overlayBtn.addEventListener('click', () => {
            container.classList.toggle('right-panel-active');

            overlayBtn.classList.remove('btnScaled');
            window.requestAnimationFrame(() => {
                overlayBtn.classList.add('btnScaled');
            });
        });

        signUpOverlay.addEventListener('click', () => {
            container.classList.add('right-panel-active');
        });

        signInOverlay.addEventListener('click', () => {
            container.classList.remove('right-panel-active');
        });

        welcomeBackButton.addEventListener('click', () => {
            window.location.href = 'index.php'; // เปลี่ยนหน้าโดยไม่โหลดหน้าใหม่
        });

        helloFriendButton.addEventListener('click', () => {
            window.location.href = 'index.php'; // เปลี่ยนหน้าโดยไม่โหลดหน้าใหม่
        });

        
        window.onload = function() {
            

            const overlayBtn = document.getElementById('overlayBtn');
            if (overlayBtn && window.opener) {
                
                overlayBtn.click();
            }
        };
    