<?php

class ClientauthModel extends Database {
    public function getUserByUsernameOrEmail($userInput) {
        $sql = "SELECT * FROM `User`
                WHERE USER_USERNAME = :user_input
                   OR USER_EMAIL = :user_input
                LIMIT 1";

        $stmt = $this->connect()->prepare($sql);
        $stmt->bindValue(':user_input', trim($userInput), PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getDuplicateCustomerField($username, $email, $phone) {
        $sql = "SELECT USER_USERNAME, USER_EMAIL, USER_PHONE
                FROM `User`
                WHERE USER_USERNAME = :username
                    OR USER_EMAIL = :email
                    OR USER_PHONE = :phone
                LIMIT 1";

        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([':username' => $username, ':email' => $email, ':phone' => $phone]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if ($user['USER_USERNAME'] === $username) {
                return 'username';
            }

            if ($user['USER_EMAIL'] === $email) {
                return 'email';
            }

            if (($user['USER_PHONE'] ?? '') === $phone) {
                return 'phone';
            }
        }

        $sql = "SELECT CUSTOMER_PHONE, CUSTOMER_EMAIL
                FROM `Customer`
                WHERE CUSTOMER_PHONE = :phone
                    OR CUSTOMER_EMAIL = :email
                LIMIT 1";

        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([':phone' => $phone, ':email' => $email]);

        $customer = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($customer) {
            if ($customer['CUSTOMER_PHONE'] === $phone) {
                return 'phone';
            }

            if (($customer['CUSTOMER_EMAIL'] ?? '') === $email) {
                return 'email';
            }
        }

        return null;
    }

    public function registerCustomerAccount(array $data) {
        $pdo = $this->connect();

        try {
            $pdo->beginTransaction();

            $sql = "INSERT INTO `User`
                    (USER_USERNAME, USER_EMAIL, USER_PASSWORD, USER_PHONE, USER_ROLE, USER_STATUS)
                    VALUES
                    (:username, :email, :password, :phone, 'Customer', 'Active')";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([ 
                ':username' => $data['username'],
                ':email' => $data['email'],
                ':password' => $data['password_hash'],
                ':phone' => $data['phone']
                ]);

             // Lấy ID tài khoản vừa tạo để liên kết với Customer.
            $userId = (int)$pdo->lastInsertId();

            $sql = "INSERT INTO `Customer`
                    (CUSTOMER_FULLNAME, CUSTOMER_PHONE, CUSTOMER_EMAIL)
                    VALUES
                    (:fullname, :phone, :email)";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':fullname' => $data['fullname'],
                ':phone' => $data['phone'],
                ':email' => $data['email']
                ]);

            $pdo->commit();
            return true;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    public function getCustomerForUser(array $user) {
        $sql = "SELECT *
        FROM `Customer`
        WHERE CUSTOMER_USER_ID = :user_id
        LIMIT 1";

        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([':user_id' => (int)$user['USER_ID']]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
        
        return false;
    }
}
