<?php
// Web Hosting & Environment Configuration:
// 1. BASE_URL: Automatically detects if running in root (/) or subdirectory (/nextgrade/)
if (!defined('BASE_URL')) {
    if (php_sapi_name() === 'cli' || empty($_SERVER['SCRIPT_NAME'])) {
        define('BASE_URL', '/nextgrade/');
    } else {
        $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        $scriptDir = preg_replace('#/(admin|api|scripts)$#', '', $scriptDir);
        $base = rtrim($scriptDir, '/');
        define('BASE_URL', ($base === '' || $base === '.') ? '/' : $base . '/');
    }
}

// 2. Database Credentials:
// For cPanel / Hostinger Web Hosting:
$host = 'localhost'; // 'localhost' connects via unix socket on Linux (Hostinger/cPanel standard)
$db = 'nextgrade_db';
$user = 'root';
$pass = ''; // Hosting MySQL database password

$charset = 'utf8mb4';
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // If running in local development environment, fallback to standard local XAMPP MySQL
    $isLocal = (php_sapi_name() === 'cli')
        || in_array($_SERVER['SERVER_NAME'] ?? '', ['localhost', '127.0.0.1', '::1'])
        || (isset($_SERVER['HTTP_HOST']) && str_starts_with($_SERVER['HTTP_HOST'], 'localhost'));

    if ($isLocal) {
        try {
            $pdo = new PDO("mysql:host=127.0.0.1;dbname=nextgrade_db;charset=$charset", "root", "", $options);
        } catch (\PDOException $e2) {
            die("Database connection failed: " . $e->getMessage());
        }
    } else {
        // Try fallback to 127.0.0.1 in case hosting requires TCP instead of socket
        try {
            $pdo = new PDO("mysql:host=127.0.0.1;dbname=$db;charset=$charset", $user, $pass, $options);
        } catch (\PDOException $e3) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
}
