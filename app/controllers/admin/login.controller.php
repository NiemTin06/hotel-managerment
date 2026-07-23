<?php 

class LoginController extends Controller{
    private $userInput;
    private $pwd;
 public function __construct() {
        parent::__construct(); // gọi initSession()  
    }
    public function index() {
        $data = [
            'title' => 'Đăng nhập Hệ Thống',
            'view_content' => 'pages/auth/login',
            'page_script' => 'login',
            'link' => 'login'
        ];
        $this->view('admin/layout/login_layout', $data);
    }

    public function loginUser(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->userInput = trim($_POST["userInput"] ?? '');
            $this->pwd = trim($_POST["pwd"] ?? '');

            // 1. Kiểm tra trống
            if ($this->emptyInput()){
                echo json_encode(['status' => 'error', 'message' => 'Vui lòng điền đầy đủ thông tin!']);
                exit();
            }

            // 2. Tìm kiếm user thông qua model 'users' (Dùng hàm getUser đã viết sẵn)
            $UserModel = $this->model('users');
            $user = $UserModel->getUser($this->userInput);

            if (!$user){
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Tài khoản hoặc Email không tồn tại!'
                ]);
                exit();
            }

            // 3. Kiểm tra mật khẩu (Khớp với cột USER_PASSWORD trong CSDL)
            if ($this->invalidPassword($user['USER_PASSWORD'])){
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Mật khẩu không chính xác!'
                ]);
                exit();
            }

            // 4. Kiểm tra trạng thái tài khoản (Active / Inactive)
            if ($user['USER_STATUS'] !== 'Active'){
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Tài khoản của bạn đã bị khóa hoặc chưa kích hoạt!'
                ]);
                exit();
            }

            // 5.  CHẶN KHÁCH HÀNG (CUSTOMER): Chỉ cho phép Admin hoặc Staff đăng nhập
            if ($user['USER_ROLE'] !== 'Admin' && $user['USER_ROLE'] !== 'Staff') {
                echo json_encode([
                    'status' => 'error',
                    'message' => ' Tài khoản khách hàng (Customer) không có quyền truy cập trang quản trị!'
                ]);
                exit();
            }

            // 6. Tạo Session đăng nhập (Khớp với các cột USER_ID, USER_USERNAME, USER_ROLE)
            $this->createSession($user);
            echo json_encode([
                'status' => 'success',
                'message' => 'Đăng nhập tài khoản thành công!',
                'redirectUrl' => URLROOT . '/admin/dashboard' // Trả về đường dẫn để JS chuyển trang
            ]);
            exit();
        }
    }

    private function emptyInput(){
        if (empty($this->userInput) || empty($this->pwd)){
            return true;
        }
        return false;
    }

    private function invalidPassword($hashedPwd){
        if (!password_verify($this->pwd, $hashedPwd)){
            return true;
        }
        return false;
    }

    private function createSession($user){
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['user_id'] = $user['USER_ID'];
        $_SESSION['username'] = $user['USER_USERNAME'];
        $_SESSION['user_role'] = $user['USER_ROLE'];
    }

    public function logout() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    session_unset();
    session_destroy();

    header('Location: ' . URLROOT . '/admin/login');
    exit();
}
}