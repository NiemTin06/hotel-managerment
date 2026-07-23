<?php 
class DashboardController extends Controller {
    public function index(){
        // Chuẩn bị dữ liệu hiển thị (ví dụ tên khách sạn)
        $data = [
            'title' => 'Chào Mừng Đến Với Hotel Manager',
            'description' => 'Hệ thống quản lý đặt phòng khách sạn thông minh.','view_content' => 'pages/dashboard/index',
            'page_script' => 'dashboard',
            'link' => 'dashboard'
        ];

        // Nạp file giao diện trang chủ: app/views/admin/pages/home/index.php
        $this->view('admin/layout/main_layout', $data);

    }
}