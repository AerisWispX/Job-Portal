<?php
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function redirect_if_not_logged_in() {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit();
    }
}

function get_user_type() {
    return $_SESSION['user_type'] ?? null;
}

function format_date($date) {
    return date('F j, Y', strtotime($date));
}