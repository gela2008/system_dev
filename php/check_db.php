<?php

include "db.php";

echo "【ユーザー】\n";

$stmt = $db->query("SELECT * FROM user");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

echo "\n【科目】\n";

$stmt = $db->query("SELECT * FROM subject");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

echo "\n【todo】\n";

$stmt = $db->query("SELECT * FROM todo");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

?>