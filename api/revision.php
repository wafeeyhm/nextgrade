<?php
// NextGrade API - 5-Minute Topic Revision Section
require_once __DIR__ . '/../db.php';

if (!headers_sent()) {
    header('Content-Type: application/json; charset=utf-8');
}

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

    $cards = json_decode($revision['content_json'], true) ?? [];
    foreach ($cards as &$card) {
        if (!empty($card['image_url'])) {
            $cleanPath = ltrim($card['image_url'], '/');
            if (str_starts_with($cleanPath, 'nextgrade/')) {
                $cleanPath = substr($cleanPath, strlen('nextgrade/'));
            }
            $card['image_url'] = BASE_URL . $cleanPath;
        }
    }
    unset($card);

    $revision['cards'] = $cards;
    echo json_encode(['success' => true, 'revision' => $revision]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rawInput = file_get_contents('php://input');
    $payload = json_decode($rawInput, true);

    if ($payload) {
        $studentId = (int)($payload['student_id'] ?? $_SESSION['student_id'] ?? 0);
        $studentName = trim($payload['student_name'] ?? $_SESSION['student_name'] ?? 'Friend');
        $topicId = trim($payload['topic_id'] ?? '');
        $subjectId = trim($payload['subject_id'] ?? '');
        $timeSpent = (int)($payload['time_spent_seconds'] ?? 0);

        if ($studentId > 0 && !empty($topicId)) {
            $stmt = $pdo->prepare("
                INSERT INTO revision_sessions (student_id, student_name, topic_id, subject_id, time_spent_seconds, completed_at)
                VALUES (?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([$studentId, $studentName, $topicId, $subjectId, $timeSpent]);
            echo json_encode(['success' => true, 'message' => 'Revision attempt logged.']);
            exit;
        }
    }
    echo json_encode(['success' => false]);
    exit;
}

