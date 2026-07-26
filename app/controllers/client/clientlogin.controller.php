<?php

class ClientLoginController extends Controller {
    public function index() {
        if (isCustomerLoggedIn()) {
            redirect('/');
        }

        $data = [
            'title' => 'Đăng nhập khách hàng',
            'view_content' => 'pages/auth/login',
            'page_style' => 'auth',
            'page_script' => 'auth',
            'hide_sidebar' => true,
            'error' => $_SESSION['auth_error'] ?? '',
            'success' => $_SESSION['auth_success'] ?? ''
        ];

        unset($_SESSION['auth_error'], $_SESSION['auth_success']);
        $this->view('client/layout/main_layout', $data);
    }
    //  xu lí kiểm tra dữ liệu đăng nhập, trạng thái, quyền...
    public function loginUser() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse('error', 'Phương thức gửi dữ liệu không hợp lệ.');
        }

        $userInput = trim($_POST['userInput'] ?? '');
        $password = $_POST['pwd'] ?? '';

        if ($userInput === '' || $password === '') {
            $this->jsonResponse('error', 'Vui lòng nhập đầy đủ tài khoản và mật khẩu.');
        }
        // check user, pass từ form với dữ liệu lưu trong database
        $authModel = $this->model('clientauth');
        $user = $authModel->getUserByUsernameOrEmail($userInput);

        if (!$user || !password_verify($password, $user['USER_PASSWORD'])) {
            $this->jsonResponse('error', 'Tài khoản hoặc mật khẩu không chính xác.');
        }

        if ($user['USER_STATUS'] !== 'Active') {
            $this->jsonResponse('error', 'Tài khoản đã bị khóa hoặc chưa kích hoạt.');
        }

        if ($user['USER_ROLE'] !== 'Customer') {
            $this->jsonResponse('error', 'Tài khoản này không có quyền đăng nhập vào trang dành cho khách hàng.');
        }

        $customer = $authModel->getCustomerForUser($user);

        if (!$customer) {
            $this->jsonResponse('error', 'Tài khoản chưa có hồ sơ khách hàng.');
        }

        $this->createSession($user, $customer);

        $redirectPath = $_SESSION['redirect_after_login'] ?? '/';
        unset($_SESSION['redirect_after_login']);

        $this->jsonResponse('success', 'Đăng nhập thành công.', URLROOT . $redirectPath);
    }

    // hiện trang đăng ký
    public function register() {
        if (isCustomerLoggedIn()) {
            redirect('/');
        }

        $data = [
            'title' => 'Đăng ký khách hàng',
            'view_content' => 'pages/auth/register',
            'page_style' => 'auth',
            'page_script' => 'auth',
            'hide_sidebar' => true
        ];

        $this->view('client/layout/main_layout', $data);
    }

    public function registerUser() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse('error', 'Phương thức gửi dữ liệu không hợp lệ.');
        }

        $fullname = trim($_POST['fullname'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? ''));
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        if ($fullname === '' || $username === '' || $email === '' || $phone === '' || $password === '' || $passwordConfirm === '') {
            $this->jsonResponse('error', 'Vui lòng nhập đầy đủ thông tin.');
        }

        if (!preg_match('/^[A-Za-z0-9_]{4,50}$/', $username)) {
            $this->jsonResponse('error', 'Tên tài khoản phải có 4-50 ký tự, chỉ gồm chữ, số và dấu gạch dưới.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->jsonResponse('error', 'Email không hợp lệ.');
        }

        if (!preg_match('/^0[0-9]{9}$/', $phone)) {
            $this->jsonResponse('error', 'Số điện thoại phải bắt đầu bằng 0 và có đúng 10 chữ số.');
        }

        if (strlen($password) < 6) {
            $this->jsonResponse('error', 'Mật khẩu phải có ít nhất 6 ký tự.');
        }

        if ($password !== $passwordConfirm) {
            $this->jsonResponse('error', 'Mật khẩu xác nhận không khớp.');
        }

        $authModel = $this->model('clientauth');
        $duplicateField = $authModel->getDuplicateCustomerField($username, $email, $phone);

        if ($duplicateField === 'username') {
            $this->jsonResponse('error', 'Tên tài khoản đã được sử dụng.');
        }

        if ($duplicateField === 'email') {
            $this->jsonResponse('error', 'Email đã được sử dụng.');
        }

        if ($duplicateField === 'phone') {
            $this->jsonResponse('error', 'Số điện thoại đã được sử dụng.');
        }

        try {
            $authModel->registerCustomerAccount([
                'fullname' => $fullname,
                'username' => $username,
                'email' => $email,
                'phone' => $phone,
                'password_hash' => password_hash($password, PASSWORD_DEFAULT)
            ]);
        } catch (Throwable $e) {
            $this->jsonResponse('error', 'Không thể tạo tài khoản. Vui lòng kiểm tra lại thông tin.');
        }

        $_SESSION['auth_success'] = 'Đăng ký thành công. Bạn có thể đăng nhập ngay.';
        $this->jsonResponse('success', 'Đăng ký thành công.', URLROOT . '/login');
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        redirect('/login');
    }

    private function createSession(array $user, $customer) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$user['USER_ID'];
        $_SESSION['username'] = $user['USER_USERNAME'];
        $_SESSION['user_role'] = $user['USER_ROLE'];
        $_SESSION['last_activity'] = time();

        if ($customer) {
            $_SESSION['customer_id'] = (int)$customer['CUSTOMER_ID'];
            $_SESSION['customer_fullname'] = $customer['CUSTOMER_FULLNAME'];
            $_SESSION['customer_email'] = $customer['CUSTOMER_EMAIL'] ?? $user['USER_EMAIL'];
            $_SESSION['customer_phone'] = $customer['CUSTOMER_PHONE'] ?? $user['USER_PHONE'];
        }
    }

    private function jsonResponse($status, $message, $redirectUrl = null) {
        header('Content-Type: application/json; charset=UTF-8');

        $response = ['status' => $status, 'message' => $message];

        if ($redirectUrl !== null) {
            $response['redirectUrl'] = $redirectUrl;
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit();
    }
}
