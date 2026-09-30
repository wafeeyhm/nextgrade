<?php
// NextGrade API - Parent CRUD on Kids Access & Profiles
require_once __DIR__ . '/../auth_helper.php';

header('Content-Type: application/json; charset=utf-8');

if (!isParentLoggedIn()) {
    sendJsonResponse(['success' => false, 'error' => 'Parent session required.'], 401);
}

$parentId = (int)$_SESSION['parent_id'];
$method = $_SERVER['REQUEST_METHOD'];

// GET: Retrieve all kids for this parent
if ($method === 'GET') {
    $stmt = $pdo->prepare("
        SELECT 
            s.*,
            COUNT(qs.id) as sessions_count,
            COALESCE(AVG(qs.percentage), 0) as avg_score,
            COALESCE(SUM(qs.total_questions), 0) as questions_answered,
            COALESCE(SUM(qs.time_spent_seconds), 0) as total_time_seconds,
            COALESCE(MAX(qs.completed_at), s.last_active) as latest_activity
        FROM students s
        LEFT JOIN quiz_sessions qs ON qs.student_id = s.id
        WHERE s.parent_id = ?
        GROUP BY s.id
        ORDER BY s.id ASC
    ");
    $stmt->execute([$parentId]);
    $kids = $stmt->fetchAll();

    sendJsonResponse([
        'success' => true,
        'parent_id' => $parentId,
        'kids' => $kids
    ]);
}

// POST: Create, Update, Delete kid, or Switch Session
if ($method === 'POST') {
    $rawInput = file_get_contents('php://input');
    $payload = json_decode($rawInput, true) ?? $_POST;
    $action = $payload['action'] ?? '';

    // 1. CREATE KID
    if ($action === 'create') {
        $name = trim($payload['name'] ?? '');
        $username = strtolower(trim($payload['username'] ?? ''));
        $pinCode = trim($payload['pin_code'] ?? '1234');
        $avatar = trim($payload['avatar'] ?? 'star_kid');
        $gradeLevel = trim($payload['grade_level'] ?? 'Kindergarten 3 (KG3)');

        if (empty($name)) {
            sendJsonResponse(['success' => false, 'error' => 'Please provide child\'s name.'], 400);
        }

        // Auto-generate child username if empty
        if (empty($username)) {
            $baseUser = preg_replace('/[^a-z0-9]/', '', strtolower($name));
            $username = $baseUser . rand(10, 99);
        }

        // Check username uniqueness
        $stmtCheck = $pdo->prepare("SELECT id FROM students WHERE username = ? LIMIT 1");
        $stmtCheck->execute([$username]);
        if ($stmtCheck->fetch()) {
            sendJsonResponse(['success' => false, 'error' => "Username '{$username}' is already taken. Please choose another."], 400);
        }

        // Default PIN if empty or invalid
        if (empty($pinCode) || strlen($pinCode) < 3) {
            $pinCode = '1234';
        }

        $stmtInsert = $pdo->prepare("
            INSERT INTO students (parent_id, name, username, pin_code, avatar, grade_level, status, created_at, last_active)
            VALUES (?, ?, ?, ?, ?, ?, 'active', NOW(), NOW())
        ");
        $stmtInsert->execute([$parentId, $name, $username, $pinCode, $avatar, $gradeLevel]);
        $kidId = (int)$pdo->lastInsertId();

        sendJsonResponse([
            'success' => true,
            'message' => "Child profile for {$name} added successfully!",
            'kid' => [
                'id' => $kidId,
                'name' => $name,
                'username' => $username,
                'pin_code' => $pinCode,
                'avatar' => $avatar,
                'grade_level' => $gradeLevel
            ]
        ]);
    }

    // 2. UPDATE KID
    if ($action === 'update') {
        $kidId = (int)($payload['id'] ?? 0);
        if ($kidId <= 0) {
            sendJsonResponse(['success' => false, 'error' => 'Invalid child ID.'], 400);
        }

        // Ensure this kid belongs to the logged-in parent
        $stmtOwner = $pdo->prepare("SELECT id FROM students WHERE id = ? AND parent_id = ?");
        $stmtOwner->execute([$kidId, $parentId]);
        if (!$stmtOwner->fetch()) {
            sendJsonResponse(['success' => false, 'error' => 'Child profile not found or permission denied.'], 403);
        }

        $name = trim($payload['name'] ?? '');
        $username = strtolower(trim($payload['username'] ?? ''));
        $pinCode = trim($payload['pin_code'] ?? '1234');
        $avatar = trim($payload['avatar'] ?? 'star_kid');
        $gradeLevel = trim($payload['grade_level'] ?? 'Kindergarten 3 (KG3)');
        $status = in_array($payload['status'] ?? '', ['active', 'inactive']) ? $payload['status'] : 'active';

        if (empty($name)) {
            sendJsonResponse(['success' => false, 'error' => 'Child name cannot be empty.'], 400);
        }

        // Check username uniqueness
        if (!empty($username)) {
            $stmtCheck = $pdo->prepare("SELECT id FROM students WHERE username = ? AND id != ? LIMIT 1");
            $stmtCheck->execute([$username, $kidId]);
            if ($stmtCheck->fetch()) {
                sendJsonResponse(['success' => false, 'error' => "Username '{$username}' is already in use by another child."], 400);
            }
        }

        $stmtUpdate = $pdo->prepare("
            UPDATE students 
            SET name = ?, username = ?, pin_code = ?, avatar = ?, grade_level = ?, status = ?
            WHERE id = ? AND parent_id = ?
        ");
        $stmtUpdate->execute([$name, $username, $pinCode, $avatar, $gradeLevel, $status, $kidId, $parentId]);

        sendJsonResponse([
            'success' => true,
            'message' => "Child profile for {$name} updated successfully."
        ]);
    }

    // 3. DELETE KID
    if ($action === 'delete') {
        $kidId = (int)($payload['id'] ?? 0);
        if ($kidId <= 0) {
            sendJsonResponse(['success' => false, 'error' => 'Invalid child ID.'], 400);
        }

        // Ensure ownership
        $stmtOwner = $pdo->prepare("SELECT id, name FROM students WHERE id = ? AND parent_id = ?");
        $stmtOwner->execute([$kidId, $parentId]);
        $kid = $stmtOwner->fetch();
        if (!$kid) {
            sendJsonResponse(['success' => false, 'error' => 'Child profile not found or permission denied.'], 403);
        }

        $stmtDel = $pdo->prepare("DELETE FROM students WHERE id = ? AND parent_id = ?");
        $stmtDel->execute([$kidId, $parentId]);

        sendJsonResponse([
            'success' => true,
            'message' => "Child profile for {$kid['name']} deleted successfully."
        ]);
    }

    // 4. SWITCH TO KID SESSION (Launch Child Learning Hub as this kid)
    if ($action === 'launch_kid_session') {
        $kidId = (int)($payload['id'] ?? 0);
        $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ? AND parent_id = ?");
        $stmt->execute([$kidId, $parentId]);
        $kid = $stmt->fetch();

        if (!$kid) {
            sendJsonResponse(['success' => false, 'error' => 'Child profile not found.'], 404);
        }

        // Set student session
        $_SESSION['student_id'] = (int)$kid['id'];
        $_SESSION['student_name'] = $kid['name'];
        $_SESSION['student_username'] = $kid['username'];
        $_SESSION['student_avatar'] = $kid['avatar'];
        $_SESSION['student_grade'] = $kid['grade_level'];
        $_SESSION['student_parent_id'] = $parentId;
        setcookie('student_name', $kid['name'], time() + (86400 * 30), '/');

        sendJsonResponse([
            'success' => true,
            'message' => "Session started for {$kid['name']}. Redirecting to learning hub...",
            'redirect' => BASE_URL . 'index.php'
        ]);
    }

    sendJsonResponse(['success' => false, 'error' => 'Invalid action.'], 400);
}

sendJsonResponse(['error' => 'Method not allowed'], 405);
