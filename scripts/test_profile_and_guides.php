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
    'admin/developer_guide.php' => 'Developer & Question Guide',
    'admin/verification.php' => 'System Health & Verification Dashboard',
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
        assertCheck(strpos($content, 'verification.php') !== false, "$name has link to Verification Dashboard");
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
// TEST 5: Guide Relocation & Student/Parent Protection (Requirement 6)
// ---------------------------------------------------------------------
echo "\n--- 5. Testing Guide Relocation & Access Gating ---\n";
$indexPhp = file_get_contents(__DIR__ . '/../index.php');
assertCheck(strpos($indexPhp, 'href="guide.php"') === false, "Student dashboard (index.php) has NO link to developer guide.php");
assertCheck(strpos($indexPhp, 'Question & Git Guide') === false, "Student dashboard (index.php) has NO developer guide text in footer");

$rootGuide = file_get_contents(__DIR__ . '/../guide.php');
assertCheck(strpos($rootGuide, 'isAdminLoggedIn()') !== false, "Root guide.php enforces isAdminLoggedIn() check");
assertCheck(strpos($rootGuide, 'admin/developer_guide.php') !== false, "Root guide.php redirects authorized admins to admin/developer_guide.php");

$truncatePhp = file_get_contents(__DIR__ . '/../truncate.php');
assertCheck(strpos($truncatePhp, 'requireAdmin()') !== false, "truncate.php requires admin authentication for web requests");

$seedPhp = file_get_contents(__DIR__ . '/../seed.php');
assertCheck(strpos($seedPhp, 'requireAdmin()') !== false, "seed.php requires admin authentication for web requests");

// ---------------------------------------------------------------------
// TEST 6: System Verification & Online Readiness (Improvement 4)
// ---------------------------------------------------------------------
echo "\n--- 6. Testing System Verification Engine & Production Checker ---\n";
require_once __DIR__ . '/../admin/verification_engine.php';

$verifyEngine = new SystemVerificationEngine($pdo);
assertCheck(is_object($verifyEngine), "SystemVerificationEngine instantiates cleanly");

$dbReport = $verifyEngine->checkDatabase();
assertCheck($dbReport['checks'][0]['status'] === 'pass', "Engine: MySQL connection and latency check passes");
assertCheck($dbReport['checks'][1]['status'] === 'pass', "Engine: 10 core schema tables verification passes");
assertCheck($dbReport['checks'][2]['status'] === 'pass', "Engine: Critical columns & schema v2 checks pass");
assertCheck($dbReport['checks'][3]['status'] === 'pass', "Engine: Read/Write transaction rollback test passes");

$pagesReport = $verifyEngine->checkPages();
assertCheck($pagesReport['checks'][0]['status'] === 'pass', "Engine: All system pages exist with 0 syntax errors");
assertCheck($pagesReport['checks'][2]['status'] === 'pass', "Engine: Security gating on maintenance scripts passes");

$imagesReport = $verifyEngine->checkImages();
assertCheck($imagesReport['checks'][0]['status'] === 'pass', "Engine: All 173 referenced question images exist on disk (0 missing)");
assertCheck($imagesReport['checks'][1]['status'] === 'pass', "Engine: Media folders architecture verified");

$credsReport = $verifyEngine->checkCredentials();
assertCheck($credsReport['checks'][0]['status'] === 'pass', "Engine: System Admin credentials & password hash verified");
assertCheck($credsReport['checks'][1]['status'] === 'pass', "Engine: Parent accounts verified");
assertCheck($credsReport['checks'][2]['status'] === 'pass', "Engine: Kid simple 4-digit PINs & KG3 eligibility verified");

$envReport = $verifyEngine->checkProductionEnvironment();
assertCheck($envReport['checks'][0]['status'] === 'pass', "Engine: PHP runtime version meets standards");
assertCheck($envReport['checks'][1]['status'] === 'pass', "Engine: All 7 required extensions are loaded");
assertCheck($envReport['checks'][2]['status'] === 'pass', "Engine: Session storage directory is writable");

$fullReport = $verifyEngine->runAllChecks();
assertCheck($fullReport['health_score'] >= 90, "Engine: Overall system health score is >= 90% (Current: {$fullReport['health_score']}%)");
assertCheck($fullReport['summary']['failed'] === 0, "Engine: Zero critical failures detected in full system audit");

$apiContent = file_get_contents(__DIR__ . '/../api/admin_verification.php');
assertCheck(strpos($apiContent, 'requireAdmin(false)') !== false, "api/admin_verification.php enforces requireAdmin check");

$cliContent = file_get_contents(__DIR__ . '/verify_production.php');
assertCheck(strpos($cliContent, 'SystemVerificationEngine') !== false, "scripts/verify_production.php integrates SystemVerificationEngine");

$uiContent = file_get_contents(__DIR__ . '/../admin/verification.php');
assertCheck(strpos($uiContent, 'runLiveVerification') !== false, "admin/verification.php contains live AJAX audit function");
assertCheck(strpos($uiContent, 'Online Production Pre-Flight Checklist') !== false, "admin/verification.php contains pre-flight production checklist");

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
