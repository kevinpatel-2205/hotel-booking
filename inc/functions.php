<?php
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

function formatStatusBadge($status) {
    $class = '';
    switch ($status) {
        case 'Pending':
            $class = 'bg-warning text-dark';
            break;
        case 'Confirmed':
            $class = 'bg-success text-white';
            break;
        case 'Cancelled':
            $class = 'bg-danger text-white';
            break;
        default:
            $class = 'bg-secondary text-white';
    }

    return '<span class="badge ' . $class . ' px-3 py-2 rounded-pill fw-semibold">' . strtoupper($status) . '</span>';
}


function isUser() {
    return isset($_SESSION['user_id']) && $_SESSION['role'] === 'user';
}

function isAdmin() {
    return isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin';
}


