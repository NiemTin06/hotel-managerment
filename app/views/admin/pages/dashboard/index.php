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
</div>
