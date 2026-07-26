<div class="auth-page">
    <section class="auth-card auth-register-card">
        <div class="auth-brand">
            <img src="<?php echo URLROOT; ?>/public/client/img/black-cat-logo.png" alt="Neko Hotel">

            <div>
                <a href="<?php echo URLROOT; ?>/">Neko Hotel</a>
                <p>Hệ thống đặt phòng khách sạn</p>
            </div>
        </div>

        <form id="registerForm" action="<?php echo URLROOT; ?>/register" method="post">
            <div class="auth-input-group">
                <i class="bi bi-person-fill"></i>

                <input
                    type="text"
                    name="fullname"
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
                    inputmode="numeric"
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