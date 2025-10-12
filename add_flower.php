<?php
// add_flower.php - tạo hoa mới (upload ảnh)
$dataPath = __DIR__ . '/data/flowers.json';

// Tạo file JSON nếu chưa tồn tại
if (!file_exists($dataPath)) {
    file_put_contents($dataPath, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

$flowers = json_decode(file_get_contents($dataPath), true);

// Thư mục upload ảnh
$uploadDir = __DIR__ . '/images/';

// Nếu chưa có folder images thì tự tạo
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $desc = trim($_POST['desc'] ?? '');
    $imageName = '';

    if ($name === '') {
        $errors[] = "Tên hoa không được để trống.";
    }

    // Xử lý upload ảnh
    if (!empty($_FILES['image']['name'])) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $fileName = $_FILES['image']['name'];
        $fileTmp = $_FILES['image']['tmp_name'];
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            $errors[] = "Chỉ chấp nhận file ảnh JPG, PNG, GIF hoặc WEBP.";
        } else {
            $newName = time() . "_" . preg_replace('/\s+/', '_', $fileName);
            $targetFile = $uploadDir . $newName;

            if (!move_uploaded_file($fileTmp, $targetFile)) {
                $errors[] = "Không thể upload ảnh. Kiểm tra quyền thư mục images.";
            } else {
                $imageName = $newName;
            }
        }
    } else {
        // Hoặc nhập tên ảnh có sẵn
        $imageName = trim($_POST['image_existing'] ?? '');
    }

    // Thêm vào JSON nếu không có lỗi
    if (empty($errors)) {
        $maxId = 0;
        foreach ($flowers as $f) {
            if ($f['id'] > $maxId) $maxId = $f['id'];
        }
        $newFlower = [
            'id' => $maxId + 1,
            'name' => $name,
            'desc' => $desc,
            'image' => $imageName
        ];
        $flowers[] = $newFlower;
        file_put_contents($dataPath, json_encode($flowers, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        header("Location: admin_flowers.php?msg=created");
        exit;
    }
}
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Thêm hoa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h2>Thêm hoa mới</h2>
    <a href="admin_flowers.php" class="btn btn-secondary mb-3">← Quay lại</a>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif ?>

    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Tên hoa</label>
            <input type="text" name="name" class="form-control" placeholder="Nhập tên hoa" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="desc" class="form-control" rows="3" placeholder="Nhập mô tả"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Upload ảnh (tùy chọn)</label>
            <input type="file" name="image" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Hoặc nhập tên file ảnh có sẵn trong thư mục images</label>
            <input type="text" name="image_existing" class="form-control" placeholder="VD: hong.jpg">
        </div>
        <button type="submit" class="btn btn-primary">Thêm hoa</button>
    </form>
</div>
</body>
</html>
