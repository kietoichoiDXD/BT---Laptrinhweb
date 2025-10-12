<?php
// edit_flower.php
$dataPath = __DIR__ . '/data/flowers.json';
if (!file_exists($dataPath)) {
    die('File dữ liệu không tồn tại.');
}
$flowers = json_decode(file_get_contents($dataPath), true) ?: [];

$id = intval($_GET['id'] ?? 0);
$found = null;
foreach ($flowers as $k => $f) {
    if (intval($f['id']) === $id) { $found = ['index'=>$k,'data'=>$f]; break; }
}
if (!$found) {
    die('Không tìm thấy hoa.');
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $desc = trim($_POST['desc'] ?? '');
    $imageName = $found['data']['image'] ?? '';

    if ($name === '') $errors[] = "Tên hoa không được để trống.";

    // Upload mới nếu có
    if (!empty($_FILES['image']['name'])) {
        $allowed = ['jpg','jpeg','png','gif','webp'];
        $fn = $_FILES['image']['name'];
        $ext = strtolower(pathinfo($fn, PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $errors[] = "Định dạng ảnh không hợp lệ.";
        } else {
            $newName = time() . '_' . preg_replace('/\s+/', '_', basename($fn));
            $target = __DIR__ . '/images/' . $newName;
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $errors[] = "Upload ảnh thất bại.";
            } else {
                // optionally xóa ảnh cũ (không ép buộc)
                if (!empty($imageName) && file_exists(__DIR__ . '/images/' . $imageName)) {
                    // unlink(__DIR__ . '/images/' . $imageName);
                }
                $imageName = $newName;
            }
        }
    } else {
        // nếu sửa tên ảnh
        $imageName = trim($_POST['image_existing'] ?? $imageName);
    }

    if (empty($errors)) {
        $flowers[$found['index']]['name'] = $name;
        $flowers[$found['index']]['desc'] = $desc;
        $flowers[$found['index']]['image'] = $imageName;
        file_put_contents($dataPath, json_encode($flowers, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        header("Location: admin_flowers.php?msg=updated");
        exit;
    }
}
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Sửa hoa</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
  <h1>Sửa hoa</h1>
  <a href="admin_flowers.php" class="btn btn-outline-secondary mb-3">← Back</a>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-danger"><ul><?php foreach ($errors as $e) echo "<li>" . htmlspecialchars($e) . "</li>"; ?></ul></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label">Tên hoa</label>
      <input type="text" name="name" class="form-control" required value="<?php echo htmlspecialchars($found['data']['name']); ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Mô tả</label>
      <textarea name="desc" class="form-control" rows="3"><?php echo htmlspecialchars($found['data']['desc']); ?></textarea>
    </div>
    <div class="mb-3">
      <label class="form-label">Ảnh hiện tại</label>
      <div>
        <?php $cur = 'images/' . ($found['data']['image'] ?: ''); ?>
        <?php if ($found['data']['image'] && file_exists(__DIR__ . '/' . $cur)): ?>
          <img src="<?php echo $cur; ?>" style="height:120px;object-fit:cover;">
        <?php else: ?>
          <div class="text-muted">Không có ảnh</div>
        <?php endif; ?>
      </div>
    </div>
    <div class="mb-3">
      <label class="form-label">Upload ảnh mới (tùy chọn)</label>
      <input type="file" name="image" class="form-control">
    </div>
    <div class="mb-3">
      <label class="form-label">Hoặc nhập tên file ảnh có sẵn</label>
      <input type="text" name="image_existing" class="form-control" value="<?php echo htmlspecialchars($found['data']['image']); ?>">
    </div>
    <button class="btn btn-primary">Lưu thay đổi</button>
  </form>
</div>
</body>
</html>
