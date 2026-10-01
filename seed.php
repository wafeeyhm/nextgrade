<?php
// NextGrade - System Admin Curriculum & Question Seeder
// Supports selective seeding: Kindergarten 3 (KG3) using data/questions_bank.json,
// Year 6 (PSR Brunei) using data/questions_bank_psr.json, or All Curricula.

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth_helper.php';

// Strict Access Guard: Only CLI or authenticated System Administrator
if (php_sapi_name() !== 'cli') {
    requireAdmin();
}

// Determine target curriculum: CLI argument, GET/POST parameter, or default 'all' for automated CLI tests
$isCli = (php_sapi_name() === 'cli');
$target = null;

if ($isCli) {
    // Parse CLI arguments: --target=kg3 | --target=psr | --target=all | kg3 | psr | all
    foreach ($argv as $arg) {
        if (str_starts_with($arg, '--target=')) {
            $target = strtolower(substr($arg, 9));
        } elseif (in_array(strtolower($arg), ['kg3', 'psr', 'all', 'truncate'])) {
            $target = strtolower($arg);
        }
    }
    if (!$target) {
        $target = 'all'; // Default to all in CLI mode for backward-compatibility with tests
    }
} else {
    // Web request: check if executing action
    $target = $_POST['target'] ?? $_GET['target'] ?? null;
    if (isset($_GET['action']) && $_GET['action'] === 'truncate') {
        $target = 'truncate';
    }
}

// Function to ensure database schema columns exist
function ensureSchemaColumns($pdo) {
    $tables = ['subjects', 'topics', 'questions'];
    foreach ($tables as $t) {
        $cols = $pdo->query("SHOW COLUMNS FROM `$t` LIKE 'grade_level'")->fetchAll();
        if (empty($cols)) {
            $pdo->exec("ALTER TABLE `$t` ADD COLUMN `grade_level` VARCHAR(50) DEFAULT 'Kindergarten 3 (KG3)'");
        }
    }
}

// Function to seed Kindergarten 3 (KG3) from data/questions_bank.json
function seedKG3Curriculum($pdo) {
    echo "=====================================================\n";
    echo "   SEEDING KINDERGARTEN 3 (KG3) CURRICULUM\n";
    echo "   Source: data/questions_bank.json\n";
    echo "=====================================================\n\n";

    ensureSchemaColumns($pdo);

    // 1. Subjects (KG3)
    $subjects = [
        [
            'id' => 'bahasa_melayu',
            'name' => 'Bahasa Melayu',
            'title_native' => 'Bahasa Melayu',
            'description' => 'Suku kata, kenderaan, haiwan, bulan & tatabahasa asas.',
            'icon' => '📚',
            'theme_gradient' => 'from-emerald-400 to-teal-600',
            'accent_color' => '#10B981',
            'sort_order' => 1
        ],
        [
            'id' => 'maths',
            'name' => 'Mathematics',
            'title_native' => 'Mathematics',
            'description' => 'Analog clocks & time, descending numbers, addition & subtraction.',
            'icon' => '🔢',
            'theme_gradient' => 'from-amber-400 to-orange-500',
            'accent_color' => '#F59E0B',
            'sort_order' => 2
        ],
        [
            'id' => 'english',
            'name' => 'English',
            'title_native' => 'English Language',
            'description' => 'Phonics, pronouns, articles, demonstratives & reading comprehension.',
            'icon' => '🔤',
            'theme_gradient' => 'from-sky-400 to-blue-600',
            'accent_color' => '#0EA5E9',
            'sort_order' => 3
        ],
        [
            'id' => 'science',
            'name' => 'Science',
            'title_native' => 'Early Science',
            'description' => 'Animal habitats, sink or float, materials, astronomy & plants.',
            'icon' => '🔬',
            'theme_gradient' => 'from-purple-400 to-indigo-600',
            'accent_color' => '#8B5CF6',
            'sort_order' => 4
        ],
        [
            'id' => 'ict',
            'name' => 'ICT & Computer',
            'title_native' => 'Computer Technology',
            'description' => 'Computer parts, storage drives, peripheral spelling & Input/Output.',
            'icon' => '💻',
            'theme_gradient' => 'from-rose-400 to-pink-600',
            'accent_color' => '#EC4899',
            'sort_order' => 5
        ]
    ];

    $stmtSubject = $pdo->prepare("
        INSERT INTO subjects (id, name, title_native, description, icon, theme_gradient, accent_color, grade_level, sort_order)
        VALUES (?, ?, ?, ?, ?, ?, ?, 'Kindergarten 3 (KG3)', ?)
        ON DUPLICATE KEY UPDATE 
            name = VALUES(name),
            title_native = VALUES(title_native),
            description = VALUES(description),
            icon = VALUES(icon),
            theme_gradient = VALUES(theme_gradient),
            accent_color = VALUES(accent_color),
            grade_level = VALUES(grade_level),
            sort_order = VALUES(sort_order)
    ");
    foreach ($subjects as $s) {
        $stmtSubject->execute([$s['id'], $s['name'], $s['title_native'], $s['description'], $s['icon'], $s['theme_gradient'], $s['accent_color'], $s['sort_order']]);
    }
    echo "✓ Seeded 5 KG3 Subjects.\n";

    // 2. Topics (KG3)
    $topics = [
        // Bahasa Melayu
        ['id' => 'bm_bulan', 'subject_id' => 'bahasa_melayu', 'name' => '12 Bulan dalam Setahun', 'name_native' => '12 Bulan dalam Setahun', 'description' => 'Mengecam dan menyusun nama 12 bulan mengikut turutan yang betul.', 'icon' => '📅', 'color_badge' => 'bg-emerald-100 text-emerald-800', 'sort_order' => 1],
        ['id' => 'bm_suku_kata', 'subject_id' => 'bahasa_melayu', 'name' => 'Pecahkan Suku Kata', 'name_native' => 'Pecahkan Perkataan kepada Suku Kata', 'description' => 'Membina perkataan mudah melalui cantuman dua suku kata terbuka.', 'icon' => '🧩', 'color_badge' => 'bg-teal-100 text-teal-800', 'sort_order' => 2],
        ['id' => 'bm_kenderaan', 'subject_id' => 'bahasa_melayu', 'name' => 'Kenderaan Darat, Air & Udara', 'name_native' => 'Kenderaan Darat, Air dan Udara', 'description' => 'Kenal pasti jenis kenderaan dan laluan pergerakannya.', 'icon' => '🚗', 'color_badge' => 'bg-cyan-100 text-cyan-800', 'sort_order' => 3],
        ['id' => 'bm_binatang', 'subject_id' => 'bahasa_melayu', 'name' => 'Haiwan 2 Kaki & 4 Kaki', 'name_native' => 'Haiwan Berkaki 2 dan Berkaki 4', 'description' => 'Mengelaskan pelbagai haiwan mengikut bilangan kakinya.', 'icon' => '🐾', 'color_badge' => 'bg-green-100 text-green-800', 'sort_order' => 4],
        ['id' => 'bm_ini_itu', 'subject_id' => 'bahasa_melayu', 'name' => 'Kata Tunjuk: Ini & Itu', 'name_native' => 'Penggunaan Kata Tunjuk Ini dan Itu', 'description' => 'Memahami perbezaan jarak dekat (Ini) dan jarak jauh (Itu).', 'icon' => '👉', 'color_badge' => 'bg-lime-100 text-lime-800', 'sort_order' => 5],

        // Maths
        ['id' => 'math_clocks', 'subject_id' => 'maths', 'name' => 'Analog Clocks & Time', 'name_native' => 'Reading Clock Numbers & Hands', 'description' => 'Learn the hour hand, minute hand, and how to read the clock.', 'icon' => '🕒', 'color_badge' => 'bg-amber-100 text-amber-800', 'sort_order' => 1],
        ['id' => 'math_descending', 'subject_id' => 'maths', 'name' => 'Descending Numbers (20 to 1)', 'name_native' => 'Descending Numbers 20 to 1', 'description' => 'Count numbers from largest to smallest, from 20 down to 1.', 'icon' => '📉', 'color_badge' => 'bg-orange-100 text-orange-800', 'sort_order' => 2],
        ['id' => 'math_addition', 'subject_id' => 'maths', 'name' => 'Addition (Combining Numbers)', 'name_native' => 'Basic Addition with Pictures', 'description' => 'Count and add groups of fun objects together.', 'icon' => '➕', 'color_badge' => 'bg-yellow-100 text-yellow-800', 'sort_order' => 3],
        ['id' => 'math_subtraction', 'subject_id' => 'maths', 'name' => 'Subtraction (Taking Away)', 'name_native' => 'Basic Subtraction with Pictures', 'description' => 'Count what remains when items are removed or popped.', 'icon' => '➖', 'color_badge' => 'bg-red-100 text-red-800', 'sort_order' => 4],

        // English
        ['id' => 'eng_days_months', 'subject_id' => 'english', 'name' => 'Days of Week & Months', 'name_native' => 'Days of the Week and Month Numbers', 'description' => 'Learn the 7 days of the week in sequence and match months to their numbers.', 'icon' => '🗓️', 'color_badge' => 'bg-sky-100 text-sky-800', 'sort_order' => 1],
        ['id' => 'eng_blending', 'subject_id' => 'english', 'name' => 'Beginning Blends (ch- & th-)', 'name_native' => 'Beginning Blending Sound Box', 'description' => 'Recognise and match beginning blend sounds: ch- (chair) and th- (thorn).', 'icon' => '🗣️', 'color_badge' => 'bg-blue-100 text-blue-800', 'sort_order' => 2],
        ['id' => 'eng_pronouns', 'subject_id' => 'english', 'name' => 'Pronouns (He, She, It, They)', 'name_native' => 'Personal Pronouns', 'description' => 'Choose the correct pronoun for boys, girls, objects, and groups.', 'icon' => '👥', 'color_badge' => 'bg-indigo-100 text-indigo-800', 'sort_order' => 3],
        ['id' => 'eng_articles', 'subject_id' => 'english', 'name' => 'Articles: A and An', 'name_native' => 'Articles A or An', 'description' => 'Use "an" before vowel sounds (a, e, i, o, u) and "a" before consonants.', 'icon' => '🔤', 'color_badge' => 'bg-violet-100 text-violet-800', 'sort_order' => 4],
        ['id' => 'eng_has_have', 'subject_id' => 'english', 'name' => 'Using Has and Have', 'name_native' => 'Has vs Have Rules', 'description' => 'He/She/It uses "has", while I/We/They uses "have".', 'icon' => '🤲', 'color_badge' => 'bg-fuchsia-100 text-fuchsia-800', 'sort_order' => 5],
        ['id' => 'eng_demonstratives', 'subject_id' => 'english', 'name' => 'This, That, These, Those', 'name_native' => 'Demonstrative Pronouns', 'description' => 'Master near vs far, singular vs plural pointer words.', 'icon' => '👉', 'color_badge' => 'bg-purple-100 text-purple-800', 'sort_order' => 6],
        ['id' => 'eng_comprehension', 'subject_id' => 'english', 'name' => 'Reading Comprehension', 'name_native' => 'Short Stories: Troy & Andy', 'description' => 'Read sweet passages, understand context, and answer smart questions.', 'icon' => '📖', 'color_badge' => 'bg-sky-100 text-sky-800', 'sort_order' => 7],

        // Science
        ['id' => 'sci_land_sea', 'subject_id' => 'science', 'name' => 'Land vs Sea Animals', 'name_native' => 'Land and Sea Animals Habitat', 'description' => 'Identify whether animals live on land or in the ocean.', 'icon' => '🐬', 'color_badge' => 'bg-emerald-100 text-emerald-800', 'sort_order' => 1],
        ['id' => 'sci_sink_float', 'subject_id' => 'science', 'name' => 'Sink or Float', 'name_native' => 'Objects that Sink or Float', 'description' => 'Discover which objects sink to the bottom or float on water.', 'icon' => '⚓', 'color_badge' => 'bg-cyan-100 text-cyan-800', 'sort_order' => 2],
        ['id' => 'sci_celestial', 'subject_id' => 'science', 'name' => 'Sun, Moon, Star & Earth', 'name_native' => 'Sun, Moon, Star, and Earth', 'description' => 'Learn about the celestial bodies in our sky and our home planet Earth.', 'icon' => '🌍', 'color_badge' => 'bg-amber-100 text-amber-800', 'sort_order' => 3],
        ['id' => 'sci_materials', 'subject_id' => 'science', 'name' => 'Materials: Metal, Glass & Paper', 'name_native' => 'Objects Made of Metal, Glass & Paper', 'description' => 'Identify everyday objects made of metal, transparent glass, or paper.', 'icon' => '🪨', 'color_badge' => 'bg-stone-100 text-stone-800', 'sort_order' => 4],
        ['id' => 'sci_pollution', 'subject_id' => 'science', 'name' => 'Types of Pollution', 'name_native' => 'Types of Pollutions', 'description' => 'Learn about air pollution, water/sea pollution, and land pollution.', 'icon' => '🏭', 'color_badge' => 'bg-red-100 text-red-800', 'sort_order' => 5],
        ['id' => 'sci_plants', 'subject_id' => 'science', 'name' => 'Parts & Needs of a Plant', 'name_native' => 'Parts of a Plant & Needs to Grow', 'description' => 'Identify roots, stem, leaves, flower, fruit and sunlight, air, water.', 'icon' => '🌱', 'color_badge' => 'bg-green-100 text-green-800', 'sort_order' => 6],

        // ICT
        ['id' => 'ict_storage', 'subject_id' => 'ict', 'name' => 'Computer Drives & Storage', 'name_native' => 'Computer Drives and Storage', 'description' => 'Learn about Hard disk drives, floppy disks, CD-ROMs, memory cards, and USB pendrives.', 'icon' => '💾', 'color_badge' => 'bg-rose-100 text-rose-800', 'sort_order' => 1],
        ['id' => 'ict_parts', 'subject_id' => 'ict', 'name' => 'All About Computer Parts', 'name_native' => 'All About Computer Peripherals', 'description' => 'Identify monitors, keyboards, printers, headphones, scanners, and system units.', 'icon' => '🖥️', 'color_badge' => 'bg-pink-100 text-pink-800', 'sort_order' => 2],
        ['id' => 'ict_counting', 'subject_id' => 'ict', 'name' => 'Count Computer Peripherals', 'name_native' => 'Count Computer Peripherals', 'description' => 'Count and determine the correct number of computer devices.', 'icon' => '🔢', 'color_badge' => 'bg-indigo-100 text-indigo-800', 'sort_order' => 3],
        ['id' => 'ict_spelling', 'subject_id' => 'ict', 'name' => 'Fill in the Missing Letters', 'name_native' => 'Fill in the Missing Letters', 'description' => 'Complete the missing letters for computer peripheral names.', 'icon' => '🔤', 'color_badge' => 'bg-purple-100 text-purple-800', 'sort_order' => 4],
        ['id' => 'ict_input_output', 'subject_id' => 'ict', 'name' => 'Input (I) vs Output (O) Devices', 'name_native' => 'Input vs Output Devices', 'description' => 'Recognise whether a device feeds data in (Input) or presents results (Output).', 'icon' => '🔌', 'color_badge' => 'bg-blue-100 text-blue-800', 'sort_order' => 5],
    ];

    $stmtTopic = $pdo->prepare("
        INSERT INTO topics (id, subject_id, name, name_native, description, icon, color_badge, revision_time_limit, grade_level, sort_order)
        VALUES (?, ?, ?, ?, ?, ?, ?, 300, 'Kindergarten 3 (KG3)', ?)
        ON DUPLICATE KEY UPDATE
            subject_id = VALUES(subject_id),
            name = VALUES(name),
            name_native = VALUES(name_native),
            description = VALUES(description),
            icon = VALUES(icon),
            color_badge = VALUES(color_badge),
            grade_level = VALUES(grade_level),
            sort_order = VALUES(sort_order)
    ");
    foreach ($topics as $t) {
        $stmtTopic->execute([$t['id'], $t['subject_id'], $t['name'], $t['name_native'], $t['description'], $t['icon'], $t['color_badge'], $t['sort_order']]);
    }
    echo "✓ Seeded " . count($topics) . " KG3 Topics.\n";

    // 3. Questions Bank (from data/questions_bank.json)
    $bankFile = __DIR__ . '/data/questions_bank.json';
    if (!file_exists($bankFile)) {
        throw new Exception("Questions bank file not found: $bankFile");
    }

    $questions = json_decode(file_get_contents($bankFile), true);
    if (!$questions || !is_array($questions)) {
        throw new Exception("Invalid JSON format in: $bankFile");
    }

    // Clear existing KG3 questions to avoid duplicates (leaves PSR questions safe)
    $psrCount = (int)$pdo->query("SELECT COUNT(*) FROM questions WHERE grade_level = 'Year 6 (PSR)' OR topic_id LIKE 'psr_%'")->fetchColumn();
    if ($psrCount === 0) {
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
        $pdo->exec("TRUNCATE TABLE questions;");
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");
    } else {
        $pdo->exec("DELETE FROM questions WHERE grade_level = 'Kindergarten 3 (KG3)' OR grade_level IS NULL OR topic_id NOT LIKE 'psr_%'");
    }
    echo "✓ Cleaned existing KG3 questions.\n";

    $pdo->beginTransaction();
    $stmtQ = $pdo->prepare("INSERT INTO questions (topic_id, subject_id, question_text, question_audio, lang, question_type, image_url, passage, options_json, correct_answer, hint_text, hint_audio, grade_level, meta_data_json) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Kindergarten 3 (KG3)', ?)");
    
    foreach ($questions as $q) {
        $stmtQ->execute([
            $q['topic_id'],
            $q['subject_id'],
            $q['question_text'],
            $q['question_audio'] ?? null,
            $q['lang'] ?? 'en',
            $q['question_type'] ?? 'multiple_choice',
            $q['image_url'] ?? null,
            $q['passage'] ?? null,
            is_array($q['options_json']) ? json_encode($q['options_json'], JSON_UNESCAPED_UNICODE) : $q['options_json'],
            $q['correct_answer'],
            $q['hint_text'] ?? null,
            $q['hint_audio'] ?? null,
            is_array($q['meta_data_json'] ?? null) ? json_encode($q['meta_data_json'], JSON_UNESCAPED_UNICODE) : ($q['meta_data_json'] ?? null)
        ]);
    }
    $pdo->commit();
    echo "✓ Seeded " . count($questions) . " KG3 Questions across all " . count($topics) . " Topics.\n";

    // 4. Dynamic 5-minute Revision Guides for KG3
    $pdo->exec("DELETE FROM revisions WHERE topic_id NOT LIKE 'psr_%'");
    $questionsByTopic = [];
    foreach ($questions as $q) {
        $questionsByTopic[$q['topic_id']][] = $q;
    }

    $stmtRev = $pdo->prepare("INSERT INTO revisions (topic_id, title, summary, content_json) VALUES (?, ?, ?, ?)");
    $revisionCount = 0;

    foreach ($topics as $t) {
        $tId = $t['id'];
        $topicQuestions = $questionsByTopic[$tId] ?? [];
        $totalTQ = count($topicQuestions);

        $isMalay = ($t['subject_id'] === 'bahasa_melayu');
        $revTitle = $isMalay ? ("Ulang Kaji: " . $t['name']) : ("Revision: " . $t['name']);
        $revSummary = $t['description'];

        $cards = [];
        $sampleCount = min(5, $totalTQ);

        if ($sampleCount > 0) {
            $indices = [];
            if ($sampleCount === 1) {
                $indices = [0];
            } else {
                for ($i = 0; $i < $sampleCount; $i++) {
                    $indices[] = (int)round($i * ($totalTQ - 1) / ($sampleCount - 1));
                }
            }

            foreach ($indices as $cardIdx => $qIdx) {
                $q = $topicQuestions[$qIdx];
                $rawAns = $q['correct_answer'];
                $decodedAns = json_decode($rawAns, true);
                $ansText = is_array($decodedAns) ? implode(' ➔ ', $decodedAns) : $rawAns;
                $hintText = !empty($q['hint_text']) ? trim($q['hint_text']) : '';

                $explanation = $isMalay 
                    ? ("Jawapan: " . $ansText . ($hintText ? " • Tip: " . $hintText : ""))
                    : ("Answer: " . $ansText . ($hintText ? " • Hint: " . $hintText : ""));

                $cards[] = [
                    'title' => $q['question_text'],
                    'text' => $explanation,
                    'icon' => $t['icon'],
                    'image_url' => $q['image_url'] ?? null,
                    'question_text' => $q['question_text'],
                    'correct_answer' => $ansText,
                    'hint_text' => $hintText
                ];
            }
        }

        if (empty($cards)) {
            $cards[] = [
                'title' => $isMalay ? 'Konsep Asas' : 'Core Concept',
                'text' => $t['description'],
                'icon' => $t['icon'],
                'image_url' => null
            ];
        }

        $stmtRev->execute([$tId, $revTitle, $revSummary, json_encode($cards, JSON_UNESCAPED_UNICODE)]);
        $revisionCount++;
    }

    echo "✓ Seeded $revisionCount KG3 Revision Modules.\n";
    echo "\n🎉 KG3 CURRICULUM SEEDING COMPLETED SUCCESSFULLY!\n\n";

    return [
        'subjects' => count($subjects),
        'topics' => count($topics),
        'questions' => count($questions),
        'revisions' => $revisionCount
    ];
}

// Function to seed Year 6 Brunei PSR from data/questions_bank_psr.json
function seedPSRCurriculum($pdo) {
    echo "=====================================================\n";
    echo "   SEEDING YEAR 6 BRUNEI PSR CURRICULUM\n";
    echo "   Source: data/questions_bank_psr.json\n";
    echo "=====================================================\n\n";

    ensureSchemaColumns($pdo);

    require_once __DIR__ . '/scripts/seed_psr_curriculum.php';
}

// Function to truncate / reset all curriculum tables
function truncateCurriculum($pdo) {
    echo "=== Truncating Curriculum Tables ===\n";
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
    $tables = ['quiz_session_answers', 'quiz_sessions', 'questions', 'revisions', 'topics', 'subjects'];
    foreach ($tables as $t) {
        $pdo->exec("TRUNCATE TABLE `$t`;");
        echo "✓ Truncated: $t\n";
    }
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");
    echo "💥 All curriculum tables cleared.\n\n";
}

// Execute Seeder based on requested target
if ($target) {
    if (!$isCli && !headers_sent()) {
        header('Content-Type: text/plain; charset=utf-8');
    }

    try {
        if ($target === 'truncate') {
            truncateCurriculum($pdo);
        } elseif ($target === 'kg3') {
            seedKG3Curriculum($pdo);
        } elseif ($target === 'psr') {
            seedPSRCurriculum($pdo);
        } elseif ($target === 'all') {
            $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
            $pdo->exec("TRUNCATE TABLE questions;");
            $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");
            seedKG3Curriculum($pdo);
            seedPSRCurriculum($pdo);
            echo "=====================================================\n";
            echo "🎉 ALL CURRICULA (KG3 + PSR) FULLY SYNCHRONIZED!\n";
            echo "=====================================================\n";
        } else {
            echo "Unknown target '$target'. Valid targets: 'kg3', 'psr', 'all', 'truncate'.\n";
        }
    } catch (Exception $e) {
        echo "❌ Seeding Error: " . $e->getMessage() . "\n";
    }

    if ($isCli || isset($_GET['plain']) || isset($_POST['ajax'])) {
        exit;
    }
}

// If accessed in the browser without an immediate execution action, display the interactive Admin Seeder Web UI
$admin = getAdminUser();
$totalKG3Q = (int)$pdo->query("SELECT COUNT(*) FROM questions WHERE grade_level = 'Kindergarten 3 (KG3)' OR grade_level IS NULL")->fetchColumn();
$totalPSRQ = (int)$pdo->query("SELECT COUNT(*) FROM questions WHERE grade_level = 'Year 6 (PSR)' OR topic_id LIKE 'psr_%'")->fetchColumn();
$totalKG3Topics = (int)$pdo->query("SELECT COUNT(*) FROM topics WHERE grade_level = 'Kindergarten 3 (KG3)' OR grade_level IS NULL")->fetchColumn();
$totalPSRTopics = (int)$pdo->query("SELECT COUNT(*) FROM topics WHERE grade_level = 'Year 6 (PSR)' OR id LIKE 'psr_%'")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Curriculum Database Seeder - NextGrade System Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="css/app.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="icon" type="image/svg+xml" href="images/favicon.svg">
  <style>
    body, html {
      background-color: #0b0f19 !important;
      background-image: 
        radial-gradient(rgba(51, 65, 85, 0.4) 1.5px, transparent 1.5px), 
        radial-gradient(rgba(51, 65, 85, 0.4) 1.5px, #0b0f19 1.5px) !important;
      color: #f1f5f9 !important;
    }
  </style>
</head>
<body class="admin-dark min-h-screen bg-slate-900 text-slate-100 flex flex-col selection:bg-indigo-500 selection:text-white">

  <!-- Top Navigation Bar -->
  <header class="bg-slate-800/90 backdrop-blur-md border-b border-slate-700/80 sticky top-0 z-30 px-4 md:px-8 py-3.5">
    <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
      <div class="flex items-center gap-3">
        <a href="admin/index.php" class="flex items-center gap-2.5">
          <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white text-xl shadow-md shadow-indigo-600/30">
            🛡️
          </div>
          <div>
            <span class="text-lg font-black tracking-tight text-white block leading-none">
              Next<span class="text-indigo-400">Grade</span>
            </span>
            <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400">Curriculum Database Seeder</span>
          </div>
        </a>
      </div>

      <div class="flex items-center gap-2">
        <a href="admin/topics.php" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-300 hover:text-white hover:bg-slate-700/50 transition-colors">
          <i class="fa-solid fa-layer-group mr-1.5 text-slate-400"></i> Topics CRUD
        </a>
        <a href="admin/questions.php" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-300 hover:text-white hover:bg-slate-700/50 transition-colors">
          <i class="fa-solid fa-circle-question mr-1.5 text-slate-400"></i> Questions CRUD
        </a>
        <a href="admin/index.php" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-300 hover:text-white hover:bg-slate-700/50 transition-colors">
          <i class="fa-solid fa-chart-pie mr-1.5 text-slate-400"></i> Dashboard
        </a>
        <a href="admin/logout.php" class="bg-rose-500/20 hover:bg-rose-500/30 border border-rose-500/40 text-rose-300 text-xs font-bold py-1.5 px-3 rounded-lg transition-colors">
          Logout
        </a>
      </div>
    </div>
  </header>

  <main class="flex-1 max-w-6xl w-full mx-auto px-4 md:px-8 py-8 space-y-8">
    
    <!-- Title Banner -->
    <div class="bg-gradient-to-r from-slate-800 via-indigo-950/40 to-slate-800 border border-slate-700/80 rounded-3xl p-6 md:p-8 shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
      <div>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/20 text-amber-300 border border-amber-500/30 text-xs font-black uppercase tracking-wider mb-2">
          <i class="fa-solid fa-database"></i> On-Demand Data Seeder & Synchronizer
        </div>
        <h1 class="text-2xl md:text-3xl font-black text-white tracking-tight">Curriculum Database Seeder</h1>
        <p class="text-sm font-semibold text-slate-400 mt-1 max-w-2xl">
          Quickly populate or restore syllabus question banks from master JSON files. Choose between Kindergarten 3 (KG3), Year 6 (PSR Brunei), or both.
        </p>
      </div>

      <div class="flex items-center gap-3">
        <a href="admin/questions.php" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs py-2.5 px-4 rounded-xl transition-all flex items-center gap-2">
          <i class="fa-solid fa-list-check"></i>
          <span>Manage Questions</span>
        </a>
      </div>
    </div>

    <!-- Live Database Status -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
      <div class="bg-slate-800/80 border border-slate-700/80 p-4 rounded-2xl">
        <span class="text-[11px] font-bold text-emerald-400 uppercase tracking-wider block">KG3 Questions in DB</span>
        <span id="stat-kg3-q" class="text-2xl font-black text-white"><?= number_format($totalKG3Q) ?></span>
        <span class="text-[10px] text-slate-400 block mt-0.5">from questions_bank.json</span>
      </div>

      <div class="bg-slate-800/80 border border-slate-700/80 p-4 rounded-2xl">
        <span class="text-[11px] font-bold text-amber-400 uppercase tracking-wider block">PSR Questions in DB</span>
        <span id="stat-psr-q" class="text-2xl font-black text-white"><?= number_format($totalPSRQ) ?></span>
        <span class="text-[10px] text-slate-400 block mt-0.5">from questions_bank_psr.json</span>
      </div>

      <div class="bg-slate-800/80 border border-slate-700/80 p-4 rounded-2xl">
        <span class="text-[11px] font-bold text-sky-400 uppercase tracking-wider block">KG3 Topics Active</span>
        <span class="text-2xl font-black text-white"><?= $totalKG3Topics ?></span>
        <span class="text-[10px] text-slate-400 block mt-0.5">5 Core Subjects</span>
      </div>

      <div class="bg-slate-800/80 border border-slate-700/80 p-4 rounded-2xl">
        <span class="text-[11px] font-bold text-purple-400 uppercase tracking-wider block">PSR Topics Active</span>
        <span class="text-2xl font-black text-white"><?= $totalPSRTopics ?></span>
        <span class="text-[10px] text-slate-400 block mt-0.5">5 PSR Exam Subjects</span>
      </div>
    </div>

    <!-- 3 Seeding Target Options -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

      <!-- Option 1: KG3 Only -->
      <div class="bg-slate-800/80 border border-emerald-500/30 rounded-3xl p-6 shadow-xl flex flex-col justify-between space-y-4 hover:border-emerald-500/60 transition-all">
        <div class="space-y-3">
          <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 flex items-center justify-center text-2xl">
            👶
          </div>
          <div>
            <h3 class="text-lg font-black text-white">Kindergarten 3 (KG3)</h3>
            <span class="text-[11px] text-emerald-400 font-mono font-bold">Source: data/questions_bank.json</span>
          </div>
          <p class="text-xs text-slate-300 leading-relaxed">
            Seeds 5 Subjects (BM 📚, English 🔤, Maths 🔢, Science 🌱, ICT 💻), 27 Topics, and ~919 Questions with phonics, syllable blending, and audio hints.
          </p>
          <div class="text-[11px] text-slate-400 bg-slate-900/60 p-2.5 rounded-xl border border-slate-700/60">
            ✓ Preserves any existing Year 6 PSR questions.
          </div>
        </div>

        <button 
          type="button" 
          onclick="runSeeder('kg3', 'Kindergarten 3 (KG3)')"
          class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-black text-sm py-3 px-4 rounded-2xl shadow-lg shadow-emerald-600/30 transition-all flex items-center justify-center gap-2 cursor-pointer"
        >
          <i class="fa-solid fa-play"></i>
          <span>Seed KG3 Data</span>
        </button>
      </div>

      <!-- Option 2: PSR Brunei Only -->
      <div class="bg-slate-800/80 border border-amber-500/30 rounded-3xl p-6 shadow-xl flex flex-col justify-between space-y-4 hover:border-amber-500/60 transition-all">
        <div class="space-y-3">
          <div class="w-12 h-12 rounded-2xl bg-amber-500/20 text-amber-400 border border-amber-500/30 flex items-center justify-center text-2xl">
            🇧🇳
          </div>
          <div>
            <h3 class="text-lg font-black text-white">Year 6 (PSR Brunei)</h3>
            <span class="text-[11px] text-amber-400 font-mono font-bold">Source: data/questions_bank_psr.json</span>
          </div>
          <p class="text-xs text-slate-300 leading-relaxed">
            Seeds 5 Core Exam Subjects (BM PSR, English PSR, Maths PSR, Science PSR, MIB), 21 Topics, and 630 Exam Questions (30 per topic).
          </p>
          <div class="text-[11px] text-slate-400 bg-slate-900/60 p-2.5 rounded-xl border border-slate-700/60">
            ✓ Preserves any existing Kindergarten 3 questions.
          </div>
        </div>

        <button 
          type="button" 
          onclick="runSeeder('psr', 'Year 6 (PSR Brunei)')"
          class="w-full bg-amber-600 hover:bg-amber-500 text-white font-black text-sm py-3 px-4 rounded-2xl shadow-lg shadow-amber-600/30 transition-all flex items-center justify-center gap-2 cursor-pointer"
        >
          <i class="fa-solid fa-play"></i>
          <span>Seed PSR Data</span>
        </button>
      </div>

      <!-- Option 3: Seed All -->
      <div class="bg-slate-800/80 border border-indigo-500/30 rounded-3xl p-6 shadow-xl flex flex-col justify-between space-y-4 hover:border-indigo-500/60 transition-all">
        <div class="space-y-3">
          <div class="w-12 h-12 rounded-2xl bg-indigo-500/20 text-indigo-400 border border-indigo-500/30 flex items-center justify-center text-2xl">
            🚀
          </div>
          <div>
            <h3 class="text-lg font-black text-white">All Curricula (Full Sync)</h3>
            <span class="text-[11px] text-indigo-400 font-mono font-bold">KG3 + Year 6 PSR</span>
          </div>
          <p class="text-xs text-slate-300 leading-relaxed">
            Synchronizes both curricula in sequence. Re-seeds 10 subjects, 48 topics, 48 revision modules, and all 1,549+ questions.
          </p>
          <div class="text-[11px] text-slate-400 bg-slate-900/60 p-2.5 rounded-xl border border-slate-700/60">
            ✓ Recommended for initial setups and complete resets.
          </div>
        </div>

        <button 
          type="button" 
          onclick="runSeeder('all', 'All Curricula (KG3 + PSR)')"
          class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-black text-sm py-3 px-4 rounded-2xl shadow-lg shadow-indigo-600/30 transition-all flex items-center justify-center gap-2 cursor-pointer"
        >
          <i class="fa-solid fa-bolt"></i>
          <span>Seed All Data</span>
        </button>
      </div>

    </div>

    <!-- Live Execution Output Box -->
    <div class="bg-slate-900/90 border border-slate-700 rounded-3xl p-6 shadow-xl space-y-4">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></div>
          <span class="text-xs font-mono font-bold text-slate-300 uppercase tracking-wider">Live Execution Terminal</span>
        </div>
        <div class="flex items-center gap-2">
          <button 
            type="button" 
            onclick="document.getElementById('console-output').textContent = 'Ready. Click a seed button above to start.';"
            class="text-[11px] text-slate-400 hover:text-white px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 transition-colors"
          >
            Clear Console
          </button>
        </div>
      </div>

      <pre id="console-output" class="bg-slate-950 p-4 rounded-2xl border border-slate-800 font-mono text-xs text-emerald-400 overflow-x-auto max-h-96 whitespace-pre-wrap leading-relaxed">Ready. Select an option above to run database seeding.</pre>
    </div>

  </main>

  <footer class="mt-auto border-t border-slate-800 py-6 text-center text-xs font-bold text-slate-500">
    NextGrade Educational Operating System • Database Seeder v2.0
  </footer>

  <script>
    async function runSeeder(target, label) {
      if (!confirm(`Are you sure you want to seed ${label}? This will refresh questions and topics for the selected curriculum.`)) {
        return;
      }

      const out = document.getElementById('console-output');
      out.textContent = `[${new Date().toLocaleTimeString()}] Starting seeding for: ${label}...\nPlease wait, processing JSON records...\n\n`;

      try {
        const formData = new FormData();
        formData.append('target', target);
        formData.append('ajax', '1');

        const resp = await fetch('seed.php', {
          method: 'POST',
          body: formData
        });

        const text = await resp.text();
        out.textContent += text;
        out.scrollTop = out.scrollHeight;

        // Auto-refresh stats if finished
        if (text.includes('SUCCESSFULLY') || text.includes('COMPLETED')) {
          out.textContent += `\n[${new Date().toLocaleTimeString()}] Finished! Reloading page status in 2 seconds...`;
          setTimeout(() => window.location.reload(), 2000);
        }
      } catch (err) {
        out.textContent += `\n❌ Network / Request Error: ` + err.message;
      }
    }
  </script>

</body>
</html>
