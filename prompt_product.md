Tôi muốn bạn thiết kế và triển khai CHỈ TRANG SẢN PHẨM (Products Page)
cho website trà sữa của tôi có thương hiệu:

VAA THÉ

Tagline:
Good Tea, Good Mood

==================================================
1. DESIGN STYLE
==================================================

Giữ nguyên phong cách thương hiệu:

Light / Gentle Cartoonish
+ Editorial Typography
+ Minimalist Luxury

Trang sản phẩm phải có cảm giác:

- Premium
- Thanh lịch
- Nhẹ nhàng
- Tối giản
- Có khoảng trắng rộng
- Typography editorial
- Hình ảnh đồ uống là điểm nhấn
- Không giống Shopee/Tiki
- Không sử dụng UI thương mại điện tử quá dày đặc.

Lấy cảm hứng từ phong cách premium tea brand và hình ảnh
reference tôi đã cung cấp trước đó, nhưng KHÔNG copy nguyên mẫu
của KOI Thé hay thương hiệu khác.

==================================================
2. BRANDING
==================================================

Tên thương hiệu:

VAA THÉ

Tagline:

GOOD TEA, GOOD MOOD

Logo:
Sử dụng logo VAA THÉ hiện có trong project nếu đã tồn tại.

Không tự tạo một logo mới nếu project đã có logo.

==================================================
3. HEADER
==================================================

Giữ Header đồng nhất với Home Page.

LEFT:
Logo VAA THÉ

CENTER:
- Trang chủ
- Sản phẩm
- Khuyến mãi
- Giới thiệu
- Liên hệ

RIGHT:
- Search
- Account
- Cart

Active menu:

"Sản phẩm"

có underline/accent tinh tế.

Header:
- nền ivory/white
- không shadow mạnh
- border-bottom rất nhẹ
- spacing rộng
- typography sạch.

==================================================
4. PAGE HERO
==================================================

Không cần Hero quá lớn như Home Page.

Tạo một Product Page introduction nhỏ:

Small label:

"OUR MENU"

Heading lớn:

"Khám phá
hương vị của bạn"

Description:

"Từ những ly trà sữa truyền thống đến những thức uống
mang hương vị hiện đại, hãy tìm cho mình một vị yêu thích."

Có thể thêm một hình minh họa nhỏ:
- tea leaf
- bubble tea
- hand-drawn decoration

Nhưng phải rất nhẹ nhàng.

==================================================
5. CATEGORY NAVIGATION
==================================================

Ngay dưới phần giới thiệu là menu category.

Categories lấy từ database:

- Tất cả
- Trà sữa
- Trà trái cây
- Đá xay
- Cà phê
- Nước ép
- Topping

Category đang chọn có:
- underline
hoặc
- background nhẹ
hoặc
- accent color.

Không làm button quá lớn.

Desktop:
category nằm ngang.

Mobile:
có thể scroll ngang.

==================================================
6. SEARCH + FILTER
==================================================

Tạo khu vực:

LEFT:
Search sản phẩm

Placeholder:

"Tìm kiếm sản phẩm..."

RIGHT:
Sort:

- Mặc định
- Giá thấp → cao
- Giá cao → thấp
- Tên A → Z

Không tạo filter sidebar quá nặng.

Có thể dùng một filter button nhỏ trên mobile.

==================================================
7. PRODUCT GRID
==================================================

Đây là phần QUAN TRỌNG NHẤT.

Hiển thị product từ database.

Desktop:

4 products / row

Tablet:

2–3 products / row

Mobile:

1–2 products / row tùy kích thước màn hình.

Product card phải mang phong cách Minimalist Luxury.

Mỗi card gồm:

1. Product image
2. Favorite icon
3. Product name
4. Short description nếu có
5. Price
6. Add button

Ví dụ:

┌────────────────────────────┐
│                    ♡       │
│                            │
│       PRODUCT IMAGE        │
│                            │
├────────────────────────────┤
│ Trà sữa truyền thống       │
│                            │
│ 35.000đ              +     │
└────────────────────────────┘

Card:
- background ivory/white
- border rất nhẹ
- border-radius vừa phải
- không shadow nặng
- hover nhẹ.

==================================================
8. PRODUCT IMAGE
==================================================

Product image phải lớn và đẹp.

Tỉ lệ khoảng:

1:1

hoặc:

4:5

Background hình ảnh:
- cream
- beige
- pastel
- hoặc background phù hợp với sản phẩm.

Khi hover:
image scale nhẹ khoảng 1.03–1.05.

Không dùng animation mạnh.

==================================================
9. PRODUCT CARD INTERACTION
==================================================

Hover product:

- image zoom nhẹ
- card translateY nhẹ
- favorite icon xuất hiện rõ hơn
- add button transition.

Click product:

→ /products/{id}

hoặc route phù hợp với Router hiện tại.

Không mở modal nếu không cần thiết.

==================================================
10. PRODUCT DATA
==================================================

Trang phải chuẩn bị để lấy dữ liệu từ database.

Database có bảng:

products

categories

sizes

toppings

product_toppings

favorites

Không hard-code product trong View nếu database đã kết nối.

Controller lấy dữ liệu:

ProductController

↓

Product Model / Service

↓

Database

↓

Product View

==================================================
11. PRODUCT DETAIL PREPARATION
==================================================

Trang Product List chỉ cần link đến Product Detail.

Product Detail sau này sẽ có:

- Product image
- Product name
- Description
- Base price
- Size
- Sugar level
- Ice level
- Toppings
- Quantity
- Add to cart

Ở Product List hiện tại:

button:

"Thêm vào giỏ"

hoặc icon "+"

Nhưng phải thiết kế để sau này có thể gọi
CartController.

==================================================
12. FAVORITE
==================================================

Mỗi product card có icon:

♡ Favorite

Nếu customer đã đăng nhập và đã yêu thích:

♥

Nếu chưa đăng nhập:
click có thể redirect tới Login.

Không cần triển khai toàn bộ Favorite nếu chức năng chưa được làm,
nhưng UI phải sẵn sàng.

==================================================
13. PRICE
==================================================

Hiển thị giá base của sản phẩm.

Ví dụ:

35.000đ

Không cộng size vào Product List.

Size surcharge sẽ được xử lý ở Product Detail.

Database có:

sizes

với:

M   +0
L   +5.000
XL  +10.000

==================================================
14. PAGINATION
==================================================

Nếu số lượng sản phẩm lớn:

Hiển thị pagination tối giản.

Ví dụ:

←  1  2  3  4  →

Không dùng pagination kiểu Bootstrap.

Nếu chưa có backend pagination,
hãy chuẩn bị UI để sau này tích hợp.

==================================================
15. EMPTY STATE
==================================================

Nếu không có sản phẩm:

Hiển thị:

"Không tìm thấy sản phẩm"

và:

"Thử tìm kiếm với từ khóa khác."

Thiết kế nhẹ nhàng, không quá nhiều illustration.

==================================================
16. FOOTER
==================================================

Giữ Footer đồng nhất với Home Page.

Brand:

VAA THÉ

GOOD TEA, GOOD MOOD

Columns:

LIÊN KẾT
- Trang chủ
- Sản phẩm
- Khuyến mãi
- Giới thiệu
- Liên hệ

HỖ TRỢ
- Hướng dẫn mua hàng
- Chính sách đổi trả
- Chính sách bảo mật
- FAQ

LIÊN HỆ
- Địa chỉ
- Điện thoại
- Email
- Giờ mở cửa

==================================================
17. RESPONSIVE
==================================================

Desktop:
4 products / row.

Tablet:
2–3 products / row.

Mobile:
2 products / row nếu màn hình đủ rộng,
1 product / row trên màn hình nhỏ.

Category:
horizontal scroll.

Search:
full width trên mobile.

Header:
hamburger menu.

Không để:
- horizontal overflow
- text tràn
- image méo
- button bị vỡ layout.

==================================================
18. TECHNICAL REQUIREMENTS
==================================================

Project:

PHP thuần
HTML5
CSS3
MySQL/MariaDB
MVC

KHÔNG sử dụng:

- Bootstrap
- React
- Tailwind
- Laravel

Không viết database query trực tiếp trong View.

Không viết business logic trong View.

Không viết toàn bộ Product Page trong một file.

Kiến trúc:

ProductController
        ↓
Product Model / Service
        ↓
Database
        ↓
products/index.php

Reusable:

layouts/header.php
layouts/footer.php
partials/navbar.php
partials/product-card.php

==================================================
19. FILE STRUCTURE
==================================================

Nếu project hiện tại đã có structure thì giữ nguyên.

Nếu chưa có:

app/
├── controllers/
│   └── ProductController.php
│
├── models/
│   ├── Product.php
│   └── Category.php
│
├── services/
│
├── views/
│   ├── layouts/
│   │   ├── header.php
│   │   └── footer.php
│   │
│   ├── partials/
│   │   ├── navbar.php
│   │   └── product-card.php
│   │
│   └── customer/
│       └── products/
│           └── index.php
│
├── core/
└── config/

public/
├── index.php
└── assets/
    ├── css/
    │   └── products.css
    ├── js/
    │   └── products.js
    └── images/
        └── products/

==================================================
20. IMPORTANT
==================================================

ĐỪNG làm Product Page giống một trang thương mại điện tử
thông thường.

Tôi muốn khi người dùng mở trang:

"Sản phẩm"

họ cảm nhận được đây là một PREMIUM TEA BRAND.

Ưu tiên:

Typography
+
Photography
+
Whitespace
+
Grid
+
Brand identity

hơn việc nhồi quá nhiều UI.

Trang phải sạch, sang và có tính editorial.

==================================================
21. IMPLEMENTATION ORDER
==================================================

Bước 1:
Kiểm tra project hiện tại.

Bước 2:
Kiểm tra các file Header/Footer đã có.

Bước 3:
Không tạo file trùng.

Bước 4:
Tạo ProductController.

Bước 5:
Tạo Product Model.

Bước 6:
Tạo Product List View.

Bước 7:
Tạo reusable product-card.php.

Bước 8:
Tạo products.css.

Bước 9:
Kết nối category.

Bước 10:
Kết nối search/filter/sort.

Bước 11:
Responsive.

Bước 12:
Test toàn bộ route.

Nếu database đã có dữ liệu,
hãy sử dụng dữ liệu thật.

Nếu database chưa có dữ liệu,
hãy tạo mock data tạm thời nhưng cấu trúc code
phải sẵn sàng chuyển sang database thật.

Trước khi code, hãy kiểm tra project hiện tại
và tận dụng các component/file đã tồn tại thay vì
tạo lại từ đầu.