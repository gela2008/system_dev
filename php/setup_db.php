<?php

$db = new PDO("sqlite:data/test.db");

$db->exec("
    DROP TABLE IF EXISTS todo;
    DROP TABLE IF EXISTS subject;
    DROP TABLE IF EXISTS user;
");

$db->exec("
    CREATE TABLE user (
        student_id INTEGER PRIMARY KEY,
        name TEXT NOT NULL,
        grade INTEGER NOT NULL,
        username TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL
    )
");

$db->exec("
    CREATE TABLE subject (
        grade INTEGER NOT NULL,
        term TEXT NOT NULL,
        subject TEXT NOT NULL,
        PRIMARY KEY (grade, term, subject)
    )
");

$db->exec("
    CREATE TABLE todo (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        student_id INTEGER NOT NULL,
        title TEXT NOT NULL,
        subject TEXT,
        date TEXT NOT NULL,
        start_date TEXT,
        time TEXT,
        note TEXT,
        completed INTEGER NOT NULL DEFAULT 0,
        FOREIGN KEY (student_id) REFERENCES user(student_id)
    )
");

echo "テーブルの作成が完了しました。";