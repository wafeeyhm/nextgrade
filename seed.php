<?php
// NextGrade Comprehensive Database Seeder
// Seeds Subjects, Topics, 5-Minute Revision Guides, and 60+ Detailed Questions

require_once __DIR__ . '/db.php';

header('Content-Type: text/plain; charset=utf-8');
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

    // 3. REVISION GUIDES (5 MINUTES INTERACTIVE FLASHCARDS & SUMMARIES)
    $revisions = [
        'bm_bulan' => [
            'title' => 'Ulang Kaji: 12 Bulan dalam Setahun',
            'summary' => 'Setiap tahun ada 12 bulan yang tersusun dari Januari hingga Disember.',
            'cards' => [
                ['title' => 'Bulan 1 hingga 4', 'text' => '1. Januari, 2. Februari, 3. Mac, 4. April. Bulan pertama ialah Januari.', 'icon' => '🌸'],
                ['title' => 'Bulan 5 hingga 8', 'text' => '5. Mei, 6. Jun, 7. Julai, 8. Ogos. Bulan Kemerdekaan Malaysia ialah Ogos!', 'icon' => '🇲🇾'],
                ['title' => 'Bulan 9 hingga 12', 'text' => '9. September, 10. Oktober, 11. November, 12. Disember. Bulan ke-12 dan terakhir ialah Disember.', 'icon' => '❄️'],
                ['title' => 'Tip Mudah Ingat', 'text' => 'Ingat turutan dari awal: Jan, Feb, Mac, Apr, Mei, Jun, Jul, Ogo, Sep, Okt, Nov, Dis!', 'icon' => '💡']
            ]
        ],
        'bm_suku_kata' => [
            'title' => 'Ulang Kaji: Gabungan Suku Kata',
            'summary' => 'Suku kata terbuka dibina daripada gabungan huruf konsonan dan vokal (KV).',
            'cards' => [
                ['title' => 'Contoh Ku + Da = Kuda', 'text' => 'Bunyi "Ku" ditambah "Da" menjadi "Kuda", haiwan yang berlari pantas.', 'icon' => '🐎'],
                ['title' => 'Contoh Ba + Ju = Baju', 'text' => 'Bunyi "Ba" ditambah "Ju" menjadi "Baju" yang kita pakai setiap hari.', 'icon' => '👕'],
                ['title' => 'Contoh Ka + Tak = Katak', 'text' => 'Bunyi "Ka" ditambah "Tak" menjadi "Katak" yang melompat di kolam.', 'icon' => '🐸'],
                ['title' => 'Contoh Lo + Ri = Lori', 'text' => 'Bunyi "Lo" ditambah "Ri" menjadi "Lori", kenderaan berat pembawa muatan.', 'icon' => '🚛']
            ]
        ],
        'bm_kenderaan' => [
            'title' => 'Ulang Kaji: Laluan Kenderaan',
            'summary' => 'Kenderaan bergerak di 3 laluan utama: Darat (jalan raya/rel), Air (sungai/laut), dan Udara (langit).',
            'cards' => [
                ['title' => 'Kenderaan Darat', 'text' => 'Kereta, Lori, Bas, Basikal dan Kereta Api. Semuanya mempunyai roda dan bergerak di atas jalan atau landasan.', 'icon' => '🚗'],
                ['title' => 'Kenderaan Air', 'text' => 'Bot, Sampan, Feri dan Kapal Persiaran. Semuanya terapung dan bergerak di atas permukaan air.', 'icon' => '🚢'],
                ['title' => 'Kenderaan Udara', 'text' => 'Kapal Terbang, Helikopter dan Belon Udara Panas. Semuanya terbang meluncur di ruang udara dan langit.', 'icon' => '✈️']
            ]
        ],
        'bm_binatang' => [
            'title' => 'Ulang Kaji: Kaki Haiwan (2 vs 4 Kaki)',
            'summary' => 'Perhatikan bilangan kaki haiwan untuk mengelaskannya dengan tepat.',
            'cards' => [
                ['title' => 'Haiwan Berkaki 2', 'text' => 'Ayam, Itik, Burung, dan Penguin. Kebanyakan haiwan berkaki 2 juga mempunyai sepasang sayap!', 'icon' => '🦆'],
                ['title' => 'Haiwan Berkaki 4', 'text' => 'Kucing, Lembu, Kambing, Kuda, Gajah, Singa dan Harimau.', 'icon' => '🐄'],
                ['title' => 'Tip Pemerhatian', 'text' => 'Kira kaki depan dan kaki belakang. Jika ada 4, ia haiwan berkaki empat!', 'icon' => '🔍']
            ]
        ],
        'bm_ini_itu' => [
            'title' => 'Ulang Kaji: Kata Tunjuk "Ini" dan "Itu"',
            'summary' => '"Ini" merujuk objek berhampiran dengan kita. "Itu" merujuk objek yang berada jauh.',
            'cards' => [
                ['title' => 'Penggunaan "Ini"', 'text' => 'Gunakan "Ini" apabila objek sangat dekat dengan tangan kita. Contoh: "Ini pensel saya."', 'icon' => '👇'],
                ['title' => 'Penggunaan "Itu"', 'text' => 'Gunakan "Itu" apabila objek berada jauh atau perlu ditunjuk dengan anak panah panjang. Contoh: "Itu burung di pokok."', 'icon' => '👉']
            ]
        ],
        'math_clocks' => [
            'title' => 'Revision: Reading Clocks & Hands',
            'summary' => 'Analog clocks have numbers 1 to 12 with a short hour hand and a long minute hand.',
            'cards' => [
                ['title' => 'Short Hand (Jarum Pendek)', 'text' => 'Points to the HOUR (Jam). If it points to 3, it is hour 3.', 'icon' => '🕰️'],
                ['title' => 'Long Hand (Jarum Panjang)', 'text' => 'Points to the MINUTES (Minit). When it points straight up at 12, it is exactly o\'clock (:00).', 'icon' => '⬆️'],
                ['title' => 'Half Past (:30)', 'text' => 'When the long hand points straight down at 6, it means 30 minutes (setengah jam).', 'icon' => '⬇️']
            ]
        ],
        'math_descending' => [
            'title' => 'Revision: Descending Order (20 to 1)',
            'summary' => 'Descending order means counting backward from the biggest number down to the smallest.',
            'cards' => [
                ['title' => 'Start from 20', 'text' => '20, 19, 18, 17, 16, 15, 14, 13, 12, 11...', 'icon' => '2️⃣0️⃣'],
                ['title' => 'Down to 1', 'text' => '10, 9, 8, 7, 6, 5, 4, 3, 2, 1! Every step goes down by 1.', 'icon' => '1️⃣']
            ]
        ],
        'math_addition' => [
            'title' => 'Revision: Addition (+) Put Together',
            'summary' => 'Addition means combining two sets together to get a bigger total number.',
            'cards' => [
                ['title' => 'Plus Sign (+)', 'text' => 'The plus symbol means "and more". 4 apples + 3 apples = 7 apples!', 'icon' => '🍎'],
                ['title' => 'Counting On Strategy', 'text' => 'Start with the bigger number in your head, then count on your fingers for the smaller number.', 'icon' => '🧠']
            ]
        ],
        'math_subtraction' => [
            'title' => 'Revision: Subtraction (-) Take Away',
            'summary' => 'Subtraction means taking some items away to find how many are left.',
            'cards' => [
                ['title' => 'Minus Sign (-)', 'text' => 'The minus symbol means "take away". 10 balloons - 4 popped = 6 balloons left.', 'icon' => '🎈'],
                ['title' => 'Counting Back', 'text' => 'Start at the first number and step backward to find the answer.', 'icon' => '👈']
            ]
        ],
        'eng_days_months' => [
            'title' => 'Revision: Days & Months Sequence',
            'summary' => 'There are 7 days in a week and 12 months in a full year.',
            'cards' => [
                ['title' => '7 Days of the Week', 'text' => 'Sunday, Monday, Tuesday, Wednesday, Thursday, Friday, Saturday.', 'icon' => '🗓️'],
                ['title' => 'Month Numbers', 'text' => 'January = 1st month, February = 2nd, March = 3rd, August = 8th, December = 12th.', 'icon' => '1️⃣']
            ]
        ],
        'eng_blending' => [
            'title' => 'Revision: Initial Blends (ch- & th-)',
            'summary' => 'Two letters team up at the start of words to make exciting phonics sounds!',
            'cards' => [
                ['title' => 'The "ch-" Blend', 'text' => 'Sounds like "ch-ch-ch" as in Chair, Chick, Chin, Cheese, and Cherry.', 'icon' => '🪑'],
                ['title' => 'The "th-" Blend', 'text' => 'Sounds like "th-th-th" as in Thorn, Thick, Thin, Thumb, and Three.', 'icon' => '🌹']
            ]
        ],
        'eng_pronouns' => [
            'title' => 'Revision: Pronouns (He, She, It, They)',
            'summary' => 'Pronouns take the place of names so we do not have to repeat them.',
            'cards' => [
                ['title' => 'He', 'text' => 'Use "He" for one boy, man, or male figure. (e.g. He is a doctor.)', 'icon' => '👦'],
                ['title' => 'She', 'text' => 'Use "She" for one girl, woman, or female figure. (e.g. She has a red bow.)', 'icon' => '👧'],
                ['title' => 'It', 'text' => 'Use "It" for ONE single object, plant, animal or vehicle. (e.g. It is a truck.)', 'icon' => '📦'],
                ['title' => 'They', 'text' => 'Use "They" for MORE THAN ONE person, toys, balls, or pencils. (e.g. They are playing.)', 'icon' => '👫']
            ]
        ],
        'eng_articles' => [
            'title' => 'Revision: Articles (A vs An)',
            'summary' => 'Use "An" before vowel sounds (A, E, I, O, U). Use "A" before all consonant sounds.',
            'cards' => [
                ['title' => 'When to use "An"', 'text' => 'Use before vowel sounds: an apple, an egg, an ice cream, an octopus, an umbrella.', 'icon' => '🍎'],
                ['title' => 'When to use "A"', 'text' => 'Use before consonant sounds: a cat, a house, a book, a banana, a bike.', 'icon' => '🐱']
            ]
        ],
        'eng_has_have' => [
            'title' => 'Revision: Has vs Have Rules',
            'summary' => 'Singular subjects use "has", while plural subjects and I/You use "have".',
            'cards' => [
                ['title' => 'Use "HAS"', 'text' => 'He has, She has, It has, The boy has. (Singular)', 'icon' => '☝️'],
                ['title' => 'Use "HAVE"', 'text' => 'I have, You have, We have, They have, Amin and Aman have. (Plural + I)', 'icon' => '✌️']
            ]
        ],
        'eng_demonstratives' => [
            'title' => 'Revision: This, That, These, Those',
            'summary' => 'Match distance (Near vs Far) with quantity (1 item vs Many items).',
            'cards' => [
                ['title' => 'Near to Us', 'text' => 'This (1 item close by) | These (many items close by)', 'icon' => '👇'],
                ['title' => 'Far Away', 'text' => 'That (1 item far away) | Those (many items far away)', 'icon' => '👉']
            ]
        ],
        'eng_comprehension' => [
            'title' => 'Revision: Reading Comprehension Secrets',
            'summary' => 'Look closely at the story details: names, colors, shapes, and actions.',
            'cards' => [
                ['title' => 'Read Slowly & Carefully', 'text' => 'Read every sentence and imagine what is happening in the picture.', 'icon' => '👀'],
                ['title' => 'Find the Clue in the Text', 'text' => 'The answer is always right inside the story! Match keywords with the question.', 'icon' => '🎯']
            ]
        ],
        'sci_land_sea' => [
            'title' => 'Revision: Land vs Sea Habitats',
            'summary' => 'Land animals live on solid ground, while marine animals swim and breathe underwater in the ocean.',
            'cards' => [
                ['title' => 'Land Animals', 'text' => 'Cows, Cats, Horses, Monkeys, and Lions live on farms, grasslands, or in forests.', 'icon' => '🦁'],
                ['title' => 'Marine / Sea Animals', 'text' => 'Fish, Sharks, Whales, Dolphins, Crabs, and Octopuses thrive in oceans and seas.', 'icon' => '🐬']
            ]
        ],
        'sci_sink_float' => [
            'title' => 'Revision: Sink vs Float Experiments',
            'summary' => 'Heavy, dense objects sink down to the bottom. Light objects with trapped air float on top.',
            'cards' => [
                ['title' => 'Objects that Sink', 'text' => 'Metal keys, heavy stones, coins, and iron spoons sink straight to the bottom of the water.', 'icon' => '⚓'],
                ['title' => 'Objects that Float', 'text' => 'Plastic balls, dry wooden twigs, rubber ducks, and leaves float gently on the water surface.', 'icon' => '🪵']
            ]
        ],
        'sci_celestial' => [
            'title' => 'Revision: Space Objects & Planet Earth',
            'summary' => 'The Sun, Moon, Stars, and our planet Earth are all part of our incredible universe.',
            'cards' => [
                ['title' => 'The Sun', 'text' => 'A giant glowing star that gives Earth bright light and warm energy during the daytime.', 'icon' => '☀️'],
                ['title' => 'The Moon & Stars', 'text' => 'The Moon orbits Earth and shines at night alongside millions of twinkling stars.', 'icon' => '🌙'],
                ['title' => 'Planet Earth', 'text' => 'Our blue planet with oceans, continents, and air where humans and animals live.', 'icon' => '🌍']
            ]
        ],
        'sci_materials' => [
            'title' => 'Revision: Metal, Glass & Paper Materials',
            'summary' => 'Different objects around us are crafted from materials with unique properties.',
            'cards' => [
                ['title' => 'Metal', 'text' => 'Hard, shiny, and strong. Examples: Keys, spoons, nails, and coins.', 'icon' => '🥄'],
                ['title' => 'Glass', 'text' => 'Clear and transparent, but fragile. Examples: Drinking glasses, windows, and spectacles.', 'icon' => '🥛'],
                ['title' => 'Paper', 'text' => 'Lightweight, flexible, and easy to fold. Examples: Storybooks, newspapers, and notebooks.', 'icon' => '📖']
            ]
        ],
        'sci_pollution' => [
            'title' => 'Revision: Types of Environmental Pollution',
            'summary' => 'Pollution damages our beautiful nature when waste and toxins are not properly handled.',
            'cards' => [
                ['title' => 'Air Pollution', 'text' => 'Black smoke and fumes from factory chimneys and car exhausts contaminate the clean air.', 'icon' => '🏭'],
                ['title' => 'Water / Sea Pollution', 'text' => 'Plastic trash, toxic runoff, and oil spills pollute rivers and oceans, harming marine life.', 'icon' => '🌊'],
                ['title' => 'Land Pollution', 'text' => 'Piles of household garbage and litter dumped onto open grounds damage the soil.', 'icon' => '🗑️']
            ]
        ],
        'sci_plants' => [
            'title' => 'Revision: Parts & Growth Needs of a Plant',
            'summary' => 'Plants need proper care, soil nutrients, and environment to blossom and grow.',
            'cards' => [
                ['title' => 'Parts of a Plant', 'text' => 'Roots (absorb water), Stem (supports the plant), Leaves (make food from light), Flower & Fruit.', 'icon' => '🌱'],
                ['title' => '3 Things Plants Need', 'text' => 'Warm Sunlight, Fresh Air, and Clean Water to stay green and healthy.', 'icon' => '💧']
            ]
        ],
        'ict_storage' => [
            'title' => 'Revision: Computer Storage Drives',
            'summary' => 'Storage devices keep photos, music, video games, and documents safely stored.',
            'cards' => [
                ['title' => 'USB Pendrive', 'text' => 'Small, pocket-sized flash stick that plugs into a computer USB port.', 'icon' => '💾'],
                ['title' => 'CD-ROM Disc', 'text' => 'A round shiny disc read with a laser beam to install games and software.', 'icon' => '💿'],
                ['title' => 'Hard Disk Drive (HDD)', 'text' => 'Large primary internal drive housed inside the system unit.', 'icon' => '🗄️'],
                ['title' => 'Memory Card & Floppy Disk', 'text' => 'Memory cards are thin chips for digital cameras; floppy disks are historic magnetic storage.', 'icon' => '🃏']
            ]
        ],
        'ict_parts' => [
            'title' => 'Revision: Basic Computer Peripherals',
            'summary' => 'A desktop computer consists of several key parts working seamlessly together.',
            'cards' => [
                ['title' => 'Monitor Screen', 'text' => 'The screen displays colorful graphics, text, videos, and games for us to see.', 'icon' => '🖥️'],
                ['title' => 'Keyboard & Mouse', 'text' => 'The keyboard has keys to type text; the mouse moves the pointer and clicks buttons.', 'icon' => '⌨️'],
                ['title' => 'Printer & Scanner', 'text' => 'The printer puts digital work onto paper; the scanner copies paper pictures into the computer.', 'icon' => '🖨️']
            ]
        ],
        'ict_counting' => [
            'title' => 'Revision: Counting Computer Devices',
            'summary' => 'Carefully point and count each computer peripheral item one by one.',
            'cards' => [
                ['title' => 'Counting Strategy', 'text' => 'Point your finger at each mouse, screen, or keyboard: 1, 2, 3, 4! Never count the same item twice.', 'icon' => '🔢']
            ]
        ],
        'ict_spelling' => [
            'title' => 'Revision: Spelling Computer Peripherals',
            'summary' => 'Master the vowels (A, E, I, O, U) that complete each peripheral name.',
            'cards' => [
                ['title' => 'PENDRIVE', 'text' => 'P - E - N - D - R - I - V - E (vowels E and I)', 'icon' => '💾'],
                ['title' => 'SCANNER', 'text' => 'S - C - A - N - N - E - R (vowels A and E)', 'icon' => '📠'],
                ['title' => 'WEBCAM', 'text' => 'W - E - B - C - A - M (vowels E and A)', 'icon' => '📹'],
                ['title' => 'PRINTER', 'text' => 'P - R - I - N - T - E - R (vowels I and E)', 'icon' => '🖨️']
            ]
        ],
        'ict_input_output' => [
            'title' => 'Revision: Input (I) vs Output (O) Devices',
            'summary' => 'Input puts commands and data INTO the computer. Output sends results OUT to you.',
            'cards' => [
                ['title' => 'Input Devices (I)', 'text' => 'Keyboard (types in), Mouse (clicks in), Microphone (voice in), Scanner (photo in), Webcam.', 'icon' => '📥'],
                ['title' => 'Output Devices (O)', 'text' => 'Monitor (displays out), Printer (prints out on paper), Speakers & Headphones (play sound out).', 'icon' => '📤']
            ]
        ]
    ];

    $stmtRev = $pdo->prepare("INSERT INTO revisions (topic_id, title, summary, content_json) VALUES (?, ?, ?, ?)");
    foreach ($revisions as $tId => $r) {
        $stmtRev->execute([$tId, $r['title'], $r['summary'], json_encode($r['cards'], JSON_UNESCAPED_UNICODE)]);
    }
    echo "✓ Seeded " . count($revisions) . " Revision Modules.\n";

    // 4. QUESTIONS BANK (Rich, diverse, covering 100% of guidelines)
    $questions = [
        // === BAHASA MELAYU ===
        // Topic 1: 12 Bulan dalam Setahun
        [
            'topic_id' => 'bm_bulan',
            'subject_id' => 'bahasa_melayu',
            'question_text' => 'Susun semula 4 bulan pertama mengikut urutan yang betul:',
            'question_audio' => 'Susun semula empat bulan pertama mengikut urutan yang betul.',
            'lang' => 'ms',
            'question_type' => 'ordering',
            'image_url' => 'images/science/sun.svg',
            'passage' => null,
            'options_json' => json_encode(['Januari', 'Februari', 'Mac', 'April']),
            'correct_answer' => json_encode(['Januari', 'Februari', 'Mac', 'April']),
            'hint_text' => 'Bulan pertama bermula dengan huruf J (Januari) diikuti oleh Februari.',
            'hint_audio' => 'Bulan pertama bermula dengan huruf J, iaitu Januari.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'bm_bulan',
            'subject_id' => 'bahasa_melayu',
            'question_text' => 'Apakah nama bulan selepas bulan Ogos?',
            'question_audio' => 'Apakah nama bulan selepas bulan Ogos?',
            'lang' => 'ms',
            'question_type' => 'multiple_choice',
            'image_url' => null,
            'passage' => null,
            'options_json' => json_encode(['September', 'Oktober', 'Julai', 'Mei']),
            'correct_answer' => 'September',
            'hint_text' => 'Bulan kemerdekaan ialah Ogos. Bulan seterusnya bermula dengan huruf S.',
            'hint_audio' => 'Bulan seterusnya bermula dengan huruf S, iaitu September.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'bm_bulan',
            'subject_id' => 'bahasa_melayu',
            'question_text' => 'Apakah bulan terakhir dalam setahun?',
            'question_audio' => 'Apakah bulan terakhir dalam setahun?',
            'lang' => 'ms',
            'question_type' => 'multiple_choice',
            'image_url' => null,
            'passage' => null,
            'options_json' => json_encode(['Disember', 'November', 'Januari', 'Oktober']),
            'correct_answer' => 'Disember',
            'hint_text' => 'Bulan ke-12 yang menyambut cuti akhir tahun bermula dengan huruf D.',
            'hint_audio' => 'Bulan ke dua belas ialah Disember.',
            'meta_data_json' => null
        ],

        // Topic 2: Pecahkan Suku Kata
        [
            'topic_id' => 'bm_suku_kata',
            'subject_id' => 'bahasa_melayu',
            'question_text' => 'Pecahkan perkataan KUDA kepada suku kata yang betul:',
            'question_audio' => 'Pecahkan perkataan Kuda kepada suku kata yang betul.',
            'lang' => 'ms',
            'question_type' => 'syllable_split',
            'image_url' => 'images/binatang/kuda.jpg',
            'passage' => null,
            'options_json' => json_encode(['da', 'ku', 'ka', 'di']),
            'correct_answer' => 'da',
            'hint_text' => 'Suku kata pertama ialah "Ku". Apakah suku kata kedua untuk melengkapkannya?',
            'hint_audio' => 'Ku tambah da sama dengan Kuda.',
            'meta_data_json' => json_encode(['first' => 'Ku', 'target' => 'da', 'word' => 'Kuda'])
        ],
        [
            'topic_id' => 'bm_suku_kata',
            'subject_id' => 'bahasa_melayu',
            'question_text' => 'Pecahkan perkataan KATAK kepada suku kata yang betul:',
            'question_audio' => 'Pecahkan perkataan Katak kepada suku kata yang betul.',
            'lang' => 'ms',
            'question_type' => 'syllable_split',
            'image_url' => 'images/binatang/katak.jpg',
            'passage' => null,
            'options_json' => json_encode(['tak', 'tuk', 'tik', 'tok']),
            'correct_answer' => 'tak',
            'hint_text' => 'Suku kata pertama ialah "Ka". Lengkapkan perkataan haiwan yang melompat ini.',
            'hint_audio' => 'Ka tambah tak sama dengan Katak.',
            'meta_data_json' => json_encode(['first' => 'Ka', 'target' => 'tak', 'word' => 'Katak'])
        ],
        [
            'topic_id' => 'bm_suku_kata',
            'subject_id' => 'bahasa_melayu',
            'question_text' => 'Pecahkan perkataan BAJU kepada suku kata yang betul:',
            'question_audio' => 'Pecahkan perkataan Baju kepada suku kata yang betul.',
            'lang' => 'ms',
            'question_type' => 'syllable_split',
            'image_url' => 'images/suku-kata/baju.jpg',
            'passage' => null,
            'options_json' => json_encode(['ju', 'ji', 'ja', 'jo']),
            'correct_answer' => 'ju',
            'hint_text' => 'Suku kata pertama "Ba". Pilih suku kata seterusnya.',
            'hint_audio' => 'Ba tambah ju sama dengan Baju.',
            'meta_data_json' => json_encode(['first' => 'Ba', 'target' => 'ju', 'word' => 'Baju'])
        ],
        [
            'topic_id' => 'bm_suku_kata',
            'subject_id' => 'bahasa_melayu',
            'question_text' => 'Pecahkan perkataan LORI kepada suku kata yang betul:',
            'question_audio' => 'Pecahkan perkataan Lori kepada suku kata yang betul.',
            'lang' => 'ms',
            'question_type' => 'syllable_split',
            'image_url' => 'images/kenderaan/lori.jpg',
            'passage' => null,
            'options_json' => json_encode(['ri', 'ra', 'ro', 'ru']),
            'correct_answer' => 'ri',
            'hint_text' => 'Lo tambah ri sama dengan Lori.',
            'hint_audio' => 'Lo tambah ri sama dengan Lori.',
            'meta_data_json' => json_encode(['first' => 'Lo', 'target' => 'ri', 'word' => 'Lori'])
        ],
        [
            'topic_id' => 'bm_suku_kata',
            'subject_id' => 'bahasa_melayu',
            'question_text' => 'Pecahkan perkataan API kepada suku kata yang betul:',
            'question_audio' => 'Pecahkan perkataan Api kepada suku kata yang betul.',
            'lang' => 'ms',
            'question_type' => 'syllable_split',
            'image_url' => 'images/binatang/ayam.jpg',
            'passage' => null,
            'options_json' => json_encode(['pi', 'pa', 'po', 'pu']),
            'correct_answer' => 'pi',
            'hint_text' => 'Huruf pertama "A". Lengkapkan perkataan Api.',
            'hint_audio' => 'A tambah pi sama dengan Api.',
            'meta_data_json' => json_encode(['first' => 'A', 'target' => 'pi', 'word' => 'Api'])
        ],

        // Topic 3: Kenderaan Darat, Air dan Udara
        [
            'topic_id' => 'bm_kenderaan',
            'subject_id' => 'bahasa_melayu',
            'question_text' => 'Lihat gambar bot ini. Di manakah bot ini bergerak?',
            'question_audio' => 'Lihat gambar bot ini. Di manakah bot ini bergerak?',
            'lang' => 'ms',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/kenderaan/bot.jpg',
            'passage' => null,
            'options_json' => json_encode(['Air', 'Darat', 'Udara']),
            'correct_answer' => 'Air',
            'hint_text' => 'Bot berenang dan terapung di atas permukaan tasik dan sungai.',
            'hint_audio' => 'Bot adalah kenderaan air.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'bm_kenderaan',
            'subject_id' => 'bahasa_melayu',
            'question_text' => 'Lihat gambar kapal terbang ini. Di manakah kapal terbang bergerak?',
            'question_audio' => 'Lihat gambar kapal terbang ini. Di manakah kapal terbang bergerak?',
            'lang' => 'ms',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/kenderaan/kapal_terbang.jpg',
            'passage' => null,
            'options_json' => json_encode(['Udara', 'Darat', 'Air']),
            'correct_answer' => 'Udara',
            'hint_text' => 'Kapal terbang mempunyai sayap dan meluncur tinggi di langit biru.',
            'hint_audio' => 'Kapal terbang terbang di udara.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'bm_kenderaan',
            'subject_id' => 'bahasa_melayu',
            'question_text' => 'Lihat gambar lori ini. Di manakah lori bergerak?',
            'question_audio' => 'Lihat gambar lori ini. Di manakah lori bergerak?',
            'lang' => 'ms',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/kenderaan/lori.jpg',
            'passage' => null,
            'options_json' => json_encode(['Darat', 'Udara', 'Air']),
            'correct_answer' => 'Darat',
            'hint_text' => 'Lori mempunyai roda getah dan dipandu di atas jalan raya.',
            'hint_audio' => 'Lori bergerak di atas darat.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'bm_kenderaan',
            'subject_id' => 'bahasa_melayu',
            'question_text' => 'Lihat helikopter ini. Di manakah laluan pergerakannya?',
            'question_audio' => 'Lihat helikopter ini. Di manakah laluan pergerakannya?',
            'lang' => 'ms',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/kenderaan/helikopter.jpg',
            'passage' => null,
            'options_json' => json_encode(['Udara', 'Darat', 'Air']),
            'correct_answer' => 'Udara',
            'hint_text' => 'Bilah kipas helikopter berputar di atas untuk terbang di langit.',
            'hint_audio' => 'Helikopter bergerak di udara.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'bm_kenderaan',
            'subject_id' => 'bahasa_melayu',
            'question_text' => 'Lihat kapal persiaran (cruise ship) ini. Di manakah ia belayar?',
            'question_audio' => 'Lihat kapal persiaran ini. Di manakah ia belayar?',
            'lang' => 'ms',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/kenderaan/kapal.jpg',
            'passage' => null,
            'options_json' => json_encode(['Air', 'Darat', 'Udara']),
            'correct_answer' => 'Air',
            'hint_text' => 'Kapal besar ini terapung dan belayar merentasi lautan luas.',
            'hint_audio' => 'Kapal persiaran bergerak di air.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'bm_kenderaan',
            'subject_id' => 'bahasa_melayu',
            'question_text' => 'Lihat kereta ini. Apakah laluan bagi kereta?',
            'question_audio' => 'Lihat kereta ini. Apakah laluan bagi kereta?',
            'lang' => 'ms',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/kenderaan/kereta.jpg',
            'passage' => null,
            'options_json' => json_encode(['Darat', 'Udara', 'Air']),
            'correct_answer' => 'Darat',
            'hint_text' => 'Kereta mempunyai 4 roda dan bergerak di atas jalan tar.',
            'hint_audio' => 'Kereta bergerak di atas darat.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'bm_kenderaan',
            'subject_id' => 'bahasa_melayu',
            'question_text' => 'Lihat belon udara panas ini. Di manakah ia terapung?',
            'question_audio' => 'Lihat belon udara panas ini. Di manakah ia terapung?',
            'lang' => 'ms',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/kenderaan/kapal_angkasa.jpg',
            'passage' => null,
            'options_json' => json_encode(['Udara', 'Darat', 'Air']),
            'correct_answer' => 'Udara',
            'hint_text' => 'Belon udara menggunakan udara panas untuk naik tinggi ke angkasa.',
            'hint_audio' => 'Belon udara panas terbang di udara.',
            'meta_data_json' => null
        ],

        // Topic 4: Binatang (2 Kaki vs 4 Kaki)
        [
            'topic_id' => 'bm_binatang',
            'subject_id' => 'bahasa_melayu',
            'question_text' => 'Manakah antara haiwan berikut mempunyai 2 KAKI?',
            'question_audio' => 'Manakah antara haiwan berikut mempunyai dua kaki?',
            'lang' => 'ms',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/binatang/ayam.jpg',
            'passage' => null,
            'options_json' => json_encode(['Ayam', 'Kucing', 'Lembu', 'Kuda']),
            'correct_answer' => 'Ayam',
            'hint_text' => 'Haiwan ini bertelur dan mempunyai sepasang sayap serta 2 kaki.',
            'hint_audio' => 'Ayam mempunyai dua kaki.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'bm_binatang',
            'subject_id' => 'bahasa_melayu',
            'question_text' => 'Manakah antara haiwan berikut mempunyai 4 KAKI?',
            'question_audio' => 'Manakah antara haiwan berikut mempunyai empat kaki?',
            'lang' => 'ms',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/binatang/kambing.jpg',
            'passage' => null,
            'options_json' => json_encode(['Kambing', 'Burung', 'Itik', 'Penguin']),
            'correct_answer' => 'Kambing',
            'hint_text' => 'Haiwan mamalia berbunyi "mbeee" dan berjalan dengan 4 kaki.',
            'hint_audio' => 'Kambing mempunyai empat kaki.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'bm_binatang',
            'subject_id' => 'bahasa_melayu',
            'question_text' => 'Berapakah bilangan kaki bagi seekor ITIK?',
            'question_audio' => 'Berapakah bilangan kaki bagi seekor itik?',
            'lang' => 'ms',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/binatang/itik.jpg',
            'passage' => null,
            'options_json' => json_encode(['2 kaki', '4 kaki', '6 kaki', 'Tiada kaki']),
            'correct_answer' => '2 kaki',
            'hint_text' => 'Itik tergolong dalam kumpulan unggas berkaki dua selaput renang.',
            'hint_audio' => 'Itik mempunyai dua kaki.',
            'meta_data_json' => null
        ],

        // Topic 5: Penggunaan Ini dan Itu
        [
            'topic_id' => 'bm_ini_itu',
            'subject_id' => 'bahasa_melayu',
            'question_text' => 'Pilih ayat yang betul bagi basikal berdekatan ini:',
            'question_audio' => 'Pilih ayat yang betul bagi basikal berdekatan ini.',
            'lang' => 'ms',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/ini-itu/basikal_dekat.svg',
            'passage' => null,
            'options_json' => json_encode(['Ini basikal Qawi', 'Itu basikal Qawi', 'Sana basikal Qawi']),
            'correct_answer' => 'Ini basikal Qawi',
            'hint_text' => 'Objek berada sangat dekat dengan kita, gunakan kata tunjuk "Ini".',
            'hint_audio' => 'Gunakan Ini untuk benda yang dekat.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'bm_ini_itu',
            'subject_id' => 'bahasa_melayu',
            'question_text' => 'Pilih ayat yang betul bagi hadiah yang berada jauh di sana:',
            'question_audio' => 'Pilih ayat yang betul bagi hadiah yang berada jauh di sana.',
            'lang' => 'ms',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/ini-itu/hadiah_jauh.svg',
            'passage' => null,
            'options_json' => json_encode(['Itu hadiah Rina', 'Ini hadiah Rina', 'Sini hadiah Rina']),
            'correct_answer' => 'Itu hadiah Rina',
            'hint_text' => 'Anak panah panjang menunjukkan objek berada jauh di sana: gunakan "Itu".',
            'hint_audio' => 'Gunakan Itu untuk objek yang jauh.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'bm_ini_itu',
            'subject_id' => 'bahasa_melayu',
            'question_text' => 'Lihat jus oren di atas meja berhampiran:',
            'question_audio' => 'Lihat jus oren di atas meja berhampiran.',
            'lang' => 'ms',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/ini-itu/jus_oren_dekat.svg',
            'passage' => null,
            'options_json' => json_encode(['Ini jus oren', 'Itu jus oren', 'Mereka jus oren']),
            'correct_answer' => 'Ini jus oren',
            'hint_text' => 'Jus oren berada betul-betul di hadapan kita: "Ini jus oren".',
            'hint_audio' => 'Ini jus oren.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'bm_ini_itu',
            'subject_id' => 'bahasa_melayu',
            'question_text' => 'Gunting berada dekat di tangan kita. Lengkapkan ayat:',
            'question_audio' => 'Gunting berada dekat di tangan kita. Lengkapkan ayat.',
            'lang' => 'ms',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/ini-itu/gunting_dekat.svg',
            'passage' => null,
            'options_json' => json_encode(['Ini gunting', 'Itu gunting', 'Dia gunting']),
            'correct_answer' => 'Ini gunting',
            'hint_text' => 'Objek berdekatan sentiasa menggunakan "Ini".',
            'hint_audio' => 'Ini gunting.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'bm_ini_itu',
            'subject_id' => 'bahasa_melayu',
            'question_text' => 'Bunga ros mekar jauh di taman sana. Lengkapkan ayat:',
            'question_audio' => 'Bunga ros mekar jauh di taman sana. Lengkapkan ayat.',
            'lang' => 'ms',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/ini-itu/bunga_ros_jauh.svg',
            'passage' => null,
            'options_json' => json_encode(['Itu bunga ros', 'Ini bunga ros', 'Kami bunga ros']),
            'correct_answer' => 'Itu bunga ros',
            'hint_text' => 'Objek yang jauh ditunjuk dengan perkataan "Itu".',
            'hint_audio' => 'Itu bunga ros.',
            'meta_data_json' => null
        ],

        // === MATHEMATICS ===
        // Topic 6: Reading Clock Numbers & Hands
        [
            'topic_id' => 'math_clocks',
            'subject_id' => 'maths',
            'question_text' => 'Look at the clock. What time is shown on the clock?',
            'question_audio' => 'Look at the clock. What time is shown on the clock?',
            'lang' => 'en',
            'question_type' => 'clock_analog',
            'image_url' => 'images/maths/clock_3_00.svg',
            'passage' => null,
            'options_json' => json_encode(['3:00', '12:00', '6:00', '9:00']),
            'correct_answer' => '3:00',
            'hint_text' => 'Short hand points to 3. Long hand points to 12. It is 3 o\'clock!',
            'hint_audio' => 'The short hand points to 3, so it is 3 o\'clock.',
            'meta_data_json' => json_encode(['hours' => 3, 'minutes' => 0])
        ],
        [
            'topic_id' => 'math_clocks',
            'subject_id' => 'maths',
            'question_text' => 'At 7:00, where does the short hand point and where does the long hand point?',
            'question_audio' => 'At 7 o\'clock, where does the short hand and long hand point?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/maths/clock_7_00.svg',
            'passage' => null,
            'options_json' => json_encode(['Short hand: 7, Long hand: 12', 'Short hand: 12, Long hand: 7', 'Short hand: 6, Long hand: 7', 'Short hand: 7, Long hand: 6']),
            'correct_answer' => 'Short hand: 7, Long hand: 12',
            'hint_text' => 'The short hand shows the hour (7) and the long minute hand points to 12.',
            'hint_audio' => 'Short hand points to 7 and long hand points to 12.',
            'meta_data_json' => json_encode(['hours' => 7, 'minutes' => 0])
        ],
        [
            'topic_id' => 'math_clocks',
            'subject_id' => 'maths',
            'question_text' => 'The short hand is at 9 and the long hand is at 12. What time is it?',
            'question_audio' => 'The short hand is at 9 and the long hand is at 12. What time is it?',
            'lang' => 'en',
            'question_type' => 'clock_analog',
            'image_url' => 'images/maths/clock_9_00.svg',
            'passage' => null,
            'options_json' => json_encode(['9:00', '12:00', '3:00', '10:00']),
            'correct_answer' => '9:00',
            'hint_text' => 'Hour hand points to 9. It is exactly 9:00.',
            'hint_audio' => 'It is 9 o\'clock.',
            'meta_data_json' => json_encode(['hours' => 9, 'minutes' => 0])
        ],
        [
            'topic_id' => 'math_clocks',
            'subject_id' => 'maths',
            'question_text' => 'The clock shows 6:30 (half past 6). Where does the long minute hand point?',
            'question_audio' => 'The clock shows 6:30. Where does the long minute hand point?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/maths/clock_6_30.svg',
            'passage' => null,
            'options_json' => json_encode(['Number 6', 'Number 12', 'Number 3', 'Number 9']),
            'correct_answer' => 'Number 6',
            'hint_text' => '30 minutes is half an hour, pointing straight down at number 6.',
            'hint_audio' => 'The long hand points straight down at number 6.',
            'meta_data_json' => json_encode(['hours' => 6, 'minutes' => 30])
        ],

        // Topic 7: Descending Numbers 20 to 1
        [
            'topic_id' => 'math_descending',
            'subject_id' => 'maths',
            'question_text' => 'Count backwards from 20: 20, 19, 18, 17, __. What comes next?',
            'question_audio' => 'Count backwards: 20, 19, 18, 17, what comes next?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => null,
            'passage' => null,
            'options_json' => json_encode(['16', '15', '18', '21']),
            'correct_answer' => '16',
            'hint_text' => 'Subtract 1 from 17: 17 minus 1 equals 16.',
            'hint_audio' => '17 minus 1 is 16.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'math_descending',
            'subject_id' => 'maths',
            'question_text' => 'Fill in the missing descending number: 15, 14, 13, __, 11',
            'question_audio' => 'Fill in the missing descending number: 15, 14, 13, blank, 11',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => null,
            'passage' => null,
            'options_json' => json_encode(['12', '10', '14', '16']),
            'correct_answer' => '12',
            'hint_text' => 'What number comes between 13 and 11 when counting down?',
            'hint_audio' => 'The missing number is 12.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'math_descending',
            'subject_id' => 'maths',
            'question_text' => 'Arrange these numbers from BIGGEST to SMALLEST (Descending):',
            'question_audio' => 'Arrange these numbers from biggest to smallest.',
            'lang' => 'en',
            'question_type' => 'ordering',
            'image_url' => null,
            'passage' => null,
            'options_json' => json_encode(['10', '9', '8', '7']),
            'correct_answer' => json_encode(['10', '9', '8', '7']),
            'hint_text' => 'Start with the biggest number: 10, then 9, 8, and finally 7.',
            'hint_audio' => 'Start with 10 down to 7.',
            'meta_data_json' => null
        ],

        // Topic 8: Addition
        [
            'topic_id' => 'math_addition',
            'subject_id' => 'maths',
            'question_text' => 'Calculate: 4 + 3 = ? (Show 4 apples + 3 apples)',
            'question_audio' => 'Calculate: 4 plus 3 equals what?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/english/apple.svg',
            'passage' => null,
            'options_json' => json_encode(['7', '6', '8', '5']),
            'correct_answer' => '7',
            'hint_text' => 'Start at 4 and count forward 3 steps: 5, 6, 7!',
            'hint_audio' => '4 plus 3 equals 7.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'math_addition',
            'subject_id' => 'maths',
            'question_text' => 'Calculate: 6 + 4 = ? (Count 6 stars + 4 stars)',
            'question_audio' => 'Calculate: 6 plus 4 equals what?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/science/star.svg',
            'passage' => null,
            'options_json' => json_encode(['10', '9', '8', '11']),
            'correct_answer' => '10',
            'hint_text' => '6 plus 4 makes a full number ten!',
            'hint_audio' => '6 plus 4 makes 10.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'math_addition',
            'subject_id' => 'maths',
            'question_text' => 'What is 8 + 5?',
            'question_audio' => 'What is 8 plus 5?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => null,
            'passage' => null,
            'options_json' => json_encode(['13', '12', '14', '11']),
            'correct_answer' => '13',
            'hint_text' => '8 + 2 = 10, then add 3 more to make 13.',
            'hint_audio' => '8 plus 5 equals 13.',
            'meta_data_json' => null
        ],

        // Topic 9: Subtraction
        [
            'topic_id' => 'math_subtraction',
            'subject_id' => 'maths',
            'question_text' => 'Calculate: 7 - 3 = ? (7 balloons, 3 popped)',
            'question_audio' => 'Calculate: 7 minus 3 equals what?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => null,
            'passage' => null,
            'options_json' => json_encode(['4', '3', '5', '2']),
            'correct_answer' => '4',
            'hint_text' => 'Hold up 7 fingers and fold down 3 fingers. 4 are left!',
            'hint_audio' => '7 take away 3 leaves 4.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'math_subtraction',
            'subject_id' => 'maths',
            'question_text' => 'Calculate: 10 - 4 = ? (10 cupcakes, 4 eaten)',
            'question_audio' => 'Calculate: 10 minus 4 equals what?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => null,
            'passage' => null,
            'options_json' => json_encode(['6', '5', '7', '4']),
            'correct_answer' => '6',
            'hint_text' => '10 take away 4 leaves 6.',
            'hint_audio' => '10 minus 4 equals 6.',
            'meta_data_json' => null
        ],

        // === ENGLISH ===
        // Topic 10: Days of Week & Month Numbers
        [
            'topic_id' => 'eng_days_months',
            'subject_id' => 'english',
            'question_text' => 'The first day has been filled in which is Sunday. What comes next?',
            'question_audio' => 'The first day is Sunday. What comes next?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => null,
            'passage' => null,
            'options_json' => json_encode(['Monday', 'Tuesday', 'Friday', 'Wednesday']),
            'correct_answer' => 'Monday',
            'hint_text' => 'School week begins right after Sunday on Monday!',
            'hint_audio' => 'After Sunday comes Monday.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'eng_days_months',
            'subject_id' => 'english',
            'question_text' => 'Which number represents the month of March?',
            'question_audio' => 'Which number represents the month of March?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => null,
            'passage' => null,
            'options_json' => json_encode(['Month 3', 'Month 1', 'Month 5', 'Month 8']),
            'correct_answer' => 'Month 3',
            'hint_text' => '1. January, 2. February, 3. March!',
            'hint_audio' => 'March is the 3rd month.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'eng_days_months',
            'subject_id' => 'english',
            'question_text' => 'Which month is the 1st month of the year?',
            'question_audio' => 'Which month is the first month of the year?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => null,
            'passage' => null,
            'options_json' => json_encode(['January', 'February', 'December', 'April']),
            'correct_answer' => 'January',
            'hint_text' => 'We celebrate New Year in January.',
            'hint_audio' => 'January is the first month.',
            'meta_data_json' => null
        ],

        // Topic 11: Beginning Blending Sounds (ch- & th-)
        [
            'topic_id' => 'eng_blending',
            'subject_id' => 'english',
            'question_text' => 'Which word begins with the "ch-" blend sound?',
            'question_audio' => 'Which word begins with the ch blend sound?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => null,
            'passage' => null,
            'options_json' => json_encode(['Chair', 'Thorn', 'Thick', 'Thin']),
            'correct_answer' => 'Chair',
            'hint_text' => 'We sit on a "ch-air"!',
            'hint_audio' => 'Chair starts with ch.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'eng_blending',
            'subject_id' => 'english',
            'question_text' => 'Which word begins with the "th-" blend sound?',
            'question_audio' => 'Which word begins with the th blend sound?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => null,
            'passage' => null,
            'options_json' => json_encode(['Thorn', 'Chick', 'Chin', 'Cheese']),
            'correct_answer' => 'Thorn',
            'hint_text' => 'A sharp prickle on a rose stem is a "th-orn".',
            'hint_audio' => 'Thorn begins with th.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'eng_blending',
            'subject_id' => 'english',
            'question_text' => 'Complete the word for baby chicken: __ick',
            'question_audio' => 'Complete the word for baby chicken: blank ick',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/binatang/ayam.jpg',
            'passage' => null,
            'options_json' => json_encode(['ch- (chick)', 'th- (thick)', 'sh- (shick)', 'wh- (whick)']),
            'correct_answer' => 'ch- (chick)',
            'hint_text' => 'A baby hen is called a chick (c-h-i-c-k).',
            'hint_audio' => 'C H makes chick.',
            'meta_data_json' => null
        ],

        // Topic 12: Pronouns (He, She, It, They)
        [
            'topic_id' => 'eng_pronouns',
            'subject_id' => 'english',
            'question_text' => 'Look at the TRUCK. Which pronoun replaces it?',
            'question_audio' => 'Look at the truck. Which pronoun replaces it?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/kenderaan/lori.jpg',
            'passage' => null,
            'options_json' => json_encode(['it', 'she', 'they']),
            'correct_answer' => 'it',
            'hint_text' => 'A vehicle or single object uses "it".',
            'hint_audio' => 'Use it for a truck.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'eng_pronouns',
            'subject_id' => 'english',
            'question_text' => 'Look at the BANANA. Which pronoun replaces it?',
            'question_audio' => 'Look at the banana. Which pronoun replaces it?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/english/banana.svg',
            'passage' => null,
            'options_json' => json_encode(['it', 'she', 'they']),
            'correct_answer' => 'it',
            'hint_text' => 'A single fruit uses "it".',
            'hint_audio' => 'Use it for a banana.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'eng_pronouns',
            'subject_id' => 'english',
            'question_text' => 'Look at the MAN / Male teacher. Which pronoun replaces him?',
            'question_audio' => 'Look at the man. Which pronoun replaces him?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/english/male_teacher.svg',
            'passage' => null,
            'options_json' => json_encode(['he', 'it', 'they']),
            'correct_answer' => 'he',
            'hint_text' => 'We use "he" for a boy or a man.',
            'hint_audio' => 'Use he for a man.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'eng_pronouns',
            'subject_id' => 'english',
            'question_text' => 'Look at a BUNCH OF TOYS. Which pronoun replaces them?',
            'question_audio' => 'Look at a bunch of toys. Which pronoun replaces them?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/english/toys_bunch.svg',
            'passage' => null,
            'options_json' => json_encode(['they', 'he', 'it']),
            'correct_answer' => 'they',
            'hint_text' => 'When there is more than one toy (plural), use "they".',
            'hint_audio' => 'Use they for many toys.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'eng_pronouns',
            'subject_id' => 'english',
            'question_text' => 'Look at the LITTLE GIRL. Which pronoun replaces her?',
            'question_audio' => 'Look at the little girl. Which pronoun replaces her?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/english/little_girl.svg',
            'passage' => null,
            'options_json' => json_encode(['she', 'he', 'they']),
            'correct_answer' => 'she',
            'hint_text' => 'We use "she" for a girl or a woman.',
            'hint_audio' => 'Use she for a girl.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'eng_pronouns',
            'subject_id' => 'english',
            'question_text' => 'Look at a BOY AND A GIRL playing together. Which pronoun replaces them?',
            'question_audio' => 'Look at a boy and a girl playing together. Which pronoun replaces them?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/english/children_group.svg',
            'passage' => null,
            'options_json' => json_encode(['they', 'she', 'he']),
            'correct_answer' => 'they',
            'hint_text' => 'A group of two or more children is called "they".',
            'hint_audio' => 'Use they for two children.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'eng_pronouns',
            'subject_id' => 'english',
            'question_text' => 'Look at the COLOUR PENCILS. Which pronoun replaces them?',
            'question_audio' => 'Look at the colour pencils. Which pronoun replaces them?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/english/color_pencils.svg',
            'passage' => null,
            'options_json' => json_encode(['they', 'she', 'it']),
            'correct_answer' => 'they',
            'hint_text' => 'There are many pencils in the box (plural): use "they".',
            'hint_audio' => 'Use they for pencils.',
            'meta_data_json' => null
        ],

        // Topic 13: Articles 'a' and 'an'
        [
            'topic_id' => 'eng_articles',
            'subject_id' => 'english',
            'question_text' => 'It is _____ cat.',
            'question_audio' => 'It is blank cat.',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/english/cat.svg',
            'passage' => null,
            'options_json' => json_encode(['a', 'an']),
            'correct_answer' => 'a',
            'hint_text' => '"Cat" starts with the consonant "c", so we say "a cat".',
            'hint_audio' => 'We say a cat.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'eng_articles',
            'subject_id' => 'english',
            'question_text' => 'It is _____ egg.',
            'question_audio' => 'It is blank egg.',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/english/egg.svg',
            'passage' => null,
            'options_json' => json_encode(['an', 'a']),
            'correct_answer' => 'an',
            'hint_text' => '"Egg" starts with the vowel sound "e", so we use "an".',
            'hint_audio' => 'We say an egg.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'eng_articles',
            'subject_id' => 'english',
            'question_text' => 'This is _____ house.',
            'question_audio' => 'This is blank house.',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/english/house.svg',
            'passage' => null,
            'options_json' => json_encode(['a', 'an']),
            'correct_answer' => 'a',
            'hint_text' => '"House" starts with "h" (consonant): "a house".',
            'hint_audio' => 'We say a house.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'eng_articles',
            'subject_id' => 'english',
            'question_text' => 'It is _____ apple.',
            'question_audio' => 'It is blank apple.',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/english/apple.svg',
            'passage' => null,
            'options_json' => json_encode(['an', 'a']),
            'correct_answer' => 'an',
            'hint_text' => '"Apple" begins with vowel "a": "an apple".',
            'hint_audio' => 'We say an apple.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'eng_articles',
            'subject_id' => 'english',
            'question_text' => 'This is _____ octopus.',
            'question_audio' => 'This is blank octopus.',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/english/octopus.svg',
            'passage' => null,
            'options_json' => json_encode(['an', 'a']),
            'correct_answer' => 'an',
            'hint_text' => '"Octopus" begins with vowel "o": "an octopus".',
            'hint_audio' => 'We say an octopus.',
            'meta_data_json' => null
        ],

        // Topic 14: Using Has and Have
        [
            'topic_id' => 'eng_has_have',
            'subject_id' => 'english',
            'question_text' => 'It _____ a long tail. (Monkey with long tail)',
            'question_audio' => 'It blank a long tail.',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/binatang/monyet.jpg',
            'passage' => null,
            'options_json' => json_encode(['has', 'have']),
            'correct_answer' => 'has',
            'hint_text' => '"It" is singular, so it pairs with "has".',
            'hint_audio' => 'It has a long tail.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'eng_has_have',
            'subject_id' => 'english',
            'question_text' => 'He _____ a bike.',
            'question_audio' => 'He blank a bike.',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/kenderaan/basikal.jpg',
            'passage' => null,
            'options_json' => json_encode(['has', 'have']),
            'correct_answer' => 'has',
            'hint_text' => '"He" pairs with "has".',
            'hint_audio' => 'He has a bike.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'eng_has_have',
            'subject_id' => 'english',
            'question_text' => 'They _____ a new house.',
            'question_audio' => 'They blank a new house.',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/english/house.svg',
            'passage' => null,
            'options_json' => json_encode(['have', 'has']),
            'correct_answer' => 'have',
            'hint_text' => '"They" is plural, so we say "They have".',
            'hint_audio' => 'They have a new house.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'eng_has_have',
            'subject_id' => 'english',
            'question_text' => 'Amin and Aman _____ a birthday party.',
            'question_audio' => 'Amin and Aman blank a birthday party.',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => null,
            'passage' => null,
            'options_json' => json_encode(['have', 'has']),
            'correct_answer' => 'have',
            'hint_text' => 'Amin and Aman are two people (plural), so use "have".',
            'hint_audio' => 'Two people have a party.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'eng_has_have',
            'subject_id' => 'english',
            'question_text' => 'I _____ a kite.',
            'question_audio' => 'I blank a kite.',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => null,
            'passage' => null,
            'options_json' => json_encode(['have', 'has']),
            'correct_answer' => 'have',
            'hint_text' => 'The pronoun "I" always takes "have".',
            'hint_audio' => 'I have a kite.',
            'meta_data_json' => null
        ],

        // Topic 15: This, That, These, Those
        [
            'topic_id' => 'eng_demonstratives',
            'subject_id' => 'english',
            'question_text' => '_____ is a pen. (Arrow pointing close to 1 pen)',
            'question_audio' => 'Blank is a pen.',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/english/color_pencils.svg',
            'passage' => null,
            'options_json' => json_encode(['This', 'That', 'These', 'Those']),
            'correct_answer' => 'This',
            'hint_text' => 'One single object close to you: "This is a pen".',
            'hint_audio' => 'Use This for one near object.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'eng_demonstratives',
            'subject_id' => 'english',
            'question_text' => '_____ are chairs. (Arrow pointing far away to 2 chairs)',
            'question_audio' => 'Blank are chairs.',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => null,
            'passage' => null,
            'options_json' => json_encode(['Those', 'These', 'This', 'That']),
            'correct_answer' => 'Those',
            'hint_text' => 'Plural (chairs) and far away: use "Those".',
            'hint_audio' => 'Use Those for plural distant objects.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'eng_demonstratives',
            'subject_id' => 'english',
            'question_text' => '_____ are balls. (Arrow pointing close to 6 balls)',
            'question_audio' => 'Blank are balls.',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/english/sports_balls.svg',
            'passage' => null,
            'options_json' => json_encode(['These', 'Those', 'This', 'That']),
            'correct_answer' => 'These',
            'hint_text' => 'Plural (balls) and near to you: use "These".',
            'hint_audio' => 'Use These for many near objects.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'eng_demonstratives',
            'subject_id' => 'english',
            'question_text' => '_____ is a camera. (Arrow pointing far to 1 camera)',
            'question_audio' => 'Blank is a camera.',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/ict/webcam.svg',
            'passage' => null,
            'options_json' => json_encode(['That', 'This', 'Those', 'These']),
            'correct_answer' => 'That',
            'hint_text' => 'One single camera far away: "That is a camera".',
            'hint_audio' => 'Use That for one distant object.',
            'meta_data_json' => null
        ],

        // Topic 16: Reading Comprehension (Troy & Andy)
        [
            'topic_id' => 'eng_comprehension',
            'subject_id' => 'english',
            'question_text' => 'Troy has a ______',
            'question_audio' => 'Troy has a what?',
            'lang' => 'en',
            'question_type' => 'comprehension',
            'image_url' => 'images/english/lollipop.svg',
            'passage' => 'Troy has a lollipop. It is a heart shaped lollipop. The lollipop is sweet. It tastes like cotton candy.',
            'options_json' => json_encode(['lollipop', 'gum', 'peach']),
            'correct_answer' => 'lollipop',
            'hint_text' => 'Look at the first sentence: "Troy has a lollipop."',
            'hint_audio' => 'Troy has a lollipop.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'eng_comprehension',
            'subject_id' => 'english',
            'question_text' => 'What is the shape of the lollipop?',
            'question_audio' => 'What is the shape of the lollipop?',
            'lang' => 'en',
            'question_type' => 'comprehension',
            'image_url' => 'images/english/lollipop.svg',
            'passage' => 'Troy has a lollipop. It is a heart shaped lollipop. The lollipop is sweet. It tastes like cotton candy.',
            'options_json' => json_encode(['heart', 'triangle', 'circle']),
            'correct_answer' => 'heart',
            'hint_text' => 'The story says: "It is a heart shaped lollipop."',
            'hint_audio' => 'It is heart shaped.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'eng_comprehension',
            'subject_id' => 'english',
            'question_text' => 'It tastes like _____',
            'question_audio' => 'It tastes like what?',
            'lang' => 'en',
            'question_type' => 'comprehension',
            'image_url' => 'images/english/lollipop.svg',
            'passage' => 'Troy has a lollipop. It is a heart shaped lollipop. The lollipop is sweet. It tastes like cotton candy.',
            'options_json' => json_encode(['cotton candy', 'apple', 'bubble gum']),
            'correct_answer' => 'cotton candy',
            'hint_text' => 'The last sentence tells us: "It tastes like cotton candy."',
            'hint_audio' => 'It tastes like cotton candy.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'eng_comprehension',
            'subject_id' => 'english',
            'question_text' => 'Who went to the zoo?',
            'question_audio' => 'Who went to the zoo?',
            'lang' => 'en',
            'question_type' => 'comprehension',
            'image_url' => 'images/binatang/gajah.jpg',
            'passage' => 'Andy went to the zoo. He saw a big elephant eating peanuts. Andy saw the lion at the zoo. It was big and scary! The giraffe was very tall! It likes to eat leaves. The monkey was silly! It likes to play in the tree. Andy had fun at the zoo!',
            'options_json' => json_encode(['Andy', 'Sam', 'Leo']),
            'correct_answer' => 'Andy',
            'hint_text' => 'The boy\'s name in the opening sentence is Andy.',
            'hint_audio' => 'Andy went to the zoo.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'eng_comprehension',
            'subject_id' => 'english',
            'question_text' => 'What was big and scary?',
            'question_audio' => 'What was big and scary?',
            'lang' => 'en',
            'question_type' => 'comprehension',
            'image_url' => 'images/binatang/singa.jpg',
            'passage' => 'Andy went to the zoo. He saw a big elephant eating peanuts. Andy saw the lion at the zoo. It was big and scary! The giraffe was very tall! It likes to eat leaves. The monkey was silly! It likes to play in the tree. Andy had fun at the zoo!',
            'options_json' => json_encode(['The lion', 'The monkey', 'The peanut']),
            'correct_answer' => 'The lion',
            'hint_text' => 'Read: "Andy saw the lion at the zoo. It was big and scary!"',
            'hint_audio' => 'The lion was big and scary.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'eng_comprehension',
            'subject_id' => 'english',
            'question_text' => 'Where did the monkey like to play?',
            'question_audio' => 'Where did the monkey like to play?',
            'lang' => 'en',
            'question_type' => 'comprehension',
            'image_url' => 'images/binatang/monyet.jpg',
            'passage' => 'Andy went to the zoo. He saw a big elephant eating peanuts. Andy saw the lion at the zoo. It was big and scary! The giraffe was very tall! It likes to eat leaves. The monkey was silly! It likes to play in the tree. Andy had fun at the zoo!',
            'options_json' => json_encode(['In the tree', 'In the water', 'In the car']),
            'correct_answer' => 'In the tree',
            'hint_text' => 'The monkey loves to swing and play in the green tree.',
            'hint_audio' => 'The monkey plays in the tree.',
            'meta_data_json' => null
        ],

        // === SCIENCE ===
        // Topic 17: Animals on Land vs Sea
        [
            'topic_id' => 'sci_land_sea',
            'subject_id' => 'science',
            'question_text' => 'Where does a dolphin live?',
            'question_audio' => 'Where does a dolphin live?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/binatang/lumba_lumba.jpg',
            'passage' => null,
            'options_json' => json_encode(['Sea', 'Land']),
            'correct_answer' => 'Sea',
            'hint_text' => 'Dolphins swim, leap, and breathe in the ocean waters.',
            'hint_audio' => 'Dolphins live in the sea.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'sci_land_sea',
            'subject_id' => 'science',
            'question_text' => 'Where does a cow live?',
            'question_audio' => 'Where does a cow live?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/binatang/lembu.jpg',
            'passage' => null,
            'options_json' => json_encode(['Land', 'Sea']),
            'correct_answer' => 'Land',
            'hint_text' => 'Cows graze on green grass in pastures on land.',
            'hint_audio' => 'Cows live on land.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'sci_land_sea',
            'subject_id' => 'science',
            'question_text' => 'Where does a shark live?',
            'question_audio' => 'Where does a shark live?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/binatang/jerung.jpg',
            'passage' => null,
            'options_json' => json_encode(['Sea', 'Land']),
            'correct_answer' => 'Sea',
            'hint_text' => 'Sharks are apex marine predators living in deep oceans.',
            'hint_audio' => 'Sharks live in the sea.',
            'meta_data_json' => null
        ],

        // Topic 18: Sink or Float
        [
            'topic_id' => 'sci_sink_float',
            'subject_id' => 'science',
            'question_text' => 'A heavy metal key dropped in water will:',
            'question_audio' => 'A heavy metal key dropped in water will sink or float?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/science/sink_key.svg',
            'passage' => null,
            'options_json' => json_encode(['Sink', 'Float']),
            'correct_answer' => 'Sink',
            'hint_text' => 'Metal is heavy and dense, so it sinks to the bottom.',
            'hint_audio' => 'A metal key will sink.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'sci_sink_float',
            'subject_id' => 'science',
            'question_text' => 'A light plastic toy ball placed on water will:',
            'question_audio' => 'A light plastic ball placed on water will sink or float?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/science/float_ball.svg',
            'passage' => null,
            'options_json' => json_encode(['Float', 'Sink']),
            'correct_answer' => 'Float',
            'hint_text' => 'Plastic is lightweight and holds air, floating on the surface.',
            'hint_audio' => 'A plastic ball will float.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'sci_sink_float',
            'subject_id' => 'science',
            'question_text' => 'A dry wooden twig placed in a pond will:',
            'question_audio' => 'A dry wooden twig placed in a pond will sink or float?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/science/float_wood.svg',
            'passage' => null,
            'options_json' => json_encode(['Float', 'Sink']),
            'correct_answer' => 'Float',
            'hint_text' => 'Wood is lighter than water, so it floats gently.',
            'hint_audio' => 'A wooden twig will float.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'sci_sink_float',
            'subject_id' => 'science',
            'question_text' => 'A stone dropped into a water bucket will:',
            'question_audio' => 'A stone dropped into a water bucket will sink or float?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/science/sink_stone.svg',
            'passage' => null,
            'options_json' => json_encode(['Sink', 'Float']),
            'correct_answer' => 'Sink',
            'hint_text' => 'Stones are solid and heavy, falling down to the bottom.',
            'hint_audio' => 'A stone will sink.',
            'meta_data_json' => null
        ],

        // Topic 19: Sun, Moon, Star, Earth
        [
            'topic_id' => 'sci_celestial',
            'subject_id' => 'science',
            'question_text' => 'Look at the picture. Select the correct celestial object:',
            'question_audio' => 'Look at the picture. Select the correct celestial object.',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/science/sun.svg',
            'passage' => null,
            'options_json' => json_encode(['Sun', 'Moon', 'Earth', 'Star']),
            'correct_answer' => 'Sun',
            'hint_text' => 'It gives us bright daylight, warmth, and sunshine.',
            'hint_audio' => 'This is the Sun.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'sci_celestial',
            'subject_id' => 'science',
            'question_text' => 'Look at the picture. Which object is our home planet?',
            'question_audio' => 'Look at the picture. Which object is our home planet?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/science/earth.svg',
            'passage' => null,
            'options_json' => json_encode(['Earth', 'Sun', 'Moon', 'Star']),
            'correct_answer' => 'Earth',
            'hint_text' => 'Our blue and green planet where humans, animals and plants live.',
            'hint_audio' => 'This is planet Earth.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'sci_celestial',
            'subject_id' => 'science',
            'question_text' => 'Look at this night object that changes its shape:',
            'question_audio' => 'Look at this night object that changes its shape.',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/science/moon.svg',
            'passage' => null,
            'options_json' => json_encode(['Moon', 'Sun', 'Earth', 'Star']),
            'correct_answer' => 'Moon',
            'hint_text' => 'It shines with gentle silvery light at night.',
            'hint_audio' => 'This is the Moon.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'sci_celestial',
            'subject_id' => 'science',
            'question_text' => 'Look at this twinkling celestial light in the dark sky:',
            'question_audio' => 'Look at this twinkling celestial light in the dark sky.',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/science/star.svg',
            'passage' => null,
            'options_json' => json_encode(['Star', 'Moon', 'Earth', 'Sun']),
            'correct_answer' => 'Star',
            'hint_text' => 'Twinkle, twinkle little star!',
            'hint_audio' => 'This is a Star.',
            'meta_data_json' => null
        ],

        // Topic 20: Materials (Metal, Glass, Paper)
        [
            'topic_id' => 'sci_materials',
            'subject_id' => 'science',
            'question_text' => 'A drinking cup that is transparent and clear is made of:',
            'question_audio' => 'A drinking cup that is transparent and clear is made of what?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/science/glass_cup.svg',
            'passage' => null,
            'options_json' => json_encode(['Glass', 'Metal', 'Paper']),
            'correct_answer' => 'Glass',
            'hint_text' => 'You can see right through it, and it can shatter if dropped.',
            'hint_audio' => 'It is made of glass.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'sci_materials',
            'subject_id' => 'science',
            'question_text' => 'A storybook or notebook is made of:',
            'question_audio' => 'A storybook or notebook is made of what?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/science/paper_book.svg',
            'passage' => null,
            'options_json' => json_encode(['Paper', 'Glass', 'Metal']),
            'correct_answer' => 'Paper',
            'hint_text' => 'We can write on it and turn its lightweight pages.',
            'hint_audio' => 'It is made of paper.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'sci_materials',
            'subject_id' => 'science',
            'question_text' => 'An iron soup spoon or car key is made of:',
            'question_audio' => 'An iron soup spoon or car key is made of what?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/science/metal_spoon.svg',
            'passage' => null,
            'options_json' => json_encode(['Metal', 'Glass', 'Paper']),
            'correct_answer' => 'Metal',
            'hint_text' => 'It is hard, shiny, strong, and clinks when tapped.',
            'hint_audio' => 'It is made of metal.',
            'meta_data_json' => null
        ],

        // Topic 21: Types of Pollution
        [
            'topic_id' => 'sci_pollution',
            'subject_id' => 'science',
            'question_text' => 'Thick dark smoke from factory chimneys causes:',
            'question_audio' => 'Thick dark smoke from factory chimneys causes which pollution?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/science/air_pollution.svg',
            'passage' => null,
            'options_json' => json_encode(['Air pollution', 'Sea pollution', 'Land pollution']),
            'correct_answer' => 'Air pollution',
            'hint_text' => 'Smoke enters the atmosphere and harms the air we breathe.',
            'hint_audio' => 'Smoke causes air pollution.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'sci_pollution',
            'subject_id' => 'science',
            'question_text' => 'Plastic bottles and waste thrown into the ocean cause:',
            'question_audio' => 'Plastic bottles and waste thrown into the ocean cause what?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/science/water_pollution.svg',
            'passage' => null,
            'options_json' => json_encode(['Sea pollution', 'Air pollution', 'Land pollution']),
            'correct_answer' => 'Sea pollution',
            'hint_text' => 'Waste in rivers and oceans harms fish and coral reefs.',
            'hint_audio' => 'Plastic waste causes sea pollution.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'sci_pollution',
            'subject_id' => 'science',
            'question_text' => 'Piles of smelly trash dumped across empty open fields cause:',
            'question_audio' => 'Piles of smelly trash dumped across empty open fields cause what?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/science/land_pollution.svg',
            'passage' => null,
            'options_json' => json_encode(['Land pollution', 'Air pollution', 'Sea pollution']),
            'correct_answer' => 'Land pollution',
            'hint_text' => 'Litter on the ground damages the soil and landscape.',
            'hint_audio' => 'Trash dumps cause land pollution.',
            'meta_data_json' => null
        ],

        // Topic 22: Parts of a Plant & Needs
        [
            'topic_id' => 'sci_plants',
            'subject_id' => 'science',
            'question_text' => 'Which part of the plant grows UNDER the soil to absorb water?',
            'question_audio' => 'Which part of the plant grows under the soil to absorb water?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/science/plant_parts.svg',
            'passage' => null,
            'options_json' => json_encode(['Roots', 'Flower', 'Leaf', 'Stem']),
            'correct_answer' => 'Roots',
            'hint_text' => 'Roots spread underground like little drinking straws.',
            'hint_audio' => 'Roots absorb water.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'sci_plants',
            'subject_id' => 'science',
            'question_text' => 'Which flat green part catches sunlight to make food for the plant?',
            'question_audio' => 'Which flat green part catches sunlight to make food for the plant?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/science/plant_parts.svg',
            'passage' => null,
            'options_json' => json_encode(['Leaf', 'Roots', 'Fruit', 'Stem']),
            'correct_answer' => 'Leaf',
            'hint_text' => 'Green leaves absorb sunlight energy.',
            'hint_audio' => 'Leaves make food from sunlight.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'sci_plants',
            'subject_id' => 'science',
            'question_text' => 'What 3 vital things does a green plant need to grow healthy?',
            'question_audio' => 'What three vital things does a green plant need to grow healthy?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/science/plant_parts.svg',
            'passage' => null,
            'options_json' => json_encode(['Sunlight, Air, Water', 'Ice, Fire, Sugar', 'Darkness, Sand, Oil']),
            'correct_answer' => 'Sunlight, Air, Water',
            'hint_text' => 'Plants love warm sun, fresh air, and regular watering!',
            'hint_audio' => 'Plants need sunlight, air, and water.',
            'meta_data_json' => null
        ],

        // === ICT (INFORMATION & COMMUNICATION TECHNOLOGY) ===
        // Topic 23: Drives & Storage
        [
            'topic_id' => 'ict_storage',
            'subject_id' => 'ict',
            'question_text' => 'Which storage device is small, portable, and plugs into a USB port?',
            'question_audio' => 'Which storage device is small, portable, and plugs into a USB port?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/ict/pendrive.svg',
            'passage' => null,
            'options_json' => json_encode(['Pendrive', 'Floppy Disk', 'CD-ROM', 'Hard Disk']),
            'correct_answer' => 'Pendrive',
            'hint_text' => 'A thumb-sized USB flash stick is called a pendrive.',
            'hint_audio' => 'It is a pendrive.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'ict_storage',
            'subject_id' => 'ict',
            'question_text' => 'Which circular shiny disc stores games and songs using a laser?',
            'question_audio' => 'Which circular shiny disc stores games and songs using a laser?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/ict/cd_rom.svg',
            'passage' => null,
            'options_json' => json_encode(['CD-ROM', 'Floppy Disk', 'Memory Card', 'Hard Disk']),
            'correct_answer' => 'CD-ROM',
            'hint_text' => 'It is shaped like a shiny rainbow round disc: CD-ROM.',
            'hint_audio' => 'It is a CD ROM.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'ict_storage',
            'subject_id' => 'ict',
            'question_text' => 'Which large storage device stays inside the computer case to store all programs?',
            'question_audio' => 'Which large storage device stays inside the computer case?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/ict/system_unit.svg',
            'passage' => null,
            'options_json' => json_encode(['Hard disk drive', 'Floppy Disk', 'Memory Card', 'CD-ROM']),
            'correct_answer' => 'Hard disk drive',
            'hint_text' => 'The hard disk drive (HDD/SSD) is the primary internal storage.',
            'hint_audio' => 'It is the hard disk drive.',
            'meta_data_json' => null
        ],

        // Topic 24: Computer Parts
        [
            'topic_id' => 'ict_parts',
            'subject_id' => 'ict',
            'question_text' => 'Which part displays pictures, videos, and games like a TV screen?',
            'question_audio' => 'Which part displays pictures, videos, and games like a TV screen?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/ict/monitor.svg',
            'passage' => null,
            'options_json' => json_encode(['Monitor', 'Keyboard', 'Mouse', 'Printer']),
            'correct_answer' => 'Monitor',
            'hint_text' => 'The monitor screen lets our eyes see everything.',
            'hint_audio' => 'It is the monitor.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'ict_parts',
            'subject_id' => 'ict',
            'question_text' => 'Which peripheral has keys with letters and numbers used for typing?',
            'question_audio' => 'Which peripheral has keys with letters and numbers used for typing?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/ict/keyboard.svg',
            'passage' => null,
            'options_json' => json_encode(['Keyboard', 'Monitor', 'Speaker', 'Webcam']),
            'correct_answer' => 'Keyboard',
            'hint_text' => 'You press keys on the keyboard to type words.',
            'hint_audio' => 'It is the keyboard.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'ict_parts',
            'subject_id' => 'ict',
            'question_text' => 'Which device prints our homework and color drawings onto real paper?',
            'question_audio' => 'Which device prints our homework and drawings onto paper?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/ict/printer.svg',
            'passage' => null,
            'options_json' => json_encode(['Printer', 'Scanner', 'Keyboard', 'Headphones']),
            'correct_answer' => 'Printer',
            'hint_text' => 'The printer sprays ink onto sheets of paper.',
            'hint_audio' => 'It is the printer.',
            'meta_data_json' => null
        ],

        // Topic 25: Count Computer Peripherals
        [
            'topic_id' => 'ict_counting',
            'subject_id' => 'ict',
            'question_text' => 'Count the computer mice: [🐭 🐭 🐭 🐭]. How many mice are there?',
            'question_audio' => 'Count the computer mice. How many mice are there?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/ict/mouse.svg',
            'passage' => null,
            'options_json' => json_encode(['4', '3', '5', '2']),
            'correct_answer' => '4',
            'hint_text' => 'Count one by one: 1, 2, 3, 4 computer mice!',
            'hint_audio' => 'There are 4 mice.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'ict_counting',
            'subject_id' => 'ict',
            'question_text' => 'Count the headphones: [🎧 🎧 🎧]. How many headphones are there?',
            'question_audio' => 'Count the headphones. How many headphones are there?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/ict/headphones.svg',
            'passage' => null,
            'options_json' => json_encode(['3', '4', '2', '5']),
            'correct_answer' => '3',
            'hint_text' => 'There are 3 sets of audio headphones.',
            'hint_audio' => 'There are 3 headphones.',
            'meta_data_json' => null
        ],

        // Topic 26: Fill in Missing Letters
        [
            'topic_id' => 'ict_spelling',
            'subject_id' => 'ict',
            'question_text' => 'Fill in the missing vowels for: P _ N D R _ V E',
            'question_audio' => 'Fill in the missing vowels for pendrive.',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/ict/pendrive.svg',
            'passage' => null,
            'options_json' => json_encode(['E and I (PENDRIVE)', 'A and O (PANDROVE)', 'U and E (PUNDREVE)']),
            'correct_answer' => 'E and I (PENDRIVE)',
            'hint_text' => 'P-E-N-D-R-I-V-E spells PENDRIVE.',
            'hint_audio' => 'The letters are E and I.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'ict_spelling',
            'subject_id' => 'ict',
            'question_text' => 'Fill in the missing vowels for: S C _ N N _ R',
            'question_audio' => 'Fill in the missing vowels for scanner.',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/ict/scanner.svg',
            'passage' => null,
            'options_json' => json_encode(['A and E (SCANNER)', 'O and I (SCONNIR)', 'E and U (SCENNUR)']),
            'correct_answer' => 'A and E (SCANNER)',
            'hint_text' => 'S-C-A-N-N-E-R spells SCANNER.',
            'hint_audio' => 'The letters are A and E.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'ict_spelling',
            'subject_id' => 'ict',
            'question_text' => 'Fill in the missing vowels for: W _ B C _ M',
            'question_audio' => 'Fill in the missing vowels for webcam.',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/ict/webcam.svg',
            'passage' => null,
            'options_json' => json_encode(['E and A (WEBCAM)', 'A and I (WABCIM)', 'O and E (WOBCE M)']),
            'correct_answer' => 'E and A (WEBCAM)',
            'hint_text' => 'W-E-B-C-A-M spells WEBCAM.',
            'hint_audio' => 'The letters are E and A.',
            'meta_data_json' => null
        ],

        // Topic 27: Input (I) vs Output (O)
        [
            'topic_id' => 'ict_input_output',
            'subject_id' => 'ict',
            'question_text' => 'A KEYBOARD sends letters and commands INTO the computer. Is it Input (I) or Output (O)?',
            'question_audio' => 'A keyboard sends text into the computer. Is it input or output?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/ict/keyboard.svg',
            'passage' => null,
            'options_json' => json_encode(['Input (I)', 'Output (O)']),
            'correct_answer' => 'Input (I)',
            'hint_text' => 'You enter data INTO the computer: Input.',
            'hint_audio' => 'A keyboard is an Input device.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'ict_input_output',
            'subject_id' => 'ict',
            'question_text' => 'A MONITOR shows visual pictures and games OUT to your eyes. Is it Input (I) or Output (O)?',
            'question_audio' => 'A monitor displays visual pictures out to you. Is it input or output?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/ict/monitor.svg',
            'passage' => null,
            'options_json' => json_encode(['Output (O)', 'Input (I)']),
            'correct_answer' => 'Output (O)',
            'hint_text' => 'The screen outputs pictures OUT to the user: Output.',
            'hint_audio' => 'A monitor is an Output device.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'ict_input_output',
            'subject_id' => 'ict',
            'question_text' => 'Computer SPEAKERS play music and voices OUT to the room. Is it Input (I) or Output (O)?',
            'question_audio' => 'Computer speakers play sound out. Is it input or output?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/ict/speakers.svg',
            'passage' => null,
            'options_json' => json_encode(['Output (O)', 'Input (I)']),
            'correct_answer' => 'Output (O)',
            'hint_text' => 'Sound comes OUT from the computer: Output.',
            'hint_audio' => 'Speakers are an Output device.',
            'meta_data_json' => null
        ],
        [
            'topic_id' => 'ict_input_output',
            'subject_id' => 'ict',
            'question_text' => 'A MICROPHONE records your speaking voice INTO the computer. Is it Input (I) or Output (O)?',
            'question_audio' => 'A microphone records your voice into the computer. Is it input or output?',
            'lang' => 'en',
            'question_type' => 'multiple_choice',
            'image_url' => 'images/ict/microphone.svg',
            'passage' => null,
            'options_json' => json_encode(['Input (I)', 'Output (O)']),
            'correct_answer' => 'Input (I)',
            'hint_text' => 'Voice travels IN through the mic: Input.',
            'hint_audio' => 'A microphone is an Input device.',
            'meta_data_json' => null
        ]
    ];

    $stmtQ = $pdo->prepare("INSERT INTO questions (topic_id, subject_id, question_text, question_audio, lang, question_type, image_url, passage, options_json, correct_answer, hint_text, hint_audio, meta_data_json) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($questions as $q) {
        $stmtQ->execute([
            $q['topic_id'],
            $q['subject_id'],
            $q['question_text'],
            $q['question_audio'],
            $q['lang'],
            $q['question_type'],
            $q['image_url'],
            $q['passage'],
            $q['options_json'],
            $q['correct_answer'],
            $q['hint_text'],
            $q['hint_audio'],
            $q['meta_data_json']
        ]);
    }
    echo "✓ Seeded " . count($questions) . " Core Questions.\n";

    echo "\n🎉 SEEDING COMPLETED SUCCESSFULLY!\n";
    echo "Total Subjects: " . count($subjects) . "\n";
    echo "Total Topics: " . count($topics) . "\n";
    echo "Total Revisions: " . count($revisions) . "\n";
    echo "Total Questions: " . count($questions) . "\n";

} catch (Exception $e) {
    echo "❌ Seeding Failed: " . $e->getMessage() . "\n";
}
