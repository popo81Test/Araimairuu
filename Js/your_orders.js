function updateOrderStatus(orderId, newStatus, event = window.event) {
    const select = event.target;
    const originalValue = select.value;

    Swal.fire({
        title: 'ยืนยันการอัปเดตสถานะ?',
  text: 'คุณต้องการเปลี่ยนสถานะคำสั่งซื้อนี้หรือไม่?',
  icon: 'warning',
  showCancelButton: true,
  confirmButtonText: 'ใช่, เปลี่ยนเลย!',
  cancelButtonText: 'ยกเลิก',
  customClass: {
    popup: 'rounded-xl shadow-lg border border-orange-200 bg-white',
    title: 'text-lg font-bold text-orange-700',
    htmlContainer: 'text-sm text-gray-700',
    confirmButton: 'bg-orange-500 hover:bg-orange-600 text-white font-semibold px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-300',
    cancelButton: 'bg-yellow-300 hover:bg-yellow-400 text-gray-800 font-medium px-4 py-2 rounded-md ml-2 focus:outline-none focus:ring-2 focus:ring-yellow-400'
  },
  buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('http://localhost/cp151/test05/update_order_status.php', { // ตรวจสอบ path อีกครั้ง
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    order_id: orderId,
                    status: newStatus
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const statusClasses = {
                        'pending': 'bg-yellow-100 text-yellow-800',
                        'processing': 'bg-blue-100 text-blue-800',
                        'out_for_delivery': 'bg-purple-100 text-purple-800',
                        'delivered': 'bg-indigo-100 text-indigo-800',
                        'completed': 'bg-green-100 text-green-800',
                        'cancelled': 'bg-red-100 text-red-800'
                    };

                    // ลบคลาสสถานะเดิมทั้งหมด
                    for (const className of Object.values(statusClasses)) {
                        const classList = className.split(' ');
                        select.classList.remove(...classList);
                    }
                    // เพิ่มคลาสสถานะใหม่
                    select.classList.add(...statusClasses[newStatus].split(' '));


                    Swal.fire({
                        icon: 'success',
                        title: 'อัปเดตสำเร็จ!',
                        text: 'สถานะถูกอัปเดตแล้ว กรุณารีหน้าเพื่อดูผลลัพธ์',
                        confirmButtonText: 'รีหน้า',
                        customClass: {
                        popup: 'rounded-xl shadow-md border border-yellow-300 bg-white',
                        title: 'text-lg font-bold text-green-600',
                        htmlContainer: 'text-sm text-gray-700',
                        confirmButton: 'bg-yellow-400 hover:bg-yellow-500 text-white font-medium px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-400'
                        },
                        buttonsStyling: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด!',
                        text: 'ไม่สามารถอัปเดตสถานะคำสั่งซื้อได้ กรุณาลองใหม่อีกครั้ง',
                        confirmButtonText: 'ตกลง',
                        customClass: {
                        popup: 'rounded-xl shadow-md border border-red-300 bg-white',
                        title: 'text-lg font-bold text-red-700',
                        htmlContainer: 'text-sm text-gray-700',
                        confirmButton: 'bg-red-500 hover:bg-red-600 text-white font-medium px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-red-400'
                        },
                        buttonsStyling: false
                    });
                    select.value = originalValue;
                }
            })
            .catch(error => {
                console.error("Fetch Error:", error); // ล็อกไว้เผื่อ debug ภายหลัง
                Swal.fire({
                    icon: 'info',
                    title: 'กรุณารีหน้าใหม่เพื่อแสดงรายการอัปเดต',
                    text: 'มีบางอย่างผิดพลาดในการสื่อสารกับเซิร์ฟเวอร์ แต่ข้อมูลอาจถูกอัปเดตแล้ว',
                    confirmButtonText: 'รีหน้า',
                    confirmButtonColor: '#3b82f6'
                }).then(() => {
                    location.reload();
                });
            });
        }
    });

}

function toggleOrderDetails(orderId) {
    const detailsRow = document.getElementById('order-details-' + orderId);
    const button = document.querySelector(`button[onclick="toggleOrderDetails(${orderId})"]`);
    const eyeIcon = button.querySelector('i');

    if (detailsRow) {
        if (detailsRow.classList.contains('hidden')) {
            detailsRow.classList.remove('hidden');
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            detailsRow.classList.add('hidden');
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }
}