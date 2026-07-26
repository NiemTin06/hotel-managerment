<?php

class ClientAccountController extends Controller {
    public function index() {
        if (!isCustomerLoggedIn()) {
            redirect('/login');
        }

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

        $customerId = (int)$account['CUSTOMER_ID'];

        $data = [
            'title' => 'Tài khoản của tôi',
            'view_content' => 'pages/my-account/index',
            'page_style' => 'my-account',
            'account' => $account,
            'bookings' => $model->getBookingsByCustomerId($customerId),
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

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/my-account');
        }

        if (!isCustomerLoggedIn()) {
            redirect('/login');
        }

        $fullname = trim($_POST['fullname'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $newPassword = $_POST['new-password'] ?? '';

        $_SESSION['account_old'] = [
            'fullname' => $fullname,
            'phone' => $phone
        ];

        if ($fullname === '') {
            $_SESSION['account_error'] = 'Vui lòng nhập họ và tên.';
            redirect('/my-account');
        }

        if (mb_strlen($fullname) <= 4) {
            $_SESSION['account_error'] = 'Họ và tên phải có nhiều hơn 4 ký tự.';
            redirect('/my-account');
        }
        
        if (mb_strlen($fullname) > 100) {
            $_SESSION['account_error'] = 'Họ và tên không được vượt quá 100 ký tự.';
            redirect('/my-account');
        }

        if ($phone === '') {
            $_SESSION['account_error'] = 'Vui lòng nhập số điện thoại.';
            redirect('/my-account');
        }

        if (!ctype_digit($phone)) {
            $_SESSION['account_error'] = 'Số điện thoại chỉ được chứa chữ số.';
            redirect('/my-account');
        }

        if (strlen($phone) !== 10) {
            $_SESSION['account_error'] = 'Số điện thoại phải có đúng 10 chữ số.';
            redirect('/my-account');
        }

        if ($phone[0] !== '0') {
            $_SESSION['account_error'] = 'Số điện thoại phải bắt đầu bằng số 0.';
            redirect('/my-account');
        }

        $passwordHash = null;

        if ($newPassword !== '') {
            if (strlen($newPassword) < 6) {
                $_SESSION['account_error'] = 'Mật khẩu mới phải có ít nhất 6 ký tự.';
                redirect('/my-account');
            }

            $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $userId = (int)($_SESSION['user_id'] ?? 0);

        if ($userId <= 0) {
            $_SESSION['auth_error'] = 'Phiên đăng nhập không hợp lệ.';
            redirect('/login');
        }

        $model = $this->model('clientaccount');

        try {
            $model->updateProfile($userId, $fullname, $phone, $passwordHash);

            $_SESSION['customer_fullname'] = $fullname;
            $_SESSION['customer_phone'] = $phone;

            unset($_SESSION['account_old']);

            $_SESSION['account_success'] = $passwordHash !== null
                ? 'Cập nhật thông tin và mật khẩu thành công.'
                : 'Cập nhật thông tin thành công.';
        } catch (RuntimeException $e) {
            if ($e->getMessage() === 'PHONE_EXISTS') {
                $_SESSION['account_error'] = 'Số điện thoại đã được sử dụng.';
            } elseif ($e->getMessage() === 'INVALID_ACCOUNT') {
                $_SESSION['account_error'] = 'Tài khoản khách hàng không hợp lệ.';
            } else {
                $_SESSION['account_error'] = 'Không thể cập nhật thông tin tài khoản.';
            }
        } catch (Throwable $e) {
            $_SESSION['account_error'] = 'Đã xảy ra lỗi khi cập nhật tài khoản.';
        }

        redirect('/my-account');
    }
}
