<?php
session_start();

function requireUserLogin() {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
        header("Location: ../user/login.php");
        exit;
    }
}

function requireAdminLogin() {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        header("Location: login.php");
        exit;
    }
}
