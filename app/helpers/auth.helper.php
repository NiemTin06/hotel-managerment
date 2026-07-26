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

function isCustomerLoggedIn(): bool {
    return !empty($_SESSION['user_id'])
        && ($_SESSION['user_role'] ?? '') === 'Customer';
}

function requireCustomerLogin(string $redirectAfterLogin = '/'): void {
    if (isCustomerLoggedIn() && !empty($_SESSION['customer_id'])) {
        return;
    }

    if (isAjaxRequest()) {
        header('Content-Type: application/json; charset=UTF-8');
        http_response_code(401);

        echo json_encode([
            'status' => 'error',
            'message' => 'Vui lòng đăng nhập để tiếp tục.',
            'redirectUrl' => URLROOT . '/login'
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }

    $_SESSION['redirect_after_login'] = $redirectAfterLogin;
    $_SESSION['auth_error'] = 'Vui lòng đăng nhập để tiếp tục.';

    redirect('/login');
}

function redirect(string $path = '/'): void {
    $url = URLROOT . '/' . ltrim($path, '/');

    header('Location: ' . $url);
    exit();
}