Tôi muốn bạn thiết kế và triển khai TRANG ĐĂNG NHẬP
(LOGIN PAGE) cho website trà sữa VAA THÉ.

==================================================
1. THÔNG TIN THƯƠNG HIỆU
==================================================

Brand:
VAA THÉ

Tagline:
GOOD TEA, GOOD MOOD

Phong cách website hiện tại:

Light / Gentle Cartoonish
+
Editorial Typography
+
Minimalist Luxury

Trang Login phải đồng bộ với Home, Products và About.

==================================================
2. BOOTSTRAP
==================================================

BẮT BUỘC sử dụng Bootstrap 5.

Bootstrap dùng cho:

- Grid
- Responsive
- Form layout
- Utilities
- Flex
- Spacing
- Responsive breakpoint

Nhưng KHÔNG sử dụng giao diện Bootstrap mặc định.

Phải custom CSS riêng để tạo nhận diện VAA THÉ.

Stack:

PHP thuần
HTML5
CSS3
Bootstrap 5
MySQL/MariaDB
MVC

Không sử dụng:

- React
- Tailwind
- Laravel

==================================================
3. LOGIN PAGE CONCEPT
==================================================

Tôi không muốn Login Page giống một form đăng nhập
thông thường.

Hãy thiết kế theo kiểu:

Premium Tea Brand Login Experience.

Trang phải:

- sạch
- sang
- nhẹ
- hiện đại
- ít thành phần
- nhiều whitespace
- typography đẹp
- hình ảnh thương hiệu là điểm nhấn.

==================================================
4. LAYOUT DESKTOP
==================================================

Desktop sử dụng layout chia 2 phần:

LEFT:
40–45%

RIGHT:
55–60%

Ví dụ:

┌──────────────────────┬─────────────────────────────┐
│                      │                             │
│                      │          VAA THÉ            │
│    BRAND IMAGE       │                             │
│                      │      Chào mừng trở lại      │
│                      │                             │
│    GOOD TEA          │      Email / Username       │
│    GOOD MOOD         │      [_______________]      │
│                      │                             │
│                      │      Mật khẩu               │
│                      │      [_______________]      │
│                      │                             │
│                      │      Quên mật khẩu?         │
│                      │                             │
│                      │      [    ĐĂNG NHẬP    ]    │
│                      │                             │
│                      │      ───── hoặc ─────       │
│                      │                             │
│                      │      Chưa có tài khoản?     │
│                      │      Đăng ký ngay            │
│                      │                             │
└──────────────────────┴─────────────────────────────┘

==================================================
5. LEFT SIDE - BRAND VISUAL
==================================================

Phần bên trái là visual branding.

Sử dụng một hình ảnh lớn:

- ly trà sữa
- tea leaves
- topping
- lifestyle tea photography

Nếu project đã có hình ảnh thương hiệu,
hãy sử dụng asset hiện tại.

KHÔNG tạo hình ảnh mới nếu đã có asset phù hợp.

Background:

cream / beige / warm ivory.

Có thể thêm decoration:

- botanical illustration
- tea leaf
- hand-drawn line
- small circle
- subtle texture

Nhưng phải rất nhẹ.

Ở góc dưới:

VAA THÉ

GOOD TEA, GOOD MOOD

==================================================
6. RIGHT SIDE - LOGIN FORM
==================================================

Form nằm giữa phần bên phải.

Logo nhỏ:

VAA THÉ

Small label:

WELCOME BACK

Heading:

"Chào mừng trở lại"

Description:

"Đăng nhập để tiếp tục hành trình
cùng hương vị yêu thích của bạn."

==================================================
7. EMAIL / USERNAME
==================================================

Label:

Email hoặc tên đăng nhập

Input placeholder:

"Nhập email hoặc tên đăng nhập"

Thiết kế:

- border-bottom hoặc border mảnh
- background transparent / ivory
- không shadow
- border-radius vừa phải hoặc rất nhỏ
- focus transition nhẹ.

Không sử dụng Bootstrap input mặc định nguyên bản.

==================================================
8. PASSWORD
==================================================

Label:

Mật khẩu

Input:

"Nhập mật khẩu"

Có icon:

👁

cho phép:

Hiện / ẩn mật khẩu.

Không dùng emoji nếu project có icon library.
Ưu tiên Bootstrap Icons hoặc inline SVG.

==================================================
9. REMEMBER ME
==================================================

Có:

☐ Ghi nhớ đăng nhập

Bên phải:

"Quên mật khẩu?"

Hai thành phần nằm cùng một hàng.

Mobile:
có thể chuyển thành vertical nếu cần.

==================================================
10. LOGIN BUTTON
==================================================

Button:

"ĐĂNG NHẬP →"

Button full width.

Style:

- dark forest green
- hoặc dark charcoal
- text trắng
- border-radius nhẹ
- height khoảng 48–52px.

Hover:

- background thay đổi nhẹ
- arrow dịch chuyển nhẹ sang phải.

Không dùng Bootstrap button mặc định.

==================================================
11. REGISTER
==================================================

Bên dưới:

"Chưa có tài khoản?"

Link:

"Đăng ký ngay"

Click:

→ /register

hoặc route tương ứng của project.

Link có underline animation nhẹ.

==================================================
12. SOCIAL LOGIN
==================================================

Nếu chưa triển khai OAuth,
KHÔNG tạo chức năng giả.

Chỉ có thể để UI placeholder nếu cần:

────── hoặc ──────

"Tiếp tục với Google"

Nhưng nếu backend OAuth chưa được xây dựng,
hãy ưu tiên ẩn phần này.

Không tạo chức năng Google Login giả.

==================================================
13. SECURITY / UX
==================================================

Form phải có:

- required
- autocomplete
- proper input type
- validation message.

Email:

type="email"

Password:

type="password"

Không hiển thị password plain text mặc định.

Không lưu password vào localStorage.

Không đưa password vào URL.

==================================================
14. VALIDATION UI
==================================================

Khi user submit form mà:

Email rỗng:

"Vui lòng nhập email hoặc tên đăng nhập."

Password rỗng:

"Vui lòng nhập mật khẩu."

Thông tin không chính xác:

"Email hoặc mật khẩu không chính xác."

Error message phải:

- nhỏ
- rõ ràng
- không làm layout bị nhảy quá mạnh.

==================================================
15. LOADING STATE
==================================================

Khi submit:

Button chuyển thành:

"ĐANG ĐĂNG NHẬP..."

Có loading indicator nhỏ.

Disable button trong lúc request.

Sau khi thành công:

redirect về trang trước đó hoặc:

/

Home Page.

==================================================
16. ROLE
==================================================

Database có:

users
customers

Role:

users:
- admin
- staff

customers:
- customer

LOGIN PAGE này là Public Customer Login.

Nếu project đã có cơ chế login riêng,
hãy kiểm tra và sử dụng lại.

Không tạo authentication system mới nếu
authentication đã tồn tại.

==================================================
17. ADMIN / STAFF LOGIN
==================================================

Không trộn Admin Login vào Customer Login
nếu hệ thống hiện tại đang tách riêng.

Customer Login:

/login

Register:

/register

Admin/Staff Login có thể sử dụng:

/admin/login

nếu project đã có route tương ứng.

==================================================
18. RESPONSIVE
==================================================

Desktop:

2-column layout.

Tablet:

2-column nhưng giảm kích thước hình ảnh.

Mobile:

1-column.

Ưu tiên:

LOGIN FORM
↓
BRAND IMAGE

hoặc có thể:

BRAND IMAGE
↓
LOGIN FORM

Tùy UX nhưng form phải dễ thao tác.

Mobile không được:

- horizontal overflow
- form quá rộng
- input bị tràn
- button nhỏ khó bấm.

Breakpoint Bootstrap:

sm
md
lg
xl

==================================================
19. TYPOGRAPHY
==================================================

Heading:

Cormorant Garamond
hoặc
Playfair Display

Body:

Inter
hoặc
Manrope

Brand heading sử dụng Serif.

Form label và input sử dụng Sans-serif.

==================================================
20. COLOR
==================================================

Sử dụng palette:

Warm Ivory:
#F7F4ED

Cream:
#EFE8DA

Dark:
#18231D

Forest Green:
#263A30

Sage:
#AAB79F

Tea Brown:
#9A7654

Không sử dụng màu neon.

==================================================
21. ANIMATION
==================================================

Animation rất nhẹ:

Page load:

fade-up form

Image:

fade / subtle scale

Input:

border transition

Button:

hover transition

Duration:

200–500ms.

Không dùng animation quá mạnh.

==================================================
22. PHP MVC
==================================================

Sử dụng MVC hiện tại.

Nếu chưa có:

app/
├── controllers/
│   └── AuthController.php
│
├── models/
│   ├── Customer.php
│   └── User.php
│
├── services/
│   └── AuthService.php
│
├── views/
│   ├── layouts/
│   │   ├── auth.php
│   │   ├── header.php
│   │   └── footer.php
│   │
│   └── customer/
│       └── auth/
│           ├── login.php
│           └── register.php
│
└── core/

public/
└── assets/
    ├── css/
    │   └── auth.css
    └── js/
        └── auth.js

Nếu project đã có cấu trúc tương tự,
hãy giữ cấu trúc hiện tại.

==================================================
23. AUTH LAYOUT
==================================================

Nếu Login Page không cần Header/Navbar của website:

KHÔNG hiển thị:

- navbar
- product menu
- cart
- footer lớn.

Login Page nên có một Auth Layout riêng.

Chỉ có:

Logo
+
Login Form
+
Brand Visual

Điều này giúp trang Login có cảm giác
premium và tập trung.

==================================================
24. BOOTSTRAP SETUP
==================================================

Nếu Bootstrap chưa có:

Bootstrap 5.x

Có thể sử dụng CDN.

Thứ tự CSS:

Bootstrap CSS
↓
auth.css

JavaScript:

Bootstrap Bundle JS
↓
auth.js

Không chỉnh sửa file Bootstrap gốc.

==================================================
25. ACCESSIBILITY
==================================================

Input phải có label.

Button phải có text rõ ràng.

Password toggle có:

aria-label="Hiện mật khẩu"

hoặc:

aria-label="Ẩn mật khẩu"

Có thể submit bằng Enter.

Focus state phải rõ ràng.

==================================================
26. IMPORTANT
==================================================

Đây là TRANG ĐĂNG NHẬP của thương hiệu VAA THÉ.

Không làm:

- dashboard
- sidebar
- product grid
- banner quảng cáo
- quá nhiều icon
- quá nhiều animation
- Bootstrap default template.

Tôi muốn cảm giác:

"Đây là trang đăng nhập của một premium tea brand."

==================================================
27. IMPLEMENTATION ORDER
==================================================

Trước khi code:

1. Kiểm tra project hiện tại.
2. Kiểm tra Router.
3. Kiểm tra AuthController nếu đã có.
4. Kiểm tra Customer Model.
5. Kiểm tra database.
6. Kiểm tra Bootstrap đã được sử dụng chưa.
7. Kiểm tra Header/Layout hiện tại.
8. Không tạo file trùng.

Sau đó:

STEP 1:
Tạo/hoàn thiện AuthController.

STEP 2:
Tạo Login View.

STEP 3:
Tạo auth.css.

STEP 4:
Tạo password toggle.

STEP 5:
Tạo validation UI.

STEP 6:
Tích hợp database authentication nếu backend đã sẵn sàng.

STEP 7:
Responsive.

STEP 8:
Test Login.

STEP 9:
Test Mobile.

==================================================
FINAL RESULT
==================================================

Khi user truy cập:

/login

phải thấy một Login Page:

VAA THÉ
GOOD TEA, GOOD MOOD

+

Brand visual

+

"Chào mừng trở lại"

+

Email / Username

+

Password

+

Quên mật khẩu?

+

[ ĐĂNG NHẬP → ]

+

"Chưa có tài khoản? Đăng ký ngay"

Toàn bộ trang phải đồng bộ với:

Home
Products
About

và sử dụng:

Bootstrap 5
+
Custom CSS
+
PHP MVC.

Ưu tiên visual quality và brand identity,
không để giao diện trông giống Bootstrap template.