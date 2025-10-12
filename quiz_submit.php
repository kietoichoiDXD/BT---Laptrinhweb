<?php
$filepath = __DIR__ . "/data/questions.txt";
if (!file_exists($filepath)) {
    die("Không tìm thấy file questions.txt");
}

$content = trim(file_get_contents($filepath));
$blocks = preg_split("/\r?\n\r?\n/", $content);

$correctAnswers = [];
foreach ($blocks as $block) {
    foreach (explode("\n", trim($block)) as $line) {
        if (stripos($line, "Answer:") !== false) {
            $correctAnswers[] = trim(substr($line, 7));
        }
    }
}

$userAnswers = $_POST["answer"] ?? [];

$score = 0;
foreach ($correctAnswers as $i => $cAns) {
    if (isset($userAnswers[$i]) && strtoupper($userAnswers[$i]) == strtoupper($cAns)) {
        $score++;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Kết quả bài thi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
    <h2>Kết quả bài làm</h2>
    <div class="alert alert-info">
        Bạn đúng <strong><?= $score ?></strong> / <?= count($correctAnswers) ?> câu.
    </div>
    <a href="quiz.php" class="btn btn-secondary">Làm lại</a>
</div>

</body>
</html>
