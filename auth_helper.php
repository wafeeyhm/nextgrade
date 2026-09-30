<?php
// NextGrade - Authentication Helper & Role Guard
require_once __DIR__ . '/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * System Admin Authentication
 */
function isAdminLoggedIn(): bool {
    return !empty($_SESSION['admin_id']) && !empty($_SESSION['admin_username']);
}

function getAdminUser(): ?array {
    if (!isAdminLoggedIn()) return null;
    return [
        'id' => $_SESSION['admin_id'],
        'username' => $_SESSION['admin_username'],
        'full_name' => $_SESSION['admin_full_name'] ?? 'Administrator',
        'email' => $_SESSION['admin_email'] ?? ''
    ];
}

function requireAdmin(bool $redirect = true) {
    if (!isAdminLoggedIn()) {
        if ($redirect) {
            header('Location: ' . BASE_URL . 'admin/login.php');
            exit;
        } else {
            http_response_code(401);
            echo json_encode(['success' => false, 'error' => 'Admin authentication required']);
            exit;
        }
    }
}

/**
 * Parent Authentication
 */
function isParentLoggedIn(): bool {
    return !empty($_SESSION['parent_id']) && !empty($_SESSION['parent_email']);
}

function getParentUser(): ?array {
    if (!isParentLoggedIn()) return null;
    return [
        'id' => $_SESSION['parent_id'],
        'parent_code' => $_SESSION['parent_code'] ?? '',
        'username' => $_SESSION['parent_username'] ?? '',
        'full_name' => $_SESSION['parent_full_name'] ?? 'Parent',
        'email' => $_SESSION['parent_email'],
        'phone' => $_SESSION['parent_phone'] ?? ''
    ];
}

function requireParent(bool $redirect = true) {
    if (!isParentLoggedIn()) {
        if ($redirect) {
            header('Location: ' . BASE_URL . 'parent_login.php');
            exit;
        } else {
            http_response_code(401);
            echo json_encode(['success' => false, 'error' => 'Parent login required']);
            exit;
        }
    }
}

/**
 * Student (Kid) Authentication
 */
function isStudentLoggedIn(): bool {
    return !empty($_SESSION['student_id']) && !empty($_SESSION['student_name']);
}

function getStudentUser(): ?array {
    if (!isStudentLoggedIn()) return null;
    return [
        'id' => $_SESSION['student_id'],
        'parent_id' => $_SESSION['student_parent_id'] ?? null,
        'name' => $_SESSION['student_name'],
        'username' => $_SESSION['student_username'] ?? '',
        'avatar' => $_SESSION['student_avatar'] ?? 'star_kid',
        'grade_level' => $_SESSION['student_grade'] ?? 'Year 1'
    ];
}

function requireStudent(bool $redirect = true) {
    if (!isStudentLoggedIn()) {
        if ($redirect) {
            header('Location: ' . BASE_URL . 'index.php');
            exit;
        } else {
            http_response_code(401);
            echo json_encode(['success' => false, 'error' => 'Kid login required']);
            exit;
        }
    }
}

/**
 * JSON Response Helper
 */
function sendJsonResponse(array $data, int $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}
