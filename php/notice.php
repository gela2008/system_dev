<?php

session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

include "db.php";

$today = date('Y-m-d');
$tomorrow = date('Y-m-d', strtotime('+1 day'));

$currentYear = date('Y');
$currentMonth = date('n');

$stmt = $db->prepare(
    "SELECT id, title, subject, date, start_date, time, end_time
     FROM todo
     WHERE student_id = ?
     AND completed = 0
     AND (
         start_date = ?
         OR date = ?
         OR date = ?
     )
     ORDER BY date, time"
);

$stmt->execute([
    $_SESSION['student_id'],
    $today,
    $today,
    $tomorrow
]);

$notices = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>お知らせ</title>

    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <div class="page-container">

        <h1>お知らせ</h1>

        <?php if (empty($notices)): ?>

            <div class="card">

                <p>
                    現在、お知らせはありません。
                </p>

            </div>

        <?php else: ?>

            <div class="notice-list">

                <?php foreach ($notices as $notice): ?>

                    <?php if ($notice['subject'] !== null): ?>

                        <?php if ($notice['start_date'] === $today): ?>

                            <div class="notice-item">

                                📝
                                今日着手予定の課題があります：

                                <?= htmlspecialchars($notice['title']) ?>

                                （<?= htmlspecialchars($notice['subject']) ?>）

                            </div>

                        <?php elseif ($notice['date'] === $today): ?>

                            <div class="notice-item">

                                ⚠️
                                今日締切の課題があります：

                                <?= htmlspecialchars($notice['title']) ?>

                                （<?= htmlspecialchars($notice['subject']) ?>）

                            </div>

                        <?php elseif ($notice['date'] === $tomorrow): ?>

                            <div class="notice-item">

                                ⏰
                                明日締切の課題があります：

                                <?= htmlspecialchars($notice['title']) ?>

                                （<?= htmlspecialchars($notice['subject']) ?>）

                            </div>

                        <?php endif; ?>

                    <?php else: ?>

                        <?php if ($notice['date'] === $today): ?>

                            <div class="notice-item">

                                📅
                                今日の予定があります：

                                <?= htmlspecialchars($notice['title']) ?>

                                <?php if (!empty($notice['time'])): ?>

                                    （<?= htmlspecialchars($notice['time']) ?>

                                    <?php if (!empty($notice['end_time'])): ?>

                                        ～<?= htmlspecialchars($notice['end_time']) ?>

                                    <?php else: ?>

                                        ～

                                    <?php endif; ?>

                                    ）

                                <?php endif; ?>

                            </div>

                        <?php elseif ($notice['date'] === $tomorrow): ?>

                            <div class="notice-item">

                                📅
                                明日の予定があります：

                                <?= htmlspecialchars($notice['title']) ?>

                                <?php if (!empty($notice['time'])): ?>

                                    （<?= htmlspecialchars($notice['time']) ?>

                                    <?php if (!empty($notice['end_time'])): ?>

                                        ～<?= htmlspecialchars($notice['end_time']) ?>

                                    <?php else: ?>

                                        ～

                                    <?php endif; ?>

                                    ）

                                <?php endif; ?>

                            </div>

                        <?php endif; ?>

                    <?php endif; ?>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>


        <div class="action-area">

            <a
                href="index.php?year=<?= $currentYear ?>&month=<?= $currentMonth ?>"
                class="button"
            >
                カレンダーへ戻る
            </a>

        </div>

    </div>

</body>
</html>