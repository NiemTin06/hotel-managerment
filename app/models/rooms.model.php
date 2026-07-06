<?php

class RoomsModel extends Database {
    public function getAllRooms(array $filters = []) {
        $sql = "SELECT * FROM `Room` WHERE 1 = 1";
        $params = [];
        
        // Lọc theo trạng thái
        if (!empty($filters['status'])) {
            $sql .= " AND ROOM_STATUS = ?";
            $params[] = $filters['status'];
        }

        // Lọc theo loại phòng
        if (!empty($filters['room-type'])) {
            $sql .= " AND ROOM_ROOMTYPE_ID = ?";
            $params[] = $filters['room-type'];
        }

        // Sắp xếp
        $sortMap = [
            'price_asc'        => 'ROOM_PRICE_PER_NIGHT ASC',
            'price_desc'       => 'ROOM_PRICE_PER_NIGHT DESC',
            'room_number_asc'  => 'ROOM_NUMBER ASC',
            'room_number_desc' => 'ROOM_NUMBER DESC',
        ];

        if (!empty($filters['sort-by']) && isset($sortMap[$filters['sort-by']])) {
            $sql .= " ORDER BY " . $sortMap[$filters['sort-by']];
        }
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateRoomStatus(array $ids, string $newStatus){
        if (empty($ids) || empty($newStatus)) {
            return false;
        }
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        if ($newStatus == 'Deleted' || $newStatus == 'Maintenance' || $newStatus == 'Occupied') {
            $sql = "UPDATE `Room`
                    SET `ROOM_STATUS` = ?, `ROOM_DELETED` = 1
                    WHERE `ROOM_ID` IN ($placeholders)";
        } else {
            $sql = "UPDATE `Room`
                    SET `ROOM_STATUS` = ?, `ROOM_DELETED` = 0
                    WHERE `ROOM_ID` IN ($placeholders)";
        }
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindValue(1, $newStatus, PDO::PARAM_STR);
        // Bind từng id
        foreach ($ids as $index => $id) {
            $stmt->bindValue($index + 2, $id, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}
