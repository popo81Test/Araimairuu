

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
    
    $pageTitle = $food['name'] . ' - ร้านก๋วยเตี๋ยวมหานคร';
    include 'includes/header.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <a href="index.php" class="text-primary hover:text-amber-600">
            <i class="fas fa-arrow-left mr-2"></i> กลับหน้าหลัก
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="md:flex">
            <div class="md:w-1/2">
                <img src="<?php echo !empty($food['image']) ? $food['image'] : 'https://images.unsplash.com/photo-1637806376426-3c40e10627c8?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=60'; ?>" 
                     alt="<?php echo $food['name']; ?>" 
                     class="w-full h-64 md:h-full object-cover">
            </div>
            
            <div class="md:w-1/2 p-6">
                <h1 class="text-2xl font-bold mb-2"><?php echo $food['name']; ?></h1>
                <p class="text-gray-700 mb-4"><?php echo $food['description']; ?></p>
                <p class="text-2xl font-bold text-primary mb-6">฿<?php echo number_format($food['price'], 2); ?></p>
                
                <form action="product-action.php?action=add_to_cart" method="POST" class="space-y-6">
                    <input type="hidden" name="food_id" value="<?php echo $food['id']; ?>">
                    <input type="hidden" name="food_name" value="<?php echo $food['name']; ?>">
                    <input type="hidden" name="food_price" value="<?php echo $food['price']; ?>">
                    <input type="hidden" name="food_image" value="<?php echo $food['image']; ?>">
                    
                    <!-- Noodle Type Selection -->
                    <?php if (!empty($noodleTypes)): ?>
                    <div>
                        <h3 class="text-lg font-semibold mb-2">เส้น <span class="text-red-500">*</span></h3>
                        <div class="grid grid-cols-2 gap-2">
                            <?php foreach ($noodleTypes as $index => $noodle): ?>
                                <label class="flex items-center space-x-2">
                                    <input type="radio" name="noodle_type" value="<?php echo $noodle['id']; ?>" 
                                           <?php echo ($index === 0) ? 'checked' : ''; ?> 
                                           class="text-primary focus:ring-primary">
                                    <span><?php echo $noodle['name']; ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Soup Type Selection -->
                    <?php if (!empty($soupTypes)): ?>
                    <div>
                        <h3 class="text-lg font-semibold mb-2">ประเภทน้ำ <span class="text-red-500">*</span></h3>
                        <div class="grid grid-cols-2 gap-2">
                            <?php foreach ($soupTypes as $index => $soup): ?>
                                <label class="flex items-center space-x-2">
                                    <input type="radio" name="soup_type" value="<?php echo $soup['id']; ?>" 
                                           <?php echo ($index === 0) ? 'checked' : ''; ?> 
                                           class="text-primary focus:ring-primary">
                                    <span><?php echo $soup['name']; ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Extra Toppings -->
                    <?php if (!empty($toppings)): ?>
                    <div>
                        <h3 class="text-lg font-semibold mb-2">เพิ่มเครื่อง <span class="text-gray-500">(เลือกได้ไม่จำกัด)</span></h3>
                        <div class="grid grid-cols-2 gap-2">
                            <?php foreach ($toppings as $topping): ?>
                                <label class="flex items-center space-x-2">
                                    <input type="checkbox" name="toppings[]" value="<?php echo $topping['id']; ?>" 
                                           class="text-primary focus:ring-primary">
                                    <span><?php echo $topping['name']; ?> +฿<?php echo number_format($topping['price'], 2); ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Special Instructions -->
                    <div>
                        <h3 class="text-lg font-semibold mb-2">รายละเอียดเพิ่มเติม (ถ้ามี)</h3>
                        <textarea name="special_instructions" placeholder="ระบุความต้องการพิเศษ เช่น ไม่ผงชูรส, ไม่ใส่ผัก, เผ็ดน้อย, เผ็ดมาก" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary" 
                                  rows="3"></textarea>
                    </div>
                    
                    <!-- Quantity -->
                    <div>
                        <h3 class="text-lg font-semibold mb-2">จำนวน</h3>
                        <div class="flex items-center">
                            <button type="button" onclick="decrementQuantity()" 
                                    class="bg-gray-200 text-gray-700 w-10 h-10 rounded-full flex items-center justify-center hover:bg-gray-300">
                                <i class="fas fa-minus"></i>
                            </button>
                            
                            <input type="number" name="quantity" id="quantity" value="1" min="1" max="10" 
                                   class="w-16 mx-4 text-center border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
                            
                            <button type="button" onclick="incrementQuantity()" 
                                    class="bg-gray-200 text-gray-700 w-10 h-10 rounded-full flex items-center justify-center hover:bg-gray-300">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Add to Cart Button -->
                    <button type="submit" class="w-full bg-primary text-white py-3 px-6 rounded-lg font-semibold hover:bg-amber-600 transition">
                        เพิ่มลงตะกร้า ฿<?php echo number_format($food['price'], 2); ?>
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
        transform: scale(1.0); /* 1.7 คือ 170% ของขนาดเดิม */
        transform-origin: top left; /* ให้การปรับขนาดเริ่มต้นจากมุมบนซ้าย */
        width: calc(100% / 1.0); /* ปรับความกว้างเพื่อชดเชยการขยาย */
        height: calc(100% / 1.0); /* ปรับความสูงเพื่อชดเชยการขยาย (ถ้าจำเป็น) */
        margin-bottom:20%;
    }
</style>
</head>