<?php
require_once 'app/helpers/toslug.helper.php';
class RoomsTypeModel extends Database {
    public function getAllRoomsType(array $filters = []){
        $sql = "SELECT * FROM `RoomType` WHERE 1 = 1";
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

        if (!empty($filters['search'])) {
            $sql .= " AND ROOM_NUMBER LIKE ?";
            $params[] = '%' . $filters['search'] . '%';
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
    public function addRoomType(array $data) {
        $slug = toSlug($data['typeName']); 
        // Câu lệnh SQL (Bỏ qua cột CREATED_AT và DELETED_AT vì Database tự lo)
        $sql = "INSERT INTO `RoomType`(
            `ROOMTYPE_NAME`,
            `ROOMTYPE_PRICE_PER_NIGHT`,
            `ROOMTYPE_DISCOUNT_PERCENTAGE`,
            `ROOMTYPE_MAX_GUESTS`,
            `ROOMTYPE_BED_TYPE`,
            `ROOMTYPE_DESCRIPTION`,
            `ROOMTYPE_THUMBNAIL`,
            `ROOMTYPE_SLUG`
        )
            VALUES
        (
            :name,
            :price,
            :discount,
            :maxGuests,
            :bedType,
            :desc,
            :thumb,
            :slug
        )";

        $stmt = $this->connect()->prepare($sql);

        $stmt->bindParam(':name', $data['typeName']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':discount', $data['discount']);
        $stmt->bindParam(':desc', $data['description']);
        $stmt->bindParam(':thumb', $data['thumbnail']);
        $stmt->bindParam(':maxGuests', $data['maxGuests'], PDO::PARAM_INT);
        $stmt->bindParam(':bedType', $data['bedType']);
        $stmt->bindParam(':slug', $slug); // Truyền slug tự động vừa tạo vào đây

        return $stmt->execute(); 
    }
}
