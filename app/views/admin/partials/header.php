<header style="background: #333; padding: 15px; color: white; display: flex; justify-content: space-between; align-items: center;">
    <div class="logo">
        <a href="<?php echo URLROOT; ?>/" style="color: white; text-decoration: none; font-weight: bold;">HOTEL MANAGER</a>
    </div>
    <nav style="display: flex; align-items: center;">

        <?php if (isLoggedIn()): ?>
            <span style="margin-left: 15px;">
                👤 <?php echo htmlspecialchars(currentUsername()); ?>
                <small style="opacity: 0.7;">(<?php echo htmlspecialchars(currentUserRole()); ?>)</small>s
            </span>

          <a href="<?php echo URLROOT; ?>/admin/logout" 
   style="margin-left: 15px; background: #c0392b; color: white; text-decoration: none; padding: 6px 12px; border-radius: 4px;"
   onclick="return confirm('Bạn có chắc muốn đăng xuất?')">
    Đăng Xuất
</a>
        <?php else: ?>
            <a href="<?php echo URLROOT; ?>/admin/login" style="color: white; margin-left: 15px; text-decoration: none;">Đăng Nhập</a>
        <?php endif; ?>
    </nav>
</header>