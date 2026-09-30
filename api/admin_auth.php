<?php
// NextGrade API - Admin Authentication
require_once __DIR__ . '/../auth_helper.php';

header('Content-Type: application/json; charset=utf-8');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    if (isAdminLoggedIn()) {
        sendJsonResponse([
            'authenticated' => true,
            'admin' => getAdminUser()
        ]);
    } else {
        sendJsonResponse([
            'authenticated' => false,
            'admin' => null
        ]);
    }
}

if ($method === 'POST') {
    $rawInput = file_get_contents('php://input');
    $payload = json_decode($rawInput, true) ?? $_POST;
    $action = $payload['action'] ?? 'login';

    if ($action === 'logout') {
        unset($_SESSION['admin_id'], $_SESSION['admin_username'], $_SESSION['admin_full_name'], $_SESSION['admin_email']);
        sendJsonResponse(['success' => true, 'message' => 'Logged out of admin portal.']);
    }

    $username = trim($payload['username'] ?? '');
    $password = $payload['password'] ?? '';

    if (empty($username) || empty($password)) {
        sendJsonResponse(['success' => false, 'error' => 'Please provide both username and password.'], 400);
    }

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ? OR email = ? LIMIT 1");
    $stmt->execute([$username, $username]);
    $admin = $stmt->fetch();

    if (!$admin || !password_verify($password, $admin['password_hash'])) {
        sendJsonResponse(['success' => false, 'error' => 'Invalid admin credentials.'], 401);
    }

    // Set Admin Session
    $_SESSION['admin_id'] = (int)$admin['id'];
    $_SESSION['admin_username'] = $admin['username'];
    $_SESSION['admin_full_name'] = $admin['full_name'];
    $_SESSION['admin_email'] = $admin['email'];

    // Update last login
    $pdo->prepare("UPDATE admins SET last_login = NOW() WHERE id = ?")->execute([$admin['id']]);

    sendJsonResponse([
        'success' => true,
        'message' => 'Admin authentication successful.',
        'admin' => [
            'id' => $admin['id'],
            'username' => $admin['username'],
            'full_name' => $admin['full_name'],
            'email' => $admin['email']
        ]
    ]);
}

sendJsonResponse(['error' => 'Method not allowed'], 405);
