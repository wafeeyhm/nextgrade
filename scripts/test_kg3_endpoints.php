<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../auth_helper.php';

// Session 1: Lana (KG3)
$_SESSION['student_id'] = 1;
$_SESSION['student_name'] = 'Lana marissa';
$_SESSION['student_grade'] = 'Kindergarten 3 (KG3)';

echo "--- 1. Testing Lana (KG3 Student) ---\n";
echo "Grade: " . getStudentUser()['grade_level'] . "\n";
echo "isCurrentStudentKG3: " . (isCurrentStudentKG3() ? "YES (allowed)" : "NO (blocked)") . "\n";

// Session 2: Danish (Year 2)
$_SESSION['student_id'] = 3;
$_SESSION['student_name'] = 'Danish Hakimi';
$_SESSION['student_grade'] = 'Year 2';

echo "\n--- 2. Testing Danish (Year 2 Student) ---\n";
echo "Grade: " . getStudentUser()['grade_level'] . "\n";
echo "isCurrentStudentKG3: " . (isCurrentStudentKG3() ? "YES (allowed)" : "NO (blocked)") . "\n";

// Test grade variations
echo "\n--- 3. Testing Grade Name Variations ---\n";
$variations = [
    'Kindergarten 3 (KG3)' => true,
    'Kindergarten 3' => true,
    'Kindergarden 3 (KG3)' => true,
    'Kindergarden 3' => true,
    'KG3' => true,
    'kg3' => true,
    'Year 1' => false,
    'Year 2' => false,
    'Year 3' => false,
    'Preschool' => false,
    '' => false,
    null => false
];

foreach ($variations as $grade => $expected) {
    $result = isGradeKG3($grade);
    $status = ($result === $expected) ? "PASS" : "FAIL";
    $dispGrade = ($grade === null) ? 'NULL' : ($grade === '' ? "''" : "'$grade'");
    echo "[$status] $dispGrade => " . ($result ? "KG3 ALLOWED" : "BLOCKED") . "\n";
}
