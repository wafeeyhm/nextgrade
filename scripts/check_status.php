<?php
require_once __DIR__ . '/../db.php';
$students = $pdo->query('SELECT id, name, username, grade_level, parent_id FROM students')->fetchAll();
print_r($students);
