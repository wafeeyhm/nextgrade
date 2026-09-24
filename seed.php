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

        // 4. QUESTIONS BANK (Loaded from data/questions_bank.json - 50-90 questions per topic, 1499 questions total)
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

    echo "✓ Seeded " . count($questions) . " Core Questions across all 27 Topics (50-60 per topic).
";


    echo "\n🎉 SEEDING COMPLETED SUCCESSFULLY!\n";
    echo "Total Subjects: " . count($subjects) . "\n";
    echo "Total Topics: " . count($topics) . "\n";
    echo "Total Revisions: " . count($revisions) . "\n";
    echo "Total Questions: " . count($questions) . "\n";

} catch (Exception $e) {
    echo "❌ Seeding Failed: " . $e->getMessage() . "\n";
}
