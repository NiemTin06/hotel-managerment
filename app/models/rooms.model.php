<?php
require_once 'app/helpers/toslug.helper.php';
class RoomsModel extends Database {
    public function getAllRooms(array $filters = [])
    {
        $sql = "
            SELECT
                Room.*,
                RoomType.ROOMTYPE_NAME
            FROM Room
            INNER JOIN RoomType
                ON Room.ROOM_ROOMTYPE_ID = RoomType.ROOMTYPE_ID
            WHERE 1 = 1
        ";

        $params = [];

        
        // Lọc theo trạng thái phòng
        if (!empty($filters['status'])) {
            $sql .= " AND Room.ROOM_STATUS = ?";
            $params[] = $filters['status'];
        }

        // Tìm kiếm theo số phòng
        if (!empty($filters['search'])) {
            $sql .= " AND Room.ROOM_NUMBER COLLATE utf8mb4_unicode_ci LIKE ?";
            $params[] = "%" . $filters['search'] . "%";
        }

        // Lọc theo loại phòng
        if (!empty($filters['roomtype'])) {
            $sql .= " AND Room.ROOM_ROOMTYPE_ID = ?";
            $params[] = $filters['roomtype'];
        }

        // Sắp xếp
        $sortMap = [
            "name_asc"  => "RoomType.ROOMTYPE_NAME ASC",
            "name_desc" => "RoomType.ROOMTYPE_NAME DESC",
        ];

        if (!empty($filters['sort-by']) && isset($sortMap[$filters['sort-by']])) {
            $sql .= " ORDER BY " . $sortMap[$filters['sort-by']];
        }

        // Phân trang
        $sql .= " LIMIT ? OFFSET ?";

        $offset = (int)($filters['offset'] ?? 0);
        $limit  = (int)($filters['limit'] ?? 10);

        $stmt = $this->connect()->prepare($sql);

        foreach ($params as $i => $value) {
            $stmt->bindValue($i + 1, $value);
        }

        $index = count($params) + 1;
        $stmt->bindValue($index, $limit, PDO::PARAM_INT);
        $stmt->bindValue($index + 1, $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function count(array $filters = [])
    {
        $sql = "
            SELECT COUNT(*)
            FROM Room
            INNER JOIN RoomType
                ON Room.ROOM_ROOMTYPE_ID = RoomType.ROOMTYPE_ID
            WHERE 1 = 1
        ";

        $params = [];

        // Trạng thái phòng
        if (!empty($filters['status'])) {
            $sql .= " AND Room.ROOM_STATUS = ?";
            $params[] = $filters['status'];
        }

        // Tìm theo số phòng
        if (!empty($filters['search'])) {
            $sql .= " AND Room.ROOM_NUMBER COLLATE utf8mb4_unicode_ci LIKE ?";
            $params[] = "%" . $filters['search'] . "%";
        }

        // Lọc theo loại phòng
        if (!empty($filters['roomtype'])) {
            $sql .= " AND Room.ROOM_ROOMTYPE_ID = ?";
            $params[] = $filters['roomtype'];
        }

        $stmt = $this->connect()->prepare($sql);
        $stmt->execute($params);

        return (int)$stmt->fetchColumn();
    }
    public function updateRoomStatus(array $ids, string $newStatus){
        if (empty($ids) || empty($newStatus)) {
            return false;
        }
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "UPDATE `Room`
                SET `ROOM_STATUS` = ?
                WHERE `ROOM_ID` IN ($placeholders)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindValue(1, $newStatus, PDO::PARAM_STR);
        // Bind từng id
        foreach ($ids as $index => $id) {
            $stmt->bindValue($index + 2, $id, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
   public function addRoom(array $data){
    $slug = toSlug($data["roomNumber"]);

    $sql = "
        INSERT INTO Room
        (
            ROOM_NUMBER,
            ROOM_ROOMTYPE_ID,
            ROOM_DESCRIPTION,
            ROOM_SLUG
        )
        VALUES
        (
            :number,
            :roomtype,
            :description,
            :slug
        )
    ";

    $stmt = $this->connect()->prepare($sql);

    $stmt->bindParam(":number", $data["roomNumber"]);
    $stmt->bindParam(":roomtype", $data["roomTypeId"], PDO::PARAM_INT);
    $stmt->bindParam(":description", $data["description"]);
    $stmt->bindParam(":slug", $slug);
    return $stmt->execute();
}
    public function getRoomById(int $id){
        $sql = "SELECT * FROM Room  INNER JOIN RoomType
                ON Room.ROOM_ROOMTYPE_ID = RoomType.ROOMTYPE_ID
                WHERE ROOM_ID = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
public function getRoomByNumber(int $number){
        $sql = "SELECT * FROM Room  INNER JOIN RoomType
                ON Room.ROOM_ROOMTYPE_ID = RoomType.ROOMTYPE_ID
                WHERE ROOM_NUMBER = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindValue(1, $number, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function updateRoom($id, $data){
        $slug = toSlug($data['number']); 
        $sql = "UPDATE Room
                SET
                    ROOM_SLUG = :slug,
                    ROOM_NUMBER = :number,
                    ROOM_ROOMTYPE_ID = :roomtypeid,
                    ROOM_DESCRIPTION = :desc";
        $sql .= " WHERE ROOM_ID=:id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':number', $data['number']);
        $stmt->bindParam(':roomtypeid', $data['room-roomtype']);
        $stmt->bindParam(':desc', $data['description']);
        $stmt->bindParam(':slug', $slug);
        $stmt->bindParam(":id", $id);
        return $stmt->execute(); 
    }
    public function deleteRoom(array $ids){   
        if (empty($ids)) {
            return false;
        }
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "
            DELETE FROM Room
            WHERE ROOM_ID IN ($placeholders)
        ";
        $stmt = $this->connect()->prepare($sql);
        foreach ($ids as $index => $id) {
            $stmt->bindValue($index + 1, $id, PDO::PARAM_INT);
        }
        return $stmt->execute();
    }
}
