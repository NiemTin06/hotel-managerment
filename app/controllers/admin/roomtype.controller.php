<?php
require_once 'app/helpers/upload.helper.php';
class RoomTypeController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Danh sách loại phòng khách sạn',
            'description' => 'Hệ thống quản lý đặt phòng khách sạn thông minh.',
            'view_content' => 'pages/room-type/index',
            'page_script' => 'room-type',
            'link' => 'rooms-type'
        ];
        $this->view('admin/layout/main_layout', $data);
        exit();
    }
    public function getRoomTypeData()
    {
        $filter = [
            'search' => trim($_GET['search'] ?? ''),
            'sort-by' => trim($_GET['sort-by'] ?? ''),
            'status' => trim($_GET['status'] ?? ''),
            'roomtype-bed-type' => trim($_GET['roomtype-bed-type'] ?? ''),
            'roomtype-max-guests' => (int)($_GET['roomtype-max-guests'] ?? 0),
        ];
        $roomsTypeModel = $this->model('roomstype');
        $roomsType = $roomsTypeModel->getAllRoomsType($filter);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($roomsType);
        exit();
    }
    public function changeMulti()
    {
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $ids = $input['ids'] ?? '';
        $status = $input['status'] ?? '';

        if (!empty($ids) && !empty($status)) {
            $idsArray = explode(',', $ids);
            $roomsModel = $this->model('roomstype');
            $result = $roomsModel->updateRoomTypeStatus($idsArray, $status);
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
    public function create()
    {
        try {

            $roomType = [
                "typeName"    => isset($_POST['roomtype-name']) ? trim($_POST['roomtype-name']) : '',
                "description" => isset($_POST['roomtype-description']) ? trim($_POST['roomtype-description']) : '',
                "price"       => isset($_POST['roomtype-price']) ? trim($_POST['roomtype-price']) : '',
                "discount"    => isset($_POST['roomtype-discount']) ? trim($_POST['roomtype-discount']) : '',
                "maxGuests"   => isset($_POST['roomtype-max-guests']) ? (int)$_POST['roomtype-max-guests'] : 0,
                "bedType"     => isset($_POST['roomtype-bed-type']) ? trim($_POST['roomtype-bed-type']) : '',
                "thumbnail"   => null,
            ];

            if (
                empty($roomType['typeName']) ||
                empty($roomType['price']) ||
                $roomType['maxGuests'] <= 0 ||
                empty($roomType['bedType'])
            ) {
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
    public function getRoomTypeOne($id)
    {   
        // $id  = trim($_GET['id'] ?? '');
        $model = $this->model('roomstype');
        $roomType = $model->getRoomTypeById($id);
        header('Content-Type: application/json');
        echo json_encode($roomType);
        exit();
    }
    public function update($id)
    {
        try {

            $roomType = [
                "typeName"    => isset($_POST['roomtype-name']) ? trim($_POST['roomtype-name']) : '',
                "description" => isset($_POST['roomtype-description']) ? trim($_POST['roomtype-description']) : '',
                "price"       => isset($_POST['roomtype-price']) ? trim($_POST['roomtype-price']) : '',
                "discount"    => isset($_POST['roomtype-discount']) ? trim($_POST['roomtype-discount']) : '',
                "maxGuests"   => isset($_POST['roomtype-max-guests']) ? (int)$_POST['roomtype-max-guests'] : 0,
                "bedType"     => isset($_POST['roomtype-bed-type']) ? trim($_POST['roomtype-bed-type']) : '',
                "thumbnail"   => null,
            ];

            if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] !== UPLOAD_ERR_NO_FILE) {
                $uploadResult = UploadHelper::uploadImage($_FILES['thumbnail'], 'public/uploads/roomtypes/');
                if (!$uploadResult['success']) {
                    echo json_encode(['success' => false, 'message' => $uploadResult['message']]);
                    exit();
                }
                $roomType['thumbnail'] = $uploadResult['fileName'];
            }

            $model = $this->model('roomstype');
            $result = $model->updateRoomType($id, $roomType);
            echo json_encode([
                'success' => (bool)$result,
                'message' => $result ? 'Cập nhật ' . $roomType["typeName"] . ' loại phòng thành công.' : 'Cập nhật ' . $roomType["typeName"] . ' loại phòng thất bại.'
            ]);
            exit();
        } catch (\Throwable $e) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
            exit();
        }
    }
    
}
