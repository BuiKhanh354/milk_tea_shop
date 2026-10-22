-- Tạo bảng phiếu kiểm kê kho và chi tiết kiểm kê
CREATE TABLE IF NOT EXISTS inventory_audits (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    audit_code VARCHAR(50) NOT NULL UNIQUE,
    user_id INT NULL,
    total_items INT UNSIGNED DEFAULT 0,
    total_difference_items INT UNSIGNED DEFAULT 0,
    status ENUM('completed', 'draft') DEFAULT 'completed',
    note TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user (user_id),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS inventory_audit_details (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    audit_id INT UNSIGNED NOT NULL,
    inventory_id INT UNSIGNED NOT NULL,
    system_quantity DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    actual_quantity DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    difference_quantity DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    reason VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_audit (audit_id),
    INDEX idx_inventory (inventory_id),
    CONSTRAINT fk_audit_details_audit FOREIGN KEY (audit_id) REFERENCES inventory_audits(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
