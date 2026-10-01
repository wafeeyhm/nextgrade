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
// For local XAMPP: host=127.0.0.1, db=nextgrade_db, user=root, pass=''
// For cPanel / Web Hosting: update $db, $user, and $pass according to your hosting MySQL database details
$host = '127.0.0.1';
$db   = 'nextgrade_db';
$user = 'root';
$pass = ''; // Set your hosting MySQL database password here

$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}