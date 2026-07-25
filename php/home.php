<?php

session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

include "db.php";

$stmt = $db->prepare(
    "SELECT grade
     FROM user
     WHERE student_id = ?"
);

$stmt->execute([
    $_SESSION['student_id']
]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>ホーム</title>

    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <div class="home-container">

        <div class="card">

            <h1>ホーム</h1>

            <p>
                ログインに成功しました！
            </p>

            <p>
                学籍番号：
                <?= htmlspecialchars($_SESSION['student_id']) ?>
            </p>

            <p>
                学年：
                <?= htmlspecialchars($user['grade']) ?>年
            </p>

            <div class="action-area">

                <a
                    href="index.php"
                    class="button"
                >
                    カレンダーへ
                </a>

                <a
                    href="logout.php"
                    class="button logout-button"
                >
                    ログアウト
                </a>

            </div>

        </div>

    </div>

</body>
</html>