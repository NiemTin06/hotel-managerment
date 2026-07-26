<?php

class ClientBookingController extends Controller {
    public function index() {
        // luu query sau khi login truy cap lai
        $queryString = $_SERVER['QUERY_STRING'] ?? '';
        $redirectPath = '/bookings' . ($queryString !== '' ? '?' . $queryString : '');
        requireCustomerLogin($redirectPath);        

        if (empty($_SESSION['customer_id'])) {
            unset(
                $_SESSION['user_id'],
                $_SESSION['username'],
                $_SESSION['user_role'],
                $_SESSION['last_activity'],
                $_SESSION['customer_id'],
                $_SESSION['customer_fullname'],
                $_SESSION['customer_email'],
                $_SESSION['customer_phone'],
                $_SESSION['customer_cccd']
            );

            $_SESSION['auth_error'] = 'Tài khoản chưa có hồ sơ khách hàng. Vui lòng đăng nhập lại.';
            redirect('/login');
        }

        $data = [
            'title' => 'Đặt phòng',
            'description' => 'Kiểm tra thông tin và gửi yêu cầu đặt phòng.',
            'view_content' => 'pages/booking/index',
            'page_style' => 'booking',
            'page_script' => 'booking',
            'link' => 'bookings',
            'customer' => [
                'fullname' => $_SESSION['customer_fullname'] ?? '',
                'phone' => $_SESSION['customer_phone'] ?? '',
                'email' => $_SESSION['customer_email'] ?? '',
                'cccd' => $_SESSION['customer_cccd'] ?? ''
            ]
        ];
        $this->view('client/layout/main_layout', $data);
        exit();
    }
    // API lấy loại phòng đã chọn và kiểm tra số phòng còn trống theo ngày.
    public function getData(){
        requireCustomerLogin('/bookings');

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
    // kiểm tra dữ liệu, tính tổng tiền và tạo booking.
    public function process() {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json([
                'success' => false,
                'message' => 'Phương thức không hợp lệ.'
            ]);
        }

        requireCustomerLogin();
        $customerId = (int)($_SESSION['customer_id'] ?? 0);
        $userId = (int)($_SESSION['user_id'] ?? 0);

        if ($customerId <= 0 || $userId <= 0) {
            $this->json([
                'success' => false,
                'message' => 'Không tìm thấy hồ sơ khách hàng.'
            ]);
        }

        try {
            $roomTypeId = (int)($_POST['room-type-id'] ?? 0);
            $note = trim($_POST['booking-note'] ?? '');

            [$checkin, $checkout] = $this->resolveDates(
                trim($_POST['booking-checkin'] ?? ''),
                trim($_POST['booking-checkout'] ?? '')
            );

            if ($roomTypeId <= 0) {
                throw new InvalidArgumentException('Vui lòng chọn loại phòng.');
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
                $customerId,
                $userId,
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
                'message' => 'Đặt phòng thành công.',
                'booking_id' => $bookingId,
                'total_price' => $totalPrice,
                'redirectUrl' => URLROOT . '/my-account'
            ]);
        } catch (InvalidArgumentException $e) {
            $this->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        } catch (RuntimeException $e) {
            if ($e->getMessage() === 'NO_AVAILABLE_ROOM') {
                $message = 'Phòng vừa được khách khác đặt. Vui lòng chọn ngày hoặc loại phòng khác.';
            } elseif ($e->getMessage() === 'INVALID_CUSTOMER') {
                $message = 'Không tìm thấy hồ sơ khách hàng của tài khoản.';
            } else {
                $message = 'Không thể tạo đơn đặt phòng.';
            }

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

    private function json(array $data, int $statusCode = 200): void {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit();
    }
}
