<?php
/** @var array $data */

$account = $data['account'] ?? [];
$fullname = $account['CUSTOMER_FULLNAME'] ?? '';
$phone = $account['CUSTOMER_PHONE'] ?? ($account['USER_PHONE'] ?? '');
?>

<div class="my-account-page">
    <?php
    $pageEyebrow = 'TÀI KHOẢN KHÁCH HÀNG';
    require __DIR__ . '/../../components/page-heading.php';
    ?>

    <section class="card mb-4">
        <div class="card-header fw-bold">
            <i class="bi bi-person-circle me-1"></i> Thông tin tài khoản
        </div>

        <div class="card-body">
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

            <div id="account-message" class="d-none" role="alert"></div>

            <form id="accountForm" action="<?php echo URLROOT; ?>/my-account/update" method="post">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="account-fullname" class="form-label">Họ và tên</label>
                        <input
                            type="text"
                            id="account-fullname"
                            name="fullname"
                            class="form-control"
                            value="<?php echo htmlspecialchars($fullname, ENT_QUOTES, 'UTF-8'); ?>"
                            required
                        >

                    </div>

                    <div class="col-md-6">
                        <label for="account-username" class="form-label">Tên đăng nhập</label>
                        <input
                            type="text"
                            id="account-username"
                            class="form-control"
                            value="<?php echo htmlspecialchars($account['USER_USERNAME'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                            title="Tên đăng nhập không thể thay đổi"
                            readonly
                        >
                    </div>

                    <div class="col-md-6">
                        <label for="account-phone" class="form-label">Số điện thoại</label>
                        <input
                            type="tel"
                            id="account-phone"
                            name="phone"
                            class="form-control"
                            inputmode="numeric"
                            value="<?php echo htmlspecialchars($phone, ENT_QUOTES, 'UTF-8'); ?>"
                            required
                        >
                    </div>

                    <div class="col-md-6">
                        <label for="account-email" class="form-label">Email đăng nhập</label>
                        <input
                            type="email"
                            id="account-email"
                            class="form-control"
                            value="<?php echo htmlspecialchars($account['USER_EMAIL'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                            title="Email đăng nhập không thể thay đổi"
                            readonly
                        >
                    </div>

                    <div class="col-md-6">
                        <label for="new-password" class="form-label">Mật khẩu mới</label>
                        <input
                            type="password"
                            id="new-password"
                            name="new-password"
                            class="form-control"
                            placeholder="Để trống nếu không đổi mật khẩu"
                            autocomplete="new-password"
                        >
                    </div>

                    <div class="col-md-6 d-flex align-items-end justify-content-md-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Cập nhật thông tin
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <section class="card">
        <div class="card-header fw-bold">
            <i class="bi bi-clock-history me-1"></i> Lịch sử đặt phòng
        </div>

        <div class="card-body">
            <div id="booking-empty" class="alert alert-secondary text-center mb-0 d-none">
                Bạn chưa có đơn đặt phòng nào.
            </div>

            <div id="booking-table" class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Mã đơn</th>
                            <th>Loại phòng</th>
                            <th>Số phòng</th>
                            <th>Ngày nhận</th>
                            <th>Ngày trả</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>

                    <tbody id="booking-history">
                        <tr>
                            <td colspan="7" class="text-center text-muted">Đang tải lịch sử đặt phòng...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
