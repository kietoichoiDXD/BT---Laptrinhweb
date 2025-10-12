<?php
// admin_flowers.php - trang quản lý hiển thị danh sách hoa
$path = __DIR__ . '/data/flowers.json';
$flowers = [];
if (file_exists($path)) {
    $json = file_get_contents($path);
    // Đọc JSON, nếu lỗi thì gán mảng rỗng
    $flowers = json_decode($json, true) ?: [];
}
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Admin - Danh sách hoa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Quản lý Hoa</h1>
        <div class="d-flex">
            <a class="btn btn-primary me-2" href="add_flower.php">Thêm hoa mới</a>
            <a class="btn btn-outline-secondary" href="flowers.php">Xem trang khách</a>
        </div>
    </div>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
        <div class="alert alert-success">Đã xóa hoa thành công!</div>
    <?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'created'): ?>
        <div class="alert alert-success">Đã thêm hoa thành công!</div>
    <?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'updated'): ?>
        <div class="alert alert-success">Đã cập nhật hoa thành công!</div>
    <?php endif; ?>

    <?php if (empty($flowers)): ?>
        <div class="alert alert-warning">Chưa có hoa nào. Vui lòng <a href="add_flower.php">thêm</a>.</div>
    <?php else: ?>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th> 
                <th>Ảnh</th>
                <th>Tên</th>
                <th>Mô tả</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php $counter = 1; ?> 
            <?php foreach ($flowers as $f): ?>
                <tr>
                    <td><?php echo $counter++; ?></td> <td>
                        <?php 
                            $img = 'images/' . ($f['image'] ?? 'placeholder.png');
                            if (!file_exists(__DIR__ . '/' . $img)) $img = 'https://via.placeholder.com/50x50?text=No+Image';
                        ?>
                        <img src="<?php echo $img; ?>" style="height:50px;width:50px;object-fit:cover;">
                    </td>
                    <td><?php echo htmlspecialchars($f['name']); ?></td>
                    <td><?php echo htmlspecialchars($f['desc']); ?></td>
                    <td>
                        <a class="btn btn-warning btn-sm" href="edit_flower.php?id=<?php echo htmlspecialchars($f['id']); ?>">Sửa</a>
                        <a class="btn btn-danger btn-sm" href="delete_flower.php?id=<?php echo htmlspecialchars($f['id']); ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa hoa ID <?php echo htmlspecialchars($f['id']); ?> không?')">Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>
</body>
</html>