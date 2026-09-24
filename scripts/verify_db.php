<?php
require_once __DIR__ . '/../db.php';

echo "=== Verifying Database State ===\n";

$tot = $pdo->query('SELECT COUNT(*) FROM questions')->fetchColumn();
echo "Total Questions in DB: $tot\n\n";

$res = $pdo->query('SELECT topic_id, COUNT(*) as cnt FROM questions GROUP BY topic_id ORDER BY topic_id')->fetchAll(PDO::FETCH_KEY_PAIR);
$allValid = true;
foreach ($res as $topic => $cnt) {
    $status = ($cnt >= 50 && $cnt <= 90) ? '✓ VALID' : '❌ INVALID';
    if ($cnt < 50 || $cnt > 90) $allValid = false;
    printf("  %-22s: %2d questions  %s\n", $topic, $cnt, $status);
}

$svgCnt = $pdo->query("SELECT COUNT(*) FROM questions WHERE image_url LIKE '%.svg%'")->fetchColumn();
echo "\nSVG Questions in DB: $svgCnt\n";

$imgCnt = $pdo->query("SELECT COUNT(DISTINCT image_url) FROM questions WHERE image_url IS NOT NULL")->fetchColumn();
echo "Distinct Images Referenced: $imgCnt\n";

$missingOnDisk = 0;
$imgs = $pdo->query("SELECT DISTINCT image_url FROM questions WHERE image_url IS NOT NULL")->fetchAll(PDO::FETCH_COLUMN);
foreach ($imgs as $img) {
    if (!file_exists(__DIR__ . '/../' . $img)) {
        echo "MISSING FILE: $img\n";
        $missingOnDisk++;
    }
}
echo "Missing Images on Disk: $missingOnDisk\n";

if ($allValid && $svgCnt === 0 && $missingOnDisk === 0 && $tot >= 1350) {
    echo "\n🎉 ALL VERIFICATIONS PASSED PERFECTLY!\n";
} else {
    echo "\n⚠️ SOME CHECKS NEED ATTENTION!\n";
}

echo "\n--- Testing API Endpoints via cURL ---\n";
$ch = curl_init('http://localhost/nextgrade/api/quiz.php?topic_id=ict_parts');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$resp = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
if ($httpCode === 200) {
    $quizData = json_decode($resp, true);
    $qList = $quizData['questions'] ?? [];
    echo "Quiz API (ict_parts): HTTP 200, " . count($qList) . " session questions loaded.\n";
    if (!empty($qList)) {
        echo "  Sample Question: " . $qList[0]['question_text'] . "\n";
        echo "  Sample Image:    " . ($qList[0]['image_url'] ?? 'none') . "\n";
    }
}
$ch2 = curl_init('http://localhost/nextgrade/api/worksheet.php?topic_id=sci_plants');
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
$resp2 = curl_exec($ch2);
$httpCode2 = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
if ($httpCode2 === 200) {
    $wsData = json_decode($resp2, true);
    $wsQ = $wsData['questions'] ?? [];
    echo "Worksheet API (sci_plants): HTTP 200, " . count($wsQ) . " printable questions loaded.\n";
    if (!empty($wsQ)) {
        echo "  Sample WS Question: " . $wsQ[0]['question_text'] . "\n";
        echo "  Sample WS Image:    " . ($wsQ[0]['image_url'] ?? 'none') . "\n";
    }
}


