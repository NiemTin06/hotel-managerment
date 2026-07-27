<?php
$loggedInCustomer = !empty($_SESSION['user_id'])
    && ($_SESSION['user_role'] ?? '') === 'Customer';

$customerName = $_SESSION['customer_fullname']
    ?? $_SESSION['username']
    ?? '';
?>

<header class="client-header">
    <div class="client-logo">
        <img src="<?php echo URLROOT; ?>/public/client/img/cute_cat.png" alt="Neko Hotel" width=48 height=48>
        <a href="<?php echo URLROOT; ?>/">NEKO HOTEL</a>
    </div>

    <nav class="client-nav">
        <?php if ($loggedInCustomer): ?>
            <span class="client-user-name">
                <i class="bi bi-person-circle"></i>

                <span class="client-user-text">
                    <?php echo htmlspecialchars($customerName, ENT_QUOTES, 'UTF-8'); ?>
                </span>
            </span>

            <a
                href="<?php echo URLROOT; ?>/logout"
                class="btn btn-sm"
                title="Đăng xuất"
                onclick="return confirm('Bạn có chắc muốn đăng xuất?')"
            >
                <i class="bi bi-box-arrow-right"></i>
                <span class="client-logout-text">Đăng xuất</span>
            </a>
        <?php else: ?>
            <a href="<?php echo URLROOT; ?>/login">
                <i class="bi bi-box-arrow-in-right"></i>
                <span class="client-login-text">Đăng nhập</span>
            </a>

            <a href="<?php echo URLROOT; ?>/register">
                <i class="bi bi-person-plus"></i>
                <span class="client-register-text">Đăng ký</span>
            </a>
        <?php endif; ?>
    </nav>
</header>