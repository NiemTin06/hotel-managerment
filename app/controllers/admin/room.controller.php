<?php
require_once 'app/helpers/upload.helper.php';
class RoomController extends Controller
{
    public function index(){

        $data = [
            'title' => 'Danh sách phòng khách sạn',
            'description' => 'Hệ thống quản lý đặt phòng khách sạn thông minh.',
            'view_content' => 'pages/room/index',
            'page_script' => 'room',
            'link' => 'rooms'
        ];
        $this->view('admin/layout/main_layout', $data);
        exit();
    }
    public function getRoomData(){
        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 5;
        $offset = ($page - 1) * $limit;
        $filter = [
            'search' => trim($_GET['search'] ?? ''),
            'sort-by' => trim($_GET['sort-by'] ?? ''),
            'status' => trim($_GET['status'] ?? ''),
            'roomtype' => trim($_GET['room-type'] ?? ''),
            'page' => $page,
            'limit' => $limit,
            'offset' => $offset
        ];
        $roomsModel = $this->model('rooms');
        $rooms = $roomsModel->getAllRooms($filter);

        $roomsTypeModel = $this->model('roomstype');
        $roomType = $roomsTypeModel->getAllRoomsType();
        
        $totalItem = $roomsModel->count($filter);
        $totalPage = ceil($totalItem / $limit);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            "record" => $rooms,
            "record-rooms-type" => $roomType,
            "pagination" => [
                "page" => $page,
                "limit" => $limit,
                "totalItem" => $totalItem,
                "totalPage" => $totalPage
            ]
        ]);
        exit();
    }
    public function changeMulti(){
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $ids = $input['ids'] ?? '';
        $status = $input['status'] ?? '';

        if (!empty($ids) && !empty($status)) {
            $idsArray = explode(',', $ids);
            $roomsModel = $this->model('rooms');
            $result = $roomsModel->updateRoomStatus($idsArray, $status);
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Cập nhật trạng thái loại phòng thành công.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Cập nhật trạng thái loại phòng thất bại.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Vui lòng cung cấp danh sách ID và trạng thái mới.']);
        }
        exit();
    }
    public function create(){
        try {

            $room = [
                "roomNumber" => trim($_POST['room-number'] ?? ''),
                "roomTypeId" => (int)($_POST['room-roomtype'] ?? 0),
                "description" => trim($_POST['roomtype-description'] ?? ''),
                "thumbnail" => null
            ];

            if (
                empty($room["roomNumber"]) ||
                $room["roomTypeId"] <= 0
            ) {
                echo json_encode([
                    "success" => false,
                    "message" => "Vui lòng nhập đầy đủ thông tin."
                ]);
                exit();
            }
            $model = $this->model("rooms");
            if ($model->getRoomByNumber($room["roomNumber"])){
                echo json_encode([
                    "success" => false,
                    "message" => "Số phòng đã tồn tại."
                ]);
                exit();
            };
            $result = $model->addRoom($room);
            echo json_encode([
                "success" => (bool)$result,
                "message" => $result
                    ? "Thêm phòng thành công."
                    : "Thêm phòng thất bại."
            ]);

            exit();

        } catch (\Throwable $e) {

            echo json_encode([
                "success" => false,
                "message" =>"Thêm phòng thất bại."
            ]);

            exit();
        }
    }
    public function getRoomOne($id){   
        $model = $this->model('rooms');
        $room = $model->getRoomById($id);
        header('Content-Type: application/json');
        echo json_encode($room);
        exit();
    }
    public function update($id){
        try {

            $room = [
                "number"    => isset($_POST['room-number']) ? trim($_POST['room-number']) : '',
                "description" => isset($_POST['room-description']) ? trim($_POST['room-description']) : '',
                "room-roomtype" => isset($_POST['room-roomtype']) ? trim($_POST['room-roomtype']) : ''
            ];
            $model = $this->model('rooms');
            $result = $model->updateRoom($id, $room);
            echo json_encode([
                'success' => (bool)$result,
                'message' => $result ? 'Cập nhật phòng ' . $room["number"] . ' thành công.' : 'Cập nhật phòng ' . $room["number"] . ' thất bại.'
            ]);
            exit();
        } catch (\Throwable $e) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
            exit();
        }
    }  
    public function delete(){
         $input = json_decode(file_get_contents('php://input'), true) ?? [];
        try {
            $model = $this->model("rooms");
            $ids = $input['ids'] ?? [];
            $result = $model->deleteRoom($ids);
            echo json_encode([
                "success" => $result,
                "message" => $result
                    ? "Xóa loại phòng thành công."
                    : "Xóa loại phòng thất bại."
            ]);
            exit();
        } catch (\Throwable $e) {
            echo json_encode([
                "success" => false,
                "message" => $e->getMessage()
            ]);
            exit();
        }
    }
 }
