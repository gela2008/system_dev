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

    <h1>お知らせ</h1>

    <?php if (empty($notices)): ?>

        <p>現在、お知らせはありません。</p>

    <?php else: ?>

        <?php foreach ($notices as $notice): ?>

            <?php if ($notice['subject'] !== null): ?>

                <?php if ($notice['start_date'] === $today): ?>

                    <p>
                        📝 今日着手予定の課題があります：
                        <?= htmlspecialchars($notice['title']) ?>
                        （<?= htmlspecialchars($notice['subject']) ?>）
                    </p>

                <?php elseif ($notice['date'] === $today): ?>

                    <p>
                        ⚠️ 今日締切の課題があります：
                        <?= htmlspecialchars($notice['title']) ?>
                        （<?= htmlspecialchars($notice['subject']) ?>）
                    </p>

                <?php elseif ($notice['date'] === $tomorrow): ?>

                    <p>
                        ⏰ 明日締切の課題があります：
                        <?= htmlspecialchars($notice['title']) ?>
                        （<?= htmlspecialchars($notice['subject']) ?>）
                    </p>

                <?php endif; ?>

            <?php else: ?>

                <?php if ($notice['date'] === $today): ?>

                    <p>
                        📅 今日の予定があります：
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

                    </p>

                <?php elseif ($notice['date'] === $tomorrow): ?>

                    <p>
                        📅 明日の予定があります：
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

                    </p>

                <?php endif; ?>

            <?php endif; ?>

        <?php endforeach; ?>

    <?php endif; ?>

    <p>
        <a href="index.php?year=<?= $currentYear ?>&month=<?= $currentMonth ?>">
            カレンダーへ戻る
        </a>
    </p>

</body>
</html>