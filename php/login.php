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

        $error = 'ユーザー名またはパスワードが違います。';

    }
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>ログイン</title>

    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <div class="login-container">

        <div class="card">

            <h1>ログイン</h1>

            <?php if ($error): ?>

                <p class="error-message">
                    <?= htmlspecialchars($error) ?>
                </p>

            <?php endif; ?>

            <form method="post">

                <div class="form-group">

                    <label for="username">
                        ユーザー名
                    </label>

                    <input
                        type="text"
                        id="username"
                        name="username"
                        required
                    >

                </div>

                <div class="form-group">

                    <label for="password">
                        パスワード
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                    >

                </div>

                <button type="submit">
                    ログイン
                </button>

            </form>

        </div>

    </div>

</body>
</html>