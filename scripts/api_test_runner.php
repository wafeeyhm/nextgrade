<?php
// NextGrade - API Subprocess Test Runner
// Safely executes an API file with isolated GET, POST, and SESSION environments.

error_reporting(0);

if ($argc < 2) {
    echo json_encode(['error' => 'Missing runner configuration']);
    exit(1);
}

$arg = $argv[1];
if (file_exists($arg)) {
    $input = json_decode(file_get_contents($arg), true);
} else {
    $input = json_decode($arg, true);
}

if (!$input || empty($input['file'])) {
    echo json_encode(['error' => 'Invalid runner payload']);
    exit(1);
}

$_GET = $input['get'] ?? [];
$_POST = $input['post'] ?? [];
$_SERVER['REQUEST_METHOD'] = $input['method'] ?? 'GET';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION = $input['session'] ?? [];

ob_start();
require $input['file'];
$output = ob_get_clean();

echo $output;
