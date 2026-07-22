<?php
class CustomerController extends Controller {
    public function index() {
        $data = [
            'title' => 'Quản lý Khách hàng',
            'description' => 'Hệ thống lưu trữ hồ sơ và lịch sử chi tiêu khách hàng.',
            'view_content' => 'pages/customer/index',
            'page_script' => 'customer',
            'link' => 'customers'
        ];
        $this->view('admin/layout/main_layout', $data);
        exit();
    }

    public function getCustomerData() {
        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        $filter = [
            'search' => trim($_GET['search'] ?? ''),
            'sort-by' => trim($_GET['sort-by'] ?? ''),
            'page' => $page,
            'limit' => $limit,
            'offset' => $offset
        ];

        $model = $this->model('customer');
        $customers = $model->getAllCustomers($filter);
        $totalItem = $model->count($filter);
        $totalPage = ceil($totalItem / $limit);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            "record" => $customers,
            "pagination" => [
                "page" => $page,
                "limit" => $limit,
                "totalItem" => $totalItem,
                "totalPage" => $totalPage
            ]
        ]);
        exit();
    }

    public function create() {
        try {
            $data = [
                "fullname" => trim($_POST['customer-fullname'] ?? ''),
                "phone"    => trim($_POST['customer-phone'] ?? ''),
                "email"    => trim($_POST['customer-email'] ?? ''),
                "cccd"     => trim($_POST['customer-cccd'] ?? '')
            ];

            if (empty($data["fullname"]) || empty($data["phone"])) {
                echo json_encode(["success" => false, "message" => "Vui lòng nhập Họ tên và Số điện thoại."]);
                exit();
            }

            $model = $this->model("customer");
            if ($model->getCustomerByPhone($data["phone"])) {
                echo json_encode(["success" => false, "message" => "Số điện thoại này đã tồn tại trong hệ thống."]);
                exit();
            }

            $result = $model->addCustomer($data);
            echo json_encode([
                "success" => (bool)$result,
                "message" => $result ? "Thêm khách hàng thành công." : "Thêm khách hàng thất bại."
            ]);
            exit();
        } catch (\Throwable $e) {
            echo json_encode(["success" => false, "message" => "Lỗi hệ thống: " . $e->getMessage()]);
            exit();
        }
    }

    public function getOne($id) {
        $model = $this->model('customer');
        $customer = $model->getCustomerById((int)$id);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($customer);
        exit();
    }

    public function update($id) {
        try {
            $data = [
                "fullname" => trim($_POST['customer-fullname'] ?? ''),
                "phone"    => trim($_POST['customer-phone'] ?? ''),
                "email"    => trim($_POST['customer-email'] ?? ''),
                "cccd"     => trim($_POST['customer-cccd'] ?? '')
            ];

            $model = $this->model("customer");
            $existing = $model->getCustomerByPhone($data["phone"]);
            // Kiểm tra trùng SĐT nhưng bỏ qua nếu SĐT đó là của chính khách hàng đang sửa
            if ($existing && $existing['CUSTOMER_ID'] != $id) {
                echo json_encode(["success" => false, "message" => "Số điện thoại đã bị trùng với khách hàng khác."]);
                exit();
            }

            $result = $model->updateCustomer((int)$id, $data);
            echo json_encode([
                "success" => (bool)$result,
                "message" => $result ? "Cập nhật thành công." : "Cập nhật thất bại."
            ]);
            exit();
        } catch (\Throwable $e) {
            echo json_encode(["success" => false, "message" => "Lỗi hệ thống: " . $e->getMessage()]);
            exit();
        }
    }

    public function delete() {
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        try {
            $model = $this->model("customer");
            $ids = $input['ids'] ?? [];
            $result = $model->deleteCustomer($ids);
            echo json_encode([
                "success" => (bool)$result,
                "message" => $result ? "Xóa khách hàng thành công." : "Xóa thất bại (Khách hàng có thể đang có đơn đặt phòng)."
            ]);
            exit();
        } catch (\Throwable $e) {
            echo json_encode(["success" => false, "message" => "Không thể xóa khách hàng đã có dữ liệu đặt phòng."]);
            exit();
        }
    }
}