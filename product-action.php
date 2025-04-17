<?php
require_once 'includes/functions.php';
require_once 'config/foodOrder.php';



// Initialize the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$action = isset($_GET['action']) ? $_GET['action'] : '';

// View food item
if ($action === 'view') {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $food = getFoodById($id);
    
    if (!$food) {
        // Food not found, redirect to homepage
        redirect('index.php');
    }
    
    // Get noodle types
    $noodleTypes = getAddonsByType('noodle');
    
    // Get soup types
    $soupTypes = getAddonsByType('soup');
    
    // Get toppings
    $toppings = getAddonsByType('topping');
    
    $pageTitle = $food['name'] . ' - เตี๋ยวเรือเจ๊เต้ย';
    include 'includes/header.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <a href="index.php" class="text-primary hover:text-amber-600" style="display: inline-flex; align-items: center; text-decoration: none; font-weight: 500;">
            <i class="fas fa-arrow-left mr-2"></i> กลับหน้าหลัก
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow-lg overflow-hidden" style="max-width: 1200px; margin: 0 auto;">
        <div class="md:flex" style="display: flex; flex-wrap: wrap; align-items: stretch;">
            <div class="md:w-1/2" style="flex: 1 1 50%; min-width: 300px; max-width: 50%; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                <img src="<?php echo !empty($food['image']) ? $food['image'] : 'https://images.unsplash.com/photo-1637806376426-3c40e10627c8?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=60'; ?>" 
                     alt="<?php echo $food['name']; ?>" 
                     class="w-full h-64 md:h-full object-cover" style="width: 100%; height: 100%; max-height: 500px; object-fit: cover;">
            </div>
            
            <div class="md:w-1/2 p-6" style="flex: 1 1 50%; min-width: 300px; max-width: 50%; padding: 2rem; box-sizing: border-box;">
                <h1 class="text-2xl font-bold mb-2" style="font-size: 1.8rem; font-weight: 700; margin-bottom: 0.75rem; color: #333; line-height: 1.3;"><?php echo $food['name']; ?></h1>
                <p class="text-gray-700 mb-4" style="color: #4a5568; margin-bottom: 1.25rem; font-size: 1.05rem; line-height: 1.5;"><?php echo $food['description']; ?></p>
                <p class="text-2xl font-bold text-primary mb-6" style="font-size: 1.75rem; font-weight: 700; color: #f04935; margin-bottom: 2rem; display: inline-block; padding: 0.5rem 0.75rem; background-color: #fff5f5; border-radius: 0.5rem;"><?php echo number_format($food['price'], 2); ?> ฿</p>
                
                <form action="product-action.php?action=add_to_cart" method="POST" class="space-y-6" style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <input type="hidden" name="food_id" value="<?php echo $food['id']; ?>">
                    <input type="hidden" name="food_name" value="<?php echo $food['name']; ?>">
                    <input type="hidden" name="food_price" value="<?php echo $food['price']; ?>">
                    <input type="hidden" name="food_image" value="<?php echo $food['image']; ?>">
                    
                    <!-- Noodle Type Selection -->
                    <?php if (!empty($noodleTypes) && $food['category_id'] == 2): ?>
                    <div>
                        <h3 class="text-lg font-semibold mb-2" style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.5rem;">เส้น <span style="color: #f04935;">*</span></h3>
                        <div class="grid grid-cols-2 gap-2" style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0.75rem;">
                            <?php foreach ($noodleTypes as $index => $noodle): ?>
                                <label class="flex items-center space-x-2" style="display: flex; align-items: center; cursor: pointer; margin-bottom: 0.5rem;">
                                    <input type="radio" name="noodle_type" value="<?php echo $noodle['id']; ?>" 
                                           <?php echo ($index === 0) ? 'checked' : ''; ?> 
                                           style="margin-right: 0.5rem; min-width: 18px; min-height: 18px;">
                                    <span style="font-size: 1rem;"><?php echo $noodle['name']; ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Soup Type Selection -->
                    <?php if (!empty($soupTypes) && $food['category_id'] == 2): ?>
                    <div>
                        <h3 class="text-lg font-semibold mb-2" style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.5rem;">ประเภทน้ำ <span style="color: #f04935;">*</span></h3>
                        <div class="grid grid-cols-2 gap-2" style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0.75rem;">
                            <?php foreach ($soupTypes as $index => $soup): ?>
                                <label class="flex items-center space-x-2" style="display: flex; align-items: center; cursor: pointer; margin-bottom: 0.5rem;">
                                    <input type="radio" name="soup_type" value="<?php echo $soup['id']; ?>" 
                                           <?php echo ($index === 0) ? 'checked' : ''; ?> 
                                           style="margin-right: 0.5rem; min-width: 18px; min-height: 18px;">
                                    <span style="font-size: 1rem;"><?php echo $soup['name']; ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Extra Toppings -->
                    <?php if (!empty($toppings) && $food['category_id'] == 2): ?>
                    <div>
                        <h3 class="text-lg font-semibold mb-2" style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.5rem;">เพิ่มเครื่อง <span style="color: #718096; font-size: 0.9rem;">(เลือกได้ไม่จำกัด)</span></h3>
                        <div class="grid grid-cols-2 gap-2" style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0.75rem;">
                            <?php foreach ($toppings as $topping): ?>
                                <label class="flex items-center space-x-2" style="display: flex; align-items: center; cursor: pointer; margin-bottom: 0.5rem;">
                                    <input type="checkbox" name="toppings[]" value="<?php echo $topping['id']; ?>" 
                                           style="margin-right: 0.5rem; min-width: 18px; min-height: 18px;">
                                    <span style="font-size: 0.95rem;"><?php echo $topping['name']; ?> <span style="color: #f04935;">+฿<?php echo number_format($topping['price'], 2); ?></span></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Special Instructions -->
                    <div>
                        <h3 class="text-lg font-semibold mb-2" style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.5rem;">รายละเอียดเพิ่มเติม (ถ้ามี)</h3>
                        <textarea name="special_instructions" placeholder="ระบุความต้องการพิเศษ เช่น ไม่ผงชูรส, ไม่ใส่ผัก, เผ็ดน้อย, เผ็ดมาก" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary" 
                                  style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.375rem; font-size: 1rem; min-height: 80px; resize: vertical;"
                                  rows="3"></textarea>
                    </div>
                    
                    <!-- Quantity -->
                    <div>
                        <h3 class="text-lg font-semibold mb-2" style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.5rem;">จำนวน</h3>
                        <div class="flex items-center" style="display: flex; align-items: center; justify-content: center; width: 100%; max-width: 200px; margin: 0 auto;">
                            <button type="button" onclick="decrementQuantity()" 
                                    class="bg-gray-200 text-gray-700 w-10 h-10 rounded-full flex items-center justify-center hover:bg-gray-300"
                                    style="background-color: #edf2f7; color: #4a5568; width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: none; cursor: pointer; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                                <i class="fas fa-minus" style="font-size: 1rem;"></i>
                            </button>
                            
                            <input type="number" name="quantity" id="quantity" value="1" min="1" max="10" 
                                   class="w-16 mx-4 text-center border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary"
                                   style="width: 70px; margin: 0 1.5rem; text-align: center; border: 1px solid #e2e8f0; border-radius: 0.375rem; padding: 0.6rem; font-size: 1.2rem; font-weight: 600;">
                            
                            <button type="button" onclick="incrementQuantity()" 
                                    class="bg-gray-200 text-gray-700 w-10 h-10 rounded-full flex items-center justify-center hover:bg-gray-300"
                                    style="background-color: #edf2f7; color: #4a5568; width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: none; cursor: pointer; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                                <i class="fas fa-plus" style="font-size: 1rem;"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Add to Cart Button -->
                    <button type="submit" class="w-full bg-primary text-white py-3 px-6 rounded-lg font-semibold hover:bg-amber-600 transition"
                            style="width: 100%; background-color: #f04935; color: white; padding: 1rem 1.5rem; border-radius: 0.5rem; font-weight: 600; border: none; cursor: pointer; margin-top: 2rem; transition: background-color 0.2s ease; font-size: 1.1rem; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                        เพิ่มลงตะกร้า <?php echo number_format($food['price'], 2); ?> ฿
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="Js/product-action.js"></script>

<?php
    include 'includes/footer.php';
    exit;
}

// Add to cart
if ($action === 'add_to_cart' || $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user is logged in
    if (!isLoggedIn()) {
        redirect('logSign.php');
    }
    
    // Get form data
    $foodId = isset($_POST['food_id']) ? intval($_POST['food_id']) : 0;
    $foodName = isset($_POST['food_name']) ? $_POST['food_name'] : '';
    $foodPrice = isset($_POST['food_price']) ? floatval($_POST['food_price']) : 0;
    $foodImage = isset($_POST['food_image']) ? $_POST['food_image'] : '';
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    $specialInstructions = isset($_POST['special_instructions']) ? $_POST['special_instructions'] : '';
    
    // Get selected noodle type
    $noodleTypeId = isset($_POST['noodle_type']) ? intval($_POST['noodle_type']) : 0;
    $noodleType = [];
    if ($noodleTypeId > 0) {
        $query = "SELECT * FROM addon_options WHERE id = $noodleTypeId";
        $result = mysqli_query($conn, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $noodleType = mysqli_fetch_assoc($result);
        }
    }
    
    // Get selected soup type
    $soupTypeId = isset($_POST['soup_type']) ? intval($_POST['soup_type']) : 0;
    $soupType = [];
    if ($soupTypeId > 0) {
        $query = "SELECT * FROM addon_options WHERE id = $soupTypeId";
        $result = mysqli_query($conn, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $soupType = mysqli_fetch_assoc($result);
        }
    }
    
    // Get selected toppings
    $selectedToppings = isset($_POST['toppings']) ? $_POST['toppings'] : [];
    $toppings = [];
    if (!empty($selectedToppings)) {
        $toppingIds = implode(',', array_map('intval', $selectedToppings));
        $query = "SELECT * FROM addon_options WHERE id IN ($toppingIds)";
        $result = mysqli_query($conn, $query);
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $toppings[] = $row;
            }
        }
    }
    
    // Create addons array
    $addons = [];
    
    // Add noodle type
    if (!empty($noodleType)) {
        $addons[] = [
            'id' => $noodleType['id'],
            'name' => $noodleType['name'],
            'type' => $noodleType['type'],
            'price' => floatval($noodleType['price'])
        ];
    }
    
    // Add soup type
    if (!empty($soupType)) {
        $addons[] = [
            'id' => $soupType['id'],
            'name' => $soupType['name'],
            'type' => $soupType['type'],
            'price' => floatval($soupType['price'])
        ];
    }
    
    // Add toppings
    foreach ($toppings as $topping) {
        $addons[] = [
            'id' => $topping['id'],
            'name' => $topping['name'],
            'type' => $topping['type'],
            'price' => floatval($topping['price'])
        ];
    }
    
    // Create cart item
    $cartItem = [
        'id' => $foodId,
        'name' => $foodName,
        'price' => $foodPrice,
        'quantity' => $quantity,
        'image' => $foodImage,
        'special_instructions' => $specialInstructions,
        'addons' => $addons
    ];
    
    // Add to cart
    $_SESSION['cart'][] = $cartItem;
    
    // Redirect to cart page
    redirect('checkout.php');
}

// Remove from cart
if ($action === 'remove_from_cart') {
    $index = isset($_GET['index']) ? intval($_GET['index']) : -1;
    
    if ($index >= 0 && isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);
        // Re-index array
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
    
    // Redirect to cart page
    redirect('checkout.php');
}

// Update cart item quantity
if ($action === 'update_quantity') {
    $index = isset($_GET['index']) ? intval($_GET['index']) : -1;
    $quantity = isset($_GET['quantity']) ? intval($_GET['quantity']) : 1;
    
    if ($index >= 0 && isset($_SESSION['cart'][$index])) {
        if ($quantity > 0) {
            $_SESSION['cart'][$index]['quantity'] = $quantity;
        } else {
            // Remove item if quantity is 0
            unset($_SESSION['cart'][$index]);
            // Re-index array
            $_SESSION['cart'] = array_values($_SESSION['cart']);
        }
    }
    
    // Redirect to cart page
    redirect('checkout.php');
}

// Clear cart
if ($action === 'clear_cart') {
    $_SESSION['cart'] = [];
    
    // Redirect to cart page
    redirect('checkout.php');
}

// Default: redirect to homepage
redirect('index.php');
?>

<head>
<style>
    .max-w-7xl.mx-auto.px-4.sm\:px-6.lg\:px-8.py-8 {
        max-width: 100%;
        margin: 0 auto;
        padding: 1rem;
        box-sizing: border-box;
    }
    
    /* Improve the layout on mobile devices */
    @media (max-width: 768px) {
        .md\:flex {
            flex-direction: column !important;
        }
        .md\:w-1\/2 {
            width: 100% !important;
            max-width: 100% !important;
            flex: 1 1 100% !important;
        }
        img.w-full.h-64.md\:h-full.object-cover {
            height: 300px !important;
            object-fit: cover;
            width: 100% !important;
        }
        
        .grid.grid-cols-2.gap-2 {
            grid-template-columns: 1fr !important; /* Single column on very small screens */
        }
    }
    
    /* Additional adjustments for extra small screens */
    @media (max-width: 480px) {
        .p-6 {
            padding: 1rem !important;
        }
        
        h1.text-2xl, p.text-2xl {
            font-size: 1.25rem !important;
        }
        
        h3.text-lg {
            font-size: 1rem !important;
        }
        
        .flex.items-center button {
            width: 40px !important;
            height: 40px !important;
        }
        
        input[type="number"] {
            width: 50px !important;
        }
    }
    
    /* Better spacing between elements */
    .p-6 {
        padding: 1.5rem;
    }
    
    /* Improve form controls for better touch targets */
    .quantity-controls button, input[type="number"] {
        min-height: 44px;
        min-width: 44px;
    }
    
    /* Make the add to cart button more prominent */
    button[type="submit"] {
        width: 100%;
        padding: 0.75rem;
        margin-top: 1rem;
        font-weight: bold;
    }
    
    /* Better spacing for radio and checkbox options */
    .grid.grid-cols-2.gap-2 label {
        margin-bottom: 8px;
        display: flex;
        align-items: center;
    }
</style>
</head>