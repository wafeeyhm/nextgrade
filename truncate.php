<?php
// NextGrade - Database Truncate Utility
// Safely empties all tables in nextgrade_db

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth_helper.php';

// Strict Access Guard: Only CLI or authenticated System Administrator
if (php_sapi_name() !== 'cli') {
    requireAdmin();
}

header('Content-Type: text/plain; charset=utf-8');
echo "=== NextGrade Truncate Process Started ===\n\n";

try {
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
    
    $tables = [
        'quiz_session_answers',
        'quiz_sessions',
        'questions',
        'revisions',
        'topics',
        'subjects',
        'students'
    ];

    foreach ($tables as $table) {
        $pdo->exec("TRUNCATE TABLE `$table`;");
        echo "✓ Truncated table: $table\n";
    }

    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");

    echo "\n💥 ALL TABLES TRUNCATED SUCCESSFULLY!\n";
    echo "Every record in the database has been deleted.\n";
    echo "To restore data, run seed.php or click 'Sync DB' in guide.php.\n";

} catch (Exception $e) {
    echo "❌ Truncate Failed: " . $e->getMessage() . "\n";
}
