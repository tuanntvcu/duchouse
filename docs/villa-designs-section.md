# Villa Designs Section Guide

## Mục đích

`Villa Designs Section` là section dạng tab để hiển thị các bài viết biệt thự theo danh mục. Mỗi tab trỏ tới một `Post Category`; khi người dùng bấm tab, section hiển thị các bài viết thuộc category đó. Khi bấm vào card bài viết, người dùng được chuyển tới trang chi tiết bài viết.

Section này dùng post mặc định của WordPress (`post`) thay vì custom post type để giữ đúng mô hình blog post hiện tại.

## Cấu hình ACF Trang Chủ

Vào `Pages -> Homepage -> Home Sections`, thêm layout:

`Villa Designs Section`

Các field:

- `Title`: tiêu đề section, ví dụ `1000+ THIẾT KẾ BIỆT THỰ ĐẲNG CẤP`.
- `Default Posts Per Tab`: số lượng bài viết mặc định lấy cho mỗi tab.
- `Tabs`: danh sách tab.

Mỗi tab có:

- `Label`: tên tab hiển thị, ví dụ `Biệt thự cổ điển`.
- `Post Category`: category dùng để query bài viết.
- `Posts Per Tab`: số lượng bài viết riêng cho tab đó. Nếu để trống sẽ dùng `Default Posts Per Tab`.

## Cấu hình Bài Viết

Tạo bài viết trong `Posts`, gán vào category tương ứng với tab.

ACF group `Villa Post Details` xuất hiện ở post:

- `Card Image Override`: ảnh dùng riêng cho card. Nếu bỏ trống sẽ dùng Featured Image.
- `Client / Chủ đầu tư`: chủ đầu tư.
- `Location / Địa chỉ`: địa chỉ.
- `Area / Diện tích`: diện tích, ví dụ `1221m2`.
- `Floors / Số tầng`: số tầng, ví dụ `3 tầng`.

Card luôn dùng title và permalink của bài viết WordPress. Vì vậy tiêu đề bài viết nên là tiêu đề đầy đủ cần hiển thị trên card.

## Luồng Render

Homepage hiện render từ fallback clone `index.html` thông qua:

- `front-page.php`
- `dimhouse_index_fallback_body()`
- `dimhouse_apply_clone_acf_overrides()`

Section mới được render trong `dimhouse_render_villa_designs_section()` và được chèn trước `<!-- start section 4 -->`, tức trước About section.

Mỗi tab chạy `WP_Query`:

- `post_type`: `post`
- `post_status`: `publish`
- `category__in`: category đã chọn trong tab
- `posts_per_page`: số lượng từ tab hoặc default của section

## File Liên Quan

- ACF fields: `inc/acf-fields.php`
- Render helper: `inc/helpers.php`
- Style: `style.css`

## Ghi Chú

Nếu tab không hiện bài viết:

- Kiểm tra tab đã chọn đúng `Post Category`.
- Kiểm tra bài viết đã publish.
- Kiểm tra bài viết đã được gán category đó.
- Kiểm tra `Posts Per Tab` lớn hơn 0.
- Clear cache nếu trang chủ đang bị cache.
