<?php

// Tên Class viết liền không dấu chấm để Router 'new' không bị lỗi
class SignupController extends Controller {
    private $uid;
    private $pwd;
    private $pwdRepeat;
    private $email;

    public function index() {
        $data = [
            'title' => 'Đăng Ký Hệ Thống',
            'view_content' => 'pages/auth/signup' 
        ];
        $this->view('admin/layout/main_layout', $data);
    }
    public function signupUser(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->uid = trim($_POST["uid"]);
            $this->pwd = trim($_POST["pwd"]);
            $this->pwdRepeat = trim($_POST["pwdRepeat"]);
            $this->email = trim($_POST["email"]);
            if ($this->emptyInput()){
                echo json_encode(['status' => 'error', 'message' => 'Vui lòng điền đầy đủ thông tin!']);
                exit();
            }
            if ($this->inValidUid()){
                echo json_encode(['status' => 'error', 'message' => 'Username không hợp lệ (chỉ dùng chữ và số)!']);
                exit();
            }
            if ($this->inValidEmail()){
                echo json_encode(['status' => 'error', 'message' => 'Định dạng Email không chính xác!']);
                exit();
            }
            if ($this->pwdMatch()){
                echo json_encode(['status' => 'error', 'message' => 'Hai mật khẩu không khớp nhau!']);
                exit();
            }
            if ($this->uidTakenCheck()){
                echo json_encode(['status' => 'error', 'message' => 'Tài khoản hoặc Email này đã bị trùng!']);
                exit();
            }

            // Gọi Model để lưu tài khoản vào DB
            $signupModel = $this->model('users');
            $signupModel->setUser($this->uid, $this->pwd, $this->email);

            // Thành công thì đá về lại trang đăng ký hoặc trang nào bạn muốn
            // header("Location: " . URLROOT . "/signup?error=none");
            echo json_encode([
                'status' => 'success',
                'message' => 'Đăng ký tài khoản thành công!'
            ]);
            exit();
        }
    }

    // các hàm private emptyInput(), inValidUid()... ở dưới của bạn cứ giữ nguyên sạch sẽ.
     private function emptyInput(){
        $result = false;
        if (empty($this->uid) || empty($this->pwd) || empty($this->pwdRepeat) || empty($this->email)){
            $result = true;
        }
        return $result;
    }

    private function inValidUid() {
        $result = false;
        if (!preg_match("/^[a-zA-Z0-9]*$/", $this->uid)) {
            $result = true; 
        }
        return $result;
    }

    private function inValidEmail() {
        $result = false;
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $result = true; 
        }
        return $result;
    }
    private function pwdMatch() {
        $result = false;
        if ($this->pwd !== $this->pwdRepeat){
            $result = true;
        }
        return $result;
    }
    private function uidTakenCheck() {
        $result = false;
        $UserModel= $this->model('users');
        if (!$UserModel->checkUser($this->uid, $this->email)){
            $result = true;
        }
        return $result;
    }
}