<?php
require_once 'config/database.php';

// Kiểm tra ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: view.php');
    exit;
}

$id = intval($_GET['id']);

// Kết nối database
$conn = getDBConnection();

// Lấy feedback theo ID
$sql = "SELECT * FROM feedbacks WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: view.php');
    exit;
}

$feedback = $result->fetch_assoc();
$stmt->close();
$conn->close();

// Kiểm tra xem tiêu đề có chứa từ "urgent" không
$isUrgent = stripos($feedback['subject'], 'urgent') !== false;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Feedback - <?php echo htmlspecialchars($feedback['subject']); ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0 <?php echo $isUrgent ? 'border-danger border-5 urgent-feedback' : ''; ?>">
                    <div class="card-header <?php echo $isUrgent ? 'bg-danger' : 'bg-primary'; ?> text-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">
                                <?php if ($isUrgent): ?>
                                    <i class="bi bi-exclamation-triangle-fill"></i> Chi Tiết Feedback URGENT
                                <?php else: ?>
                                    <i class="bi bi-eye-fill"></i> Chi Tiết Feedback
                                <?php endif; ?>
                            </h4>
                            <a href="view.php" class="btn btn-light btn-sm">
                                <i class="bi bi-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-body p-4 <?php echo $isUrgent ? 'bg-warning bg-opacity-10' : ''; ?>">
                        <!-- Urgent Badge -->
                        <?php if ($isUrgent): ?>
                            <div class="alert alert-danger mb-4" role="alert">
                                <h5 class="alert-heading">
                                    <i class="bi bi-exclamation-triangle-fill"></i> FEEDBACK KHẨN CẤP - URGENT
                                </h5>
                                <p class="mb-0">Feedback này được đánh dấu là khẩn cấp và cần được xử lý ưu tiên!</p>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Tiêu đề và Đánh giá -->
                        <div class="mb-4">
                            <h2 class="<?php echo $isUrgent ? 'text-danger' : 'text-primary'; ?> mb-3">
                                <i class="bi bi-chat-quote-fill"></i> 
                                <?php echo htmlspecialchars($feedback['subject']); ?>
                            </h2>
                            <div class="d-flex align-items-center">
                                <span class="me-2 fw-bold">Đánh giá:</span>
                                <span class="fs-4">
                                    <?php 
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $feedback['rating']) {
                                                echo '<i class="bi bi-star-fill text-warning"></i>';
                                            } else {
                                                echo '<i class="bi bi-star text-muted"></i>';
                                            }
                                        }
                                    ?>
                                </span>
                                <span class="ms-2 badge bg-warning text-dark">
                                    <?php echo $feedback['rating']; ?>/5
                                </span>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <!-- Thông tin người gửi -->
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="text-muted mb-2">
                                            <i class="bi bi-person-fill"></i> Người gửi
                                        </h6>
                                        <p class="mb-0 fs-5 fw-bold text-dark">
                                            <?php echo htmlspecialchars($feedback['name']); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="text-muted mb-2">
                                            <i class="bi bi-envelope-fill"></i> Email
                                        </h6>
                                        <p class="mb-0 fs-5 fw-bold text-dark">
                                            <a href="mailto:<?php echo htmlspecialchars($feedback['email']); ?>" class="text-decoration-none">
                                                <?php echo htmlspecialchars($feedback['email']); ?>
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="text-muted mb-2">
                                            <i class="bi bi-calendar-fill"></i> Ngày gửi
                                        </h6>
                                        <p class="mb-0 fs-5 fw-bold text-dark">
                                            <?php echo date('d/m/Y', strtotime($feedback['created_at'])); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="text-muted mb-2">
                                            <i class="bi bi-clock-fill"></i> Thời gian
                                        </h6>
                                        <p class="mb-0 fs-5 fw-bold text-dark">
                                            <?php echo date('H:i:s', strtotime($feedback['created_at'])); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <!-- Nội dung Feedback -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="bi bi-chat-dots-fill text-primary"></i> Nội dung Feedback
                            </h5>
                            <div class="card border-primary">
                                <div class="card-body bg-light">
                                    <p class="mb-0 fs-6 lh-lg" style="white-space: pre-wrap;">
                                        <?php echo htmlspecialchars($feedback['message']); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <!-- Thống kê -->
                        <div class="mb-4">
                            <h6 class="text-muted mb-3">
                                <i class="bi bi-info-circle-fill"></i> Thông tin bổ sung
                            </h6>
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="p-3 bg-light rounded">
                                        <i class="bi bi-hash fs-3 text-primary"></i>
                                        <p class="mb-0 mt-2 fw-bold">ID: <?php echo $feedback['id']; ?></p>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-3 bg-light rounded">
                                        <i class="bi bi-file-text fs-3 text-success"></i>
                                        <p class="mb-0 mt-2 fw-bold"><?php echo str_word_count($feedback['message']); ?> từ</p>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-3 bg-light rounded">
                                        <i class="bi bi-justify fs-3 text-info"></i>
                                        <p class="mb-0 mt-2 fw-bold"><?php echo strlen($feedback['message']); ?> ký tự</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Nút hành động -->
                        <div class="d-flex gap-2 justify-content-center mt-4">
                            <a href="view.php" class="btn btn-secondary btn-lg">
                                <i class="bi bi-list-ul"></i> Danh sách Feedback
                            </a>
                            <a href="index.php" class="btn btn-primary btn-lg">
                                <i class="bi bi-plus-circle-fill"></i> Gửi Feedback Mới
                            </a>
                            <a href="delete.php?id=<?php echo $feedback['id']; ?>" 
                               class="btn btn-danger btn-lg"
                               onclick="return confirm('Bạn có chắc muốn xóa feedback này?')">
                                <i class="bi bi-trash-fill"></i> Xóa
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-footer text-muted text-center">
                        <small>
                            <i class="bi bi-clock-history"></i> 
                            Feedback được tạo vào <?php echo date('d/m/Y lúc H:i:s', strtotime($feedback['created_at'])); ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
