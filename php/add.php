<?php

session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

include "db.php";

$date = $_GET['date'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $type = $_POST['type'] ?? '';
    $date = $_POST['date'] ?? '';

    if ($type === 'task') {

        $title = $_POST['task_title'] ?? '';
        $subject = $_POST['subject'] ?? '';
        $start_date = $_POST['start_date'] ?? '';
        $time = $_POST['deadline'] ?? '';
        $end_time = null;
        $note = $_POST['task_note'] ?? '';

    } else {

        $title = $_POST['schedule_title'] ?? '';
        $subject = null;
        $start_date = null;
        $time = $_POST['start_time'] ?? '';
        $end_time = $_POST['end_time'] ?? '';
        $note = $_POST['schedule_note'] ?? '';

    }

    $stmt = $db->prepare(
        "INSERT INTO todo
        (student_id, title, subject, date, start_date, time, end_time, note)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
    );

    $stmt->execute([
        $_SESSION['student_id'],
        $title,
        $subject,
        $date,
        $start_date,
        $time,
        $end_time,
        $note
    ]);

    header("Location: date.php?date=" . urlencode($date));
    exit;
}

$stmt = $db->prepare(
    "SELECT grade
     FROM user
     WHERE student_id = ?"
);

$stmt->execute([$_SESSION['student_id']]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

$grade = $user['grade'];

$stmt = $db->prepare(
    "SELECT subject
     FROM subject
     WHERE grade = ?
     AND term = '前期'
     ORDER BY subject"
);

$stmt->execute([$grade]);

$subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

    <div class="page-container">

        <div class="edit-card">

            <h1><?= htmlspecialchars($date) ?></h1>


            <div class="type-select">

                <label>
                    <input
                        type="radio"
                        name="type-select"
                        value="task"
                        checked
                    >
                    タスク
                </label>

                <label>
                    <input
                        type="radio"
                        name="type-select"
                        value="schedule"
                    >
                    スケジュール
                </label>

            </div>


            <form
                action="add.php"
                method="post"
            >

                <input
                    type="hidden"
                    name="date"
                    value="<?= htmlspecialchars($date) ?>"
                >


                <input
                    type="hidden"
                    name="type"
                    id="type"
                    value="task"
                >


                <div id="task-form">

                    <h2>タスク</h2>


                    <div class="form-item">

                        <label for="task_title">
                            タイトル
                        </label>

                        <input
                            type="text"
                            id="task_title"
                            name="task_title"
                            required
                        >

                    </div>


                    <div class="form-item">

                        <label for="subject">
                            科目
                        </label>

                        <select
                            id="subject"
                            name="subject"
                        >

                            <?php foreach ($subjects as $subject): ?>

                                <option
                                    value="<?= htmlspecialchars($subject['subject']) ?>"
                                >
                                    <?= htmlspecialchars($subject['subject']) ?>
                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>


                    <div class="form-item">

                        <label for="deadline">
                            締切
                        </label>

                        <input
                            type="time"
                            id="deadline"
                            name="deadline"
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
                        >

                    </div>


                    <div class="form-item">

                        <label for="task_note">
                            備考
                        </label>

                        <textarea
                            id="task_note"
                            name="task_note"
                        ></textarea>

                    </div>

                </div>


                <div
                    id="schedule-form"
                    style="display: none;"
                >

                    <h2>スケジュール</h2>


                    <div class="form-item">

                        <label for="schedule_title">
                            タイトル
                        </label>

                        <input
                            type="text"
                            id="schedule_title"
                            name="schedule_title"
                        >

                    </div>


                    <div class="form-item">

                        <label>
                            時間
                        </label>

                        <input
                            type="time"
                            name="start_time"
                        >

                        ～

                        <input
                            type="time"
                            name="end_time"
                        >

                    </div>


                    <div class="form-item">

                        <label for="schedule_note">
                            備考
                        </label>

                        <textarea
                            id="schedule_note"
                            name="schedule_note"
                        ></textarea>

                    </div>

                </div>


                <div class="form-buttons">

                    <button
                        type="submit"
                        class="save-button"
                    >
                        追加
                    </button>

                    <a
                        href="date.php?date=<?= urlencode($date) ?>"
                        class="button back-button"
                    >
                        戻る
                    </a>

                </div>

            </form>

        </div>

    </div>


    <script>

        const taskRadio =
            document.querySelector('input[value="task"]');

        const scheduleRadio =
            document.querySelector('input[value="schedule"]');

        const taskForm =
            document.getElementById('task-form');

        const scheduleForm =
            document.getElementById('schedule-form');

        const typeInput =
            document.getElementById('type');


        taskRadio.addEventListener('change', function() {

            taskForm.style.display = 'block';

            scheduleForm.style.display = 'none';

            typeInput.value = 'task';

        });


        scheduleRadio.addEventListener('change', function() {

            taskForm.style.display = 'none';

            scheduleForm.style.display = 'block';

            typeInput.value = 'schedule';

        });

    </script>

</body>

</html>