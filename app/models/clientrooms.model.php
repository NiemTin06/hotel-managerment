<?php

class ClientroomsModel extends Database {
    private function priceExpression(): string {
        return "(rt.ROOMTYPE_PRICE_PER_NIGHT * (100 - rt.ROOMTYPE_DISCOUNT_PERCENTAGE) / 100)";
    }

    private function availableExpression(): string {
        return "GREATEST(
            (
                SELECT COUNT(*)
                FROM Room r
                WHERE r.ROOM_ROOMTYPE_ID = rt.ROOMTYPE_ID
                    AND r.ROOM_STATUS <> 'Maintenance'
            )
            -
            (
                SELECT COUNT(*)
                FROM Booking b
                WHERE b.BOOKING_ROOMTYPE_ID = rt.ROOMTYPE_ID
                    AND b.BOOKING_STATUS IN ('Pending', 'Confirmed', 'CheckedIn')
                    AND b.BOOKING_CHECKIN < :checkout
                    AND b.BOOKING_CHECKOUT > :checkin
            ),
            0
        )";
    }

    public function getRoomTypes(array $filters): array {
        $price = $this->priceExpression();
        $available = $this->availableExpression();
        $sql = "
            SELECT
                rt.*,
                ROUND($price, 2) AS PRICE_AFTER_DISCOUNT,
                $available AS AVAILABLE_ROOM_COUNT
            FROM RoomType rt
            WHERE rt.ROOMTYPE_STATUS = 'Active'
        ";

        $params = [
            ':checkin' => $filters['checkin'],
            ':checkout' => $filters['checkout']
        ];

        if (!empty($filters['roomTypeId'])) {
            $sql .= " AND rt.ROOMTYPE_ID = :roomTypeId";
            $params[':roomTypeId'] = (int)$filters['roomTypeId'];
        }

        $sortMap = [
            'price_asc' => 'PRICE_AFTER_DISCOUNT ASC',
            'price_desc' => 'PRICE_AFTER_DISCOUNT DESC',
            'discount_desc' => 'rt.ROOMTYPE_DISCOUNT_PERCENTAGE DESC'
        ];

        if (isset($sortMap[$filters['sortBy']])) {
            $sql .= ' ORDER BY ' . $sortMap[$filters['sortBy']];
        } else {
            $sql .= ' ORDER BY rt.ROOMTYPE_ID DESC';
        }

        $stmt = $this->connect()->prepare($sql);

        foreach ($params as $name => $value) {
            $type = $name === ':roomTypeId' ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue($name, $value, $type);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFeaturedRoomTypes(int $limit = 3): array {
        $price = $this->priceExpression();
        $sql = "
            SELECT
                rt.*,
                ROUND($price, 2) AS PRICE_AFTER_DISCOUNT
            FROM RoomType rt
            WHERE rt.ROOMTYPE_STATUS = 'Active'
            ORDER BY
                rt.ROOMTYPE_DISCOUNT_PERCENTAGE DESC,
                rt.ROOMTYPE_ID DESC
            LIMIT :limit
        ";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActiveRoomTypes(): array {
        $sql = "
            SELECT
                ROOMTYPE_ID,
                ROOMTYPE_NAME
            FROM RoomType
            WHERE ROOMTYPE_STATUS = 'Active'
            ORDER BY ROOMTYPE_NAME ASC
        ";

        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRoomTypeById( int $roomTypeId, string $checkin, string $checkout) {
        $price = $this->priceExpression();
        $available = $this->availableExpression();
        $sql = "
            SELECT
                rt.*,
                ROUND($price, 2) AS PRICE_AFTER_DISCOUNT,
                $available AS AVAILABLE_ROOM_COUNT
            FROM RoomType rt
            WHERE rt.ROOMTYPE_ID = :roomTypeId
                AND rt.ROOMTYPE_STATUS = 'Active'
            LIMIT 1
        ";

        $stmt = $this->connect()->prepare($sql);
        $stmt->bindValue(':roomTypeId', $roomTypeId, PDO::PARAM_INT);
        $stmt->bindValue(':checkin', $checkin);
        $stmt->bindValue(':checkout', $checkout);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
