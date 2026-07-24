<!-- app/views/admin/partials/sidebar.php -->
<aside id="main-sidebar" class="sidebar text-whited-flex flex-column">
    
    <!-- Nút Toggle đóng/mở -->
    <div class="d-flex align-items-center justify-content-center mb-3">
        <button id="sidebar-toggle" class="btn btn-outline-light btn-sm border-0">
            <span class="bi bi-list fs-4"> ☰  </span>
        </button>
    </div>

    <hr class="text-secondary mt-0">

    <ul class="nav nav-pills flex-column">
        <li class="nav-item">
            <a href="<?php echo URLROOT; ?>/admin/dashboard" class="nav-link d-flex align-items-center gap-3" title="Dashboard">
                <i class="bi bi-speedometer2"></i>
                <span class="link-text">Dashboard</span>
            </a>
        </li>

        <?php if (($_SESSION['user_role'] ?? null) === 'Admin'): ?>
            <li class="nav-item">
                <a href="<?php echo URLROOT; ?>/admin/users" class="nav-link  d-flex align-items-center gap-3" title="Danh sách người dùng">
                    <i class="bi bi-people"></i>
                    <span class="link-text">Danh sách người dùng</span>
                </a>
            </li>
        <?php endif; ?>

        <li class="nav-item">
            <a href="<?php echo URLROOT; ?>/admin/rooms" class="nav-link  d-flex align-items-center gap-3" title="Danh sách phòng">
                <i class="bi bi-door-closed"></i>
                <span class="link-text">Danh sách phòng</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?php echo URLROOT; ?>/admin/rooms-type" class="nav-link d-flex align-items-center gap-3" title="Danh sách loại phòng">
                <i class="bi bi-grid-3x3-gap"></i>
                <span class="link-text">Danh sách loại phòng</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?php echo URLROOT; ?>/admin/customers" class="nav-link d-flex align-items-center gap-3" title="Danh sách khách hàng">
                <i class="bi bi-person-lines-fill"></i>
                <span class="link-text">Danh sách khách hàng</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?php echo URLROOT; ?>/admin/bookings" class="nav-link  d-flex align-items-center gap-3" title="Danh sách đặt phòng">
                <i class="bi bi-journal-check"></i>
                <span class="link-text">Danh sách đặt phòng</span>
            </a>
        </li>
    </ul>
</aside>

<!-- Thêm Script xử lý Toggle Sidebar ở đây -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const toggleBtn = document.getElementById("sidebar-toggle");
            const sidebar = document.getElementById("main-sidebar");

            if (toggleBtn && sidebar) {
                toggleBtn.addEventListener("click", function () {
                    sidebar.classList.toggle("collapsed");
                });
            }
        });
    </script>