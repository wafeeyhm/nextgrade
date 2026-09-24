<?php
require_once 'db.php';

// 1. Force truncate the table to start completely fresh
$pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
$pdo->exec("TRUNCATE TABLE MalayQuestion");
$pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

echo "<h3>Database truncated successfully.</h3>";

// 2. Define your exact subfolders
$folders = [
    'kenderaan' => 'images/kenderaan',
    'haiwan'    => 'images/binatang',
    'suku_kata' => 'images/suku-kata'
];

$inserted = 0;

// 3. Scan folders and insert automatically
foreach ($folders as $category => $dir) {
    if (!is_dir($dir)) {
        echo "<p style='color:red;'>Folder missing: $dir</p>";
        continue;
    }

    $files = scandir($dir);
    foreach ($files as $file) {
        if (in_array($file, ['.', '..'])) continue;
        
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) continue;

        // Clean up filenames (e.g., "kapal_terbang" -> "Kapal Terbang")
        $nameWithoutExt = pathinfo($file, PATHINFO_FILENAME);
        $cleanName = ucwords(str_replace(['_', '-'], ' ', $nameWithoutExt));
        $imagePath = $dir . '/' . $file;

        if ($category === 'suku_kata') {
            // Suku kata logic (e.g., "baju" -> prompt "ba", answer "ju")
            $prompt = substr($nameWithoutExt, 0, 2);
            $answer = substr($nameWithoutExt, 2);
            $speech = "$prompt tambah $answer sama dengan $nameWithoutExt";
            $wrong  = 'la,ku,ri'; // Default placeholder distractors
            $type   = 'SYLLABLE';
        } else {
            // Standard categories (Vehicles / Animals)
            $prompt = ($category === 'kenderaan') ? 'Apakah kenderaan ini?' : 'Apakah nama haiwan ini?';
            $answer = $cleanName;
            $speech = "$prompt $cleanName";
            $wrong  = 'Option A,Option B,Option C'; // Default placeholder distractors
            $type   = 'STANDARD';
        }

        $stmt = $pdo->prepare("INSERT INTO MalayQuestion (prompt, speechPrompt, correctAnswer, wrongOptions, imageUrl, questionType, category) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$prompt, $speech, $answer, $wrong, $imagePath, $type, $category]);
        $inserted++;
    }
}

echo "<h3 style='color:green;'>Success: $inserted images scanned and added to the database!</h3>";
?>