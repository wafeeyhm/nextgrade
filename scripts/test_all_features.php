<?php
// NextGrade - Automated End-to-End Verification Test
require_once __DIR__ . '/../db.php';

echo "=========================================================\n";
echo "   NextGrade Feature Verification Test (CLI Suite)\n";
echo "=========================================================\n\n";

$passCount = 0;
$failCount = 0;

function assertTest($condition, $name, $detail = '') {
    global $passCount, $failCount;
    if ($condition) {
        echo "  [PASS] $name\n";
        if ($detail) echo "         $detail\n";
        $passCount++;
    } else {
        echo "  [FAIL] $name\n";
        if ($detail) echo "         Error: $detail\n";
        $failCount++;
    }
}

// ---------------------------------------------------------------------
// TEST 1: Database Tables & Schema
// ---------------------------------------------------------------------
echo "--- 1. Testing Database Schema Tables ---\n";
$tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
assertTest(in_array('admins', $tables), "Admins table exists");
assertTest(in_array('parents', $tables), "Parents table exists");
assertTest(in_array('students', $tables), "Students table exists");
assertTest(in_array('quiz_sessions', $tables), "Quiz sessions table exists");
assertTest(in_array('quiz_session_answers', $tables), "Quiz answers table exists");

$studentCols = $pdo->query("SHOW COLUMNS FROM students")->fetchAll(PDO::FETCH_COLUMN);
assertTest(in_array('parent_id', $studentCols), "students.parent_id column present");
assertTest(in_array('username', $studentCols), "students.username column present");
assertTest(in_array('pin_code', $studentCols), "students.pin_code column present");

// ---------------------------------------------------------------------
// TEST 2: Admin Authentication & Password Verification
// ---------------------------------------------------------------------
echo "\n--- 2. Testing System Admin Login ---\n";
$admin = $pdo->query("SELECT * FROM admins WHERE username = 'admin'")->fetch();
assertTest(!empty($admin), "System Admin record exists in database");
$pwdCheck = password_verify('admin123', $admin['password_hash']);
assertTest($pwdCheck, "Admin password hash verifies correctly ('admin123')");

// ---------------------------------------------------------------------
// TEST 3: Parent Authentication & Data
// ---------------------------------------------------------------------
echo "\n--- 3. Testing Parent Accounts ---\n";
$sarah = $pdo->query("SELECT * FROM parents WHERE username = 'parent'")->fetch();
assertTest(!empty($sarah), "Demo Parent 'Sarah' exists");
$pCheck = password_verify('parent123', $sarah['password_hash']);
assertTest($pCheck, "Sarah's password verifies correctly ('parent123')");

$sarahKids = $pdo->query("SELECT * FROM students WHERE parent_id = {$sarah['id']}")->fetchAll();
assertTest(count($sarahKids) >= 2, "Sarah has multiple kids linked", "Found " . count($sarahKids) . " kids (Lana, Adam)");

// ---------------------------------------------------------------------
// TEST 4: Kids Simple Login Credentials
// ---------------------------------------------------------------------
echo "\n--- 4. Testing Simple Kids Access ---\n";
$lana = $pdo->query("SELECT * FROM students WHERE username = 'lana'")->fetch();
assertTest(!empty($lana), "Kid 'lana' has simple username");
assertTest($lana['pin_code'] === '1234', "Kid 'lana' has 4-digit PIN", "PIN: " . $lana['pin_code']);

$adam = $pdo->query("SELECT * FROM students WHERE username = 'adam'")->fetch();
assertTest(!empty($adam), "Kid 'adam' has simple username");
assertTest($adam['pin_code'] === '1234', "Kid 'adam' has 4-digit PIN", "PIN: " . $adam['pin_code']);

// ---------------------------------------------------------------------
// TEST 5: Parent CRUD Operations on Kids (Database Simulation)
// ---------------------------------------------------------------------
echo "\n--- 5. Testing Parent CRUD on Kids ---\n";
// Create temporary kid
$stmtIns = $pdo->prepare("
    INSERT INTO students (parent_id, name, username, pin_code, avatar, grade_level, status)
    VALUES (?, ?, ?, ?, ?, ?, 'active')
");
$stmtIns->execute([$sarah['id'], 'Maya Test', 'mayatest99', '5678', 'kitten', 'Kindergarten']);
$testKidId = (int)$pdo->lastInsertId();
assertTest($testKidId > 0, "Parent CREATE Kid: Created child 'Maya Test' with ID $testKidId");

// Update kid
$stmtUpd = $pdo->prepare("UPDATE students SET name = 'Maya Marissa', grade_level = 'Preschool' WHERE id = ? AND parent_id = ?");
$stmtUpd->execute([$testKidId, $sarah['id']]);
$updatedKid = $pdo->query("SELECT * FROM students WHERE id = $testKidId")->fetch();
assertTest($updatedKid['name'] === 'Maya Marissa', "Parent UPDATE Kid: Name updated to 'Maya Marissa'");

// Delete kid
$stmtDel = $pdo->prepare("DELETE FROM students WHERE id = ? AND parent_id = ?");
$stmtDel->execute([$testKidId, $sarah['id']]);
$deletedKid = $pdo->query("SELECT * FROM students WHERE id = $testKidId")->fetch();
assertTest(empty($deletedKid), "Parent DELETE Kid: Profile successfully removed");

// ---------------------------------------------------------------------
// TEST 6: System Admin CRUD on Parents (Database Simulation)
// ---------------------------------------------------------------------
echo "\n--- 6. Testing System Admin CRUD on Parents ---\n";
// Create test parent
$stmtParentIns = $pdo->prepare("
    INSERT INTO parents (parent_code, username, password_hash, full_name, email, phone, status)
    VALUES (?, ?, ?, ?, ?, ?, 'active')
");
$stmtParentIns->execute([
    'PAR-TEST99',
    'testparent99',
    password_hash('testpass123', PASSWORD_DEFAULT),
    'Encik Test Parent',
    'test99@example.com',
    '+60100000000'
]);
$testParentId = (int)$pdo->lastInsertId();
assertTest($testParentId > 0, "Admin CREATE Parent: Created 'Encik Test Parent' (ID: $testParentId)");

// Update test parent
$pdo->prepare("UPDATE parents SET full_name = 'Encik Test Updated', status = 'inactive' WHERE id = ?")->execute([$testParentId]);
$updParent = $pdo->query("SELECT * FROM parents WHERE id = $testParentId")->fetch();
assertTest($updParent['full_name'] === 'Encik Test Updated' && $updParent['status'] === 'inactive', "Admin UPDATE Parent: Details & status updated");

// Delete test parent
$pdo->prepare("DELETE FROM parents WHERE id = ?")->execute([$testParentId]);
$delParent = $pdo->query("SELECT * FROM parents WHERE id = $testParentId")->fetch();
assertTest(empty($delParent), "Admin DELETE Parent: Account successfully removed");

// ---------------------------------------------------------------------
// TEST 7: Previous Attempts & Answer Inspector (Requirement 3)
// ---------------------------------------------------------------------
echo "\n--- 7. Testing Previous Attempts & Question Inspector ---\n";
$sessionCount = (int)$pdo->query("SELECT COUNT(*) FROM quiz_sessions")->fetchColumn();
assertTest($sessionCount > 0, "Quiz sessions recorded", "Total: $sessionCount sessions in database");

// Inspect first session answers
$sess = $pdo->query("SELECT * FROM quiz_sessions LIMIT 1")->fetch();
$answers = $pdo->query("
    SELECT qsa.*, q.question_text, q.correct_answer 
    FROM quiz_session_answers qsa 
    JOIN questions q ON q.id = qsa.question_id 
    WHERE qsa.session_id = {$sess['id']}
")->fetchAll();

assertTest(count($answers) > 0, "Answers exist for session #{$sess['id']}", "Found " . count($answers) . " questions with answers");
$hasCorrect = false;
$hasIncorrect = false;
foreach ($answers as $a) {
    if ($a['is_correct'] == 1) $hasCorrect = true;
    if ($a['is_correct'] == 0) $hasIncorrect = true;
}
assertTest($hasCorrect, "Inspector identifies CORRECT answers (marked is_correct = 1)");
assertTest($hasIncorrect, "Inspector identifies INCORRECT answers (marked is_correct = 0)");

// ---------------------------------------------------------------------
// Summary
// ---------------------------------------------------------------------
echo "\n=========================================================\n";
echo "   Test Results: $passCount Passed, $failCount Failed\n";
echo "=========================================================\n";

if ($failCount === 0) {
    echo "🎉 ALL TESTS PASSED WITH ZERO ERRORS!\n\n";
    exit(0);
} else {
    echo "⚠️ SOME TESTS FAILED!\n\n";
    exit(1);
}
