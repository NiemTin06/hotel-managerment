<?php

class ClientRoomController extends Controller {
    public function index(){
        $data = [
            'title' => 'Danh sách loại phòng',
            'description' => 'Chọn loại phòng và thời gian lưu trú phù hợp.',
            'view_content' => 'pages/room/index',
            'page_style' => 'room',
            'page_script' => 'room',
            'link' => 'rooms'
        ];

        $this->view('client/layout/main_layout', $data);
        exit();
    }

    public function getData(){
        try {
            // chuẩn hoá và kiểm tra ngày nhận trả
            [$checkin, $checkout, $checkedDate] = $this->resolveDates(
                trim($_GET['checkin'] ?? ''), trim($_GET['checkout'] ?? '')
            );
            // gom các điều kiện lọc vào model
            $filters = [
                'roomTypeId' => (int)($_GET['room-type'] ?? 0),
                'sortBy' => trim($_GET['sort-by'] ?? ''),
                'checkin' => $checkin,
                'checkout' => $checkout
            ];

            $model = $this->model('clientrooms');

            $this->json([
                'success' => true,
                'room_types' => $model->getRoomTypes($filters),
                'room_type_options' => $model->getActiveRoomTypes(),
                'checked_date' => $checkedDate,
                'checkin' => $checkin,
                'checkout' => $checkout
            ]);
        } catch (InvalidArgumentException $e) {
            $this->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        } catch (Throwable $e) {
            $this->json([
                'success' => false,
                'message' => 'Không thể tải danh sách loại phòng.'
            ]);
        }
    }
    // Xử lý ngày nhận và ngày trả;
private function resolveDates(string $checkin, string $checkout): array {
    // Chưa chọn ngày thì dùng ngày tạm để tải danh sách phòng
    if ($checkin === '' && $checkout === '') {
        $checkin = date('Y-m-d');
        $checkout = date('Y-m-d', strtotime('+1 day'));

        // false: chưa kiểm tra phòng trống theo ngày
        return [$checkin, $checkout, false];
    }

    if ($checkin === '' || $checkout === '') {
        throw new InvalidArgumentException('Vui lòng chọn đầy đủ ngày nhận và ngày trả.');
    }

    // Kiểm tra định dạng ngày
    if (!$this->isDate($checkin) || !$this->isDate($checkout)) {
        throw new InvalidArgumentException('Ngày nhận hoặc ngày trả không hợp lệ.');
    }

    if ($checkin < date('Y-m-d')) {
        throw new InvalidArgumentException('Ngày nhận phòng không được nhỏ hơn ngày hiện tại.');
    }

    if ($checkout <= $checkin) {
        throw new InvalidArgumentException('Ngày trả phải sau ngày nhận phòng.');
    }

    return [$checkin, $checkout, true];
}

    private function isDate(string $date): bool {
        $value = DateTime::createFromFormat('!Y-m-d', $date);
        return $value && $value->format('Y-m-d') === $date;
    }

    private function json(array $data): void {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit();
    }
}
