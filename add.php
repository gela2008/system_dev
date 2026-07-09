<!-- データを追加する -->

<?php

include "db.php";

// 入力されたタスクを受け取る
$task = $_POST["task"];

// データベースに保存する
$sql = "INSERT INTO todo (task) VALUES (?)";

$stmt = $db->prepare($sql);
$stmt->execute([$task]);

// index.php に戻る
header("Location: index.php");
exit;

?>