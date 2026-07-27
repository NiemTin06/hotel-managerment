<?php
/** @var array $data */
?>

<div class="dashboard container py-4">
    <div class="text-center mb-4">
        <h1 class="inner-title mb-2"><?php echo $data['title']; ?></h1>
        <p class="text-muted mb-2"><?php echo $data['description']; ?></p>  
        <h2>Chào 
            <?php 
            $hour  = (int)date('H');         // Định dạng 24h
            // 3. Xác định buổi trong ngày
            $session = '';

            if ($hour >= 5 && $hour < 11) {
                $buoi = 'buổi sáng';
            } elseif ($hour >= 11 && $hour < 13) {
                $buoi = 'buổi trưa';
            } elseif ($hour >= 13 && $hour < 18) {
                $buoi = 'buổi chiều';
            } else {
                // Từ 18h tối đến 4h59 sáng hôm sau
                $buoi = 'buổi tối'; 
            }
            echo $buoi . ", ";
            echo htmlspecialchars(currentUsername()); 
            ?>
        </h2>
    </div>
    <!-- Thống kê -->
    <div class="row g-4 mb-5">

        <div class="col-lg-3 col-md-6">
            <div class="card shadow border-0">
                <div class="card-body text-center">
                    <h2 class="text-primary">120</h2>
                    <p class="mb-0">Phòng</p>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card shadow border-0">
                <div class="card-body text-center">
                    <h2 class="text-success">85</h2>
                    <p class="mb-0">Đang sử dụng</p>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card shadow border-0">
                <div class="card-body text-center">
                    <h2 class="text-warning">20</h2>
                    <p class="mb-0">Đã đặt</p>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card shadow border-0">
                <div class="card-body text-center">
                    <h2 class="text-danger">15</h2>
                    <p class="mb-0">Bảo trì</p>
                </div>
            </div>
        </div>

    </div>

    <!-- Biểu đồ -->
    <div class="row">

        <div class="col-lg-7 mb-4">
            <div class="card shadow border-0">
                <div class="card-header">
                    Tỷ lệ trạng thái phòng
                </div>

                <div class="card-body">
                    <canvas id="roomChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-5 mb-4">
            <div class="card shadow border-0">
                <div class="card-header">
                    Đặt phòng theo tháng
                </div>

                <div class="card-body">
                    <canvas id="bookingChart"></canvas>
                </div>
            </div>
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>