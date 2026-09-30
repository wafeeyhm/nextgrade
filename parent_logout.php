<?php
require_once __DIR__ . '/auth_helper.php';

unset(
    $_SESSION['parent_id'], 
    $_SESSION['parent_code'], 
    $_SESSION['parent_username'], 
    $_SESSION['parent_full_name'], 
    $_SESSION['parent_email'], 
    $_SESSION['parent_phone']
);
header('Location: ' . BASE_URL . 'parent_login.php');
exit;
