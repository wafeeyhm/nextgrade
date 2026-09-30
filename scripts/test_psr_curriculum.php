<?php
// NextGrade - Year 6 Brunei PSR Curriculum Comprehensive Test Suite
// Verifies 5 Subjects, 21 Topics, Minimum 30 Questions/Topic, and Strict Grade Isolation.

require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../auth_helper.php';

echo "=========================================================\n";
echo "   Year 6 Brunei PSR Curriculum & Isolation Test Suite\n";
echo "=========================================================\n\n";

$passCount = 0;
$failCount = 0;

function assertCheck(bool $condition, string $testName, string $details = '') {
    global $passCount, $failCount;
    if ($condition) {
        $passCount++;
        echo "  [PASS] $testName\n";
        if ($details) echo "         $details\n";
    } else {
        $failCount++;
        echo "  [FAIL] $testName\n";
        if ($details) echo "         ERROR: $details\n";
    }
}

function runApiSubprocess(string $apiPath, array $get = [], array $session = []): array {
    $runner = __DIR__ . '/api_test_runner.php';
    $tmpDir = __DIR__ . '/../scratch';
    if (!is_dir($tmpDir)) {
        @mkdir($tmpDir, 0777, true);
    }
    $tmpFile = $tmpDir . '/runner_' . uniqid() . '.json';
    $payload = json_encode([
        'file' => $apiPath,
        'get' => $get,
        'session' => $session,
        'method' => 'GET'
    ]);
    file_put_contents($tmpFile, $payload);

    $cmd = 'php ' . escapeshellarg($runner) . ' ' . escapeshellarg($tmpFile);
    $raw = shell_exec($cmd);
    @unlink($tmpFile);

    $start = strpos($raw, '{');
    $end = strrpos($raw, '}');
    if ($start !== false && $end !== false) {
        $json = substr($raw, $start, $end - $start + 1);
        $data = json_decode($json, true);
        if (is_array($data)) return $data;
    }
    return ['success' => false, 'raw' => $raw];
}

// -------------------------------------------------------------
// 1. PSR Subjects Verification
// -------------------------------------------------------------
echo "--- 1. Testing Year 6 PSR Core Subjects (5 Core Subjects) ---\n";
$expectedSubjects = ['psr_maths', 'psr_science', 'psr_english', 'psr_bahasa_melayu', 'psr_mib'];
$stmtSub = $pdo->query("SELECT id, name, grade_level FROM subjects WHERE grade_level = 'Year 6 (PSR)' ORDER BY sort_order");
$psrSubs = $stmtSub->fetchAll(PDO::FETCH_ASSOC);

assertCheck(count($psrSubs) === 5, "Exactly 5 Year 6 PSR subjects present in database", "Found " . count($psrSubs));
$subIds = array_column($psrSubs, 'id');
foreach ($expectedSubjects as $sId) {
    assertCheck(in_array($sId, $subIds), "PSR Subject '$sId' exists and active");
}

// -------------------------------------------------------------
// 2. PSR Topics Verification (21 Topics)
// -------------------------------------------------------------
echo "\n--- 2. Testing Year 6 PSR Topics (21 Topics) ---\n";
$stmtTop = $pdo->query("SELECT id, subject_id, name, grade_level FROM topics WHERE grade_level = 'Year 6 (PSR)' ORDER BY id");
$psrTopics = $stmtTop->fetchAll(PDO::FETCH_ASSOC);
assertCheck(count($psrTopics) === 21, "Exactly 21 Year 6 PSR topics present in database", "Found " . count($psrTopics));

// -------------------------------------------------------------
// 3. Minimum 30 Questions Per Topic Requirement
// -------------------------------------------------------------
echo "\n--- 3. Testing Minimum 30 Questions Per Topic (User Req #2) ---\n";
$stmtCounts = $pdo->query("
    SELECT t.id, t.name, s.name as subject_name, COUNT(q.id) as q_count
    FROM topics t
    JOIN subjects s ON s.id = t.subject_id
    LEFT JOIN questions q ON q.topic_id = t.id AND q.grade_level = 'Year 6 (PSR)'
    WHERE t.grade_level = 'Year 6 (PSR)'
    GROUP BY t.id, t.name, s.name
    ORDER BY t.subject_id, t.id
");
$topicCounts = $stmtCounts->fetchAll(PDO::FETCH_ASSOC);

$allMeetThreshold = true;
$totalQuestionsCount = 0;
foreach ($topicCounts as $tc) {
    $c = (int)$tc['q_count'];
    $totalQuestionsCount += $c;
    $meets = ($c >= 30);
    if (!$meets) {
        $allMeetThreshold = false;
    }
    assertCheck($meets, "Topic '{$tc['id']}' ({$tc['subject_name']}) has >= 30 questions ($c questions)");
}

assertCheck($allMeetThreshold, "ALL 21 Year 6 PSR topics meet or exceed the 30-question threshold");
assertCheck($totalQuestionsCount >= 630, "Total Year 6 PSR questions count meets requirement ($totalQuestionsCount >= 630)");

// -------------------------------------------------------------
// 4. Strict Grade Isolation (User Req #3 - Zero mixing with KG3)
// -------------------------------------------------------------
echo "\n--- 4. Testing Strict Grade Isolation (Zero Mixing with KG3) ---\n";

// A. Database Level check: Zero questions belong to both or have mismatched grade_level
$kg3InPsrTopics = (int)$pdo->query("
    SELECT COUNT(*) FROM questions 
    WHERE topic_id LIKE 'psr_%' AND grade_level != 'Year 6 (PSR)'
")->fetchColumn();
assertCheck($kg3InPsrTopics === 0, "Zero KG3 or untagged questions in PSR topics (Found: $kg3InPsrTopics)");

$psrInKg3Topics = (int)$pdo->query("
    SELECT COUNT(*) FROM questions 
    WHERE topic_id NOT LIKE 'psr_%' AND grade_level = 'Year 6 (PSR)'
")->fetchColumn();
assertCheck($psrInKg3Topics === 0, "Zero PSR questions in KG3 topics (Found: $psrInKg3Topics)");

// B. Subject Catalog Isolation via api/subjects.php for Year 6
$subJson = runApiSubprocess(realpath(__DIR__ . '/../api/subjects.php'), [], [
    'student_grade' => 'Year 6 (PSR Brunei)',
    'student_name' => 'Danish (PSR Candidate)'
]);

assertCheck(!empty($subJson['success']) && $subJson['success'] === true, "api/subjects.php returns success for Year 6 student");
assertCheck(count($subJson['subjects'] ?? []) === 5, "api/subjects.php returns exactly 5 subjects for Year 6 student");
$allPsr = true;
foreach ($subJson['subjects'] ?? [] as $s) {
    if (strpos($s['id'], 'psr_') !== 0) {
        $allPsr = false;
    }
}
assertCheck($allPsr, "ALL subjects returned for Year 6 student are PSR subjects (Zero KG3 subjects)");

// C. Subject Catalog Isolation for KG3
$subJsonKg3 = runApiSubprocess(realpath(__DIR__ . '/../api/subjects.php'), [], [
    'student_grade' => 'Kindergarten 3 (KG3)',
    'student_name' => 'Lana (KG3)'
]);

assertCheck(!empty($subJsonKg3['success']) && $subJsonKg3['success'] === true, "api/subjects.php returns success for KG3 student");
$allKg3 = true;
foreach ($subJsonKg3['subjects'] ?? [] as $s) {
    if (strpos($s['id'], 'psr_') === 0) {
        $allKg3 = false;
    }
}
assertCheck($allKg3, "ALL subjects returned for KG3 student are KG3 subjects (Zero PSR subjects)");

// D. Mixed Quiz Mode Isolation for Year 6
$quizJsonPsr = runApiSubprocess(realpath(__DIR__ . '/../api/quiz.php'), ['mode' => 'mixed'], [
    'student_grade' => 'Year 6 (PSR Brunei)'
]);

assertCheck(!empty($quizJsonPsr['success']) && $quizJsonPsr['success'] === true, "api/quiz.php returns mixed quiz for Year 6 student");
assertCheck(count($quizJsonPsr['questions'] ?? []) === 10, "Mixed quiz provides exactly 10 questions for Year 6");
$allQuestionsArePsr = true;
foreach ($quizJsonPsr['questions'] ?? [] as $q) {
    if (strpos($q['topic_id'], 'psr_') !== 0) {
        $allQuestionsArePsr = false;
    }
}
assertCheck($allQuestionsArePsr, "10/10 questions in mixed quiz are authentic PSR questions (Zero KG3 questions)");

// E. Mixed Quiz Mode Isolation for KG3
$quizJsonKg3 = runApiSubprocess(realpath(__DIR__ . '/../api/quiz.php'), ['mode' => 'mixed'], [
    'student_grade' => 'Kindergarten 3 (KG3)'
]);

assertCheck(!empty($quizJsonKg3['success']) && $quizJsonKg3['success'] === true, "api/quiz.php returns mixed quiz for KG3 student");
$allQuestionsAreKg3 = true;
foreach ($quizJsonKg3['questions'] ?? [] as $q) {
    if (strpos($q['topic_id'], 'psr_') === 0) {
        $allQuestionsAreKg3 = false;
    }
}
assertCheck($allQuestionsAreKg3, "10/10 questions in mixed quiz are KG3 questions (Zero PSR questions)");

// F. Cross-grade Topic Access Blocking (KG3 tries to access PSR topic)
$crossJson = runApiSubprocess(realpath(__DIR__ . '/../api/quiz.php'), ['topic_id' => 'psr_math_numbers'], [
    'student_grade' => 'Kindergarten 3 (KG3)'
]);
assertCheck(empty($crossJson['success']), "KG3 student blocked from accessing PSR topic ('psr_math_numbers')");

// G. Cross-grade Topic Access Blocking (Year 6 tries to access KG3 topic)
$crossJson2 = runApiSubprocess(realpath(__DIR__ . '/../api/quiz.php'), ['topic_id' => 'bm_suku_kata'], [
    'student_grade' => 'Year 6 (PSR Brunei)'
]);
assertCheck(empty($crossJson2['success']), "Year 6 student blocked from accessing KG3 topic ('bm_suku_kata')");

// H. Cross-grade Worksheet Blocking (Year 6 tries to access KG3 worksheet)
$crossWs = runApiSubprocess(realpath(__DIR__ . '/../api/worksheet.php'), ['topic_id' => 'bm_suku_kata'], [
    'student_grade' => 'Year 6 (PSR Brunei)'
]);
assertCheck(empty($crossWs['success']), "Year 6 student blocked from accessing KG3 worksheet ('bm_suku_kata')");

// I. Year 6 Worksheet Access for PSR Topic
$psrWs = runApiSubprocess(realpath(__DIR__ . '/../api/worksheet.php'), ['topic_id' => 'psr_math_numbers'], [
    'student_grade' => 'Year 6 (PSR Brunei)'
]);
assertCheck(!empty($psrWs['success']) && count($psrWs['questions'] ?? []) === 10, "Year 6 student accesses valid PSR worksheet with 10 questions");

// -------------------------------------------------------------
// 5. Revision Guides for PSR Topics
// -------------------------------------------------------------
echo "\n--- 5. Testing 5-Minute Revisions for PSR Topics ---\n";
$stmtRev = $pdo->query("SELECT COUNT(*) FROM revisions WHERE topic_id LIKE 'psr_%'");
$psrRevCount = (int)$stmtRev->fetchColumn();
assertCheck($psrRevCount === 21, "All 21 Year 6 PSR topics have interactive 5-minute revision modules", "Found $psrRevCount");

// -------------------------------------------------------------
// Summary
// -------------------------------------------------------------
echo "\n=========================================================\n";
echo "   Test Results: $passCount Passed, $failCount Failed\n";
echo "=========================================================\n";

if ($failCount === 0) {
    echo "🎉 ALL YEAR 6 PSR REQUIREMENTS VERIFIED AND PASSED!\n\n";
    exit(0);
} else {
    echo "⚠️ SOME PSR TESTS FAILED!\n\n";
    exit(1);
}
