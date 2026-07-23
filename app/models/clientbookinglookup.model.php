<?php
class ClientbookinglookupModel extends Database {
    public function findBooking(int $bookingId, string $phone){
        $sql = "
            SELECT
                b.BOOKING_ID,
                b.BOOKING_DATE,
                b.BOOKING_CHECKIN,
                b.BOOKING_CHECKOUT,
                b.BOOKING_TOTAL_PRICE,
                b.BOOKING_STATUS,
                b.BOOKING_NOTE,
                b.BOOKING_CREATED_AT,
                c.CUSTOMER_FULLNAME,
                c.CUSTOMER_PHONE,
                c.CUSTOMER_EMAIL,
                c.CUSTOMER_CCCD,
                rt.ROOMTYPE_NAME,
                rt.ROOMTYPE_MAX_GUESTS,
                rt.ROOMTYPE_BED_TYPE,
                r.ROOM_NUMBER
            FROM Booking b 
            INNER JOIN Customer c ON b.BOOKING_CUSTOMER_ID = c.CUSTOMER_ID
            INNER JOIN RoomType rt ON b.BOOKING_ROOMTYPE_ID = rt.ROOMTYPE_ID
            LEFT JOIN Room r ON b.BOOKING_ROOM_ID = r.ROOM_ID
            WHERE b.BOOKING_ID = ? AND c.CUSTOMER_PHONE = ?
            LIMIT 1
        ";

        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$bookingId, $phone]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
