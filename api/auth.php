<?php
// NextGrade API - Student Authentication & Simple Kid Access
require_once __DIR__ . '/../db.php';

header('Content-Type: application/json; charset=utf-8');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $action = $_GET['action'] ?? 'status';

    // 1. Get Available Kids for Visual Tablet Picker
    if ($action === 'kids_list') {
        $parentCode = trim($_GET['parent_code'] ?? '');
        $sql = "
            SELECT s.id, s.name, s.username, s.avatar, s.grade_level, p.parent_code, p.full_name as parent_name
            FROM students s
            LEFT JOIN parents p ON p.id = s.parent_id
            WHERE s.status = 'active'
        ";
        $params = [];
        if (!empty($parentCode)) {
            $sql .= " AND p.parent_code = ?";
            $params[] = $parentCode;
        }
        $sql .= " ORDER BY s.id ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $kids = $stmt->fetchAll();

        echo json_encode([
            'success' => true,
            'kids' => $kids
        ]);
        exit;
    }

    // 2. Default: Current Session Status
    $studentName = $_SESSION['student_name'] ?? $_COOKIE['student_name'] ?? null;
    $studentId = $_SESSION['student_id'] ?? null;
    $avatar = $_SESSION['student_avatar'] ?? 'star_kid';

    if ($studentName) {
        $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ? OR name = ? ORDER BY id DESC LIMIT 1");
        $stmt->execute([$studentId ?? 0, $studentName]);
        $row = $stmt->fetch();
        if ($row) {
            $studentId = (int)$row['id'];
            $avatar = $row['avatar'];
            $_SESSION['student_id'] = $studentId;
            $_SESSION['student_avatar'] = $avatar;
            $_SESSION['student_name'] = $row['name'];
            $_SESSION['student_username'] = $row['username'];
            $_SESSION['student_parent_id'] = $row['parent_id'];
            $_SESSION['student_grade'] = $row['grade_level'];
        }

        echo json_encode([
            'authenticated' => true,
            'student' => [
                'id' => $studentId,
                'name' => $row['name'] ?? $studentName,
                'username' => $row['username'] ?? '',
                'avatar' => $avatar,
                'grade_level' => $row['grade_level'] ?? 'Year 1',
                'parent_id' => $row['parent_id'] ?? null
            ]
        ]);
    } else {
        echo json_encode([
            'authenticated' => false,
            'student' => null
        ]);
    }
    exit;
}

if ($method === 'POST') {
    $rawInput = file_get_contents('php://input');
    $data = json_decode($rawInput, true) ?? $_POST;
    $action = $data['action'] ?? 'kid_login';

    if ($action === 'logout' || isset($_GET['logout'])) {
        unset(
            $_SESSION['student_name'], 
            $_SESSION['student_id'], 
            $_SESSION['student_avatar'], 
            $_SESSION['student_username'], 
            $_SESSION['student_parent_id'],
            $_SESSION['student_grade']
        );
        setcookie('student_name', '', time() - 3600, '/');
        echo json_encode(['success' => true, 'message' => 'Logged out successfully']);
        exit;
    }

    // 1. SIMPLE KID LOGIN (Username + PIN or ID + PIN)
    if ($action === 'kid_login') {
        $identifier = trim($data['username'] ?? $data['name'] ?? '');
        $pinCode = trim($data['pin_code'] ?? $data['pin'] ?? '');
        $studentId = !empty($data['student_id']) ? (int)$data['student_id'] : 0;

        if (empty($identifier) && $studentId <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Please choose or type your name!']);
            exit;
        }

        if ($studentId > 0) {
            $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ? AND status = 'active' LIMIT 1");
            $stmt->execute([$studentId]);
        } else {
            $stmt = $pdo->prepare("SELECT * FROM students WHERE (username = ? OR name = ?) AND status = 'active' LIMIT 1");
            $stmt->execute([$identifier, $identifier]);
        }

        $student = $stmt->fetch();

        if (!$student) {
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'Child profile not found. Please ask your parent to add you!']);
            exit;
        }

        // Verify PIN if child has one set
        if (!empty($student['pin_code'])) {
            if ($pinCode !== $student['pin_code']) {
                http_response_code(401);
                echo json_encode(['success' => false, 'error' => 'Oops! That PIN code does not match. Try again!']);
                exit;
            }
        }

        // Set session
        $_SESSION['student_id'] = (int)$student['id'];
        $_SESSION['student_name'] = $student['name'];
        $_SESSION['student_username'] = $student['username'];
        $_SESSION['student_avatar'] = $student['avatar'];
        $_SESSION['student_grade'] = $student['grade_level'];
        $_SESSION['student_parent_id'] = $student['parent_id'];
        setcookie('student_name', $student['name'], time() + (86400 * 365), '/');

        // Update last active
        $pdo->prepare("UPDATE students SET last_active = NOW() WHERE id = ?")->execute([$student['id']]);

        echo json_encode([
            'success' => true,
            'message' => "Welcome back, {$student['name']}! 🌟",
            'student' => [
                'id' => $student['id'],
                'name' => $student['name'],
                'username' => $student['username'],
                'avatar' => $student['avatar'],
                'grade_level' => $student['grade_level']
            ]
        ]);
        exit;
    }

    // 2. QUICK NAME ONBOARDING (Legacy / Instant entry)
    $name = trim($data['name'] ?? '');
    $avatar = trim($data['avatar'] ?? 'star_kid');

    if (empty($name)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Child name is required to enter NextGrade!']);
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM students WHERE name = ? LIMIT 1");
    $stmt->execute([$name]);
    $student = $stmt->fetch();

    if ($student) {
        $studentId = (int)$student['id'];
        $stmtUpdate = $pdo->prepare("UPDATE students SET last_active = NOW(), avatar = ? WHERE id = ?");
        $stmtUpdate->execute([$avatar, $studentId]);
    } else {
        $baseUser = preg_replace('/[^a-z0-9]/', '', strtolower($name)) . rand(10, 99);
        $stmtInsert = $pdo->prepare("INSERT INTO students (name, username, pin_code, avatar, created_at, last_active) VALUES (?, ?, '1234', ?, NOW(), NOW())");
        $stmtInsert->execute([$name, $baseUser, $avatar]);
        $studentId = (int)$pdo->lastInsertId();
    }

    $_SESSION['student_name'] = $name;
    $_SESSION['student_id'] = $studentId;
    $_SESSION['student_avatar'] = $avatar;
    setcookie('student_name', $name, time() + (86400 * 365), '/');

    echo json_encode([
        'success' => true,
        'student' => [
            'id' => $studentId,
            'name' => $name,
            'avatar' => $avatar
        ]
    ]);
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
