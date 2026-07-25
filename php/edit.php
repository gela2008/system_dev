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

    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

    <div class="page-container">

        <div class="edit-card">

            <h1>編集</h1>


            <form
                action="edit.php?id=<?= $todo['id'] ?>"
                method="post"
            >

                <?php if ($todo['subject'] !== null): ?>

                    <h2>タスク</h2>


                    <div class="form-item">

                        <label for="title">
                            タイトル
                        </label>

                        <input
                            type="text"
                            id="title"
                            name="title"
                            value="<?= htmlspecialchars($todo['title']) ?>"
                            required
                        >

                    </div>


                    <div class="form-item">

                        <label>
                            科目
                        </label>

                        <p class="readonly-value">
                            <?= htmlspecialchars($todo['subject']) ?>
                        </p>

                    </div>


                    <div class="form-item">

                        <label for="time">
                            締切
                        </label>

                        <input
                            type="time"
                            id="time"
                            name="time"
                            value="<?= htmlspecialchars($todo['time']) ?>"
                        >

                    </div>


                    <div class="form-item">

                        <label for="start_date">
                            着手予定日
                        </label>

                        <input
                            type="date"
                            id="start_date"
                            name="start_date"
                            value="<?= htmlspecialchars($todo['start_date']) ?>"
                        >

                    </div>


                    <div class="form-item">

                        <label for="note">
                            備考
                        </label>

                        <textarea
                            id="note"
                            name="note"
                        ><?= htmlspecialchars($todo['note']) ?></textarea>

                    </div>


                <?php else: ?>

                    <h2>スケジュール</h2>


                    <div class="form-item">

                        <label for="title">
                            タイトル
                        </label>

                        <input
                            type="text"
                            id="title"
                            name="title"
                            value="<?= htmlspecialchars($todo['title']) ?>"
                            required
                        >

                    </div>


                    <div class="form-item">

                        <label for="time">
                            時間
                        </label>

                        <input
                            type="time"
                            id="time"
                            name="time"
                            value="<?= htmlspecialchars($todo['time']) ?>"
                        >

                    </div>


                    <div class="form-item">

                        <label for="note">
                            備考
                        </label>

                        <textarea
                            id="note"
                            name="note"
                        ><?= htmlspecialchars($todo['note']) ?></textarea>

                    </div>

                <?php endif; ?>


                <div class="form-buttons">

                    <button
                        type="submit"
                        class="save-button"
                    >
                        保存
                    </button>

                    <a
                        href="detail.php?id=<?= $todo['id'] ?>"
                        class="button back-button"
                    >
                        戻る
                    </a>

                </div>

            </form>

        </div>

    </div>

</body>

</html>