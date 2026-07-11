<?php
require_once 'app/helpers/upload.helper.php';
class RoomTypeController extends Controller {
    public function index(){
        $data = [
            'title' => 'Danh sách loại phòng khách sạn',
            'description' => 'Hệ thống quản lý đặt phòng khách sạn thông minh.',
            'view_content' => 'pages/room-type/index' ,
            'page_script' => 'room-type',
            'dir-view' => 'room-type',
            'link' => 'rooms-type'
        ];
        $this->view('admin/layout/main_layout', $data);
        exit();
    }
    public function getRoomTypeData() {
        $filter = [
            'status' => $_GET['status'] ?? '',
            'room-type' => $_GET['room-type'] ?? '',
            'sort-by' => $_GET['sort-by'] ?? '',
            'search' => $_GET['search'] ?? ''
        ];
        $roomsTypeModel = $this->model('roomstype');
        $roomsType = $roomsTypeModel->getAllRoomsType($filter);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($roomsType);
        exit();
    }
    
    public function create() {
    try{
         $roomType = [
            "typeName"    => isset($_POST['roomtype-name']) ? trim($_POST['roomtype-name']) : '',
            "description" => isset($_POST['roomtype-description']) ? trim($_POST['roomtype-description']) : '',
            "price"       => isset($_POST['roomtype-price']) ? trim($_POST['roomtype-price']) : '',
            "discount"    => isset($_POST['roomtype-discount']) ? trim($_POST['roomtype-discount']) : '',
            "thumbnail"   => null,
        ];

        if (empty($roomType['typeName']) || empty($roomType['price'])) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng nhập đầy đủ tên và giá loại phòng.']);
            exit();
        }
       
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] !== UPLOAD_ERR_NO_FILE) {
            
            $uploadResult = UploadHelper::uploadImage($_FILES['thumbnail'], 'public/uploads/roomtypes/');
            if (!$uploadResult['success']) {
                echo json_encode(['success' => false, 'message' => $uploadResult['message']]);
                exit();
            }
            $roomType['thumbnail'] = $uploadResult['fileName'];
        }

        $model = $this->model('roomstype');
        $result = $model->addRoomType($roomType);

        echo json_encode([
            'success' => (bool)$result,
            'message' => $result ? 'Thêm loại ' . $roomType["typeName"] . ' phòng thành công.' : 'Thêm loại ' . $roomType["typeName"] . ' phòng thất bại.'
        ]);
        exit();
    } catch (\Throwable $e) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
    exit();
}
}
}