<?php
session_start();
require_once 'config.php';

// Kiểm tra phương thức POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Kiểm tra ID
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    $_SESSION['error'] = "ID không hợp lệ";
    header('Location: index.php');
    exit;
}

$id = intval($_POST['id']);
$page = isset($_POST['page']) ? intval($_POST['page']) : 1;

try {
    // Kết nối database bằng PDO
    $pdo = getDBConnectionPDO();
    
    // Xóa feedback
    $sql = "DELETE FROM feedbacks WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    
    if ($stmt->rowCount() > 0) {
        $_SESSION['success'] = "Đã xóa feedback thành công!";
    } else {
        $_SESSION['error'] = "Không tìm thấy feedback để xóa";
    }
    
} catch (PDOException $e) {
    $_SESSION['error'] = "Lỗi khi xóa feedback: " . $e->getMessage();
}

// Chuyển hướng về trang gốc (với phân trang nếu có)
header('Location: ' . (isset($_POST['from']) && $_POST['from'] == 'view' ? 'view.php' : 'index.php?page=' . $page));
exit;
?>
