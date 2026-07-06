<?php

class RoomsModel extends Database {
    public function getAllRooms() {
        $stmt = $this->connect()->prepare("SELECT * FROM `Room`");
        $stmt->execute();
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
