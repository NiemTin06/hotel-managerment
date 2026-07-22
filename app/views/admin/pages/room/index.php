<?php
/** @var array $data */

     $tableHeader = '
    <tr>
        <th><input type="checkbox" checkbox-multi></th>
        <th>STT</th>
        <th>Tên phòng</th>
        <th>Mô tả</th>
        <th>Trạng thái </th>
        <th>Hành động</th>
    </tr>
    ';
    $tbodyId = "room-list";
    $object = "Danh sách phòng";
    $status = [
        [
            "label" => "Phòng trống",
            "value" => "Available"
        ],
        [
            "label" => "Đã đặt",
            "value" => "Booked"
        ],
        [
            "label" => "Đang sử dụng",
            "value" => "Occupied"
        ],
        [
            "label" => "Bảo trì",
            "value" => "Maintenance"
        ],
        ["label" => "Xóa phòng", "value" => "Delete"]
    ];
    $btnCreateName = "Thêm phòng mới";
    $sortOptions = [
        "" => "Mặc định",
        "name_asc" => "số phòng A-Z",
        "name_desc" => "số phòng Z-A",
    ];

    $statusOptions = [
        "" => "Tất cả",
        "Available"   => "Phòng trống",
        "Booked"      => "Đã đặt",
        "Occupied"    => "Đang sử dụng",
        "Maintenance" => "Bảo trì"
    ];
    $filterTypeRoom = true;
?>
<div class="container py-4">
    <div class="text-center mb-4">
        <h1 class="h3 mb-2"><?php echo $data['title']; ?></h1>
        <p class="text-muted mb-0"><?php echo $data['description']; ?></p>  
    </div>
    <?php require_once __DIR__ .  '/../../components/filter.php';  ?>
    <?php require_once __DIR__ .  '/../../components/toolbar.php'; ?>
    <?php require_once __DIR__ .  '/../../components/table.php'; ?>
    <?php require_once __DIR__ .  '/../../components/pagination.php' ?>
    <?php require_once __DIR__ .  '/popup.php'?>
    <?php require_once __DIR__ .  '/detail.php'?>

    
</div>
