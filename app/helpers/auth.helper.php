<?php

function isLoggedIn() {
    return !empty($_SESSION['user_id']);
}

function currentUserId() {
    return $_SESSION['user_id'] ?? null;
}

function currentUsername() {
    return $_SESSION['username'] ?? null;
}

function currentUserRole() {
    return $_SESSION['user_role'] ?? null;
}

function isAjaxRequest() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

function requireLogin() {
    if (!isLoggedIn()) {
        if (isAjaxRequest()) {
            header('Content-Type: application/json');
            http_response_code(401);
            echo json_encode([
                'status' => 'error',
                'message' => 'Bạn cần đăng nhập để tiếp tục!',
                'redirectUrl' => URLROOT . '/admin/login'
            ]);
        } else {
            header('Location: ' . URLROOT . '/admin/login');
        }
        exit();
    }
}

function requireRole($role) {
    requireLogin();

    if (currentUserRole() !== $role) {
        if (isAjaxRequest()) {
            header('Content-Type: application/json');
            http_response_code(403);
            echo json_encode([
                'status' => 'error',
                'message' => 'Bạn không có quyền truy cập chức năng này!'
            ]);
        } else {
            http_response_code(403);
            echo "Bạn không có quyền truy cập trang này!";
        }
        exit();
    }
}