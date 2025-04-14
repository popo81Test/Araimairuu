// เปิดหน้าต่างเมนู

document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('foodModal');
    const content = document.getElementById('modalContent');
    const closeModalBtn = document.getElementById('closeModalBtn');

    // เปิด modal
    document.querySelectorAll('.open-modal').forEach(button => {
        button.addEventListener('click', async function (e) {
            e.preventDefault();
            const foodId = this.getAttribute('data-id');

            content.innerHTML = 'กำลังโหลด...';
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            try {
                // ดึงข้อมูลเมนู
                const res = await fetch(`details/food-detail-api.php?id=${foodId}`);
                const data = await res.json();

                if (data.error) {
                    content.innerHTML = 'ไม่พบข้อมูลเมนูนี้';
                    return;
                }

                // ตรวจสอบว่าเมนูนี้อยู่ในรายการโปรดไหม
                const favRes = await fetch(`details/fav.php?action=check&id=${foodId}`);
                const isFavorite = (await favRes.text()).trim() === 'true';

                const heart = isFavorite ? '❤️' : '🤍';

                const reviewsHTML = data.reviews.length > 0
                    ? data.reviews.map(r => `
                        <div class="border-b pb-2 mb-2">
                            <p class="font-medium">${r.name}
                                <span class="text-yellow-500 ml-2">${'★'.repeat(r.rating)}${'☆'.repeat(5 - r.rating)}</span>
                            </p>
                            <p class="text-sm text-gray-600">${r.comment}</p>
                        </div>
                    `).join('')
                    : '<p class="text-gray-500">ยังไม่มีรีวิว</p>';

                content.innerHTML = `
                    <div>
                        <h2 class="text-2xl font-bold mb-2">${data.name}</h2>
                        <img src="${data.image}" alt="${data.name}" class="w-full h-48 object-cover rounded mb-4">
                        <p class="text-gray-700 mb-2">${data.description}</p>
                        <p class="text-xl text-primary font-bold mb-4">฿${parseFloat(data.price).toFixed(2)}</p>

                        <button id="heartBtn" data-id="${data.id}" class="text-2xl mb-4" style="color: white;">${heart}</button>
                        <a href="product-action.php?action=view&id=${data.id}" class="bg-primary text-white px-4 py-2 rounded hover:bg-amber-600 ml-2">สั่งเลย</a>

                        <div class="mt-6">
                            <h3 class="text-lg font-semibold mb-2">รีวิวจากลูกค้า</h3>
                            ${reviewsHTML}
                        </div>
                    </div>
                `;

                // หลังจากโหลด content เสร็จ ค่อยใส่ event ให้ปุ่มหัวใจ
                const heartBtn = document.getElementById('heartBtn');
                heartBtn.addEventListener('click', toggleHeart);

            } catch (error) {
                content.innerHTML = 'เกิดข้อผิดพลาดในการโหลดข้อมูล';
                console.error(error);
            }
        });
    });

    // ปิด modal เมื่อกดปุ่มกากบาท
    closeModalBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    });

    // ปิด modal เมื่อคลิกพื้นหลัง
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    });
});

// toggle หัวใจ
function toggleHeart(e) {
    const button = e.currentTarget;
    const foodId = button.getAttribute('data-id');

    if (!foodId) return;

    const isFav = button.textContent === '❤️';
    const action = isFav ? 'remove' : 'add';
    const newHeart = isFav ? '🤍' : '❤️';

    fetch('details/fav.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `action=${action}&id=${encodeURIComponent(foodId)}`
    })
    .then(res => res.text())
    .then(response => {
        if (response.trim() === 'ok') {
            button.textContent = newHeart;
            // กำหนดสีโดยตรง
            button.style.color = newHeart === '❤️' ? 'red' : 'white'; // เปลี่ยน 'red' เป็นสีที่คุณต้องการ
        }
    })
    .catch(err => console.error('เกิดข้อผิดพลาด:', err));
}

