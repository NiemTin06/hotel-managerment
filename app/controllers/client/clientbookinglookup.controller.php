<?php

class ClientBookingLookupController extends Controller{
    public function index(){
        $data = [
            'title' => 'Tra cứu đơn',
            'description' => 'Nhập mã đơn và số điện thoại đã dùng khi đặt phòng.',
            'view_content' => 'pages/booking-lookup/index',
            'page_style' => 'booking-lookup',
            'page_script' => 'booking-lookup',
            'link' => 'booking-lookup'
        ];
        $this->view('client/layout/main_layout', $data);
        exit();
    }

    public function search(){
        try {
            $bookingId = (int)($_POST['booking-id'] ?? 0);
            $phone = preg_replace('/[\s.\-]+/', '', trim($_POST['customer-phone'] ?? ''));

            if ($bookingId <= 0) {
                throw new InvalidArgumentException('Mã đơn không hợp lệ.');
            }

            if (!preg_match('/^0[0-9]{9}$/', $phone)) { 
                throw new InvalidArgumentException('Số điện thoại không hợp lệ.');
            }

            $model = $this->model('clientbookinglookup');
            $booking = $model->findBooking($bookingId, $phone);

            if (!$booking) {
                $this->json([
                    'success' => false,
                    'message' => 'Không tìm thấy đơn phù hợp với mã đơn và số điện thoại.'
                ]);
            }

            $this->json([
                'success' => true,
                'booking' => $booking
            ]);
        } catch (InvalidArgumentException $e) {
            $this->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        } catch (Throwable $e) {
            $this->json([
                'success' => false,
                'message' => 'Không thể tra cứu đơn.'
            ]);
        }
    }

    private function json(array $data): void
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit();
    }
}
