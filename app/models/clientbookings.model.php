<?php

class ClientbookingsModel extends Database {
    public function createBooking(array $customer, array $booking): int {
        $db = $this->connect();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            // Bat dau thuc hien cac cau lenh sql the mot nhom, chi khi commit thi mmoi thay doi moi duoc luu vao database
            $db->beginTransaction();
            $customerId = $this->findOrCreateCustomer($db, $customer);
            $roomStmt = $db->prepare("
                SELECT ROOM_ID
                FROM Room
                WHERE ROOM_ROOMTYPE_ID = ? 
                    AND ROOM_STATUS <> 'Maintenance'
                FOR UPDATE
            ");
            $roomStmt->execute([$booking['roomTypeId']]);
            $totalRooms = count($roomStmt->fetchAll(PDO::FETCH_ASSOC));

            $bookingStmt = $db->prepare("
                SELECT COUNT(*)
                FROM Booking
                WHERE BOOKING_ROOMTYPE_ID = ?
                    AND BOOKING_STATUS IN ('Pending', 'Confirmed', 'CheckedIn')
                    AND BOOKING_CHECKIN < ?
                    AND BOOKING_CHECKOUT > ?
            ");
            $bookingStmt->execute([
                $booking['roomTypeId'],
                $booking['checkout'],
                $booking['checkin']
            ]);

            $bookedRooms = (int)$bookingStmt->fetchColumn();

            if (($totalRooms - $bookedRooms) <= 0) {
                throw new RuntimeException('NO_AVAILABLE_ROOM');
            }

            $insert = $db->prepare("
                INSERT INTO Booking (
                    BOOKING_CUSTOMER_ID,
                    BOOKING_ROOMTYPE_ID,
                    BOOKING_ROOM_ID,
                    BOOKING_CHECKIN,
                    BOOKING_CHECKOUT,
                    BOOKING_TOTAL_PRICE,
                    BOOKING_STATUS,
                    BOOKING_NOTE
                ) VALUES (
                    :customerId,
                    :roomTypeId,
                    NULL,
                    :checkin,
                    :checkout,
                    :totalPrice,
                    'Pending',
                    :note
                )
            ");

            $insert->execute([
                ':customerId' => $customerId,
                ':roomTypeId' => $booking['roomTypeId'],
                ':checkin' => $booking['checkin'],
                ':checkout' => $booking['checkout'],
                ':totalPrice' => $booking['totalPrice'],
                ':note' => $booking['note'] !== '' ? $booking['note'] : null
            ]);

            $bookingId = (int)$db->lastInsertId();
            $db->commit();
            return $bookingId;
        } catch (Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            throw $e;
        }
    }

    private function findOrCreateCustomer(PDO $db, array $customer): int {
        $stmt = $db->prepare("
            SELECT CUSTOMER_ID
            FROM Customer
            WHERE CUSTOMER_PHONE = ?
            LIMIT 1
            FOR UPDATE
        ");
        $stmt->execute([$customer['phone']]);
        $record = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($record) {
            $update = $db->prepare("
                UPDATE Customer
                SET CUSTOMER_FULLNAME = :fullname,
                    CUSTOMER_EMAIL = :email,
                    CUSTOMER_CCCD = :cccd
                WHERE CUSTOMER_ID = :id
            ");

            $update->execute([
                ':fullname' => $customer['fullname'],
                ':email' => $customer['email'] !== ''
                    ? $customer['email']
                    : null,
                ':cccd' => $customer['cccd'],
                ':id' => (int)$record['CUSTOMER_ID']
            ]);

            return (int)$record['CUSTOMER_ID'];
        }

        $insert = $db->prepare("
            INSERT INTO Customer (
                CUSTOMER_FULLNAME,
                CUSTOMER_PHONE,
                CUSTOMER_EMAIL,
                CUSTOMER_CCCD
            ) VALUES (
                :fullname,
                :phone,
                :email,
                :cccd
            )
        ");

        $insert->execute([ 
            ':fullname' => $customer['fullname'],
            ':phone' => $customer['phone'],
            ':email' => $customer['email'] !== '' ? $customer['email'] : null,
            ':cccd' => $customer['cccd']
        ]);

        return (int)$db->lastInsertId();
    }
}
