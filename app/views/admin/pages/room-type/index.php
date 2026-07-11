<?php
/** @var array $data */

     $tableHeader = '
    <tr>
        <th><input type="checkbox" checkbox-multi></th>
        <th>STT</th>
        <th>Tên loại phòng</th>
        <th>Ảnh</th>
        <th>Giá phòng</th>
        <th>Giảm giá</th>
        <th>Mô tả</th>
        <th>Hành động</th>
    </tr>
    ';
    $tbodyId = "room-type-list";
    $object = "Danh mục phòng";
    $status = [
        ["label" => "Hoạt động", "value" => "Active"],
        ["label" => "Không hoạt động", "value" => "Inactive"],
    ];
?>
<div class="container py-4">
    <div class="text-center mb-4">
        <h1 class="h3 mb-2"><?php echo $data['title']; ?></h1>
        <p class="text-muted mb-0"><?php echo $data['description']; ?></p>  
    </div>
    <?php require_once __DIR__ .  '/../../components/filter.php';  ?>
    <?php require_once __DIR__ .  '/../../components/toolbar.php'; ?>
    <?php require_once __DIR__ .  '/../../components/table.php'; ?>
    <?php require_once __DIR__ .  '/create.php'?>
    
</div>
