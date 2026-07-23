<?php
class UserController extends Controller {
     public function __construct() {
        parent::__construct(); // gọi initSession()
        requireLogin();     
        requireRole('Admin');
    }
    // 1. Load giao diện trang quản lý
    public function index() {
        $data = [
            'title' => 'Quản lý Tài khoản Nhân viên',
            'view_content' => 'pages/user/index',
            'page_script' => 'user',
            'link' => 'users'
        ];
        $this->view('admin/layout/main_layout', $data);
    }

    // 2. API Lấy danh sách (Có tìm kiếm & phân trang)
public function getUserData() {
        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        $filter = [
            'search'  => trim($_GET['search'] ?? ''),
            'status'  => trim($_GET['status'] ?? ''),
            'sort_by' => trim($_GET['sort-by'] ?? ''),
            'page'    => $page, 
            'limit'   => $limit, 
            'offset'  => $offset
        ];

        $model = $this->model('users');
        $users = $model->getAllUsers($filter);
        $totalItem = $model->count($filter);
        $totalPage = ceil($totalItem / $limit);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            "record" => $users,
            "pagination" => ["page" => $page, "limit" => $limit, "totalItem" => $totalItem, "totalPage" => $totalPage]
        ]);
        exit();
    }

    // 3. API Thêm tài khoản mới
    public function create() {
        $data = [
            'username' => trim($_POST['username'] ?? ''),
            'email'    => trim($_POST['email'] ?? ''),
            'password' => trim($_POST['password'] ?? ''),
            'phone'    => trim($_POST['phone'] ?? ''),
            'role'     => trim($_POST['role'] ?? 'Staff'),
            'status'   => trim($_POST['status'] ?? 'Active')
        ];

        if (empty($data['username']) || empty($data['email'])) {
            echo json_encode(["success" => false, "message" => "Vui lòng nhập đầy đủ Tài khoản và Email!"]);
            exit();
        }

        $model = $this->model("users"); // Đã fix thành 'users'
        $result = $model->addUser($data);
        echo json_encode([
            "success" => (bool)$result,
            "message" => $result ? "Tạo tài khoản thành công! (Mật khẩu mặc định: 123456 nếu để trống)" : "Tên tài khoản hoặc Email đã tồn tại!"
        ]);
        exit();
    }

    public function getOne($id) {
        $model = $this->model('users');
        $user = $model->getUser(trim($id)); 
        
        if ($user) {
            unset($user['USER_PASSWORD']); 
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($user);
        exit();
    }

    public function checkPassword() {
        $identifier = trim($_POST['identifier'] ?? ''); 
        $password   = trim($_POST['password'] ?? '');

        if (empty($identifier) || empty($password)) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(["success" => false, "message" => "Vui lòng cung cấp đủ thông tin!"]);
            exit();
        }

        $model = $this->model('users');
        $isCorrect = $model->verifyPassword($identifier, $password);

        header('Content-Type: application/json; charset=utf-8');
        if ($isCorrect) {
            echo json_encode(["success" => true, "message" => "Mật khẩu chính xác!"]);
        } else {
            echo json_encode(["success" => false, "message" => "⛔ Mật khẩu không khớp!"]);
        }
        exit();
    }
    
    // 5. API Cập nhật tài khoản (Không đụng đến pass)
    public function update($id) {
        $data = [
            'username' => trim($_POST['username'] ?? ''),
            'email'    => trim($_POST['email'] ?? ''),
            'phone'    => trim($_POST['phone'] ?? ''),
            'role'     => trim($_POST['role'] ?? 'Staff'),
            'status'   => trim($_POST['status'] ?? 'Active')
        ];

        $model = $this->model("users"); // Đã fix thành 'users'
        $result = $model->updateUser((int)$id, $data);
        echo json_encode([
            "success" => (bool)$result,
            "message" => $result ? "Cập nhật tài khoản thành công!" : "Cập nhật thất bại."
        ]);
        exit();
    }

    // 6. API Reset mật khẩu về 123456
    public function resetPass($id) {
        $model = $this->model("users"); // Đã fix thành 'users'
        $result = $model->resetPassword((int)$id);
        echo json_encode([
            "success" => (bool)$result,
            "message" => $result ? "🔑 Đã đặt lại mật khẩu về mặc định: 123456" : "Reset mật khẩu thất bại!"
        ]);
        exit();
    }

    // 7. API Xóa tài khoản
    public function delete() {
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $model = $this->model("users"); // Đã fix thành 'users'
        $result = $model->deleteUsers($input['ids'] ?? []);
        echo json_encode([
            "success" => (bool)$result,
            "message" => $result ? "Xóa tài khoản thành công!" : "Xóa tài khoản thất bại."
        ]);
        exit();
    }
}