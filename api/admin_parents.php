<?php
// NextGrade API - System Admin Parents Management (CRUD)
require_once __DIR__ . '/../auth_helper.php';

header('Content-Type: application/json; charset=utf-8');

// Ensure request is from an authenticated System Admin
if (!isAdminLoggedIn()) {
    sendJsonResponse(['success' => false, 'error' => 'Unauthorized: Admin access required'], 401);
}

$method = $_SERVER['REQUEST_METHOD'];

// GET: Retrieve parents list or single parent
if ($method === 'GET') {
    $action = $_GET['action'] ?? 'list';

    if ($action === 'single' && !empty($_GET['id'])) {
        $parentId = (int)$_GET['id'];
        $stmt = $pdo->prepare("
            SELECT p.*, COUNT(s.id) as total_kids
            FROM parents p
            LEFT JOIN students s ON s.parent_id = p.id
            WHERE p.id = ?
            GROUP BY p.id
        ");
        $stmt->execute([$parentId]);
        $parent = $stmt->fetch();

        if (!$parent) {
            sendJsonResponse(['success' => false, 'error' => 'Parent not found'], 404);
        }

        unset($parent['password_hash']);

        // Fetch kids linked to this parent
        $stmtKids = $pdo->prepare("
            SELECT s.*, 
                   COUNT(qs.id) as quiz_count, 
                   COALESCE(AVG(qs.percentage), 0) as avg_score
            FROM students s
            LEFT JOIN quiz_sessions qs ON qs.student_id = s.id
            WHERE s.parent_id = ?
            GROUP BY s.id
            ORDER BY s.id ASC
        ");
        $stmtKids->execute([$parentId]);
        $kids = $stmtKids->fetchAll();

        sendJsonResponse([
            'success' => true,
            'parent' => $parent,
            'kids' => $kids
        ]);
    }

    // Default: List parents
    $search = trim($_GET['search'] ?? '');
    $status = trim($_GET['status'] ?? '');

    $sql = "
        SELECT 
            p.id, 
            p.parent_code, 
            p.username, 
            p.full_name, 
            p.email, 
            p.phone, 
            p.status, 
            p.created_at, 
            p.last_login,
            COUNT(s.id) as kids_count
        FROM parents p
        LEFT JOIN students s ON s.parent_id = p.id
        WHERE 1=1
    ";
    $params = [];

    if ($search !== '') {
        $sql .= " AND (p.full_name LIKE ? OR p.email LIKE ? OR p.username LIKE ? OR p.parent_code LIKE ? OR p.phone LIKE ?)";
        $searchParam = "%{$search}%";
        $params = array_merge($params, [$searchParam, $searchParam, $searchParam, $searchParam, $searchParam]);
    }

    if ($status !== '' && in_array($status, ['active', 'inactive'])) {
        $sql .= " AND p.status = ?";
        $params[] = $status;
    }

    $sql .= " GROUP BY p.id ORDER BY p.id DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $parents = $stmt->fetchAll();

    // System overview stats
    $totalParents = (int)$pdo->query("SELECT COUNT(*) FROM parents")->fetchColumn();
    $activeParents = (int)$pdo->query("SELECT COUNT(*) FROM parents WHERE status = 'active'")->fetchColumn();
    $totalKids = (int)$pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
    $totalSessions = (int)$pdo->query("SELECT COUNT(*) FROM quiz_sessions")->fetchColumn();

    sendJsonResponse([
        'success' => true,
        'parents' => $parents,
        'stats' => [
            'total_parents' => $totalParents,
            'active_parents' => $activeParents,
            'total_kids' => $totalKids,
            'total_sessions' => $totalSessions
        ]
    ]);
}

// POST: Create, Update, Delete, or Toggle Parent
if ($method === 'POST') {
    $rawInput = file_get_contents('php://input');
    $payload = json_decode($rawInput, true) ?? $_POST;
    $action = $payload['action'] ?? '';

    // 1. CREATE PARENT
    if ($action === 'create') {
        $fullName = trim($payload['full_name'] ?? '');
        $username = strtolower(trim($payload['username'] ?? ''));
        $email = strtolower(trim($payload['email'] ?? ''));
        $phone = trim($payload['phone'] ?? '');
        $password = $payload['password'] ?? '';
        $status = in_array($payload['status'] ?? '', ['active', 'inactive']) ? $payload['status'] : 'active';

        // Auto-generate parent code if empty
        $parentCode = trim($payload['parent_code'] ?? '');
        if (empty($parentCode)) {
            $nextId = (int)$pdo->query("SELECT MAX(id) FROM parents")->fetchColumn() + 1;
            $parentCode = 'PAR-' . str_pad((string)$nextId, 4, '0', STR_PAD_LEFT);
        }

        // Validations
        if (empty($fullName) || empty($username) || empty($email) || empty($password)) {
            sendJsonResponse(['success' => false, 'error' => 'Please fill in Full Name, Username, Email, and Password.'], 400);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            sendJsonResponse(['success' => false, 'error' => 'Invalid email address format.'], 400);
        }

        // Check unique username, email, parent_code
        $stmtCheck = $pdo->prepare("SELECT id, username, email, parent_code FROM parents WHERE username = ? OR email = ? OR parent_code = ? LIMIT 1");
        $stmtCheck->execute([$username, $email, $parentCode]);
        $existing = $stmtCheck->fetch();

        if ($existing) {
            if ($existing['username'] === $username) {
                sendJsonResponse(['success' => false, 'error' => 'Username already taken. Please choose another.'], 400);
            }
            if ($existing['email'] === $email) {
                sendJsonResponse(['success' => false, 'error' => 'Email already registered. Please use another.'], 400);
            }
            if ($existing['parent_code'] === $parentCode) {
                sendJsonResponse(['success' => false, 'error' => 'Parent code already in use.'], 400);
            }
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmtInsert = $pdo->prepare("
            INSERT INTO parents (parent_code, username, password_hash, full_name, email, phone, status, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        $stmtInsert->execute([$parentCode, $username, $passwordHash, $fullName, $email, $phone, $status]);
        $newId = (int)$pdo->lastInsertId();

        sendJsonResponse([
            'success' => true,
            'message' => "Parent account for {$fullName} successfully created!",
            'parent_id' => $newId,
            'parent_code' => $parentCode
        ]);
    }

    // 2. UPDATE PARENT
    if ($action === 'update') {
        $id = (int)($payload['id'] ?? 0);
        if ($id <= 0) {
            sendJsonResponse(['success' => false, 'error' => 'Invalid parent ID.'], 400);
        }

        $fullName = trim($payload['full_name'] ?? '');
        $username = strtolower(trim($payload['username'] ?? ''));
        $email = strtolower(trim($payload['email'] ?? ''));
        $phone = trim($payload['phone'] ?? '');
        $status = in_array($payload['status'] ?? '', ['active', 'inactive']) ? $payload['status'] : 'active';
        $newPassword = $payload['password'] ?? '';

        if (empty($fullName) || empty($username) || empty($email)) {
            sendJsonResponse(['success' => false, 'error' => 'Full Name, Username, and Email cannot be empty.'], 400);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            sendJsonResponse(['success' => false, 'error' => 'Invalid email address.'], 400);
        }

        // Check uniqueness excluding current ID
        $stmtCheck = $pdo->prepare("SELECT id, username, email FROM parents WHERE (username = ? OR email = ?) AND id != ? LIMIT 1");
        $stmtCheck->execute([$username, $email, $id]);
        $existing = $stmtCheck->fetch();

        if ($existing) {
            if ($existing['username'] === $username) {
                sendJsonResponse(['success' => false, 'error' => 'Username already taken by another account.'], 400);
            }
            if ($existing['email'] === $email) {
                sendJsonResponse(['success' => false, 'error' => 'Email already registered to another account.'], 400);
            }
        }

        if (!empty($newPassword)) {
            $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmtUpdate = $pdo->prepare("
                UPDATE parents 
                SET full_name = ?, username = ?, email = ?, phone = ?, status = ?, password_hash = ?
                WHERE id = ?
            ");
            $stmtUpdate->execute([$fullName, $username, $email, $phone, $status, $passwordHash, $id]);
        } else {
            $stmtUpdate = $pdo->prepare("
                UPDATE parents 
                SET full_name = ?, username = ?, email = ?, phone = ?, status = ?
                WHERE id = ?
            ");
            $stmtUpdate->execute([$fullName, $username, $email, $phone, $status, $id]);
        }

        sendJsonResponse([
            'success' => true,
            'message' => "Parent account {$fullName} updated successfully."
        ]);
    }

    // 3. DELETE PARENT
    if ($action === 'delete') {
        $id = (int)($payload['id'] ?? 0);
        if ($id <= 0) {
            sendJsonResponse(['success' => false, 'error' => 'Invalid parent ID.'], 400);
        }

        // Count kids linked
        $kidsCount = (int)$pdo->prepare("SELECT COUNT(*) FROM students WHERE parent_id = ?")->execute([$id]) ? $pdo->query("SELECT COUNT(*) FROM students WHERE parent_id = {$id}")->fetchColumn() : 0;

        // Unlink or delete children based on option
        $deleteKids = !empty($payload['delete_kids']);
        if ($deleteKids) {
            $pdo->prepare("DELETE FROM students WHERE parent_id = ?")->execute([$id]);
        } else {
            $pdo->prepare("UPDATE students SET parent_id = NULL WHERE parent_id = ?")->execute([$id]);
        }

        // Delete parent
        $stmtDelete = $pdo->prepare("DELETE FROM parents WHERE id = ?");
        $stmtDelete->execute([$id]);

        sendJsonResponse([
            'success' => true,
            'message' => "Parent account deleted successfully." . ($deleteKids ? " Linked children were also deleted." : " Linked children were unlinked.")
        ]);
    }

    // 4. TOGGLE STATUS (Quick Active / Inactive switch)
    if ($action === 'toggle_status') {
        $id = (int)($payload['id'] ?? 0);
        $stmt = $pdo->prepare("SELECT status FROM parents WHERE id = ?");
        $stmt->execute([$id]);
        $curStatus = $stmt->fetchColumn();

        if (!$curStatus) {
            sendJsonResponse(['success' => false, 'error' => 'Parent not found.'], 404);
        }

        $newStatus = ($curStatus === 'active') ? 'inactive' : 'active';
        $pdo->prepare("UPDATE parents SET status = ? WHERE id = ?")->execute([$newStatus, $id]);

        sendJsonResponse([
            'success' => true,
            'new_status' => $newStatus,
            'message' => "Parent status changed to " . ucfirst($newStatus)
        ]);
    }

    sendJsonResponse(['success' => false, 'error' => 'Invalid action.'], 400);
}

sendJsonResponse(['error' => 'Method not allowed'], 405);
