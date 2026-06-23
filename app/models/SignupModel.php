<?php

class SignupModel extends DatabaseUser {

    // Hàm kiểm tra xem Username hoặc Email đã bị ai đăng ký chưa
    public function checkUser($uid, $email) {
        $stmt = $this->connect()->prepare('SELECT users_uid FROM users WHERE users_uid = ? OR users_email = ?;');
        
        if (!$stmt->execute(array($uid, $email))) {
            $stmt = null;
            echo json_encode(['status' => 'error', 'message' => 'Lỗi database: Không thể tạo tài khoản!']);
            // header("Location: " . URLROOT . "/signup?error=stmtfailed");
            exit();
        }

        // Nếu số dòng trả về > 0 nghĩa là đã bị trùng tài khoản
        if ($stmt->rowCount() > 0) {
            return false; 
        } else {
            return true;
        }
    }

    // Hàm chèn tài khoản mới vào Database
    public function setUser($uid, $pwd, $email) {
        $stmt = $this->connect()->prepare('INSERT INTO users (users_uid, users_pwd, users_email) VALUES (?, ?, ?);');
        $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
        if (!$stmt->execute(array($uid, $hashedPwd, $email))) {
            $stmt = null;
            header("Location: " . URLROOT . "/signup?error=stmtfailed");
            exit();
        }
        $stmt = null;
    }
}