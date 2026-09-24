<?php
// NextGrade API - 5-Minute Topic Revision Section
require_once __DIR__ . '/../db.php';

header('Content-Type: application/json; charset=utf-8');

$topicId = $_GET['topic_id'] ?? null;

if (!$topicId) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing topic_id parameter']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT r.*, t.name as topic_name, t.name_native, t.revision_time_limit,
               s.id as subject_id, s.name as subject_name, s.theme_gradient, s.accent_color
        FROM revisions r
        JOIN topics t ON t.id = r.topic_id
        JOIN subjects s ON s.id = t.subject_id
        WHERE r.topic_id = ?
        LIMIT 1
    ");
    $stmt->execute([$topicId]);
    $revision = $stmt->fetch();

    if (!$revision) {
        // Fallback default revision if none explicitly seeded
        $stmtTopic = $pdo->prepare("SELECT * FROM topics WHERE id = ?");
        $stmtTopic->execute([$topicId]);
        $topic = $stmtTopic->fetch();

        if (!$topic) {
            http_response_code(404);
            echo json_encode(['error' => 'Topic not found']);
            exit;
        }

        $revision = [
            'topic_id' => $topic['id'],
            'title' => 'Ulang Kaji: ' . $topic['name'],
            'summary' => $topic['description'],
            'revision_time_limit' => 300,
            'content_json' => json_encode([
                ['title' => 'Konsep Utama', 'text' => $topic['description'], 'icon' => '💡']
            ]),
            'topic_name' => $topic['name'],
            'subject_id' => $topic['subject_id']
        ];
    }

    $revision['cards'] = json_decode($revision['content_json'], true) ?? [];
    echo json_encode(['success' => true, 'revision' => $revision]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
