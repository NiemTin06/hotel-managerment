<?php
require_once 'app/helpers/toslug.helper.php';
class RoomsTypeModel extends Database {
    public function getAllRoomsType(array $filters = []){
        $sql = "SELECT * FROM `RoomType` WHERE 1 = 1";
        $params = [];
        
        /// Lọc theo trạng thái
        if (!empty($filters['status'])) {
            $sql .= " AND ROOMTYPE_STATUS = ?";
            $params[] = $filters['status'];
        }

        // Lọc theo loại giường
        if (!empty($filters['roomtype-bed-type'])) {
            $sql .= " AND ROOMTYPE_BED_TYPE = ?";
            $params[] = $filters['roomtype-bed-type'];
        }

        // Lọc theo sức chứa
        if (!empty($filters['roomtype-max-guests'])) {
            $sql .= " AND ROOMTYPE_MAX_GUESTS = ?";
            $params[] = $filters['roomtype-max-guests'];
        }

        // Tìm kiếm theo tên loại phòng
        if (!empty($filters['search'])) {
            $sql .= " AND ROOMTYPE_NAME COLLATE utf8mb4_unicode_ci LIKE ?";
            $params[] = '%' . $filters['search'] . '%';
        }

        // Sắp xếp
        $sortMap = [
            'price_asc'        => 'ROOMTYPE_PRICE_PER_NIGHT ASC',
            'price_desc'       => 'ROOMTYPE_PRICE_PER_NIGHT DESC',
            'room_number_asc'  => 'ROOMTYPE_NAME ASC',
            'room_number_desc' => 'ROOMTYPE_NAME DESC',
        ];

        if (!empty($filters['sort-by']) && isset($sortMap[$filters['sort-by']])) {
            $sql .= " ORDER BY " . $sortMap[$filters['sort-by']];
        }
        // Phân trang
        // $sql .= " LIMIT ? OFFSET ?";

        // $offset = (int)($filters['offset'] ?? 0);
        // $limit = (int)($filters['limit'] ?? 10);
        $stmt = $this->connect()->prepare($sql);

         // Bind từng param WHERE như bình thường
        foreach ($params as $i => $value) {
            $stmt->bindValue($i + 1, $value);
        }

        // Bind riêng LIMIT/OFFSET là kiểu INT
        // $paramIndex = count($params) + 1;
        // $stmt->bindValue($paramIndex, $limit, PDO::PARAM_INT);
        // $stmt->bindValue($paramIndex + 1, $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function count(array $filters = []){
        $sql = "SELECT COUNT(*) FROM RoomType WHERE 1 = 1";
        $params = [];

        // Lọc theo trạng thái
        if (!empty($filters['status'])) {
            $sql .= " AND ROOMTYPE_STATUS = ?";
            $params[] = $filters['status'];
        }

        // Lọc theo loại giường
        if (!empty($filters['roomtype-bed-type'])) {
            $sql .= " AND ROOMTYPE_BED_TYPE = ?";
            $params[] = $filters['roomtype-bed-type'];
        }

        // Lọc theo sức chứa
        if (!empty($filters['roomtype-max-guests'])) {
            $sql .= " AND ROOMTYPE_MAX_GUESTS = ?";
            $params[] = $filters['roomtype-max-guests'];
        }

        // Tìm kiếm
        if (!empty($filters['search'])) {
            $sql .= " AND ROOMTYPE_NAME COLLATE utf8mb4_unicode_ci LIKE ?";
            $params[] = '%' . $filters['search'] . '%';
        }

        $stmt = $this->connect()->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn(); 
    }
    public function updateRoomTypeStatus(array $ids, string $newStatus){
        if (empty($ids) || empty($newStatus)) {
            return false;
        }
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "UPDATE `RoomType`
                SET `ROOMTYPE_STATUS` = ?
                WHERE `ROOMTYPE_ID` IN ($placeholders)";
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
    public function getRoomTypeById(int $id){
        $sql = "SELECT * FROM RoomType WHERE ROOMTYPE_ID = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function updateRoomType($id, $data){
        $slug = toSlug($data['typeName']); 
        $sql = "UPDATE RoomType
                SET
                    ROOMTYPE_NAME = :name,
                    ROOMTYPE_PRICE_PER_NIGHT = :price,
                    ROOMTYPE_DISCOUNT_PERCENTAGE = :discount,
                    ROOMTYPE_DESCRIPTION = :desc,
                    ROOMTYPE_MAX_GUESTS = :maxg,
                    ROOMTYPE_BED_TYPE = :bed,
                    ROOMTYPE_SLUG = :slug ";
        if($data['thumbnail']){
            $sql .= ", ROOMTYPE_THUMBNAIL=:thumb";
        };
        $sql .= " WHERE ROOMTYPE_ID=:id";

        $stmt = $this->connect()->prepare($sql);

        $stmt->bindParam(':name', $data['typeName']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':discount', $data['discount']);
        $stmt->bindParam(':desc', $data['description']);
        $stmt->bindParam(':maxg', $data['maxGuests'], PDO::PARAM_INT);
        $stmt->bindParam(':bed', $data['bedType']);
        $stmt->bindParam(':slug', $slug); 
        $stmt->bindParam(':id', $id);

        if ($data['thumbnail']){
            $stmt->bindParam(':thumb', $data['thumbnail']);
        }
        return $stmt->execute(); 
    }
    public function deleteRoomType(array $ids)
    {   
        if (empty($ids)) {
            return false;
        }
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "
            DELETE FROM RoomType
            WHERE ROOMTYPE_ID IN ($placeholders)
        ";
        $stmt = $this->connect()->prepare($sql);
        foreach ($ids as $index => $id) {
            $stmt->bindValue($index + 1, $id, PDO::PARAM_INT);
        }
        return $stmt->execute();
    }
}
