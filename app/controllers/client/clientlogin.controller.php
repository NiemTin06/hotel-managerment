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
            'hide_sidebar' => true,
            'error' => $_SESSION['auth_error'] ?? '',
            'success' => $_SESSION['auth_success'] ?? '',
            'old' => $_SESSION['auth_old'] ?? []
        ];

        unset($_SESSION['auth_error'], $_SESSION['auth_success'], $_SESSION['auth_old']);
        $this->view('client/layout/main_layout', $data);
    }
    //  xu lí kiểm tra dữ liệu đăng nhập, trạng thái, quyền...
    public function loginUser() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/login');
        }
        // lấy user, pass từ form
        $userInput = trim($_POST['userInput'] ?? '');
        $password = $_POST['pwd'] ?? '';

        if ($userInput === '' || $password === '') {
            $this->errorMessage('/login', 'Vui lòng nhập đầy đủ tài khoản và mật khẩu.', ['userInput' => $userInput]);
        }
        // check user, pass từ form với dữ liệu lưu trong database
        $authModel = $this->model('clientauth');
        $user = $authModel->getUserByUsernameOrEmail($userInput);

        if (!$user || !password_verify($password, $user['USER_PASSWORD'])) {
            $this->errorMessage('/login', 'Tài khoản hoặc mật khẩu không chính xác.', ['userInput' => $userInput]);
        }

        if ($user['USER_STATUS'] !== 'Active') {
            $this->errorMessage('/login', 'Tài khoản đã bị khóa hoặc chưa kích hoạt.', ['userInput' => $userInput]);
        }

        if ($user['USER_ROLE'] !== 'Customer') {
            $this->errorMessage('/login', 'Tài khoản này không có quyền đăng nhập vào trang dành cho khách hàng.', ['userInput' => $userInput]);
        }

        $customer = $authModel->getCustomerForUser($user);

        if (!$customer) {
            $this->errorMessage('/login', 'Tài khoản chưa có hồ sơ khách hàng. Vui lòng đăng ký lại tài khoản khách hàng.', ['userInput' => $userInput]);
        }

        $this->createSession($user, $customer);

        $redirectPath = $_SESSION['redirect_after_login'] ?? '/';
        unset($_SESSION['redirect_after_login']);

        if (!is_string($redirectPath) || strpos($redirectPath, '/bookings') !== 0) {
            $redirectPath = '/';
        }
        redirect($redirectPath);
    }
    // hiện trang đăng ký
    public function register() {
        if (isCustomerLoggedIn()) redirect('/');

        $data = [
            'title' => 'Đăng ký khách hàng',
            'view_content' => 'pages/auth/register',
            'page_style' => 'auth',
            'hide_sidebar' => true,
            'error' => $_SESSION['auth_error'] ?? '',
            'old' => $_SESSION['auth_old'] ?? []
        ];

        unset($_SESSION['auth_error'], $_SESSION['auth_old']);
        $this->view('client/layout/main_layout', $data);
    }

    public function registerUser() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('/register');

        $fullname = trim($_POST['fullname'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? ''));
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';
        // lưu thông tin cũ khi đăng ký lỗi
        $old = [ 
            'fullname' => $fullname,
            'username' => $username,
            'email' => $email,
            'phone' => $phone
            ];
        // thông báo lỗi
        if ($fullname === '' || $username === '' || $email === '' || $phone === '' || $password === '' || $passwordConfirm === '') {
            $this->errorMessage('/register', 'Vui lòng nhập đầy đủ thông tin.', $old);
        }

        $fnameLength = mb_strlen($fullname, 'UTF-8');

        if ($fnameLength <= 4) {
            $this->errorMessage('/register', 'Họ và tên phải có nhiều hơn 4 ký tự.', $old);
        }

        if ($fnameLength > 100) {
            $this->errorMessage('/register', 'Họ và tên không được vượt quá 100 ký tự.', $old);
        }

        if (!preg_match('/^[A-Za-z0-9_]{4,50}$/', $username)) {
            $this->errorMessage('/register', 'Tên tài khoản phải có 4-50 ký tự, chỉ gồm chữ, số và dấu gạch dưới.', $old);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errorMessage('/register', 'Email không hợp lệ.', $old);
        }

        if (!preg_match('/^0[0-9]{9}$/', $phone)) {
            $this->errorMessage('/register', 'Số điện thoại phải bắt đầu bằng 0 và có đúng 10 chữ số.', $old);
        }

        if (strlen($password) < 6) {
            $this->errorMessage('/register', 'Mật khẩu phải có ít nhất 6 ký tự.', $old);
        }

        if ($password !== $passwordConfirm) {
            $this->errorMessage('/register', 'Mật khẩu xác nhận không khớp.', $old);
        }

        $authModel = $this->model('clientauth');
        $duplicateField = $authModel->getDuplicateCustomerField($username, $email, $phone);

        if ($duplicateField === 'username') {
            $this->errorMessage('/register', 'Tên tài khoản đã được sử dụng.', $old);
        }

        if ($duplicateField === 'email') {
            $this->errorMessage('/register', 'Email đã được sử dụng.', $old);
        }

        if ($duplicateField === 'phone') {
            $this->errorMessage('/register', 'Số điện thoại đã được sử dụng.', $old);
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
            $this->errorMessage('/register', 'Không thể tạo tài khoản. Vui lòng kiểm tra lại thông tin.', $old);
        }

        unset($_SESSION['auth_error'], $_SESSION['auth_old']);
        $_SESSION['auth_success'] = 'Đăng ký thành công. Bạn có thể đăng nhập ngay.';
        redirect('/login');
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        redirect('/login');
    }

    private function createSession(array $user, array $customer): void {
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$user['USER_ID'];
        $_SESSION['username'] = $user['USER_USERNAME'];
        $_SESSION['user_role'] = $user['USER_ROLE'];
        $_SESSION['last_activity'] = time();
        $_SESSION['customer_id'] = (int)$customer['CUSTOMER_ID'];
        $_SESSION['customer_fullname'] = $customer['CUSTOMER_FULLNAME'];
        $_SESSION['customer_email'] = $customer['CUSTOMER_EMAIL'] ?? $user['USER_EMAIL'];
        $_SESSION['customer_phone'] = $customer['CUSTOMER_PHONE'] ?? $user['USER_PHONE'];
        $_SESSION['customer_cccd'] = $customer['CUSTOMER_CCCD'] ?? '';
    }

    private function errorMessage(string $path, string $message, array $old = []): void {
        $_SESSION['auth_error'] = $message;
        $_SESSION['auth_old'] = $old;
        redirect($path);
    }
}
