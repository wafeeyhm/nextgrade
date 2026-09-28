<?php
// NextGrade Comprehensive Database Seeder
// Seeds Subjects, Topics, Core Questions, and 5-Minute Revision Guides from questions_bank.json

require_once __DIR__ . '/db.php';

header('Content-Type: text/plain; charset=utf-8');

// Optional direct truncate action via seed.php?action=truncate
if (isset($_GET['action']) && $_GET['action'] === 'truncate') {
    echo "=== NextGrade Truncate Process Started ===\n\n";
    try {
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
        $tables = ['quiz_session_answers', 'quiz_sessions', 'questions', 'revisions', 'topics', 'subjects', 'students'];
        foreach ($tables as $t) {
            $pdo->exec("TRUNCATE TABLE `$t`;");
            echo "✓ Truncated table: $t\n";
        }
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");
        echo "\n💥 ALL TABLES TRUNCATED SUCCESSFULLY!\n";
    } catch (Exception $e) {
        echo "❌ Truncate Failed: " . $e->getMessage() . "\n";
    }
    exit;
}

echo "=== NextGrade Seeding Process Started ===\n\n";

try {
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
    $pdo->exec("TRUNCATE TABLE quiz_session_answers;");
    $pdo->exec("TRUNCATE TABLE quiz_sessions;");
    $pdo->exec("TRUNCATE TABLE questions;");
    $pdo->exec("TRUNCATE TABLE revisions;");
    $pdo->exec("TRUNCATE TABLE topics;");
    $pdo->exec("TRUNCATE TABLE subjects;");
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");
    echo "✓ Cleaned existing tables.\n";

    // 1. SUBJECTS
    $subjects = [
        [
            'id' => 'bahasa_melayu',
            'name' => 'Bahasa Melayu',
            'title_native' => 'Bahasa Melayu',
            'description' => 'Suku kata, kenderaan, haiwan, bulan & tatabahasa asas.',
            'icon' => '🇲🇾',
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

    $stmtSubject = $pdo->prepare("INSERT INTO subjects (id, name, title_native, description, icon, theme_gradient, accent_color, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($subjects as $s) {
        $stmtSubject->execute([$s['id'], $s['name'], $s['title_native'], $s['description'], $s['icon'], $s['theme_gradient'], $s['accent_color'], $s['sort_order']]);
    }
    echo "✓ Seeded 5 Subjects.\n";

    // 2. TOPICS
    $topics = [
        // Bahasa Melayu (Kept 100% in Malay as requested)
        ['id' => 'bm_bulan', 'subject_id' => 'bahasa_melayu', 'name' => '12 Bulan dalam Setahun', 'name_native' => '12 Bulan dalam Setahun', 'description' => 'Mengecam dan menyusun nama 12 bulan mengikut turutan yang betul.', 'icon' => '📅', 'color_badge' => 'bg-emerald-100 text-emerald-800', 'sort_order' => 1],
        ['id' => 'bm_suku_kata', 'subject_id' => 'bahasa_melayu', 'name' => 'Pecahkan Suku Kata', 'name_native' => 'Pecahkan Perkataan kepada Suku Kata', 'description' => 'Membina perkataan mudah melalui cantuman dua suku kata terbuka.', 'icon' => '🧩', 'color_badge' => 'bg-teal-100 text-teal-800', 'sort_order' => 2],
        ['id' => 'bm_kenderaan', 'subject_id' => 'bahasa_melayu', 'name' => 'Kenderaan Darat, Air & Udara', 'name_native' => 'Kenderaan Darat, Air dan Udara', 'description' => 'Kenal pasti jenis kenderaan dan laluan pergerakannya.', 'icon' => '🚗', 'color_badge' => 'bg-cyan-100 text-cyan-800', 'sort_order' => 3],
        ['id' => 'bm_binatang', 'subject_id' => 'bahasa_melayu', 'name' => 'Haiwan 2 Kaki & 4 Kaki', 'name_native' => 'Haiwan Berkaki 2 dan Berkaki 4', 'description' => 'Mengelaskan pelbagai haiwan mengikut bilangan kakinya.', 'icon' => '🐾', 'color_badge' => 'bg-green-100 text-green-800', 'sort_order' => 4],
        ['id' => 'bm_ini_itu', 'subject_id' => 'bahasa_melayu', 'name' => 'Kata Tunjuk: Ini & Itu', 'name_native' => 'Penggunaan Kata Tunjuk Ini dan Itu', 'description' => 'Memahami perbezaan jarak dekat (Ini) dan jarak jauh (Itu).', 'icon' => '👉', 'color_badge' => 'bg-lime-100 text-lime-800', 'sort_order' => 5],

        // Maths (English)
        ['id' => 'math_clocks', 'subject_id' => 'maths', 'name' => 'Analog Clocks & Time', 'name_native' => 'Reading Clock Numbers & Hands', 'description' => 'Learn the hour hand, minute hand, and how to read the clock.', 'icon' => '🕒', 'color_badge' => 'bg-amber-100 text-amber-800', 'sort_order' => 1],
        ['id' => 'math_descending', 'subject_id' => 'maths', 'name' => 'Descending Numbers (20 to 1)', 'name_native' => 'Descending Numbers 20 to 1', 'description' => 'Count numbers from largest to smallest, from 20 down to 1.', 'icon' => '📉', 'color_badge' => 'bg-orange-100 text-orange-800', 'sort_order' => 2],
        ['id' => 'math_addition', 'subject_id' => 'maths', 'name' => 'Addition (Combining Numbers)', 'name_native' => 'Basic Addition with Pictures', 'description' => 'Count and add groups of fun objects together.', 'icon' => '➕', 'color_badge' => 'bg-yellow-100 text-yellow-800', 'sort_order' => 3],
        ['id' => 'math_subtraction', 'subject_id' => 'maths', 'name' => 'Subtraction (Taking Away)', 'name_native' => 'Basic Subtraction with Pictures', 'description' => 'Count what remains when items are removed or popped.', 'icon' => '➖', 'color_badge' => 'bg-red-100 text-red-800', 'sort_order' => 4],

        // English (English)
        ['id' => 'eng_days_months', 'subject_id' => 'english', 'name' => 'Days of Week & Months', 'name_native' => 'Days of the Week and Month Numbers', 'description' => 'Learn the 7 days of the week in sequence and match months to their numbers.', 'icon' => '🗓️', 'color_badge' => 'bg-sky-100 text-sky-800', 'sort_order' => 1],
        ['id' => 'eng_blending', 'subject_id' => 'english', 'name' => 'Beginning Blends (ch- & th-)', 'name_native' => 'Beginning Blending Sound Box', 'description' => 'Recognise and match beginning blend sounds: ch- (chair) and th- (thorn).', 'icon' => '🗣️', 'color_badge' => 'bg-blue-100 text-blue-800', 'sort_order' => 2],
        ['id' => 'eng_pronouns', 'subject_id' => 'english', 'name' => 'Pronouns (He, She, It, They)', 'name_native' => 'Personal Pronouns', 'description' => 'Choose the correct pronoun for boys, girls, objects, and groups.', 'icon' => '👥', 'color_badge' => 'bg-indigo-100 text-indigo-800', 'sort_order' => 3],
        ['id' => 'eng_articles', 'subject_id' => 'english', 'name' => 'Articles: A and An', 'name_native' => 'Articles A or An', 'description' => 'Use "an" before vowel sounds (a, e, i, o, u) and "a" before consonants.', 'icon' => '🔤', 'color_badge' => 'bg-violet-100 text-violet-800', 'sort_order' => 4],
        ['id' => 'eng_has_have', 'subject_id' => 'english', 'name' => 'Using Has and Have', 'name_native' => 'Has vs Have Rules', 'description' => 'He/She/It uses "has", while I/We/They uses "have".', 'icon' => '🤲', 'color_badge' => 'bg-fuchsia-100 text-fuchsia-800', 'sort_order' => 5],
        ['id' => 'eng_demonstratives', 'subject_id' => 'english', 'name' => 'This, That, These, Those', 'name_native' => 'Demonstrative Pronouns', 'description' => 'Master near vs far, singular vs plural pointer words.', 'icon' => '👉', 'color_badge' => 'bg-purple-100 text-purple-800', 'sort_order' => 6],
        ['id' => 'eng_comprehension', 'subject_id' => 'english', 'name' => 'Reading Comprehension', 'name_native' => 'Short Stories: Troy & Andy', 'description' => 'Read sweet passages, understand context, and answer smart questions.', 'icon' => '📖', 'color_badge' => 'bg-sky-100 text-sky-800', 'sort_order' => 7],

        // Science (English)
        ['id' => 'sci_land_sea', 'subject_id' => 'science', 'name' => 'Land vs Sea Animals', 'name_native' => 'Land and Sea Animals Habitat', 'description' => 'Identify whether animals live on land or in the ocean.', 'icon' => '🐬', 'color_badge' => 'bg-emerald-100 text-emerald-800', 'sort_order' => 1],
        ['id' => 'sci_sink_float', 'subject_id' => 'science', 'name' => 'Sink or Float', 'name_native' => 'Objects that Sink or Float', 'description' => 'Discover which objects sink to the bottom or float on water.', 'icon' => '⚓', 'color_badge' => 'bg-cyan-100 text-cyan-800', 'sort_order' => 2],
        ['id' => 'sci_celestial', 'subject_id' => 'science', 'name' => 'Sun, Moon, Star & Earth', 'name_native' => 'Sun, Moon, Star, and Earth', 'description' => 'Learn about the celestial bodies in our sky and our home planet Earth.', 'icon' => '🌍', 'color_badge' => 'bg-amber-100 text-amber-800', 'sort_order' => 3],
        ['id' => 'sci_materials', 'subject_id' => 'science', 'name' => 'Materials: Metal, Glass & Paper', 'name_native' => 'Objects Made of Metal, Glass & Paper', 'description' => 'Identify everyday objects made of metal, transparent glass, or paper.', 'icon' => '🪨', 'color_badge' => 'bg-stone-100 text-stone-800', 'sort_order' => 4],
        ['id' => 'sci_pollution', 'subject_id' => 'science', 'name' => 'Types of Pollution', 'name_native' => 'Types of Pollutions', 'description' => 'Learn about air pollution, water/sea pollution, and land pollution.', 'icon' => '🏭', 'color_badge' => 'bg-red-100 text-red-800', 'sort_order' => 5],
        ['id' => 'sci_plants', 'subject_id' => 'science', 'name' => 'Parts & Needs of a Plant', 'name_native' => 'Parts of a Plant & Needs to Grow', 'description' => 'Identify roots, stem, leaves, flower, fruit and sunlight, air, water.', 'icon' => '🌱', 'color_badge' => 'bg-green-100 text-green-800', 'sort_order' => 6],

        // ICT (English)
        ['id' => 'ict_storage', 'subject_id' => 'ict', 'name' => 'Computer Drives & Storage', 'name_native' => 'Computer Drives and Storage', 'description' => 'Learn about Hard disk drives, floppy disks, CD-ROMs, memory cards, and USB pendrives.', 'icon' => '💾', 'color_badge' => 'bg-rose-100 text-rose-800', 'sort_order' => 1],
        ['id' => 'ict_parts', 'subject_id' => 'ict', 'name' => 'All About Computer Parts', 'name_native' => 'All About Computer Peripherals', 'description' => 'Identify monitors, keyboards, printers, headphones, scanners, and system units.', 'icon' => '🖥️', 'color_badge' => 'bg-pink-100 text-pink-800', 'sort_order' => 2],
        ['id' => 'ict_counting', 'subject_id' => 'ict', 'name' => 'Count Computer Peripherals', 'name_native' => 'Count Computer Peripherals', 'description' => 'Count and determine the correct number of computer devices.', 'icon' => '🔢', 'color_badge' => 'bg-indigo-100 text-indigo-800', 'sort_order' => 3],
        ['id' => 'ict_spelling', 'subject_id' => 'ict', 'name' => 'Fill in the Missing Letters', 'name_native' => 'Fill in the Missing Letters', 'description' => 'Complete the missing letters for computer peripheral names.', 'icon' => '🔤', 'color_badge' => 'bg-purple-100 text-purple-800', 'sort_order' => 4],
        ['id' => 'ict_input_output', 'subject_id' => 'ict', 'name' => 'Input (I) vs Output (O) Devices', 'name_native' => 'Input vs Output Devices', 'description' => 'Recognise whether a device feeds data in (Input) or presents results (Output).', 'icon' => '🔌', 'color_badge' => 'bg-blue-100 text-blue-800', 'sort_order' => 5],
    ];

    $stmtTopic = $pdo->prepare("INSERT INTO topics (id, subject_id, name, name_native, description, icon, color_badge, revision_time_limit, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, 300, ?)");
    foreach ($topics as $t) {
        $stmtTopic->execute([$t['id'], $t['subject_id'], $t['name'], $t['name_native'], $t['description'], $t['icon'], $t['color_badge'], $t['sort_order']]);
    }
    echo "✓ Seeded " . count($topics) . " Topics.\n";

    // 3. QUESTIONS BANK (Loaded from data/questions_bank.json)
    $bankFile = __DIR__ . '/data/questions_bank.json';
    if (!file_exists($bankFile)) {
        throw new Exception("Questions bank file not found: $bankFile");
    }

    $questions = json_decode(file_get_contents($bankFile), true);
    if (!$questions || !is_array($questions)) {
        throw new Exception("Invalid JSON format in: $bankFile");
    }

    $pdo->beginTransaction();
    $stmtQ = $pdo->prepare("INSERT INTO questions (topic_id, subject_id, question_text, question_audio, lang, question_type, image_url, passage, options_json, correct_answer, hint_text, hint_audio, meta_data_json) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
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
    echo "✓ Seeded " . count($questions) . " Core Questions across all " . count($topics) . " Topics.\n";

    // 4. REVISION GUIDES (Generated dynamically based on questions_bank.json)
    // Indexes questions by topic to create 5-minute flashcards from actual question bank items
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
            // Select 5 evenly spaced questions across the topic's question bank
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
                if (is_array($decodedAns)) {
                    $ansText = implode(' ➔ ', $decodedAns);
                } else {
                    $ansText = $rawAns;
                }

                $hintText = !empty($q['hint_text']) ? trim($q['hint_text']) : '';

                if ($isMalay) {
                    $explanation = "Jawapan: " . $ansText . ($hintText ? " • Tip: " . $hintText : "");
                } else {
                    $explanation = "Answer: " . $ansText . ($hintText ? " • Hint: " . $hintText : "");
                }

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

        $stmtRev->execute([
            $tId,
            $revTitle,
            $revSummary,
            json_encode($cards, JSON_UNESCAPED_UNICODE)
        ]);
        $revisionCount++;
    }

    echo "✓ Seeded $revisionCount Revision Modules dynamically based on questions_bank.json.\n";

    echo "\n🎉 SEEDING COMPLETED SUCCESSFULLY!\n";
    echo "Total Subjects: " . count($subjects) . "\n";
    echo "Total Topics: " . count($topics) . "\n";
    echo "Total Revisions: $revisionCount\n";
    echo "Total Questions: " . count($questions) . "\n";

} catch (Exception $e) {
    echo "❌ Seeding Failed: " . $e->getMessage() . "\n";
}
