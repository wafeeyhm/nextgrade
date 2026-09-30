<?php
// NextGrade - API: System Admin Profile & Credentials Management
require_once __DIR__ . '/../auth_helper.php';
requireAdmin(false);

header('Content-Type: application/json; charset=utf-8');

$adminId = (int)$_SESSION['admin_id'];
$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'GET') {
        $stmt = $pdo->prepare("SELECT id, username, full_name, email, created_at, last_login FROM admins WHERE id = ?");
        $stmt->execute([$adminId]);
        $admin = $stmt->fetch();

        if (!$admin) {
            sendJsonResponse(['success' => false, 'error' => 'Admin account not found'], 404);
        }

        sendJsonResponse([
            'success' => true,
            'admin' => $admin
        ]);
    }

    if ($method === 'POST') {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true) ?? $_POST;

        $fullName = trim($data['full_name'] ?? '');
        $email = trim(strtolower($data['email'] ?? ''));
        $username = trim(strtolower($data['username'] ?? ''));
        $currentPassword = $data['current_password'] ?? '';
        $newPassword = $data['new_password'] ?? '';
        $confirmPassword = $data['confirm_password'] ?? '';

        if (empty($fullName)) {
            sendJsonResponse(['success' => false, 'error' => 'Full name is required'], 422);
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            sendJsonResponse(['success' => false, 'error' => 'A valid email address is required'], 422);
        }

        if (empty($username) || strlen($username) < 3) {
            sendJsonResponse(['success' => false, 'error' => 'Username must be at least 3 characters'], 422);
        }

        if (!preg_match('/^[a-z0-9_.-]+$/i', $username)) {
            sendJsonResponse(['success' => false, 'error' => 'Username can only contain letters, numbers, hyphens, and underscores'], 422);
        }

        // Check if email taken by another admin
        $stmtEmail = $pdo->prepare("SELECT id FROM admins WHERE email = ? AND id != ?");
        $stmtEmail->execute([$email, $adminId]);
        if ($stmtEmail->fetch()) {
            sendJsonResponse(['success' => false, 'error' => 'This email is already in use by another administrator'], 409);
        }

        // Check if username taken by another admin
        $stmtUser = $pdo->prepare("SELECT id FROM admins WHERE username = ? AND id != ?");
        $stmtUser->execute([$username, $adminId]);
        if ($stmtUser->fetch()) {
            sendJsonResponse(['success' => false, 'error' => 'This username is already taken by another administrator'], 409);
        }

        // Fetch current admin password hash
        $stmtCur = $pdo->prepare("SELECT password_hash FROM admins WHERE id = ?");
        $stmtCur->execute([$adminId]);
        $currentHash = $stmtCur->fetchColumn();

        $updatePassword = false;
        $newHash = null;

        if (!empty($newPassword)) {
            if (empty($currentPassword)) {
                sendJsonResponse(['success' => false, 'error' => 'Current password is required to change administrator password'], 422);
            }

            if (!password_verify($currentPassword, $currentHash)) {
                sendJsonResponse(['success' => false, 'error' => 'Current password does not match'], 403);
            }

            if (strlen($newPassword) < 6) {
                sendJsonResponse(['success' => false, 'error' => 'New password must be at least 6 characters long'], 422);
            }

            if ($newPassword !== $confirmPassword) {
                sendJsonResponse(['success' => false, 'error' => 'New password and confirmation do not match'], 422);
            }

            $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $updatePassword = true;
        }

        // Update database
        if ($updatePassword) {
            $stmtUpd = $pdo->prepare("
                UPDATE admins 
                SET full_name = ?, email = ?, username = ?, password_hash = ?
                WHERE id = ?
            ");
            $stmtUpd->execute([$fullName, $email, $username, $newHash, $adminId]);
        } else {
            $stmtUpd = $pdo->prepare("
                UPDATE admins 
                SET full_name = ?, email = ?, username = ?
                WHERE id = ?
            ");
            $stmtUpd->execute([$fullName, $email, $username, $adminId]);
        }

        // Update active session values
        $_SESSION['admin_full_name'] = $fullName;
        $_SESSION['admin_username'] = $username;
        $_SESSION['admin_email'] = $email;

        sendJsonResponse([
            'success' => true,
            'message' => 'Administrator profile updated successfully!' . ($updatePassword ? ' Password updated.' : ''),
            'admin' => [
                'id' => $adminId,
                'full_name' => $fullName,
                'email' => $email,
                'username' => $username
            ]
        ]);
    }

    sendJsonResponse(['success' => false, 'error' => 'Method not allowed'], 405);

} catch (Exception $e) {
    sendJsonResponse(['success' => false, 'error' => 'Database error: ' . $e->getMessage()], 500);
}
