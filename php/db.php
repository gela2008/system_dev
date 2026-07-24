<!-- sqliteへ接続する処理 -->
<?php

$db = new PDO(
    "sqlite:" . __DIR__ . "/../data/test.db"
);

$db->setAttribute(
    PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION
);

?>