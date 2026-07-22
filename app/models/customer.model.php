<?php
class CustomerModel extends Database {
    public function getAllCustomers(array $filters = []): array {
        // LEFT JOIN với Booking để đếm số lần đặt và tổng tiền chi tiêu
        $sql = "
            SELECT 
                c.*,
                COUNT(b.BOOKING_ID) AS SO_LAN_DAT,
                COALESCE(SUM(b.BOOKING_TOTAL_PRICE), 0) AS TONG_CHI_TIEU
            FROM Customer c
            LEFT JOIN Booking b ON c.CUSTOMER_ID = b.BOOKING_CUSTOMER_ID
            WHERE 1 = 1
        ";

        $params = [];

        // Tìm kiếm theo tên, số điện thoại hoặc CCCD
        if (!empty($filters['search'])) {
            $sql .= " AND (
                c.CUSTOMER_FULLNAME COLLATE utf8mb4_unicode_ci LIKE ? OR 
                c.CUSTOMER_PHONE LIKE ? OR 
                c.CUSTOMER_CCCD LIKE ?
            )";
            $keyword = "%" . trim($filters['search']) . "%";
            $params[] = $keyword;
            $params[] = $keyword;
            $params[] = $keyword;
        }

        $sql .= " GROUP BY c.CUSTOMER_ID";

        // Sắp xếp (Hỗ trợ sort theo khách VIP chi tiêu nhiều hoặc đặt nhiều)
        $sortMap = [
            "name_asc"  => "c.CUSTOMER_FULLNAME ASC",
            "name_desc" => "c.CUSTOMER_FULLNAME DESC",
            "spent_desc"=> "TONG_CHI_TIEU DESC",
            "booking_desc" => "SO_LAN_DAT DESC"
        ];

        if (!empty($filters['sort-by']) && isset($sortMap[$filters['sort-by']])) {
            $sql .= " ORDER BY " . $sortMap[$filters['sort-by']];
        } else {
            $sql .= " ORDER BY c.CUSTOMER_ID DESC"; // Mặc định khách mới nhất lên đầu
        }

        // Phân trang
        $sql .= " LIMIT ? OFFSET ?";
        $offset = (int)($filters['offset'] ?? 0);
        $limit  = (int)($filters['limit'] ?? 10);

        $stmt = $this->connect()->prepare($sql);

        foreach ($params as $i => $value) {
            $stmt->bindValue($i + 1, $value, PDO::PARAM_STR);
        }

        $index = count($params) + 1;
        $stmt->bindValue($index, $limit, PDO::PARAM_INT);
        $stmt->bindValue($index + 1, $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function count(array $filters = []): int {
        $sql = "SELECT COUNT(*) FROM Customer c WHERE 1 = 1";
        $params = [];

        if (!empty($filters['search'])) {
            $sql .= " AND (
                c.CUSTOMER_FULLNAME COLLATE utf8mb4_unicode_ci LIKE ? OR 
                c.CUSTOMER_PHONE LIKE ? OR 
                c.CUSTOMER_CCCD LIKE ?
            )";
            $keyword = "%" . trim($filters['search']) . "%";
            $params[] = $keyword;
            $params[] = $keyword;
            $params[] = $keyword;
        }

        $stmt = $this->connect()->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public function getCustomerById(int $id) {
        $sql = "
            SELECT 
                c.*,
                COUNT(b.BOOKING_ID) AS SO_LAN_DAT,
                COALESCE(SUM(b.BOOKING_TOTAL_PRICE), 0) AS TONG_CHI_TIEU
            FROM Customer c
            LEFT JOIN Booking b ON c.CUSTOMER_ID = b.BOOKING_CUSTOMER_ID
            WHERE c.CUSTOMER_ID = ?
            GROUP BY c.CUSTOMER_ID
        ";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCustomerByPhone(string $phone) {
        $sql = "SELECT * FROM Customer WHERE CUSTOMER_PHONE = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindValue(1, trim($phone), PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addCustomer(array $data): bool {
        $sql = "
            INSERT INTO Customer (CUSTOMER_FULLNAME, CUSTOMER_PHONE, CUSTOMER_EMAIL, CUSTOMER_CCCD)
            VALUES (:fullname, :phone, :email, :cccd)
        ";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":fullname", $data["fullname"], PDO::PARAM_STR);
        $stmt->bindParam(":phone", $data["phone"], PDO::PARAM_STR);
        $stmt->bindParam(":email", $data["email"], PDO::PARAM_STR);
        $stmt->bindParam(":cccd", $data["cccd"], PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function updateCustomer(int $id, array $data): bool {
        $sql = "
            UPDATE Customer 
            SET CUSTOMER_FULLNAME = :fullname,
                CUSTOMER_PHONE = :phone,
                CUSTOMER_EMAIL = :email,
                CUSTOMER_CCCD = :cccd
            WHERE CUSTOMER_ID = :id
        ";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":fullname", $data["fullname"], PDO::PARAM_STR);
        $stmt->bindParam(":phone", $data["phone"], PDO::PARAM_STR);
        $stmt->bindParam(":email", $data["email"], PDO::PARAM_STR);
        $stmt->bindParam(":cccd", $data["cccd"], PDO::PARAM_STR);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteCustomer(array $ids): bool {
        if (empty($ids)) return false;
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "DELETE FROM Customer WHERE CUSTOMER_ID IN ($placeholders)";
        $stmt = $this->connect()->prepare($sql);
        foreach ($ids as $index => $id) {
            $stmt->bindValue($index + 1, (int)$id, PDO::PARAM_INT);
        }
        return $stmt->execute();
    }
}