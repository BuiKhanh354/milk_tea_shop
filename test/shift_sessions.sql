-- Bảng Phiên ca làm việc (Mở ca & Kết ca)
CREATE TABLE IF NOT EXISTS shift_sessions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    session_code VARCHAR(50) NOT NULL UNIQUE,
    user_id INT UNSIGNED NOT NULL,
    shift_id INT UNSIGNED NULL,
    employee_shift_id INT UNSIGNED NULL,
    opened_at DATETIME NOT NULL,
    closed_at DATETIME NULL,
    initial_cash DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    cash_sales DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    bank_sales DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    momo_sales DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    total_sales DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    expected_cash DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    actual_cash DECIMAL(12, 2) NULL,
    cash_difference DECIMAL(12, 2) NULL,
    cash_difference_reason VARCHAR(255) NULL,
    status ENUM('open', 'closed') DEFAULT 'open',
    note TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user (user_id),
    INDEX idx_status (status),
    INDEX idx_opened_at (opened_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng Xác nhận & Kiểm kê tồn nguyên liệu theo ca
CREATE TABLE IF NOT EXISTS shift_session_inventory (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    session_id INT UNSIGNED NOT NULL,
    inventory_id INT UNSIGNED NOT NULL,
    start_quantity DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    expected_usage DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    expected_ending_qty DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    actual_ending_qty DECIMAL(12, 2) NULL,
    difference_qty DECIMAL(12, 2) NULL,
    reason VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_session (session_id),
    INDEX idx_inventory (inventory_id),
    CONSTRAINT fk_shift_session_inv FOREIGN KEY (session_id) REFERENCES shift_sessions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bổ sung shift_session_id vào orders nếu chưa có
SET @dbname = DATABASE();
SET @tablename = "orders";
SET @columnname = "shift_session_id";
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  "SELECT 1",
  "ALTER TABLE orders ADD COLUMN shift_session_id INT UNSIGNED NULL AFTER table_id, ADD INDEX idx_shift_session (shift_session_id);"
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Bổ sung trạng thái 'in_progress' vào employee_shifts để tự động ghi nhận nhân viên đang làm ca
ALTER TABLE employee_shifts MODIFY COLUMN status ENUM('assigned','in_progress','completed','absent','cancelled') DEFAULT 'assigned';
