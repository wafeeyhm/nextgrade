<?php
// Migration for Improvement 8: Add status column to questions table
require_once __DIR__ . '/../db.php';

echo "Running Migration for Improvement 8...\n";

try {
    // 1. Check if 'status' column exists in 'questions' table
    $stmt = $pdo->query("SHOW COLUMNS FROM `questions` LIKE 'status'");
    $exists = $stmt->fetch();

    if (!$exists) {
        echo "Adding 'status' column to questions table...\n";
        $pdo->exec("ALTER TABLE `questions` ADD COLUMN `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active' AFTER `grade_level`");
        echo "Adding index idx_q_status...\n";
        $pdo->exec("ALTER TABLE `questions` ADD INDEX `idx_q_status` (`status`)");
        echo "Successfully added 'status' column to questions table.\n";
    } else {
        echo "'status' column already exists in questions table.\n";
    }

    // Verify questions count
    $totalQ = $pdo->query("SELECT COUNT(*) FROM questions")->fetchColumn();
    $activeQ = $pdo->query("SELECT COUNT(*) FROM questions WHERE status = 'active'")->fetchColumn();
    $inactiveQ = $pdo->query("SELECT COUNT(*) FROM questions WHERE status = 'inactive'")->fetchColumn();

    echo "Questions verification: Total = $totalQ, Active = $activeQ, Inactive = $inactiveQ\n";
    echo "Migration completed successfully!\n";

} catch (Exception $e) {
    echo "Migration error: " . $e->getMessage() . "\n";
    exit(1);
}
