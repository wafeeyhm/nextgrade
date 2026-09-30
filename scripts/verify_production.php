<?php
// NextGrade - Production Verification CLI Runner
// Usage: php scripts/verify_production.php

require_once __DIR__ . '/../admin/verification_engine.php';

echo "\n" . str_repeat('=', 70) . "\n";
echo "   NextGrade Production Readiness & System Verification Suite\n";
echo str_repeat('=', 70) . "\n\n";

$engine = new SystemVerificationEngine($pdo);
$report = $engine->runAllChecks();

$statusIcons = [
    'pass' => "\033[32m[PASS]\033[0m",
    'warning' => "\033[33m[WARN]\033[0m",
    'fail' => "\033[31m[FAIL]\033[0m"
];

$plainIcons = [
    'pass' => '[PASS]',
    'warning' => '[WARN]',
    'fail' => '[FAIL]'
];

// Determine if ANSI color is supported
$useColor = (DIRECTORY_SEPARATOR === '/' || getenv('ANSICON') !== false || getenv('ConEmuANSI') === 'ON' || strpos(getenv('TERM') ?: '', 'xterm') !== false);
$icons = $useColor ? $statusIcons : $plainIcons;

foreach ($report['categories'] as $catKey => $category) {
    echo "--- " . strtoupper($category['title']) . " ---\n";
    foreach ($category['checks'] as $check) {
        $icon = $icons[$check['status']] ?? '[INFO]';
        echo sprintf("  %s %-45s\n       %s\n", $icon, $check['name'], $check['message']);
        if ($check['status'] === 'fail' && !empty($check['details'])) {
            echo "       DETAILS: " . json_encode($check['details'], JSON_UNESCAPED_SLASHES) . "\n";
        }
    }
    echo "\n";
}

echo str_repeat('=', 70) . "\n";
echo sprintf("   HEALTH SCORE: %d%% | Total: %d | Passed: %d | Warnings: %d | Failed: %d\n",
    $report['health_score'],
    $report['summary']['total'],
    $report['summary']['passed'],
    $report['summary']['warnings'],
    $report['summary']['failed']
);
echo sprintf("   Scan Time: %s ms | Timestamp: %s\n", $report['execution_time_ms'], $report['timestamp']);
echo str_repeat('=', 70) . "\n";

if ($report['summary']['failed'] === 0) {
    echo "\n🎉 SYSTEM VERIFICATION COMPLETED WITH ZERO CRITICAL FAILURES!\n";
    if ($report['summary']['warnings'] > 0) {
        echo "💡 Note: " . $report['summary']['warnings'] . " warning(s) listed above for production online readiness.\n\n";
    }
    exit(0);
} else {
    echo "\n❌ SYSTEM VERIFICATION FAILED: " . $report['summary']['failed'] . " critical issue(s) detected!\n\n";
    exit(1);
}
