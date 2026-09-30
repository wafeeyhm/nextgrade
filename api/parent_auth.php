<?php
// NextGrade API - Parent Authentication
require_once __DIR__ . '/../auth_helper.php';

header('Content-Type: application/json; charset=utf-8');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    if (isParentLoggedIn()) {
        sendJsonResponse([
            'authenticated' => true,
            'parent' => getParentUser()
        ]);
    } else {
        sendJsonResponse([
            'authenticated' => false,
            'parent' => null
        ]);
    }
}

if ($method === 'POST') {
    $rawInput = file_get_contents('php://input');
    $payload = json_decode($rawInput, true) ?? $_POST;
    $action = $payload['action'] ?? 'login';

    if ($action === 'logout') {
        unset(
            $_SESSION['parent_id'], 
            $_SESSION['parent_code'], 
            $_SESSION['parent_username'], 
            $_SESSION['parent_full_name'], 
            $_SESSION['parent_email'], 
            $_SESSION['parent_phone']
        );
        sendJsonResponse(['success' => true, 'message' => 'Logged out successfully.']);
    }

    $loginInput = trim($payload['login'] ?? $payload['email'] ?? $payload['username'] ?? '');
    $password = $payload['password'] ?? '';

    if (empty($loginInput) || empty($password)) {
        sendJsonResponse(['success' => false, 'error' => 'Please enter your username/email and password.'], 400);
    }

    $stmt = $pdo->prepare("SELECT * FROM parents WHERE username = ? OR email = ? OR parent_code = ? LIMIT 1");
    $stmt->execute([$loginInput, $loginInput, $loginInput]);
    $parent = $stmt->fetch();

    if (!$parent || !password_verify($password, $parent['password_hash'])) {
        sendJsonResponse(['success' => false, 'error' => 'Invalid parent credentials. Please try again.'], 401);
    }

    if ($parent['status'] !== 'active') {
        sendJsonResponse(['success' => false, 'error' => 'This parent account has been deactivated. Please contact the administrator.'], 403);
    }

    // Set Parent Session
    $_SESSION['parent_id'] = (int)$parent['id'];
    $_SESSION['parent_code'] = $parent['parent_code'];
    $_SESSION['parent_username'] = $parent['username'];
    $_SESSION['parent_full_name'] = $parent['full_name'];
    $_SESSION['parent_email'] = $parent['email'];
    $_SESSION['parent_phone'] = $parent['phone'];

    // Update last login
    $pdo->prepare("UPDATE parents SET last_login = NOW() WHERE id = ?")->execute([$parent['id']]);

    sendJsonResponse([
        'success' => true,
        'message' => 'Parent login successful.',
        'parent' => [
            'id' => $parent['id'],
            'parent_code' => $parent['parent_code'],
            'username' => $parent['username'],
            'full_name' => $parent['full_name'],
            'email' => $parent['email']
        ]
    ]);
}

sendJsonResponse(['error' => 'Method not allowed'], 405);
