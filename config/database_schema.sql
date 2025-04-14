-- Create database
CREATE DATABASE IF NOT EXISTS food_ordering CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE food_ordering;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    display_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create foods table
CREATE TABLE IF NOT EXISTS foods (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255),
    category_id INT,
    is_recommended BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Create addon_options table
CREATE TABLE IF NOT EXISTS addon_options (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    type VARCHAR(50) NOT NULL, -- e.g., 'noodle', 'soup', 'topping'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create orders table
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    address TEXT NOT NULL,
    phone VARCHAR(20) NOT NULL,
    status ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create order_items table
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    food_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    special_instructions TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (food_id) REFERENCES foods(id) ON DELETE CASCADE
);

-- Create order_item_addons table
CREATE TABLE IF NOT EXISTS order_item_addons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_item_id INT NOT NULL,
    addon_id INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_item_id) REFERENCES order_items(id) ON DELETE CASCADE,
    FOREIGN KEY (addon_id) REFERENCES addon_options(id) ON DELETE CASCADE
);

-- Insert admin user
INSERT INTO users (username, password, email, role) VALUES 
('admin', '$2y$10$ZALc0A3u6M7RBHSsCJ9J3.wHxqXH3rNLX/uP4WrK5qwI4vWkMj2Nu', 'admin@example.com', 'admin');

-- Insert regular user
INSERT INTO users (username, password, email, role) VALUES 
('user', '$2y$10$ZALc0A3u6M7RBHSsCJ9J3.wHxqXH3rNLX/uP4WrK5qwI4vWkMj2Nu', 'user@example.com', 'user');

-- Insert some categories
INSERT INTO categories (name, display_name) VALUES 
('recommended', 'แนะนำ'),
('noodles', 'ก๋วยเตี๋ยว'),
('rice_dishes', 'อาหารจานเดียว'),
('drinks', 'เครื่องดื่ม'),
('desserts', 'ของหวาน');

-- Insert some foods
INSERT INTO foods (name, description, price, category_id, is_recommended) VALUES 
('น้ำตก หมู (พิเศษ)', 'ก๋วยเตี๋ยวน้ำตกหมูพิเศษ เนื้อเยอะ น้ำซุปกลมกล่อม', 50.00, 2, 1),
('เย็นตาโฟ', 'เย็นตาโฟรสชาติเข้มข้น หอมกลิ่นเครื่องเทศ', 45.00, 2, 0),
('ก๋วยเตี๋ยวต้มยำ', 'ก๋วยเตี๋ยวต้มยำรสจัด เผ็ดสะใจ', 45.00, 2, 1),
('ก๋วยเตี๋ยวเนื้อตุ๋น', 'ก๋วยเตี๋ยวเนื้อตุ๋นเนื้อนุ่ม น้ำซุปหอมเครื่องเทศ', 55.00, 2, 0),
('ข้าวหมูกรอบ', 'ข้าวหมูทอดกรอบ ราดน้ำราดหวานพิเศษ', 60.00, 3, 1),
('ข้าวมันไก่', 'ข้าวมันไก่สูตรพิเศษ หอมมัน ไก่นุ่ม', 50.00, 3, 0),
('ชาเย็น', 'ชาไทยเย็น หวานมัน', 25.00, 4, 0),
('กาแฟเย็น', 'กาแฟดำเย็น รสเข้มข้น', 30.00, 4, 0),
('ลอดช่อง', 'ลอดช่องน้ำกะทิ หอมหวาน', 35.00, 5, 0),
('ทับทิมกรอบ', 'ทับทิมกรอบน้ำแข็งใส หวานเย็น', 40.00, 5, 0);

-- Insert addon options
INSERT INTO addon_options (name, price, type) VALUES 
('เส้นเล็ก', 0.00, 'noodle'),
('เส้นใหญ่', 0.00, 'noodle'),
('หมี่ขาว', 0.00, 'noodle'),
('หมี่เหลือง', 0.00, 'noodle'),
('บะหมี่', 0.00, 'noodle'),
('วุ้นเส้น', 0.00, 'noodle'),
('น้ำ', 0.00, 'soup'),
('แห้ง', 0.00, 'soup'),
('หมูสไลด์ 4 ชิ้น', 15.00, 'topping'),
('เนื้อตุ๋น 3 ชิ้น', 15.00, 'topping'),
('เนื้อสไลด์ 4 ชิ้น', 15.00, 'topping'),
('หมูตุ๋น 3 ชิ้น', 15.00, 'topping'),
('ลูกชิ้น 3 ลูก', 15.00, 'topping'),
('ตับ 4 ชิ้น', 15.00, 'topping'),
('พิเศษ (เนื้อเยอะ)', 10.00, 'special'),
('ธรรมดา', 0.00, 'special'),
('ไม่เผ็ด', 0.00, 'spiciness'),
('เผ็ดน้อย', 0.00, 'spiciness'),
('เผ็ดกลาง', 0.00, 'spiciness'),
('เผ็ดมาก', 0.00, 'spiciness');