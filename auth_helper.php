<?php
// NextGrade - Authentication Helper & Role Guard
require_once __DIR__ . '/db.php';

if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    @session_start();
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
    global $pdo;
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

    // Verify parent status in database
    if (isset($pdo) && !empty($_SESSION['parent_id'])) {
        $stmtStatus = $pdo->prepare("SELECT status FROM parents WHERE id = ?");
        $stmtStatus->execute([(int)$_SESSION['parent_id']]);
        $status = $stmtStatus->fetchColumn();
        if ($status !== 'active') {
            unset(
                $_SESSION['parent_id'], 
                $_SESSION['parent_code'], 
                $_SESSION['parent_username'], 
                $_SESSION['parent_full_name'], 
                $_SESSION['parent_email'], 
                $_SESSION['parent_phone']
            );
            if ($redirect) {
                header('Location: ' . BASE_URL . 'parent_login.php?error=deactivated');
                exit;
            } else {
                http_response_code(403);
                echo json_encode(['success' => false, 'error' => 'This parent account has been deactivated.']);
                exit;
            }
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
    global $pdo;
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

    // Verify student and linked parent status in database
    if (isset($pdo) && !empty($_SESSION['student_id'])) {
        $stmtChk = $pdo->prepare("
            SELECT s.status as student_status, p.status as parent_status 
            FROM students s 
            LEFT JOIN parents p ON p.id = s.parent_id 
            WHERE s.id = ?
        ");
        $stmtChk->execute([(int)$_SESSION['student_id']]);
        $chk = $stmtChk->fetch();

        if (!$chk || $chk['student_status'] !== 'active' || ($chk['parent_status'] !== null && $chk['parent_status'] !== 'active')) {
            unset(
                $_SESSION['student_name'], 
                $_SESSION['student_id'], 
                $_SESSION['student_avatar'], 
                $_SESSION['student_username'], 
                $_SESSION['student_parent_id'],
                $_SESSION['student_grade']
            );
            setcookie('student_name', '', time() - 3600, '/');
            if ($redirect) {
                header('Location: ' . BASE_URL . 'index.php?error=deactivated');
                exit;
            } else {
                http_response_code(403);
                echo json_encode(['success' => false, 'error' => 'Student or parent account is deactivated.']);
                exit;
            }
        }
    }
}

/**
 * Grade Level & Curriculum Checks
 * - Kindergarten 3 (KG3): Early Learning (Ages 5 to 6)
 * - Year 6 (PSR): Brunei Penilaian Sekolah Rendah Exam Preparation (Ages 11 to 12)
 */
function isGradeKG3(?string $grade): bool {
    if (!$grade) return false;
    $g = strtolower(trim($grade));
    return ($g === 'kg3' || str_contains($g, 'kg3') || str_contains($g, 'kindergarten 3') || str_contains($g, 'kindergarden 3'));
}

function isGradeYear6(?string $grade): bool {
    if (!$grade) return false;
    $g = strtolower(trim($grade));
    return (str_contains($g, 'year 6') || str_contains($g, 'psr') || str_contains($g, 'tahun 6') || $g === 'y6');
}

function getCurriculumCategory(?string $grade): string {
    if (isGradeYear6($grade)) {
        return 'Year 6 (PSR)';
    }
    if (isGradeKG3($grade)) {
        return 'Kindergarten 3 (KG3)';
    }
    return 'Other';
}

function isCurrentStudentKG3(): bool {
    if (!isStudentLoggedIn()) return true;
    $grade = $_SESSION['student_grade'] ?? null;
    return isGradeKG3($grade);
}

function isCurrentStudentYear6(): bool {
    if (!isStudentLoggedIn()) return false;
    $grade = $_SESSION['student_grade'] ?? null;
    return isGradeYear6($grade);
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

