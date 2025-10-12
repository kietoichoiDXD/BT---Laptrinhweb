<?php
$filepath = __DIR__ . "/data/questions.txt";
if (!file_exists($filepath)) {
    die("Không tìm thấy file questions.txt trong thư mục /data/");
}

$content = trim(file_get_contents($filepath));
$blocks = preg_split("/\r?\n\r?\n/", $content);

$questions = [];
foreach ($blocks as $block) {
    $lines = explode("\n", trim($block));
    $qText = "";
    $options = [];
    $answer = "";

    foreach ($lines as $line) {
        $line = trim($line);
        if (preg_match("/^Câu\s*\d+:/i", $line)) {
            $qText = preg_replace("/^Câu\s*\d+:\s*/i", "", $line);
        } elseif (preg_match("/^[A-D]\./", $line)) {
            $options[substr($line, 0, 1)] = substr($line, 3);
        } elseif (stripos($line, "Answer:") !== false) {
            $answer = trim(substr($line, 7));
        }
    }
    $questions[] = ["text" => $qText, "options" => $options, "answer" => $answer];
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bài 2 - Trắc nghiệm PHP thuần</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
    <h2 class="mb-3">BÀI 2: Thi Trắc Nghiệm (đọc từ file .txt)</h2>
    <form action="quiz_submit.php" method="POST">
        <?php foreach ($questions as $i => $q): ?>
            <div class="card p-3 mb-3 shadow-sm">
                <h5>Câu <?= $i + 1 ?>: <?= htmlspecialchars($q["text"]) ?></h5>
                <?php foreach ($q["options"] as $key => $opt): ?>
                    <div class="form-check">
                        <input type="radio" name="answer[<?= $i ?>]" value="<?= $key ?>" class="form-check-input" id="q<?= $i.$key ?>">
                        <label for="q<?= $i.$key ?>" class="form-check-label">
                            <?= $key ?>. <?= htmlspecialchars($opt) ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>

        <button type="submit" class="btn btn-primary">Nộp bài</button>
    </form>
</div>
<a href="index.php" class="btn btn-secondary mb-3">← Quay lại trang chính</a>


</body>
</html>
