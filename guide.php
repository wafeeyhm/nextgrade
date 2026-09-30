<?php
// NextGrade - Internal Developer & Creator Guide Gateway
require_once __DIR__ . '/auth_helper.php';

// Strict Admin Gate: Developer tools and question guides are strictly internal to the System Admin section
if (!isAdminLoggedIn()) {
    header('Location: ' . BASE_URL . 'admin/login.php');
    exit;
}

// Redirect authenticated System Administrators to the Admin Developer Guide
header('Location: ' . BASE_URL . 'admin/developer_guide.php');
exit;
