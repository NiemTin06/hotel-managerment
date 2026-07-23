<?php
class UsersModel extends Database {
    
    public function getAllUsers($filters = []) {
        $sql = "SELECT USER_ID, USER_USERNAME, USER_EMAIL, USER_PHONE, USER_ROLE, USER_STATUS, USER_CREATED_AT 
                FROM `User` WHERE 1=1";
        $params = [];

        // 1. Xử lý tìm kiếm
        if (!empty($filters['search'])) {
            $sql .= " AND (USER_USERNAME LIKE ? OR USER_EMAIL LIKE ? OR USER_PHONE LIKE ?)";
            $kw = "%" . trim($filters['search']) . "%";
            $params[] = $kw;
            $params[] = $kw;
            $params[] = $kw;
        }

        // 2. Xử lý lọc theo trạng thái (Active / Inactive)
        if (!empty($filters['status'])) {
            $sql .= " AND USER_STATUS = ?";
            $params[] = $filters['status'];
        }

        // 3. Xử lý sắp xếp (sort-by từ URL)
        $sql .= " ORDER BY ";
        if (!empty($filters['sort_by'])) {
            switch ($filters['sort_by']) {
                case 'username_asc':
                    $sql .= "USER_USERNAME ASC";
                    break;
                case 'username_desc':
                    $sql .= "USER_USERNAME DESC";
                    break;
                case 'created_desc':
                    $sql .= "USER_ID DESC";
                    break;
                default:
                    $sql .= "USER_ID DESC";
                    break;
            }
        } else {
            $sql .= "USER_ID DESC";
        }

        // 4. Phân trang (LIMIT & OFFSET)
        $sql .= " LIMIT ? OFFSET ?";
        $offset = (int)($filters['offset'] ?? 0);
        $limit  = (int)($filters['limit'] ?? 10);

        $stmt = $this->connect()->prepare($sql);
        
        // Bind các tham số trong WHERE trước
        foreach ($params as $i => $val) {
            $stmt->bindValue($i + 1, $val, PDO::PARAM_STR);
        }
        
        // Bind LIMIT và OFFSET vào cuối
        $stmt->bindValue(count($params) + 1, $limit, PDO::PARAM_INT);
        $stmt->bindValue(count($params) + 2, $offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function count($filters = []) {
        $sql = "SELECT COUNT(*) FROM `User` WHERE 1=1";
        $params = [];
        
        // Xử lý tìm kiếm cho hàm đếm tổng trang
        if (!empty($filters['search'])) {
            $sql .= " AND (USER_USERNAME LIKE ? OR USER_EMAIL LIKE ? OR USER_PHONE LIKE ?)";
            $kw = "%" . trim($filters['search']) . "%";
            $params[] = $kw;
            $params[] = $kw;
            $params[] = $kw;
        }

        // Xử lý lọc theo trạng thái cho hàm đếm tổng trang
        if (!empty($filters['status'])) {
            $sql .= " AND USER_STATUS = ?";
            $params[] = $filters['status'];
        }

        $stmt = $this->connect()->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public function getUser($identifier) { 
        $sql = "SELECT * FROM `User` 
                WHERE USER_ID = :id 
                   OR USER_USERNAME = :username 
                   OR USER_EMAIL = :email 
                LIMIT 1";
                
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindValue(':id', is_numeric($identifier) ? (int)$identifier : 0, PDO::PARAM_INT);
        $stmt->bindValue(':username', trim($identifier), PDO::PARAM_STR);
        $stmt->bindValue(':email', trim($identifier), PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function verifyPassword($identifier, $passwordInput) {
        $user = $this->getUser($identifier);
        if (!$user || empty($user['USER_PASSWORD'])) {
            return false;
        }
        return password_verify($passwordInput, $user['USER_PASSWORD']);
    }

    public function addUser($data) {
        $passToHash = !empty($data['password']) ? $data['password'] : '123456';
        $hashedPassword = password_hash($passToHash, PASSWORD_DEFAULT);

        $sql = "INSERT INTO `User` (USER_USERNAME, USER_EMAIL, USER_PASSWORD, USER_PHONE, USER_ROLE, USER_STATUS)
                VALUES (:username, :email, :password, :phone, :role, :status)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":username", $data['username']);
        $stmt->bindParam(":email", $data['email']);
        $stmt->bindParam(":password", $hashedPassword);
        $stmt->bindParam(":phone", $data['phone']);
        $stmt->bindParam(":role", $data['role']);
        $stmt->bindParam(":status", $data['status']);
        return $stmt->execute();
    }

    public function updateUser($id, $data) {
        $sql = "UPDATE `User` SET USER_USERNAME = :username, USER_EMAIL = :email, 
                USER_PHONE = :phone, USER_ROLE = :role, USER_STATUS = :status WHERE USER_ID = :id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":username", $data['username']);
        $stmt->bindParam(":email", $data['email']);
        $stmt->bindParam(":phone", $data['phone']);
        $stmt->bindParam(":role", $data['role']);
        $stmt->bindParam(":status", $data['status']);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function resetPassword($id) {
        $defaultHash = password_hash('123456', PASSWORD_DEFAULT);
        $sql = "UPDATE `User` SET USER_PASSWORD = ? WHERE USER_ID = ?";
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([$defaultHash, (int)$id]);
    }

    public function deleteUsers($ids) {
        if (empty($ids)) return false;
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "DELETE FROM `User` WHERE USER_ID IN ($placeholders)";
        $stmt = $this->connect()->prepare($sql);
        foreach ($ids as $index => $id) {
            $stmt->bindValue($index + 1, (int)$id, PDO::PARAM_INT);
        }
        return $stmt->execute();
    }
}