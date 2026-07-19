<!-- データを追加する -->

<?php

$date = $_GET['date'] ?? '';

?>

<!DOCTYPE html>

<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>追加</title>


<link rel="stylesheet" href="../css/style.css">


</head>

<body>


<h1><?= htmlspecialchars($date) ?></h1>

<form action="add.php" method="post">

    <input type="hidden" name="date" value="<?= htmlspecialchars($date) ?>">

    <p>
        <label>
            <input type="radio" name="type" value="task" checked>
            タスク
        </label>

        <label>
            <input type="radio" name="type" value="schedule">
            スケジュール
        </label>
    </p>

    <!-- タスクの入力欄 -->
    <div id="task-form">

        <p>
            <label>
                タイトル：
                <input type="text" name="task_title">
            </label>
        </p>

        <p>
            <label>
                締切：
                <input type="time" name="deadline">
            </label>
        </p>

        <p>
            <label>
                着手予定日：
                <input type="month" name="start_date">
            </label>
        </p>

        <p>
            <label>
                備考：
                <br>
                <textarea name="task_note"></textarea>
            </label>
        </p>

    </div>

    <!-- スケジュールの入力欄 -->
    <div id="schedule-form" style="display: none;">

        <p>
            <label>
                タイトル：
                <input type="text" name="schedule_title">
            </label>
        </p>

        <p>
            <label>
                時間：
                <input type="time" name="start_time">
                ～
                <input type="time" name="end_time">
            </label>
        </p>

        <p>
            <label>
                備考：
                <br>
                <textarea name="schedule_note"></textarea>
            </label>
        </p>

    </div>

    <button type="submit">追加</button>

</form>

<a href="date.php?date=<?= urlencode($date) ?>">戻る</a>

<script>

    const taskRadio = document.querySelector('input[value="task"]');
    const scheduleRadio = document.querySelector('input[value="schedule"]');

    const taskForm = document.getElementById('task-form');
    const scheduleForm = document.getElementById('schedule-form');

    taskRadio.addEventListener('change', function() {
        taskForm.style.display = 'block';
        scheduleForm.style.display = 'none';
    });

    scheduleRadio.addEventListener('change', function() {
        taskForm.style.display = 'none';
        scheduleForm.style.display = 'block';
    });

</script>


</body>
</html>
