<!-- app/views/client/partials/sidebar.php -->
<aside id="main-sidebar" class="sidebar collapsed text-white d-flex flex-column">

    <!-- Nút đóng/mở sidebar -->
    <div class="d-flex align-items-center justify-content-center mb-3">
        <button id="sidebar-toggle" type="button" class="btn btn-outline-light btn-sm border-0" title="Đóng/mở menu">
                <span class="fs-4">☰</span>
                <span class="link-text sidebar-menu-text">Menu</span>
        </button>
    </div>

    <hr class="text-secondary mt-0">

    <ul class="nav nav-pills flex-column">
        <li class="nav-item">
            <a href="<?php echo URLROOT; ?>/" class="nav-link d-flex align-items-center gap-3" title="Trang chủ">
                <i class="bi bi-house-door"></i>
                <span class="link-text">Trang chủ</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?php echo URLROOT; ?>/rooms" class="nav-link d-flex align-items-center gap-3" title="Xem phòng">
                <i class="bi bi-door-closed"></i>
                <span class="link-text">Xem phòng</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?php echo URLROOT; ?>/bookings" class="nav-link d-flex align-items-center gap-3" title="Đặt phòng">
                <i class="bi bi-calendar-check"></i>
                <span class="link-text">Đặt phòng</span>
            </a>
        </li>

        <?php if (($_SESSION['user_role'] ?? null) === 'Customer'): ?>
            <li class="nav-item">
                <a href="<?php echo URLROOT; ?>/my-account" class="nav-link d-flex align-items-center gap-3" title="Tài khoản của tôi">
                    <i class="bi bi-person-circle"></i>
                    <span class="link-text">Tài khoản của tôi</span>
                </a>
            </li>
        <?php endif; ?>
    </ul>
</aside>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleButton = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('main-sidebar');

    if (!toggleButton || !sidebar) {
        return;
    }

    toggleButton.addEventListener('click', function () {
        sidebar.classList.toggle('collapsed');
    });
});
</script>
