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
        <th>Trạng thái </th>
        <th>Hành động</th>
    </tr>
    ';
    $tbodyId = "room-type-list";
    $object = "Danh mục phòng";
    $status = [
        ["label" => "Hoạt động", "value" => "Active"],
        ["label" => "Không hoạt động", "value" => "Inactive"],
        ["label" => "Xóa loại phòng", "value" => "Delete"]
    ];
    $sortOptions = [
        "" => "Mặc định",
        "price_asc" => "Giá tăng dần",
        "price_desc" => "Giá giảm dần",
        "name_asc" => "loại phòng A-Z",
        "name_desc" => "loại phòng Z-A",
    ];

    $statusOptions = [
        "" => "Tất cả",
        "Active" => "Đang hoạt động",
        "Inactive" => "Ngừng hoạt động"
    ];

    $maxGuests = [
        [
            "value" => "",
            "label" => "Mặc định"
        ],
        [
            "value" => 1,
            "label" => "1 người"
        ],
        [
            "value" => 2,
            "label" => "2 người"
        ],
        [
            "value" => 3,
            "label" => "3 người"
        ],
        [
            "value" => 4,
            "label" => "4 người"
        ],
        [
            "value" => 5,
            "label" => "5 người"
        ],
        [
            "value" => 6,
            "label" => "6 người"
        ],
    ];

    $bedTypes = [
        [
            "value" => "",
            "label" => "Mặc định"
        ],
        [
            "value" => "singleBed",
            "label" => "Giường đơn"
        ],
        [
            "value" => "doubleBed",
            "label" => "Giường đôi"
        ],
        [
            "value" => "queenBed",
            "label" => "Giường Queen"
        ],
        [
            "value" => "kingBed",
            "label" => "Giường King"
        ]
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
    <div id="pagination" class="pagination">
        
    </div>
    <?php require_once __DIR__ .  '/popup.php'?>
    <?php require_once __DIR__ .  '/detail.php'?>

    
</div>
