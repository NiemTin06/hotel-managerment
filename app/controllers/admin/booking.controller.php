<?php
class BookingController extends Controller {
    public function index() {
        $data = [
            'title' => 'Quản lý Đặt phòng',
            'description' => 'Hệ thống tiếp nhận giữ chỗ và điều phối phòng khách sạn.',
            'view_content' => 'pages/booking/index',
            'page_script' => 'booking',
            'link' => 'bookings'
        ];
        $this->view('admin/layout/main_layout', $data);
        exit();
    }
    public function getBookingData() {
        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        $filter = [
            'search' => trim($_GET['search'] ?? ''),
            'status' => trim($_GET['status'] ?? ''),
            'roomtype' => trim($_GET['roomtype'] ?? ''),
            'sort-by' => trim($_GET['sort-by'] ?? ''),
            'page' => $page, 'limit' => $limit, 'offset' => $offset
        ];

        $model = $this->model('bookings');
        $bookings = $model->getAllBookings($filter);
        $totalItem = $model->count($filter);
        $totalPage = ceil($totalItem / $limit);

        // Kéo thêm danh sách Khách hàng và Loại phòng để đổ vào Thẻ Select của Modal
        $customerModel = $this->model('customer');
        $roomTypeModel = $this->model('roomstype');

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            "record" => $bookings,
            "record-customers" => $customerModel->getAllCustomers(['limit' => 100]),
            "record-rooms-type" => $roomTypeModel->getAllRoomsType(['status' => 'Active', 'limit' => 50]),
            "pagination" => [
                "page" => $page, "limit" => $limit, "totalItem" => $totalItem, "totalPage" => $totalPage
            ]
        ]);
        exit();
    }
    public function create() {
        try {
            $data = [
                "customerId" => (int)($_POST['booking-customer'] ?? 0),
                "roomTypeId" => (int)($_POST['booking-roomtype'] ?? 0),
                "checkin"    => trim($_POST['booking-checkin'] ?? ''),
                "checkout"   => trim($_POST['booking-checkout'] ?? ''),
                "totalPrice" => (float)($_POST['booking-price'] ?? 0),
               "status"     => trim($_POST['booking-status'] ?? 'Pending'),
                "note"       => trim($_POST['booking-note'] ?? '')
            ];

            if ($data['customerId'] <= 0 || $data['roomTypeId'] <= 0 || empty($data['checkin']) || empty($data['checkout'])) {
                echo json_encode(["success" => false, "message" => "Vui lòng chọn đầy đủ Khách hàng, Loại phòng và Ngày ở."]);
                exit();
            }

            if (strtotime($data['checkout']) <= strtotime($data['checkin'])) {
                echo json_encode(["success" => false, "message" => "Ngày Check-out phải sau Ngày Check-in!"]);
                exit();
            }

            $model = $this->model("bookings");
            $result = $model->addBooking($data);
            echo json_encode([
                "success" => (bool)$result,
                "message" => $result ? "Tạo đơn đặt phòng thành công!" : "Tạo đơn thất bại."
            ]);
            exit();
        } catch (\Throwable $e) {
            echo json_encode(["success" => false, "message" => "Lỗi hệ thống: " . $e->getMessage()]);
            exit();
        }
    }
    public function getOne($id) {
        $model = $this->model('bookings');
        $booking = $model->getBookingById((int)$id);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($booking);
        exit();
    }
    // API Lấy danh sách phòng vật lý đang trống để Lễ tân chọn lúc Check-in
    public function getAvailableRooms($roomTypeId) {
        $model = $this->model('bookings');
        $rooms = $model->getAvailableRoomsByTypeId((int)$roomTypeId);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(["success" => true, "rooms" => $rooms]);
        exit();
    }
    // API Xử lý Check-in
    public function checkIn($id) {
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $roomId = (int)($input['roomId'] ?? 0);
        
        if ($roomId <= 0) {
            echo json_encode(["success" => false, "message" => "Vui lòng chọn số phòng để Check-in!"]);
            exit();
        }

        $model = $this->model('bookings');
        $result = $model->checkInBooking((int)$id, $roomId);
        echo json_encode([
            "success" => $result,
            "message" => $result ? "Check-in gán phòng thành công!" : "Check-in thất bại."
        ]);
        exit();
    }
    // API Xử lý Check-out
    public function checkOut($id) {
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $roomId = (int)($input['roomId'] ?? 0);

        $model = $this->model('bookings');
        $result = $model->checkOutBooking((int)$id, $roomId);
        echo json_encode([
            "success" => $result,
            "message" => $result ? "Hoàn tất trả phòng Check-out!" : "Trả phòng thất bại."
        ]);
        exit();
    }
    public function delete() {
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $model = $this->model("bookings");
        $ids = $input['ids'] ?? [];
        $result = $model->deleteBooking($ids);
        echo json_encode([
            "success" => (bool)$result,
            "message" => $result ? "Xóa đơn đặt phòng thành công." : "Xóa đơn thất bại."
        ]);
        exit();
    }
    public function update($id) {
    try {
        $data = [
            "customerId" => (int)($_POST['booking-customer'] ?? 0),
            "roomTypeId" => (int)($_POST['booking-roomtype'] ?? 0),
            "checkin"    => trim($_POST['booking-checkin'] ?? ''),
            "checkout"   => trim($_POST['booking-checkout'] ?? ''),
            "totalPrice" => (float)($_POST['booking-price'] ?? 0),
            "status"     => trim($_POST['booking-status'] ?? 'Pending'),
            "note"       => trim($_POST['booking-note'] ?? '')
        ];

        if ($data['customerId'] <= 0 || $data['roomTypeId'] <= 0 || empty($data['checkin']) || empty($data['checkout'])) {
            echo json_encode(["success" => false, "message" => "Vui lòng điền đầy đủ thông tin!"]);
            exit();
        }

        if (strtotime($data['checkout']) <= strtotime($data['checkin'])) {
            echo json_encode(["success" => false, "message" => "Ngày Check-out phải sau Ngày Check-in!"]);
            exit();
        }

        $model = $this->model("bookings");
        $result = $model->updateBooking((int)$id, $data);
        echo json_encode([
            "success" => (bool)$result,
            "message" => $result ? "Cập nhật đơn đặt phòng thành công!" : "Cập nhật đơn thất bại."
        ]);
        exit();
        } catch (\Throwable $e) {
            echo json_encode(["success" => false, "message" => "Lỗi hệ thống: " . $e->getMessage()]);
            exit();
        }
    }

    
}