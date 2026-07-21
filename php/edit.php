<?php

session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? '';

include "db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = $_POST['title'] ?? '';
    $time = $_POST['time'] ?? '';
    $start_date = $_POST['start_date'] ?? '';
    $note = $_POST['note'] ?? '';

    $stmt = $db->prepare(
        "UPDATE todo
         SET title = ?,
             time = ?,
             start_date = ?,
             note = ?
         WHERE id = ?
         AND student_id = ?"
    );

    $stmt->execute([
        $title,
        $time,
        $start_date,
        $note,
        $id,
        $_SESSION['student_id']
    ]);

    header("Location: detail.php?id=" . $id);
    exit;
}

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
    <title>編集</title>
</head>

<body>

    <h1>編集</h1>

    <form action="edit.php?id=<?= $todo['id'] ?>" method="post">

    <?php if ($todo['subject'] !== null): ?>

        <!-- タスクの編集 -->
        <h2>タスク</h2>

        <p>
            <label>
                タイトル：
                <input
                    type="text"
                    name="title"
                    value="<?= htmlspecialchars($todo['title']) ?>"
                >
            </label>
        </p>

        <p>
            <label>
                科目：
                <?= htmlspecialchars($todo['subject']) ?>
            </label>
        </p>

        <p>
            <label>
                締切：
                <input
                    type="time"
                    name="time"
                    value="<?= htmlspecialchars($todo['time']) ?>"
                >
            </label>
        </p>

        <p>
            <label>
                着手予定日：
                <input
                    type="date"
                    name="start_date"
                    value="<?= htmlspecialchars($todo['start_date']) ?>"
                >
            </label>
        </p>

        <p>
            <label>
                備考：
                <br>
                <textarea name="note"><?= htmlspecialchars($todo['note']) ?></textarea>
            </label>
        </p>

    <?php else: ?>

        <!-- スケジュールの編集 -->
        <h2>スケジュール</h2>

        <p>
            <label>
                タイトル：
                <input
                    type="text"
                    name="title"
                    value="<?= htmlspecialchars($todo['title']) ?>"
                >
            </label>
        </p>

        <p>
            <label>
                時間：
                <input
                    type="time"
                    name="time"
                    value="<?= htmlspecialchars($todo['time']) ?>"
                >
            </label>
        </p>

        <p>
            <label>
                備考：
                <br>
                <textarea name="note"><?= htmlspecialchars($todo['note']) ?></textarea>
            </label>
        </p>

            <?php endif; ?>

    <button type="submit">保存</button>

    </form>

    <p>
        <a href="detail.php?id=<?= $todo['id'] ?>">
            戻る
        </a>
    </p>

</body>
</html>