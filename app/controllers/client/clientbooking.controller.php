<?php

class ClientBookingController extends Controller{
    public function index(){
        $data = [
            'title' => 'Đặt phòng',
            'description' => 'Nhập thông tin khách hàng để gửi yêu cầu đặt phòng.',
            'view_content' => 'pages/booking/index',
            'page_style' => 'booking',
            'page_script' => 'booking',
            'link' => 'bookings'
        ];
        $this->view('client/layout/main_layout', $data);
        exit();
    }

    public function getData(){
        try {
            $roomTypeId = (int)($_GET['room_type_id'] ?? 0);
            if ($roomTypeId <= 0) {
                $this->json([
                    'success' => true,
                    'selected_room_type' => null,
                    'checkin' => '',
                    'checkout' => ''
                ]);
            }

            [$checkin, $checkout] = $this->resolveDates(
                trim($_GET['checkin'] ?? ''),
                trim($_GET['checkout'] ?? '')
            );

            $model = $this->model('clientrooms');
            $selected = $model->getRoomTypeById($roomTypeId, $checkin, $checkout);

            if (!$selected) {
                $this->json([
                    'success' => true,
                    'selected_room_type' => null,
                    'checkin' => '',
                    'checkout' => ''
                ]);
            }

            $this->json([
                'success' => true,
                'selected_room_type' => $selected,
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
                'message' => 'Không thể tải thông tin đặt phòng.'
            ]);
        }
    }

    public function process(){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json([
                'success' => false,
                'message' => 'Phương thức không hợp lệ.'
            ]);
        }

        try {
            $roomTypeId = (int)($_POST['room-type-id'] ?? 0);
            $fullname = trim($_POST['customer-fullname'] ?? '');
            $phone = preg_replace('/[\s.\-]+/', '', trim($_POST['customer-phone'] ?? ''));
            $email = trim($_POST['customer-email'] ?? '');
            $cccd = preg_replace('/\s+/','', trim($_POST['customer-cccd'] ?? ''));
            $note = trim($_POST['booking-note'] ?? '');

            [$checkin, $checkout] = $this->resolveDates(trim($_POST['booking-checkin'] ?? ''), trim($_POST['booking-checkout'] ?? ''));

            if ($roomTypeId <= 0) {
                throw new InvalidArgumentException('Vui lòng chọn loại phòng.');
            }
            if ($fullname === '') {
                throw new InvalidArgumentException('Vui lòng nhập họ tên khách hàng.');
            }
            if (!preg_match('/^0[0-9]{9}$/', $phone)) {
                throw new InvalidArgumentException('Số điện thoại phải bắt đầu từ 0 và có 10 số ');
            }
            if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new InvalidArgumentException('Email không hợp lệ.');
            }
            if (!preg_match('/^[0-9]{12}$/', $cccd)) {
                throw new InvalidArgumentException('CCCD phải gồm đúng 12 chữ số.');
            }

            $roomsModel = $this->model('clientrooms');
            $roomType = $roomsModel->getRoomTypeById($roomTypeId, $checkin, $checkout);

            if (!$roomType) {
                throw new InvalidArgumentException('Loại phòng không tồn tại hoặc đã ngừng hoạt động.');
            }
            if ((int)$roomType['AVAILABLE_ROOM_COUNT'] <= 0) {
                throw new InvalidArgumentException('Loại phòng này đã hết phòng trong khoảng ngày đã chọn.');
            }

            $checkinDate = new DateTime($checkin);
            $checkoutDate = new DateTime($checkout);
            $dateDifference = $checkinDate->diff($checkoutDate);
            $nightCount = $dateDifference->days;
            $totalPrice = $nightCount * (float)$roomType['PRICE_AFTER_DISCOUNT'];

            $model = $this->model('clientbookings');
            $bookingId = $model->createBooking(
                [
                    'fullname' => $fullname,
                    'phone' => $phone,
                    'email' => $email,
                    'cccd' => $cccd
                ],
                [
                    'roomTypeId' => $roomTypeId,
                    'checkin' => $checkin,
                    'checkout' => $checkout,
                    'totalPrice' => $totalPrice,
                    'note' => $note
                ]
            );

            $this->json([
                'success' => true,
                'message' => 'Đặt phòng thành công. Vui lòng lưu mã đơn để tra cứu.',
                'booking_id' => $bookingId,
                'guest_phone' => $phone,
                'total_price' => $totalPrice
            ]);
        } catch (InvalidArgumentException $e) {
            $this->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        } catch (RuntimeException $e) {
            $message = $e->getMessage() === 'NO_AVAILABLE_ROOM'
                ? 'Phòng vừa được khách khác đặt. Vui lòng chọn ngày hoặc loại phòng khác.'
                : 'Không thể tạo đơn đặt phòng.';

            $this->json([
                'success' => false,
                'message' => $message
            ]);
        } catch (Throwable $e) {
            $this->json([
                'success' => false,
                'message' => 'Không thể tạo đơn đặt phòng.'
            ]);
        }
    }

    private function resolveDates(string $checkin, string $checkout): array {
        if ($checkin === '' || $checkout === '') {
            throw new InvalidArgumentException('Vui lòng chọn đầy đủ ngày nhận và ngày trả.');
        }

        if (!$this->isDate($checkin) || !$this->isDate($checkout)) {
            throw new InvalidArgumentException('Ngày nhận hoặc ngày trả không hợp lệ.');
        }

        if ($checkin < date('Y-m-d')) {
            throw new InvalidArgumentException('Ngày nhận phòng không được nhỏ hơn ngày hiện tại.');
        }

        if ($checkout <= $checkin) {
            throw new InvalidArgumentException('Ngày trả phải sau ngày nhận phòng.');
        }
        return [$checkin, $checkout];
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
