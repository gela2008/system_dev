<?php

$db = new PDO("sqlite:data/test.db");

$stmt = $db->prepare(
    "INSERT INTO user (student_id, name, grade, username, password)
     VALUES (?, ?, ?, ?, ?)"
);

$stmt->execute([
    230227,
    'テストユーザー',
    4,
    'testuser',
    'password123'
]);

echo "ユーザーの登録が完了しました。";