<?php
session_start();
require_once 'config.php';

// Lấy trang hiện tại từ URL
$current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

// Tính offset
$offset = ($current_page - 1) * ITEMS_PER_PAGE;

// Kết nối database
$pdo = getDBConnectionPDO();

// Đếm tổng số feedback
$count_sql = "SELECT COUNT(*) as total FROM feedbacks";
$count_stmt = $pdo->query($count_sql);
$total_feedbacks = $count_stmt->fetch()['total'];

// Tính tổng số trang
$total_pages = ceil($total_feedbacks / ITEMS_PER_PAGE);

// Lấy feedback với phân trang
$sql = "SELECT * FROM feedbacks ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':limit', ITEMS_PER_PAGE, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$feedbacks = $stmt->fetchAll();

$pageTitle = 'Danh Sách Feedback';
require_once 'components/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">
                    <h1 class="text-center mb-4">
                        <i class="bi bi-list-check text-primary"></i> Danh Sách Feedback
                        <small class="text-muted fs-6 d-block mt-2">
                            (Trang <?php echo $current_page; ?> / <?php echo max(1, $total_pages); ?>)
                        </small>
                    </h1>
                    
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill"></i>
                            <?php 
                                echo $_SESSION['success']; 
                                unset($_SESSION['success']);
                            ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <div class="actions text-center mb-4">
                        <a href="submit.php" class="btn btn-primary btn-lg">
                            <i class="bi bi-plus-circle-fill"></i> Gửi Feedback Mới
                        </a>
                        <a href="view.php" class="btn btn-secondary btn-lg">
                            <i class="bi bi-eye-fill"></i> Xem Tất Cả (Không Phân Trang)
                        </a>
                    </div>
                    
                    <?php if (count($feedbacks) > 0): ?>
                        <!-- Thông tin tổng quan -->
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle-fill"></i>
                            Tổng số feedback: <strong><?php echo $total_feedbacks; ?></strong> |
                            Hiển thị: <strong><?php echo count($feedbacks); ?></strong> feedback trên trang này
                        </div>
                        
                        <div class="feedback-list">
                            <?php foreach ($feedbacks as $row): 
                                // Kiểm tra xem tiêu đề có chứa từ "urgent" không
                                $isUrgent = is_urgent($row['subject']);
                                
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
                                                        echo $i <= $row['rating'] ? '<i class="bi bi-star-fill text-warning"></i>' : '<i class="bi bi-star text-muted"></i>';
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
                                            <p class="card-text mb-0">
                                                <?php 
                                                    $content = htmlspecialchars($row['message']);
                                                    echo strlen($content) > 200 ? substr($content, 0, 200) . '...' : $content;
                                                ?>
                                            </p>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between align-items-center">
                                            <a href="feedback_detail.php?id=<?php echo $row['id']; ?>" 
                                               class="btn btn-primary btn-sm">
                                                <i class="bi bi-eye-fill"></i> Xem chi tiết
                                            </a>
                                            <form method="POST" action="delete.php" class="d-inline" 
                                                  onsubmit="return confirm('Bạn có chắc muốn xóa feedback này?');">
                                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                <input type="hidden" name="page" value="<?php echo $current_page; ?>">
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="bi bi-trash-fill"></i> Xóa
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <!-- Phân trang -->
                        <?php if ($total_pages > 1): ?>
                            <nav aria-label="Pagination">
                                <ul class="pagination justify-content-center mt-4">
                                    <!-- Nút Previous -->
                                    <li class="page-item <?php echo $current_page == 1 ? 'disabled' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $current_page - 1; ?>">
                                            <i class="bi bi-chevron-left"></i> Trước
                                        </a>
                                    </li>
                                    
                                    <!-- Các số trang -->
                                    <?php
                                        $start_page = max(1, $current_page - 2);
                                        $end_page = min($total_pages, $current_page + 2);
                                        
                                        if ($start_page > 1) {
                                            echo '<li class="page-item"><a class="page-link" href="?page=1">1</a></li>';
                                            if ($start_page > 2) {
                                                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                            }
                                        }
                                        
                                        for ($i = $start_page; $i <= $end_page; $i++):
                                    ?>
                                        <li class="page-item <?php echo $i == $current_page ? 'active' : ''; ?>">
                                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                        </li>
                                    <?php 
                                        endfor;
                                        
                                        if ($end_page < $total_pages) {
                                            if ($end_page < $total_pages - 1) {
                                                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                            }
                                            echo '<li class="page-item"><a class="page-link" href="?page=' . $total_pages . '">' . $total_pages . '</a></li>';
                                        }
                                    ?>
                                    
                                    <!-- Nút Next -->
                                    <li class="page-item <?php echo $current_page == $total_pages ? 'disabled' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $current_page + 1; ?>">
                                            Tiếp <i class="bi bi-chevron-right"></i>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        <?php endif; ?>
                        
                    <?php else: ?>
                        <div class="alert alert-info text-center" role="alert">
                            <i class="bi bi-info-circle-fill fs-1 d-block mb-3"></i>
                            <h4>Chưa có feedback nào</h4>
                            <p class="mb-0">
                                <a href="submit.php" class="alert-link">Gửi feedback đầu tiên</a>!
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'components/footer.php'; ?>
