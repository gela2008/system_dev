<?php

session_start();

if (!isset($_SESSION['student_id'])) {
    exit;
}

$id = $_POST['id'] ?? '';
$completed = $_POST['completed'] ?? '';

if ($id === '' || ($completed !== '0' && $completed !== '1')) {
    exit;
}

include "db.php";

$stmt = $db->prepare(
    "UPDATE todo
     SET completed = ?
     WHERE id = ?
     AND student_id = ?"
);

$stmt->execute([
    $completed,
    $id,
    $_SESSION['student_id']
]);

?>