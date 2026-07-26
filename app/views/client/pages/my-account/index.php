<?php
/** @var array $data */

$account = $data['account'] ?? [];
$bookings = $data['bookings'] ?? [];
$old = $data['old'] ?? [];

$fullname = $old['fullname'] ?? ($account['CUSTOMER_FULLNAME'] ?? '');
$phone = $old['phone'] ?? ($account['CUSTOMER_PHONE'] ?? $account['USER_PHONE'] ?? '');

$statusNames = [
    'Pending' => 'Chờ xác nhận',
    'Confirmed' => 'Đã xác nhận',
    'CheckedIn' => 'Đã nhận phòng',
    'CheckedOut' => 'Đã trả phòng',
    'Cancelled' => 'Đã hủy'
];

$statusClasses = [
    'Pending' => 'text-bg-warning',
    'Confirmed' => 'text-bg-primary',
    'CheckedIn' => 'text-bg-success',
    'CheckedOut' => 'text-bg-secondary',
    'Cancelled' => 'text-bg-danger'
];
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

            <form action="<?php echo URLROOT; ?>/my-account/update" method="post">
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
            <?php if (empty($bookings)): ?>
                <div class="alert alert-secondary text-center mb-0">
                    Bạn chưa có đơn đặt phòng nào.
                </div>
            <?php else: ?>
                <div class="table-responsive">
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

                        <tbody>
                            <?php foreach ($bookings as $booking): ?>
                                <?php
                                $status = $booking['BOOKING_STATUS'] ?? '';
                                $statusName = $statusNames[$status] ?? $status;
                                $statusClass = $statusClasses[$status] ?? 'text-bg-secondary';
                                ?>

                                <tr>
                                    <td>#<?php echo (int)$booking['BOOKING_ID']; ?></td>

                                    <td>
                                        <?php echo htmlspecialchars($booking['ROOMTYPE_NAME'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($booking['ROOM_NUMBER'] ?? 'Chưa xếp phòng', ENT_QUOTES, 'UTF-8'); ?>
                                    </td>

                                    <td>
                                        <?php echo date('d/m/Y', strtotime($booking['BOOKING_CHECKIN'])); ?>
                                    </td>

                                    <td>
                                        <?php echo date('d/m/Y', strtotime($booking['BOOKING_CHECKOUT'])); ?>
                                    </td>

                                    <td class="text-danger fw-bold">
                                        <?php echo number_format((float)$booking['BOOKING_TOTAL_PRICE'], 0, ',', '.'); ?> đ
                                    </td>

                                    <td>
                                        <span class="badge <?php echo $statusClass; ?>">
                                            <?php echo htmlspecialchars($statusName, ENT_QUOTES, 'UTF-8'); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </section>
</div>
