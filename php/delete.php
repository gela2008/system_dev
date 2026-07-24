<?php

session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_POST['id'] ?? '';

include "db.php";

$stmt = $db->prepare(
    "SELECT date
     FROM todo
     WHERE id = ?
     AND student_id = ?"
);

$stmt->execute([
    $id,
    $_SESSION['student_id']
]);

$todo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$todo) {
    exit('削除対象のデータが見つかりません。');
}

$stmt = $db->prepare(
    "DELETE FROM todo
     WHERE id = ?
     AND student_id = ?"
);

$stmt->execute([
    $id,
    $_SESSION['student_id']
]);

header(
    "Location: date.php?date="
    . urlencode($todo['date'])
);

exit;