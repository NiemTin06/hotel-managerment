<?php

class ClientAccountController extends Controller {
    public function index() {
        requireCustomerLogin();

        $userId = (int)($_SESSION['user_id'] ?? 0);
        $model = $this->model('clientaccount');
        $account = $model->getAccountByUserId($userId);

        if (!$account) {
            unset(
                $_SESSION['user_id'],
                $_SESSION['username'],
                $_SESSION['user_role'],
                $_SESSION['customer_id'],
                $_SESSION['customer_fullname'],
                $_SESSION['customer_phone'],
                $_SESSION['customer_email']
            );

            $_SESSION['auth_error'] = 'Tài khoản khách hàng không hợp lệ.';
            redirect('/login');
        }

        $data = [
            'title' => 'Tài khoản của tôi',
            'view_content' => 'pages/my-account/index',
            'page_script' => 'my-account',
            'account' => $account,
            'error' => $_SESSION['account_error'] ?? '',
            'success' => $_SESSION['account_success'] ?? '',
            'old' => $_SESSION['account_old'] ?? []
        ];

        unset(
            $_SESSION['account_error'],
            $_SESSION['account_success'],
            $_SESSION['account_old']
        );

        $this->view('client/layout/main_layout', $data);
    }

    public function getHistory() {
        requireCustomerLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->json([
                'success' => false,
                'message' => 'Phương thức không hợp lệ.'
            ], 405);
        }

        $customerId = (int)($_SESSION['customer_id'] ?? 0);

        if ($customerId <= 0) {
            $this->json([
                'success' => false,
                'message' => 'Phiên đăng nhập không hợp lệ.',
                'redirectUrl' => URLROOT . '/login'
            ], 401);
        }

        try {
            $model = $this->model('clientaccount');

            $this->json([
                'success' => true,
                'data' => $model->getBookingsByCustomerId($customerId)
            ]);
        } catch (Throwable $e) {
            $this->json([
                'success' => false,
                'message' => 'Không thể tải lịch sử đặt phòng.'
            ], 500);
        }
    }

    public function update() {
        requireCustomerLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json([
                'success' => false,
                'message' => 'Phương thức không hợp lệ.'
            ], 405);
        }

        $fullname = trim($_POST['fullname'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $newPassword = $_POST['new-password'] ?? '';

        if ($fullname === '') {
            $this->validationError('Vui lòng nhập họ và tên.');
        }
        $fnameLength = mb_strlen($fullname, 'UTF-8');
        if ($fnameLength <= 4) {
            $this->validationError('Họ và tên phải có nhiều hơn 4 ký tự.');
        }

        if ($fnameLength > 100) {
            $this->validationError('Họ và tên không được vượt quá 100 ký tự.');
        }

        if ($phone === '') {
            $this->validationError('Vui lòng nhập số điện thoại.');
        }

        if (!ctype_digit($phone)) {
            $this->validationError('Số điện thoại chỉ được chứa chữ số.');
        }

        if (strlen($phone) !== 10) {
            $this->validationError('Số điện thoại phải có đúng 10 chữ số.');
        }

        if ($phone[0] !== '0') {
            $this->validationError('Số điện thoại phải bắt đầu bằng số 0.');
        }

        $passwordHash = null;

        if ($newPassword !== '') {
            if (strlen($newPassword) < 6) {
                $this->validationError('Mật khẩu mới phải có ít nhất 6 ký tự.');
            }

            $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $userId = (int)($_SESSION['user_id'] ?? 0);

        if ($userId <= 0) {
            $this->json([
                'success' => false,
                'message' => 'Phiên đăng nhập không hợp lệ.',
                'redirectUrl' => URLROOT . '/login'
            ], 401);
        }

        $model = $this->model('clientaccount');

        try {
            $model->updateProfile($userId, $fullname, $phone, $passwordHash);

            $_SESSION['customer_fullname'] = $fullname;
            $_SESSION['customer_phone'] = $phone;

            $this->json([
                'success' => true,
                'message' => $passwordHash !== null
                    ? 'Cập nhật thông tin và mật khẩu thành công.'
                    : 'Cập nhật thông tin thành công.',
                'data' => [
                    'fullname' => $fullname,
                    'phone' => $phone
                ]
            ]);
        } catch (RuntimeException $e) {
            if ($e->getMessage() === 'PHONE_EXISTS') {
                $this->json([
                    'success' => false,
                    'message' => 'Số điện thoại đã được sử dụng.'
                ], 409);
            }

            if ($e->getMessage() === 'INVALID_ACCOUNT') {
                $this->json([
                    'success' => false,
                    'message' => 'Tài khoản khách hàng không hợp lệ.',
                    'redirectUrl' => URLROOT . '/login'
                ], 401);
            }

            $this->json([
                'success' => false,
                'message' => 'Không thể cập nhật thông tin tài khoản.'
            ], 500);
        } catch (Throwable $e) {
            $this->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi cập nhật tài khoản.'
            ], 500);
        }
    }

    private function validationError(string $message): void {
        $this->json([
            'success' => false,
            'message' => $message
        ], 422);
    }

    private function json(array $data, int $statusCode = 200): void {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit();
    }
}
