<?php

session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

$date = $_GET['date'] ?? '';

include "db.php";

$stmt = $db->prepare(
    "SELECT *
     FROM todo
     WHERE student_id = ?
     AND date = ?
     ORDER BY time"
);

$stmt->execute([
    $_SESSION['student_id'],
    $date
]);

$todos = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>タスク一覧</title>

    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <h1><?= htmlspecialchars($date) ?></h1>

    <h2>タスク</h2>

    <div>

        <?php foreach ($todos as $todo): ?>

            <?php if ($todo['subject'] !== null): ?>

                <p>
                    <a href="detail.php?id=<?= $todo['id'] ?>">
                        <?= htmlspecialchars($todo['title']) ?>
                        （<?= htmlspecialchars($todo['subject']) ?>）
                    </a>
                </p>

            <?php endif; ?>

        <?php endforeach; ?>

    </div>

    <h2>スケジュール</h2>

    <div>

        <?php foreach ($todos as $todo): ?>

            <?php if ($todo['subject'] === null): ?>

                <p>
                    <a href="detail.php?id=<?= $todo['id'] ?>">
                        <?= htmlspecialchars($todo['title']) ?>
                    </a>
                </p>

            <?php endif; ?>

        <?php endforeach; ?>

    </div>

    <a href="index.php">戻る</a>

    <a href="add.php?date=<?= urlencode($date) ?>">追加</a>

</body>
</html>