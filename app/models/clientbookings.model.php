<?php

class ClientbookingsModel extends Database {
    public function createBooking(int $customerId, int $userId, array $booking): int {
        $db = $this->connect();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $db->beginTransaction();
            $bookingCode = $this->generateBookingCode();
            $customerStmt = $db->prepare("
            SELECT c.CUSTOMER_ID
            FROM Customer c
            INNER JOIN `User` u
                ON c.CUSTOMER_USER_ID = u.USER_ID
            WHERE c.CUSTOMER_ID = ?
                AND c.CUSTOMER_USER_ID = ?
                AND u.USER_ROLE = 'Customer'
                AND u.USER_STATUS = 'Active'
            LIMIT 1
            FOR UPDATE
            ");

            $customerStmt->execute([
                $customerId,
                $userId
            ]);

            if (!$customerStmt->fetch(PDO::FETCH_ASSOC)) {
                throw new RuntimeException('INVALID_CUSTOMER');
            }

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
                    BOOKING_CODE,
                    BOOKING_CUSTOMER_ID,
                    BOOKING_ROOMTYPE_ID,
                    BOOKING_ROOM_ID,
                    BOOKING_CHECKIN,
                    BOOKING_CHECKOUT,
                    BOOKING_TOTAL_PRICE,
                    BOOKING_STATUS,
                    BOOKING_NOTE
                ) VALUES (
                   :booking_code,
                    :customer_id,
                    :room_type_id,
                    NULL,
                    :checkin,
                    :checkout,
                    :total_price,
                    'Pending',
                    :note
                )
            ");

            $insert->execute([
                ':booking_code' => $bookingCode,
                ':customer_id'  => $customerId,
                ':room_type_id' => $booking['roomTypeId'],
                ':checkin'      => $booking['checkin'],
                ':checkout'     => $booking['checkout'],
                ':total_price'  => $booking['totalPrice'],
                ':note'         => $booking['note'] !== '' ? $booking['note'] : null
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
    public function generateBookingCode()
{
    $today = date('Ymd');

    $sql = "
        SELECT BOOKING_CODE
        FROM Booking
        WHERE BOOKING_CODE LIKE ?
        ORDER BY BOOKING_CODE DESC
        LIMIT 1
    ";

    $stmt = $this->connect()->prepare($sql);

    $stmt->execute(["BK{$today}%"]);

    $lastCode = $stmt->fetchColumn();

    if (!$lastCode) {
        return "BK{$today}0001";
    }

    $number = (int)substr($lastCode, -4);
    $number++;

    return "BK{$today}" . str_pad($number, 4, "0", STR_PAD_LEFT);
}
}
