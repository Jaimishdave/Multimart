-- Create database
CREATE DATABASE IF NOT EXISTS multimart;
USE multimart;

-- Create admin table
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL
);

-- Create customer table
CREATE TABLE customer (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    address TEXT,
    phone VARCHAR(15)
);

-- Create delivery person table
CREATE TABLE delivery_person (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(15)
);

-- Create products table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    category VARCHAR(50),
    stock INT NOT NULL,
    image VARCHAR(255)
);

-- Insert sample products into the products table
INSERT INTO products (name, description, price, category, stock, image) VALUES
-- Phones
('iPhone 13', 'Latest Apple iPhone 13 with A15 Bionic chip', 999.99, 'Phones', 50, 'assets/images/iphone13.jpg'),
('Samsung Galaxy S21', 'Samsung Galaxy S21 with Exynos 2100', 799.99, 'Phones', 30, 'assets/images/galaxy_s21.jpg'),
('Google Pixel 6', 'Google Pixel 6 with Google Tensor chip', 699.99, 'Phones', 40, 'assets/images/pixel6.jpg'),
('OnePlus 9', 'OnePlus 9 with Snapdragon 888', 729.99, 'Phones', 25, 'assets/images/oneplus9.jpg'),
('Sony Xperia 5 III', 'Sony Xperia 5 III with Snapdragon 888', 949.99, 'Phones', 20, 'assets/images/xperia5iii.jpg'),

-- Accessories
('Sony WH-1000XM4', 'Sony Noise Cancelling Headphones', 349.99, 'Accessories', 100, 'assets/images/sony_wh1000xm4.jpg'),
('Apple AirPods Pro', 'Apple AirPods Pro with Active Noise Cancellation', 249.99, 'Accessories', 150, 'assets/images/airpods_pro.jpg'),
('Samsung Galaxy Buds Pro', 'Samsung Galaxy Buds Pro with ANC', 199.99, 'Accessories', 120, 'assets/images/galaxy_buds_pro.jpg'),
('Anker PowerCore 10000', 'Anker PowerCore 10000 Portable Charger', 29.99, 'Accessories', 200, 'assets/images/anker_powercore_10000.jpg'),
('Logitech MX Master 3', 'Logitech MX Master 3 Advanced Wireless Mouse', 99.99, 'Accessories', 80, 'assets/images/logitech_mx_master_3.jpg'),

-- Furniture
('IKEA Sofa', 'Comfortable 3-seater sofa from IKEA', 499.99, 'Furniture', 20, 'assets/images/ikea_sofa.jpg'),
('Dining Table Set', 'Modern dining table set with 4 chairs', 299.99, 'Furniture', 15, 'assets/images/dining_table_set.jpg'),
('Office Chair', 'Ergonomic office chair with lumbar support', 149.99, 'Furniture', 30, 'assets/images/office_chair.jpg'),
('Bookshelf', '5-tier wooden bookshelf', 89.99, 'Furniture', 25, 'assets/images/bookshelf.jpg'),
('Bed Frame', 'Queen size bed frame with storage', 399.99, 'Furniture', 10, 'assets/images/bed_frame.jpg'),
('Coffee Table', 'Stylish coffee table with glass top', 79.99, 'Furniture', 35, 'assets/images/coffee_table.jpg');

-- Update sample products in the products table
-- Phones
UPDATE products SET price = 74999.25 WHERE id = 1;
UPDATE products SET price = 59999.25 WHERE id = 2;
UPDATE products SET price = 52499.25 WHERE id = 3;
UPDATE products SET price = 54749.25 WHERE id = 4;
UPDATE products SET price = 71249.25 WHERE id = 5;

-- Accessories
UPDATE products SET price = 26249.25 WHERE id = 6;
UPDATE products SET price = 18749.25 WHERE id = 7;
UPDATE products SET price = 14999.25 WHERE id = 8;
UPDATE products SET price = 2249.25 WHERE id = 9;
UPDATE products SET price = 7499.25 WHERE id = 10;

-- Furniture
UPDATE products SET price = 37499.25 WHERE id = 11;
UPDATE products SET price = 22499.25 WHERE id = 12;
UPDATE products SET price = 11249.25 WHERE id = 13;
UPDATE products SET price = 6749.25 WHERE id = 14;
UPDATE products SET price = 29999.25 WHERE id = 15;
UPDATE products SET price = 5999.25 WHERE id = 16;

-- Create orders table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    total DECIMAL(10, 2) NOT NULL,
    status VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customer(id)
);
ALTER TABLE orders ADD COLUMN total DECIMAL(10, 2) NOT NULL DEFAULT 0.00;
-- Create order_items table
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Create cart table
CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    product_id INT,
    quantity INT NOT NULL,
    FOREIGN KEY (customer_id) REFERENCES customer(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);
ALTER TABLE cart ADD COLUMN product_name VARCHAR(255);

-- Create wishlist table
CREATE TABLE wishlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    product_id INT,
    FOREIGN KEY (customer_id) REFERENCES customer(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);
ALTER TABLE wishlist ADD COLUMN in_wishlist BOOLEAN DEFAULT TRUE;
ALTER TABLE wishlist ADD COLUMN product_name VARCHAR(255);