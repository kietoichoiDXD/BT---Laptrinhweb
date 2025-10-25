# Feedback Management System 🎯# Dự Án PHP Feedback Form



![PHP](https://img.shields.io/badge/PHP-8.2-blue)Dự án PHP thuần để nhận và quản lý feedback từ người dùng.

![MySQL](https://img.shields.io/badge/MySQL-8.0-orange)

![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple)## Cấu Trúc Thư Mục

![License](https://img.shields.io/badge/License-MIT-green)

```

## 📋 Mô tả dự ánform/

│

Hệ thống quản lý feedback hoàn chỉnh được xây dựng bằng PHP thuần và MySQL. Dự án đáp ứng đầy đủ các yêu cầu:├── config/

- Gửi và quản lý feedback│   └── database.php          # Cấu hình kết nối database

- Cá nhân hóa với MSSV│

- Conditional Styling (urgent feedback)├── database/

- Phân trang (pagination)│   └── setup.sql             # Script tạo database và bảng

- Bảo mật với Prepared Statements│

├── assets/

## ✨ Tính năng│   └── css/

│       └── style.css         # CSS styling

### Tính năng cơ bản│

- ✅ **Form gửi feedback** (`submit.php`) - Nhập đầy đủ thông tin với validation├── index.php                 # Trang form nhập feedback

- ✅ **Danh sách feedback** (`index.php`) - Hiển thị với phân trang├── process.php               # Xử lý dữ liệu form

- ✅ **Xem chi tiết** (`feedback_detail.php`) - Xem thông tin đầy đủ├── view.php                  # Hiển thị danh sách feedback

- ✅ **Xóa feedback** (`delete.php`) - Xóa an toàn với POST method├── delete.php                # Xóa feedback

└── README.md                 # File hướng dẫn

### Tính năng đặc biệt```

- 🎓 **Cá nhân hóa MSSV** - Mỗi feedback lưu kèm mã số sinh viên

- 🚨 **Conditional Styling** - Feedback có từ "urgent" được làm nổi bật## Cấu Trúc Database

- 📄 **Phân trang** - Hiển thị 5 feedback/trang với navigation đẹp

- 🔒 **Bảo mật cao** - PDO Prepared Statements, XSS protection### Bảng: feedbacks

- `id` (INT, AUTO_INCREMENT, PRIMARY KEY)

## 🚀 Cài đặt- `name` (VARCHAR(100)) - Tên người gửi

- `email` (VARCHAR(100)) - Email người gửi

### Yêu cầu hệ thống- `subject` (VARCHAR(200)) - Tiêu đề feedback

- PHP 7.4 trở lên (Khuyến nghị: PHP 8.2)- `message` (TEXT) - Nội dung feedback

- MySQL 5.6 trở lên- `rating` (INT) - Đánh giá từ 1-5 sao

- Web server (Apache/Nginx) hoặc PHP built-in server- `created_at` (TIMESTAMP) - Thời gian tạo



### Các bước cài đặt## Cài Đặt



1. **Clone repository**### Yêu Cầu

   ```bash- PHP 7.0 trở lên

   git clone https://github.com/kietoichoiDXD/BT---Laptrinhweb.git- MySQL 5.6 trở lên

   cd BT---Laptrinhweb- Web server (Apache/Nginx) hoặc PHP built-in server

   ```

### Các Bước Cài Đặt

2. **Cấu hình database**

   - Mở file `config.php`1. **Import Database**

   - Chỉnh sửa thông tin kết nối:   - Mở phpMyAdmin hoặc MySQL client

   ```php   - Import file `database/setup.sql`

   define('DB_HOST', 'localhost');   - Hoặc chạy lệnh:

   define('DB_PORT', 3307);      // Port MySQL của bạn   ```bash

   define('DB_USER', 'root');   mysql -u root -p < database/setup.sql

   define('DB_PASS', '');   ```

   define('DB_NAME', 'feedback_db');

   ```2. **Cấu Hình Database**

   - Mở file `config/database.php`

3. **Cấu hình MSSV**   - Chỉnh sửa thông tin kết nối:

   - Trong file `config.php`, thay đổi MSSV của bạn:   ```php

   ```php   define('DB_HOST', 'localhost');

   define('STUDENT_ID', 'B20DCCN001'); // Thay bằng MSSV của bạn   define('DB_PORT', 3307);

   ```   define('DB_USER', 'root');

   define('DB_PASS', 'your_password');

4. **Tạo database**   define('DB_NAME', 'feedback_db');

   - **Cách 1:** Truy cập `http://localhost/setup_database.php` (Tự động)   ```

   - **Cách 2:** Import file `database/setup.sql` vào MySQL

   - **Cách 3:** Chạy lệnh:3. **Chạy Ứng Dụng**

   ```bash   

   mysql -u root -p < database/setup.sql   **Sử dụng XAMPP/WAMP:**

   ```   - Copy thư mục vào `htdocs/` hoặc `www/`

   - Truy cập: `http://localhost/form/`

5. **Chạy server**   

      **Sử dụng PHP Built-in Server:**

   **Với XAMPP/WAMP:**   ```bash

   - Copy thư mục vào `htdocs/` hoặc `www/`   cd "e:\UED\TK và LT WEB\form"

   - Truy cập: `http://localhost/BT---Laptrinhweb/`   php -S localhost:80

      ```

   **Với PHP Built-in Server:**   - Truy cập: `http://localhost/`

   ```bash

   php -S localhost:8000## Tính Năng

   ```

   - Truy cập: `http://localhost:8000/`✅ Gửi feedback với thông tin:

  - Họ tên

## 📁 Cấu trúc dự án  - Email

  - Tiêu đề

```  - Đánh giá (1-5 sao)

BT---Laptrinhweb/  - Nội dung

│

├── components/              # Components tái sử dụng✅ Validate dữ liệu đầu vào

│   ├── header.php          # Header với navigation✅ Hiển thị danh sách feedback

│   └── footer.php          # Footer✅ Xóa feedback

│✅ Responsive design

├── config/                 # Cấu hình✅ Bảo mật với Prepared Statements

│   └── database.php        # Database config (legacy)✅ XSS protection với htmlspecialchars()

│

├── database/               # Database## Sử Dụng

│   └── setup.sql          # SQL script

│1. **Gửi Feedback**: Truy cập `index.php` và điền form

├── assets/                 # Assets2. **Xem Feedback**: Click "Xem Feedback" hoặc truy cập `view.php`

│   └── css/3. **Xóa Feedback**: Click nút "Xóa" trên mỗi feedback

│       └── style.css      # Custom CSS

│## Bảo Mật

├── config.php             # Cấu hình chính (PDO + MySQLi)

├── index.php              # Danh sách feedback (có phân trang)- Sử dụng Prepared Statements để tránh SQL Injection

├── submit.php             # Form gửi feedback- Validate và sanitize input

├── view.php               # Xem tất cả (không phân trang)- XSS protection với htmlspecialchars()

├── feedback_detail.php    # Chi tiết feedback- CSRF protection với sessions

├── delete.php             # Xóa feedback

├── process.php            # Xử lý form (legacy)## Mở Rộng

├── setup_database.php     # Auto setup database

└── README.md              # File nàyCó thể mở rộng thêm:

```- Phân trang

- Tìm kiếm và lọc

## 💡 Hướng dẫn sử dụng- Export dữ liệu

- Quản trị viên (admin panel)

### Gửi feedback mới- Email notification

1. Truy cập `submit.php`- API endpoints

2. Điền thông tin: Tên, Email, Tiêu đề, Nội dung, Đánh giá

3. Click "Gửi Feedback"## License

4. Tự động chuyển về trang danh sách

Free to use for educational purposes.

### Xem danh sách feedback
- **Có phân trang:** `index.php` - Hiển thị 5 feedback/trang
- **Xem tất cả:** `view.php` - Không phân trang

### Tạo feedback URGENT
Thêm từ khóa "urgent" vào tiêu đề (không phân biệt hoa thường):
- "URGENT: Lỗi hệ thống"
- "Bug urgent cần fix"
- "Vấn đề Urgent"

Feedback sẽ được làm nổi bật với:
- Border đỏ dày 5px
- Background màu vàng
- Badge "URGENT"
- Animation pulse

### Xóa feedback
1. Click nút "Xóa" trên feedback
2. Xác nhận xóa
3. Feedback sẽ bị xóa vĩnh viễn

## 🔒 Bảo mật

- ✅ **SQL Injection:** PDO Prepared Statements
- ✅ **XSS:** htmlspecialchars() cho output
- ✅ **Email Validation:** filter_var()
- ✅ **Input Sanitization:** trim(), stripslashes()
- ✅ **CSRF Protection:** Session management
- ✅ **Safe Delete:** POST method với confirm

## 🎨 Công nghệ sử dụng

- **Backend:** PHP 8.2 (Pure PHP, PDO)
- **Database:** MySQL 8.0
- **Frontend:** Bootstrap 5.3.2, Bootstrap Icons
- **CSS:** Custom CSS với animations
- **Security:** Prepared Statements, Input Validation

## 📊 Cấu trúc Database

### Bảng: `feedbacks`
```sql
CREATE TABLE feedbacks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    rating INT DEFAULT 5,
    student_id VARCHAR(20) NULL,      -- MSSV
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_created_at (created_at),
    INDEX idx_student_id (student_id)
);
```

## 🌟 Tính năng nổi bật

### 1. Cá nhân hóa MSSV
Mỗi feedback tự động lưu MSSV của sinh viên. Hiển thị dạng:
```
Gửi bởi: Nguyễn Văn A (B20DCCN001)
```

### 2. Conditional Styling
Tự động phát hiện và làm nổi bật feedback urgent:
```php
$isUrgent = stripos($row['subject'], 'urgent') !== false;
```

### 3. Phân trang thông minh
- Hiển thị 5 feedback/trang (configurable)
- Navigation với "..." cho nhiều trang
- Disable nút khi ở đầu/cuối
- URL parameters: `?page=2`

## 🚨 Xử lý lỗi

### Lỗi kết nối database
- Hiển thị thông báo rõ ràng
- Link đến trang setup_database.php
- Hướng dẫn khắc phục

### Lỗi validation
- Hiển thị tất cả lỗi trên form
- Giữ lại dữ liệu đã nhập
- Alert có thể dismiss

## 📝 Changelog

### Version 1.0.0 (2025-10-18)
- ✅ Initial release
- ✅ Form gửi feedback với validation
- ✅ Danh sách feedback với phân trang
- ✅ Cá nhân hóa MSSV
- ✅ Conditional Styling cho urgent feedback
- ✅ Tính năng xóa feedback
- ✅ Bootstrap 5 responsive design
- ✅ Bảo mật với PDO Prepared Statements

## 👨‍💻 Tác giả

- **GitHub:** [@kietoichoiDXD](https://github.com/kietoichoiDXD)
- **Repository:** [BT---Laptrinhweb](https://github.com/kietoichoiDXD/BT---Laptrinhweb)

## 📄 License

MIT License - Tự do sử dụng cho mục đích học tập

## 🙏 Đóng góp

Mọi đóng góp đều được chào đón! Hãy tạo Pull Request hoặc Issue.

## 📞 Hỗ trợ

Nếu gặp vấn đề, vui lòng:
1. Kiểm tra file `CHECKLIST.md` để xem các tính năng đã implement
2. Xem file `CONDITIONAL_STYLING.md` để hiểu về tính năng urgent
3. Tạo Issue trên GitHub

---

**⭐ Đừng quên star repository nếu thấy hữu ích!**
