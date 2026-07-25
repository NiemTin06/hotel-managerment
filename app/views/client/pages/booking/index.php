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
            $sectionText = 'Nhập đầy đủ thông tin của người đặt phòng.';
            require __DIR__ . '/../../components/section-heading.php';
            ?>

            <?php if (!empty($_SESSION['customer_id'])): ?>
                <p class="guest-note">Thông tin đã được điền từ tài khoản. Bạn vẫn có thể chỉnh sửa trước khi gửi đơn.</p>
            <?php endif; ?>

            <div class="booking-inline-field">
                <label for="customer-fullname">Họ tên <span class="required-mark">*</span></label>
                <input type="text" id="customer-fullname" name="customer-fullname" class="form-control" maxlength="100" value="<?php echo htmlspecialchars($customer['fullname'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"  placeholder="Nhập họ tên người đặt" required>
            </div>

            <div class="booking-inline-field">
                <label for="customer-phone">Số điện thoại <span class="required-mark">*</span></label>
                <input type="tel" id="customer-phone" name="customer-phone" class="form-control" inputmode="numeric" pattern="0[0-9]{9}" maxlength="10" value="<?php echo htmlspecialchars($customer['phone'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="Ví dụ: 0373737650" required>
            </div>

            <div class="booking-inline-field">
                <label for="customer-cccd">CCCD <span class="required-mark">*</span></label>
                <input type="text" id="customer-cccd" name="customer-cccd" class="form-control" inputmode="numeric" pattern="[0-9]{12}" minlength="12" maxlength="12" value="<?php echo htmlspecialchars($customer['cccd'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="Nhập đúng 12 chữ số" required>
            </div>

            <div class="booking-inline-field">
                <label for="customer-email">Email</label>
                <input type="email" id="customer-email" name="customer-email" class="form-control" maxlength="100" value="<?php echo htmlspecialchars($customer['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </div>

            <div class="booking-stay-row">
                <div><span>Ngày nhận:</span><strong id="booking-checkin"></strong></div>
                <div><span>Ngày trả:</span><strong id="booking-checkout"></strong></div>
                <div><span>Thời gian:</span><strong id="booking-night-count"></strong></div>
            </div>

            <div class="booking-note-field">
                <label for="booking-note">Ghi chú</label>
                <textarea id="booking-note" name="booking-note" class="form-control" rows="3" placeholder="Không bắt buộc"></textarea>
            </div>

            <p class="guest-note">Vui lòng nhập chính xác thông tin! Khách sạn sẽ dùng thông tin trên để liên hệ xác nhận đơn.</p>

            <div class="total-box">
                <div><span>Tổng tiền:</span><strong id="total-price">0 đ</strong></div>
                <button id="btn-booking" type="submit" class="btn btn-success" disabled>Gửi yêu cầu đặt phòng</button>
            </div>
        </section>
    </form>
</div>
