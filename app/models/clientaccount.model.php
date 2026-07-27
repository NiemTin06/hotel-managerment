<?php

class ClientaccountModel extends Database {
    // Lấy thông tin tài khoản Customer đang đăng nhập.
    public function getAccountByUserId(int $userId) {
        $sql = "SELECT
                    u.USER_ID,
                    u.USER_USERNAME,
                    u.USER_EMAIL,
                    u.USER_PHONE,
                    u.USER_ROLE,
                    u.USER_STATUS,
                    u.USER_CREATED_AT,
                    c.CUSTOMER_ID,
                    c.CUSTOMER_FULLNAME,
                    c.CUSTOMER_PHONE,
                    c.CUSTOMER_EMAIL,
                    c.CUSTOMER_CREATED_AT
                FROM `User` u
                INNER JOIN `Customer` c
                    ON c.CUSTOMER_USER_ID = u.USER_ID
                WHERE u.USER_ID = :user_id
                  AND u.USER_ROLE = 'Customer'
                  AND u.USER_STATUS = 'Active'
                LIMIT 1";

        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([
            ':user_id' => $userId
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Cập nhật họ tên, số điện thoại và mật khẩu nếu người dùng có nhập.
    public function updateProfile(
        int $userId,
        string $fullname,
        string $phone,
        ?string $passwordHash = null
    ): bool {
        $db = $this->connect();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $db->beginTransaction();

            // Kiểm tra User và Customer có liên kết với nhau không.
            $accountStmt = $db->prepare("SELECT
                                            u.USER_ID,
                                            c.CUSTOMER_ID
                                        FROM `User` u
                                        INNER JOIN `Customer` c
                                            ON c.CUSTOMER_USER_ID = u.USER_ID
                                        WHERE u.USER_ID = :user_id
                                          AND u.USER_ROLE = 'Customer'
                                          AND u.USER_STATUS = 'Active'
                                        LIMIT 1
                                        FOR UPDATE");

            $accountStmt->execute([
                ':user_id' => $userId
            ]);

            $account = $accountStmt->fetch(PDO::FETCH_ASSOC);

            if (!$account) {
                throw new RuntimeException('INVALID_ACCOUNT');
            }

            $customerId = (int)$account['CUSTOMER_ID'];

            // Không cho dùng số điện thoại của User hoặc Customer khác.
            $duplicateStmt = $db->prepare("SELECT 1
                                           FROM `User` u
                                           WHERE u.USER_PHONE = :user_phone
                                             AND u.USER_ID <> :user_id

                                           UNION ALL

                                           SELECT 1
                                           FROM `Customer` c
                                           WHERE c.CUSTOMER_PHONE = :customer_phone
                                             AND c.CUSTOMER_ID <> :customer_id
                                           LIMIT 1");

            $duplicateStmt->execute([
                ':user_phone' => $phone,
                ':customer_phone' => $phone,
                ':user_id' => $userId,
                ':customer_id' => $customerId
            ]);

            if ($duplicateStmt->fetchColumn()) {
                throw new RuntimeException('PHONE_EXISTS');
            }

            if ($passwordHash !== null) {
                $userStmt = $db->prepare("UPDATE `User`
                                          SET USER_PHONE = :phone,
                                              USER_PASSWORD = :password
                                          WHERE USER_ID = :user_id
                                            AND USER_ROLE = 'Customer'
                                            AND USER_STATUS = 'Active'");

                $userStmt->execute([
                    ':phone' => $phone,
                    ':password' => $passwordHash,
                    ':user_id' => $userId
                ]);
            } else {
                $userStmt = $db->prepare("UPDATE `User`
                                          SET USER_PHONE = :phone
                                          WHERE USER_ID = :user_id
                                            AND USER_ROLE = 'Customer'
                                            AND USER_STATUS = 'Active'");

                $userStmt->execute([
                    ':phone' => $phone,
                    ':user_id' => $userId
                ]);
            }

            $customerStmt = $db->prepare("UPDATE `Customer`
                                          SET CUSTOMER_FULLNAME = :fullname,
                                              CUSTOMER_PHONE = :phone
                                          WHERE CUSTOMER_ID = :customer_id
                                            AND CUSTOMER_USER_ID = :user_id");

            $customerStmt->execute([
                ':fullname' => $fullname,
                ':phone' => $phone,
                ':customer_id' => $customerId,
                ':user_id' => $userId
            ]);

            $db->commit();
            return true;
        } catch (Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }

            throw $e;
        }
    }

    // Lấy toàn bộ đơn đặt phòng của Customer.
    public function getBookingsByCustomerId(int $customerId): array {
        $sql = "SELECT
                    b.BOOKING_CODE,
                    b.BOOKING_CHECKIN,
                    b.BOOKING_CHECKOUT,
                    b.BOOKING_TOTAL_PRICE,
                    b.BOOKING_STATUS,
                    b.BOOKING_NOTE,
                    b.BOOKING_CREATED_AT,
                    rt.ROOMTYPE_NAME,
                    r.ROOM_NUMBER
                FROM `Booking` b
                INNER JOIN `RoomType` rt
                    ON rt.ROOMTYPE_ID = b.BOOKING_ROOMTYPE_ID
                LEFT JOIN `Room` r
                    ON r.ROOM_ID = b.BOOKING_ROOM_ID
                WHERE b.BOOKING_CUSTOMER_ID = :customer_id
                ORDER BY b.BOOKING_CREATED_AT DESC,
                         b.BOOKING_ID DESC";

        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([
            ':customer_id' => $customerId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
