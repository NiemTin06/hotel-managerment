<?php /** @var array $data */ ?>

<div class="container-fluid room-page">
    <?php
    $pageEyebrow = 'XEM PHÒNG';
    require __DIR__ . '/../../components/page-heading.php';
    ?>

    <?php require __DIR__ . '/../../components/filter.php'; ?>

    <div class="room-result-bar">
        <span id="room-result-count">
            Đang tải danh sách loại phòng...
        </span>
    </div>

    <div id="client-room-type-list" class="row g-4">
        <div class="col-12">
            <div class="alert alert-secondary text-center">Đang tải các loại phòng...</div>
        </div>
    </div>
</div>
