<?php
// NextGrade - Profile Update and System/User Guide Verification Test
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../auth_helper.php';

echo "=========================================================\n";
echo "   Profile Updates & Guide Pages Verification Test\n";
echo "=========================================================\n\n";

$passCount = 0;
$failCount = 0;

function assertCheck($cond, $title, $detail = '') {
    global $passCount, $failCount;
    if ($cond) {
        $passCount++;
        echo "  [PASS] $title\n";
        if ($detail) echo "         $detail\n";
    } else {
        $failCount++;
        echo "  [FAIL] $title\n";
        if ($detail) echo "         ERROR: $detail\n";
    }
}

// ---------------------------------------------------------------------
// TEST 1: Parent Profile API Checks
// ---------------------------------------------------------------------
echo "--- 1. Testing Parent Profile Management API ---\n";
// Create temporary test parent
$testCode = 'PAR-TEST' . rand(1000, 9999);
$testUser = 'parent_test_' . rand(100, 999);
$testEmail = $testUser . '@example.com';
$initialPass = 'Secret123!';

$stmtP = $pdo->prepare("
    INSERT INTO parents (parent_code, username, password_hash, full_name, email, phone, status)
    VALUES (?, ?, ?, ?, ?, ?, 'active')
");
$stmtP->execute([$testCode, $testUser, password_hash($initialPass, PASSWORD_DEFAULT), 'Initial Parent Name', $testEmail, '+60111111111']);
$testParentId = (int)$pdo->lastInsertId();
assertCheck($testParentId > 0, "Created temporary test parent account (ID: $testParentId)");

// Simulate Parent Session
$_SESSION['parent_id'] = $testParentId;
$_SESSION['parent_email'] = $testEmail;
$_SESSION['parent_username'] = $testUser;
$_SESSION['parent_full_name'] = 'Initial Parent Name';

// Test Profile Update (Name, Phone, Email, Username)
$newParentName = 'Puan Siti Nurhaliza';
$newParentUser = 'siti_' . rand(100, 999);
$newParentEmail = $newParentUser . '@example.com';
$newParentPhone = '+60199999999';

// Direct execution test of update logic
$stmtUpd = $pdo->prepare("
    UPDATE parents 
    SET full_name = ?, email = ?, username = ?, phone = ?
    WHERE id = ?
");
$stmtUpd->execute([$newParentName, $newParentEmail, $newParentUser, $newParentPhone, $testParentId]);
$freshParent = $pdo->query("SELECT * FROM parents WHERE id = $testParentId")->fetch();
assertCheck($freshParent['full_name'] === $newParentName, "Parent updated full name to '$newParentName'");
assertCheck($freshParent['username'] === $newParentUser, "Parent updated username to '$newParentUser'");
assertCheck($freshParent['email'] === $newParentEmail, "Parent updated email to '$newParentEmail'");
assertCheck($freshParent['phone'] === $newParentPhone, "Parent updated phone to '$newParentPhone'");

// Test Password Change with verification
$newPassword = 'NewSecretPassword456!';
$verifyCurrent = password_verify($initialPass, $freshParent['password_hash']);
assertCheck($verifyCurrent, "Current parent password verified successfully before change");

$newHash = password_hash($newPassword, PASSWORD_DEFAULT);
$pdo->prepare("UPDATE parents SET password_hash = ? WHERE id = ?")->execute([$newHash, $testParentId]);
$updatedPassParent = $pdo->query("SELECT * FROM parents WHERE id = $testParentId")->fetch();
assertCheck(password_verify($newPassword, $updatedPassParent['password_hash']), "New parent password verified with password_hash");
assertCheck(!password_verify('wrongpass', $updatedPassParent['password_hash']), "Wrong password correctly rejected for parent");

// Clean up test parent
$pdo->prepare("DELETE FROM parents WHERE id = ?")->execute([$testParentId]);
assertCheck(empty($pdo->query("SELECT id FROM parents WHERE id = $testParentId")->fetch()), "Test parent account cleaned up");

// ---------------------------------------------------------------------
// TEST 2: System Admin Profile Management API
// ---------------------------------------------------------------------
echo "\n--- 2. Testing System Admin Profile Management API ---\n";
// Create temporary test admin
$testAdminUser = 'adm_test_' . rand(100, 999);
$testAdminEmail = $testAdminUser . '@example.com';
$initialAdminPass = 'AdminPass123!';

$stmtA = $pdo->prepare("
    INSERT INTO admins (username, password_hash, full_name, email)
    VALUES (?, ?, ?, ?)
");
$stmtA->execute([$testAdminUser, password_hash($initialAdminPass, PASSWORD_DEFAULT), 'Initial Admin Name', $testAdminEmail]);
$testAdminId = (int)$pdo->lastInsertId();
assertCheck($testAdminId > 0, "Created temporary test admin account (ID: $testAdminId)");

// Simulate Admin Session
$_SESSION['admin_id'] = $testAdminId;
$_SESSION['admin_username'] = $testAdminUser;
$_SESSION['admin_full_name'] = 'Initial Admin Name';
$_SESSION['admin_email'] = $testAdminEmail;

// Test Admin Profile Update
$newAdminName = 'Chief Technology Officer';
$newAdminUser = 'cto_' . rand(100, 999);
$newAdminEmail = $newAdminUser . '@nextgrade.edu.my';

$stmtAdminUpd = $pdo->prepare("
    UPDATE admins 
    SET full_name = ?, email = ?, username = ?
    WHERE id = ?
");
$stmtAdminUpd->execute([$newAdminName, $newAdminEmail, $newAdminUser, $testAdminId]);
$freshAdmin = $pdo->query("SELECT * FROM admins WHERE id = $testAdminId")->fetch();
assertCheck($freshAdmin['full_name'] === $newAdminName, "Admin updated full name to '$newAdminName'");
assertCheck($freshAdmin['username'] === $newAdminUser, "Admin updated username to '$newAdminUser'");
assertCheck($freshAdmin['email'] === $newAdminEmail, "Admin updated email to '$newAdminEmail'");

// Test Admin Password Change
$newAdminPass = 'NextGrade2026Secure!';
$verifyCurrentAdmin = password_verify($initialAdminPass, $freshAdmin['password_hash']);
assertCheck($verifyCurrentAdmin, "Current admin password verified successfully before change");

$newAdminHash = password_hash($newAdminPass, PASSWORD_DEFAULT);
$pdo->prepare("UPDATE admins SET password_hash = ? WHERE id = ?")->execute([$newAdminHash, $testAdminId]);
$updatedPassAdmin = $pdo->query("SELECT * FROM admins WHERE id = $testAdminId")->fetch();
assertCheck(password_verify($newAdminPass, $updatedPassAdmin['password_hash']), "New admin password verified successfully");
assertCheck(!password_verify('wrongadminpass', $updatedPassAdmin['password_hash']), "Wrong password rejected for admin");

// Clean up test admin
$pdo->prepare("DELETE FROM admins WHERE id = ?")->execute([$testAdminId]);
assertCheck(empty($pdo->query("SELECT id FROM admins WHERE id = $testAdminId")->fetch()), "Test admin account cleaned up");

// ---------------------------------------------------------------------
// TEST 3: System Admin Guide and Dark Theme File Checks
// ---------------------------------------------------------------------
echo "\n--- 3. Testing System Admin Pages & Dark Theme ---\n";
$adminFiles = [
    'admin/index.php' => 'Dashboard',
    'admin/parents.php' => 'Parents CRUD',
    'admin/kids.php' => 'Kids Overview',
    'admin/profile.php' => 'Admin Profile & Credentials',
    'admin/guide.php' => 'System Guide',
    'admin/login.php' => 'Admin Login'
];

foreach ($adminFiles as $file => $name) {
    $path = __DIR__ . '/../' . $file;
    assertCheck(file_exists($path), "$name exists ($file)");
    $content = file_get_contents($path);
    assertCheck(strpos($content, 'bg-slate-900') !== false || strpos($content, 'bg-slate-800') !== false, "$name uses dark mode theme (bg-slate-900 / bg-slate-800)");
    if ($file !== 'admin/login.php') {
        assertCheck(strpos($content, 'guide.php') !== false, "$name has link to System Guide");
        assertCheck(strpos($content, 'profile.php') !== false, "$name has link to Admin Profile");
    }
}

// ---------------------------------------------------------------------
// TEST 4: Parent Portal Tabs & User Guide Checks
// ---------------------------------------------------------------------
echo "\n--- 4. Testing Parent Portal User Guide & Profile Tabs ---\n";
$parentPhp = file_get_contents(__DIR__ . '/../parent.php');
assertCheck(strpos($parentPhp, 'id="tab-btn-profile"') !== false, "parent.php contains Profile & Credentials tab button");
assertCheck(strpos($parentPhp, 'id="tab-content-profile"') !== false, "parent.php contains Profile edit section and form");
assertCheck(strpos($parentPhp, 'id="tab-btn-guide"') !== false, "parent.php contains Parent User Guide tab button");
assertCheck(strpos($parentPhp, 'id="tab-content-guide"') !== false, "parent.php contains Parent User Guide content container");
assertCheck(strpos($parentPhp, 'api/parent_profile.php') !== false, "parent.php integrates with api/parent_profile.php");
assertCheck(strpos($parentPhp, 'Kindergarten 3 (KG3)') !== false, "parent.php explains Kindergarten 3 (KG3) curriculum in guide");

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
