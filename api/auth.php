<?php
// NextGrade API - Student Authentication & Profiles
require_once __DIR__ . '/../db.php';

header('Content-Type: application/json; charset=utf-8');
session_start();

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $studentName = $_SESSION['student_name'] ?? $_COOKIE['student_name'] ?? null;
    $studentId = $_SESSION['student_id'] ?? null;
    $avatar = $_SESSION['student_avatar'] ?? 'star_kid';

    if ($studentName) {
        // Fetch or ensure record in DB
        $stmt = $pdo->prepare("SELECT * FROM students WHERE name = ? ORDER BY id DESC LIMIT 1");
        $stmt->execute([$studentName]);
        $row = $stmt->fetch();
        if ($row) {
            $studentId = $row['id'];
            $avatar = $row['avatar'];
            $_SESSION['student_id'] = $studentId;
            $_SESSION['student_avatar'] = $avatar;
        }

        echo json_encode([
            'authenticated' => true,
            'student' => [
                'id' => $studentId,
                'name' => $studentName,
                'avatar' => $avatar
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
    $action = $data['action'] ?? 'login';

    if ($action === 'logout' || isset($_GET['logout'])) {
        unset($_SESSION['student_name'], $_SESSION['student_id'], $_SESSION['student_avatar']);
        setcookie('student_name', '', time() - 3600, '/');
        echo json_encode(['success' => true, 'message' => 'Logged out successfully']);
        exit;
    }

    $name = trim($data['name'] ?? '');
    $avatar = trim($data['avatar'] ?? 'star_kid');

    if (empty($name)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Child name is required to enter NextGrade!']);
        exit;
    }

    // Insert or update student in DB
    $stmt = $pdo->prepare("SELECT * FROM students WHERE name = ? LIMIT 1");
    $stmt->execute([$name]);
    $student = $stmt->fetch();

    if ($student) {
        $studentId = $student['id'];
        $stmtUpdate = $pdo->prepare("UPDATE students SET last_active = NOW(), avatar = ? WHERE id = ?");
        $stmtUpdate->execute([$avatar, $studentId]);
    } else {
        $stmtInsert = $pdo->prepare("INSERT INTO students (name, avatar, created_at, last_active) VALUES (?, ?, NOW(), NOW())");
        $stmtInsert->execute([$name, $avatar]);
        $studentId = $pdo->lastInsertId();
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
