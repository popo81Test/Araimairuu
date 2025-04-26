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

                    content.innerHTML = `
                    <div class="text-center">
                        <h2 class="text-2xl font-bold mb-2">${data.name}</h2>
                        <img src="${data.image}" class="w-full h-48 object-cover rounded border border-gray-200 mb-4">
                        <p class="text-xs text-gray-400">รหัสเมนู: FD${data.id.toString().padStart(3, '0')}</p>

                        <p class="text-gray-700 mb-2">${data.description}</p>
                        <p class="text-xl text-primary font-bold mb-4">฿${parseFloat(data.price).toFixed(2)}</p>
                        <hr class="my-4 border-t border-gray-200">

                        <div class="flex justify-center">
                            <a href="product-action.php?action=view&id=${data.id}" class="bg-primary text-white px-6 py-2 rounded hover:bg-amber-600">
                                สั่งเลย
                            </a>
                        </div>
                    </div>
                `;
                

               
            } catch (error) {
                content.innerHTML = 'เกิดข้อผิดพลาดในการโหลดข้อมูล';
                console.error(error);
            }
        });
    });

    // ปิด modal เมื่อกดปุ่มกากบาท
    closeModalBtn.addEventListener('click', () => {
        content.innerHTML = '';
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

