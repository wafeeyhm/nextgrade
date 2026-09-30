<?php
require_once __DIR__ . '/../db.php';

$students = $pdo->query('SELECT * FROM students')->fetchAll();
print_r($students);
