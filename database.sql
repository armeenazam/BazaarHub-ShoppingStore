/* =========================================================
   BAZARHUB DATABASE SCHEMA
   Course: Database Systems
   Stack: PHP + MySQL
   ========================================================= */


/* =========================================================
   1. USERS TABLE
   ========================================================= */
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','seller','customer') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


/* =========================================================
   2. CATEGORIES TABLE
   ========================================================= */
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL
);


/* =========================================================
   3. PRODUCTS TABLE
   ========================================================= */
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    category_id INT NOT NULL,
    seller_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (category_id)
        REFERENCES categories(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    FOREIGN KEY (seller_id)
        REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);


/* =========================================================
   4. CART TABLE
   ========================================================= */
CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,

    FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    FOREIGN KEY (product_id)
        REFERENCES products(id)
        ON DELETE CASCADE
);


/* =========================================================
   5. ORDERS TABLE
   ========================================================= */
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending','completed','cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
);


/* =========================================================
   6. ORDER ITEMS TABLE
   ========================================================= */
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,

    FOREIGN KEY (order_id)
        REFERENCES orders(id)
        ON DELETE CASCADE,

    FOREIGN KEY (product_id)
        REFERENCES products(id)
        ON DELETE CASCADE
);


/* =========================================================
   7. REVIEWS TABLE
   ========================================================= */
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE(user_id, product_id),

    FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    FOREIGN KEY (product_id)
        REFERENCES products(id)
        ON DELETE CASCADE
);


/* =========================================================
   8. PAYMENTS TABLE
   ========================================================= */
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_status ENUM('pending','paid') DEFAULT 'paid',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (order_id)
        REFERENCES orders(id)
        ON DELETE CASCADE
);



/* =========================================================
   SEED DATA SECTION
   ========================================================= */


/* ---------------- USERS ---------------- */
INSERT INTO users (name, email, password, role) VALUES
('Ali Raza', 'ali.raza@gmail.com', 'Ali@123', 'admin'),
('Sara Khan', 'sara.khan@gmail.com', 'Sara@123', 'admin'),

('Ahmed Hassan', 'ahmed.seller@gmail.com', 'Ahmed@123', 'seller'),
('Hina Malik', 'hina.seller@gmail.com', 'Hina@123', 'seller'),

('Usman Tariq', 'usman@gmail.com', 'Usman@123', 'customer'),
('Ayesha Noor', 'ayesha@gmail.com', 'Ayesha@123', 'customer'),
('Zain Ali', 'zain@gmail.com', 'Zain@123', 'customer'),
('Fatima Khan', 'fatima@gmail.com', 'Fatima@123', 'customer'),
('Hassan Raza', 'hassan@gmail.com', 'Hassan@123', 'customer'),
('Laiba Ahmed', 'laiba@gmail.com', 'Laiba@123', 'customer');


/* ---------------- CATEGORIES ---------------- */
INSERT INTO categories (name) VALUES
('Smartphones'),
('Laptops'),
('Fashion'),
('Books'),
('Home & Kitchen'),
('Beauty'),
('Sports'),
('Toys'),
('Automotive'),
('Gaming');


/* ---------------- PRODUCTS ---------------- */
INSERT INTO products (name, description, price, stock, category_id, seller_id) VALUES
('iPhone 14 Pro', 'Apple flagship smartphone', 280000, 8, 1, 3),
('Samsung S23', 'Android flagship phone', 240000, 10, 1, 3),

('Dell Inspiron', 'Core i5 laptop for work', 185000, 6, 2, 4),
('HP Pavilion Gaming', 'Gaming laptop RTX series', 320000, 4, 2, 4),

('Men Cotton Shirt', 'Comfortable summer wear', 2500, 40, 3, 3),
('Women Lawn Suit', 'Stylish stitched outfit', 4500, 30, 3, 4),

('Physics Book FSC', 'Complete guide for exams', 1200, 50, 4, 3),

('Non-stick Pan', 'Kitchen cooking essential', 3500, 25, 5, 4),

('Lipstick Set', 'Matte beauty combo', 2800, 35, 6, 4),

('Football', 'Professional match ball', 3200, 20, 7, 3);


/* ---------------- CART ---------------- */
INSERT INTO cart (user_id, product_id, quantity) VALUES
(5, 1, 1),
(6, 2, 1),
(7, 3, 1),
(8, 4, 1),
(9, 5, 2),
(10, 6, 1),
(5, 7, 1),
(6, 8, 1),
(7, 9, 1),
(8, 10, 1);


/* ---------------- ORDERS ---------------- */
INSERT INTO orders (user_id, total_amount, status) VALUES
(5, 280000, 'completed'),
(6, 240000, 'pending'),
(7, 185000, 'completed'),
(8, 320000, 'pending'),
(9, 5000, 'completed'),
(10, 3500, 'pending'),
(5, 2800, 'completed'),
(6, 4500, 'pending'),
(7, 3200, 'completed'),
(8, 1200, 'pending');


/* ---------------- ORDER ITEMS ---------------- */
INSERT INTO order_items (order_id, product_id, quantity, price) VALUES
(1, 1, 1, 280000),
(2, 2, 1, 240000),
(3, 3, 1, 185000),
(4, 4, 1, 320000),
(5, 5, 2, 2500),
(6, 6, 1, 3500),
(7, 9, 1, 2800),
(8, 8, 1, 4500),
(9, 10, 1, 3200),
(10, 7, 1, 1200);


/* ---------------- REVIEWS ---------------- */
INSERT INTO reviews (user_id, product_id, rating, comment) VALUES
(5, 1, 5, 'Excellent phone performance'),
(6, 2, 4, 'Very smooth Android experience'),
(7, 3, 5, 'Perfect for office work'),
(8, 4, 5, 'Great gaming performance'),
(9, 5, 4, 'Good quality shirt'),
(10, 6, 5, 'Very useful kitchen item'),
(5, 7, 5, 'Nice lipstick shades'),
(6, 8, 4, 'Good football quality'),
(7, 9, 5, 'Helpful book for studies'),
(8, 10, 4, 'Solid build mouse');