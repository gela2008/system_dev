<?php

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    include "db.php";

    $student_id = $_POST['student_id'] ?? '';
    $name = $_POST['name'] ?? '';
    $grade = $_POST['grade'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $db->prepare(
        "INSERT INTO user (student_id, name, grade, username, password)
         VALUES (?, ?, ?, ?, ?)"
    );

    try {
        $stmt->execute([
            $student_id,
            $name,
            $grade,
            $username,
            $password
        ]);

        header("Location: login.php");
        exit;

    } catch (PDOException $e) {

        $error = '登録に失敗しました。学籍番号またはユーザ名がすでに使われている可能性があります。';

    }
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザ登録</title>
</head>

<body>

    <h1>ユーザ登録</h1>

    <?php if ($error): ?>
        <p><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post">

        <p>
            <label>
                学籍番号：
                <input type="text" name="student_id" required>
            </label>
        </p>

        <p>
            <label>
                名前：
                <input type="text" name="name" required>
            </label>
        </p>

        <p>
            <label>
                学年：
                <select name="grade" required>
                    <option value="">選択してください</option>
                    <option value="1">1年</option>
                    <option value="2">2年</option>
                    <option value="3">3年</option>
                    <option value="4">4年</option>
                    <option value="5">5年</option>
                </select>
            </label>
        </p>

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

        <button type="submit">登録</button>

    </form>

    <a href="login.php">ログイン画面へ</a>

</body>
</html>