<?php

session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

$year = $_GET['year'] ?? 2026;
$month = $_GET['month'] ?? 6;

$year = (int)$year;
$month = (int)$month;

if ($month < 1) {
    $year--;
    $month = 12;
}

if ($month > 12) {
    $year++;
    $month = 1;
}

include "db.php";

$stmt = $db->prepare(
    "SELECT id, date, start_date, title, subject
     FROM todo
     WHERE student_id = ?
     AND completed = 0"
);

$stmt->execute([
    $_SESSION['student_id']
]);

$todos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$today = date('Y-m-d');
$tomorrow = date('Y-m-d', strtotime('+1 day'));

$noticeStmt = $db->prepare(
    "SELECT id, title, subject, date, start_date
     FROM todo
     WHERE student_id = ?
     AND completed = 0
     AND (
         start_date = ?
         OR date = ?
         OR date = ?
     )
     ORDER BY date"
);

$noticeStmt->execute([
    $_SESSION['student_id'],
    $today,
    $today,
    $tomorrow
]);

$notices = $noticeStmt->fetchAll(PDO::FETCH_ASSOC);

$todoByDate = [];

foreach ($todos as $todo) {

    // 締切日
    $todoByDate[$todo['date']][] = [
        'id' => $todo['id'],
        'title' => $todo['title'],
        'subject' => $todo['subject'],
        'date' => $todo['date'],
        'type' => 'deadline'
    ];

    // 着手予定日
    if (
        $todo['subject'] !== null
        && !empty($todo['start_date'])
    ) {

        $todoByDate[$todo['start_date']][] = [
            'id' => $todo['id'],
            'title' => $todo['title'],
            'subject' => $todo['subject'],
            'date' => $todo['date'],
            'type' => 'start'
        ];

    }

}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>カレンダー</title>

    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="notice-area">

        <a href="notice.php" class="notice-link">

            ✉️

            <?php if (!empty($notices)): ?>

                <span class="notice-count">
                    <?= count($notices) ?>
                </span>

            <?php endif; ?>

        </a>

    </div>

    <div class="calendar-header">

        <a href="index.php?year=<?= $year ?>&month=<?= $month - 1 ?>">
            ＜
        </a>

        <h1><?= $year ?>年<?= $month ?>月</h1>

        <a href="index.php?year=<?= $year ?>&month=<?= $month + 1 ?>">
            ＞
        </a>

    </div>

    <table class="calendar">

        <tr>
            <th>日</th>
            <th>月</th>
            <th>火</th>
            <th>水</th>
            <th>木</th>
            <th>金</th>
            <th>土</th>
        </tr>

        <?php

        $firstDay = new DateTime("$year-$month-01");

        $startWeekday = (int)$firstDay->format('w');

        $daysInMonth = (int)$firstDay->format('t');

        $day = 1;

        while ($day <= $daysInMonth):

        ?>

            <tr>

                <?php for ($i = 0; $i < 7; $i++): ?>

                    <?php if (
                        ($day === 1 && $i < $startWeekday)
                        || $day > $daysInMonth
                    ): ?>

                        <td></td>

                    <?php else: ?>

                        <?php

                        $date = sprintf(
                            '%04d-%02d-%02d',
                            $year,
                            $month,
                            $day
                        );

                        ?>

                        <td>

                            <a href="date.php?date=<?= $date ?>">
                                <?= $day ?>
                            </a>

                            <?php if (isset($todoByDate[$date])): ?>

                                <?php foreach ($todoByDate[$date] as $todo): ?>

                                    <?php if ($todo['type'] === 'start'): ?>

                                        <div class="calendar-start">

                                            <div>
                                                📝 着手予定：
                                                <?= htmlspecialchars($todo['title']) ?>
                                            </div>

                                            <small>
                                                締切：
                                                <?= date(
                                                    'n月j日',
                                                    strtotime($todo['date'])
                                                ) ?>
                                            </small>

                                            <small>
                                                （<?= htmlspecialchars($todo['subject']) ?>）
                                            </small>

                                        </div>

                                    <?php elseif ($todo['subject'] !== null): ?>

                                        <div class="calendar-task">

                                            📚
                                            <?= htmlspecialchars($todo['title']) ?>

                                            （<?= htmlspecialchars($todo['subject']) ?>）

                                        </div>

                                    <?php else: ?>

                                        <div class="calendar-schedule">

                                            📅
                                            <?= htmlspecialchars($todo['title']) ?>

                                        </div>

                                    <?php endif; ?>

                                <?php endforeach; ?>

                            <?php endif; ?>

                        </td>

                        <?php $day++; ?>

                    <?php endif; ?>

                <?php endfor; ?>

            </tr>

        <?php endwhile; ?>

    </table>

</body>
</html>