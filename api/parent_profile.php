<?php
// NextGrade - API: Parent Profile & Credentials Management
require_once __DIR__ . '/../auth_helper.php';
requireParent(false);

header('Content-Type: application/json; charset=utf-8');

$parentId = (int)$_SESSION['parent_id'];
$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'GET') {
        $stmt = $pdo->prepare("SELECT id, parent_code, username, full_name, email, phone, status, created_at, last_login FROM parents WHERE id = ?");
        $stmt->execute([$parentId]);
        $parent = $stmt->fetch();

        if (!$parent) {
            sendJsonResponse(['success' => false, 'error' => 'Parent account not found'], 404);
        }

        sendJsonResponse([
            'success' => true,
            'parent' => $parent
        ]);
    }

    if ($method === 'POST') {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true) ?? $_POST;

        $fullName = trim($data['full_name'] ?? '');
        $email = trim(strtolower($data['email'] ?? ''));
        $username = trim(strtolower($data['username'] ?? ''));
        $phone = trim($data['phone'] ?? '');
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

        // Check if email taken by another parent
        $stmtEmail = $pdo->prepare("SELECT id FROM parents WHERE email = ? AND id != ?");
        $stmtEmail->execute([$email, $parentId]);
        if ($stmtEmail->fetch()) {
            sendJsonResponse(['success' => false, 'error' => 'This email is already in use by another parent account'], 409);
        }

        // Check if username taken by another parent
        $stmtUser = $pdo->prepare("SELECT id FROM parents WHERE username = ? AND id != ?");
        $stmtUser->execute([$username, $parentId]);
        if ($stmtUser->fetch()) {
            sendJsonResponse(['success' => false, 'error' => 'This username is already taken. Please choose another.'], 409);
        }

        // Fetch current password hash
        $stmtCur = $pdo->prepare("SELECT password_hash FROM parents WHERE id = ?");
        $stmtCur->execute([$parentId]);
        $currentHash = $stmtCur->fetchColumn();

        $updatePassword = false;
        $newHash = null;

        if (!empty($newPassword)) {
            if (empty($currentPassword)) {
                sendJsonResponse(['success' => false, 'error' => 'Current password is required to set a new password'], 422);
            }

            if (!password_verify($currentPassword, $currentHash)) {
                sendJsonResponse(['success' => false, 'error' => 'Current password is incorrect'], 403);
            }

            if (strlen($newPassword) < 6) {
                sendJsonResponse(['success' => false, 'error' => 'New password must be at least 6 characters'], 422);
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
                UPDATE parents 
                SET full_name = ?, email = ?, username = ?, phone = ?, password_hash = ?
                WHERE id = ?
            ");
            $stmtUpd->execute([$fullName, $email, $username, $phone, $newHash, $parentId]);
        } else {
            $stmtUpd = $pdo->prepare("
                UPDATE parents 
                SET full_name = ?, email = ?, username = ?, phone = ?
                WHERE id = ?
            ");
            $stmtUpd->execute([$fullName, $email, $username, $phone, $parentId]);
        }

        // Update active session values
        $_SESSION['parent_full_name'] = $fullName;
        $_SESSION['parent_email'] = $email;
        $_SESSION['parent_username'] = $username;
        $_SESSION['parent_phone'] = $phone;

        sendJsonResponse([
            'success' => true,
            'message' => 'Profile updated successfully!' . ($updatePassword ? ' Your password has been changed.' : ''),
            'parent' => [
                'id' => $parentId,
                'full_name' => $fullName,
                'email' => $email,
                'username' => $username,
                'phone' => $phone
            ]
        ]);
    }

    sendJsonResponse(['success' => false, 'error' => 'Method not allowed'], 405);

} catch (Exception $e) {
    sendJsonResponse(['success' => false, 'error' => 'Database error: ' . $e->getMessage()], 500);
}
