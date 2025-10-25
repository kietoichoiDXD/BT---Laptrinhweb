<?php
// File cấu hình kết nối database
define('DB_HOST', 'localhost');
define('DB_PORT', 3307);
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'feedback_db');

// MSSV của sinh viên - Thay đổi theo MSSV của bạn
define('STUDENT_ID', 'B20DCCN001');

// Số feedback hiển thị trên mỗi trang (cho phân trang)
define('ITEMS_PER_PAGE', 5);

// Tạo kết nối PDO
function getDBConnectionPDO() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;
    } catch (PDOException $e) {
        die("
            <div style='font-family: Arial; padding: 20px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px; margin: 20px;'>
                <h2>❌ Lỗi kết nối Database!</h2>
                <p><strong>Không thể kết nối đến MySQL.</strong></p>
                <p>Chi tiết lỗi: " . $e->getMessage() . "</p>
                <p><a href='setup_database.php' style='display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;'>
                    🔧 Chạy Setup Database
                </a></p>
            </div>
        ");
    }
}

// Tạo kết nối MySQLi (giữ lại cho các file cũ)
function getDBConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
    
    if ($conn->connect_error) {
        if ($conn->connect_errno === 1049) {
            die("
                <div style='font-family: Arial; padding: 20px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px; margin: 20px;'>
                    <h2>❌ Lỗi: Database chưa được tạo!</h2>
                    <p><strong>Database '" . DB_NAME . "' không tồn tại.</strong></p>
                    <p><a href='setup_database.php' style='display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;'>
                        🔧 Chạy Setup Database
                    </a></p>
                </div>
            ");
        }
        die("Kết nối thất bại: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8mb4");
    return $conn;
}

// Hàm làm sạch dữ liệu đầu vào
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Hàm validate email
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Hàm kiểm tra feedback có urgent không
function is_urgent($title) {
    return stripos($title, 'urgent') !== false;
}
?>
