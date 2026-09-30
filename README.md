# VAA THÉ - Hệ Thống Quản Lý & Đặt Hàng Trà Sữa

Chào mừng bạn đến với **VAA THÉ**! Đây là một dự án website thương mại điện tử (bán trà sữa) tích hợp hệ thống quản trị, quản lý kho, nhân sự và bán hàng.

---

## 🌟 Tính Năng Nổi Bật

### 1. Phía Khách Hàng (Customer)
- **Trang chủ & Menu**: Hiển thị sản phẩm theo danh mục đẹp mắt, UI/UX hiện đại (kèm animation).
- **Chi tiết sản phẩm**: Cho phép tùy chỉnh Kích cỡ (Size), Mức đường (Sugar), Mức đá (Ice), và thêm các loại Topping.
- **Giỏ hàng (Cart)**: Tích hợp Session và AJAX, tính toán giá tiền (bao gồm giá cộng thêm của Size và Topping) mượt mà không cần tải lại trang. 

### 2. Phía Nhân Viên (Staff)
- **Quản lý Đơn hàng**: Xem và xử lý các đơn hàng của khách.
- **Lịch làm việc (My Shifts)**: Xem ca làm việc cá nhân của mình.
- **Quản lý Kho (Cơ bản)**: Xem số lượng tồn kho, tạo phiếu nhập/xuất kho thực tế.

### 3. Phía Quản Trị Viên (Admin)
- **Dashboard Thống kê**: Báo cáo tổng quan về doanh thu, đơn hàng, và cảnh báo tồn kho.
- **Quản lý Nhân sự**: Phân quyền Admin / Staff. Khóa chặn tính năng đối với Staff.
- **Quản lý Kho Bãi Chuyên Nghiệp**: 
  - Khởi tạo Nguyên liệu, tính toán Giá vốn.
  - Quản lý công thức pha chế (`Product Ingredients`): Tự động trừ kho nguyên liệu khi đơn hàng hoàn thành dựa trên công thức.
  - Lịch sử xuất/nhập kho (100% Tracking).
- **Quản lý Sản Phẩm & Danh Mục**: Đầy đủ tính năng Thêm/Sửa/Xóa.

---

## 🚀 Hướng Dẫn Cài Đặt

Để chạy dự án này trên máy của bạn (Localhost), hãy làm theo các bước sau:

### Bước 1: Cài đặt Môi trường
- Tải và cài đặt phần mềm **XAMPP** (hoặc WAMP/Laragon) hỗ trợ PHP >= 8.0 và MySQL.
- Bật module **Apache** và **MySQL** trên bảng điều khiển XAMPP Control Panel.

### Bước 2: Clone & Đặt Code đúng vị trí
- Clone dự án này về máy hoặc giải nén file ZIP.
- Đổi tên thư mục thành `milk_tea_shop`.
- Đưa thư mục `milk_tea_shop` vào trong thư mục `htdocs` của XAMPP.
  - Đường dẫn chuẩn: `C:\xampp\htdocs\milk_tea_shop`

### Bước 3: Cài đặt Cơ Sở Dữ Liệu (Database)
Cách nhanh nhất (Tự động):
1. Đảm bảo đã bật MySQL trong XAMPP.
2. Mở thư mục dự án, click đúp vào file **`setup_db.bat`** (hoặc mở Terminal gõ `.\setup_db.bat`).
3. Tool sẽ tự động tạo database `vaa_the` và nạp toàn bộ dữ liệu vào cho bạn.

*Cách thủ công (Dành cho MacOS/Linux hoặc WAMP):*
1. Truy cập vào `http://localhost/phpmyadmin`.
2. Tạo database tên là **`vaa_the`** (Bảng mã: `utf8mb4_unicode_ci`).
3. Chọn tab **Import**, tải file **`vaa_the.sql`** lên và bấm **Go**.

### Bước 4: Cấu hình Kết nối (Nếu cần)
Mặc định hệ thống kết nối với Database qua cấu hình XAMPP chuẩn:
- **Server**: `127.0.0.1` (hoặc `localhost`)
- **Username**: `root`
- **Password**: `(để trống)`
- **Database**: `vaa_the`

*(Nếu máy có cài mật khẩu MySQL, mở file `app/config/database.php` và sửa lại biến `$pass` cho phù hợp).*

---

## 🎮 Cách Sử Dụng & Test Hệ Thống

### Truy cập Trang Khách Hàng (Customer)
Mở trình duyệt và truy cập:
👉 **[http://localhost/milk_tea_shop/public/index.php](http://localhost/milk_tea_shop/public/index.php)**

### Truy cập Trang Quản Trị (Admin/Staff Panel)
Mở trình duyệt và truy cập:
👉 **[http://localhost/milk_tea_shop/public/admin.php](http://localhost/milk_tea_shop/public/admin.php)**

**Tài khoản đăng nhập có sẵn (Dữ liệu mẫu):**

1. **Quản Trị Viên (Admin)**
   - Username: `admin`
   - Password: `123456` *(Vui lòng nhập đúng mật khẩu bạn đã thiết lập lúc tạo)*

2. **Nhân Viên (Staff)**
   - Username: `staff01`
   - Password: `123456` *(Vui lòng nhập đúng mật khẩu bạn đã thiết lập lúc tạo)*

*(Lưu ý: Nếu mật khẩu trên không đúng, bạn hãy tự tạo 1 tài khoản bằng code hoặc sử dụng mã MD5/Bcrypt chèn thẳng vào database nhé).*

---

## 🛠 Cấu Trúc Thư Mục (Folder Structure)

```text
milk_tea_shop/
│
├── app/                  # Chứa toàn bộ logic ứng dụng (Mô hình MVC)
│   ├── config/           # Cấu hình kết nối DB (database.php)
│   ├── controllers/      # Chứa Controllers (Admin, Staff, Customer)
│   ├── models/           # Chứa các Model tương tác với Database
│   └── views/            # Chứa giao diện (HTML/PHP)
│
├── public/               # Thư mục gốc chứa tài nguyên tĩnh và File chạy chính
│   ├── assets/           # CSS, JS, Hình ảnh (css/, js/, uploads/)
│   ├── index.php         # File định tuyến (Router) cho Khách Hàng
│   └── admin.php         # File định tuyến (Router) cho Admin & Staff
│
├── vaa_the.sql           # File Database gốc (Import file này)
└── README.md             # File hướng dẫn này
```
