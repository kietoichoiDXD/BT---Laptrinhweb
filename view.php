<?php
session_start();
require_once 'config.php';

// Kết nối database
$conn = getDBConnection();

// Lấy tất cả feedback
$sql = "SELECT * FROM feedbacks ORDER BY created_at DESC";
$result = $conn->query($sql);

$pageTitle = 'Xem Tất Cả Feedback';
require_once 'components/header.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Feedback</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-4">
                        <h1 class="text-center mb-4">
                            <i class="bi bi-list-check text-primary"></i> Danh Sách Feedback
                        </h1>
                        
                        <div class="actions text-center mb-4">
                            <a href="index.php" class="btn btn-primary btn-lg">
                                <i class="bi bi-plus-circle-fill"></i> Gửi Feedback Mới
                            </a>
                        </div>
                        
                        <?php if ($result && $result->num_rows > 0): ?>
                            <div class="feedback-list">
                                <?php while ($row = $result->fetch_assoc()): 
                                    // Kiểm tra xem tiêu đề có chứa từ "urgent" không (không phân biệt hoa thường)
                                    $isUrgent = stripos($row['subject'], 'urgent') !== false;
                                    
                                    // Thiết lập class CSS dựa trên điều kiện
                                    $cardClass = $isUrgent 
                                        ? 'card mb-3 feedback-item border-start border-danger border-5 bg-warning bg-opacity-10 urgent-feedback' 
                                        : 'card mb-3 feedback-item border-start border-primary border-4';
                                ?>
                                    <div class="<?php echo $cardClass; ?>">
                                        <div class="card-body">
                                            <?php if ($isUrgent): ?>
                                                <div class="alert alert-danger d-inline-flex align-items-center mb-2 py-1 px-2">
                                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                                    <strong>URGENT</strong>
                                                </div>
                                            <?php endif; ?>
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <h5 class="card-title mb-0 <?php echo $isUrgent ? 'text-danger fw-bold' : ''; ?>">
                                                    <i class="bi bi-chat-quote-fill <?php echo $isUrgent ? 'text-danger' : 'text-primary'; ?>"></i> 
                                                    <?php echo htmlspecialchars($row['subject']); ?>
                                                </h5>
                                                <span class="rating fs-5">
                                                    <?php 
                                                        for ($i = 1; $i <= 5; $i++) {
                                                            echo $i <= $row['rating'] ? '⭐' : '☆';
                                                        }
                                                    ?>
                                                </span>
                                            </div>
                                            
                                            <div class="feedback-meta mb-3">
                                                <span class="badge bg-secondary me-2">
                                                    <i class="bi bi-person-fill"></i> 
                                                    Gửi bởi: <?php echo htmlspecialchars($row['name']); ?>
                                                    <?php if (!empty($row['student_id'])): ?>
                                                        (<?php echo htmlspecialchars($row['student_id']); ?>)
                                                    <?php endif; ?>
                                                </span>
                                                <span class="badge bg-info me-2">
                                                    <i class="bi bi-envelope-fill"></i> <?php echo htmlspecialchars($row['email']); ?>
                                                </span>
                                                <span class="badge bg-dark">
                                                    <i class="bi bi-calendar-fill"></i> <?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?>
                                                </span>
                                            </div>
                                            
                                            <div class="card bg-light p-3 mb-3">
                                                <p class="card-text mb-0"><?php echo nl2br(htmlspecialchars($row['message'])); ?></p>
                                            </div>
                                            
                                            <div class="d-flex justify-content-between align-items-center">
                                                <a href="feedback_detail.php?id=<?php echo $row['id']; ?>" 
                                                   class="btn btn-primary btn-sm">
                                                    <i class="bi bi-eye-fill"></i> Xem chi tiết
                                                </a>
                                                <form method="POST" action="delete.php" class="d-inline"
                                                      onsubmit="return confirm('Bạn có chắc muốn xóa feedback này?');">
                                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                    <input type="hidden" name="from" value="view">
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="bi bi-trash-fill"></i> Xóa
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info text-center" role="alert">
                                <i class="bi bi-info-circle-fill fs-1 d-block mb-3"></i>
                                <h4>Chưa có feedback nào</h4>
                                <p class="mb-0">
                                    <a href="index.php" class="alert-link">Gửi feedback đầu tiên</a>!
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php 
$conn->close();
require_once 'components/footer.php'; 
?>
