<?php
$pageTitle = "จัดการอาหาร - เตี๋ยวเรือเจ๊เต้ย";
include 'includes/header.php';
include 'config/foodOrder.php';

// Redirect if not admin
if (!isAdmin()) {
    redirect('index.php');
}


$error = '';
$success = '';
$formMode = 'add';
$editId = 0;
$name = '';
$description = '';
$price = '';
$category_id = '';
$is_recommended = 0;
$image = '';

// Get categories
$categories = getAllCategories();

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    
    if ($action === 'add' || $action === 'edit') {
        // Get form data
        $name = sanitize($_POST['name']);
        $description = sanitize($_POST['description']);
        $price = sanitize($_POST['price']);
        $category_id = sanitize($_POST['category_id']);
        $is_recommended = isset($_POST['is_recommended']) ? 1 : 0;
        $image_url = isset($_POST['image_url']) ? sanitize($_POST['image_url']) : '';
        
        
        //test
        $image = $image_url;
        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/';
            $fileName = basename($_FILES['image_file']['name']);
            $targetPath = $uploadDir . $fileName;
        
            if (move_uploaded_file($_FILES['image_file']['tmp_name'], $targetPath)) {
                $image = $targetPath;
            }
            $image = $targetPath;
        }
        
        

        
        // Validate input
        if (empty($name) || empty($price) || empty($category_id)) {
            $error = 'กรุณากรอกชื่ออาหาร, ราคา และเลือกหมวดหมู่';
        } else {
            if ($action === 'add') {
                // Insert food
                $query = "INSERT INTO foods (name, description, price, category_id, is_recommended, image) 
                          VALUES ('$name', '$description', '$price', '$category_id', '$is_recommended', '$image')";
                
                if (mysqli_query($conn, $query)) {
                    $success = 'เพิ่มอาหารสำเร็จ';
                    
                    // Clear form fields
                    $name = '';
                    $description = '';
                    $price = '';
                    $category_id = '';
                    $is_recommended = 0;
                    $image = '';
                } else {
                    $error = 'เกิดข้อผิดพลาดในการเพิ่มอาหาร: ' . mysqli_error($conn);
                }
            } elseif ($action === 'edit') {
                $id = sanitize($_POST['id']);
                
                // Update food
                $query = "UPDATE foods SET 
                          name = '$name', 
                          description = '$description', 
                          price = '$price', 
                          category_id = '$category_id', 
                          is_recommended = '$is_recommended', 
                          image = '$image' 
                          WHERE id = '$id'";
                
                if (mysqli_query($conn, $query)) {
                    $success = 'แก้ไขอาหารสำเร็จ';
                    
                    // Clear form fields and reset form mode
                    $formMode = 'add';
                    $editId = 0;
                    $name = '';
                    $description = '';
                    $price = '';
                    $category_id = '';
                    $is_recommended = 0;
                    $image = '';
                } else {
                    $error = 'เกิดข้อผิดพลาดในการแก้ไขอาหาร: ' . mysqli_error($conn);
                }
            }
        }
    } elseif ($action === 'delete') {
        $id = sanitize($_POST['id']);
        
        // Delete food
        $query = "DELETE FROM foods WHERE id = '$id'";
        
        if (mysqli_query($conn, $query)) {
            $success = 'ลบอาหารสำเร็จ';
        } else {
            $error = 'เกิดข้อผิดพลาดในการลบอาหาร: ' . mysqli_error($conn);
        }
    }
}

// Handle edit request
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $food = getFoodById($id);
    
    if ($food) {
        $formMode = 'edit';
        $editId = $id;
        $name = $food['name'];
        $description = $food['description'];
        $price = $food['price'];
        $category_id = $food['category_id'];
        $is_recommended = $food['is_recommended'];
        $image = $food['image'];
    }
}

// Get all foods
$foods = getAllFoods();
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold mb-6">จัดการอาหาร</h1>
    
    <?php if (!empty($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?php echo $error; ?></span>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($success)): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?php echo $success; ?></span>
        </div>
    <?php endif; ?>
    
    <div class="lg:flex lg:space-x-8">
        <div class="lg:w-1/3 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold mb-4">
                    <?php echo $formMode === 'add' ? 'เพิ่มอาหารใหม่' : 'แก้ไขอาหาร'; ?>
                </h2>
                
                <form method="POST" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="space-y-4">
                    <input type="hidden" name="action" value="<?php echo $formMode; ?>">
                    <?php if ($formMode === 'edit'): ?>
                        <input type="hidden" name="id" value="<?php echo $editId; ?>">
                    <?php endif; ?>
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">ชื่ออาหาร <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" value="<?php echo $name; ?>" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
                    </div>
                    
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">รายละเอียด</label>
                        <textarea id="description" name="description" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary"><?php echo $description; ?></textarea>
                    </div>
                    
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">ราคา <span class="text-red-500">*</span></label>
                        <input type="number" id="price" name="price" value="<?php echo $price; ?>" step="0.01" min="0" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
                    </div>
                    
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">หมวดหมู่ <span class="text-red-500">*</span></label>
                        <select id="category_id" name="category_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
                            <option value="">เลือกหมวดหมู่</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>" <?php echo $category_id == $category['id'] ? 'selected' : ''; ?>>
                                    <?php echo $category['display_name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">รูปภาพ</label>
                        <div class="space-y-2">
                            <div>
                                <label for="image_url" class="block text-sm font-medium text-gray-700 mb-1">ใส่ URL รูปภาพ:</label>
                                <input type="text" id="image_url" name="image_url" value="<?php echo htmlspecialchars($image); ?>"
                                       placeholder="https://example.com/image.jpg"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
                                <?php if ($formMode === 'edit' && !empty($image) && strpos($image, 'http') === 0): ?>
                                    <p class="text-gray-500 text-xs mt-1">URL ปัจจุบัน: <?php echo $image; ?></p>
                                <?php endif; ?>
                            </div>
                            <div>
                                <label for="image_file" class="block text-sm font-medium text-gray-700 mb-1">หรือ อัปโหลดไฟล์รูปภาพ:</label>
                                <input type="file" id="image_file" name="image_file"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
                                <?php if ($formMode === 'edit' && !empty($image) && strpos($image, 'uploads/') !== false): ?>
                                    <p class="text-gray-500 text-xs mt-1">ไฟล์ปัจจุบัน: <?php echo basename($image); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" id="is_recommended" name="is_recommended" value="1" <?php echo $is_recommended ? 'checked' : ''; ?>
                               class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                        <label for="is_recommended" class="ml-2 block text-sm text-gray-700">
                            แสดงในเมนูแนะนำ
                        </label>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <button type="submit" class="bg-primary text-white py-2 px-4 rounded-md hover:bg-amber-600 transition focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                            <?php echo $formMode === 'add' ? 'เพิ่มอาหาร' : 'บันทึกการแก้ไข'; ?>
                        </button>
                        
                        <?php if ($formMode === 'edit'): ?>
                            <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="text-gray-600 hover:text-gray-800">
                                ยกเลิก
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="lg:w-2/3">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">รูปภาพ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ชื่อ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ราคา</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">หมวดหมู่</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">แนะนำ</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php if (empty($foods)): ?>
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    ไม่พบข้อมูลอาหาร
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($foods as $food): ?>
                                <?php
                                    // Get category name
                                    $categoryName = '';
                                    foreach ($categories as $category) {
                                        if ($category['id'] == $food['category_id']) {
                                            $categoryName = $category['display_name'];
                                            break;
                                        }
                                    }
                                ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if (!empty($food['image'])): ?>
                                            <img src="<?php echo $food['image']; ?>" alt="<?php echo $food['name']; ?>" class="w-12 h-12 object-cover rounded">
                                        <?php else: ?>
                                            <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                                <i class="fas fa-utensils text-gray-400"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900"><?php echo $food['name']; ?></div>
                                        <?php if (!empty($food['description'])): ?>
                                            <div class="text-xs text-gray-500"><?php echo substr($food['description'], 0, 50); ?><?php echo strlen($food['description']) > 50 ? '...' : ''; ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        ฿<?php echo number_format($food['price'], 2); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo $categoryName; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <?php if ($food['is_recommended']): ?>
                                            <span class="text-green-500"><i class="fas fa-check"></i></span>
                                        <?php else: ?>
                                            <span class="text-gray-300"><i class="fas fa-times"></i></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?action=edit&id=<?php echo $food['id']; ?>" 
                                               class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="inline" 
                                                  onsubmit="return confirm('คุณต้องการลบอาหารรายการนี้ใช่หรือไม่?');">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?php echo $food['id']; ?>">
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<?php include 'includes/footer.php'; ?>