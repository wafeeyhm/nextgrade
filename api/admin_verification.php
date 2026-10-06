<?php
// NextGrade - System Verification API Endpoint
// Only accessible to authenticated System Administrators

require_once __DIR__ . '/../auth_helper.php';
require_once __DIR__ . '/../admin/verification_engine.php';

// Strict Admin Gate
requireAdmin(false);

header('Content-Type: application/json; charset=utf-8');

$action = $_GET['action'] ?? $_POST['action'] ?? 'run_all';

try {
    $engine = new SystemVerificationEngine($pdo);

    if ($action === 'run_all') {
        $results = $engine->runAllChecks();
        sendJsonResponse([
            'success' => true,
            'data' => $results
        ]);
    } elseif ($action === 'check_db') {
        $res = $engine->checkDatabase();
        sendJsonResponse(['success' => true, 'data' => $res]);
    } elseif ($action === 'check_pages') {
        $res = $engine->checkPages();
        sendJsonResponse(['success' => true, 'data' => $res]);
    } elseif ($action === 'check_images') {
        $res = $engine->checkImages();
        sendJsonResponse(['success' => true, 'data' => $res]);
    } elseif ($action === 'check_credentials') {
        $res = $engine->checkCredentials();
        sendJsonResponse(['success' => true, 'data' => $res]);
    } elseif ($action === 'check_env') {
        $res = $engine->checkProductionEnvironment();
        sendJsonResponse(['success' => true, 'data' => $res]);
    } else {
        sendJsonResponse([
            'success' => false,
            'error' => "Unknown verification action: $action"
        ], 400);
    }
} catch (\Throwable $e) {
    sendJsonResponse([
        'success' => false,
        'error' => 'Verification failed: ' . $e->getMessage()
    ], 200);
}
