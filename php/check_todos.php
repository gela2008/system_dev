<?php

$db = new PDO("sqlite:data/test.db");

$todos = $db->query(
    "SELECT * FROM todo"
)->fetchAll(PDO::FETCH_ASSOC);

print_r($todos);