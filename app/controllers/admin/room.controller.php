<?php 
class RoomController extends Controller {
    public function index(){
        // Chuẩn bị dữ liệu hiển thị (ví dụ tên khách sạn)
        $data = [
            'title' => 'Danh sách phòng khách sạn',
            'description' => 'Hệ thống quản lý đặt phòng khách sạn thông minh.',
            'view_content' => 'pages/room/index' ,
            'page_script' => 'room',
            'dir-view' => 'rooms',
            'link' => 'rooms'
        ];
        $this->view('admin/layout/main_layout', $data);
        exit();
    }
    public function getRoomData() {
        $filter = [
            'status' => $_GET['status'] ?? '',
            'room-type' => $_GET['room-type'] ?? '',
            'sort-by' => $_GET['sort-by'] ?? '',
            'search' => $_GET['search'] ?? ''
        ];
        $roomsModel = $this->model('rooms');
        $rooms = $roomsModel->getAllRooms($filter);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($rooms);
        exit();
    }

    public function changeMulti() {
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $ids = $input['ids'] ?? '';
        $status = $input['status'] ?? '';

        if (!empty($ids) && !empty($status)) {
            $idsArray = explode(',', $ids);
            $roomsModel = $this->model('rooms');
            $result = $roomsModel->updateRoomStatus($idsArray, $status);
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Cập nhật trạng thái phòng thành công.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Cập nhật trạng thái phòng thất bại.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Vui lòng cung cấp danh sách ID và trạng thái mới.']);
        }
        exit();
    }
    public function create(){
        // Chuẩn bị dữ liệu hiển thị (ví dụ tên khách sạn)
        $data = [
            'title' => 'Thêm phòng mới',
            'description' => 'Hệ thống quản lý đặt phòng khách sạn thông minh.',
            'view_content' => 'pages/room/create' ,
            'page_script' => 'room'
        ];
        $this->view('admin/layout/main_layout', $data);
        exit();
    }
}