<?php
// NextGrade API - Subjects & Subject Details
require_once __DIR__ . '/../db.php';

header('Content-Type: application/json; charset=utf-8');

$subjectId = $_GET['id'] ?? $_GET['subject_id'] ?? null;

try {
    if ($subjectId) {
        $stmtSub = $pdo->prepare("SELECT * FROM subjects WHERE id = ?");
        $stmtSub->execute([$subjectId]);
        $subject = $stmtSub->fetch();

        if (!$subject) {
            http_response_code(404);
            echo json_encode(['error' => 'Subject not found']);
            exit;
        }

        // Fetch topics for this subject
        $stmtTopics = $pdo->prepare("
            SELECT t.*, COUNT(q.id) as question_count 
            FROM topics t
            LEFT JOIN questions q ON q.topic_id = t.id
            WHERE t.subject_id = ?
            GROUP BY t.id
            ORDER BY t.sort_order ASC
        ");
        $stmtTopics->execute([$subjectId]);
        $topics = $stmtTopics->fetchAll();

        $subject['topics'] = $topics;
        echo json_encode(['success' => true, 'subject' => $subject]);
    } else {
        // Fetch all subjects with aggregated counts
        $stmt = $pdo->query("
            SELECT s.*, 
                   COUNT(DISTINCT t.id) as topic_count,
                   COUNT(DISTINCT q.id) as total_questions
            FROM subjects s
            LEFT JOIN topics t ON t.subject_id = s.id
            LEFT JOIN questions q ON q.subject_id = s.id
            GROUP BY s.id
            ORDER BY s.sort_order ASC
        ");
        $subjects = $stmt->fetchAll();

        echo json_encode(['success' => true, 'subjects' => $subjects]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
