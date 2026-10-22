-- Bảng lưu trữ giỏ hàng (không bị mất khi đóng trình duyệt hoặc đăng xuất)
CREATE TABLE IF NOT EXISTS cart_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NULL,
    cart_token VARCHAR(64) NOT NULL,
    cart_key VARCHAR(64) NOT NULL,
    product_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    image VARCHAR(255) NULL,
    size VARCHAR(50) NULL,
    sugar VARCHAR(50) NULL,
    ice VARCHAR(50) NULL,
    temperature VARCHAR(20) DEFAULT 'cold',
    toppings TEXT NULL,
    total_price DECIMAL(12, 2) NOT NULL DEFAULT 0,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_customer (customer_id),
    INDEX idx_token (cart_token),
    INDEX idx_cart_key (cart_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
