<?php
// admin/pages/shifts/action.php — Quản lý ca làm việc (OOP)
$action = get_param('action', '');
$shiftModel = new Shift();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if ($action === 'store') {
        $name = post_param('name');
        $startTime = post_param('start_time');
        $endTime = post_param('end_time');

        if (!empty($name) && !empty($startTime) && !empty($endTime)) {
            if (strtotime($endTime) <= strtotime($startTime)) {
                set_flash('error', 'Giờ kết thúc phải lớn hơn giờ bắt đầu ca làm việc!');
            } else {
                $shiftModel->store($name, $startTime, $endTime);
                set_flash('success', 'Đã tạo ca làm việc thành công!');
            }
        } else {
            set_flash('error', 'Vui lòng điền đầy đủ tên ca, giờ bắt đầu và giờ kết thúc!');
        }
        redirect(admin_url('shifts'));
    }

    if ($action === 'assign') {
        $employeeId = (int)post_param('employee_id');
        $shiftId = (int)post_param('shift_id');
        $workDate = post_param('work_date');

        if ($employeeId && $shiftId && !empty($workDate)) {
            if ($shiftModel->hasAssignment($employeeId, $shiftId, $workDate)) {
                set_flash('error', 'Đã phân công nhân viên này vào ca trong ngày!');
            } else {
                $shiftModel->assignEmployee($employeeId, $shiftId, $workDate);
                set_flash('success', 'Đã phân công ca làm việc thành công!');
            }
        }
        redirect(admin_url('shifts'));
    }

    if ($action === 'update_status') {
        $id = (int)post_param('assignment_id');
        $status = post_param('status');
        if ($id && $shiftModel->updateAssignmentStatus($id, $status)) {
            set_flash('success', 'Đã cập nhật trạng thái phân công!');
        }
        redirect(admin_url('shifts'));
    }
}

if ($action === 'delete') {
    $id = (int)get_param('id');
    if ($id) {
        if (!$shiftModel->deleteShift($id)) {
            set_flash('error', 'Không thể xóa ca làm việc vì đang có phân công liên quan!');
        } else {
            set_flash('success', 'Đã xóa ca làm việc thành công!');
        }
    }
    redirect(admin_url('shifts'));
}

$currentPageName = get_param('page', 'shifts');
$currentUserId = (int)($_SESSION['user_id'] ?? 0);

if ($currentPageName === 'my-shifts') {
    $pageTitle = 'Lịch làm việc của tôi';
    $title = 'Lịch làm việc của tôi';
    $myShifts = $shiftModel->getEmployeeSchedule($currentUserId, 50);
} else {
    $startDate = get_param('start_date', date('Y-m-d', strtotime('-30 days')));
    $endDate = get_param('end_date', date('Y-m-d', strtotime('+30 days')));

    $shifts = $shiftModel->all('start_time ASC');
    $staffs = db()->fetchAll("SELECT * FROM users WHERE role = 'staff' AND status = 1 ORDER BY full_name ASC");
    $assignments = $shiftModel->getAssignmentsByDateRange($startDate, $endDate);

    $pageTitle = 'Quản lý ca làm việc';
}
