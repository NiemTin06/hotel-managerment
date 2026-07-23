<?php

// Thời gian timeout phiên (giây) - 60 phút
define('SESSION_TIMEOUT', 60 * 60);
function initSession() {
    if (session_status() === PHP_SESSION_NONE) {

        session_set_cookie_params([
            'lifetime' => SESSION_TIMEOUT,
            'path' => '/',
            'domain' => '',
            'secure' => false,   // đổi true nếu chạy HTTPS
            'httponly' => true,
            'samesite' => 'Lax'
        ]);

        session_start();
    }

    checkSessionTimeout();
}

function checkSessionTimeout() {
    // Chưa login thì khỏi check timeout
    if (empty($_SESSION['user_id'])) {
        return;
    }

    // Mới login, chưa có last_activity -> set luôn
    if (!isset($_SESSION['last_activity'])) {
        $_SESSION['last_activity'] = time();
        return;
    }

    $elapsed = time() - $_SESSION['last_activity'];

    if ($elapsed > SESSION_TIMEOUT) {
        session_unset();
        session_destroy();

        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        if ($isAjax) {
            header('Content-Type: application/json');
            http_response_code(401);
            echo json_encode([
                'status' => 'error',
                'message' => 'Phiên đăng nhập đã hết hạn, vui lòng đăng nhập lại!',
                'redirectUrl' => URLROOT . '/admin/login'
            ]);
        } else {
            header('Location: ' . URLROOT . '/admin/login');
        }
        exit();
    }

    // Còn hạn -> gia hạn thêm (rolling session)
    $_SESSION['last_activity'] = time();
}