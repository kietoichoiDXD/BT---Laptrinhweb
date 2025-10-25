<?php
// File này sẽ tạo database và bảng tự động
define('DB_HOST', 'localhost');
define('DB_PORT', 3307);
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'feedback_db');

echo "<h2>Đang thiết lập Database...</h2>";

// Bước 1: Kết nối MySQL mà không chọn database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, '', DB_PORT);

if ($conn->connect_error) {
    die("<p style='color: red;'>❌ Lỗi kết nối MySQL: " . $conn->connect_error . "</p>
         <p><strong>Nguyên nhân có thể:</strong></p>
         <ul>
            <li>MySQL không chạy trên port 3307</li>
            <li>Username/Password không đúng</li>
            <li>Cần khởi động MySQL/XAMPP/WAMP</li>
         </ul>
         <p><a href='index.php'>Quay lại</a></p>");
}

echo "<p style='color: green;'>✅ Kết nối MySQL thành công!</p>";

// Bước 2: Tạo database nếu chưa tồn tại
$sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if ($conn->query($sql) === TRUE) {
    echo "<p style='color: green;'>✅ Database '" . DB_NAME . "' đã được tạo hoặc đã tồn tại!</p>";
} else {
    die("<p style='color: red;'>❌ Lỗi tạo database: " . $conn->error . "</p>");
}

// Bước 3: Chọn database
$conn->select_db(DB_NAME);

// Bước 4: Tạo bảng feedbacks
$sql = "CREATE TABLE IF NOT EXISTS feedbacks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    rating INT DEFAULT 5,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($conn->query($sql) === TRUE) {
    echo "<p style='color: green;'>✅ Bảng 'feedbacks' đã được tạo hoặc đã tồn tại!</p>";
} else {
    die("<p style='color: red;'>❌ Lỗi tạo bảng: " . $conn->error . "</p>");
}

// Bước 5: Kiểm tra số lượng feedback
$result = $conn->query("SELECT COUNT(*) as total FROM feedbacks");
$row = $result->fetch_assoc();

echo "<p style='color: blue;'>📊 Hiện có <strong>" . $row['total'] . "</strong> feedback trong database.</p>";

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Database Thành Công</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-5 text-center">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 80px;"></i>
                        <h1 class="mt-4 mb-4">🎉 Setup Hoàn Tất!</h1>
                        <p class="lead">Database và bảng đã được tạo thành công!</p>
                        
                        <div class="alert alert-info mt-4">
                            <h5><i class="bi bi-info-circle-fill"></i> Thông tin Database:</h5>
                            <ul class="list-unstyled mt-3">
                                <li><strong>Host:</strong> <?php echo DB_HOST; ?></li>
                                <li><strong>Port:</strong> <?php echo DB_PORT; ?></li>
                                <li><strong>Database:</strong> <?php echo DB_NAME; ?></li>
                                <li><strong>User:</strong> <?php echo DB_USER; ?></li>
                            </ul>
                        </div>
                        
                        <div class="d-grid gap-3 mt-4">
                            <a href="index.php" class="btn btn-primary btn-lg">
                                <i class="bi bi-house-fill"></i> Về Trang Chủ
                            </a>
                            <a href="view.php" class="btn btn-secondary btn-lg">
                                <i class="bi bi-list-ul"></i> Xem Danh Sách Feedback
                            </a>
                        </div>
                        
                        <div class="mt-4">
                            <small class="text-muted">
                                Bạn có thể chạy lại file này bất cứ lúc nào để kiểm tra database.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
