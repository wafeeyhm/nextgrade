<?php
// NextGrade API - Parent Attempts, Question-by-Question Inspector & GAP Analysis
require_once __DIR__ . '/../auth_helper.php';

header('Content-Type: application/json; charset=utf-8');

if (!isParentLoggedIn()) {
    sendJsonResponse(['success' => false, 'error' => 'Parent session required.'], 401);
}

$parentId = (int)$_SESSION['parent_id'];
$method = $_SERVER['REQUEST_METHOD'];

if ($method !== 'GET') {
    sendJsonResponse(['error' => 'Method not allowed'], 405);
}

// 1. Fetch Question-by-Question Detail for a Specific Quiz Session
if (isset($_GET['action']) && $_GET['action'] === 'attempt_details') {
    $sessionId = (int)($_GET['session_id'] ?? 0);
    if ($sessionId <= 0) {
        sendJsonResponse(['success' => false, 'error' => 'Invalid session ID.'], 400);
    }

    // Verify session belongs to one of this parent's kids
    $stmtSess = $pdo->prepare("
        SELECT 
            qs.*, 
            s.name as student_name, 
            s.avatar as student_avatar,
            s.grade_level,
            sub.name as subject_name,
            sub.icon as subject_icon,
            sub.accent_color as subject_color,
            t.name as topic_name
        FROM quiz_sessions qs
        JOIN students s ON s.id = qs.student_id
        LEFT JOIN subjects sub ON sub.id = qs.subject_id
        LEFT JOIN topics t ON t.id = qs.topic_id
        WHERE qs.id = ? AND s.parent_id = ?
    ");
    $stmtSess->execute([$sessionId, $parentId]);
    $session = $stmtSess->fetch();

    if (!$session) {
        sendJsonResponse(['success' => false, 'error' => 'Quiz attempt not found or unauthorized.'], 404);
    }

    // Fetch individual answers with full question context
    $stmtAnswers = $pdo->prepare("
        SELECT 
            qsa.id as answer_id,
            qsa.question_id,
            qsa.student_answer,
            qsa.is_correct,
            qsa.used_hint,
            qsa.created_at as answered_at,
            q.question_text,
            q.question_audio,
            q.question_type,
            q.image_url,
            q.correct_answer,
            q.hint_text,
            q.options_json,
            q.meta_data_json
        FROM quiz_session_answers qsa
        JOIN questions q ON q.id = qsa.question_id
        WHERE qsa.session_id = ?
        ORDER BY qsa.id ASC
    ");
    $stmtAnswers->execute([$sessionId]);
    $rawAnswers = $stmtAnswers->fetchAll();

    $detailedAnswers = [];
    $correctCount = 0;
    $incorrectCount = 0;
    $gapsIdentified = [];

    foreach ($rawAnswers as $idx => $ans) {
        $isCorrect = (bool)$ans['is_correct'];
        if ($isCorrect) {
            $correctCount++;
        } else {
            $incorrectCount++;
            // Log GAP pattern
            $gapsIdentified[] = [
                'question_num' => $idx + 1,
                'question' => $ans['question_text'],
                'student_answer' => $ans['student_answer'],
                'correct_answer' => $ans['correct_answer'],
                'type' => $ans['question_type'],
                'hint_used' => (bool)$ans['used_hint']
            ];
        }

        // Normalize image url
        $imgUrl = null;
        if (!empty($ans['image_url'])) {
            $cleanPath = ltrim($ans['image_url'], '/');
            if (str_starts_with($cleanPath, 'nextgrade/')) {
                $cleanPath = substr($cleanPath, strlen('nextgrade/'));
            }
            $imgUrl = BASE_URL . $cleanPath;
        }

        $detailedAnswers[] = [
            'question_number' => $idx + 1,
            'question_id' => (int)$ans['question_id'],
            'question_text' => $ans['question_text'],
            'question_audio' => $ans['question_audio'],
            'question_type' => $ans['question_type'],
            'image_url' => $imgUrl,
            'options' => json_decode($ans['options_json'], true) ?? [],
            'student_answer' => $ans['student_answer'],
            'correct_answer' => $ans['correct_answer'],
            'is_correct' => $isCorrect,
            'used_hint' => (bool)$ans['used_hint'],
            'hint_text' => $ans['hint_text'],
            'meta' => json_decode($ans['meta_data_json'] ?? '', true)
        ];
    }

    sendJsonResponse([
        'success' => true,
        'session' => $session,
        'breakdown' => [
            'total_questions' => count($detailedAnswers),
            'correct_count' => $correctCount,
            'incorrect_count' => $incorrectCount,
            'accuracy_percentage' => count($detailedAnswers) > 0 ? round(($correctCount / count($detailedAnswers)) * 100, 1) : 0,
            'gaps_identified' => $gapsIdentified
        ],
        'answers' => $detailedAnswers
    ]);
}

// 2. Fetch Revision History
if (isset($_GET['action']) && $_GET['action'] === 'revision_history') {
    $studentId = !empty($_GET['student_id']) ? (int)$_GET['student_id'] : null;

    $sql = "
        SELECT 
            rs.*, 
            s.name as student_name, 
            s.avatar as student_avatar,
            t.name as topic_name,
            sub.name as subject_name,
            sub.icon as subject_icon
        FROM revision_sessions rs
        JOIN students s ON s.id = rs.student_id
        LEFT JOIN topics t ON t.id = rs.topic_id
        LEFT JOIN subjects sub ON sub.id = rs.subject_id
        WHERE s.parent_id = ?
    ";
    $params = [$parentId];

    if ($studentId) {
        $sql .= " AND rs.student_id = ?";
        $params[] = $studentId;
    }

    $sql .= " ORDER BY rs.completed_at DESC LIMIT 30";
    $stmtRev = $pdo->prepare($sql);
    $stmtRev->execute($params);
    $revisions = $stmtRev->fetchAll();

    sendJsonResponse([
        'success' => true,
        'revisions' => $revisions
    ]);
}

// 3. Overall Attempts & GAP Analysis for Parent Dashboard
$studentId = !empty($_GET['student_id']) ? (int)$_GET['student_id'] : null;

// Ensure child belongs to parent if specified
if ($studentId) {
    $stmtChk = $pdo->prepare("SELECT id FROM students WHERE id = ? AND parent_id = ?");
    $stmtChk->execute([$studentId, $parentId]);
    if (!$stmtChk->fetch()) {
        sendJsonResponse(['success' => false, 'error' => 'Child not found.'], 404);
    }
}

// Fetch all kids belonging to parent for selector
$stmtKids = $pdo->prepare("SELECT id, name, avatar, grade_level FROM students WHERE parent_id = ? ORDER BY id ASC");
$stmtKids->execute([$parentId]);
$kidsList = $stmtKids->fetchAll();
$kidIds = array_column($kidsList, 'id');

if (empty($kidIds)) {
    sendJsonResponse([
        'success' => true,
        'has_kids' => false,
        'kids_list' => [],
        'summary' => [
            'total_sessions' => 0,
            'total_questions' => 0,
            'total_correct' => 0,
            'overall_accuracy' => 0,
            'total_time_seconds' => 0,
            'formatted_time' => '0m'
        ],
        'subject_stats' => [],
        'insights' => ['needs_practice' => [], 'strengths' => []],
        'gap_analysis' => [],
        'recent_sessions' => []
    ]);
}

$kidPlaceholders = implode(',', array_fill(0, count($kidIds), '?'));
$filterParams = $kidIds;

$whereClause = "qs.student_id IN ($kidPlaceholders)";
if ($studentId) {
    $whereClause = "qs.student_id = ?";
    $filterParams = [$studentId];
}

// Summary Metrics
$stmtSummary = $pdo->prepare("
    SELECT 
        COUNT(qs.id) as total_sessions,
        COALESCE(SUM(qs.total_questions), 0) as total_questions,
        COALESCE(SUM(qs.score), 0) as total_correct,
        COALESCE(AVG(qs.percentage), 0) as average_score,
        COALESCE(SUM(qs.time_spent_seconds), 0) as total_time_seconds
    FROM quiz_sessions qs
    WHERE {$whereClause}
");
$stmtSummary->execute($filterParams);
$summary = $stmtSummary->fetch();

$overallAccuracy = $summary['total_questions'] > 0 
    ? round(($summary['total_correct'] / $summary['total_questions']) * 100, 1) 
    : 0;

// Subject Performance Breakdown
$stmtSub = $pdo->prepare("
    SELECT 
        sub.id as subject_id,
        sub.name as subject_name,
        sub.icon as subject_icon,
        sub.accent_color,
        COUNT(qs.id) as sessions_count,
        COALESCE(SUM(qs.total_questions), 0) as questions_attempted,
        COALESCE(SUM(qs.score), 0) as correct_answers,
        COALESCE(AVG(qs.percentage), 0) as accuracy
    FROM subjects sub
    LEFT JOIN quiz_sessions qs ON qs.subject_id = sub.id AND {$whereClause}
    GROUP BY sub.id
    ORDER BY sub.sort_order ASC
");
$stmtSub->execute($filterParams);
$subjectStats = $stmtSub->fetchAll();

// Topic Mastery & GAP Analysis
$stmtTopics = $pdo->prepare("
    SELECT 
        t.id as topic_id,
        t.name as topic_name,
        sub.name as subject_name,
        sub.icon as subject_icon,
        COUNT(qs.id) as sessions_count,
        COALESCE(AVG(qs.percentage), 0) as avg_score,
        COALESCE(SUM(qs.score), 0) as total_score,
        COALESCE(SUM(qs.total_questions), 0) as total_q,
        (COALESCE(SUM(qs.total_questions), 0) - COALESCE(SUM(qs.score), 0)) as total_mistakes
    FROM topics t
    JOIN subjects sub ON sub.id = t.subject_id
    JOIN quiz_sessions qs ON qs.topic_id = t.id AND {$whereClause}
    GROUP BY t.id
    ORDER BY avg_score ASC
");
$stmtTopics->execute($filterParams);
$topicPerformances = $stmtTopics->fetchAll();

$needsPractice = [];
$strengths = [];
$gapItems = [];

foreach ($topicPerformances as $tp) {
    $score = round((float)$tp['avg_score'], 1);
    if ($score < 70) {
        $needsPractice[] = $tp;

        // Formulate tailored GAP tip
        $tip = "Encourage child to revisit fundamentals on {$tp['topic_name']}. Try the 5-minute interactive revision first.";
        if (str_contains($tp['topic_id'], 'clock')) {
            $tip = "Child struggles with clock hands. Emphasize that the short hand points to the hour, and the long hand indicates minutes.";
        } elseif (str_contains($tp['topic_id'], 'addition')) {
            $tip = "Practice basic counting with real toys or objects to solidify addition skills up to 10.";
        } elseif (str_contains($tp['topic_id'], 'suku_kata')) {
            $tip = "Help child pronounce vocal sounds (a, e, i, o, u) clearly and clap syllables together.";
        }

        $gapItems[] = [
            'topic_id' => $tp['topic_id'],
            'topic_name' => $tp['topic_name'],
            'subject_name' => $tp['subject_name'],
            'subject_icon' => $tp['subject_icon'],
            'avg_score' => $score,
            'mistakes_count' => (int)$tp['total_mistakes'],
            'guidance_tip' => $tip,
            'revision_url' => "revision.php?topic={$tp['topic_id']}",
            'quiz_url' => "quiz.php?topic_id={$tp['topic_id']}"
        ];
    } else {
        $strengths[] = $tp;
    }
}

// Recent Quiz Sessions Log
$stmtRecent = $pdo->prepare("
    SELECT 
        qs.*,
        s.name as student_name,
        s.avatar as student_avatar,
        COALESCE(sub.name, 'Mixed Quiz') as subject_name,
        COALESCE(sub.icon, '🎯') as subject_icon,
        COALESCE(t.name, 'All Topics') as topic_name
    FROM quiz_sessions qs
    JOIN students s ON s.id = qs.student_id
    LEFT JOIN subjects sub ON sub.id = qs.subject_id
    LEFT JOIN topics t ON t.id = qs.topic_id
    WHERE {$whereClause}
    ORDER BY qs.completed_at DESC
    LIMIT 25
");
$stmtRecent->execute($filterParams);
$recentSessions = $stmtRecent->fetchAll();

sendJsonResponse([
    'success' => true,
    'has_kids' => true,
    'kids_list' => $kidsList,
    'selected_student_id' => $studentId,
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
            'accuracy' => round((float)$sub['accuracy'], 1)
        ];
    }, $subjectStats),
    'insights' => [
        'needs_practice' => $needsPractice,
        'strengths' => array_reverse($strengths)
    ],
    'gap_analysis' => $gapItems,
    'recent_sessions' => $recentSessions
]);
