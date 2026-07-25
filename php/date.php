<?php

session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

$date = $_GET['date'] ?? '';

$year = date('Y', strtotime($date));
$month = date('m', strtotime($date));

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

    <div class="page-container">

        <h1><?= htmlspecialchars($date) ?></h1>


        <h2>タスク</h2>

        <div class="task-list">

            <?php foreach ($todos as $todo): ?>

                <?php if ($todo['subject'] !== null): ?>

                    <div class="task-item">

                        <input
                            type="checkbox"
                            class="complete-checkbox"
                            data-id="<?= $todo['id'] ?>"
                            <?= $todo['completed'] == 1 ? 'checked' : '' ?>
                        >

                        <a
                            href="detail.php?id=<?= $todo['id'] ?>"
                            style="<?= $todo['completed'] == 1
                                ? 'text-decoration: line-through;'
                                : '' ?>"
                        >
                            <?= htmlspecialchars($todo['title']) ?>

                            （<?= htmlspecialchars($todo['subject']) ?>）
                        </a>

                    </div>

                <?php endif; ?>

            <?php endforeach; ?>

        </div>


        <h2>スケジュール</h2>

        <div class="task-list">

            <?php foreach ($todos as $todo): ?>

                <?php if ($todo['subject'] === null): ?>

                    <div class="task-item">

                        <input
                            type="checkbox"
                            class="complete-checkbox"
                            data-id="<?= $todo['id'] ?>"
                            <?= $todo['completed'] == 1 ? 'checked' : '' ?>
                        >

                        <a
                            href="detail.php?id=<?= $todo['id'] ?>"
                            style="<?= $todo['completed'] == 1
                                ? 'text-decoration: line-through;'
                                : '' ?>"
                        >
                            <?= htmlspecialchars($todo['title']) ?>
                        </a>

                    </div>

                <?php endif; ?>

            <?php endforeach; ?>

        </div>


        <div class="action-area">

            <a
                href="index.php?year=<?= $year ?>&month=<?= $month ?>"
                class="button back-button"
            >
                戻る
            </a>

            <a
                href="add.php?date=<?= urlencode($date) ?>"
                class="button"
            >
                追加
            </a>

        </div>

    </div>


    <script>

        const checkboxes =
            document.querySelectorAll('.complete-checkbox');


        checkboxes.forEach(function(checkbox) {

            checkbox.addEventListener('change', function() {

                const todoTitle =
                    this.parentElement.querySelector('a');


                if (this.checked) {

                    todoTitle.style.textDecoration =
                        'line-through';

                } else {

                    todoTitle.style.textDecoration =
                        'none';

                }


                fetch('complete.php', {

                    method: 'POST',

                    headers: {
                        'Content-Type':
                            'application/x-www-form-urlencoded'
                    },

                    body:
                        'id=' +
                        encodeURIComponent(this.dataset.id) +
                        '&completed=' +
                        (this.checked ? '1' : '0')

                });

            });

        });

    </script>

</body>
</html>