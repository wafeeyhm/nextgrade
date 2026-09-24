<?php
// NextGrade API - Topics
require_once __DIR__ . '/../db.php';

header('Content-Type: application/json; charset=utf-8');

$subjectId = $_GET['subject_id'] ?? null;
$topicId = $_GET['id'] ?? $_GET['topic_id'] ?? null;

try {
    if ($topicId) {
        $stmt = $pdo->prepare("
            SELECT t.*, s.name as subject_name, s.theme_gradient, s.accent_color,
                   COUNT(q.id) as question_count
            FROM topics t
            JOIN subjects s ON s.id = t.subject_id
            LEFT JOIN questions q ON q.topic_id = t.id
            WHERE t.id = ?
            GROUP BY t.id
        ");
        $stmt->execute([$topicId]);
        $topic = $stmt->fetch();

        if (!$topic) {
            http_response_code(404);
            echo json_encode(['error' => 'Topic not found']);
            exit;
        }

        echo json_encode(['success' => true, 'topic' => $topic]);
    } else {
        $query = "
            SELECT t.*, s.name as subject_name, s.accent_color,
                   COUNT(q.id) as question_count
            FROM topics t
            JOIN subjects s ON s.id = t.subject_id
            LEFT JOIN questions q ON q.topic_id = t.id
        ";
        $params = [];

        if ($subjectId) {
            $query .= " WHERE t.subject_id = ? ";
            $params[] = $subjectId;
        }

        $query .= " GROUP BY t.id ORDER BY t.sort_order ASC";

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $topics = $stmt->fetchAll();

        echo json_encode(['success' => true, 'topics' => $topics]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
