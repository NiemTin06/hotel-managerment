<?php
/** @var array $data */

    $tableHeader = '
    <tr>
        <th><input type="checkbox" checkbox-multi></th>
        <th>STT</th>
        <th>Tài khoản</th>
        <th>Email</th>
        <th>Số điện thoại</th>
        <th>Quyền hạn</th>
        <th>Trạng thái</th>
        <th class="text-center">Hành động</th>
    </tr>
    ';
    $tbodyId = "user-list";
    $object = "Danh sách tài khoản";
    $status = [
        [
            "label" => "Hoạt động (Active)",
            "value" => "Active"
        ],
        [
            "label" => "Khóa tài khoản (Inactive)",
            "value" => "Inactive"
        ],
        ["label" => "Xóa tài khoản", "value" => "Delete"]
    ];
    $btnCreateName = "Thêm nhân viên mới";
    $sortOptions = [
        "" => "Mặc định",
        "username_asc" => "Tên tài khoản A-Z",
        "username_desc" => "Tên tài khoản Z-A",
        "created_desc" => "Mới tạo nhất",
    ];

    $statusOptions = [
        "" => "Tất cả trạng thái",
        "Active"   => "Hoạt động (Active)",
        "Inactive" => "Khóa (Inactive)"
    ];
?>
<div class="container py-4">
    <div class="text-center mb-4">
        <h1 class="h3 mb-2"><?php echo $data['title']; ?></h1>
        <p class="text-muted mb-0"><?php echo $data['description'] ?? 'Quản lý thông tin và phân quyền nhân viên'; ?></p>  
    </div>
    
    <?php require_once __DIR__ .  '/../../components/filter.php';  ?>
    <?php require_once __DIR__ .  '/../../components/toolbar.php'; ?>
    <?php require_once __DIR__ .  '/../../components/table.php'; ?>
    <?php require_once __DIR__ .  '/../../components/pagination.php'; ?>
    
    <?php require_once __DIR__ .  '/popup.php'; ?>
    <?php require_once __DIR__ .  '/detail.php'; ?>
</div>