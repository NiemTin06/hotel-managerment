<div class="auth-page">
    <section class="auth-card auth-register-card">
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

        <form action="<?php echo URLROOT; ?>/register" method="post">
            <div class="auth-input-group">
                <i class="bi bi-person-fill"></i>

                <input
                    type="text"
                    name="fullname"
                    value="<?php echo htmlspecialchars($data['old']['fullname'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                    maxlength="100"
                    autocomplete="name"
                    placeholder="Họ và tên"
                    required
                >
            </div>

            <div class="auth-input-group">
                <i class="bi bi-person-badge-fill"></i>

                <input
                    type="text"
                    name="username"
                    value="<?php echo htmlspecialchars($data['old']['username'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                    minlength="4"
                    maxlength="50"
                    autocomplete="username"
                    placeholder="Tên tài khoản"
                    required
                >
            </div>

            <div class="auth-input-group">
                <i class="bi bi-envelope-fill"></i>

                <input
                    type="email"
                    name="email"
                    value="<?php echo htmlspecialchars($data['old']['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                    maxlength="100"
                    autocomplete="email"
                    placeholder="Email"
                    required
                >
            </div>

            <div class="auth-input-group">
                <i class="bi bi-telephone-fill"></i>

                <input
                    type="tel"
                    name="phone"
                    value="<?php echo htmlspecialchars($data['old']['phone'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                    inputmode="numeric"
                    pattern="0[0-9]{9}"
                    maxlength="10"
                    autocomplete="tel"
                    placeholder="Số điện thoại"
                    required
                >
            </div>

            <div class="auth-input-group">
                <i class="bi bi-lock-fill"></i>

                <input
                    type="password"
                    name="password"
                    minlength="6"
                    autocomplete="new-password"
                    placeholder="Mật khẩu"
                    required
                >
            </div>

            <div class="auth-input-group">
                <i class="bi bi-lock-fill"></i>

                <input
                    type="password"
                    name="password_confirm"
                    minlength="6"
                    autocomplete="new-password"
                    placeholder="Xác nhận mật khẩu"
                    required
                >
            </div>

            <button type="submit" class="auth-submit">Đăng ký</button>
        </form>

        <p class="auth-switch">
            Đã có tài khoản?
            <a href="<?php echo URLROOT; ?>/login">Đăng nhập</a>
        </p>
    </section>
</div>