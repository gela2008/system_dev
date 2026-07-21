<?php

session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? '';

include "db.php";

$stmt = $db->prepare(
    "SELECT *
     FROM todo
     WHERE id = ?
     AND student_id = ?"
);

$stmt->execute([
    $id,
    $_SESSION['student_id']
]);

$todo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$todo) {
    exit('データが見つかりません。');
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>詳細</title>
</head>

<body>

    <h1><?= htmlspecialchars($todo['title']) ?></h1>

    <?php if ($todo['subject'] !== null): ?>

        <p>種類：タスク</p>
        <p>科目：<?= htmlspecialchars($todo['subject']) ?></p>
        <p>締切：<?= htmlspecialchars($todo['date']) ?> <?= htmlspecialchars($todo['time']) ?></p>
        <p>着手予定日：<?= htmlspecialchars($todo['start_date']) ?></p>

    <?php else: ?>

        <p>種類：スケジュール</p>
        <p>日付：<?= htmlspecialchars($todo['date']) ?></p>
        <p>時間：<?= htmlspecialchars($todo['time']) ?></p>

    <?php endif; ?>

    <p>備考：</p>
    <p><?= nl2br(htmlspecialchars($todo['note'])) ?></p>

    <a href="date.php?date=<?= urlencode($todo['date']) ?>">
        一覧に戻る
    </a>

    <a href="edit.php?id=<?= $todo['id'] ?>">
        編集
    </a>

</body>
</html>