<?php
// NextGrade API - System Admin Topics Management (CRUD)
require_once __DIR__ . '/../auth_helper.php';

header('Content-Type: application/json; charset=utf-8');

// Ensure request is from an authenticated System Admin
if (!isAdminLoggedIn()) {
    sendJsonResponse(['success' => false, 'error' => 'Unauthorized: Admin access required'], 401);
}

$method = $_SERVER['REQUEST_METHOD'];

// GET: Retrieve topics list, single topic, or subjects list
if ($method === 'GET') {
    $action = $_GET['action'] ?? 'list';

    // 1. Get subjects list for dropdowns
    if ($action === 'subjects') {
        try {
            $subjects = $pdo->query("SELECT id, name, grade_level, icon FROM subjects ORDER BY sort_order ASC, name ASC")->fetchAll();
            sendJsonResponse(['success' => true, 'subjects' => $subjects]);
        } catch (Exception $e) {
            sendJsonResponse(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    // 2. Get single topic details
    if ($action === 'single' && !empty($_GET['id'])) {
        $topicId = trim($_GET['id']);
        try {
            $stmt = $pdo->prepare("
                SELECT t.*, s.name as subject_name, s.grade_level as subject_grade,
                       COUNT(q.id) as question_count
                FROM topics t
                LEFT JOIN subjects s ON s.id = t.subject_id
                LEFT JOIN questions q ON q.topic_id = t.id
                WHERE t.id = ?
                GROUP BY t.id
            ");
            $stmt->execute([$topicId]);
            $topic = $stmt->fetch();

            if (!$topic) {
                sendJsonResponse(['success' => false, 'error' => 'Topic not found'], 404);
            }

            sendJsonResponse(['success' => true, 'topic' => $topic]);
        } catch (Exception $e) {
            sendJsonResponse(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    // 3. List topics with search and filtering
    $search = trim($_GET['search'] ?? '');
    $subjectId = trim($_GET['subject_id'] ?? '');
    $gradeLevel = trim($_GET['grade_level'] ?? '');

    $where = [];
    $params = [];

    if ($search !== '') {
        $where[] = "(t.id LIKE ? OR t.name LIKE ? OR t.name_native LIKE ? OR t.description LIKE ?)";
        $term = "%$search%";
        $params[] = $term;
        $params[] = $term;
        $params[] = $term;
        $params[] = $term;
    }

    if ($subjectId !== '') {
        $where[] = "t.subject_id = ?";
        $params[] = $subjectId;
    }

    if ($gradeLevel !== '') {
        $where[] = "t.grade_level = ?";
        $params[] = $gradeLevel;
    }

    $whereSql = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

    try {
        $sql = "
            SELECT t.*, s.name as subject_name, s.grade_level as subject_grade,
                   COUNT(q.id) as question_count
            FROM topics t
            LEFT JOIN subjects s ON s.id = t.subject_id
            LEFT JOIN questions q ON q.topic_id = t.id
            $whereSql
            GROUP BY t.id
            ORDER BY t.grade_level ASC, t.subject_id ASC, t.sort_order ASC, t.name ASC
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $topics = $stmt->fetchAll();

        // Get subjects for filter dropdown
        $subjects = $pdo->query("SELECT id, name, grade_level FROM subjects ORDER BY sort_order ASC, name ASC")->fetchAll();

        sendJsonResponse([
            'success' => true,
            'total' => count($topics),
            'topics' => $topics,
            'subjects' => $subjects
        ]);
    } catch (Exception $e) {
        sendJsonResponse(['success' => false, 'error' => $e->getMessage()], 500);
    }
}

// POST: Create, Update, or Delete Topic
if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
    $action = $input['action'] ?? '';

    // A. Create Topic
    if ($action === 'create') {
        $id = trim($input['id'] ?? '');
        $subjectId = trim($input['subject_id'] ?? '');
        $name = trim($input['name'] ?? '');
        $nameNative = trim($input['name_native'] ?? $name);
        $description = trim($input['description'] ?? '');
        $icon = trim($input['icon'] ?? '📚');
        $colorBadge = trim($input['color_badge'] ?? 'bg-sky-100 text-sky-800');
        $timeLimit = (int)($input['revision_time_limit'] ?? 300);
        $sortOrder = (int)($input['sort_order'] ?? 0);
        $gradeLevel = trim($input['grade_level'] ?? 'Kindergarten 3 (KG3)');

        if (empty($id) || empty($subjectId) || empty($name)) {
            sendJsonResponse(['success' => false, 'error' => 'Topic ID, Subject, and Topic Name are required.'], 400);
        }

        // Sanitize topic ID slug
        $id = strtolower(preg_replace('/[^a-zA-Z0-9_-]/', '_', $id));

        // Check if ID exists
        $stmtCheck = $pdo->prepare("SELECT id FROM topics WHERE id = ?");
        $stmtCheck->execute([$id]);
        if ($stmtCheck->fetch()) {
            sendJsonResponse(['success' => false, 'error' => "A topic with ID '$id' already exists. Please choose a unique ID."], 400);
        }

        try {
            $stmt = $pdo->prepare("
                INSERT INTO topics (id, subject_id, name, name_native, description, icon, color_badge, revision_time_limit, grade_level, sort_order)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$id, $subjectId, $name, $nameNative, $description, $icon, $colorBadge, $timeLimit, $gradeLevel, $sortOrder]);

            // Create placeholder revision module if none exists
            $stmtRev = $pdo->prepare("
                INSERT IGNORE INTO revisions (topic_id, title, summary, content_json)
                VALUES (?, ?, ?, ?)
            ");
            $initialCards = [
                ['title' => $name, 'text' => $description, 'icon' => $icon, 'image_url' => null]
            ];
            $stmtRev->execute([$id, "Revision: $name", $description, json_encode($initialCards, JSON_UNESCAPED_UNICODE)]);

            sendJsonResponse([
                'success' => true,
                'message' => "Topic '$name' created successfully.",
                'topic_id' => $id
            ]);
        } catch (Exception $e) {
            sendJsonResponse(['success' => false, 'error' => 'Database error: ' . $e->getMessage()], 500);
        }
    }

    // B. Update Topic
    if ($action === 'update') {
        $id = trim($input['id'] ?? '');
        $subjectId = trim($input['subject_id'] ?? '');
        $name = trim($input['name'] ?? '');
        $nameNative = trim($input['name_native'] ?? $name);
        $description = trim($input['description'] ?? '');
        $icon = trim($input['icon'] ?? '📚');
        $colorBadge = trim($input['color_badge'] ?? 'bg-sky-100 text-sky-800');
        $timeLimit = (int)($input['revision_time_limit'] ?? 300);
        $sortOrder = (int)($input['sort_order'] ?? 0);
        $gradeLevel = trim($input['grade_level'] ?? 'Kindergarten 3 (KG3)');

        if (empty($id) || empty($name) || empty($subjectId)) {
            sendJsonResponse(['success' => false, 'error' => 'Topic ID, Subject, and Name are required.'], 400);
        }

        try {
            $stmt = $pdo->prepare("
                UPDATE topics 
                SET subject_id = ?, name = ?, name_native = ?, description = ?, icon = ?, 
                    color_badge = ?, revision_time_limit = ?, grade_level = ?, sort_order = ?
                WHERE id = ?
            ");
            $stmt->execute([$subjectId, $name, $nameNative, $description, $icon, $colorBadge, $timeLimit, $gradeLevel, $sortOrder, $id]);

            sendJsonResponse([
                'success' => true,
                'message' => "Topic '$name' updated successfully."
            ]);
        } catch (Exception $e) {
            sendJsonResponse(['success' => false, 'error' => 'Database error: ' . $e->getMessage()], 500);
        }
    }

    // C. Delete Topic
    if ($action === 'delete') {
        $id = trim($input['id'] ?? '');
        if (empty($id)) {
            sendJsonResponse(['success' => false, 'error' => 'Topic ID is required.'], 400);
        }

        try {
            // Check question count
            $stmtCount = $pdo->prepare("SELECT COUNT(*) FROM questions WHERE topic_id = ?");
            $stmtCount->execute([$id]);
            $qCount = (int)$stmtCount->fetchColumn();

            $stmtDel = $pdo->prepare("DELETE FROM topics WHERE id = ?");
            $stmtDel->execute([$id]);

            sendJsonResponse([
                'success' => true,
                'message' => "Topic '$id' and its associated $qCount question(s) were deleted successfully."
            ]);
        } catch (Exception $e) {
            sendJsonResponse(['success' => false, 'error' => 'Failed to delete topic: ' . $e->getMessage()], 500);
        }
    }

    sendJsonResponse(['success' => false, 'error' => 'Invalid action specified.'], 400);
}

sendJsonResponse(['success' => false, 'error' => 'Method not allowed.'], 405);
