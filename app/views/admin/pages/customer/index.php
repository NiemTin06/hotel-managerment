<?php
/** @var array $data */
$tableHeader = '
<tr>
    <th><input type="checkbox" checkbox-multi></th>
    <th>STT</th>
    <th>Họ và tên</th>
    <th>Số điện thoại</th>
    <th>CCCD / Passport</th>
    <th>Hồ sơ hoạt động</th>
    <th>Hành động</th>
</tr>
';
$tbodyId = "customer-list";
$object = "Khách hàng";
 $status = [
        ["label" => "Xóa khách hàng", "value" => "Delete"]
    ];
    $btnCreateName = "Thêm khách hàng mới";
$sortOptions = [
    "" => "Mặc định (Mới nhất)",
    "spent_desc" => "Chi tiêu nhiều nhất (VIP)",
    "booking_desc" => "Đặt phòng nhiều nhất",
    "name_asc" => "Tên A-Z"
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
    <?php require_once __DIR__ .  '/popup.php'?>
    <?php require_once __DIR__ .  '/detail.php'?>
</div>

