-- Bảng quy chuẩn định lượng đường theo % cho từng sản phẩm
CREATE TABLE IF NOT EXISTS product_sugar_levels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT(10) UNSIGNED NOT NULL,
    size_id INT(10) UNSIGNED NOT NULL DEFAULT 0,
    sugar_percent INT NOT NULL,
    quantity DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    inventory_id INT(10) UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_prod_size_sugar (product_id, size_id, sugar_percent),
    CONSTRAINT fk_sugar_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    CONSTRAINT fk_sugar_inventory FOREIGN KEY (inventory_id) REFERENCES inventory(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
