<?php
// Cấu hình kết nối database
define('DB_HOST', 'localhost');
define('DB_PORT', 3307);
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'feedback_db');

// Tạo kết nối
function getDBConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
    
    // Kiểm tra kết nối
    if ($conn->connect_error) {
        // Nếu lỗi là database không tồn tại, hướng dẫn user
        if ($conn->connect_errno === 1049) {
            die("
                <div style='font-family: Arial; padding: 20px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px; margin: 20px;'>
                    <h2>❌ Lỗi: Database chưa được tạo!</h2>
                    <p><strong>Database '" . DB_NAME . "' không tồn tại.</strong></p>
                    <p>Vui lòng chạy file setup để tạo database:</p>
                    <p><a href='setup_database.php' style='display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;'>
                        🔧 Chạy Setup Database
                    </a></p>
                    <hr>
                    <p><strong>Hoặc tạo thủ công:</strong></p>
                    <ol>
                        <li>Mở phpMyAdmin hoặc MySQL client</li>
                        <li>Import file: <code>database/setup.sql</code></li>
                        <li>Hoặc chạy lệnh: <code>mysql -u root -p &lt; database/setup.sql</code></li>
                    </ol>
                </div>
            ");
        }
        die("Kết nối thất bại: " . $conn->connect_error);
    }
    
    // Set charset UTF-8
    $conn->set_charset("utf8mb4");
    
    return $conn;
}
?>
