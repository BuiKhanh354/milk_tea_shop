Hãy xây dựng TRANG CHI TIẾT SẢN PHẨM cho website VAA THÉ.

Thương hiệu:

VAA THÉ
GOOD TEA, GOOD MOOD

Công nghệ bắt buộc:

- PHP thuần theo mô hình MVC
- HTML5
- CSS3
- JavaScript
- Bootstrap 5
- Bootstrap Icons hoặc Font Awesome

Không sử dụng:

- React
- Vue
- Angular
- Laravel
- Tailwind CSS

==================================================
1. MỤC TIÊU
==================================================

Khi khách hàng bấm vào BẤT KỲ SẢN PHẨM NÀO trên:

- Trang chủ
- Trang Menu
- Trang tìm kiếm
- Trang yêu thích

thì chuyển đến:

/products/{id}

Ví dụ:

/products/1
/products/2
/products/15

Trang phải lấy ID sản phẩm để hiển thị thông tin tương ứng.

Mỗi sản phẩm có một trang chi tiết riêng.

==================================================
2. LAYOUT TỔNG THỂ
==================================================

Trang chi tiết gồm:

HEADER
↓
BREADCRUMB
↓
PRODUCT DETAIL
↓
PRODUCT DESCRIPTION / STORY
↓
REVIEWS
↓
RELATED PRODUCTS
↓
FOOTER

Không thiết kế giống Admin.

Đây là trang Customer/Public.

==================================================
3. HEADER
==================================================

Sử dụng navbar chung của website VAA THÉ.

Logo:

VAA THÉ

Menu:

HOME
MENU
PROMOTIONS
ABOUT
CONTACT

Bên phải:

Search
Account
Favorites
Cart

Cart có badge số lượng.

==================================================
4. BREADCRUMB
==================================================

Ngay dưới header:

HOME
/
MENU
/
TRÀ SỮA TRUYỀN THỐNG

Breadcrumb phải tự thay đổi theo sản phẩm.

Ví dụ:

HOME / MENU / TRÀ ĐÀO

Không hard-code tên sản phẩm.

==================================================
5. PRODUCT DETAIL
==================================================

Desktop:

Chia layout thành 2 cột.

Bên trái:

PRODUCT IMAGE

Bên phải:

Product information.

Tỷ lệ khoảng:

Image: 50%
Information: 50%

Có khoảng trắng lớn.

==================================================
6. PRODUCT IMAGE
==================================================

Hiển thị hình ảnh sản phẩm lớn.

Image:

- Tỷ lệ đẹp
- Không méo
- Object-fit: cover/contain phù hợp
- Border-radius nhẹ
- Background cream/ivory

Có hiệu ứng hover zoom nhẹ.

Nếu sản phẩm có nhiều hình:

Hiển thị thumbnail bên dưới hoặc bên trái.

Click thumbnail để thay đổi ảnh chính.

Nếu chỉ có một ảnh:

Chỉ hiển thị ảnh chính.

==================================================
7. PRODUCT INFORMATION
==================================================

Hiển thị:

CATEGORY

Product Name

Rating

Review count

Description

Price

Ví dụ:

TRÀ SỮA

Trà Sữa Truyền Thống

★★★★★
4.9 (128 reviews)

"Hương vị trà đậm đà kết hợp cùng vị sữa
béo nhẹ và trân châu dai mềm."

45.000đ

Không hard-code dữ liệu.

Lấy dữ liệu từ product.

==================================================
8. FAVORITE
==================================================

Bên cạnh tên sản phẩm hoặc gần khu vực price:

♡ Favorite

Khi click:

♡ → ♥ 

Có trạng thái:

Đã yêu thích
Chưa yêu thích

Nếu customer chưa đăng nhập:

Có thể yêu cầu đăng nhập khi xử lý backend sau này.

Ở giai đoạn UI:

Chỉ cần mô phỏng trạng thái bằng JavaScript.

==================================================
9. CHỌN SIZE
==================================================

Tạo section:

CHOOSE YOUR SIZE

Options:

M
L
XL

Hiển thị giá:

M
+0đ

L
+5.000đ

XL
+10.000đ

Mặc định:

M được chọn.

Thiết kế dạng selectable cards/radio.

Khi click:

L → cập nhật giá.

XL → cập nhật giá.

Không reload trang.

==================================================
10. CHỌN ĐƯỜNG
==================================================

Section:

SUGAR LEVEL

Options:

0%
25%
50%
75%
100%

Mặc định:

50%

Thiết kế dạng button/radio.

Chỉ được chọn một mức.

==================================================
11. CHỌN ĐÁ
==================================================

Section:

ICE LEVEL

Options:

0%
25%
50%
75%
100%

Mặc định:

50%

Chỉ được chọn một mức.

==================================================
12. TOPPING
==================================================

Section:

ADD TOPPINGS

Hiển thị danh sách topping.

Ví dụ:

□ Trân châu đen +5.000đ
□ Trân châu trắng +5.000đ
□ Pudding +7.000đ
□ Thạch trái cây +5.000đ

Có thể chọn nhiều topping.

Khi chọn topping:

Tự động cộng giá.

Khi bỏ chọn:

Tự động trừ giá.

Không reload trang.

==================================================
13. QUANTITY
==================================================

Section:

QUANTITY

[-] 1 [+]

Mặc định:

1

Không cho số lượng < 1.

==================================================
14. NOTE
==================================================

Tạo textarea:

NOTE FOR YOUR DRINK

Placeholder:

"Ví dụ: Ít ngọt hơn, nhiều trân châu..."

Khách hàng có thể nhập ghi chú.

==================================================
15. GIÁ TỰ ĐỘNG TÍNH
==================================================

Đây là phần rất quan trọng.

Giá phải tự động cập nhật bằng JavaScript.

Công thức:

Total Unit Price
=
Base Product Price
+
Size Extra Price
+
Total Topping Price

Sau đó:

Total Price
=
Total Unit Price × Quantity

Ví dụ:

Trà sữa:
35.000đ

Size L:
+5.000đ

Trân châu:
+5.000đ

→ Đơn giá:

45.000đ

Quantity:
2

→ Tổng:

90.000đ

Hiển thị rõ:

ĐƠN GIÁ
45.000đ

TỔNG
90.000đ

==================================================
16. ADD TO CART
==================================================

Button chính:

ADD TO CART

Hoặc:

THÊM VÀO GIỎ

Button lớn, nổi bật.

Khi click:

Hiển thị toast:

"Đã thêm sản phẩm vào giỏ hàng."

Không reload trang.

Có thể cập nhật badge Cart.

==================================================
17. BUY NOW
==================================================

Bên cạnh:

BUY NOW

Hoặc:

MUA NGAY

Khi click:

Chuyển sang:

/checkout

với sản phẩm đã chọn.

Ở giai đoạn UI:

Có thể mô phỏng bằng JavaScript.

==================================================
18. PRODUCT STORY
==================================================

Bên dưới khu vực mua hàng:

THE STORY BEHIND THE TEA

Hiển thị:

- Mô tả sản phẩm
- Thành phần
- Hương vị
- Gợi ý sử dụng

Ví dụ:

Tea
Milk
Tapioca pearls

Không cần quá nhiều text.

Thiết kế editorial.

==================================================
19. PRODUCT INFORMATION
==================================================

Tạo section:

PRODUCT INFORMATION

Có thể hiển thị:

Category
Ingredients
Available sizes
Calories nếu có
Allergens nếu có

Nếu dữ liệu chưa có:

Dùng placeholder hợp lý.

Không tự thêm quá nhiều thông tin giả.

==================================================
20. REVIEWS
==================================================

Section:

CUSTOMER REVIEWS

Hiển thị:

Average rating

★★★★★

4.8 out of 5

128 reviews

Sau đó:

Review list.

Mỗi review:

Customer name
Rating
Date
Comment

Ví dụ:

Nguyễn Văn A
★★★★★
"Trà thơm, vị vừa phải và topping ngon."

==================================================
21. REVIEW FORM
==================================================

Nếu customer đã đăng nhập:

Hiển thị:

WRITE A REVIEW

Rating:

☆ ☆ ☆ ☆ ☆

Comment textarea.

Button:

SUBMIT REVIEW

Nếu chưa đăng nhập:

Hiển thị:

"Please login to write a review."

Button:

LOGIN

Chỉ cần UI ở giai đoạn này.

==================================================
22. RELATED PRODUCTS
==================================================

Bên dưới reviews:

YOU MAY ALSO LIKE

Hiển thị 4 sản phẩm liên quan.

Product card:

Image
Name
Price
Favorite
ADD TO CART

Các sản phẩm nên cùng category hoặc liên quan.

Click product card:

→ /products/{id}

Không hard-code link cố định.

==================================================
23. RESPONSIVE
==================================================

Desktop:

2 columns.

Tablet:

2 columns nhưng thu gọn.

Mobile:

Image
↓
Product information
↓
Size
↓
Sugar
↓
Ice
↓
Toppings
↓
Quantity
↓
Note
↓
Price
↓
Buttons

Toàn bộ thành một column.

Button Add to Cart / Buy Now phải dễ bấm trên mobile.

==================================================
24. DESIGN
==================================================

Phong cách VAA THÉ:

Minimalist Luxury
Editorial Typography
Light / Gentle Cartoonish
Premium Tea Brand
Elegant
Warm
Modern
Clean

Không sử dụng card quá nặng.

Không có shadow quá mạnh.

Không dùng gradient quá nhiều.

Không dùng animation quá mạnh.

==================================================
25. COLOR
==================================================

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

==================================================
26. TYPOGRAPHY
==================================================

Heading:

Cormorant Garamond
hoặc
Playfair Display

Body:

Inter
hoặc
Manrope

==================================================
27. BOOTSTRAP 5
==================================================

Bắt buộc sử dụng Bootstrap 5.

Sử dụng:

container
row
col
btn
form
breadcrumb
card khi cần
badge
toast
modal
responsive utilities

Nhưng custom CSS toàn bộ visual style.

Không để giao diện giống Bootstrap mặc định.

==================================================
28. MVC
==================================================

Controller:

app/controllers/ProductController.php

Model:

app/models/Product.php
app/models/Size.php
app/models/Topping.php
app/models/Review.php

View:

app/views/customer/products/
├── index.php
└── detail.php

Reusable:

app/views/partials/
├── navbar.php
├── footer.php
├── product-card.php
├── rating.php
└── breadcrumb.php

CSS:

public/assets/css/
├── products.css
└── product-detail.css

JavaScript:

public/assets/js/
└── product-detail.js

==================================================
29. MOCK DATA
==================================================

Nếu chưa kết nối database:

Tạo mock product:

id
name
category
description
price
image
rating
review_count

Mock sizes:

M = 0
L = 5000
XL = 10000

Mock toppings:

Trân châu đen = 5000
Trân châu trắng = 5000
Pudding = 7000
Thạch trái cây = 5000

Dùng JavaScript để tính giá.

==================================================
30. URL
==================================================

Khi click:

Trà sữa truyền thống:

/products/1

Trà đào:

/products/2

Matcha Latte:

/products/3

Không được tạo riêng:

/tra-sua-truyen-thong.php

Mỗi sản phẩm phải sử dụng:

/products/{id}

==================================================
31. ERROR STATE
==================================================

Nếu product ID không tồn tại:

Hiển thị:

PRODUCT NOT FOUND

"Sorry, we couldn't find this tea."

Button:

BACK TO MENU

Không hiển thị lỗi PHP thô cho người dùng.

==================================================
32. LOADING STATE
==================================================

Nếu sau này dữ liệu lấy từ database/API:

Hiển thị skeleton/loading nhẹ trong lúc tải sản phẩm.

Ở giai đoạn UI:

Có thể tạo skeleton component để demo.

==================================================
33. QUAN TRỌNG
==================================================

Trang này phải hoạt động như một PRODUCT DETAIL PAGE thực tế.

Khách hàng có thể:

1. Xem hình sản phẩm
2. Xem tên
3. Xem mô tả
4. Xem giá
5. Chọn size
6. Chọn % đường
7. Chọn % đá
8. Chọn nhiều topping
9. Chọn số lượng
10. Nhập ghi chú
11. Xem giá tự động cập nhật
12. Thêm vào yêu thích
13. Thêm vào giỏ hàng
14. Mua ngay
15. Xem đánh giá
16. Xem sản phẩm liên quan

==================================================
34. KHÔNG LÀM
==================================================

Chưa cần:

- Thanh toán thật
- MoMo API
- VNPay API
- Database thật
- Trừ kho
- Tạo order thật
- Authentication backend
- API

Nhưng cấu trúc code phải sẵn sàng để tích hợp các chức năng này sau.

==================================================
35. KẾT QUẢ CUỐI
==================================================

Khi tôi đang ở:

/products

và click vào:

[Trà sữa truyền thống]

phải chuyển sang:

/products/1

và hiển thị đúng trang:

TRÀ SỮA TRUYỀN THỐNG

Khách hàng có thể tùy chỉnh:

Size
Sugar
Ice
Toppings
Quantity
Note

Giá cập nhật realtime.

Sau đó:

ADD TO CART

hoặc

BUY NOW

Giao diện phải thống nhất với toàn bộ thương hiệu VAA THÉ.