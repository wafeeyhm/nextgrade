<?php
// NextGrade API - Subjects & Subject Details
require_once __DIR__ . '/../db.php';

if (!headers_sent()) {
    header('Content-Type: application/json; charset=utf-8');
}

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
        // Support Grade Filtering (KG3 vs Year 6 PSR)
        if (session_status() === PHP_SESSION_NONE) {
            @session_start();
        }

        $filterGrade = $_GET['grade'] ?? $_SESSION['student_grade'] ?? null;
        $studentId = $_SESSION['student_id'] ?? null;
        if ($studentId && !$filterGrade) {
            $stmtG = $pdo->prepare("SELECT grade_level FROM students WHERE id = ?");
            $stmtG->execute([$studentId]);
            $filterGrade = $stmtG->fetchColumn();
            if ($filterGrade) {
                $_SESSION['student_grade'] = $filterGrade;
            }
        }
        $whereClause = "";
        $params = [];

        if (!empty($_GET['all'])) {
            // Explicit request for all subjects (e.g. Admin or catalog)
            $whereClause = "";
        } elseif ($filterGrade) {
            require_once __DIR__ . '/../auth_helper.php';
            if (isGradeYear6($filterGrade)) {
                $whereClause = " WHERE s.grade_level = 'Year 6 (PSR)' ";
            } elseif (isGradeKG3($filterGrade)) {
                $whereClause = " WHERE s.grade_level = 'Kindergarten 3 (KG3)' OR s.grade_level IS NULL ";
            }
        } else {
            // Default to KG3 for public visitor view if unauthenticated
            $whereClause = " WHERE s.grade_level = 'Kindergarten 3 (KG3)' OR s.grade_level IS NULL ";
        }

        // Fetch subjects with aggregated counts
        $query = "
            SELECT s.*, 
                   COUNT(DISTINCT t.id) as topic_count,
                   COUNT(DISTINCT q.id) as total_questions
            FROM subjects s
            LEFT JOIN topics t ON t.subject_id = s.id
            LEFT JOIN questions q ON q.subject_id = s.id
            $whereClause
            GROUP BY s.id
            ORDER BY s.sort_order ASC
        ";

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $subjects = $stmt->fetchAll();

        echo json_encode([
            'success' => true, 
            'grade_filter' => $filterGrade,
            'subjects' => $subjects
        ]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
