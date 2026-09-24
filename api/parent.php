<?php
// NextGrade API - Parent Portal Progress, Status, Scores & Insights
require_once __DIR__ . '/../db.php';

header('Content-Type: application/json; charset=utf-8');

$studentName = $_GET['student_name'] ?? null;

try {
    // 1. Overall Summary Statistics
    $summarySql = "
        SELECT 
            COUNT(id) as total_sessions,
            COALESCE(SUM(total_questions), 0) as total_questions,
            COALESCE(SUM(score), 0) as total_correct,
            COALESCE(AVG(percentage), 0) as average_score,
            COALESCE(SUM(time_spent_seconds), 0) as total_time_seconds,
            COUNT(DISTINCT student_name) as total_students
        FROM quiz_sessions
    ";
    if ($studentName) {
        $summarySql .= " WHERE student_name = " . $pdo->quote($studentName);
    }
    $summary = $pdo->query($summarySql)->fetch();

    $overallAccuracy = $summary['total_questions'] > 0 
        ? round(($summary['total_correct'] / $summary['total_questions']) * 100, 1) 
        : 0;

    // 2. Subject Breakdown Insights
    $subjectSql = "
        SELECT 
            s.id as subject_id,
            s.name as subject_name,
            s.icon as subject_icon,
            s.accent_color,
            COUNT(qs.id) as sessions_count,
            COALESCE(SUM(qs.total_questions), 0) as questions_attempted,
            COALESCE(SUM(qs.score), 0) as correct_answers,
            COALESCE(AVG(qs.percentage), 0) as avg_accuracy
        FROM subjects s
        LEFT JOIN quiz_sessions qs ON qs.subject_id = s.id " . ($studentName ? " AND qs.student_name = " . $pdo->quote($studentName) : "") . "
        GROUP BY s.id
        ORDER BY s.sort_order ASC
    ";
    $subjectStats = $pdo->query($subjectSql)->fetchAll();

    // 3. Topic Mastery & Improvement Needs
    $topicSql = "
        SELECT 
            t.id as topic_id,
            t.name as topic_name,
            s.name as subject_name,
            COUNT(qs.id) as sessions_count,
            COALESCE(AVG(qs.percentage), 0) as avg_score,
            COALESCE(SUM(qs.score), 0) as total_score,
            COALESCE(SUM(qs.total_questions), 0) as total_q
        FROM topics t
        JOIN subjects s ON s.id = t.subject_id
        LEFT JOIN quiz_sessions qs ON qs.topic_id = t.id " . ($studentName ? " AND qs.student_name = " . $pdo->quote($studentName) : "") . "
        GROUP BY t.id
        HAVING sessions_count > 0
        ORDER BY avg_score ASC
    ";
    $topicPerformances = $pdo->query($topicSql)->fetchAll();

    $needsPractice = [];
    $strengths = [];
    foreach ($topicPerformances as $tp) {
        if ($tp['avg_score'] < 70) {
            $needsPractice[] = $tp;
        } else {
            $strengths[] = $tp;
        }
    }

    // 4. Recent Session History
    $historySql = "
        SELECT 
            qs.*,
            COALESCE(s.name, 'Mixed Quiz') as subject_name,
            COALESCE(s.icon, '🎯') as subject_icon,
            COALESCE(t.name, 'All Topics') as topic_name
        FROM quiz_sessions qs
        LEFT JOIN subjects s ON s.id = qs.subject_id
        LEFT JOIN topics t ON t.id = qs.topic_id
    ";
    if ($studentName) {
        $historySql .= " WHERE qs.student_name = " . $pdo->quote($studentName);
    }
    $historySql .= " ORDER BY qs.completed_at DESC LIMIT 20";
    $recentSessions = $pdo->query($historySql)->fetchAll();

    // 5. Unique Students List for Filter
    $studentsList = $pdo->query("SELECT DISTINCT student_name FROM quiz_sessions ORDER BY student_name ASC")->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode([
        'success' => true,
        'filter_student' => $studentName,
        'students_list' => $studentsList,
        'summary' => [
            'total_sessions' => (int)$summary['total_sessions'],
            'total_questions' => (int)$summary['total_questions'],
            'total_correct' => (int)$summary['total_correct'],
            'average_score' => round((float)$summary['average_score'], 1),
            'overall_accuracy' => $overallAccuracy,
            'total_time_seconds' => (int)$summary['total_time_seconds'],
            'formatted_time' => sprintf('%dh %02dm', floor($summary['total_time_seconds'] / 3600), floor(($summary['total_time_seconds'] % 3600) / 60))
        ],
        'subject_stats' => array_map(function($sub) {
            return [
                'id' => $sub['subject_id'],
                'name' => $sub['subject_name'],
                'icon' => $sub['subject_icon'],
                'color' => $sub['accent_color'],
                'sessions_count' => (int)$sub['sessions_count'],
                'questions_attempted' => (int)$sub['questions_attempted'],
                'correct_answers' => (int)$sub['correct_answers'],
                'accuracy' => round((float)$sub['avg_accuracy'], 1)
            ];
        }, $subjectStats),
        'insights' => [
            'needs_practice' => $needsPractice,
            'strengths' => array_reverse($strengths)
        ],
        'recent_sessions' => $recentSessions
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
