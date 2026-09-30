<?php
// NextGrade - Year 6 Brunei PSR Curriculum Database Seeder & Migrator
// Seeds 5 Subjects, 21 Topics, 630 Questions, and 21 Revision Guides with strict grade segregation.

require_once __DIR__ . '/../db.php';

echo "=== NextGrade: Seeding Year 6 Brunei PSR Curriculum ===\n\n";

try {
    // 1. Ensure `grade_level` columns exist in subjects, topics, and questions
    echo "1. Checking database schema columns...\n";
    $tables = ['subjects', 'topics', 'questions'];
    foreach ($tables as $t) {
        $cols = $pdo->query("SHOW COLUMNS FROM `$t` LIKE 'grade_level'")->fetchAll();
        if (empty($cols)) {
            $pdo->exec("ALTER TABLE `$t` ADD COLUMN `grade_level` VARCHAR(50) DEFAULT 'Kindergarten 3 (KG3)'");
            echo "   ✓ Added `grade_level` column to `$t` table.\n";
        } else {
            echo "   ✓ `grade_level` column already exists in `$t` table.\n";
        }
    }

    // Set existing records to 'Kindergarten 3 (KG3)' if not set
    $pdo->exec("UPDATE `subjects` SET `grade_level` = 'Kindergarten 3 (KG3)' WHERE `grade_level` IS NULL OR `grade_level` = ''");
    $pdo->exec("UPDATE `topics` SET `grade_level` = 'Kindergarten 3 (KG3)' WHERE `grade_level` IS NULL OR `grade_level` = ''");
    $pdo->exec("UPDATE `questions` SET `grade_level` = 'Kindergarten 3 (KG3)' WHERE `grade_level` IS NULL OR `grade_level` = ''");
    echo "   ✓ Existing records verified as Kindergarten 3 (KG3).\n\n";

    // 2. Insert or Update Year 6 PSR Subjects
    echo "2. Seeding Year 6 PSR Subjects...\n";
    $psrSubjects = [
        [
            'id' => 'psr_maths',
            'name' => 'Mathematics (PSR)',
            'title_native' => 'Matematik PSR (Tahun 6)',
            'description' => 'Numbers & operations, fractions, decimals, percentages, measurement, area, volume & data handling.',
            'icon' => '📐',
            'theme_gradient' => 'from-blue-600 to-indigo-700',
            'accent_color' => '#2563EB',
            'grade_level' => 'Year 6 (PSR)',
            'sort_order' => 11
        ],
        [
            'id' => 'psr_science',
            'name' => 'Science (PSR)',
            'title_native' => 'Sains PSR (Tahun 6)',
            'description' => 'Human body systems, plant processes, energy, circuits, forces, machines, matter & solar system.',
            'icon' => '🔬',
            'theme_gradient' => 'from-emerald-500 to-teal-700',
            'accent_color' => '#059669',
            'grade_level' => 'Year 6 (PSR)',
            'sort_order' => 12
        ],
        [
            'id' => 'psr_english',
            'name' => 'English Language (PSR)',
            'title_native' => 'English Language (Year 6)',
            'description' => 'Tenses, subject-verb agreement, prepositions, idioms, phrasal verbs, voice & sentence structures.',
            'icon' => '📖',
            'theme_gradient' => 'from-purple-600 to-pink-700',
            'accent_color' => '#9333EA',
            'grade_level' => 'Year 6 (PSR)',
            'sort_order' => 13
        ],
        [
            'id' => 'psr_bahasa_melayu',
            'name' => 'Bahasa Melayu (PSR)',
            'title_native' => 'Bahasa Melayu PSR (Tahun 6)',
            'description' => 'Tatabahasa, imbuhan awalan/akhiran/apitan, penjodoh bilangan, peribahasa Brunei & kosa kata.',
            'icon' => '🇧🇳',
            'theme_gradient' => 'from-amber-500 to-red-600',
            'accent_color' => '#D97706',
            'grade_level' => 'Year 6 (PSR)',
            'sort_order' => 14
        ],
        [
            'id' => 'psr_mib',
            'name' => 'Melayu Islam Beraja (MIB)',
            'title_native' => 'Melayu Islam Beraja (Tahun 6)',
            'description' => 'Konsep MIB, sejarah Kesultanan Brunei, Sultan berdaulat, tatasusila, adat istiadat & kebudayaan.',
            'icon' => '🕌',
            'theme_gradient' => 'from-yellow-500 to-amber-700',
            'accent_color' => '#B45309',
            'grade_level' => 'Year 6 (PSR)',
            'sort_order' => 15
        ]
    ];

    $stmtSub = $pdo->prepare("
        INSERT INTO subjects (id, name, title_native, description, icon, theme_gradient, accent_color, grade_level, sort_order)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
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

    foreach ($psrSubjects as $s) {
        $stmtSub->execute([
            $s['id'], $s['name'], $s['title_native'], $s['description'],
            $s['icon'], $s['theme_gradient'], $s['accent_color'], $s['grade_level'], $s['sort_order']
        ]);
    }
    echo "   ✓ Seeded 5 Year 6 PSR Subjects.\n\n";

    // 3. Insert or Update Year 6 PSR Topics
    echo "3. Seeding Year 6 PSR Topics (21 Topics)...\n";
    $psrTopics = [
        // Mathematics (5 topics)
        ['id' => 'psr_math_numbers', 'subject_id' => 'psr_maths', 'name' => 'Numbers, Place Value & BODMAS', 'name_native' => 'Nombor Bulat & Operasi Bergabung', 'description' => 'Whole numbers up to 1,000,000, place value, prime numbers, rounding off and order of operations (BODMAS).', 'icon' => '🔢', 'color_badge' => 'bg-blue-100 text-blue-800', 'sort_order' => 1],
        ['id' => 'psr_math_fractions_decimals', 'subject_id' => 'psr_maths', 'name' => 'Fractions, Decimals & Operations', 'name_native' => 'Pecahan & Perpuluhan', 'description' => 'Addition, subtraction, multiplication and division of proper, improper and mixed fractions, plus decimal conversions.', 'icon' => '🍰', 'color_badge' => 'bg-indigo-100 text-indigo-800', 'sort_order' => 2],
        ['id' => 'psr_math_percentages_ratios', 'subject_id' => 'psr_maths', 'name' => 'Percentages, Ratio & Financial Maths', 'name_native' => 'Peratusan, Nisbah & Wang', 'description' => 'Calculate percentages of quantities, discounts, profit/loss, simple interest, currency and ratio sharing.', 'icon' => '💰', 'color_badge' => 'bg-emerald-100 text-emerald-800', 'sort_order' => 3],
        ['id' => 'psr_math_measurement_geometry', 'subject_id' => 'psr_maths', 'name' => 'Measurement, Geometry & Volume', 'name_native' => 'Ukuran, Geometri & Isi Padu', 'description' => 'Metric unit conversions, 24-hour time, perimeter, area of 2D shapes, angles and volume of cuboids.', 'icon' => '📐', 'color_badge' => 'bg-cyan-100 text-cyan-800', 'sort_order' => 4],
        ['id' => 'psr_math_data_probability', 'subject_id' => 'psr_maths', 'name' => 'Data Handling, Mean & Probability', 'name_native' => 'Pengendalian Data & Purata', 'description' => 'Calculating the mean (average), median, mode, range, interpreting bar charts, pie charts and simple probability.', 'icon' => '📊', 'color_badge' => 'bg-purple-100 text-purple-800', 'sort_order' => 5],

        // Science (5 topics)
        ['id' => 'psr_sci_human_body', 'subject_id' => 'psr_science', 'name' => 'Human Body Systems & Health', 'name_native' => 'Sistem Tubuh Manusia & Kesihatan', 'description' => 'Circulatory system, heart & blood vessels, respiratory system, lungs & alveoli, digestion and skeletal framework.', 'icon' => '🫀', 'color_badge' => 'bg-red-100 text-red-800', 'sort_order' => 1],
        ['id' => 'psr_sci_plants_living_things', 'subject_id' => 'psr_science', 'name' => 'Plant Processes & Reproduction', 'name_native' => 'Proses Hidup Tumbuhan', 'description' => 'Photosynthesis, xylem & phloem transport, pollination, seed dispersal mechanisms and germination requirements.', 'icon' => '🌱', 'color_badge' => 'bg-green-100 text-green-800', 'sort_order' => 2],
        ['id' => 'psr_sci_energy_electricity', 'subject_id' => 'psr_science', 'name' => 'Energy, Circuits & Electrical Safety', 'name_native' => 'Tenaga, Litar Elektrik & Keselamatan', 'description' => 'Forms of energy, renewable vs non-renewable sources, series vs parallel circuits, conductors, insulators and fuses.', 'icon' => '⚡', 'color_badge' => 'bg-yellow-100 text-yellow-800', 'sort_order' => 3],
        ['id' => 'psr_sci_forces_machines', 'subject_id' => 'psr_science', 'name' => 'Forces, Friction & Simple Machines', 'name_native' => 'Daya, Geseran & Mesin Ringkas', 'description' => 'Gravity, friction, air resistance, balanced forces, levers (Class 1, 2, 3), pulleys, ramps and wheel & axle.', 'icon' => '⚙️', 'color_badge' => 'bg-stone-100 text-stone-800', 'sort_order' => 4],
        ['id' => 'psr_sci_matter_earth', 'subject_id' => 'psr_science', 'name' => 'States of Matter & The Solar System', 'name_native' => 'Jirim, Kitaran Air & Sistem Suria', 'description' => 'Solids, liquids, gases, evaporation, condensation, water cycle, day/night rotation and solar system planets.', 'icon' => '🌍', 'color_badge' => 'bg-sky-100 text-sky-800', 'sort_order' => 5],

        // English (4 topics)
        ['id' => 'psr_eng_grammar_tenses', 'subject_id' => 'psr_english', 'name' => 'Grammar Tenses & Subject-Verb Agreement', 'name_native' => 'Tenses & Subject-Verb Concord', 'description' => 'Past perfect, present perfect, continuous tenses, modal verbs, collective nouns and subject-verb agreement rules.', 'icon' => '⏱️', 'color_badge' => 'bg-purple-100 text-purple-800', 'sort_order' => 1],
        ['id' => 'psr_eng_parts_of_speech', 'subject_id' => 'psr_english', 'name' => 'Prepositions, Conjunctions & Relative Clauses', 'name_native' => 'Parts of Speech & Connectors', 'description' => 'Prepositions of time and movement, correlative conjunctions (neither/nor), relative pronouns (who/whom/whose) and adverbs.', 'icon' => '🔗', 'color_badge' => 'bg-indigo-100 text-indigo-800', 'sort_order' => 2],
        ['id' => 'psr_eng_vocabulary_idioms', 'subject_id' => 'psr_english', 'name' => 'Idioms, Phrasal Verbs & Vocabulary', 'name_native' => 'Idiomatic Expressions & Lexis', 'description' => 'PSR exam idioms, phrasal verbs, context clues, advanced synonyms and antonyms.', 'icon' => '💡', 'color_badge' => 'bg-amber-100 text-amber-800', 'sort_order' => 3],
        ['id' => 'psr_eng_sentence_structures', 'subject_id' => 'psr_english', 'name' => 'Active/Passive Voice & Reported Speech', 'name_native' => 'Sentence Transformation', 'description' => 'Converting active to passive voice, direct to indirect speech, question tags and complex sentence synthesis.', 'icon' => '📝', 'color_badge' => 'bg-pink-100 text-pink-800', 'sort_order' => 4],

        // Bahasa Melayu (4 topics)
        ['id' => 'psr_bm_tatabahasa_imbuhan', 'subject_id' => 'psr_bahasa_melayu', 'name' => 'Morfologi, Golongan Kata & Imbuhan', 'name_native' => 'Morfologi & Imbuhan Lengkap', 'description' => 'Kata Nama, Kata Kerja transitif/tak transitif, Kata Adjektif, Kata Hubung, Kata Pemeri dan imbuhan apitan/sisipan.', 'icon' => '📚', 'color_badge' => 'bg-amber-100 text-amber-800', 'sort_order' => 1],
        ['id' => 'psr_bm_penjodoh_bilangan', 'subject_id' => 'psr_bahasa_melayu', 'name' => 'Penjodoh Bilangan Komprehensif', 'name_native' => 'Penjodoh Bilangan Peperiksaan PSR', 'description' => 'Penggunaan jitu penjodoh bilangan: laras, pucuk, bilah, bentuk, kaki, lembar, naskhah, rumpun, utas dan ulas.', 'icon' => '🔢', 'color_badge' => 'bg-teal-100 text-teal-800', 'sort_order' => 2],
        ['id' => 'psr_bm_peribahasa', 'subject_id' => 'psr_bahasa_melayu', 'name' => 'Peribahasa, Simpulan Bahasa & Kiasan', 'name_native' => 'Peribahasa & Simpulan Bahasa Melayu', 'description' => 'Maksud dan penggunaan simpulan bahasa, perumpamaan, pepatah, dan bidalan warisan Melayu serta kiasan Brunei.', 'icon' => '💎', 'color_badge' => 'bg-red-100 text-red-800', 'sort_order' => 3],
        ['id' => 'psr_bm_kosa_kata_pemahaman', 'subject_id' => 'psr_bahasa_melayu', 'name' => 'Sinonim, Antonim & Kosa Kata Kontekstual', 'name_native' => 'Kosa Kata & Pemahaman Teks', 'description' => 'Perkataan seerti (sinonim), berlawan (antonim), kata ganda, bahasa kiasan dan pemahaman petikan peperiksaan PSR.', 'icon' => '📖', 'color_badge' => 'bg-orange-100 text-orange-800', 'sort_order' => 4],

        // Melayu Islam Beraja (3 topics)
        ['id' => 'psr_mib_falsafah_konsep', 'subject_id' => 'psr_mib', 'name' => 'Falsafah Negara & Konsep Teras MIB', 'name_native' => 'Falsafah Negara Melayu Islam Beraja', 'description' => 'Maksud Melayu, Islam, dan Beraja, Perlembagaan 1959, Pemasyhuran 1984, Negara Zikir, Jata Kebangsaan dan Wawasan 2035.', 'icon' => '👑', 'color_badge' => 'bg-yellow-100 text-yellow-800', 'sort_order' => 1],
        ['id' => 'psr_mib_sejarah_kesultanan', 'subject_id' => 'psr_mib', 'name' => 'Sejarah Kesultanan Brunei & Mercu Tanda', 'name_native' => 'Sejarah Kesultanan Melayu Brunei', 'description' => 'Sultan Muhammad Shah, Sultan Sharif Ali, Sultan Bolkiah, Perang Kastila, Arkitek Brunei Moden dan mercu tanda negara.', 'icon' => '🏛️', 'color_badge' => 'bg-amber-100 text-amber-800', 'sort_order' => 2],
        ['id' => 'psr_mib_adat_tatasusila', 'subject_id' => 'psr_mib', 'name' => 'Adat Istiadat, Tatasusila & Budaya Brunei', 'name_native' => 'Adat Istiadat & Tatasusila Brunei', 'description' => 'Tatasusila bersalaman, bahasa dalam (istana), makanan tradisi (ambuyat, kelupis), tenunan Brunei dan adab berteraskan syarak.', 'icon' => '🤝', 'color_badge' => 'bg-emerald-100 text-emerald-800', 'sort_order' => 3]
    ];

    $stmtTopic = $pdo->prepare("
        INSERT INTO topics (id, subject_id, name, name_native, description, icon, color_badge, revision_time_limit, grade_level, sort_order)
        VALUES (?, ?, ?, ?, ?, ?, ?, 300, 'Year 6 (PSR)', ?)
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

    foreach ($psrTopics as $t) {
        $stmtTopic->execute([
            $t['id'], $t['subject_id'], $t['name'], $t['name_native'],
            $t['description'], $t['icon'], $t['color_badge'], $t['sort_order']
        ]);
    }
    echo "   ✓ Seeded 21 Year 6 PSR Topics.\n\n";

    // 4. Load & Seed Questions from data/questions_bank_psr.json
    $jsonFile = __DIR__ . '/../data/questions_bank_psr.json';
    if (!file_exists($jsonFile)) {
        throw new Exception("File not found: $jsonFile");
    }

    $psrQuestions = json_decode(file_get_contents($jsonFile), true);
    if (!$psrQuestions || !is_array($psrQuestions)) {
        throw new Exception("Invalid JSON format in $jsonFile");
    }

    echo "4. Seeding Year 6 PSR Question Bank (" . count($psrQuestions) . " questions)...\n";
    // Delete existing PSR questions first to prevent duplicates upon re-seeding
    $delStmt = $pdo->prepare("DELETE FROM questions WHERE grade_level = 'Year 6 (PSR)' OR topic_id LIKE 'psr_%'");
    $delStmt->execute();
    echo "   ✓ Cleaned existing Year 6 PSR questions.\n";

    $stmtQ = $pdo->prepare("
        INSERT INTO questions (topic_id, subject_id, question_text, question_audio, lang, question_type, image_url, passage, options_json, correct_answer, hint_text, hint_audio, grade_level, meta_data_json)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Year 6 (PSR)', ?)
    ");

    $pdo->beginTransaction();
    $qCount = 0;
    foreach ($psrQuestions as $q) {
        $stmtQ->execute([
            $q['topic_id'],
            $q['subject_id'],
            $q['question_text'],
            $q['question_audio'] ?? $q['question_text'],
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
        $qCount++;
    }
    $pdo->commit();
    echo "   ✓ Successfully inserted $qCount questions across all 21 Year 6 PSR Topics.\n\n";

    // 5. Generate 5-Minute Interactive Revision Guides in `revisions` table
    echo "5. Generating 5-Minute Revision Guides for all 21 Year 6 PSR Topics...\n";
    // Clean existing PSR revisions
    $pdo->exec("DELETE FROM revisions WHERE topic_id LIKE 'psr_%'");

    $questionsByTopic = [];
    foreach ($psrQuestions as $q) {
        $questionsByTopic[$q['topic_id']][] = $q;
    }

    $stmtRev = $pdo->prepare("INSERT INTO revisions (topic_id, title, summary, content_json) VALUES (?, ?, ?, ?)");
    $revCount = 0;

    foreach ($psrTopics as $t) {
        $tid = $t['id'];
        $tQuestions = $questionsByTopic[$tid] ?? [];
        
        // Take 5 key core concept cards for the 5-minute revision guide
        $cards = [];
        $sampleSlice = array_slice($tQuestions, 0, 5);
        foreach ($sampleSlice as $idx => $sq) {
            $cards[] = [
                'card_number' => $idx + 1,
                'title' => "PSR Key Concept " . ($idx + 1),
                'question' => $sq['question_text'],
                'key_fact' => $sq['correct_answer'],
                'explanation' => $sq['hint_text'] ?? "Essential examination concept for Year 6 PSR Brunei syllabus.",
                'quick_tip' => "Tip: Master the underlying rule or formula to answer related PSR questions with speed and precision."
            ];
        }

        $revTitle = "5-Minute Quick Revision: " . $t['name'];
        $revSummary = "Essential Year 6 Brunei PSR revision flashcards for " . $t['name'] . ". Review core concepts, formulas, and key examination facts.";

        $stmtRev->execute([
            $tid,
            $revTitle,
            $revSummary,
            json_encode($cards, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        ]);
        $revCount++;
    }
    echo "   ✓ Generated $revCount 5-minute revision guides.\n\n";

    // 6. Update student 'danish' (ID 3) to Year 6 (PSR Brunei) for instant testing
    echo "6. Updating student account 'danish' to Year 6 (PSR Brunei)...\n";
    $updDanish = $pdo->prepare("UPDATE students SET grade_level = 'Year 6 (PSR Brunei)' WHERE username = 'danish'");
    $updDanish->execute();
    if ($updDanish->rowCount() > 0) {
        echo "   ✓ Updated student 'danish' to 'Year 6 (PSR Brunei)'.\n";
    } else {
        echo "   ℹ Student 'danish' was already updated or not found.\n";
    }

    echo "\n" . str_repeat('=', 65) . "\n";
    echo "🎉 YEAR 6 BRUNEI PSR CURRICULUM SEEDING COMPLETED SUCCESSFULLY!\n";
    echo "   - Subjects: 5 PSR Subjects Seeded\n";
    echo "   - Topics: 21 PSR Topics Seeded\n";
    echo "   - Questions: 630 Questions Seeded (30 per topic)\n";
    echo "   - Revision Guides: 21 Guides Seeded\n";
    echo "   - Grade Isolation: Strict 'Year 6 (PSR)' tag applied\n";
    echo str_repeat('=', 65) . "\n\n";

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "\n❌ SEEDING FAILED: " . $e->getMessage() . "\n";
    exit(1);
}
