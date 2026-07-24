<?php

session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ホーム</title>
</head>

<body>

    <h1>ホーム</h1>

    <p>ログインに成功しました！</p>

    <p>学籍番号：<?= htmlspecialchars($_SESSION['student_id']) ?></p>

    <?php
    
    include "db.php";
    
    $stmt = $db->prepare(
        "SELECT grade FROM user WHERE student_id = ?"
        );
        
    $stmt->execute([$_SESSION['student_id']]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    ?>

<p>学年：<?= htmlspecialchars($user['grade']) ?>年</p>

    <a href="index.php">カレンダーへ</a>

<p>
    <a href="logout.php">ログアウト</a>
</p>

</body>
</html>