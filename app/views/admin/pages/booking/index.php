<?php
/** @var array $data */
$tableHeader = '
<tr>
    <th><input type="checkbox" checkbox-multi></th>
    <th>Mã đơn</th>
    <th>Khách hàng</th>
    <th>Loại & Số phòng</th>
    <th>Thời gian ở</th>
    <th>Tổng tiền</th>
    <th>Trạng thái</th>
    <th>Hành động</th>
</tr>
';
$btnCreateName = "Thêm đặt phòng mới";
$tbodyId = "booking-list";
$object = "Đơn đặt phòng";

$sortOptions = [
    "" => "Mặc định (Mới nhất)",
    "date_desc" => "Ngày tạo gần nhất",
    "checkin_asc" => "Sắp check-in",
    "price_desc" => "Giá trị đơn cao nhất"
];

$statusOptions = [
    "" => "Tất cả trạng thái",
    "Pending" => "Chờ xác nhận",
    "Confirmed" => "Đã xác nhận / Cọc",
    "CheckedIn" => "Đang ở (Checked-in)",
    "CheckedOut" => "Đã trả phòng",
    "Cancelled" => "Đã hủy"
];
?>
<div class="container py-4">
    <div class="text-center mb-4">
        <h1 class="h3 mb-2"><?php echo $data['title']; ?></h1>
        <p class="text-muted mb-0"><?php echo $data['description']; ?></p>  
    </div>
    
    <?php require_once __DIR__ . '/../../components/filter.php'; ?>
    <?php require_once __DIR__ . '/../../components/toolbar.php'; ?>
    <?php require_once __DIR__ . '/../../components/table.php'; ?>
    <?php require_once __DIR__ . '/../../components/pagination.php'; ?>
    <?php require_once __DIR__ . '/popup.php'; ?>
    <?php require_once __DIR__ . '/detail.php'; ?>
    <?php require_once __DIR__ . '/checkin-modal.php'; ?>
</div>