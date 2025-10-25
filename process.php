<?php
session_start();
require_once 'config.php';

// Kiểm tra phương thức POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Lấy dữ liệu từ form
$name = clean_input($_POST['name'] ?? '');
$email = clean_input($_POST['email'] ?? '');
$subject = clean_input($_POST['subject'] ?? '');
$message = clean_input($_POST['message'] ?? '');
$rating = intval($_POST['rating'] ?? 5);
$student_id = STUDENT_ID; // Lấy từ config

// Validate dữ liệu
$errors = [];

if (empty($name)) {
    $errors[] = "Vui lòng nhập họ tên";
}

if (empty($email)) {
    $errors[] = "Vui lòng nhập email";
} elseif (!validate_email($email)) {
    $errors[] = "Email không hợp lệ";
}

if (empty($subject)) {
    $errors[] = "Vui lòng nhập tiêu đề";
}

if (empty($message)) {
    $errors[] = "Vui lòng nhập nội dung";
}

if ($rating < 1 || $rating > 5) {
    $errors[] = "Đánh giá phải từ 1 đến 5";
}

// Nếu có lỗi, quay lại trang form
if (!empty($errors)) {
    $_SESSION['error'] = implode('<br>', $errors);
    header('Location: index.php');
    exit;
}

// Kết nối database
try {
    $pdo = getDBConnectionPDO();
    
    // Chuẩn bị câu lệnh SQL
    $sql = "INSERT INTO feedbacks (name, email, subject, message, rating, student_id) VALUES (:name, :email, :subject, :message, :rating, :student_id)";
    $stmt = $pdo->prepare($sql);
    
    // Thực thi
    $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':subject' => $subject,
        ':message' => $message,
        ':rating' => $rating,
        ':student_id' => $student_id
    ]);
    
    $_SESSION['success'] = "Gửi feedback thành công! Cảm ơn bạn đã đóng góp ý kiến.";
    header('Location: index.php');
    
} catch (PDOException $e) {
    $_SESSION['error'] = "Lỗi khi lưu feedback: " . $e->getMessage();
    header('Location: index.php');
}

exit;
?>
