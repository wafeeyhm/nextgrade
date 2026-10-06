<?php
// NextGrade API - System Admin Students & Kids Management (CRUD, Toggle Status, PIN Update)
require_once __DIR__ . '/../auth_helper.php';

header('Content-Type: application/json; charset=utf-8');

// Ensure request is from an authenticated System Admin
if (!isAdminLoggedIn()) {
    sendJsonResponse(['success' => false, 'error' => 'Unauthorized: Admin access required.'], 401);
}

$method = $_SERVER['REQUEST_METHOD'];

// ==========================================
// GET: Retrieve Students List, Single, or Stats
// ==========================================
if ($method === 'GET') {
    $action = $_GET['action'] ?? 'list';

    // 1. Single student details
    if ($action === 'single' && !empty($_GET['id'])) {
        $studentId = (int)$_GET['id'];
        $stmt = $pdo->prepare("
            SELECT s.*, p.full_name as parent_name, p.parent_code, p.email as parent_email, p.status as parent_status
            FROM students s
            LEFT JOIN parents p ON p.id = s.parent_id
            WHERE s.id = ?
        ");
        $stmt->execute([$studentId]);
        $student = $stmt->fetch();

        if (!$student) {
            sendJsonResponse(['success' => false, 'error' => 'Student not found.'], 404);
        }

        sendJsonResponse(['success' => true, 'student' => $student]);
    }

    // 2. Parents dropdown list for select menus
    if ($action === 'parents_list') {
        $parents = $pdo->query("SELECT id, full_name, username, parent_code, status FROM parents ORDER BY full_name ASC")->fetchAll();
        sendJsonResponse(['success' => true, 'parents' => $parents]);
    }

    // 3. Paginated / Filtered Students List
    $search = trim($_GET['search'] ?? '');
    $status = trim($_GET['status'] ?? '');
    $gradeLevel = trim($_GET['grade_level'] ?? '');

    $where = [];
    $params = [];

    if ($search !== '') {
        $where[] = "(s.name LIKE ? OR s.username LIKE ? OR s.pin_code LIKE ? OR p.full_name LIKE ? OR p.parent_code LIKE ? OR p.email LIKE ?)";
        $term = "%{$search}%";
        $params = array_merge($params, [$term, $term, $term, $term, $term, $term]);
    }

    if ($status !== '' && in_array($status, ['active', 'inactive'])) {
        $where[] = "s.status = ?";
        $params[] = $status;
    }

    if ($gradeLevel !== '') {
        $where[] = "s.grade_level = ?";
        $params[] = $gradeLevel;
    }

    $whereSql = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

    $sql = "
        SELECT 
            s.*, 
            p.full_name as parent_name, 
            p.parent_code,
            p.email as parent_email,
            p.status as parent_status,
            COUNT(qs.id) as sessions_count,
            COALESCE(AVG(qs.percentage), 0) as avg_score,
            COALESCE(MAX(qs.completed_at), s.last_active) as latest_activity
        FROM students s
        LEFT JOIN parents p ON p.id = s.parent_id
        LEFT JOIN quiz_sessions qs ON qs.student_id = s.id
        $whereSql
        GROUP BY s.id
        ORDER BY s.id DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $students = $stmt->fetchAll();

    // Summary statistics
    $totalStudents = (int)$pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
    $activeStudents = (int)$pdo->query("SELECT COUNT(*) FROM students WHERE status = 'active'")->fetchColumn();
    $inactiveStudents = (int)$pdo->query("SELECT COUNT(*) FROM students WHERE status = 'inactive'")->fetchColumn();
    $totalQuizzes = (int)$pdo->query("SELECT COUNT(*) FROM quiz_sessions")->fetchColumn();

    // All parents list for modal select
    $parentsList = $pdo->query("SELECT id, full_name, parent_code, status FROM parents ORDER BY full_name ASC")->fetchAll();

    sendJsonResponse([
        'success' => true,
        'students' => $students,
        'parents' => $parentsList,
        'stats' => [
            'total' => $totalStudents,
            'active' => $activeStudents,
            'inactive' => $inactiveStudents,
            'quizzes' => $totalQuizzes
        ]
    ]);
}

// ==========================================
// POST: Create, Update, Delete, Toggle, Change PIN
// ==========================================
if ($method === 'POST') {
    $rawInput = file_get_contents('php://input');
    $payload = json_decode($rawInput, true) ?? $_POST;
    $action = $payload['action'] ?? '';

    // 1. TOGGLE STUDENT STATUS (Active / Inactive)
    if ($action === 'toggle_status') {
        $id = (int)($payload['id'] ?? 0);
        if ($id <= 0) {
            sendJsonResponse(['success' => false, 'error' => 'Invalid student ID.'], 400);
        }

        $stmt = $pdo->prepare("SELECT status, name FROM students WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        if (!$row) {
            sendJsonResponse(['success' => false, 'error' => 'Student not found.'], 404);
        }

        $newStatus = ($row['status'] === 'active') ? 'inactive' : 'active';
        $pdo->prepare("UPDATE students SET status = ? WHERE id = ?")->execute([$newStatus, $id]);

        sendJsonResponse([
            'success' => true,
            'id' => $id,
            'new_status' => $newStatus,
            'message' => "Student {$row['name']} is now " . ($newStatus === 'active' ? 'active (enabled)' : 'deactivated (disabled)') . "."
        ]);
    }

    // 2. QUICK UPDATE PIN CODE
    if ($action === 'update_pin') {
        $id = (int)($payload['id'] ?? 0);
        $pinCode = trim((string)($payload['pin_code'] ?? ''));

        if ($id <= 0) {
            sendJsonResponse(['success' => false, 'error' => 'Invalid student ID.'], 400);
        }

        if (empty($pinCode) || strlen($pinCode) < 3) {
            sendJsonResponse(['success' => false, 'error' => 'PIN code must be at least 3 digits/characters.'], 400);
        }

        $stmt = $pdo->prepare("SELECT name FROM students WHERE id = ?");
        $stmt->execute([$id]);
        $name = $stmt->fetchColumn();

        if (!$name) {
            sendJsonResponse(['success' => false, 'error' => 'Student not found.'], 404);
        }

        $pdo->prepare("UPDATE students SET pin_code = ? WHERE id = ?")->execute([$pinCode, $id]);

        sendJsonResponse([
            'success' => true,
            'id' => $id,
            'pin_code' => $pinCode,
            'message' => "PIN code for {$name} successfully updated to '{$pinCode}'."
        ]);
    }

    // 3. CREATE STUDENT
    if ($action === 'create') {
        $name = trim($payload['name'] ?? '');
        $username = strtolower(trim($payload['username'] ?? ''));
        $pinCode = trim((string)($payload['pin_code'] ?? '1234'));
        $avatar = trim($payload['avatar'] ?? 'star_kid');
        $gradeLevel = trim($payload['grade_level'] ?? 'Kindergarten 3 (KG3)');
        $parentId = !empty($payload['parent_id']) ? (int)$payload['parent_id'] : null;
        $status = in_array($payload['status'] ?? '', ['active', 'inactive']) ? $payload['status'] : 'active';

        if (empty($name)) {
            sendJsonResponse(['success' => false, 'error' => 'Student name is required.'], 400);
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
            sendJsonResponse(['success' => false, 'error' => "Username '{$username}' is already in use. Please pick another."], 400);
        }

        if (empty($pinCode) || strlen($pinCode) < 3) {
            $pinCode = '1234';
        }

        $stmtInsert = $pdo->prepare("
            INSERT INTO students (parent_id, name, username, pin_code, avatar, grade_level, status, created_at, last_active)
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");
        $stmtInsert->execute([$parentId, $name, $username, $pinCode, $avatar, $gradeLevel, $status]);
        $newId = (int)$pdo->lastInsertId();

        sendJsonResponse([
            'success' => true,
            'student_id' => $newId,
            'message' => "Student profile for {$name} created successfully!"
        ]);
    }

    // 4. UPDATE STUDENT
    if ($action === 'update') {
        $id = (int)($payload['id'] ?? 0);
        if ($id <= 0) {
            sendJsonResponse(['success' => false, 'error' => 'Invalid student ID.'], 400);
        }

        $name = trim($payload['name'] ?? '');
        $username = strtolower(trim($payload['username'] ?? ''));
        $pinCode = trim((string)($payload['pin_code'] ?? '1234'));
        $avatar = trim($payload['avatar'] ?? 'star_kid');
        $gradeLevel = trim($payload['grade_level'] ?? 'Kindergarten 3 (KG3)');
        $parentId = !empty($payload['parent_id']) ? (int)$payload['parent_id'] : null;
        $status = in_array($payload['status'] ?? '', ['active', 'inactive']) ? $payload['status'] : 'active';

        if (empty($name)) {
            sendJsonResponse(['success' => false, 'error' => 'Student name cannot be empty.'], 400);
        }

        // Check username uniqueness
        if (!empty($username)) {
            $stmtCheck = $pdo->prepare("SELECT id FROM students WHERE username = ? AND id != ? LIMIT 1");
            $stmtCheck->execute([$username, $id]);
            if ($stmtCheck->fetch()) {
                sendJsonResponse(['success' => false, 'error' => "Username '{$username}' is already in use by another student."], 400);
            }
        }

        if (empty($pinCode) || strlen($pinCode) < 3) {
            $pinCode = '1234';
        }

        $stmtUpdate = $pdo->prepare("
            UPDATE students 
            SET parent_id = ?, name = ?, username = ?, pin_code = ?, avatar = ?, grade_level = ?, status = ?
            WHERE id = ?
        ");
        $stmtUpdate->execute([$parentId, $name, $username, $pinCode, $avatar, $gradeLevel, $status, $id]);

        sendJsonResponse([
            'success' => true,
            'message' => "Student profile for {$name} updated successfully."
        ]);
    }

    // 5. DELETE STUDENT
    if ($action === 'delete') {
        $id = (int)($payload['id'] ?? 0);
        if ($id <= 0) {
            sendJsonResponse(['success' => false, 'error' => 'Invalid student ID.'], 400);
        }

        $stmt = $pdo->prepare("SELECT name FROM students WHERE id = ?");
        $stmt->execute([$id]);
        $name = $stmt->fetchColumn();

        if (!$name) {
            sendJsonResponse(['success' => false, 'error' => 'Student not found.'], 404);
        }

        // Delete quiz session answers & sessions
        $pdo->prepare("DELETE FROM quiz_sessions WHERE student_id = ?")->execute([$id]);
        $pdo->prepare("DELETE FROM revision_sessions WHERE student_id = ?")->execute([$id]);

        // Delete student
        $pdo->prepare("DELETE FROM students WHERE id = ?")->execute([$id]);

        sendJsonResponse([
            'success' => true,
            'message' => "Student {$name} deleted successfully."
        ]);
    }

    sendJsonResponse(['success' => false, 'error' => 'Invalid action.'], 400);
}

sendJsonResponse(['error' => 'Method not allowed'], 405);
