<?php
// admin/pages/shifts/action.php — Quản lý ca làm việc
$action = get_param('action', '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'store') {
        $name = post_param('name');
        $startTime = post_param('start_time');
        $endTime = post_param('end_time');

        if (!empty($name) && !empty($startTime) && !empty($endTime)) {
            if (strtotime($endTime) <= strtotime($startTime)) {
                set_flash('error', 'Giờ kết thúc phải lớn hơn giờ bắt đầu ca làm việc!');
            } else {
                db()->execute("INSERT INTO shifts (name, start_time, end_time, status) VALUES (?, ?, ?, 1)", [$name, $startTime, $endTime]);
                set_flash('success', 'Đã tạo ca làm việc thành công!');
            }
        }
        redirect(admin_url('shifts'));
    }

    if ($action === 'assign') {
        $employeeId = (int)post_param('employee_id');
        $shiftId = (int)post_param('shift_id');
        $workDate = post_param('work_date');

        if ($employeeId && $shiftId && !empty($workDate)) {
            $check = db()->fetchOne("SELECT id FROM employee_shifts WHERE employee_id = ? AND shift_id = ? AND work_date = ?", [$employeeId, $shiftId, $workDate]);
            if ($check) {
                set_flash('error', 'Đã phân công nhân viên này vào ca trong ngày!');
            } else {
                db()->execute("INSERT INTO employee_shifts (employee_id, shift_id, work_date, status) VALUES (?, ?, ?, 'assigned')", [$employeeId, $shiftId, $workDate]);
                set_flash('success', 'Đã phân công ca làm việc thành công!');
            }
        }
        redirect(admin_url('shifts'));
    }

    if ($action === 'update_status') {
        $id = (int)post_param('assignment_id');
        $status = post_param('status');
        if ($id && in_array($status, ['assigned', 'completed', 'absent', 'cancelled'])) {
            db()->execute("UPDATE employee_shifts SET status = ? WHERE id = ?", [$status, $id]);
            set_flash('success', 'Đã cập nhật trạng thái phân công!');
        }
        redirect(admin_url('shifts'));
    }
}

if ($action === 'delete') {
    $id = (int)get_param('id');
    if ($id) {
        $check = db()->fetchOne("SELECT COUNT(*) as c FROM employee_shifts WHERE shift_id = ?", [$id]);
        if ($check && (int)$check['c'] > 0) {
            set_flash('error', 'Không thể xóa ca làm việc vì đang có phân công liên quan!');
        } else {
            db()->execute("DELETE FROM shifts WHERE id = ?", [$id]);
            set_flash('success', 'Đã xóa ca làm việc thành công!');
        }
    }
    redirect(admin_url('shifts'));
}

$startDate = get_param('start_date', date('Y-m-d', strtotime('-30 days')));
$endDate = get_param('end_date', date('Y-m-d', strtotime('+30 days')));

$shifts = db()->fetchAll("SELECT * FROM shifts ORDER BY start_time ASC");
$staffs = db()->fetchAll("SELECT * FROM users WHERE role = 'staff' AND status = 1 ORDER BY full_name ASC");

$assignments = db()->fetchAll("SELECT es.*, u.full_name as employee_name, s.name as shift_name, s.start_time, s.end_time 
    FROM employee_shifts es
    JOIN users u ON es.employee_id = u.id
    JOIN shifts s ON es.shift_id = s.id
    WHERE es.work_date BETWEEN ? AND ?
    ORDER BY es.work_date DESC, s.start_time ASC", [$startDate, $endDate]);

$pageTitle = 'Quản lý ca làm việc';

