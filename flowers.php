<?php
$path = __DIR__ . '/data/flowers.json';
$flowers = [];
if (file_exists($path)) {
    $json = file_get_contents($path);
    $flowers = json_decode($json, true) ?: [];
}
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Danh sách hoa</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-white">
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Danh sách hoa</h1>
    <a class="btn btn-outline-secondary" href="index.php">← Back</a>
  </div>

  <div class="row">
    <?php if (empty($flowers)): ?>
      <div class="alert alert-warning">Chưa có hoa nào. Vào <a href="admin_flowers.php">Admin</a> để thêm.</div>
    <?php endif; ?>

    <?php foreach ($flowers as $f): ?>
      <div class="col-md-4 mb-4">
        <div class="card h-100">
          <?php
            $img = 'images/' . ($f['image'] ?: 'placeholder.png');
            if (!file_exists(__DIR__ . '/' . $img)) $img = 'https://via.placeholder.com/600x400?text=No+Image';
          ?>
          <img src="<?php echo $img; ?>" class="card-img-top" style="height:220px; object-fit:cover;">
          <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($f['name']); ?></h5>
            <p class="card-text"><?php echo htmlspecialchars($f['desc']); ?></p>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
</body>
</html>
