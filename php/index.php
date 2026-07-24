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
    "SELECT date, title
     FROM todo
     WHERE student_id = ?
     AND completed = 0"
);

$stmt->execute([
    $_SESSION['student_id']
]);

$todos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$todoByDate = [];

foreach ($todos as $todo) {
    $todoByDate[$todo['date']][] = $todo['title'];
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

                                <?php foreach ($todoByDate[$date] as $title): ?>

                                    <div>
                                        <?= htmlspecialchars($title) ?>
                                    </div>

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