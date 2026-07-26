<div class="auth-page">
    <section class="auth-card auth-login-card">
        <div class="auth-brand">
            <img src="<?php echo URLROOT; ?>/public/client/img/black-cat-logo.png" alt="Neko Hotel">

            <div>
                <a href="<?php echo URLROOT; ?>/">Neko Hotel</a>
                <p>Hệ thống đặt phòng khách sạn</p>
            </div>
        </div>

        <?php if (!empty($data['error'])): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($data['error'], ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($data['success'])): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($data['success'], ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo URLROOT; ?>/login" method="post">
            <div class="auth-input-group">
                <i class="bi bi-person-fill"></i>

                <input
                    type="text"
                    name="userInput"
                    value="<?php echo htmlspecialchars($data['old']['userInput'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                    autocomplete="username"
                    placeholder="Tên tài khoản hoặc email"
                    required
                >
            </div>

            <div class="auth-input-group">
                <i class="bi bi-lock-fill"></i>

                <input
                    type="password"
                    name="pwd"
                    autocomplete="current-password"
                    placeholder="Mật khẩu"
                    required
                >
            </div>

            <button type="submit" class="auth-submit">Đăng nhập</button>
        </form>
        <p class="auth-switch">
            Chưa có tài khoản?
            <a href="<?php echo URLROOT; ?>/register">Đăng ký</a>
        </p>
    </section>
</div>
