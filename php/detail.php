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

    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <div class="page-container">

        <div class="detail-card">

            <h1>
                <?= htmlspecialchars($todo['title']) ?>
            </h1>


            <?php if ($todo['subject'] !== null): ?>

                <div class="detail-item">
                    <span>種類</span>
                    <strong>タスク</strong>
                </div>

                <div class="detail-item">
                    <span>科目</span>
                    <strong>
                        <?= htmlspecialchars($todo['subject']) ?>
                    </strong>
                </div>

                <div class="detail-item">
                    <span>締切</span>
                    <strong>
                        <?= htmlspecialchars($todo['date']) ?>
                        <?= htmlspecialchars($todo['time']) ?>
                    </strong>
                </div>

                <div class="detail-item">
                    <span>着手予定日</span>
                    <strong>
                        <?= htmlspecialchars($todo['start_date']) ?>
                    </strong>
                </div>


            <?php else: ?>

                <div class="detail-item">
                    <span>種類</span>
                    <strong>スケジュール</strong>
                </div>

                <div class="detail-item">
                    <span>日付</span>
                    <strong>
                        <?= htmlspecialchars($todo['date']) ?>
                    </strong>
                </div>

                <div class="detail-item">
                    <span>時間</span>
                    <strong>

                        <?= htmlspecialchars($todo['time']) ?>

                        <?php if (!empty($todo['end_time'])): ?>

                            ～
                            <?= htmlspecialchars($todo['end_time']) ?>

                        <?php endif; ?>

                    </strong>
                </div>

            <?php endif; ?>


            <div class="detail-note">

                <h3>備考</h3>

                <p>
                    <?= nl2br(htmlspecialchars($todo['note'])) ?>
                </p>

            </div>


            <div class="action-area">

                <a
                    href="date.php?date=<?= urlencode($todo['date']) ?>"
                    class="button back-button"
                >
                    一覧に戻る
                </a>

                <a
                    href="edit.php?id=<?= $todo['id'] ?>"
                    class="button"
                >
                    編集
                </a>

                <form
                    action="delete.php"
                    method="post"
                    onsubmit="return confirm('この予定を削除してもよろしいですか？');"
                >

                    <input
                        type="hidden"
                        name="id"
                        value="<?= htmlspecialchars($todo['id']) ?>"
                    >

                    <button
                        type="submit"
                        class="delete-button"
                    >
                        削除
                    </button>

                </form>

            </div>

        </div>

    </div>

</body>
</html>