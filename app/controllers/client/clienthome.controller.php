<?php

class ClientHomeController extends Controller{
    public function index(){
        $data = [
            'title' => 'Trang chủ',
            'description' => 'Khám phá không gian nghỉ dưỡng và các loại phòng nổi bật.',
            'view_content' => 'pages/home/index',
            'page_style' => 'home',
            'page_script' => 'home',
            'link' => 'home'
        ];
        $this->view('client/layout/main_layout', $data);
        exit();
    }

    public function getData(){
        try {
            $model = $this->model('clientrooms');
            $this->json([
                'success' => true,
                'room_types' => $model->getFeaturedRoomTypes()
            ]);
        } catch (Throwable $e) {
            $this->json([
                'success' => false,
                'message' => 'Không thể tải các loại phòng nổi bật.'
            ]);
        }
    }

    private function json(array $data): void {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit();
    }
}
