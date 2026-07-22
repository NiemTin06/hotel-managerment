<?php
class BookingsModel extends Database {
    public function getAllBookings(array $filters = []): array {
        $sql = "
            SELECT 
                b.*,
                c.CUSTOMER_FULLNAME,
                c.CUSTOMER_PHONE,
                rt.ROOMTYPE_NAME,
                rt.ROOMTYPE_PRICE_PER_NIGHT,
                r.ROOM_NUMBER
            FROM Booking b
            INNER JOIN Customer c ON b.BOOKING_CUSTOMER_ID = c.CUSTOMER_ID
            INNER JOIN RoomType rt ON b.BOOKING_ROOMTYPE_ID = rt.ROOMTYPE_ID
            LEFT JOIN Room r ON b.BOOKING_ROOM_ID = r.ROOM_ID
            WHERE 1 = 1
        ";

        $params = [];

        // Lọc theo trạng thái đơn
        if (!empty($filters['status'])) {
            $sql .= " AND b.BOOKING_STATUS = ?";
            $params[] = $filters['status'];
        }

        // Lọc theo loại phòng
        if (!empty($filters['roomtype'])) {
            $sql .= " AND b.BOOKING_ROOMTYPE_ID = ?";
            $params[] = $filters['roomtype'];
        }

        // Tìm kiếm theo tên hoặc SĐT khách
        if (!empty($filters['search'])) {
            $sql .= " AND (
                c.CUSTOMER_FULLNAME COLLATE utf8mb4_unicode_ci LIKE ? OR 
                c.CUSTOMER_PHONE LIKE ?
            )";
            $keyword = "%" . trim($filters['search']) . "%";
            $params[] = $keyword;
            $params[] = $keyword;
        }

        // Sắp xếp
        $sortMap = [
            "date_desc" => "b.BOOKING_CREATED_AT DESC",
            "date_asc"  => "b.BOOKING_CREATED_AT ASC",
            "checkin_asc" => "b.BOOKING_CHECKIN ASC",
            "price_desc" => "b.BOOKING_TOTAL_PRICE DESC"
        ];

        if (!empty($filters['sort-by']) && isset($sortMap[$filters['sort-by']])) {
            $sql .= " ORDER BY " . $sortMap[$filters['sort-by']];
        } else {
            $sql .= " ORDER BY b.BOOKING_ID DESC";
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
        $sql = "
            SELECT COUNT(*) 
            FROM Booking b
            INNER JOIN Customer c ON b.BOOKING_CUSTOMER_ID = c.CUSTOMER_ID
            WHERE 1 = 1
        ";
        $params = [];

        if (!empty($filters['status'])) {
            $sql .= " AND b.BOOKING_STATUS = ?";
            $params[] = $filters['status'];
        }
        if (!empty($filters['roomtype'])) {
            $sql .= " AND b.BOOKING_ROOMTYPE_ID = ?";
            $params[] = $filters['roomtype'];
        }
        if (!empty($filters['search'])) {
            $sql .= " AND (c.CUSTOMER_FULLNAME COLLATE utf8mb4_unicode_ci LIKE ? OR c.CUSTOMER_PHONE LIKE ?)";
            $keyword = "%" . trim($filters['search']) . "%";
            $params[] = $keyword;
            $params[] = $keyword;
        }

        $stmt = $this->connect()->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public function getBookingById(int $id) {
        $sql = "
            SELECT b.*, c.CUSTOMER_FULLNAME, c.CUSTOMER_PHONE, rt.ROOMTYPE_NAME, r.ROOM_NUMBER
            FROM Booking b
            INNER JOIN Customer c ON b.BOOKING_CUSTOMER_ID = c.CUSTOMER_ID
            INNER JOIN RoomType rt ON b.BOOKING_ROOMTYPE_ID = rt.ROOMTYPE_ID
            LEFT JOIN Room r ON b.BOOKING_ROOM_ID = r.ROOM_ID
            WHERE b.BOOKING_ID = ?
        ";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách phòng trống của 1 Loại phòng để Lễ tân chọn lúc Check-in
    public function getAvailableRoomsByTypeId(int $roomTypeId): array {
        $sql = "SELECT ROOM_ID, ROOM_NUMBER, ROOM_DESCRIPTION FROM Room WHERE ROOM_ROOMTYPE_ID = ? AND ROOM_STATUS = 'Available'";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindValue(1, $roomTypeId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addBooking(array $data): bool {
        $sql = "
            INSERT INTO Booking (
                BOOKING_CUSTOMER_ID, BOOKING_ROOMTYPE_ID, BOOKING_CHECKIN, 
                BOOKING_CHECKOUT, BOOKING_TOTAL_PRICE, BOOKING_STATUS, BOOKING_NOTE
            ) VALUES (:customer, :roomtype, :checkin, :checkout, :price, :status, :note)
        ";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":customer", $data['customerId'], PDO::PARAM_INT);
        $stmt->bindParam(":roomtype", $data['roomTypeId'], PDO::PARAM_INT);
        $stmt->bindParam(":checkin", $data['checkin']);
        $stmt->bindParam(":checkout", $data['checkout']);
        $stmt->bindParam(":price", $data['totalPrice']);
        $stmt->bindParam(":status", $data['status']);
        $stmt->bindParam(":note", $data['note']);
        return $stmt->execute();
    }

    // Nghiệp vụ Lễ tân xác nhận Check-in gán số phòng
    public function checkInBooking(int $bookingId, int $roomId): bool {
        try {
            $db = $this->connect();
            $db->beginTransaction();

            // 1. Cập nhật Booking
            $sql1 = "UPDATE Booking SET BOOKING_ROOM_ID = ?, BOOKING_STATUS = 'CheckedIn' WHERE BOOKING_ID = ?";
            $stmt1 = $db->prepare($sql1);
            $stmt1->execute([$roomId, $bookingId]);

            // 2. Cập nhật trạng thái phòng thành Đang sử dụng
            $sql2 = "UPDATE Room SET ROOM_STATUS = 'Occupied' WHERE ROOM_ID = ?";
            $stmt2 = $db->prepare($sql2);
            $stmt2->execute([$roomId]);

            $db->commit();
            return true;
        } catch (\Throwable $e) {
            if (isset($db)) $db->rollBack();
            return false;
        }
    }

    // Nghiệp vụ Trả phòng Check-out
    public function checkOutBooking(int $bookingId, int $roomId = null): bool {
        try {
            $db = $this->connect();
            $db->beginTransaction();

            $sql1 = "UPDATE Booking SET BOOKING_STATUS = 'CheckedOut' WHERE BOOKING_ID = ?";
            $stmt1 = $db->prepare($sql1);
            $stmt1->execute([$bookingId]);

            if ($roomId) {
                $sql2 = "UPDATE Room SET ROOM_STATUS = 'Available' WHERE ROOM_ID = ?";
                $stmt2 = $db->prepare($sql2);
                $stmt2->execute([$roomId]);
            }

            $db->commit();
            return true;
        } catch (\Throwable $e) {
            if (isset($db)) $db->rollBack();
            return false;
        }
    }

    public function deleteBooking(array $ids): bool {
        if (empty($ids)) return false;
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "DELETE FROM Booking WHERE BOOKING_ID IN ($placeholders)";
        $stmt = $this->connect()->prepare($sql);
        foreach ($ids as $index => $id) {
            $stmt->bindValue($index + 1, (int)$id, PDO::PARAM_INT);
        }
        return $stmt->execute();
    }

    public function updateBooking(int $id, array $data): bool {
    $sql = "
        UPDATE Booking SET 
            BOOKING_CUSTOMER_ID = :customer,
            BOOKING_ROOMTYPE_ID = :roomtype,
            BOOKING_CHECKIN = :checkin,
            BOOKING_CHECKOUT = :checkout,
            BOOKING_TOTAL_PRICE = :price,
            BOOKING_STATUS = :status,
            BOOKING_NOTE = :note
        WHERE BOOKING_ID = :id
    ";
    $stmt = $this->connect()->prepare($sql);
    $stmt->bindParam(":customer", $data['customerId'], PDO::PARAM_INT);
    $stmt->bindParam(":roomtype", $data['roomTypeId'], PDO::PARAM_INT);
    $stmt->bindParam(":checkin", $data['checkin']);
    $stmt->bindParam(":checkout", $data['checkout']);
    $stmt->bindParam(":price", $data['totalPrice']);
    $stmt->bindParam(":status", $data['status']);
    $stmt->bindParam(":note", $data['note']);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    return $stmt->execute();
}
}