<?php

$date = $_GET['date'] ?? '';

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
        <p>タスク1</p>
        <p>タスク2</p>
    </div>

    <h2>スケジュール</h2>

    <div>
        <p>スケジュール1</p>
        <p>スケジュール2</p>
    </div>

    <a href="index.php">戻る</a>

    <a href="add.php?date=<?= urlencode($date) ?>">追加</a>

</body>
</html>