<?php
// auth.php
session_start();
function is_logged_in() {
    return isset($_SESSION['user_id']);
}
function user_role() {
    return $_SESSION['role'] ?? null;
}
function require_login() {
    if (!is_logged_in()) {
        header('Location: /website/login.php');
        exit();
    }
}
function require_role($role) {
    if (!is_logged_in() || user_role() !== $role) {
        header('Location: /website/login.php');
        exit();
    }
}
function require_roles($roles) {
    if (!is_logged_in() || !in_array(user_role(), $roles)) {
        header('Location: /website/login.php');
        exit();
    }
}
?>
