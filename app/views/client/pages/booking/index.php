<?php
/** @var array $data */
$customer = $data['customer'] ?? [];
?>

<div class="booking-page">
    <?php
    $pageEyebrow = 'ĐẶT PHÒNG';
    $pageActionUrl = URLROOT . '/rooms';
    $pageActionText = 'Chọn lại phòng';
    $pageActionClass = 'btn btn-outline-primary';
    require __DIR__ . '/../../components/page-heading.php';
    ?>

    <div id="booking-message"></div>

    <form id="booking-form">
        <input type="hidden" id="booking-room-type-id" name="room-type-id">
        <input type="hidden" id="booking-checkin-input" name="booking-checkin">
        <input type="hidden" id="booking-checkout-input" name="booking-checkout">

        <section class="booking-box">
            <?php
            $sectionTitle = 'Loại phòng đã chọn';
            $sectionText = 'Kiểm tra lại loại phòng và giá phòng trước khi đặt.';
            require __DIR__ . '/../../components/section-heading.php';
            ?>

            <div id="selected-room-list"><div class="empty-text">Đang tải thông tin loại phòng...</div></div>
        </section>

        <section class="booking-box">
            <?php
            $sectionTitle = 'Thông tin đặt phòng';
            $sectionText = 'Thông tin khách hàng được lấy từ tài khoản đang đăng nhập.';
            require __DIR__ . '/../../components/section-heading.php';
            ?>

            <p class="guest-note">
                Họ tên, số điện thoại và email không thể thay đổi trong lúc đặt phòng.
                Muốn chỉnh sửa thông tin, vui lòng liên hệ quản trị viên.
            </p>

            <div class="booking-inline-field">
                <label for="customer-fullname">Họ tên</label>
                <input
                    type="text"
                    id="customer-fullname"
                    class="form-control"
                    value="<?php echo htmlspecialchars($customer['fullname'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                    readonly
                >
            </div>

            <div class="booking-inline-field">
                <label for="customer-phone">Số điện thoại</label>
                <input
                    type="text"
                    id="customer-phone"
                    class="form-control"
                    value="<?php echo htmlspecialchars($customer['phone'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                    readonly
                >
            </div>

            <div class="booking-inline-field">
                <label for="customer-email">Email</label>
                <input
                    type="text"
                    id="customer-email"
                    class="form-control"
                    value="<?php echo htmlspecialchars($customer['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                    readonly
                >
            </div>

            <div class="booking-stay-row">
                <div>
                    <span>Ngày nhận:</span>
                    <strong id="booking-checkin"></strong>
                </div>

                <div>
                    <span>Ngày trả:</span>
                    <strong id="booking-checkout"></strong>
                </div>

                <div>
                    <span>Thời gian:</span>
                    <strong id="booking-night-count"></strong>
                </div>
            </div>

            <div class="booking-note-field">
                <label for="booking-note">Ghi chú</label>
                <textarea
                    id="booking-note"
                    name="booking-note"
                    class="form-control"
                    rows="3"
                    placeholder="Không bắt buộc"
                ></textarea>
            </div>

            <div class="total-box">
                <div>
                    <span>Tổng tiền:</span>
                    <strong id="total-price">0 đ</strong>
                </div>

                <button
                    id="btn-booking"
                    type="submit"
                    class="btn btn-success"
                    disabled
                >
                    Gửi yêu cầu đặt phòng
                </button>
            </div>
        </section>
    </form>
</div>
