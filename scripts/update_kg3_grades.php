<?php
require_once __DIR__ . '/../db.php';

echo "Updating grade levels to Kindergarten 3 (KG3)...\n";

// 1. Update students table default
$pdo->exec("ALTER TABLE `students` MODIFY COLUMN `grade_level` VARCHAR(50) DEFAULT 'Kindergarten 3 (KG3)'");
echo "✓ Set default grade_level to 'Kindergarten 3 (KG3)' in students table.\n";

// 2. Update existing Preschool records
$stmt = $pdo->prepare("
    UPDATE `students` 
    SET `grade_level` = 'Kindergarten 3 (KG3)' 
    WHERE `grade_level` LIKE '%Preschool%' OR `grade_level` LIKE '%Tadika%' OR `grade_level` = 'Year 1'
");
$stmt->execute();
echo "✓ Updated " . $stmt->rowCount() . " student records to 'Kindergarten 3 (KG3)'.\n";

// 3. Ensure Danish is Year 2 (non-KG3) for testing
$pdo->exec("UPDATE `students` SET `grade_level` = 'Year 2' WHERE `username` = 'danish'");
echo "✓ Ensured Danish Hakimi is 'Year 2' (non-KG3 test profile).\n";

// Check all students
$students = $pdo->query("SELECT id, name, username, grade_level FROM students")->fetchAll();
foreach ($students as $s) {
    echo "  [Student #{$s['id']}] {$s['name']} (@{$s['username']}) -> {$s['grade_level']}\n";
}
