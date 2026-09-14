Tôi muốn thêm một INTRO VIDEO SCREEN xuất hiện trước khi
người dùng truy cập vào trang chủ của website VAA THÉ.

==================================================
1. MỤC TIÊU
==================================================

Khi người dùng truy cập website:

VAA THÉ

hãy HIỂN THỊ VIDEO INTRO trước tiên.

Video này là video thương hiệu mà tôi đã chuẩn bị sẵn.

Flow:

User truy cập website
        ↓
Intro Video Screen
        ↓
Video tự động phát
        ↓
[ SKIP INTRO ]
        ↓
Home Page

Hoặc:

Video phát hết
        ↓
Tự động chuyển vào Home Page

==================================================
2. VIDEO
==================================================

Tôi đã chuẩn bị sẵn một video intro.

KHÔNG tạo video mới.

Hãy sử dụng file video tôi cung cấp.

Video phải:

- autoplay
- muted
- playsinline
- loop = false

Nếu trình duyệt không cho autoplay có âm thanh,
video phải tự động phát ở chế độ muted.

HTML video nên tương đương:

<video
    autoplay
    muted
    playsinline
    preload="auto">
</video>

Không bật âm thanh tự động vì có thể bị browser chặn.

==================================================
3. INTRO SCREEN
==================================================

Intro Video phải phủ TOÀN BỘ màn hình.

Sử dụng:

position: fixed;
inset: 0;
z-index rất cao;

để Intro nằm trên toàn bộ website.

Video:

width: 100%;
height: 100%;
object-fit: cover;

Video phải giữ đúng tỷ lệ và không bị méo.

==================================================
4. OVERLAY
==================================================

Không đặt quá nhiều UI lên video.

Chỉ cần một UI rất tối giản.

Góc trên:

VAA THÉ

hoặc sử dụng logo VAA THÉ nếu logo đã tồn tại.

Góc dưới hoặc góc trên bên phải:

SKIP INTRO →

Button phải nhỏ, tinh tế.

Ví dụ:

┌───────────────────────────────────────┐
│ VAA THÉ                         SKIP → │
│                                       │
│                                       │
│            INTRO VIDEO                │
│                                       │
│                                       │
│                                       │
└───────────────────────────────────────┘

==================================================
5. SKIP BUTTON
==================================================

Bắt buộc phải có nút:

"SKIP INTRO"

Người dùng click vào:

SKIP INTRO

thì:

1. Dừng video.
2. Intro biến mất.
3. Hiển thị Home Page.

Không reload toàn bộ website.

Sử dụng animation:

fade-out

khoảng:

500–800ms.

Ví dụ:

Intro
opacity: 1

↓

click Skip

↓

opacity: 0

↓

display: none

↓

Home Page.

==================================================
6. VIDEO END
==================================================

Nếu người dùng không click Skip:

Khi video phát hết:

video.onended

↓

Intro fade-out

↓

Home Page xuất hiện.

Không để màn hình bị đứng ở frame cuối.

==================================================
7. SKIP ANIMATION
==================================================

Khi click:

SKIP INTRO

không chuyển trang đột ngột.

Sử dụng:

opacity transition
+
visibility
+
pointer-events

Ví dụ:

.intro-video-wrapper {
    opacity: 1;
    visibility: visible;
    transition: opacity 0.7s ease;
}

.intro-video-wrapper.hide {
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
}

==================================================
8. LOADING
==================================================

Nếu video chưa load xong:

Không để người dùng nhìn thấy màn hình trắng.

Có thể hiển thị một loading screen tối giản:

VAA THÉ

Good Tea, Good Mood

và sau khi video sẵn sàng:

fade-in video.

Không sử dụng spinner quá lớn.

==================================================
9. BRAND STYLE
==================================================

Intro phải phù hợp với brand:

VAA THÉ

Style:

Minimalist Luxury
+
Editorial Typography
+
Light / Gentle Cartoonish

Nhưng vì đây là Intro Video nên UI phải CỰC KỲ TỐI GIẢN.

Không làm:

- navbar
- product cards
- menu
- banner
- quá nhiều text.

Video là nhân vật chính.

==================================================
10. SKIP BUTTON DESIGN
==================================================

Button:

SKIP INTRO →

Phong cách:

- transparent hoặc semi-transparent
- border mảnh
- màu trắng nếu video tối
- màu dark nếu video sáng
- uppercase
- letter-spacing rộng
- font-size nhỏ

Hover:

- background nhẹ
- arrow di chuyển nhẹ sang phải

Transition:

300ms.

Không sử dụng Bootstrap button mặc định.

Có thể dùng Bootstrap utilities nếu cần,
nhưng button phải custom CSS.

==================================================
11. RESPONSIVE
==================================================

Desktop:
video full viewport.

Tablet:
video full viewport.

Mobile:
video full viewport.

Sử dụng:

object-fit: cover;

Nếu video có tỷ lệ khác với màn hình,
ưu tiên giữ focal point của video.

Không để:

- video bị méo
- thanh scroll xuất hiện
- horizontal overflow.

==================================================
12. MOBILE AUTOPLAY
==================================================

Đảm bảo video mobile sử dụng:

muted
playsinline
autoplay

vì mobile browser thường hạn chế autoplay.

Nếu autoplay bị browser chặn:

hiển thị một nút:

"XEM VIDEO"

để user click và bắt đầu video.

Sau đó vẫn có:

SKIP INTRO

==================================================
13. SESSION / COOKIE
==================================================

QUAN TRỌNG:

Không nhất thiết phải phát Intro Video
mỗi lần user chuyển trang.

Intro chỉ nên xuất hiện khi user bắt đầu một phiên truy cập website.

Ví dụ:

Lần đầu vào website:

Intro Video
→ Home

Khi chuyển:

Home → Products → About

KHÔNG hiển thị Intro lại.

Có thể sử dụng:

sessionStorage

Ví dụ:

sessionStorage.setItem('introShown', 'true');

Khi user refresh:

tùy thiết kế có thể không phát lại.

Ưu tiên trải nghiệm:

Intro chỉ xuất hiện một lần trong một session.

==================================================
14. PHP MVC
==================================================

Intro Video là một phần của frontend layout,
không tạo một hệ thống MVC riêng.

Không viết logic intro vào Controller nếu không cần.

Có thể đặt:

public/assets/videos/
    └── intro.mp4

CSS:

public/assets/css/intro.css

JS:

public/assets/js/intro.js

Nếu cần tích hợp layout:

app/views/layouts/header.php

hoặc layout phù hợp với cấu trúc hiện tại.

Tuy nhiên KHÔNG làm Intro xuất hiện
trong Admin Dashboard.

Intro chỉ áp dụng cho Customer/Public website.

==================================================
15. ROUTING
==================================================

Flow:

/ 
↓
Intro Video
↓
Home

/products
/about
/contact

KHÔNG hiển thị Intro lại khi user
đang chuyển giữa các trang.

Nếu Router hiện tại có layout Customer,
hãy tích hợp Intro vào Customer/Public layout.

Không đưa vào Admin layout.

==================================================
16. ACCESSIBILITY
==================================================

Button phải có:

aria-label="Skip intro"

Video có:

aria-hidden="true"

Không để intro chặn keyboard navigation
nếu user muốn skip.

Có thể hỗ trợ:

ESC

để skip intro.

Ví dụ:

User nhấn ESC
↓
Skip Intro.

==================================================
17. PERFORMANCE
==================================================

Video có thể khá lớn nên:

- preload="auto" hoặc "metadata" tùy kích thước video
- không tải video lại nhiều lần
- không tạo duplicate video
- chỉ có một video element.

Nếu video quá lớn,
khuyến nghị tối ưu file trước khi deploy.

==================================================
18. IMPORTANT UX
==================================================

Đây là INTRO VIDEO,
không phải loading screen.

Mục tiêu:

Tạo ấn tượng thương hiệu khi user
lần đầu truy cập VAA THÉ.

Nhưng KHÔNG được gây khó chịu.

Do đó:

- Có Skip Intro rõ ràng.
- Không bắt user xem video.
- Không autoplay âm thanh.
- Không loop.
- Video kết thúc tự động vào Home.
- Animation chuyển tiếp mượt.
- Không làm người dùng bị kẹt ở Intro.

==================================================
19. IMPLEMENTATION
==================================================

Trước khi code:

1. Kiểm tra cấu trúc project.
2. Kiểm tra layout hiện tại.
3. Kiểm tra vị trí assets.
4. Kiểm tra file video tôi đã cung cấp.
5. Không tạo file trùng.
6. Nếu đã có JavaScript/CSS global,
   hãy tận dụng nhưng không làm ảnh hưởng trang khác.

Sau đó:

STEP 1:
Đặt video vào:

public/assets/videos/

STEP 2:
Tạo:

intro.css

STEP 3:
Tạo:

intro.js

STEP 4:
Tạo Intro Video component/partial phù hợp
với MVC hiện tại.

STEP 5:
Tích hợp vào Public/Customer Layout.

STEP 6:
Implement:

Autoplay
Muted
Playsinline
Skip
Video End
Fade-out
ESC
SessionStorage

STEP 7:
Responsive.

STEP 8:
Test:

Chrome Desktop
Chrome Mobile
Edge
Mobile viewport.

==================================================
FINAL RESULT
==================================================

Khi truy cập:

VAA THÉ

người dùng sẽ thấy:

        VAA THÉ

        [ FULLSCREEN INTRO VIDEO ]

                         SKIP INTRO →

Video tự chạy.

Nếu user click:

SKIP INTRO

→ fade out
→ Home Page.

Nếu user không click:

Video chạy hết
→ fade out
→ Home Page.

Intro phải mang cảm giác:

"Premium tea brand"

chứ không giống:

"Website loading screen".

Hãy kiểm tra project hiện tại trước khi triển khai
và tận dụng cấu trúc MVC/layout hiện có.