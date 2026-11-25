<?php
/**
 * Staff — Quản lý nhân viên & quản trị viên (bảng users).
 */
class Staff extends BaseModel
{
    protected string $table = 'users';

    public function findByLogin(string $loginInput): ?array
    {
        $row = $this->db->fetchOne(
            "SELECT * FROM users WHERE (username = ? OR email = ? OR phone = ?) AND status = 1",
            [$loginInput, $loginInput, $loginInput]
        );
        return $row ?: null;
    }

    public function getAllStaff(): array
    {
        return $this->db->fetchAll("SELECT * FROM users WHERE role = 'staff' ORDER BY created_at DESC");
    }

    public function storeStaff(string $username, string $plainPassword, string $fullName, string $email = '', string $phone = ''): bool
    {
        $hashed = password_hash($plainPassword, PASSWORD_DEFAULT);
        return (bool)$this->db->execute(
            "INSERT INTO users (username, password, full_name, email, phone, role, status) VALUES (?, ?, ?, ?, ?, 'staff', 1)",
            [$username, $hashed, $fullName, $email, $phone]
        );
    }

    public function updateStaff(int $id, string $username, string $fullName, string $email = '', string $phone = '', string $plainPassword = ''): bool
    {
        if ($plainPassword !== '') {
            $hashed = password_hash($plainPassword, PASSWORD_DEFAULT);
            return (bool)$this->db->execute(
                "UPDATE users SET username = ?, password = ?, full_name = ?, email = ?, phone = ? WHERE id = ? AND role = 'staff'",
                [$username, $hashed, $fullName, $email, $phone, $id]
            );
        }

        return (bool)$this->db->execute(
            "UPDATE users SET username = ?, full_name = ?, email = ?, phone = ? WHERE id = ? AND role = 'staff'",
            [$username, $fullName, $email, $phone, $id]
        );
    }

    public function toggleStaffStatus(int $id): bool
    {
        return (bool)$this->db->execute(
            "UPDATE users SET status = CASE WHEN status = 1 THEN 0 ELSE 1 END WHERE id = ? AND role = 'staff'",
            [$id]
        );
    }

    public function updateProfile(int $id, string $fullName, string $email, string $phone, string $newPassword = ''): bool
    {
        if ($newPassword !== '') {
            $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
            return (bool)$this->db->execute(
                "UPDATE users SET full_name = ?, email = ?, phone = ?, password = ? WHERE id = ?",
                [$fullName, $email, $phone, $hashed, $id]
            );
        }

        return (bool)$this->db->execute(
            "UPDATE users SET full_name = ?, email = ?, phone = ? WHERE id = ?",
            [$fullName, $email, $phone, $id]
        );
    }
}
