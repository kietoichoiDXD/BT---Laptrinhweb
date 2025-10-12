<?php
// Đường dẫn đến file CSV
$filePath = __DIR__ . "/data/accounts.csv";

// Kiểm tra file có tồn tại không
if (!file_exists($filePath)) {
    die("❌ Không tìm thấy file accounts.csv trong thư mục /data/");
}

$rows = [];
$header = [];

if (($handle = fopen($filePath, "r")) !== FALSE) {
    // Đọc dòng đầu tiên làm header
    $header = fgetcsv($handle);

    // Đọc các dòng tiếp theo
    while (($data = fgetcsv($handle)) !== FALSE) {
        if (count($data) == count($header)) {
            $rows[] = array_combine($header, $data);
        }
    }

    fclose($handle);
} else {
    die("❌ Không thể mở file accounts.csv");
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bài 3 - Đọc CSV bằng PHP thuần</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
    <h2 class="mb-3">Bài 3: Hiển thị danh sách tài khoản (CSV)</h2>
    <p class="text-muted">Dữ liệu được đọc từ file <code>data/accounts.csv</code></p>

    <?php if (empty($rows)): ?>
        <div class="alert alert-warning">Không có dữ liệu trong file CSV.</div>
    <?php else: ?>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <?php foreach ($header as $col): ?>
                        <th><?= htmlspecialchars($col) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <?php foreach ($header as $col): ?>
                            <td><?= htmlspecialchars($row[$col]) ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="index.php" class="btn btn-secondary mt-2">← Quay lại trang chính</a>
</div>

</body>
</html>
