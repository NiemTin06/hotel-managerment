<?php 

class LoginController extends Controller{
    private $userInput;
    private $pwd;
    public function index() {
        $data = [
            'title' => 'Đăng nhập Hệ Thống',
            'view_content' => 'pages/auth/login' 
        ];
        $this->view('admin/layout/main_layout', $data);
    }

     public function loginUser(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->userInput = trim($_POST["userInput"]);
            $this->pwd = trim($_POST["pwd"]);
            if ($this->emptyInput()){
                echo json_encode(['status' => 'error', 'message' => 'Vui lòng điền đầy đủ thông tin!']);
                exit();
            }

            $UserModel = $this->model('users');
            $user = $UserModel->getUser($this->userInput);
            if (!$user){
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Tài khoản không tồn tại!'
                ]);
                exit();
            }
             if ($this->invalidPassword($user['users_pwd'])){
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Mật khẩu không chính xác!'
                ]);
                exit();
            }

                // Tài khoản bị khóa
            // if ($this->isAccountBlocked($user)){
            //     echo json_encode([
            //         'status' => 'error',
            //         'message' => 'Tài khoản đã bị khóa!'
            //     ]);
            //     exit();
            // }

            $this->createSession($user);

            echo json_encode([
                'status' => 'success',
                'message' => 'Đăng nhập tài khoản thành công!'
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
    private function notUserExists(){
        $UserModel = $this->model('users');
        if (!$UserModel->checkUser($this->userInput, $this->userInput)){
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
    // private function isAccountBlocked($user){
    //     if ($user['status'] !== 'active'){
    //         return true;
    //     }
    //     return false;
    // }
    // private function tooManyAttempts($user){
    //     if ($user['failed_login'] >= 5){
    //         return true;
    //     }
    //     return false;
    // }
    private function createSession($user){
        $_SESSION['user_id'] = $user['users_id'];
        $_SESSION['username'] = $user['users_uid'];
    }
}

