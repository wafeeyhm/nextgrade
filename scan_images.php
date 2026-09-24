<?php
require_once 'db.php';

$folders = [
    'kenderaan' => 'images/kenderaan',
    'haiwan'    => 'images/binatang',
    'suku_kata' => 'images/suku-kata'
];

$inserted = 0;

foreach ($folders as $category => $dir) {
    if (!is_dir($dir)) continue;

    $files = scandir($dir);
    foreach ($files as $file) {
        if (in_array($file, ['.', '..'])) continue;
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) continue;

        $nameWithoutExt = pathinfo($file, PATHINFO_FILENAME);
        // Replace underscores/hyphens with spaces and capitalize
        $cleanName = ucwords(str_replace(['_', '-'], ' ', $nameWithoutExt));
        $imagePath = $dir . '/' . $file;

        if ($category === 'suku_kata') {
            // e.g. "baju" -> prompt "ba", answer "ju"
            $prompt = substr($nameWithoutExt, 0, 2);
            $answer = substr($nameWithoutExt, 2);
            $speech = "$prompt tambah $answer sama dengan $nameWithoutExt";
            $wrong  = 'la,ku,ri'; // placeholder distractors
            $type   = 'SYLLABLE';
        } else {
            $prompt = ($category === 'kenderaan') ? 'Apakah kenderaan ini?' : 'Apakah nama haiwan ini?';
            $answer = $cleanName;
            $speech = "$prompt $cleanName";
            $wrong  = 'Pilihan A,Pilihan B,Pilihan C'; // placeholder distractors
            $type   = 'STANDARD';
        }

        $stmt = $pdo->prepare("INSERT INTO MalayQuestion (prompt, speechPrompt, correctAnswer, wrongOptions, imageUrl, questionType, category) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$prompt, $speech, $answer, $wrong, $imagePath, $type, $category]);
        $inserted++;
    }
}

echo "Berjaya memasukkan $inserted soalan daripada fail gambar!";