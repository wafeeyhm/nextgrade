<?php
// NextGrade API - System Admin Questions Bank Management (CRUD)
require_once __DIR__ . '/../auth_helper.php';

header('Content-Type: application/json; charset=utf-8');

// Ensure request is from an authenticated System Admin
if (!isAdminLoggedIn()) {
    sendJsonResponse(['success' => false, 'error' => 'Unauthorized: Admin access required'], 401);
}

$method = $_SERVER['REQUEST_METHOD'];

// GET: Retrieve questions list, metadata, or single question
if ($method === 'GET') {
    $action = $_GET['action'] ?? 'list';

    // 1. Get metadata for cascading dropdowns (subjects, topics, stats)
    if ($action === 'meta') {
        try {
            $subjects = $pdo->query("SELECT id, name, grade_level, icon FROM subjects ORDER BY sort_order ASC, name ASC")->fetchAll();
            $topics = $pdo->query("SELECT id, subject_id, name, grade_level, icon FROM topics ORDER BY subject_id ASC, sort_order ASC, name ASC")->fetchAll();

            $totalAll = (int)$pdo->query("SELECT COUNT(*) FROM questions")->fetchColumn();
            $totalActive = (int)$pdo->query("SELECT COUNT(*) FROM questions WHERE status = 'active'")->fetchColumn();
            $totalInactive = (int)$pdo->query("SELECT COUNT(*) FROM questions WHERE status = 'inactive'")->fetchColumn();
            $totalKG3 = (int)$pdo->query("SELECT COUNT(*) FROM questions WHERE grade_level = 'Kindergarten 3 (KG3)' OR grade_level IS NULL")->fetchColumn();
            $totalPSR = (int)$pdo->query("SELECT COUNT(*) FROM questions WHERE grade_level = 'Year 6 (PSR)' OR topic_id LIKE 'psr_%'")->fetchColumn();

            sendJsonResponse([
                'success' => true,
                'subjects' => $subjects,
                'topics' => $topics,
                'stats' => [
                    'total' => $totalAll,
                    'active' => $totalActive,
                    'inactive' => $totalInactive,
                    'kg3' => $totalKG3,
                    'psr' => $totalPSR
                ]
            ]);
        } catch (Exception $e) {
            sendJsonResponse(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    // 2. Get single question details
    if ($action === 'single' && !empty($_GET['id'])) {
        $qId = (int)$_GET['id'];
        try {
            $stmt = $pdo->prepare("
                SELECT q.*, s.name as subject_name, t.name as topic_name
                FROM questions q
                LEFT JOIN subjects s ON s.id = q.subject_id
                LEFT JOIN topics t ON t.id = q.topic_id
                WHERE q.id = ?
            ");
            $stmt->execute([$qId]);
            $question = $stmt->fetch();

            if (!$question) {
                sendJsonResponse(['success' => false, 'error' => 'Question not found'], 404);
            }

            // Decode JSON options
            $question['options_list'] = json_decode($question['options_json'], true) ?: [];
            $question['meta_data'] = json_decode($question['meta_data_json'] ?? '', true) ?: [];

            sendJsonResponse(['success' => true, 'question' => $question]);
        } catch (Exception $e) {
            sendJsonResponse(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    // 3. Paginated questions list with search & multiple filters
    $page = max(1, (int)($_GET['page'] ?? 1));
    $limit = max(5, min(100, (int)($_GET['limit'] ?? 20)));
    $offset = ($page - 1) * $limit;

    $search = trim($_GET['search'] ?? '');
    $gradeLevel = trim($_GET['grade_level'] ?? '');
    $subjectId = trim($_GET['subject_id'] ?? '');
    $topicId = trim($_GET['topic_id'] ?? '');
    $qType = trim($_GET['question_type'] ?? '');
    $hasImage = trim($_GET['has_image'] ?? '');
    $status = trim($_GET['status'] ?? '');

    $where = [];
    $params = [];

    if ($search !== '') {
        $where[] = "(q.question_text LIKE ? OR q.correct_answer LIKE ? OR q.hint_text LIKE ? OR q.passage LIKE ?)";
        $term = "%$search%";
        $params[] = $term;
        $params[] = $term;
        $params[] = $term;
        $params[] = $term;
    }

    if ($gradeLevel !== '') {
        $where[] = "q.grade_level = ?";
        $params[] = $gradeLevel;
    }

    if ($subjectId !== '') {
        $where[] = "q.subject_id = ?";
        $params[] = $subjectId;
    }

    if ($topicId !== '') {
        $where[] = "q.topic_id = ?";
        $params[] = $topicId;
    }

    if ($qType !== '') {
        $where[] = "q.question_type = ?";
        $params[] = $qType;
    }

    if ($status !== '' && in_array($status, ['active', 'inactive'])) {
        $where[] = "q.status = ?";
        $params[] = $status;
    }

    if ($hasImage === 'yes') {
        $where[] = "q.image_url IS NOT NULL AND q.image_url != ''";
    } elseif ($hasImage === 'no') {
        $where[] = "(q.image_url IS NULL OR q.image_url = '')";
    }

    $whereSql = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

    try {
        // Count total matching records
        $countStmt = $pdo->prepare("SELECT COUNT(*) FROM questions q $whereSql");
        $countStmt->execute($params);
        $totalQuestions = (int)$countStmt->fetchColumn();

        // Fetch paginated slice
        $sql = "
            SELECT q.id, q.topic_id, q.subject_id, q.question_text, q.question_audio,
                   q.lang, q.question_type, q.image_url, q.options_json, q.correct_answer,
                   q.hint_text, q.grade_level, q.sort_order, q.status,
                   s.name as subject_name, s.icon as subject_icon,
                   t.name as topic_name, t.icon as topic_icon
            FROM questions q
            LEFT JOIN subjects s ON s.id = q.subject_id
            LEFT JOIN topics t ON t.id = q.topic_id
            $whereSql
            ORDER BY q.id DESC
            LIMIT $limit OFFSET $offset
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $questions = $stmt->fetchAll();

        foreach ($questions as &$q) {
            $q['options_list'] = json_decode($q['options_json'], true) ?: [];
        }
        unset($q);

        sendJsonResponse([
            'success' => true,
            'total' => $totalQuestions,
            'page' => $page,
            'limit' => $limit,
            'total_pages' => ceil($totalQuestions / $limit),
            'questions' => $questions
        ]);
    } catch (Exception $e) {
        sendJsonResponse(['success' => false, 'error' => $e->getMessage()], 500);
    }
}

// POST: Create, Update, Delete, or Duplicate Question
if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
    $action = $input['action'] ?? '';

    // A. Create Question
    if ($action === 'create') {
        $topicId = trim($input['topic_id'] ?? '');
        $subjectId = trim($input['subject_id'] ?? '');
        $questionText = trim($input['question_text'] ?? '');
        $questionAudio = trim($input['question_audio'] ?? $questionText);
        $lang = trim($input['lang'] ?? 'en');
        $questionType = trim($input['question_type'] ?? 'multiple_choice');
        $imageUrl = trim($input['image_url'] ?? '') ?: null;
        $passage = trim($input['passage'] ?? '') ?: null;
        $options = $input['options'] ?? [];
        $correctAnswer = trim($input['correct_answer'] ?? '');
        $hintText = trim($input['hint_text'] ?? '') ?: $questionText;
        $hintAudio = trim($input['hint_audio'] ?? '') ?: $hintText;
        $gradeLevel = trim($input['grade_level'] ?? 'Kindergarten 3 (KG3)');
        $sortOrder = (int)($input['sort_order'] ?? 0);
        $status = in_array($input['status'] ?? '', ['active', 'inactive']) ? $input['status'] : 'active';

        if (empty($topicId) || empty($subjectId) || empty($questionText) || empty($correctAnswer)) {
            sendJsonResponse(['success' => false, 'error' => 'Subject, Topic, Question Text, and Correct Answer are required.'], 400);
        }

        // Format options array
        if (is_string($options)) {
            $options = json_decode($options, true) ?: array_map('trim', explode(',', $options));
        }
        if (!is_array($options)) {
            $options = [];
        }
        // Ensure options include the correct answer if multiple_choice
        if ($questionType === 'multiple_choice' && !empty($correctAnswer) && !in_array($correctAnswer, $options)) {
            array_unshift($options, $correctAnswer);
        }

        try {
            $stmt = $pdo->prepare("
                INSERT INTO questions (topic_id, subject_id, question_text, question_audio, lang, question_type, image_url, passage, options_json, correct_answer, hint_text, hint_audio, grade_level, sort_order, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $topicId,
                $subjectId,
                $questionText,
                $questionAudio,
                $lang,
                $questionType,
                $imageUrl,
                $passage,
                json_encode($options, JSON_UNESCAPED_UNICODE),
                $correctAnswer,
                $hintText,
                $hintAudio,
                $gradeLevel,
                $sortOrder,
                $status
            ]);
            $newId = (int)$pdo->lastInsertId();

            sendJsonResponse([
                'success' => true,
                'message' => 'Question created successfully.',
                'question_id' => $newId
            ]);
        } catch (Exception $e) {
            sendJsonResponse(['success' => false, 'error' => 'Database error: ' . $e->getMessage()], 500);
        }
    }

    // B. Update Question
    if ($action === 'update') {
        $id = (int)($input['id'] ?? 0);
        $topicId = trim($input['topic_id'] ?? '');
        $subjectId = trim($input['subject_id'] ?? '');
        $questionText = trim($input['question_text'] ?? '');
        $questionAudio = trim($input['question_audio'] ?? $questionText);
        $lang = trim($input['lang'] ?? 'en');
        $questionType = trim($input['question_type'] ?? 'multiple_choice');
        $imageUrl = trim($input['image_url'] ?? '') ?: null;
        $passage = trim($input['passage'] ?? '') ?: null;
        $options = $input['options'] ?? [];
        $correctAnswer = trim($input['correct_answer'] ?? '');
        $hintText = trim($input['hint_text'] ?? '') ?: $questionText;
        $hintAudio = trim($input['hint_audio'] ?? '') ?: $hintText;
        $gradeLevel = trim($input['grade_level'] ?? 'Kindergarten 3 (KG3)');
        $sortOrder = (int)($input['sort_order'] ?? 0);
        $status = in_array($input['status'] ?? '', ['active', 'inactive']) ? $input['status'] : 'active';

        if ($id <= 0 || empty($topicId) || empty($subjectId) || empty($questionText) || empty($correctAnswer)) {
            sendJsonResponse(['success' => false, 'error' => 'Question ID, Subject, Topic, Question Text, and Correct Answer are required.'], 400);
        }

        // Format options array
        if (is_string($options)) {
            $options = json_decode($options, true) ?: array_map('trim', explode(',', $options));
        }
        if (!is_array($options)) {
            $options = [];
        }

        try {
            $stmt = $pdo->prepare("
                UPDATE questions 
                SET topic_id = ?, subject_id = ?, question_text = ?, question_audio = ?, lang = ?, 
                    question_type = ?, image_url = ?, passage = ?, options_json = ?, correct_answer = ?, 
                    hint_text = ?, hint_audio = ?, grade_level = ?, sort_order = ?, status = ?
                WHERE id = ?
            ");
            $stmt->execute([
                $topicId,
                $subjectId,
                $questionText,
                $questionAudio,
                $lang,
                $questionType,
                $imageUrl,
                $passage,
                json_encode($options, JSON_UNESCAPED_UNICODE),
                $correctAnswer,
                $hintText,
                $hintAudio,
                $gradeLevel,
                $sortOrder,
                $status,
                $id
            ]);

            sendJsonResponse([
                'success' => true,
                'message' => 'Question updated successfully.'
            ]);
        } catch (Exception $e) {
            sendJsonResponse(['success' => false, 'error' => 'Database error: ' . $e->getMessage()], 500);
        }
    }

    // C. Delete Question
    if ($action === 'delete') {
        $id = (int)($input['id'] ?? 0);
        if ($id <= 0) {
            sendJsonResponse(['success' => false, 'error' => 'Valid Question ID is required.'], 400);
        }

        try {
            $stmt = $pdo->prepare("DELETE FROM questions WHERE id = ?");
            $stmt->execute([$id]);

            sendJsonResponse([
                'success' => true,
                'message' => 'Question deleted successfully.'
            ]);
        } catch (Exception $e) {
            sendJsonResponse(['success' => false, 'error' => 'Failed to delete question: ' . $e->getMessage()], 500);
        }
    }

    // D. Duplicate Question
    if ($action === 'duplicate') {
        $id = (int)($input['id'] ?? 0);
        if ($id <= 0) {
            sendJsonResponse(['success' => false, 'error' => 'Valid Question ID is required.'], 400);
        }

        try {
            $stmtOriginal = $pdo->prepare("SELECT * FROM questions WHERE id = ?");
            $stmtOriginal->execute([$id]);
            $orig = $stmtOriginal->fetch();

            if (!$orig) {
                sendJsonResponse(['success' => false, 'error' => 'Original question not found.'], 404);
            }

            $stmtDup = $pdo->prepare("
                INSERT INTO questions (topic_id, subject_id, question_text, question_audio, lang, question_type, image_url, passage, options_json, correct_answer, hint_text, hint_audio, grade_level, sort_order, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmtDup->execute([
                $orig['topic_id'],
                $orig['subject_id'],
                $orig['question_text'] . ' (Copy)',
                $orig['question_audio'],
                $orig['lang'],
                $orig['question_type'],
                $orig['image_url'],
                $orig['passage'],
                $orig['options_json'],
                $orig['correct_answer'],
                $orig['hint_text'],
                $orig['hint_audio'],
                $orig['grade_level'],
                $orig['sort_order'] + 1,
                $orig['status'] ?? 'active'
            ]);
            $newId = (int)$pdo->lastInsertId();

            sendJsonResponse([
                'success' => true,
                'message' => "Question duplicated as #$newId.",
                'new_id' => $newId
            ]);
        } catch (Exception $e) {
            sendJsonResponse(['success' => false, 'error' => 'Failed to duplicate question: ' . $e->getMessage()], 500);
        }
    }

    // E. Toggle Question Status (Enable / Disable)
    if ($action === 'toggle_status') {
        $id = (int)($input['id'] ?? 0);
        if ($id <= 0) {
            sendJsonResponse(['success' => false, 'error' => 'Valid Question ID is required.'], 400);
        }

        try {
            $stmt = $pdo->prepare("SELECT status FROM questions WHERE id = ?");
            $stmt->execute([$id]);
            $curStatus = $stmt->fetchColumn();

            if (!$curStatus) {
                sendJsonResponse(['success' => false, 'error' => 'Question not found.'], 404);
            }

            $newStatus = ($curStatus === 'active') ? 'inactive' : 'active';
            $stmtUpdate = $pdo->prepare("UPDATE questions SET status = ? WHERE id = ?");
            $stmtUpdate->execute([$newStatus, $id]);

            sendJsonResponse([
                'success' => true,
                'id' => $id,
                'new_status' => $newStatus,
                'message' => "Question #$id " . ($newStatus === 'active' ? 'enabled' : 'disabled') . " successfully."
            ]);
        } catch (Exception $e) {
            sendJsonResponse(['success' => false, 'error' => 'Failed to toggle status: ' . $e->getMessage()], 500);
        }
    }

    sendJsonResponse(['success' => false, 'error' => 'Invalid action specified.'], 400);
}

sendJsonResponse(['success' => false, 'error' => 'Method not allowed.'], 405);
