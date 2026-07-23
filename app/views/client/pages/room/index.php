<?php /** @var array $data */ ?>

<div class="container-fluid room-page">
    <div class="room-page-title">
        <div>
            <h1><?php echo htmlspecialchars($data['title']); ?></h1>
            <p><?php echo htmlspecialchars($data['description']); ?></p>
        </div>
    </div>

    <?php require_once __DIR__ . '/../../components/filter.php'; ?>

    <div class="room-result-bar">
        <span id="room-result-count">
            Đang tải danh sách loại phòng...
        </span>
    </div>

    <div id="client-room-type-list" class="row g-4"></div>
</div>
