<header class="main-header">
    <div class="logo">
        <a href="<?php echo URLROOT; ?>/">HOTEL MANAGER</a>
    </div>
    
    <nav class="main-nav">
        <?php if (isLoggedIn()): ?>
            <span class="user-info">
                👤 <?php echo htmlspecialchars(currentUsername()); ?>
                <small class="user-role">(<?php echo htmlspecialchars(currentUserRole()); ?>)</small>
            </span>

            <a href="<?php echo URLROOT; ?>/admin/logout" 
                class="btn-logout"
                tittle="Đăng Xuất"
                onclick="return confirm('Bạn có chắc muốn đăng xuất?')">
                <i class="bi bi-box-arrow-right"></i>
                <span class="logout-text">Đăng xuất</span>
            </a>

        <?php else: ?>
            <a href="<?php echo URLROOT; ?>/admin/login" class="nav-link">Đăng Nhập</a>
        <?php endif; ?>
    </nav>
</header>