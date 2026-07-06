<?php 
class HomeController extends Controller {
    public function index(){
        // Chuẩn bị dữ liệu hiển thị (ví dụ tên khách sạn)
        $data = [
            'title' => 'Chào Mừng Đến Với Hotel Manager',
            'description' => 'Hệ thống quản lý đặt phòng khách sạn thông minh.'
        ];

        // Nạp file giao diện trang chủ: app/views/admin/pages/home/index.php
        $this->view('admin/pages/home/index', $data);

    }
}