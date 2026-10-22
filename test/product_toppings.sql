-- Tạo bảng liên kết sản phẩm - topping (quan hệ nhiều-nhiều)
CREATE TABLE IF NOT EXISTS product_toppings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    topping_id INT NOT NULL,
    UNIQUE KEY unique_product_topping (product_id, topping_id),
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (topping_id) REFERENCES toppings(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
