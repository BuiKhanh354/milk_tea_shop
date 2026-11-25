<?php
/**
 * Customer — Quản lý khách hàng (bảng customers).
 */
class Customer extends BaseModel
{
    protected string $table = 'customers';

    public function findByLogin(string $loginInput): ?array
    {
        $row = $this->db->fetchOne(
            "SELECT * FROM customers WHERE (email = ? OR phone = ? OR full_name = ?) AND (status = 1 OR status IS NULL)",
            [$loginInput, $loginInput, $loginInput]
        );
        return $row ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        $row = $this->db->fetchOne("SELECT * FROM customers WHERE email = ?", [$email]);
        return $row ?: null;
    }

    public function register(string $fullName, string $email, string $plainPassword): bool
    {
        $hashed = password_hash($plainPassword, PASSWORD_DEFAULT);
        return (bool)$this->db->execute(
            "INSERT INTO customers (full_name, email, password, created_at) VALUES (?, ?, ?, NOW())",
            [$fullName, $email, $hashed]
        );
    }

    public function updateProfile(int $id, string $fullName, string $phone, string $address): bool
    {
        return (bool)$this->db->execute(
            "UPDATE customers SET full_name = ?, phone = ?, address = ? WHERE id = ?",
            [$fullName, $phone, $address, $id]
        );
    }

    public function changePassword(int $id, string $newPlainPassword): bool
    {
        $hash = password_hash($newPlainPassword, PASSWORD_DEFAULT);
        return (bool)$this->db->execute("UPDATE customers SET password = ? WHERE id = ?", [$hash, $id]);
    }

    public function toggleStatus(int $id): bool
    {
        return (bool)$this->db->execute(
            "UPDATE customers SET status = CASE WHEN status = 1 THEN 0 ELSE 1 END WHERE id = ?",
            [$id]
        );
    }

    public function getPaginatedWithStats(int $page = 1, int $limit = 10, string $search = '', string $status = ''): array
    {
        $offset = max(0, ($page - 1) * $limit);
        $conditions = [];
        $params = [];

        if ($search !== '') {
            $conditions[] = "(c.full_name LIKE ? OR c.email LIKE ? OR c.phone LIKE ?)";
            $like = '%' . $search . '%';
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }
        if ($status !== '') {
            $conditions[] = "c.status = ?";
            $params[] = (int)$status;
        }

        $whereClause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";
        $countRow = $this->db->fetchOne("SELECT COUNT(*) as total FROM customers c $whereClause", $params);
        $total = (int)($countRow['total'] ?? 0);

        $rows = $this->db->fetchAll(
            "SELECT c.*,
                COUNT(o.id) as total_orders,
                COALESCE(SUM(CASE WHEN o.status = 'completed' THEN o.final_amount ELSE 0 END), 0) as total_spent
             FROM customers c
             LEFT JOIN orders o ON c.id = o.customer_id
             $whereClause
             GROUP BY c.id
             ORDER BY c.created_at DESC
             LIMIT ? OFFSET ?",
            array_merge($params, [$limit, $offset])
        );

        return [
            'data'        => $rows,
            'total'       => $total,
            'total_pages' => (int)ceil($total / $limit),
        ];
    }
}
