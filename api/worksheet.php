<?php
// NextGrade API - Worksheet Generator for Handwriting and Writing Skills
require_once __DIR__ . '/../db.php';

header('Content-Type: application/json; charset=utf-8');

$topicId = $_GET['topic_id'] ?? null;

if (!$topicId) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing topic_id parameter']);
    exit;
}

try {
    // Fetch topic & subject details
    $stmtTopic = $pdo->prepare("
        SELECT t.*, s.name as subject_name, s.icon as subject_icon, s.accent_color
        FROM topics t
        JOIN subjects s ON s.id = t.subject_id
        WHERE t.id = ?
    ");
    $stmtTopic->execute([$topicId]);
    $topic = $stmtTopic->fetch();

    if (!$topic) {
        http_response_code(404);
        echo json_encode(['error' => 'Topic not found']);
        exit;
    }

    // Fetch up to 10 questions for this topic
    $stmtQ = $pdo->prepare("SELECT * FROM questions WHERE topic_id = ? ORDER BY id ASC LIMIT 10");
    $stmtQ->execute([$topicId]);
    $questions = $stmtQ->fetchAll();

    $cleanQuestions = [];
    foreach ($questions as $q) {
        $imgUrl = null;
        if (!empty($q['image_url'])) {
            $cleanPath = ltrim($q['image_url'], '/');
            if (str_starts_with($cleanPath, 'nextgrade/')) {
                $cleanPath = substr($cleanPath, strlen('nextgrade/'));
            }
            $imgUrl = BASE_URL . $cleanPath;
        }

        $cleanQuestions[] = [
            'id' => $q['id'],
            'question_text' => $q['question_text'],
            'question_type' => $q['question_type'],
            'image_url' => $imgUrl,
            'passage' => $q['passage'],
            'options' => json_decode($q['options_json'], true) ?? [],
            'correct_answer' => $q['correct_answer'],
            'hint_text' => $q['hint_text']
        ];
    }

    echo json_encode([
        'success' => true,
        'topic' => $topic,
        'questions' => $cleanQuestions
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
