<?php
// NextGrade - Database Migration v2 (Multi-parent, Kids CRUD, System Admin)
require_once __DIR__ . '/../db.php';

echo "Running Migration v2...\n";

try {
    // 1. Create Admins Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `admins` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `username` VARCHAR(50) UNIQUE NOT NULL,
            `password_hash` VARCHAR(255) NOT NULL,
            `full_name` VARCHAR(100) NOT NULL,
            `email` VARCHAR(100) UNIQUE NOT NULL,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `last_login` DATETIME DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    echo "✓ admins table ready.\n";

    // 2. Create Parents Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `parents` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `parent_code` VARCHAR(30) UNIQUE NOT NULL,
            `username` VARCHAR(50) UNIQUE NOT NULL,
            `password_hash` VARCHAR(255) NOT NULL,
            `full_name` VARCHAR(100) NOT NULL,
            `email` VARCHAR(100) UNIQUE NOT NULL,
            `phone` VARCHAR(30) DEFAULT NULL,
            `status` ENUM('active', 'inactive') DEFAULT 'active',
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `last_login` DATETIME DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    echo "✓ parents table ready.\n";

    // 3. Add columns to students table if not exists
    $cols = $pdo->query("SHOW COLUMNS FROM students")->fetchAll(PDO::FETCH_COLUMN);

    if (!in_array('parent_id', $cols)) {
        $pdo->exec("ALTER TABLE `students` ADD COLUMN `parent_id` INT DEFAULT NULL AFTER `id`, ADD INDEX `idx_student_parent` (`parent_id`)");
        echo "✓ Added parent_id to students.\n";
    }
    if (!in_array('username', $cols)) {
        $pdo->exec("ALTER TABLE `students` ADD COLUMN `username` VARCHAR(50) DEFAULT NULL AFTER `name`, ADD UNIQUE INDEX `idx_student_username` (`username`)");
        echo "✓ Added username to students.\n";
    }
    if (!in_array('pin_code', $cols)) {
        $pdo->exec("ALTER TABLE `students` ADD COLUMN `pin_code` VARCHAR(20) DEFAULT '1234' AFTER `username`");
        echo "✓ Added pin_code to students.\n";
    }
    if (!in_array('grade_level', $cols)) {
        $pdo->exec("ALTER TABLE `students` ADD COLUMN `grade_level` VARCHAR(50) DEFAULT 'Year 1' AFTER `avatar`");
        echo "✓ Added grade_level to students.\n";
    }
    if (!in_array('status', $cols)) {
        $pdo->exec("ALTER TABLE `students` ADD COLUMN `status` ENUM('active', 'inactive') DEFAULT 'active' AFTER `grade_level`");
        echo "✓ Added status to students.\n";
    }

    // 4. Create Revision Sessions Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `revision_sessions` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `student_id` INT NOT NULL,
            `student_name` VARCHAR(100) NOT NULL,
            `topic_id` VARCHAR(50) NOT NULL,
            `subject_id` VARCHAR(50) NOT NULL,
            `time_spent_seconds` INT DEFAULT 0,
            `completed_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX `idx_rev_student` (`student_id`),
            INDEX `idx_rev_topic` (`topic_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    echo "✓ revision_sessions table ready.\n";

    // 5. Seed System Admin
    $adminCount = $pdo->query("SELECT COUNT(*) FROM admins")->fetchColumn();
    if ($adminCount == 0) {
        $stmt = $pdo->prepare("
            INSERT INTO admins (username, password_hash, full_name, email)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([
            'admin',
            password_hash('admin123', PASSWORD_DEFAULT),
            'System Administrator',
            'admin@nextgrade.edu'
        ]);
        echo "✓ Seeded admin user: admin / admin123\n";
    }

    // 6. Seed Demo Parents
    $parentCount = $pdo->query("SELECT COUNT(*) FROM parents")->fetchColumn();
    if ($parentCount == 0) {
        $stmtP = $pdo->prepare("
            INSERT INTO parents (parent_code, username, password_hash, full_name, email, phone, status)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmtP->execute([
            'PAR-1001',
            'parent',
            password_hash('parent123', PASSWORD_DEFAULT),
            'Puan Sarah Ahmad',
            'sarah.ahmad@example.com',
            '+60123456789',
            'active'
        ]);
        $sarahId = $pdo->lastInsertId();

        $stmtP->execute([
            'PAR-1002',
            'azman',
            password_hash('parent123', PASSWORD_DEFAULT),
            'Encik Azman Ismail',
            'azman.ismail@example.com',
            '+60198765432',
            'active'
        ]);
        $azmanId = $pdo->lastInsertId();
        echo "✓ Seeded demo parents: parent / parent123 (Sarah), azman / parent123 (Azman)\n";

        // Link existing student 1 to Sarah
        $pdo->prepare("UPDATE students SET parent_id = ?, username = 'lana', pin_code = '1234', grade_level = 'Kindergarten 3 (KG3)' WHERE id = 1")->execute([$sarahId]);
        echo "✓ Linked student Lana marissa to parent Sarah.\n";

        // Add second child for Sarah
        $stmtK = $pdo->prepare("
            INSERT INTO students (parent_id, name, username, pin_code, avatar, grade_level, status)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmtK->execute([$sarahId, 'Adam Rayyan', 'adam', '1234', 'astronaut', 'Kindergarten 3 (KG3)', 'active']);
        $adamId = $pdo->lastInsertId();
        echo "✓ Added child Adam Rayyan for parent Sarah.\n";

        // Add child for Azman
        $stmtK->execute([$azmanId, 'Danish Hakimi', 'danish', '1234', 'dino', 'Year 2', 'active']);
        $danishId = $pdo->lastInsertId();
        echo "✓ Added child Danish Hakimi for parent Azman.\n";

        // Seed some sample quiz sessions for Adam and Danish so parents have rich insights to view
        $sampleTopics = [
            ['maths', 'math_addition', 8, 80],
            ['english', 'eng_sight_words', 10, 100],
            ['bahasa_melayu', 'bm_suku_kata', 6, 60],
            ['science', 'sci_animals', 7, 70]
        ];

        foreach ($sampleTopics as $tp) {
            $stmtSess = $pdo->prepare("
                INSERT INTO quiz_sessions (student_id, student_name, subject_id, topic_id, total_questions, score, percentage, time_spent_seconds, completed_at)
                VALUES (?, ?, ?, ?, 10, ?, ?, ?, NOW() - INTERVAL FLOOR(RAND()*5) DAY)
            ");
            $stmtSess->execute([$adamId, 'Adam Rayyan', $tp[0], $tp[1], $tp[2], $tp[3], rand(80, 180)]);
            $sessId = $pdo->lastInsertId();

            // Insert 10 answers for each session
            $questions = $pdo->query("SELECT id, correct_answer FROM questions WHERE topic_id = '{$tp[1]}' LIMIT 10")->fetchAll();
            $stmtAns = $pdo->prepare("
                INSERT INTO quiz_session_answers (session_id, question_id, student_answer, is_correct, used_hint, created_at)
                VALUES (?, ?, ?, ?, ?, NOW())
            ");
            $curCorrect = 0;
            foreach ($questions as $idx => $q) {
                $isCorrect = ($curCorrect < $tp[2]) ? 1 : 0;
                if ($isCorrect) $curCorrect++;
                $ansText = $isCorrect ? $q['correct_answer'] : 'Wrong choice';
                $stmtAns->execute([$sessId, $q['id'], $ansText, $isCorrect, rand(0, 1)]);
            }
        }
        echo "✓ Seeded realistic quiz sessions and answers for multiple kids.\n";
    }

    echo "\n🎉 Migration completed successfully!\n";

} catch (Exception $e) {
    echo "ERROR during migration: " . $e->getMessage() . "\n";
    exit(1);
}
