<?php
session_start();
require_once 'config.php';

// Xử lý form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    
    // Lấy và làm sạch dữ liệu
    $title = clean_input($_POST['title'] ?? '');
    $content = clean_input($_POST['content'] ?? '');
    $name = clean_input($_POST['name'] ?? '');
    $email = clean_input($_POST['email'] ?? '');
    $rating = intval($_POST['rating'] ?? 5);
    $student_id = clean_input($_POST['student_id'] ?? STUDENT_ID);
    
    // Validate dữ liệu
    if (empty($title)) {
        $errors[] = "Vui lòng nhập tiêu đề";
    }
    
    if (empty($content)) {
        $errors[] = "Vui lòng nhập nội dung";
    }
    
    if (empty($name)) {
        $errors[] = "Vui lòng nhập tên người gửi";
    }
    
    if (empty($email)) {
        $errors[] = "Vui lòng nhập email";
    } elseif (!validate_email($email)) {
        $errors[] = "Email không hợp lệ";
    }
    
    if ($rating < 1 || $rating > 5) {
        $errors[] = "Đánh giá phải từ 1 đến 5";
    }
    
    // Nếu không có lỗi, lưu vào database
    if (empty($errors)) {
        try {
            $pdo = getDBConnectionPDO();
            
            $sql = "INSERT INTO feedbacks (subject, message, name, email, rating, student_id) 
                    VALUES (:title, :content, :name, :email, :rating, :student_id)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':title' => $title,
                ':content' => $content,
                ':name' => $name,
                ':email' => $email,
                ':rating' => $rating,
                ':student_id' => $student_id
            ]);
            
            $_SESSION['success'] = "Gửi feedback thành công! Cảm ơn bạn đã đóng góp ý kiến.";
            header('Location: index.php');
            exit;
            
        } catch (PDOException $e) {
            $errors[] = "Lỗi khi lưu feedback: " . $e->getMessage();
        }
    }
    
    // Nếu có lỗi, lưu vào session
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
    }
}

// Lấy dữ liệu cũ nếu có lỗi
$old_data = $_SESSION['form_data'] ?? [];
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['form_data'], $_SESSION['errors']);

$pageTitle = 'Gửi Feedback';
require_once 'components/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">
                    <h1 class="text-center mb-4">
                        <i class="bi bi-chat-left-text-fill text-primary"></i> Gửi Feedback Của Bạn
                    </h1>
                    
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h5 class="alert-heading">
                                <i class="bi bi-exclamation-triangle-fill"></i> Có lỗi xảy ra!
                            </h5>
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="submit.php" class="needs-validation" novalidate>
                        <!-- Trường ẩn MSSV -->
                        <input type="hidden" name="student_id" value="<?php echo STUDENT_ID; ?>">
                        
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">
                                <i class="bi bi-person-fill"></i> Tên người gửi <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?php echo htmlspecialchars($old_data['name'] ?? ''); ?>"
                                   required placeholder="Nhập họ tên của bạn">
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">
                                <i class="bi bi-envelope-fill"></i> Email <span class="text-danger">*</span>
                            </label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo htmlspecialchars($old_data['email'] ?? ''); ?>"
                                   required placeholder="email@example.com">
                        </div>
                        
                        <div class="mb-3">
                            <label for="title" class="form-label fw-bold">
                                <i class="bi bi-pencil-fill"></i> Tiêu đề <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="title" name="title" 
                                   value="<?php echo htmlspecialchars($old_data['title'] ?? ''); ?>"
                                   required placeholder="Tiêu đề feedback (Thêm 'urgent' nếu khẩn cấp)">
                            <div class="form-text">
                                <i class="bi bi-info-circle"></i> Thêm từ khóa <strong>"urgent"</strong> vào tiêu đề nếu feedback cần xử lý khẩn cấp.
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="rating" class="form-label fw-bold">
                                <i class="bi bi-star-fill"></i> Đánh giá <span class="text-danger">*</span>
                            </label>
                            <select id="rating" name="rating" class="form-select" required>
                                <option value="">Chọn mức đánh giá</option>
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <option value="<?php echo $i; ?>" 
                                            <?php echo (isset($old_data['rating']) && $old_data['rating'] == $i) || (!isset($old_data['rating']) && $i == 5) ? 'selected' : ''; ?>>
                                        <?php echo str_repeat('⭐', $i); ?> <?php echo $i; ?> - 
                                        <?php 
                                            $labels = [1 => 'Rất tệ', 2 => 'Tệ', 3 => 'Bình thường', 4 => 'Tốt', 5 => 'Rất tốt'];
                                            echo $labels[$i];
                                        ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label for="content" class="form-label fw-bold">
                                <i class="bi bi-chat-dots-fill"></i> Nội dung <span class="text-danger">*</span>
                            </label>
                            <textarea id="content" name="content" rows="5" class="form-control" 
                                      required placeholder="Nhập nội dung feedback của bạn..."><?php echo htmlspecialchars($old_data['content'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="bi bi-person-badge"></i> MSSV: <strong><?php echo STUDENT_ID; ?></strong>
                            <small class="d-block mt-1">Mã số sinh viên sẽ được lưu cùng feedback này</small>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="bi bi-send-fill"></i> Gửi Feedback
                            </button>
                            <a href="index.php" class="btn btn-secondary btn-lg px-5">
                                <i class="bi bi-list-ul"></i> Xem Danh Sách
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'components/footer.php'; ?>
