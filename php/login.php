<?php

session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    include "db.php";

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $db->prepare(
        "SELECT * FROM user WHERE username = ?"
    );

    $stmt->execute([$username]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $user['password'] === $password) {

        $_SESSION['student_id'] = $user['student_id'];

        header("Location: home.php");
        exit;

    } else {

        $error = 'ユーザ名またはパスワードが違います。';

    }
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ログイン</title>
</head>

<body>

    <h1>ログイン</h1>

    <?php if ($error): ?>
        <p><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post">

        <p>
            <label>
                ユーザ名：
                <input type="text" name="username" required>
            </label>
        </p>

        <p>
            <label>
                パスワード：
                <input type="password" name="password" required>
            </label>
        </p>

        <button type="submit">ログイン</button>

    </form>

</body>
</html>