<?php 
class CustomerController extends Controller {
    public function index(){
        // Chuẩn bị dữ liệu hiển thị (ví dụ tên khách sạn)
          $data = [
            'title' => 'Danh sách khách hàng khách sạn',
            'description' => 'Hệ thống quản lý đặt phòng khách sạn thông minh.',
            'view_content' => 'pages/customer/index',
            'page_script' => 'customer',
            'link' => 'customers'
        ];

        // Nạp file giao diện trang chủ: app/views/admin/pages/home/index.php
        $this->view('admin/layout/main_layout', $data);
        exit();


    }
}