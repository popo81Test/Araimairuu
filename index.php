<?php
session_start();
$pageTitle = "เตี๋ยวเรือเจ๊เต้ย - หน้าหลัก";
$customCss = 'style.css';
include 'includes/header.php';


// Get all categories
$categories = getAllCategories();

// Get recommended foods
$recommendedFoods = [];
$query = "SELECT * FROM foods WHERE is_recommended = 1 LIMIT 6";
$result = mysqli_query($conn, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $recommendedFoods[] = $row;
    }
}

// Get all foods
$allFoods = getAllFoods();
?>

<!-- Upper start -->
<!--Picture header start -->
<div class="carousel">
        <!-- list item -->
        <div class="list" >
            <div class="item">
                <img src="images/img/00R.jpg">
                <div class="content">
                    <div class="author">เตี๋ยวเรือเจ้เต้ย</div>
                    <div class="title">อร่อยครบ จบทุกมื้อ</div>
                    <div class="topic">ก๋วยเตี๋ยวเรือ</div>
                    <div class="des">
                        <!-- lorem 50 -->
                        น้ำซุปเข้มข้น หอมสมุนไพร เส้นเหนียวนุ่ม พร้อมเนื้อหมู/เนื้อวัวเปื่อยละลายในปาก เจเต้ยปรุงด้วยใจทุกชาม อร่อยจนหยดสุดท้าย
                    </div>
                    <div class="buttons">
                        <button><a href="#menu">เมนูอาหาร</a></button>
                        <?php if (!isLoggedIn()): ?>
                        <button><a href="logSign.php">สมัครสมาชิก</a></button>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
            <div class="item">
                <img src="images/img/12fo.jpg">
                <div class="content">
                    <div class="author">เตี๋ยวเรือเจ้เต้ย</div>
                    <div class="title">อร่อยครบ จบทุกมื้อ</div>
                    <div class="topic">เย็นตาโฟ</div>
                    <div class="des">
                        สีแดงสดจากเต้าหู้ยี้แท้ หอมกลมกล่อม เครื่องทะเลแน่นทั้งปลา ลูกชิ้น เต้าหู้ทอด ซดน้ำแล้วรู้เลยว่าใช่ เย็นตาโฟต้องเจ้เต้ยเท่านั้น
                    </div>
                    <div class="buttons">
                        <button><a href="#menu">เมนูอาหาร</a></button>
                        <?php if (!isLoggedIn()): ?>
                        <button><a href="logSign.php">สมัครสมาชิก</a></button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="item">
                <img src="images/img/8tom.jpg">
                <div class="content">
                    <div class="author">เตี๋ยวเรือเจ้เต้ย</div>
                    <div class="title">อร่อยครบ จบทุกมื้อ</div>
                    <div class="topic">ก๋วยเตี๋ยวต้มยำ</div>
                    <div class="des">
                        รสชาติแซ่บจี๊ดถึงใจ ซดน้ำแล้วตื่น! เส้นลวกพอดี เครื่องต้มยำครบเครื่อง โรยถั่วคั่วหอม ๆ บีบมะนาวนิดคือจบ
                    </div>
                    <div class="buttons">
                        <button><a href="#menu">เมนูอาหาร</a></button>
                        <?php if (!isLoggedIn()): ?>
                        <button><a href="logSign.php">สมัครสมาชิก</a></button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="item">
                <img src="images/img/7kmg.jpg">
                <div class="content">
                    <div class="author">เตี๋ยวเรือเจ้เต้ย</div>
                    <div class="title">อร่อยครบ จบทุกมื้อ</div>
                    <div class="topic">อาหารจานเดียว</div>
                    <div class="des">
                        อิ่มแน่นแบบจัดเต็มกับเมนูข้าวหลากหลาย ทั้งเนื้อสัตว์คุณภาพ ข้าวหอมร้อน ๆ และรสชาติที่ลงตัวทุกคำ พร้อมให้คุณเลือกตามใจในทุกมื้อ
                    </div>
                    <div class="buttons">
                        <button><a href="#menu">เมนูอาหาร</a></button>
                        <?php if (!isLoggedIn()): ?>
                        <button><a href="logSign.php">สมัครสมาชิก</a></button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="item">
                <img src="images/img/9nam.jpg">
                <div class="content">
                    <div class="author">เตี๋ยวเรือเจ้เต้ย</div>
                    <div class="title">อร่อยครบ จบทุกมื้อ</div>
                    <div class="topic">เครื่องดื่ม</div>
                    <div class="des">
                        ดับกระหายด้วยเครื่องดื่มเย็น ๆ หวานหอมสดชื่น เติมความสดใสให้ทุกมื้อ ไม่ว่าจะกินคู่กับอะไรก็ลงตัวที่สุด
                    </div>
                    <div class="buttons">
                        <button><a href="#menu">เมนูอาหาร</a></button>
                        <?php if (!isLoggedIn()): ?>
                        <button><a href="logSign.php">สมัครสมาชิก</a></button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="item">
                <img src="images/img/11big.jpg">
                <div class="content">
                    <div class="author">เตี๋ยวเรือเจ้เต้ย</div>
                    <div class="title">อร่อยครบ จบทุกมื้อ</div>
                    <div class="topic">ของหวาน</div>
                    <div class="des">
                        ปิดท้ายมื้ออร่อยด้วยของหวานเย็นฉ่ำสไตล์ไทย หอม หวาน ละมุน ช่วยเติมความสุขแบบเบา ๆ ให้หัวใจ
                    </div>
                    <div class="buttons">
                        <button><a href="#menu">เมนูอาหาร</a></button>
                        <?php if (!isLoggedIn()): ?>
                        <button><a href="logSign.php">สมัครสมาชิก</a></button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- list thumnail -->
        <div class="thumbnail">
            <div class="item">
                <img src="images/img/00R.jpg">
                <div class="content">
                    <div class="title">ก๋วยเตี๋ยวเรือ</div>
                    <div class="description">เข้มข้นถึงใจ น้ำซุปกลมกล่อม เครื่องแน่นทุกชาม</div>
                </div>
            </div>
            <div class="item">
                <img src="images/img/12fo.jpg">
                <div class="content">
                    <div class="title">เย็นตาโฟ</div>
                    <div class="description">สีสวย รสเข้มข้น เครื่องทะเลแน่น</div>
                </div>
            </div>
            <div class="item">
                <img src="images/img/8tom.jpg">
                <div class="content">
                    <div class="title">ก๋วยเตี๋ยวต้มยำ</div>
                    <div class="description">แซ่บจี๊ดจ๊าด หอมมะนาวพริกคั่ว</div>
                </div>
            </div>
            <div class="item">
                <img src="images/img/7kmg.jpg">
                <div class="content">
                    <div class="title">ข้าวมันไก่</div>
                    <div class="description">ข้าวหอม ไก่นุ่ม น้ำจิ้มสูตรเด็ด</div>
                </div>
            </div>
            <div class="item">
                <img src="images/img/9nam.jpg">
                <div class="content">
                    <div class="title">ชาเย็น</div>
                    <div class="description">ชาเย็นเข้มข้น หอมมัน สดชื่นทุกแก้ว</div>
                </div>
            </div>
            <div class="item">
                <img src="images/img/11big.jpg">
                <div class="content">
                    <div class="title">น้ำแข็งใสคลายร้อน</div>
                    <div class="description">เย็นชื่นใจ หวานกำลังดี ของหวานที่กินเมื่อไหร่ก็รู้สึกดีทุกครั้ง</div>
                </div>
            </div>
        </div>
        <!-- next prev -->

        <div class="arrows">
            <button id="prev"><</button>
            <button id="next">></button>
        </div>
        <!-- time running -->
        <div class="time"></div>
    </div>

<!-- Upper End -->


<!--recommend-->
<?php if (!empty($recommendedFoods)): ?>
<section class="py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <h2 class="text-2xl font-bold mb-8 text-center">เมนูแนะนำ</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
        <?php foreach ($recommendedFoods as $food): ?>
            <div class="menu-item category-<?php echo $food['category_id']; ?> bg-white rounded-lg shadow-md overflow-hidden">
                <a href="#" class="open-modal block" data-id="<?php echo $food['id']; ?>">
                    <img
                        src="<?php echo !empty($food['image']) ? $food['image'] : 'images/img/restrrr.png'; ?>"
                        alt="<?php echo htmlspecialchars($food['name']); ?>"
                        class="w-full h-48 object-cover"
                    >
                </a>
                <div class="p-4">
                    <h3 class="text-lg font-semibold">
                        <a href="#" class="open-modal hover:text-primary transition" data-id="<?php echo $food['id']; ?>">
                            <?php echo htmlspecialchars($food['name']); ?>
                        </a>
                    </h3>
                    <p class="text-gray-600 text-sm mb-2"><?php echo htmlspecialchars($food['description']); ?></p>
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold text-primary"><?php echo number_format($food['price'], 2); ?> ฿</span>
                        <a href="product-action.php?action=view&id=<?php echo $food['id']; ?>" class="bg-primary text-white px-4 py-2 rounded hover:bg-amber-600 transition">สั่งเลย</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="text-center mt-8">
        <a href="#menu" class="text-primary hover:text-amber-600 font-semibold">ดูเมนูทั้งหมด <i class="fas fa-arrow-right ml-1"></i></a>
    </div>
</section>
<?php endif; ?>

<!-- Menu -->
<section id="menu" class="py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <h2 class="text-2xl font-bold mb-8 text-center">เมนูทั้งหมด</h2>
    
    <!-- Category Tabs -->
    <div class="flex flex-wrap justify-center mb-8 space-x-2">
        <button class="px-4 py-2 mb-2 rounded-full bg-primary text-white font-medium" onclick="filterCategory('all')">
            ทั้งหมด
        </button>
        
        <?php foreach ($categories as $category): ?>
            <button class="px-4 py-2 mb-2 rounded-full bg-gray-200 text-gray-700 font-medium hover:bg-gray-300" 
                    onclick="filterCategory('<?php echo $category['id']; ?>')">
                <?php echo $category['display_name']; ?>
            </button>
        <?php endforeach; ?>
    </div>

    
    <!-- menu -->
    <!-- Menu Items -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8" id="menu-items">
    <?php foreach ($allFoods as $food): ?>
        <div class="menu-item category-<?php echo $food['category_id']; ?> bg-white rounded-lg shadow-md overflow-hidden">
            <a href="#" class="open-modal block" data-id="<?php echo $food['id']; ?>">
                <img
                    src="<?php echo !empty($food['image']) ? $food['image'] : 'images/img/restrrr.png'; ?>"
                    alt="<?php echo htmlspecialchars($food['name']); ?>"
                    class="w-full h-48 object-cover"
                >
            </a>
            <div class="p-4">
                <h3 class="text-lg font-semibold">
                    <a href="#" class="open-modal hover:text-primary transition" data-id="<?php echo $food['id']; ?>">
                        <?php echo htmlspecialchars($food['name']); ?>
                    </a>
                </h3>
                <p class="text-gray-600 text-sm mb-2"><?php echo htmlspecialchars($food['description']); ?></p>
                <div class="flex justify-between items-center">
                    <span class="text-lg font-bold text-primary"><?php echo number_format($food['price'], 2); ?> ฿</span>
                    <a href="product-action.php?action=view&id=<?php echo $food['id']; ?>" class="bg-primary text-white px-4 py-2 rounded hover:bg-amber-600 transition">สั่งเลย</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

</section>

        <!-- Test upper menu-->


<section class="bg-primary py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto text-center">
        <h2 class="text-2xl font-bold text-white mb-4">เตี๋ยวเรือเจ๊เต้ยยินดีต้อนรับ</h2>
        <p class="text-white mb-6">อร่อยจบครบทุกมื้อ ที่เจ๊เต้ย</p>
    </div>
</section>


<div id="foodModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white p-6 rounded relative max-w-md w-full" id="modalContentWrapper">
        <button id="closeModalBtn" class="absolute top-2 right-2 text-3xl text-gray-600 hover:text-black">&times;</button>
        <div id="modalContent">...</div>
    </div>
</div>


<!--Picture Js-->
<script src="Js/Pic.js"></script>

<!--Recommend menu ?-->
<script src="Js/filterCategory.js"></script>
     
<!--modal & heart -->
<script src="Js/comm.js"></script>



<?php include 'includes/footer.php'; ?>