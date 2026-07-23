<?php $currentLink = $data['link'] ?? ''; ?>

<div class="sidebar">
    <h2>Menu</h2>

    <ul>
        <li>
            <a href="<?php echo URLROOT; ?>/" class="<?php echo $currentLink === 'home' ? 'active' : ''; ?>">Trang chủ</a>
        </li>

        <li>
            <a href="<?php echo URLROOT; ?>/rooms" class="<?php echo $currentLink === 'rooms' ? 'active' : ''; ?>">Danh sách phòng</a>
        </li>

        <li>
            <a href="<?php echo URLROOT; ?>/bookings" class="<?php echo $currentLink === 'bookings' ? 'active' : ''; ?>">Đặt phòng</a>
        </li>

        <li>
            <a href="<?php echo URLROOT; ?>/booking-lookup" class="<?php echo $currentLink === 'booking-lookup' ? 'active' : ''; ?>">Tra cứu đơn</a>
        </li>
    </ul>
</div>
