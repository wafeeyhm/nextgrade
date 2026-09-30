<?php
require_once __DIR__ . '/../auth_helper.php';

unset($_SESSION['admin_id'], $_SESSION['admin_username'], $_SESSION['admin_full_name'], $_SESSION['admin_email']);
header('Location: ' . BASE_URL . 'admin/login.php');
exit;
